import * as THREE from "./vendor/three.module.min.js";

const DEFAULT_DURATION = 5300;
const DEFAULT_EXCLUDE_SELECTOR = [
  "a",
  "button",
  "code",
  "pre",
  "kbd",
  "samp",
  "input",
  "textarea",
  "select",
  "option",
  "summary",
  "[contenteditable]:not([contenteditable='false'])",
  "[aria-hidden='true']",
  "[inert]",
].join(", ");
const DEFAULT_FREQUENCY = Object.freeze({ min: 25000, max: 39000 });
const DEFAULT_SELECTOR = "[data-document-looks-back]";
const GLYPH_PATTERN = /[abdegopq0689]/gi;
const mountedDocuments = new WeakSet();

const vertexShader = `
  varying vec2 vUv;

  void main() {
    vUv = uv;
    gl_Position = vec4(position.xy, 0.0, 1.0);
  }
`;

const fragmentShader = `
  precision highp float;

  uniform sampler2D uSilhouette;
  uniform vec2 uResolution;
  uniform float uEyeAngle;
  uniform vec2 uEyeCenter;
  uniform vec2 uSilhouetteRadius;
  uniform vec2 uGaze;
  uniform vec3 uInk;
  uniform float uGrowth;
  uniform float uEyeOpen;
  varying vec2 vUv;

  float ellipse(vec2 point, vec2 center, vec2 radius) {
    return length((point - center) / radius) - 1.0;
  }

  float coverage(float distanceToEdge) {
    float antialias = max(fwidth(distanceToEdge), 0.012);
    return 1.0 - smoothstep(-antialias, antialias, distanceToEdge);
  }

  void main() {
    vec2 point = vec2(vUv.x * uResolution.x, (1.0 - vUv.y) * uResolution.y);
    vec4 silhouette = texture2D(uSilhouette, vUv);
    float distanceFromPerimeter = silhouette.r;
    float insideOuterPerimeter = silhouette.g;
    float sourceInk = silhouette.b;
    float fillWidth = max(fwidth(distanceFromPerimeter) * 1.5, 0.018);
    float fillProgress = min(1.05, uGrowth * 1.05);
    float fillReveal = 1.0 - smoothstep(
      fillProgress - fillWidth,
      fillProgress + fillWidth,
      distanceFromPerimeter
    );
    float addedInk = insideOuterPerimeter * fillReveal * smoothstep(0.0, 0.035, uGrowth);
    float exposedFill = addedInk * (1.0 - sourceInk);

    vec2 eyeRadius = vec2(
      max(1.25, min(uSilhouetteRadius.x * 0.48, uSilhouetteRadius.y * 0.68)),
      max(0.72, min(uSilhouetteRadius.y * 0.28, uSilhouetteRadius.x * 0.34)) * uEyeOpen
    );
    vec2 eyeDelta = point - uEyeCenter;
    float eyeCosine = cos(uEyeAngle);
    float eyeSine = sin(uEyeAngle);
    vec2 eyeLocal = vec2(
      eyeCosine * eyeDelta.x + eyeSine * eyeDelta.y,
      -eyeSine * eyeDelta.x + eyeCosine * eyeDelta.y
    );
    vec2 visibleEyeRadius = max(eyeRadius, vec2(0.15));
    float eye = coverage(ellipse(eyeLocal, vec2(0.0), visibleEyeRadius));
    float interiorClip = smoothstep(0.55, 0.85, insideOuterPerimeter);
    float sclera = eye * interiorClip * step(0.025, uEyeOpen);
    vec2 eyelidWidth = vec2(
      max(0.8, uSilhouetteRadius.x * 0.065),
      max(0.35, visibleEyeRadius.y * 0.25)
    );
    float eyeOutline = coverage(ellipse(eyeLocal, vec2(0.0), visibleEyeRadius + eyelidWidth));
    eyeOutline *= interiorClip * step(0.025, uEyeOpen);
    float eyelid = max(0.0, eyeOutline - sclera);
    vec2 pupilCenter = uEyeCenter + uGaze;
    float pupilRadius = max(0.44, eyeRadius.y * 0.52);
    float pupil = coverage(length(point - pupilCenter) / pupilRadius - 1.0) * sclera;
    vec2 glintCenter = pupilCenter - vec2(pupilRadius * 0.28);
    float glint = coverage(length(point - glintCenter) / max(0.16, pupilRadius * 0.2) - 1.0) * pupil;

    float inkLuminance = dot(uInk, vec3(0.2126, 0.7152, 0.0722));
    float lightInk = smoothstep(0.58, 0.78, inkLuminance);
    vec3 eyelidColor = mix(uInk, vec3(0.12, 0.035, 0.105), lightInk);
    float alpha = max(exposedFill, eyeOutline);
    vec3 color = uInk;
    color = mix(color, eyelidColor, eyelid);
    color = mix(color, vec3(0.985, 0.976, 0.945), sclera);
    color = mix(color, vec3(0.018, 0.014, 0.026), pupil);
    color = mix(color, vec3(1.0), glint);
    if (alpha < 0.004) discard;
    gl_FragColor = vec4(color, alpha);
  }
`;

function positiveNumber(value, name, minimum = 0) {
  if (!Number.isFinite(value) || value < minimum) {
    throw new TypeError(`${name} must be a finite number greater than or equal to ${minimum}.`);
  }
  return value;
}

function resolveMaxEyes(value) {
  if (!Number.isInteger(value) || value < 1 || value > 8) {
    throw new TypeError("maxEyes must be an integer from 1 through 8.");
  }
  return value;
}

function resolveFrequency(value) {
  if (value === false || value === null || value === 0) return null;
  if (Number.isFinite(value)) {
    const interval = positiveNumber(value, "frequency", 250);
    return Object.freeze({ min: interval, max: interval });
  }
  if (!value || typeof value !== "object") {
    throw new TypeError("frequency must be milliseconds, { min, max }, or 0 to disable automatic spawning.");
  }
  const min = positiveNumber(value.min, "frequency.min", 250);
  const max = positiveNumber(value.max, "frequency.max", min);
  return Object.freeze({ min, max });
}

function resolveRoot(value, document) {
  if (value === undefined) return document;
  if (value instanceof document.defaultView.Document || value instanceof document.defaultView.Element) return value;
  throw new TypeError("root must be a Document or Element.");
}

function resolveMount(value, document) {
  if (value === undefined) return document.body;
  if (value instanceof document.defaultView.Element) return value;
  throw new TypeError("mount must be an Element.");
}

function resolveSelector(value, root) {
  if (value === null || value === undefined) return DEFAULT_SELECTOR;
  if (typeof value !== "string" || !value.trim()) {
    throw new TypeError("selector must be a non-empty CSS selector or null.");
  }
  const selector = value.trim();
  try {
    root.querySelector(selector);
  } catch {
    throw new TypeError("selector must be a valid CSS selector.");
  }
  return selector;
}

function resolveExcludeSelector(value, root) {
  if (value === null || value === undefined) return DEFAULT_EXCLUDE_SELECTOR;
  if (value === false) return null;
  if (typeof value !== "string" || !value.trim()) {
    throw new TypeError("excludeSelector must be a non-empty CSS selector, false, or null.");
  }
  const selector = value.trim();
  try {
    root.querySelector(selector);
  } catch {
    throw new TypeError("excludeSelector must be a valid CSS selector.");
  }
  return selector;
}

function phasesFor(duration, reduced) {
  if (reduced) {
    return {
      closeDuration: 0,
      closeStarts: duration,
      drainDuration: 0,
      drainStarts: duration,
      eyeDuration: 0,
      eyeStarts: 0,
      fillDuration: 0,
      total: duration,
      watchStarts: 0,
    };
  }
  const scale = duration / DEFAULT_DURATION;
  return {
    closeDuration: 190 * scale,
    closeStarts: 4260 * scale,
    drainDuration: 680 * scale,
    drainStarts: 4540 * scale,
    eyeDuration: 280 * scale,
    eyeStarts: 880 * scale,
    fillDuration: 700 * scale,
    total: duration,
    watchStarts: 1160 * scale,
  };
}

function smooth(progress) {
  const value = Math.min(1, Math.max(0, progress));
  return value * value * (3 - 2 * value);
}

function fontShorthand(computed, scale) {
  const fontSize = Number.parseFloat(computed.fontSize) * scale;
  return `${computed.fontStyle} ${computed.fontWeight} ${fontSize}px ${computed.fontFamily}`;
}

function convexHull(points) {
  if (points.length < 3) return points;
  const sorted = [...points].sort((left, right) => left.x - right.x || left.y - right.y);
  const cross = (origin, first, second) =>
    (first.x - origin.x) * (second.y - origin.y) - (first.y - origin.y) * (second.x - origin.x);
  const lower = [];
  for (const point of sorted) {
    while (lower.length >= 2 && cross(lower.at(-2), lower.at(-1), point) <= 0) lower.pop();
    lower.push(point);
  }
  const upper = [];
  for (let index = sorted.length - 1; index >= 0; index -= 1) {
    const point = sorted[index];
    while (upper.length >= 2 && cross(upper.at(-2), upper.at(-1), point) <= 0) upper.pop();
    upper.push(point);
  }
  lower.pop();
  upper.pop();
  return lower.concat(upper);
}

function removeHorizontalTerminals(ink, width, height, radius) {
  const offsets = Array.from({ length: radius * 2 + 1 }, (_, index) => ({ x: 0, y: index - radius }));
  const eroded = new Uint8Array(ink.length);
  for (let index = 0; index < ink.length; index += 1) {
    if (!ink[index]) continue;
    const originX = index % width;
    const originY = Math.floor(index / width);
    let survives = true;
    for (const offset of offsets) {
      const x = originX + offset.x;
      const y = originY + offset.y;
      if (x < 0 || x >= width || y < 0 || y >= height || !ink[y * width + x]) {
        survives = false;
        break;
      }
    }
    if (survives) eroded[index] = 1;
  }

  const opened = new Uint8Array(ink.length);
  for (let index = 0; index < eroded.length; index += 1) {
    if (!eroded[index]) continue;
    const originX = index % width;
    const originY = Math.floor(index / width);
    for (const offset of offsets) {
      const x = originX + offset.x;
      const y = originY + offset.y;
      if (x >= 0 && x < width && y >= 0 && y < height) opened[y * width + x] = 1;
    }
  }
  return opened;
}

function dominantComponent(mask, width, height) {
  const visited = new Uint8Array(mask.length);
  let largest = [];
  for (let start = 0; start < mask.length; start += 1) {
    if (!mask[start] || visited[start]) continue;
    const component = [];
    const queue = [start];
    visited[start] = 1;
    for (let cursor = 0; cursor < queue.length; cursor += 1) {
      const index = queue[cursor];
      component.push(index);
      const x = index % width;
      const y = Math.floor(index / width);
      for (let offsetY = -1; offsetY <= 1; offsetY += 1) {
        for (let offsetX = -1; offsetX <= 1; offsetX += 1) {
          if (!offsetX && !offsetY) continue;
          const adjacentX = x + offsetX;
          const adjacentY = y + offsetY;
          if (adjacentX < 0 || adjacentX >= width || adjacentY < 0 || adjacentY >= height) continue;
          const adjacent = adjacentY * width + adjacentX;
          if (!mask[adjacent] || visited[adjacent]) continue;
          visited[adjacent] = 1;
          queue.push(adjacent);
        }
      }
    }
    if (component.length > largest.length) largest = component;
  }
  if (!largest.length) return null;

  let centerX = 0;
  let centerY = 0;
  for (const index of largest) {
    centerX += index % width;
    centerY += Math.floor(index / width);
  }
  centerX /= largest.length;
  centerY /= largest.length;

  let xx = 0;
  let xy = 0;
  let yy = 0;
  for (const index of largest) {
    const x = index % width - centerX;
    const y = Math.floor(index / width) - centerY;
    xx += x * x;
    xy += x * y;
    yy += y * y;
  }
  return { angle: 0.5 * Math.atan2(2 * xy, xx - yy) };
}

function analyzeSilhouette(imageData, width, height, scale, document) {
  const ink = new Uint8Array(width * height);
  let minX = width;
  let maxX = 0;
  let minY = height;
  let maxY = 0;
  for (let index = 0; index < ink.length; index += 1) {
    if (imageData.data[index * 4 + 3] < 72) continue;
    ink[index] = 1;
    const x = index % width;
    const y = Math.floor(index / width);
    minX = Math.min(minX, x);
    maxX = Math.max(maxX, x);
    minY = Math.min(minY, y);
    maxY = Math.max(maxY, y);
  }
  if (minX > maxX) return null;

  const glyphHeight = maxY - minY + 1;
  const openedInk = removeHorizontalTerminals(ink, width, height, Math.max(1, Math.round(glyphHeight * 0.035)));
  const dominant = dominantComponent(openedInk, width, height);
  const perimeterPoints = [];
  for (let index = 0; index < openedInk.length; index += 1) {
    if (!openedInk[index]) continue;
    const x = index % width;
    const y = Math.floor(index / width);
    const boundary = x === 0 || x === width - 1 || y === 0 || y === height - 1 ||
      !openedInk[index - 1] || !openedInk[index + 1] ||
      !openedInk[index - width] || !openedInk[index + width];
    if (boundary) perimeterPoints.push({ x, y });
  }
  if (perimeterPoints.length < 3) {
    for (let index = 0; index < ink.length; index += 1) {
      if (!ink[index]) continue;
      const x = index % width;
      const y = Math.floor(index / width);
      const boundary = x === 0 || x === width - 1 || y === 0 || y === height - 1 ||
        !ink[index - 1] || !ink[index + 1] || !ink[index - width] || !ink[index + width];
      if (boundary) perimeterPoints.push({ x, y });
    }
  }
  const hull = convexHull(perimeterPoints);
  if (hull.length < 3) return null;

  minX = Math.min(...hull.map(point => point.x));
  maxX = Math.max(...hull.map(point => point.x));
  minY = Math.min(...hull.map(point => point.y));
  maxY = Math.max(...hull.map(point => point.y));

  const hullCanvas = document.createElement("canvas");
  hullCanvas.width = width;
  hullCanvas.height = height;
  const hullContext = hullCanvas.getContext("2d", { willReadFrequently: true });
  hullContext.beginPath();
  hullContext.moveTo(hull[0].x, hull[0].y);
  hull.slice(1).forEach(point => hullContext.lineTo(point.x, point.y));
  hullContext.closePath();
  hullContext.fillStyle = "#fff";
  hullContext.fill();
  const hullData = hullContext.getImageData(0, 0, width, height).data;

  const inside = new Uint8Array(width * height);
  const distance = new Uint16Array(width * height);
  const distanceQueue = [];
  let fillablePixels = 0;
  let insidePixels = 0;
  let insideX = 0;
  let insideY = 0;
  for (let index = 0; index < inside.length; index += 1) {
    inside[index] = hullData[index * 4 + 3] >= 128 ? 1 : 0;
    if (!inside[index]) continue;
    insidePixels += 1;
    insideX += index % width;
    insideY += Math.floor(index / width);
    if (!ink[index]) fillablePixels += 1;
  }
  if (fillablePixels < scale * scale * 0.7) return null;

  for (let index = 0; index < inside.length; index += 1) {
    if (!inside[index]) continue;
    const x = index % width;
    const y = Math.floor(index / width);
    const touchesExterior = x === 0 || x === width - 1 || y === 0 || y === height - 1 ||
      !inside[index - 1] || !inside[index + 1] || !inside[index - width] || !inside[index + width];
    if (!touchesExterior) continue;
    distance[index] = 1;
    distanceQueue.push(index);
  }
  for (let cursor = 0; cursor < distanceQueue.length; cursor += 1) {
    const index = distanceQueue[cursor];
    const x = index % width;
    for (const adjacent of [index - 1, index + 1, index - width, index + width]) {
      const adjacentX = adjacent % width;
      if (adjacent < 0 || adjacent >= inside.length || Math.abs(adjacentX - x) > 1) continue;
      if (!inside[adjacent] || distance[adjacent]) continue;
      distance[adjacent] = distance[index] + 1;
      distanceQueue.push(adjacent);
    }
  }

  let maximumDistance = 0;
  for (let index = 0; index < distance.length; index += 1) {
    if (inside[index]) maximumDistance = Math.max(maximumDistance, distance[index]);
  }
  if (!insidePixels || maximumDistance < scale * 0.7) return null;

  const maskCanvas = document.createElement("canvas");
  maskCanvas.width = width;
  maskCanvas.height = height;
  const maskContext = maskCanvas.getContext("2d");
  const maskData = maskContext.createImageData(width, height);
  const distanceRange = Math.max(1, maximumDistance - 1);
  for (let index = 0; index < inside.length; index += 1) {
    const normalizedDistance = inside[index]
      ? Math.min(1, Math.max(0, distance[index] - 1) / distanceRange)
      : 0;
    maskData.data[index * 4] = Math.round(normalizedDistance * 255);
    maskData.data[index * 4 + 1] = inside[index] ? 255 : 0;
    maskData.data[index * 4 + 2] = ink[index] ? 255 : 0;
    maskData.data[index * 4 + 3] = 255;
  }
  maskContext.putImageData(maskData, 0, 0);
  return {
    eyeAngle: dominant?.angle || 0,
    eyeCenter: new THREE.Vector2(insideX / insidePixels / scale, insideY / insidePixels / scale),
    maskCanvas,
    radius: new THREE.Vector2((maxX - minX + 1) / (2 * scale), (maxY - minY + 1) / (2 * scale)),
  };
}

/**
 * Make occasional letterforms in marked document text look back at the reader.
 */
export class DocumentLooksBack {
  constructor({
    root,
    mount,
    maxEyes = 8,
    duration = DEFAULT_DURATION,
    excludeSelector = null,
    frequency = DEFAULT_FREQUENCY,
    motionQuery,
    selector = null,
  } = {}) {
    const document = (root?.nodeType === 9 ? root : root?.ownerDocument)
      || mount?.ownerDocument
      || globalThis.document;
    if (!document) throw new Error("DocumentLooksBack requires a browser document.");

    this.document = document;
    this.window = document.defaultView;
    this.root = resolveRoot(root, document);
    this.mountNode = resolveMount(mount, document);
    this.maxEyes = resolveMaxEyes(maxEyes);
    this.duration = positiveNumber(duration, "duration", 500);
    this.excludeSelector = resolveExcludeSelector(excludeSelector, this.root);
    this.frequency = resolveFrequency(frequency);
    this.motionQuery = motionQuery || this.window.matchMedia("(prefers-reduced-motion: reduce)");
    this.selector = resolveSelector(selector, this.root);

    this.activeEyes = new Set();
    this.abortController = null;
    this.autoTimer = 0;
    this.destroyed = false;
    this.mounted = false;
    this.nextNodeId = 1;
    this.nodeIds = new WeakMap();
    this.pointer = {
      movedAt: -Infinity,
      serial: 0,
      x: this.window.innerWidth / 2,
      y: this.window.innerHeight / 2,
    };
    this.rendererPool = [];
    this.rendererUnavailable = false;
    this.timers = new Set();
  }

  mount() {
    if (this.destroyed) throw new Error("A destroyed DocumentLooksBack controller cannot be mounted again.");
    if (this.mounted) return this;
    if (mountedDocuments.has(this.document)) {
      throw new Error("Only one DocumentLooksBack controller may be mounted in a document.");
    }

    this.abortController = new this.window.AbortController();
    const { signal } = this.abortController;
    this.window.addEventListener("pointermove", event => this.onPointerMove(event), { passive: true, signal });
    this.document.addEventListener("visibilitychange", () => {
      if (this.document.hidden) {
        this.clearTimers();
        [...this.activeEyes].forEach(active => this.restore(active));
      } else {
        this.scheduleAutomatic();
      }
    }, { signal });
    this.motionQuery.addEventListener("change", () => this.reset(), { signal });

    mountedDocuments.add(this.document);
    this.mounted = true;
    this.scheduleAutomatic();
    return this;
  }

  schedule(callback, delay) {
    const timer = this.window.setTimeout(() => {
      this.timers.delete(timer);
      callback();
    }, delay);
    this.timers.add(timer);
    return timer;
  }

  clearTimers() {
    this.timers.forEach(timer => this.window.clearTimeout(timer));
    this.timers.clear();
    this.autoTimer = 0;
  }

  automaticDelay() {
    if (!this.frequency) return null;
    return this.frequency.min + Math.random() * (this.frequency.max - this.frequency.min);
  }

  scheduleAutomatic(delay = this.automaticDelay()) {
    if (
      !this.mounted ||
      this.destroyed ||
      this.document.hidden ||
      this.rendererUnavailable ||
      this.autoTimer ||
      delay === null
    ) return;
    this.autoTimer = this.schedule(() => {
      this.autoTimer = 0;
      this.summon();
      this.scheduleAutomatic();
    }, delay);
  }

  visibleBounds(element) {
    const viewport = this.window.visualViewport;
    const bounds = {
      bottom: viewport ? viewport.offsetTop + viewport.height : this.window.innerHeight,
      left: viewport ? viewport.offsetLeft : 0,
      right: viewport ? viewport.offsetLeft + viewport.width : this.window.innerWidth,
      top: viewport ? viewport.offsetTop : 0,
    };
    let ancestor = element;
    while (ancestor) {
      const overflow = this.window.getComputedStyle(ancestor);
      if ([overflow.overflow, overflow.overflowX, overflow.overflowY].some(value =>
        ["auto", "clip", "hidden", "scroll"].includes(value)
      )) {
        const rect = ancestor.getBoundingClientRect();
        bounds.left = Math.max(bounds.left, rect.left);
        bounds.top = Math.max(bounds.top, rect.top);
        bounds.right = Math.min(bounds.right, rect.right);
        bounds.bottom = Math.min(bounds.bottom, rect.bottom);
      }
      ancestor = ancestor.parentElement;
    }
    return bounds;
  }

  hasVisiblePaint(element) {
    let ancestor = element;
    while (ancestor) {
      const computed = this.window.getComputedStyle(ancestor);
      if (
        computed.display === "none" ||
        computed.visibility === "hidden" ||
        computed.visibility === "collapse" ||
        computed.contentVisibility === "hidden" ||
        Number.parseFloat(computed.opacity) <= 0
      ) return false;
      ancestor = ancestor.parentElement;
    }
    return true;
  }

  intersectsVisibleBounds(rect, element) {
    if (rect.width <= 0 || rect.height <= 0 || !element || !this.hasVisiblePaint(element)) return false;
    const bounds = this.visibleBounds(element);
    return Math.min(rect.right, bounds.right) > Math.max(rect.left, bounds.left) &&
      Math.min(rect.bottom, bounds.bottom) > Math.max(rect.top, bounds.top);
  }

  isVisible(rect, element, minimumCoverage = 0.82) {
    if (!this.intersectsVisibleBounds(rect, element)) return false;
    const bounds = this.visibleBounds(element);
    const width = Math.max(0, Math.min(rect.right, bounds.right) - Math.max(rect.left, bounds.left));
    const height = Math.max(0, Math.min(rect.bottom, bounds.bottom) - Math.max(rect.top, bounds.top));
    if (width * height / (rect.width * rect.height) < minimumCoverage) return false;

    const x = Math.min(bounds.right - 0.5, Math.max(bounds.left + 0.5, rect.left + rect.width / 2));
    const y = Math.min(bounds.bottom - 0.5, Math.max(bounds.top + 0.5, rect.top + rect.height / 2));
    const topmost = this.document.elementFromPoint(x, y);
    return Boolean(topmost && (element.contains(topmost) || topmost.contains(element)));
  }

  watchableElements() {
    const elements = [...this.root.querySelectorAll(this.selector)];
    if (this.root instanceof this.window.Element && this.root.matches(this.selector)) {
      elements.unshift(this.root);
    }
    return elements.filter(element => !elements.some(other => other !== element && other.contains(element)));
  }

  isExcluded(element) {
    return Boolean(this.excludeSelector && element?.closest(this.excludeSelector));
  }

  transformedGlyph(node, index) {
    const element = node.parentElement;
    if (!element) return null;
    const source = node.data[index];
    const transform = this.window.getComputedStyle(element).textTransform;
    const locale = element.closest("[lang]")?.lang || this.document.documentElement.lang || undefined;
    let glyph = source;

    if (transform === "uppercase") {
      glyph = source.toLocaleUpperCase(locale);
    } else if (transform === "lowercase") {
      glyph = source.toLocaleLowerCase(locale);
    } else if (transform === "capitalize") {
      let scope = element;
      while (
        scope.parentElement &&
        this.window.getComputedStyle(scope.parentElement).textTransform === transform
      ) scope = scope.parentElement;

      const prefix = this.document.createRange();
      prefix.selectNodeContents(scope);
      prefix.setEnd(node, index);
      const offset = prefix.toString().length;
      prefix.detach();
      const segmenter = new Intl.Segmenter(locale, { granularity: "word" });
      const segment = [...segmenter.segment(scope.textContent || "")].find(part =>
        part.isWordLike && offset >= part.index && offset < part.index + part.segment.length
      );
      if (segment && offset === segment.index) glyph = source.toLocaleUpperCase(locale);
    } else if (transform !== "none") {
      return null;
    }

    return [...glyph].length === 1 ? glyph : null;
  }

  findCandidates() {
    const candidates = [];
    const occupied = new Set([...this.activeEyes].map(active =>
      `${this.nodeIds.get(active.candidate.node)}:${active.candidate.index}`
    ));
    for (const element of this.watchableElements()) {
      if (!this.intersectsVisibleBounds(element.getBoundingClientRect(), element)) continue;
      const walker = this.document.createTreeWalker(element, this.window.NodeFilter.SHOW_TEXT);
      let node;
      while ((node = walker.nextNode())) {
        if (this.isExcluded(node.parentElement)) continue;
        if (!this.nodeIds.has(node)) this.nodeIds.set(node, this.nextNodeId++);
        GLYPH_PATTERN.lastIndex = 0;
        let match;
        while ((match = GLYPH_PATTERN.exec(node.data))) {
          const glyph = this.transformedGlyph(node, match.index);
          if (!glyph) continue;
          const range = this.document.createRange();
          range.setStart(node, match.index);
          range.setEnd(node, match.index + 1);
          const rect = range.getBoundingClientRect();
          const key = `${this.nodeIds.get(node)}:${match.index}`;
          if (!occupied.has(key) && this.isVisible(rect, node.parentElement) && rect.width >= 4 && rect.height >= 8) {
            candidates.push({ glyph, node, index: match.index, rect });
          }
          range.detach();
        }
      }
    }
    return candidates;
  }

  chooseCandidates(candidates) {
    const centered = candidates.filter(({ rect }) =>
      rect.top > this.window.innerHeight * 0.16 && rect.bottom < this.window.innerHeight * 0.84
    );
    const pool = centered.length ? centered : candidates;
    return [...pool].sort(() => Math.random() - 0.5);
  }

  parseColor(color) {
    const context = this.document.createElement("canvas").getContext("2d");
    context.fillStyle = color;
    context.fillRect(0, 0, 1, 1);
    const [red, green, blue] = context.getImageData(0, 0, 1, 1).data;
    return new THREE.Color(red / 255, green / 255, blue / 255);
  }

  snapshotGlyph(candidate) {
    const parent = candidate.node.parentElement;
    if (!parent) return null;
    const { rect } = candidate;
    const computed = this.window.getComputedStyle(parent);
    const scale = Math.max(3, Math.ceil(this.window.devicePixelRatio * 2));
    const padLeft = Math.max(4, rect.height * 0.24);
    const padRight = padLeft;
    const padTop = Math.max(6, rect.height * 0.42);
    const padBottom = Math.max(2, rect.height * 0.08);
    const width = Math.ceil(rect.width + padLeft + padRight);
    const height = Math.ceil(rect.height + padTop + padBottom);
    const canvas = this.document.createElement("canvas");
    canvas.width = Math.ceil(width * scale);
    canvas.height = Math.ceil(height * scale);
    const context = canvas.getContext("2d", { willReadFrequently: true });
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.fillStyle = "#fff";
    context.font = fontShorthand(computed, scale);
    context.textAlign = "center";
    context.textBaseline = "alphabetic";
    const metrics = context.measureText(candidate.glyph);
    const ascent = metrics.fontBoundingBoxAscent || metrics.actualBoundingBoxAscent;
    const descent = metrics.fontBoundingBoxDescent || metrics.actualBoundingBoxDescent;
    const fontBoxHeight = (ascent + descent) / scale;
    const baseline = padTop + Math.max(0, (rect.height - fontBoxHeight) / 2) + ascent / scale;
    context.fillText(candidate.glyph, (padLeft + rect.width / 2) * scale, baseline * scale);
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const silhouette = analyzeSilhouette(imageData, canvas.width, canvas.height, scale, this.document);
    if (!silhouette) return null;
    return { computed, height, padLeft, padTop, silhouette, width };
  }

  measureCandidate(candidate) {
    if (!candidate.node.isConnected || candidate.index >= candidate.node.length) return null;
    const range = this.document.createRange();
    range.setStart(candidate.node, candidate.index);
    range.setEnd(candidate.node, candidate.index + 1);
    const rect = range.getBoundingClientRect();
    range.detach();
    return rect;
  }

  positionEye(active) {
    const rect = this.measureCandidate(active.candidate);
    const element = active.candidate.node.parentElement;
    if (!rect || this.isExcluded(element) || !this.isVisible(rect, element)) return false;
    active.candidate.rect = rect;
    active.canvas.style.left = `${rect.left - active.layout.padLeft}px`;
    active.canvas.style.top = `${rect.top - active.layout.padTop}px`;
    return true;
  }

  acquireRenderSurface() {
    const pooled = this.rendererPool.pop();
    if (pooled) return pooled;
    if (this.rendererUnavailable) return null;

    const canvas = this.document.createElement("canvas");
    try {
      const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true, premultipliedAlpha: false });
      renderer.setClearColor(0x000000, 0);
      return { canvas, renderer };
    } catch {
      this.rendererUnavailable = true;
      return null;
    }
  }

  releaseRenderSurface(canvas, renderer) {
    canvas.className = "document-looks-back-witness";
    delete canvas.dataset.stage;
    if (!this.destroyed && this.rendererPool.length < this.maxEyes) {
      this.rendererPool.push({ canvas, renderer });
      return;
    }
    renderer.dispose();
  }

  createEye(candidate) {
    const snapshot = this.snapshotGlyph(candidate);
    if (!snapshot) return null;
    const surface = this.acquireRenderSurface();
    if (!surface) return null;
    const { canvas, renderer } = surface;
    canvas.className = "document-looks-back-witness";
    canvas.setAttribute("aria-hidden", "true");
    canvas.style.width = `${snapshot.width}px`;
    canvas.style.height = `${snapshot.height}px`;
    renderer.setPixelRatio(Math.min(this.window.devicePixelRatio * 2, 4));
    renderer.setSize(snapshot.width, snapshot.height, false);

    const texture = new THREE.CanvasTexture(snapshot.silhouette.maskCanvas);
    texture.colorSpace = THREE.NoColorSpace;
    texture.minFilter = THREE.LinearFilter;
    texture.magFilter = THREE.LinearFilter;
    texture.generateMipmaps = false;
    const uniforms = {
      uEyeAngle: { value: snapshot.silhouette.eyeAngle },
      uEyeOpen: { value: 0 },
      uEyeCenter: { value: snapshot.silhouette.eyeCenter },
      uGaze: { value: new THREE.Vector2() },
      uGrowth: { value: 0 },
      uInk: { value: this.parseColor(snapshot.computed.color) },
      uResolution: { value: new THREE.Vector2(snapshot.width, snapshot.height) },
      uSilhouette: { value: texture },
      uSilhouetteRadius: { value: snapshot.silhouette.radius },
    };
    const material = new THREE.ShaderMaterial({ fragmentShader, transparent: true, uniforms, vertexShader });
    const geometry = new THREE.PlaneGeometry(2, 2);
    const scene = new THREE.Scene();
    scene.add(new THREE.Mesh(geometry, material));
    const camera = new THREE.Camera();
    renderer.render(scene, camera);

    return {
      camera,
      canvas,
      candidate,
      layout: snapshot,
      renderer,
      scene,
      uniforms,
      dispose: () => {
        geometry.dispose();
        material.dispose();
        texture.dispose();
        this.releaseRenderSurface(canvas, renderer);
      },
    };
  }

  prepareCandidate(candidate) {
    candidate.glyph = this.transformedGlyph(candidate.node, candidate.index);
    if (!candidate.glyph) return null;
    const active = this.createEye(candidate);
    if (!active) return null;
    this.mountNode.append(active.canvas);
    if (!this.positionEye(active)) {
      active.dispose();
      active.canvas.remove();
      return null;
    }
    return active;
  }

  blink(active) {
    if (!this.activeEyes.has(active) || this.motionQuery.matches || active.blinked) return;
    active.blinked = true;
    active.blinkUntil = this.window.performance.now() + active.phases.blinkDuration;
  }

  updateGaze(active, now, elapsed) {
    if (this.motionQuery.matches) return;
    if (this.pointer.serial > active.pointerSerialAtSummon) {
      active.pointerEngaged = true;
      active.lastPointerAt = this.pointer.movedAt;
    }

    const { phases } = active;
    const target = active.gazeTarget;
    const pointerIdleFor = now - active.lastPointerAt;
    const autonomousStarts = phases.watchStarts + phases.total * 0.072;
    const autonomousReturns = phases.watchStarts + phases.total * 0.208;
    const pointerReturnsAfter = phases.total * 0.311;
    const finalReturnStarts = phases.closeStarts - phases.total * 0.068;

    if (elapsed >= finalReturnStarts) {
      if (!active.blinked) this.blink(active);
      target.set(0, 0);
    } else if (active.pointerEngaged && pointerIdleFor < pointerReturnsAfter) {
      const rect = active.candidate.rect;
      const dx = this.pointer.x - (rect.left + rect.width / 2);
      const dy = this.pointer.y - (rect.top + rect.height / 2);
      const distance = Math.max(1, Math.hypot(dx, dy));
      target.set(dx / distance * 0.72, dy / distance * 0.48);
      if (!active.blinked && pointerIdleFor >= pointerReturnsAfter - phases.total * 0.038) this.blink(active);
    } else if (!active.pointerEngaged && elapsed >= autonomousStarts && elapsed < autonomousReturns) {
      target.copy(active.autonomousGaze);
      if (!active.blinked && elapsed >= autonomousReturns - phases.total * 0.038) this.blink(active);
    } else {
      target.set(0, 0);
    }

    const delta = Math.min(40, Math.max(0, now - active.lastGazeFrame));
    const timingScale = this.duration / DEFAULT_DURATION;
    const easingTime = target.lengthSq() < 0.0001 ? 260 : 90;
    const ease = 1 - Math.exp(-delta / Math.max(25, easingTime * timingScale));
    active.uniforms.uGaze.value.lerp(target, ease);
    active.lastGazeFrame = now;
  }

  restore(active) {
    if (!active || active.restored) return;
    active.restored = true;
    this.window.cancelAnimationFrame(active.animationFrame);
    if (active.timer) {
      this.window.clearTimeout(active.timer);
      this.timers.delete(active.timer);
    }
    active.dispose();
    active.canvas.remove();
    this.activeEyes.delete(active);
  }

  summon() {
    if (
      !this.mounted ||
      this.destroyed ||
      this.document.hidden ||
      this.rendererUnavailable
    ) return false;
    if (this.activeEyes.size >= this.maxEyes) return false;

    const rejected = new Set();
    let active = null;
    for (let attempt = 0; attempt < 14; attempt += 1) {
      const candidates = this.chooseCandidates(this.findCandidates()).filter(candidate => {
        const glyph = candidate.glyph;
        const computed = candidate.node.parentElement
          ? this.window.getComputedStyle(candidate.node.parentElement)
          : null;
        const signature = computed
          ? `${glyph}|${computed.fontFamily}|${computed.fontSize}|${computed.fontWeight}`
          : glyph;
        candidate.signature = signature;
        return !rejected.has(signature);
      });
      const candidate = candidates[0];
      if (!candidate) break;
      active = this.prepareCandidate(candidate);
      if (active) break;
      rejected.add(candidate.signature);
    }
    if (!active) return false;

    this.activeEyes.add(active);
    active.startedAt = this.window.performance.now();
    active.blinkUntil = 0;
    active.blinked = false;
    active.pointerEngaged = false;
    active.pointerSerialAtSummon = this.pointer.serial;
    active.lastPointerAt = -Infinity;
    active.lastGazeFrame = active.startedAt;
    active.gazeTarget = new THREE.Vector2();
    const saccadeDirection = Math.random() < 0.5 ? -1 : 1;
    active.autonomousGaze = new THREE.Vector2(
      saccadeDirection * (0.28 + Math.random() * 0.18),
      (Math.random() - 0.5) * 0.16
    );
    const reduced = this.motionQuery.matches;
    active.phases = phasesFor(this.duration, reduced);
    active.phases.blinkDuration = Math.max(80, 145 * this.duration / DEFAULT_DURATION);

    const render = now => {
      if (!this.activeEyes.has(active)) return;
      if (!this.positionEye(active)) {
        this.restore(active);
        return;
      }
      const elapsed = now - active.startedAt;
      let growth = reduced ? 1 : smooth(elapsed / active.phases.fillDuration);
      if (!reduced && elapsed >= active.phases.drainStarts) {
        growth = 1 - smooth((elapsed - active.phases.drainStarts) / active.phases.drainDuration);
      }
      let eyeOpen = reduced ? 1 : smooth((elapsed - active.phases.eyeStarts) / active.phases.eyeDuration);
      if (!reduced && elapsed >= active.phases.closeStarts) {
        eyeOpen = 1 - smooth((elapsed - active.phases.closeStarts) / active.phases.closeDuration);
      }
      if (now < active.blinkUntil) eyeOpen = 0.025;
      active.canvas.dataset.stage = elapsed < active.phases.eyeStarts
        ? "filling"
        : elapsed < active.phases.closeStarts ? "watching" : "returning";
      active.uniforms.uGrowth.value = growth;
      active.uniforms.uEyeOpen.value = eyeOpen;
      this.updateGaze(active, now, elapsed);
      active.renderer.render(active.scene, active.camera);
      active.animationFrame = this.window.requestAnimationFrame(render);
    };

    if (reduced) {
      active.canvas.dataset.stage = "watching";
      active.uniforms.uGrowth.value = 1;
      active.uniforms.uEyeOpen.value = 1;
      active.renderer.render(active.scene, active.camera);
      const trackPosition = () => {
        if (!this.activeEyes.has(active)) return;
        if (!this.positionEye(active)) {
          this.restore(active);
          return;
        }
        active.animationFrame = this.window.requestAnimationFrame(trackPosition);
      };
      active.animationFrame = this.window.requestAnimationFrame(trackPosition);
    } else {
      active.animationFrame = this.window.requestAnimationFrame(render);
    }
    active.timer = this.schedule(() => this.restore(active), active.phases.total);
    return true;
  }

  reset() {
    this.clearTimers();
    [...this.activeEyes].forEach(active => this.restore(active));
    this.scheduleAutomatic();
  }

  destroy() {
    if (this.destroyed) return;
    this.destroyed = true;
    this.clearTimers();
    [...this.activeEyes].forEach(active => this.restore(active));
    this.rendererPool.splice(0).forEach(({ canvas, renderer }) => {
      canvas.remove();
      renderer.dispose();
    });
    this.abortController?.abort();
    if (this.mounted) mountedDocuments.delete(this.document);
    this.mounted = false;
  }

  onPointerMove(event) {
    if (event.pointerType && event.pointerType !== "mouse") return;
    this.pointer.movedAt = this.window.performance.now();
    this.pointer.serial += 1;
    this.pointer.x = event.clientX;
    this.pointer.y = event.clientY;
  }

  get active() {
    return this.activeEyes.size > 0;
  }

  get activeCount() {
    return this.activeEyes.size;
  }
}

export { DEFAULT_DURATION, DEFAULT_FREQUENCY };
