import * as THREE from "./vendor/three.module.min.js";
import { DEFAULT_SUN_STYLE, resolveSunStyle } from "./heliogenesis-options.js";

/**
 * Create the Three.js renderer used by the Heliogenesis browser integration.
 *
 * The returned object owns its WebGL context and every resource allocated in
 * the scene. Call destroy() when removing the integration from a document.
 */
export function createHeliogenesisScene({
  canvas,
  reducedMotion = () => false,
  onSunPosition = () => {},
  sunStyle = DEFAULT_SUN_STYLE,
}) {
  const view = canvas?.ownerDocument?.defaultView;
  if (!view || !(canvas instanceof view.HTMLCanvasElement)) {
    throw new TypeError("Heliogenesis requires a canvas element.");
  }
  sunStyle = resolveSunStyle(sunStyle);
  const useSynthwaveSun = sunStyle === "synthwave";
  const useTransmutationSun = sunStyle === "transmutation";
  const useStylizedSun = useSynthwaveSun || useTransmutationSun;
  const sunDefines = useSynthwaveSun
    ? { HELIOGENESIS_SPECTRAL: 1, HELIOGENESIS_SYNTHWAVE: 1 }
    : useTransmutationSun
      ? { HELIOGENESIS_SPECTRAL: 1, HELIOGENESIS_TRANSMUTATION: 1 }
      : {};
  const environment = canvas.closest("[data-heliogenesis-environment]") || canvas.parentElement;

  const motionQuery = {
    get matches() {
      return Boolean(reducedMotion());
    },
  };

  let renderer;
  let scene;
  let camera;
  let solarAnchor;
  let accretionGroup;
  let registryGroup;
  let hydrogen;
  let feederField;
  let innerDiskField;
  let absorptionDisk;
  let jetField;
  let star;
  let protoVolume;
  let accretionKnots;
  let protoField;
  let ignitionShell;
  let atmosphere;
  let corona;
  let prominenceField;
  let ruptureField;
  let tomographyField;
  let tomographySynchronizations = 0;
  let gravityWell;
  let emberField;
  let petalField;
  let filamentMaterials = [];
  let registryMaterials = [];
  let mode = "idle";
  let animationFrame = null;
  let eventStartedAt = 0;
  let renderedFrames = 0;
  let riseDuration = 15000;
  let viewport = {
    width: 1,
    height: 1,
    offsetLeft: 0,
    offsetTop: 0,
    worldWidth: 1,
    worldHeight: 1,
    compact: false,
    narrow: false,
  };

  const random = mulberry32(0x5ec0da7a);
  const volumeCameraLocal = new THREE.Vector3();
  const particleFlowPosition = new THREE.Vector3();

  function mulberry32(seed) {
    return function next() {
      let value = seed += 0x6d2b79f5;
      value = Math.imul(value ^ value >>> 15, value | 1);
      value ^= value + Math.imul(value ^ value >>> 7, value | 61);
      return ((value ^ value >>> 14) >>> 0) / 4294967296;
    };
  }

  function gaussian(scale = 1) {
    const u = Math.max(0.0001, random());
    const v = random();
    return Math.sqrt(-2 * Math.log(u)) * Math.cos(Math.PI * 2 * v) * scale;
  }

  function clamp01(value) {
    return Math.max(0, Math.min(1, value));
  }

  function smoothstep(edge0, edge1, value) {
    const x = clamp01((value - edge0) / (edge1 - edge0));
    return x * x * (3 - 2 * x);
  }

  function impactPulse(progress, center, width) {
    return 1 - smoothstep(0, width, Math.abs(progress - center));
  }

  function makeRenderer() {
    renderer = new THREE.WebGLRenderer({
      canvas,
      alpha: true,
      antialias: true,
      powerPreference: "high-performance",
    });
    renderer.setClearColor(0x000000, 0);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.32;
  }

  function makeParticleMaterial() {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uCollapse: { value: 0 },
        uGlobalAlpha: { value: 0 },
        uPixelRatio: { value: 1 },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uCollapse;
        uniform float uGlobalAlpha;
        uniform float uPixelRatio;
        attribute vec4 aStart;
        attribute vec4 aFlow;
        attribute vec3 color;
        varying vec3 vColor;
        varying float vAlpha;

        float ease(float value) {
          return value * value * (3.0 - 2.0 * value);
        }

        void main() {
          float delay = aStart.w;
          float p = clamp((uCollapse - delay) / max(0.12, 1.0 - delay), 0.0, 1.0);
          float curved = ease(p);
          float radius0 = length(aStart.xz);
          float radius = mix(radius0, aFlow.x, pow(curved, 0.72));
          float startAngle = atan(aStart.z, aStart.x);
          float angle = startAngle + curved * aFlow.y * 6.2831853 + uTime * (0.08 + aFlow.z * 0.012);
          float finalY = sin(startAngle * 3.0 + aFlow.y) * (0.04 + aFlow.x * 0.022);
          float y = mix(aStart.y, finalY, curved);
          y += sin(angle * 2.0 + uTime * 0.27) * (1.0 - curved) * 0.22;
          vec3 transformed = vec3(cos(angle) * radius, y, sin(angle) * radius);
          vec4 mvPosition = modelViewMatrix * vec4(transformed, 1.0);

          float coreHeat = 1.0 - smoothstep(1.0, 5.4, radius);
          vColor = mix(color, vec3(1.0, 0.56, 0.13), coreHeat * 0.78);
          float absorbed = mix(1.0, 1.0 - smoothstep(0.76, 1.0, p), aFlow.w);
          float reveal = smoothstep(0.0, 0.08, p);
          float distanceFade = 1.0 - smoothstep(16.0, 23.0, radius0);
          vAlpha = uGlobalAlpha * reveal * absorbed * (0.24 + distanceFade * 0.76);
          gl_PointSize = (0.8 + aFlow.z * 1.65) * uPixelRatio * (34.0 / max(4.0, -mvPosition.z));
          gl_Position = projectionMatrix * mvPosition;
        }
      `,
      fragmentShader: `
        varying vec3 vColor;
        varying float vAlpha;

        void main() {
          vec2 point = gl_PointCoord - 0.5;
          float distanceToCenter = length(point);
          if (distanceToCenter > 0.5) discard;
          float core = 1.0 - smoothstep(0.02, 0.5, distanceToCenter);
          float spark = pow(core, 5.0);
          gl_FragColor = vec4(vColor + spark * 0.34, vAlpha * core * 0.72);
        }
      `,
    });
  }

  function buildHydrogen() {
    const count = viewport.narrow ? 7600 : viewport.compact ? 11200 : 16800;
    const positions = new Float32Array(count * 3);
    const starts = new Float32Array(count * 4);
    const flows = new Float32Array(count * 4);
    const colors = new Float32Array(count * 3);
    const corridors = Array.from({ length: viewport.compact ? 10 : 15 }, (_, index) =>
      index / (viewport.compact ? 10 : 15) * Math.PI * 2 + gaussian(0.12)
    );
    const palette = [
      new THREE.Color(0x63e5e5),
      new THREE.Color(0x63e5e5),
      new THREE.Color(0x9a8de7),
      new THREE.Color(0xe83d7c),
      new THREE.Color(0xe83d7c),
      new THREE.Color(0xffa761),
    ];

    for (let index = 0; index < count; index += 1) {
      const offset3 = index * 3;
      const offset4 = index * 4;
      const corridor = corridors[Math.floor(random() * corridors.length)];
      const angle = corridor + gaussian(0.14 + random() * 0.17);
      const outside = random() < 0.68;
      const radius = outside ? 10.5 + Math.pow(random(), 0.52) * 12.5 : 7 + random() * 8;
      const vertical = gaussian(outside ? 3.1 : 1.85);
      const depthNoise = gaussian(0.75);
      const coreBound = random() < 0.37;
      const haloBound = !coreBound && random() < 0.12;
      const targetRadius = coreBound
        ? 0.12 + Math.pow(random(), 1.8) * 1.6
        : haloBound
          ? 6.5 + random() * 3.4
          : 1.7 + Math.pow(random(), 0.66) * 5.7;
      const color = palette[Math.floor(random() * palette.length)].clone();
      color.offsetHSL(gaussian(0.012), gaussian(0.025), gaussian(0.035));

      starts[offset4] = Math.cos(angle) * radius;
      starts[offset4 + 1] = vertical;
      starts[offset4 + 2] = Math.sin(angle) * radius + depthNoise;
      starts[offset4 + 3] = Math.pow(random(), 1.5) * 0.66;
      flows[offset4] = targetRadius;
      flows[offset4 + 1] = 1.15 + random() * 3.8 + radius * 0.045;
      flows[offset4 + 2] = random();
      flows[offset4 + 3] = coreBound ? 1 : 0;
      colors[offset3] = color.r;
      colors[offset3 + 1] = color.g;
      colors[offset3 + 2] = color.b;
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute("aStart", new THREE.BufferAttribute(starts, 4));
    geometry.setAttribute("aFlow", new THREE.BufferAttribute(flows, 4));
    geometry.setAttribute("color", new THREE.BufferAttribute(colors, 3));
    geometry.boundingSphere = new THREE.Sphere(new THREE.Vector3(), 28);

    hydrogen = new THREE.Points(geometry, makeParticleMaterial());
    hydrogen.frustumCulled = false;
    hydrogen.renderOrder = 3;
    accretionGroup.add(hydrogen);
  }

  function makeFeederPointMaterial() {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uPixelRatio: { value: 1 },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uPixelRatio;
        attribute vec4 aFeed;
        attribute vec4 aMotion;
        attribute vec3 aColor;
        varying vec3 vColor;
        varying float vAlpha;

        vec3 feederPosition(float cycle) {
          float inward = pow(cycle, 0.72);
          float radius = mix(aFeed.x, aFeed.y, inward);
          float acceleratingTurn = cycle * 0.28 + cycle * cycle * 0.72;
          float angle = aFeed.z + acceleratingTurn * aMotion.x * 6.2831853 + uTime * 0.032;
          float settledY = sin(angle * 2.0 + aFeed.z) * (0.035 + aFeed.y * 0.026);
          float height = mix(aMotion.y, settledY, smoothstep(0.08, 0.9, cycle));
          height += sin(cycle * 12.0 + aFeed.z * 3.0) * (1.0 - cycle) * 0.26;
          return vec3(cos(angle) * radius, height, sin(angle) * radius);
        }

        void main() {
          float cycle = fract(aFeed.w + uTime * aMotion.z);
          vec3 transformed = feederPosition(cycle);
          vec4 mvPosition = modelViewMatrix * vec4(transformed, 1.0);
          float radius = length(transformed.xz);
          float heat = 1.0 - smoothstep(1.0, 6.4, radius);
          float whiteHeat = 1.0 - smoothstep(0.45, 2.0, radius);
          vColor = mix(aColor, vec3(1.0, 0.42, 0.08), heat * 0.86);
          vColor = mix(vColor, vec3(1.0, 0.92, 0.57), whiteHeat * 0.72);
          float birth = smoothstep(0.0, 0.055, cycle);
          float absorption = 1.0 - smoothstep(0.8, 0.985, cycle);
          float pulse = 0.78 + sin(cycle * 28.0 + aFeed.w * 19.0) * 0.22;
          vAlpha = uPresence * birth * absorption * pulse * (0.72 + heat * 0.58);
          gl_PointSize = (1.18 + aMotion.w * 2.7 + heat * 1.08) * uPixelRatio *
            (36.0 / max(4.0, -mvPosition.z));
          gl_Position = projectionMatrix * mvPosition;
        }
      `,
      fragmentShader: `
        varying vec3 vColor;
        varying float vAlpha;
        void main() {
          vec2 point = gl_PointCoord - 0.5;
          float radius = length(point);
          if (radius > 0.5) discard;
          float light = 1.0 - smoothstep(0.01, 0.5, radius);
          float core = pow(light, 5.0);
          float gaseousHalo = pow(light, 1.65);
          gl_FragColor = vec4(vColor + core * 0.62, vAlpha * (gaseousHalo * 0.78 + core * 0.2));
        }
      `,
    });
  }

  function makeFeederStreakMaterial() {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uPresence;
        attribute vec4 aFeed;
        attribute vec4 aMotion;
        attribute vec3 aColor;
        attribute float aTail;
        varying vec3 vColor;
        varying float vAlpha;

        vec3 feederPosition(float cycle) {
          float inward = pow(cycle, 0.72);
          float radius = mix(aFeed.x, aFeed.y, inward);
          float acceleratingTurn = cycle * 0.28 + cycle * cycle * 0.72;
          float angle = aFeed.z + acceleratingTurn * aMotion.x * 6.2831853 + uTime * 0.032;
          float settledY = sin(angle * 2.0 + aFeed.z) * (0.035 + aFeed.y * 0.026);
          float height = mix(aMotion.y, settledY, smoothstep(0.08, 0.9, cycle));
          height += sin(cycle * 12.0 + aFeed.z * 3.0) * (1.0 - cycle) * 0.26;
          return vec3(cos(angle) * radius, height, sin(angle) * radius);
        }

        void main() {
          float headCycle = fract(aFeed.w + uTime * aMotion.z);
          float trailLength = mix(0.003, 0.0105, headCycle);
          float cycle = max(0.0, headCycle - aTail * trailLength);
          vec3 transformed = feederPosition(cycle);
          float radius = length(transformed.xz);
          float heat = 1.0 - smoothstep(1.0, 6.4, radius);
          vColor = mix(aColor, vec3(1.0, 0.54, 0.11), heat * 0.88);
          float birth = smoothstep(0.04, 0.09, headCycle);
          float absorption = 1.0 - smoothstep(0.8, 0.98, headCycle);
          vAlpha = uPresence * birth * absorption * mix(0.06, 0.58, 1.0 - aTail);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(transformed, 1.0);
        }
      `,
      fragmentShader: `
        varying vec3 vColor;
        varying float vAlpha;
        void main() {
          gl_FragColor = vec4(vColor, vAlpha);
        }
      `,
    });
  }

  function makeFeederSheathMaterial(color, speed, opacity) {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uSpeed: { value: speed },
        uOpacity: { value: opacity },
        uColor: { value: color.clone() },
      },
      vertexShader: `
        varying float vAlong;
        varying vec3 vNormalView;
        void main() {
          vAlong = uv.x;
          vNormalView = normalize(normalMatrix * normal);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uSpeed;
        uniform float uOpacity;
        uniform vec3 uColor;
        varying float vAlong;
        varying vec3 vNormalView;
        void main() {
          float phase = fract(vAlong * 7.0 - uTime * uSpeed);
          float compression = pow(1.0 - abs(phase - 0.5) * 2.0, 5.0);
          float taper = smoothstep(0.0, 0.055, vAlong) * (1.0 - smoothstep(0.9, 1.0, vAlong));
          float edge = 0.42 + (1.0 - abs(vNormalView.z)) * 0.58;
          float heat = smoothstep(0.55, 0.96, vAlong);
          vec3 color = mix(uColor, vec3(1.0, 0.46, 0.08), heat * 0.86);
          color = mix(color, vec3(1.0, 0.91, 0.58), smoothstep(0.84, 0.99, vAlong) * 0.62);
          float alpha = uPresence * uOpacity * taper * edge * (0.28 + compression * 0.72);
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
  }

  function buildFeeders() {
    const laneCount = viewport.narrow ? 5 : viewport.compact ? 6 : 8;
    const pointCount = viewport.narrow ? 4200 : viewport.compact ? 6800 : 9400;
    const streakCount = viewport.narrow ? 360 : viewport.compact ? 620 : 920;
    const palette = [0x4ce9e6, 0x79bde9, 0xd44894, 0xf05b86, 0xc68af0];
    const visibleIngressCount = Math.ceil(laneCount * 0.64);
    const lanes = Array.from({ length: laneCount }, (_, index) => {
      const angle = index < visibleIngressCount
        ? Math.PI + ((index / Math.max(1, visibleIngressCount - 1)) - 0.5) * 1.95
        : (index - visibleIngressCount) / Math.max(1, laneCount - visibleIngressCount) * Math.PI * 2;
      return {
        angle: angle + gaussian(0.065),
        height: gaussian(2.7),
        turns: 1.6 + random() * 2.1,
        speed: 0.072 + random() * 0.034,
        startRadius: 17.5 + random() * 8.5,
        endRadius: 0.58 + random() * 1.45,
        color: new THREE.Color(palette[index % palette.length]),
      };
    });
    const sheathMaterials = [];

    lanes.forEach((lane, laneIndex) => {
      const path = [];
      const segments = 190;
      for (let segment = 0; segment <= segments; segment += 1) {
        const cycle = segment / segments;
        const inward = Math.pow(cycle, 0.72);
        const radius = THREE.MathUtils.lerp(lane.startRadius, lane.endRadius, inward);
        const acceleratingTurn = cycle * 0.28 + cycle * cycle * 0.72;
        const angle = lane.angle + acceleratingTurn * lane.turns * Math.PI * 2;
        const settledY = Math.sin(angle * 2 + lane.angle) * (0.035 + lane.endRadius * 0.026);
        const height = THREE.MathUtils.lerp(lane.height, settledY, smoothstep(0.08, 0.9, cycle)) +
          Math.sin(cycle * 12 + lane.angle * 3) * (1 - cycle) * 0.26;
        path.push(new THREE.Vector3(Math.cos(angle) * radius, height, Math.sin(angle) * radius));
      }
      const curve = new THREE.CatmullRomCurve3(path);
      const tubeLayers = [
        { radius: 0.082 + (laneIndex % 3) * 0.008, opacity: 0.055, speed: 0.34 + random() * 0.12 },
        { radius: 0.038 + (laneIndex % 2) * 0.006, opacity: 0.13, speed: 0.39 + random() * 0.13 },
      ];
      tubeLayers.forEach((layer, layerIndex) => {
        const geometry = new THREE.TubeGeometry(curve, 230, layer.radius, 6, false);
        const material = makeFeederSheathMaterial(lane.color, layer.speed, layer.opacity);
        const tube = new THREE.Mesh(geometry, material);
        tube.frustumCulled = false;
        tube.renderOrder = 4 + layerIndex;
        sheathMaterials.push(material);
        accretionGroup.add(tube);
      });
    });

    function writeParticle(target, index, lane, tail = null) {
      const offset3 = index * 3;
      const offset4 = index * 4;
      const startRadius = Math.max(14, lane.startRadius + gaussian(tail === null ? 1.15 : 0.64));
      const endRadius = Math.max(0.38, lane.endRadius + gaussian(tail === null ? 0.14 : 0.08));
      const angle = lane.angle + gaussian(tail === null ? 0.022 : 0.012);
      const phase = random();
      const turns = lane.turns + gaussian(tail === null ? 0.055 : 0.028);
      const height = lane.height + gaussian(tail === null ? 0.2 : 0.11);
      const speed = Math.max(0.055, lane.speed + gaussian(tail === null ? 0.0026 : 0.0014));
      const size = random();
      const color = lane.color.clone().offsetHSL(gaussian(0.008), gaussian(0.018), gaussian(0.026));

      target.feed[offset4] = startRadius;
      target.feed[offset4 + 1] = endRadius;
      target.feed[offset4 + 2] = angle;
      target.feed[offset4 + 3] = phase;
      target.motion[offset4] = turns;
      target.motion[offset4 + 1] = height;
      target.motion[offset4 + 2] = speed;
      target.motion[offset4 + 3] = size;
      target.color[offset3] = color.r;
      target.color[offset3 + 1] = color.g;
      target.color[offset3 + 2] = color.b;
      if (target.tail) target.tail[index] = tail;
    }

    const pointData = {
      feed: new Float32Array(pointCount * 4),
      motion: new Float32Array(pointCount * 4),
      color: new Float32Array(pointCount * 3),
    };
    for (let index = 0; index < pointCount; index += 1) {
      writeParticle(pointData, index, lanes[index % lanes.length]);
    }
    const pointGeometry = new THREE.BufferGeometry();
    pointGeometry.setAttribute("position", new THREE.BufferAttribute(new Float32Array(pointCount * 3), 3));
    pointGeometry.setAttribute("aFeed", new THREE.BufferAttribute(pointData.feed, 4));
    pointGeometry.setAttribute("aMotion", new THREE.BufferAttribute(pointData.motion, 4));
    pointGeometry.setAttribute("aColor", new THREE.BufferAttribute(pointData.color, 3));
    pointGeometry.boundingSphere = new THREE.Sphere(new THREE.Vector3(), 32);
    const pointMaterial = makeFeederPointMaterial();
    pointMaterial.uniforms.uPixelRatio.value = renderer.getPixelRatio();
    const points = new THREE.Points(pointGeometry, pointMaterial);
    points.frustumCulled = false;
    points.renderOrder = 7;
    accretionGroup.add(points);

    const vertexCount = streakCount * 2;
    const streakData = {
      feed: new Float32Array(vertexCount * 4),
      motion: new Float32Array(vertexCount * 4),
      color: new Float32Array(vertexCount * 3),
      tail: new Float32Array(vertexCount),
    };
    for (let streak = 0; streak < streakCount; streak += 1) {
      const lane = lanes[streak % lanes.length];
      const record = {
        feed: new Float32Array(4),
        motion: new Float32Array(4),
        color: new Float32Array(3),
      };
      writeParticle(record, 0, lane);
      for (let endpoint = 0; endpoint < 2; endpoint += 1) {
        const vertex = streak * 2 + endpoint;
        streakData.feed.set(record.feed, vertex * 4);
        streakData.motion.set(record.motion, vertex * 4);
        streakData.color.set(record.color, vertex * 3);
        streakData.tail[vertex] = endpoint === 0 ? 1 : 0;
      }
    }
    const streakGeometry = new THREE.BufferGeometry();
    streakGeometry.setAttribute("position", new THREE.BufferAttribute(new Float32Array(vertexCount * 3), 3));
    streakGeometry.setAttribute("aFeed", new THREE.BufferAttribute(streakData.feed, 4));
    streakGeometry.setAttribute("aMotion", new THREE.BufferAttribute(streakData.motion, 4));
    streakGeometry.setAttribute("aColor", new THREE.BufferAttribute(streakData.color, 3));
    streakGeometry.setAttribute("aTail", new THREE.BufferAttribute(streakData.tail, 1));
    streakGeometry.boundingSphere = new THREE.Sphere(new THREE.Vector3(), 32);
    const streakMaterial = makeFeederStreakMaterial();
    const streaks = new THREE.LineSegments(streakGeometry, streakMaterial);
    streaks.frustumCulled = false;
    streaks.renderOrder = 6;
    accretionGroup.add(streaks);

    feederField = { points, pointMaterial, streaks, streakMaterial, sheathMaterials };
  }

  function makeInnerDiskMaterial(sharedUniforms) {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: true,
      blending: THREE.AdditiveBlending,
      uniforms: sharedUniforms,
      vertexShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uStarRadius;
        uniform float uMass;
        uniform float uDoppler;
        uniform float uLensing;
        uniform float uPixelRatio;
        attribute vec4 aOrbit;
        attribute vec4 aMotion;
        attribute vec3 aColor;
        varying vec3 vColor;
        varying float vAlpha;
        varying float vLensing;

        void main() {
          float cycle = fract(aOrbit.w + uTime * aMotion.x);
          float infall = pow(cycle, 1.34);
          float acceleration = cycle * 0.12 + cycle * cycle * 0.88;
          float angle = aOrbit.y + aOrbit.z * 6.2831853 * acceleration + uTime * 0.12;
          float innerRadius = uStarRadius * (1.025 + aMotion.w * 0.085);
          float radius = mix(aOrbit.x, innerRadius, infall);
          float eccentricity = 1.0 + sin(angle * 2.0 + aOrbit.y) * aMotion.w * 0.075;
          float height = aMotion.y * (1.0 - infall) * 0.34;
          height += sin(angle * 3.0 + aOrbit.w * 13.0) * (0.018 + radius * 0.009) * (1.0 - infall * 0.7);
          vec3 transformed = vec3(
            cos(angle) * radius * eccentricity,
            height,
            sin(angle) * radius / eccentricity
          );
          vec4 mvPosition = modelViewMatrix * vec4(transformed, 1.0);
          vec4 viewCenter = modelViewMatrix * vec4(0.0, 0.0, 0.0, 1.0);
          float viewStarRadius = uStarRadius * length(modelViewMatrix[0].xyz);
          vec2 sourceOffset = mvPosition.xy - viewCenter.xy;
          float sourceDistance = length(sourceOffset);
          float normalizedSource = sourceDistance / max(0.001, viewStarRadius);
          float farSide = step(mvPosition.z, viewCenter.z - 0.012);
          float lensZone = 1.0 - smoothstep(0.82, 1.62, normalizedSource);
          float einsteinRadius = 0.94;
          float outerImage = 0.5 * (
            normalizedSource + sqrt(normalizedSource * normalizedSource +
            4.0 * einsteinRadius * einsteinRadius)
          ) * viewStarRadius;
          vec2 fallbackDirection = normalize(vec2(cos(angle), sin(angle) * 0.62) + vec2(0.0001));
          vec2 sourceDirection = sourceDistance > 0.001
            ? sourceOffset / sourceDistance
            : fallbackDirection;
          float raySample = smoothstep(0.24, 0.58, aMotion.z);
          float lensing = farSide * lensZone * uLensing * raySample;
          mvPosition.xy = viewCenter.xy + sourceDirection * mix(sourceDistance, outerImage, lensing);
          float caustic = 1.0 - smoothstep(0.02, 0.62, normalizedSource);
          vLensing = farSide * uLensing * raySample *
            (0.24 + caustic * 0.76) * lensZone;
          vec3 tangent = normalize(vec3(-sin(angle), 0.0, cos(angle)));
          vec3 tangentView = normalize(mat3(modelViewMatrix) * tangent);
          float radialVelocity = tangentView.z;
          float birth = smoothstep(0.0, 0.055, cycle);
          float absorption = 1.0 - smoothstep(0.925, 0.998, cycle);
          float innerHeat = smoothstep(0.34, 0.98, infall);
          float plunge = smoothstep(0.58, 0.9, infall) *
            (1.0 - smoothstep(0.91, 0.998, infall));
          float compressionWave = pow(0.5 + 0.5 * cos(
            angle * 3.0 - log(max(radius, 0.2)) * 8.0 + uTime * 0.42
          ), 7.0);
          float drained = mix(1.0, 0.56, uMass);
          vColor = mix(aColor, vec3(1.0, 0.28, 0.025), innerHeat * 0.82);
          vColor = mix(vColor, vec3(1.0, 0.88, 0.48), pow(innerHeat, 4.0) * 0.72);
          vec3 approaching = vec3(0.015, 0.88, 1.0);
          vec3 receding = vec3(1.0, 0.018, 0.35);
          vec3 dopplerColor = mix(receding, approaching, smoothstep(-0.08, 0.08, radialVelocity));
          float dopplerStrength = abs(radialVelocity) * uDoppler * (1.0 - innerHeat * 0.52);
          vColor = mix(vColor, dopplerColor, dopplerStrength * 0.78);
          vColor += mix(dopplerColor, vec3(1.0, 0.84, 0.54), caustic * 0.62) *
            vLensing * 0.72;
          vAlpha = uPresence * birth * absorption * drained *
            (0.12 + aMotion.z * 0.62) *
            (0.42 + innerHeat * 0.48 + plunge * 0.46 + compressionWave * 0.34);
          vAlpha *= 0.92 + max(0.0, radialVelocity) * uDoppler * 0.22;
          gl_PointSize = (0.9 + aMotion.z * 2.75 + innerHeat * 1.35 +
            plunge * 1.1 + compressionWave * 0.7) * (1.0 + vLensing * 1.15) * uPixelRatio *
            (34.0 / max(4.0, -mvPosition.z));
          gl_Position = projectionMatrix * mvPosition;
        }
      `,
      fragmentShader: `
        varying vec3 vColor;
        varying float vAlpha;
        varying float vLensing;
        void main() {
          vec2 p = gl_PointCoord - 0.5;
          float radius = length(p);
          if (radius > 0.5) discard;
          float glow = 1.0 - smoothstep(0.03, 0.5, radius);
          float core = pow(glow, 4.0);
          float causticHalo = (1.0 - smoothstep(0.18, 0.5, radius)) * vLensing;
          gl_FragColor = vec4(
            vColor + core * 0.38 + vec3(1.0, 0.36, 0.42) * causticHalo * 0.42,
            vAlpha * glow * (1.0 + vLensing * 0.92)
          );
        }
      `,
    });
  }

  function buildInnerDisk() {
    const count = viewport.narrow ? 3600 : viewport.compact ? 5600 : 8200;
    const diskRandom = mulberry32(0xa11ce7ed);
    const orbit = new Float32Array(count * 4);
    const motion = new Float32Array(count * 4);
    const colors = new Float32Array(count * 3);
    const palette = [
      new THREE.Color(0x41e9e5),
      new THREE.Color(0x7bb7ef),
      new THREE.Color(0xda3288),
      new THREE.Color(0xff476f),
      new THREE.Color(0xffa03d),
    ];
    const laneCount = viewport.narrow ? 3 : viewport.compact ? 4 : 5;

    for (let index = 0; index < count; index += 1) {
      const offset4 = index * 4;
      const offset3 = index * 3;
      const lane = index % laneCount;
      const laneAngle = lane / laneCount * Math.PI * 2;
      const followsLane = diskRandom() < 0.89;
      const color = palette[Math.floor(diskRandom() * palette.length)].clone();
      color.offsetHSL((diskRandom() - 0.5) * 0.018, (diskRandom() - 0.5) * 0.04, 0);
      orbit[offset4] = followsLane
        ? 5.75 + lane * 0.18 + (diskRandom() - 0.5) * 0.86
        : 2.3 + Math.pow(diskRandom(), 0.68) * 4.9;
      orbit[offset4 + 1] = followsLane
        ? laneAngle + (diskRandom() - 0.5) * 0.16
        : diskRandom() * Math.PI * 2;
      orbit[offset4 + 2] = followsLane
        ? 2.2 + lane * 0.17 + (diskRandom() - 0.5) * 0.3
        : 1.65 + diskRandom() * 3.4;
      orbit[offset4 + 3] = diskRandom();
      motion[offset4] = 0.044 + diskRandom() * 0.032;
      motion[offset4 + 1] = (diskRandom() - 0.5) *
        (followsLane ? 0.3 + diskRandom() * 0.22 : 0.34 + diskRandom() * 0.36);
      motion[offset4 + 2] = followsLane ? 0.42 + diskRandom() * 0.58 : diskRandom();
      motion[offset4 + 3] = diskRandom();
      colors[offset3] = color.r;
      colors[offset3 + 1] = color.g;
      colors[offset3 + 2] = color.b;
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute("position", new THREE.BufferAttribute(new Float32Array(count * 3), 3));
    geometry.setAttribute("aOrbit", new THREE.BufferAttribute(orbit, 4));
    geometry.setAttribute("aMotion", new THREE.BufferAttribute(motion, 4));
    geometry.setAttribute("aColor", new THREE.BufferAttribute(colors, 3));
    geometry.boundingSphere = new THREE.Sphere(new THREE.Vector3(), 14);

    const uniforms = {
      uTime: { value: 0 },
      uPresence: { value: 0 },
      uStarRadius: { value: 0.35 },
      uMass: { value: 0 },
      uDoppler: { value: 0 },
      uLensing: { value: 0 },
      uPixelRatio: { value: renderer.getPixelRatio() },
    };
    const points = new THREE.Points(geometry, makeInnerDiskMaterial(uniforms));
    points.frustumCulled = false;
    points.renderOrder = 11;
    accretionGroup.add(points);
    innerDiskField = { geometry, points, uniforms };
  }

  function buildAbsorptionDisk() {
    const material = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: true,
      blending: THREE.NormalBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uStarRadius: { value: 0.4 },
      },
      vertexShader: `
        varying vec2 vDiskPoint;
        void main() {
          vDiskPoint = position.xy;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uStarRadius;
        varying vec2 vDiskPoint;
        ${noiseGlsl}
        void main() {
          float radius = length(vDiskPoint);
          if (radius < uStarRadius * 1.035 || radius > 7.35) discard;
          float angle = atan(vDiskPoint.y, vDiskPoint.x);
          float annulus = smoothstep(uStarRadius * 1.035, uStarRadius * 1.2 + 0.16, radius) *
            (1.0 - smoothstep(6.15, 7.35, radius));
          float distortion = noise3(vec3(
            vDiskPoint * 0.56,
            uTime * 0.034
          ));
          float fineDust =
            sin(vDiskPoint.x * 3.7 + sin(vDiskPoint.y * 1.9 - uTime * 0.1) * 2.2) * 0.28 +
            sin(vDiskPoint.y * 5.2 - vDiskPoint.x * 1.35 + uTime * 0.07) * 0.22 + 0.5;
          float spiralCoordinate =
            angle * 3.0 - log(max(radius, 0.28)) * 8.6 - uTime * 0.19 + distortion * 2.7;
          float primaryLane = pow(max(0.0, sin(spiralCoordinate) * 0.5 + 0.5), 7.0);
          float secondaryLane = pow(max(0.0,
            sin(spiralCoordinate * 0.67 + angle * 2.0 + 1.8) * 0.5 + 0.5
          ), 11.0) * 0.62;
          float fractured = smoothstep(0.28, 0.72, fineDust + distortion * 0.34);
          float innerWeight = 1.0 - smoothstep(2.0, 6.8, radius);
          float absorption = max(primaryLane, secondaryLane) * fractured * annulus;
          vec3 coldViolet = vec3(0.004, 0.001, 0.017);
          vec3 coldCyan = vec3(0.002, 0.035, 0.047);
          vec3 color = mix(
            coldViolet,
            coldCyan,
            sin(angle * 2.0 - uTime * 0.045) * 0.5 + 0.5
          );
          float alpha = uPresence * absorption * (0.075 + innerWeight * 0.135);
          if (alpha < 0.002) discard;
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
    const mesh = new THREE.Mesh(new THREE.CircleGeometry(7.6, 96), material);
    mesh.rotation.x = -Math.PI * 0.5;
    mesh.position.y = 0.006;
    mesh.frustumCulled = false;
    mesh.renderOrder = 10;
    accretionGroup.add(mesh);
    absorptionDisk = { mesh, material };
  }

  function makeJetSheathMaterial(side) {
    return new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uShock: { value: 0 },
        uSide: { value: side },
      },
      vertexShader: `
        varying vec2 vUv;
        varying vec3 vPosition;
        void main() {
          vUv = uv;
          vPosition = position;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uShock;
        uniform float uSide;
        varying vec2 vUv;
        varying vec3 vPosition;
        ${noiseGlsl}
        void main() {
          float angle = atan(vPosition.z, vPosition.x);
          float axial = smoothstep(0.0, 0.09, vUv.y) *
            (1.0 - smoothstep(0.58, 1.0, vUv.y));
          float tornEdge = noise3(vec3(
            cos(angle) * 2.2 + vUv.y * 3.4,
            sin(angle) * 2.2 - uTime * 0.18,
            vUv.y * 7.0 + uTime * 0.11
          ));
          float filaments = pow(max(0.0,
            sin(angle * 5.0 + vUv.y * 24.0 - uTime * (1.2 + uSide * 0.17)) * 0.5 +
            tornEdge * 0.82 - 0.28
          ), 2.4);
          vec3 cyan = vec3(0.02, 0.77, 0.92);
          vec3 rose = vec3(1.0, 0.025, 0.34);
          vec3 gold = vec3(1.0, 0.56, 0.12);
          vec3 color = mix(cyan, rose, uSide * 0.5 + 0.5);
          color = mix(color, gold, (1.0 - vUv.y) * (0.22 + uShock * 0.42));
          float alpha = uPresence * axial * filaments * (0.035 + uShock * 0.085);
          if (alpha < 0.002) discard;
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
  }

  function buildJets() {
    const count = viewport.narrow ? 620 : viewport.compact ? 900 : 1400;
    const jetRandom = mulberry32(0xb1701a7);
    const jet = new Float32Array(count * 4);
    const motion = new Float32Array(count * 4);

    for (let index = 0; index < count; index += 1) {
      const offset = index * 4;
      jet[offset] = index % 2 === 0 ? -1 : 1;
      jet[offset + 1] = jetRandom() * Math.PI * 2;
      jet[offset + 2] = Math.pow(jetRandom(), 0.62);
      jet[offset + 3] = 0.07 + jetRandom() * 0.065;
      motion[offset] = jetRandom();
      motion[offset + 1] = (0.72 + jetRandom() * 1.46) * (jetRandom() < 0.5 ? -1 : 1);
      motion[offset + 2] = 0.34 + jetRandom() * 0.66;
      motion[offset + 3] = jetRandom();
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute("position", new THREE.BufferAttribute(new Float32Array(count * 3), 3));
    geometry.setAttribute("aJet", new THREE.BufferAttribute(jet, 4));
    geometry.setAttribute("aMotion", new THREE.BufferAttribute(motion, 4));
    geometry.boundingSphere = new THREE.Sphere(new THREE.Vector3(), 12);
    const material = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uShock: { value: 0 },
        uBaseRadius: { value: 0.4 },
        uPixelRatio: { value: renderer.getPixelRatio() },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uShock;
        uniform float uBaseRadius;
        uniform float uPixelRatio;
        attribute vec4 aJet;
        attribute vec4 aMotion;
        varying vec3 vColor;
        varying float vAlpha;
        void main() {
          float side = aJet.x;
          float cycle = fract(aMotion.x + uTime * aJet.w);
          float launch = smoothstep(0.0, 0.075, cycle);
          float dissolve = 1.0 - smoothstep(0.68, 1.0, cycle);
          float distance = uBaseRadius + pow(cycle, 0.72) * (5.8 + aMotion.w * 2.4);
          float plumeRadius = (0.045 + distance * 0.078) * (0.22 + aJet.z * 0.92);
          float angle = aJet.y + distance * aMotion.y + uTime * side * 0.31;
          vec3 transformed = vec3(
            cos(angle) * plumeRadius,
            side * distance,
            sin(angle) * plumeRadius
          );
          transformed.x += sin(distance * 1.8 - uTime * 0.7 + aJet.y) * distance * 0.012;
          transformed.z += cos(distance * 1.45 + uTime * 0.56 + aJet.y) * distance * 0.014;
          vec4 mvPosition = modelViewMatrix * vec4(transformed, 1.0);
          float baseHeat = 1.0 - smoothstep(uBaseRadius, uBaseRadius + 2.7, distance);
          vec3 cyan = vec3(0.03, 0.84, 0.94);
          vec3 rose = vec3(1.0, 0.025, 0.34);
          vec3 gold = vec3(1.0, 0.66, 0.18);
          vColor = mix(cyan, rose, side * 0.5 + 0.5);
          vColor = mix(vColor, gold, baseHeat * (0.48 + uShock * 0.28));
          vAlpha = uPresence * launch * dissolve *
            (0.16 + aMotion.z * 0.62) * (0.62 + uShock * 0.52);
          gl_PointSize = (0.9 + aMotion.z * 3.0 + baseHeat * 1.35) * uPixelRatio *
            (34.0 / max(4.0, -mvPosition.z));
          gl_Position = projectionMatrix * mvPosition;
        }
      `,
      fragmentShader: `
        varying vec3 vColor;
        varying float vAlpha;
        void main() {
          vec2 point = gl_PointCoord - 0.5;
          float radius = length(point);
          if (radius > 0.5) discard;
          float glow = 1.0 - smoothstep(0.04, 0.5, radius);
          float core = pow(glow, 5.0);
          gl_FragColor = vec4(vColor + core * 0.46, vAlpha * glow);
        }
      `,
    });
    const points = new THREE.Points(geometry, material);
    points.frustumCulled = false;
    points.renderOrder = 6;

    const height = viewport.narrow ? 5.2 : 6.7;
    const plumeGeometry = new THREE.CylinderGeometry(0.58, 0.065, height, 24, 36, true);
    const plumeRecords = [-1, 1].map((side) => {
      const plumeMaterial = makeJetSheathMaterial(side);
      const mesh = new THREE.Mesh(plumeGeometry, plumeMaterial);
      if (side < 0) mesh.rotation.z = Math.PI;
      mesh.frustumCulled = false;
      mesh.renderOrder = 5;
      return { side, mesh, material: plumeMaterial, height };
    });

    const group = new THREE.Group();
    group.add(points, ...plumeRecords.map((record) => record.mesh));
    accretionGroup.add(group);
    jetField = { group, points, material, plumeRecords };
  }

  function makeFilamentMaterial(color) {
    const material = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uCollapse: { value: 0 },
        uAlpha: { value: 0 },
        uColor: { value: new THREE.Color(color) },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uCollapse;
        attribute float aPath;
        varying float vAlpha;

        void main() {
          vec3 transformed = position;
          float turn = uTime * (0.035 + aPath * 0.012);
          float c = cos(turn);
          float s = sin(turn);
          transformed.xz = mat2(c, -s, s, c) * transformed.xz;
          float head = smoothstep(aPath - 0.1, aPath + 0.08, uCollapse);
          float tail = 0.28 + 0.72 * smoothstep(0.0, 0.8, aPath);
          vAlpha = head * tail;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(transformed, 1.0);
        }
      `,
      fragmentShader: `
        uniform vec3 uColor;
        uniform float uAlpha;
        varying float vAlpha;
        void main() {
          gl_FragColor = vec4(uColor, uAlpha * vAlpha);
        }
      `,
    });
    filamentMaterials.push(material);
    return material;
  }

  function buildFilaments() {
    const colors = [0x44ece7, 0xf71375, 0xffb45e, 0x917aff];
    const lanes = viewport.compact ? 12 : 18;
    for (let lane = 0; lane < lanes; lane += 1) {
      const segments = 150;
      const positions = new Float32Array(segments * 3);
      const path = new Float32Array(segments);
      const corridor = lane / lanes * Math.PI * 2 + gaussian(0.2);
      const radius0 = 12 + random() * 10;
      const endRadius = 1.6 + random() * 3.3;
      const turns = 1.8 + random() * 2.7;
      const startY = gaussian(3.2);

      for (let point = 0; point < segments; point += 1) {
        const t = point / (segments - 1);
        const curved = smoothstep(0, 1, t);
        const radius = THREE.MathUtils.lerp(radius0, endRadius, Math.pow(curved, 0.72));
        const angle = corridor + curved * turns * Math.PI * 2;
        const offset = point * 3;
        positions[offset] = Math.cos(angle) * radius;
        positions[offset + 1] = THREE.MathUtils.lerp(startY, Math.sin(angle * 2.1) * 0.08, curved);
        positions[offset + 2] = Math.sin(angle) * radius;
        path[point] = t;
      }

      const geometry = new THREE.BufferGeometry();
      geometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));
      geometry.setAttribute("aPath", new THREE.BufferAttribute(path, 1));
      const line = new THREE.Line(geometry, makeFilamentMaterial(colors[lane % colors.length]));
      line.frustumCulled = false;
      line.renderOrder = 2;
      accretionGroup.add(line);
    }
  }

  const noiseGlsl = `
    float hash31(vec3 p) {
      p = fract(p * 0.1031);
      p += dot(p, p.yzx + 33.33);
      return fract((p.x + p.y) * p.z);
    }

    float noise3(vec3 p) {
      vec3 i = floor(p);
      vec3 f = fract(p);
      f = f * f * (3.0 - 2.0 * f);
      return mix(
        mix(mix(hash31(i), hash31(i + vec3(1,0,0)), f.x),
            mix(hash31(i + vec3(0,1,0)), hash31(i + vec3(1,1,0)), f.x), f.y),
        mix(mix(hash31(i + vec3(0,0,1)), hash31(i + vec3(1,0,1)), f.x),
            mix(hash31(i + vec3(0,1,1)), hash31(i + vec3(1,1,1)), f.x), f.y), f.z
      );
    }

    float fbm(vec3 p) {
      float value = 0.0;
      float amplitude = 0.5;
      for (int octave = 0; octave < 5; octave++) {
        value += noise3(p) * amplitude;
        p = p * 2.03 + vec3(17.1, 9.2, 13.7);
        amplitude *= 0.5;
      }
      return value;
    }
  `;

  function buildAccretionKnots() {
    const count = viewport.narrow ? 6 : viewport.compact ? 7 : 9;
    const knotRandom = mulberry32(0x9f31c5a7);
    const geometry = new THREE.IcosahedronGeometry(0.34, viewport.compact ? 3 : 4);
    const haloGeometry = new THREE.PlaneGeometry(1.65, 1.65);
    const group = new THREE.Group();
    const records = [];

    for (let index = 0; index < count; index += 1) {
      const material = new THREE.ShaderMaterial({
        transparent: true,
        depthWrite: false,
        depthTest: false,
        blending: THREE.AdditiveBlending,
        uniforms: {
          uTime: { value: 0 },
          uAlpha: { value: 0 },
          uCompression: { value: 0 },
          uSeed: { value: 3.7 + index * 8.13 },
        },
        vertexShader: `
          uniform float uTime;
          uniform float uCompression;
          uniform float uSeed;
          varying vec3 vNormalObject;
          varying vec3 vNormalView;
          ${noiseGlsl}
          void main() {
            vNormalObject = normalize(normal);
            vNormalView = normalize(normalMatrix * normal);
            float turbulence = fbm(normal * 2.65 + vec3(uSeed, uTime * 0.07, -uTime * 0.045));
            float tidalRidge = sin(normal.y * 7.0 + normal.x * 5.0 + uTime * 0.8 + uSeed) * 0.055;
            float instability = mix(1.42, 0.82, uCompression);
            vec3 displaced = position + normal * ((turbulence - 0.46) * 0.18 + tidalRidge) * instability;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(displaced, 1.0);
          }
        `,
        fragmentShader: `
          uniform float uTime;
          uniform float uAlpha;
          uniform float uCompression;
          uniform float uSeed;
          varying vec3 vNormalObject;
          varying vec3 vNormalView;
          ${noiseGlsl}
          void main() {
            vec3 n = normalize(vNormalObject);
            float broad = fbm(n * 3.7 + vec3(uSeed, uTime * 0.09, -uTime * 0.055));
            float cells = fbm(n * 9.2 - vec3(uTime * 0.07, uSeed * 0.31, uTime * 0.12));
            float density = smoothstep(0.18, 0.78, broad * 0.62 + cells * 0.38 + uCompression * 0.12);
            float rim = pow(1.0 - abs(normalize(vNormalView).z), 2.1);
            vec3 cold = mix(vec3(0.018, 0.42, 0.78), vec3(0.72, 0.018, 0.32), broad);
            vec3 hot = mix(vec3(0.94, 0.018, 0.16), vec3(1.0, 0.29, 0.035), cells);
            vec3 color = mix(cold, hot, smoothstep(0.12, 0.82, uCompression));
            color = mix(color, vec3(1.0, 0.7, 0.18),
              smoothstep(0.48, 0.96, cells + broad * 0.35) * uCompression);
            color += rim * mix(vec3(0.03, 0.45, 0.56), vec3(0.72, 0.015, 0.24), uCompression) * 0.42;
            float alpha = uAlpha * (0.32 + density * 0.68) * (1.0 - rim * 0.08);
            if (alpha < 0.01) discard;
            gl_FragColor = vec4(color * (0.78 + uCompression * 0.46), alpha);
          }
        `,
      });

      const mesh = new THREE.Mesh(geometry, material);
      mesh.renderOrder = 6;
      const haloMaterial = new THREE.ShaderMaterial({
        transparent: true,
        depthWrite: false,
        depthTest: false,
        blending: THREE.AdditiveBlending,
        uniforms: {
          uTime: { value: 0 },
          uAlpha: { value: 0 },
          uCompression: { value: 0 },
          uSeed: { value: 3.7 + index * 8.13 },
        },
        vertexShader: `
          varying vec2 vUv;
          void main() {
            vUv = uv;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
          }
        `,
        fragmentShader: `
          uniform float uTime;
          uniform float uAlpha;
          uniform float uCompression;
          uniform float uSeed;
          varying vec2 vUv;
          ${noiseGlsl}
          void main() {
            vec2 p = (vUv - 0.5) * 2.0;
            float radius = length(p);
            float hazeNoise = fbm(vec3(p * 2.7, uSeed + uTime * 0.035));
            float boundary = 0.68 + (hazeNoise - 0.5) * 0.28;
            float haze = 1.0 - smoothstep(boundary - 0.24, boundary + 0.18, radius);
            float inner = 1.0 - smoothstep(0.08, 0.82, radius);
            vec3 cold = mix(vec3(0.015, 0.68, 0.82), vec3(0.86, 0.018, 0.42), hazeNoise);
            vec3 hot = mix(vec3(1.0, 0.025, 0.18), vec3(1.0, 0.48, 0.07), hazeNoise);
            vec3 color = mix(cold, hot, smoothstep(0.1, 0.88, uCompression));
            float alpha = uAlpha * haze * (0.075 + inner * 0.095 + hazeNoise * 0.07);
            if (alpha < 0.004) discard;
            gl_FragColor = vec4(color, alpha);
          }
        `,
      });
      const halo = new THREE.Mesh(haloGeometry, haloMaterial);
      halo.renderOrder = 5;
      group.add(halo);
      group.add(mesh);
      const delay = count === 1 ? 0 : index / (count - 1);
      const impactSlot = Math.min(3, Math.round(delay * 3));
      const targetAngles = [Math.PI, 0, Math.PI * 0.46, 0.08];
      const targetHeights = [0.28, 0.38, -0.42, -0.18];
      const turns = 1.2 + knotRandom() * 2.05;
      const targetAngle = targetAngles[impactSlot] + (knotRandom() - 0.5) * 0.24;
      records.push({
        mesh,
        material,
        halo,
        haloMaterial,
        delay,
        startAngle: targetAngle - turns * Math.PI * 2,
        targetAngle,
        startRadius: 5.2 + knotRandom() * 7.1,
        startY: (knotRandom() - 0.5) * 4.6,
        targetY: targetHeights[impactSlot] + (knotRandom() - 0.5) * 0.14,
        turns,
        size: 0.58 + knotRandom() * 0.46,
        stretchX: 0.62 + knotRandom() * 1.24,
        stretchY: 0.52 + knotRandom() * 0.9,
        stretchZ: 0.58 + knotRandom() * 1.06,
        phase: knotRandom() * Math.PI * 2,
        spin: (knotRandom() - 0.5) * 2.1,
      });
    }

    solarAnchor.add(group);
    accretionKnots = { group, records };
  }

  function updateAccretionKnots(progress, time, moving, starScale) {
    accretionKnots.records.forEach((record) => {
      const arrival = 0.2 + record.delay * 0.62;
      const start = Math.max(0.025, arrival - 0.28);
      const end = arrival + 0.055;
      const local = clamp01((progress - start) / (end - start));

      if (!moving || local <= 0 || local >= 1) {
        record.mesh.scale.setScalar(0);
        record.halo.scale.setScalar(0);
        record.material.uniforms.uAlpha.value = 0;
        record.haloMaterial.uniforms.uAlpha.value = 0;
        return;
      }

      const eased = smoothstep(0, 1, local);
      const targetY = record.targetY * starScale;
      const surfaceRadius = Math.max(0.24, starScale * 1.56);
      const targetRadius = Math.sqrt(Math.max(0.04, surfaceRadius * surfaceRadius - targetY * targetY));
      const radius = THREE.MathUtils.lerp(record.startRadius, targetRadius, Math.pow(eased, 0.68));
      const angle = record.startAngle + record.turns * Math.PI * 2 * eased + time * 0.045 * (1 - eased);
      const y = THREE.MathUtils.lerp(record.startY, targetY, smoothstep(0.06, 0.94, eased));
      const compression = smoothstep(0.28, 0.92, local);
      const emergence = smoothstep(0, 0.12, local);
      const fusionFade = 1 - smoothstep(0.82, 1, local);
      const tidal = 1 - compression;
      const size = record.size * (0.72 + compression * 0.28) * emergence * fusionFade;

      record.mesh.position.set(Math.cos(angle) * radius, y, Math.sin(angle) * radius);
      record.halo.position.copy(record.mesh.position);
      record.mesh.rotation.set(
        record.phase + time * record.spin * 0.16,
        angle + time * record.spin * 0.11,
        record.phase * 0.6 - time * record.spin * 0.13,
      );
      record.mesh.scale.set(
        size * THREE.MathUtils.lerp(1, record.stretchX, tidal),
        size * THREE.MathUtils.lerp(1, record.stretchY, tidal),
        size * THREE.MathUtils.lerp(1, record.stretchZ, tidal),
      );
      record.halo.scale.setScalar(size * (1.12 + tidal * 0.18));
      record.halo.rotation.z = record.phase + time * record.spin * 0.025;
      record.material.uniforms.uTime.value = time;
      record.material.uniforms.uCompression.value = compression;
      record.material.uniforms.uAlpha.value =
        (0.035 + compression * 0.575) * emergence * fusionFade;
      record.haloMaterial.uniforms.uTime.value = time;
      record.haloMaterial.uniforms.uCompression.value = compression;
      record.haloMaterial.uniforms.uAlpha.value =
        (1.12 - compression * 0.24) * emergence * fusionFade;
    });
    accretionKnots.group.rotation.y = 0;
  }

  function buildProtoFragments() {
    const count = viewport.narrow ? 38 : viewport.compact ? 52 : 76;
    const geometry = new THREE.IcosahedronGeometry(0.13, 1);
    const material = new THREE.MeshBasicMaterial({
      color: 0xffffff,
      transparent: true,
      opacity: 0.68,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
    });
    const mesh = new THREE.InstancedMesh(geometry, material, count);
    mesh.instanceMatrix.setUsage(THREE.DynamicDrawUsage);
    mesh.frustumCulled = false;
    mesh.renderOrder = 7;
    const palette = [0xff234f, 0xff5338, 0xff8530, 0xffb04a, 0x55d8d3];
    const records = [];

    for (let index = 0; index < count; index += 1) {
      const finalAngle = random() * Math.PI * 2;
      const turns = 1.15 + random() * 2.25;
      const targetY = gaussian(0.68);
      const targetRadius = Math.sqrt(Math.max(0.38, 2.42 - targetY * targetY));
      const color = new THREE.Color(palette[index % palette.length]);
      color.offsetHSL(gaussian(0.008), gaussian(0.02), gaussian(0.035));
      records.push({
        startAngle: finalAngle - turns * Math.PI * 2,
        finalAngle,
        turns,
        startRadius: 3.8 + random() * 7.2,
        targetRadius,
        startY: gaussian(2.5),
        targetY,
        delay: random(),
        scale: 0.44 + random() * 0.9,
        stretchX: 0.62 + random() * 1.35,
        stretchY: 0.48 + random() * 0.94,
        stretchZ: 0.58 + random() * 1.18,
        phase: random() * Math.PI * 2,
        spin: gaussian(1.3),
      });
      mesh.setColorAt(index, color);
    }
    if (mesh.instanceColor) mesh.instanceColor.needsUpdate = true;
    solarAnchor.add(mesh);

    const shellMaterial = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPulse: { value: 0 },
      },
      vertexShader: `
        varying vec3 vNormalView;
        varying vec3 vNormalObject;
        void main() {
          vNormalView = normalize(normalMatrix * normal);
          vNormalObject = normalize(normal);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPulse;
        varying vec3 vNormalView;
        varying vec3 vNormalObject;
        void main() {
          float rim = pow(1.0 - abs(vNormalView.z), 2.2);
          float fracture =
            sin(vNormalObject.y * 18.0 + uTime * 1.7) * 0.34 +
            sin(vNormalObject.x * 27.0 - uTime * 1.1) * 0.2 + 0.46;
          float veins = smoothstep(0.45, 0.78, fracture);
          vec3 rose = vec3(1.0, 0.03, 0.27);
          vec3 gold = vec3(1.0, 0.67, 0.12);
          vec3 color = mix(rose, gold, rim + veins * 0.35);
          float alpha = uPulse * (rim * 0.36 + veins * 0.065);
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
    ignitionShell = new THREE.Mesh(new THREE.SphereGeometry(1.92, 56, 36), shellMaterial);
    ignitionShell.renderOrder = 9;
    solarAnchor.add(ignitionShell);
    protoField = { mesh, material, records };
  }

  function updateProtoFragments(progress, time, moving, starScale) {
    const dummy = updateProtoFragments.dummy || (updateProtoFragments.dummy = new THREE.Object3D());
    protoField.records.forEach((record, index) => {
      const start = 0.08 + record.delay * 0.19;
      const end = 0.61 + record.delay * 0.16;
      const local = clamp01((progress - start) / (end - start));
      if (!moving || local <= 0 || local >= 1) {
        dummy.scale.setScalar(0);
      } else {
        const eased = smoothstep(0, 1, local);
        const radius = THREE.MathUtils.lerp(
          record.startRadius,
          record.targetRadius * starScale,
          Math.pow(eased, 0.72),
        );
        const angle = record.startAngle + record.turns * Math.PI * 2 * eased + time * 0.055 * (1 - eased);
        const y = THREE.MathUtils.lerp(
          record.startY,
          record.targetY * starScale,
          smoothstep(0.04, 0.94, eased),
        );
        dummy.position.set(Math.cos(angle) * radius, y, Math.sin(angle) * radius);
        dummy.rotation.set(
          record.phase + time * record.spin * 0.42,
          time * 0.31 + local * 4.2,
          record.phase - time * record.spin * 0.27,
        );
        const arrival = 1 - smoothstep(0.72, 1, local);
        const emergence = smoothstep(0, 0.12, local);
        const compression = 0.72 + smoothstep(0.35, 0.82, local) * 0.58;
        const flicker = 0.76 + Math.sin(time * 7.2 + record.phase) * 0.24;
        const size = record.scale * emergence * arrival * compression * flicker;
        dummy.scale.set(size * record.stretchX, size * record.stretchY, size * record.stretchZ);
      }
      dummy.updateMatrix();
      protoField.mesh.setMatrixAt(index, dummy.matrix);
    });
    protoField.mesh.instanceMatrix.needsUpdate = true;
  }

  function buildProtoVolume() {
    const material = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: true,
      side: THREE.FrontSide,
      blending: THREE.NormalBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uDensity: { value: 0 },
        uMass: { value: 0 },
        uIgnition: { value: 0 },
        uCompression: { value: 0 },
        uShockPhase: { value: 0 },
        uShockPulse: { value: 0 },
        uImpacts: { value: new THREE.Vector4() },
        uCameraLocal: { value: new THREE.Vector3(0, 0, 10) },
      },
      vertexShader: `
        varying vec3 vLocalPosition;
        void main() {
          vLocalPosition = position;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uDensity;
        uniform float uMass;
        uniform float uIgnition;
        uniform float uCompression;
        uniform float uShockPhase;
        uniform float uShockPulse;
        uniform vec4 uImpacts;
        uniform vec3 uCameraLocal;
        varying vec3 vLocalPosition;
        ${noiseGlsl}

        const float VOLUME_RADIUS = 1.84;

        mat2 rotate2(float angle) {
          float sine = sin(angle);
          float cosine = cos(angle);
          return mat2(cosine, -sine, sine, cosine);
        }

        vec3 gasSample(vec3 point) {
          float radius = length(point) / VOLUME_RADIUS;
          vec3 direction = normalize(point + vec3(0.0001));
          vec3 folded = point;
          folded.xz = rotate2(
            uTime * (0.075 + (1.0 - radius) * 0.11) + (1.0 - radius) * 2.15
          ) * folded.xz;

          vec3 drift = vec3(uTime * 0.045, -uTime * 0.028, uTime * 0.036);
          float billow = noise3(folded * 1.42 + drift);
          float cells = noise3(folded * 3.25 - drift * 1.7 + vec3(8.2, 3.7, 1.4));
          float threads = noise3(folded * 7.1 + drift * 2.2 + vec3(2.4, 9.1, 5.6));
          float turbulence = billow * 0.57 + cells * 0.3 + threads * 0.13;

          float breathing = sin(direction.y * 8.0 + direction.x * 5.0 + uTime * 0.7) * 0.025;
          float raggedEdge = 0.74 + billow * 0.23 + cells * 0.07 + breathing;
          float boundary = 1.0 - smoothstep(raggedEdge, raggedEdge + 0.13, radius);
          float core = 1.0 - smoothstep(0.08, 0.82, radius);
          float compressedCore = pow(max(0.0, core), 2.8) * uCompression;
          float shockRadius = mix(0.08, 1.04, smoothstep(0.0, 1.0, uShockPhase));
          float shockFront = (1.0 - smoothstep(
            0.035,
            0.13,
            abs(radius - shockRadius)
          )) * uShockPulse;
          float knotThreshold = mix(0.66, 0.43, uMass);
          float knots = smoothstep(knotThreshold, knotThreshold + 0.16, turbulence + core * 0.2);
          float cavities = smoothstep(0.08, 0.34, abs(cells - billow));

          vec4 impactAlignment = vec4(
            max(0.0, dot(direction, normalize(vec3(-0.72, 0.18, 0.67)))),
            max(0.0, dot(direction, normalize(vec3(0.58, 0.42, 0.69)))),
            max(0.0, dot(direction, normalize(vec3(0.15, -0.72, 0.68)))),
            max(0.0, dot(direction, normalize(vec3(0.75, -0.22, 0.62))))
          );
          float impact = dot(uImpacts, pow(impactAlignment, vec4(12.0))) *
            smoothstep(0.34, 0.8, radius);

          float ignitionSignal = max(
            dot(direction, normalize(vec3(-0.56, 0.42, 0.72))),
            max(
              dot(direction, normalize(vec3(0.61, 0.12, 0.78))),
              dot(direction, normalize(vec3(0.08, -0.67, 0.74)))
            )
          ) + (billow - 0.5) * 0.48;
          float ignitionSites = smoothstep(mix(1.05, 0.08, uIgnition), 1.1, ignitionSignal);

          float density = boundary * (
            0.055 + knots * (0.62 + uMass * 0.4) + core * (0.19 + uMass * 0.66)
          );
          density *= 0.58 + cavities * 0.42;
          density += impact * boundary * 0.72;
          density += boundary * (compressedCore * 0.84 + shockFront * 0.52);
          float heat = clamp(
            core * (0.24 + uMass * 0.84) +
            knots * 0.18 + ignitionSites * (0.38 + uIgnition * 0.82) + impact * 0.92 +
            compressedCore * 1.2 + shockFront * 1.08,
            0.0,
            1.45
          );
          return vec3(max(0.0, density), heat, billow);
        }

        void main() {
          vec3 rayOrigin = uCameraLocal;
          vec3 rayDirection = normalize(vLocalPosition - rayOrigin);
          float projection = dot(rayOrigin, rayDirection);
          float discriminant = projection * projection -
            (dot(rayOrigin, rayOrigin) - VOLUME_RADIUS * VOLUME_RADIUS);
          if (discriminant <= 0.0 || uPresence <= 0.001) discard;

          float root = sqrt(discriminant);
          float entry = max(0.0, -projection - root);
          float exit = -projection + root;
          float segment = max(0.0, exit - entry);
          float stepLength = segment / 26.0;
          float jitter = fract(sin(dot(gl_FragCoord.xy, vec2(12.9898, 78.233))) * 43758.5453);
          vec3 accumulated = vec3(0.0);
          float transmittance = 1.0;

          for (int index = 0; index < 26; index++) {
            float distanceAlongRay = entry + (float(index) + jitter) * stepLength;
            vec3 point = rayOrigin + rayDirection * distanceAlongRay;
            vec3 gas = gasSample(point);
            float extinction = gas.x * uDensity * stepLength * 1.34;
            float sampleAlpha = 1.0 - exp(-extinction);
            float radius = length(point) / VOLUME_RADIUS;
            float outer = smoothstep(0.28, 0.92, radius);
            vec3 coldGas = mix(
              vec3(0.025, 0.4, 0.54),
              vec3(0.72, 0.012, 0.16),
              gas.z
            );
            vec3 ember = mix(vec3(0.9, 0.018, 0.055), vec3(1.0, 0.29, 0.025), gas.y);
            vec3 whiteHot = vec3(1.0, 0.88, 0.48);
            vec3 color = mix(coldGas, ember, smoothstep(0.12, 0.66, gas.y));
            color = mix(color, whiteHot, smoothstep(0.74, 1.35, gas.y));
            color += outer * vec3(0.025, 0.012, 0.055);
            float emissive = 0.86 + gas.y * 1.34 + uIgnition * 0.28;
            accumulated += transmittance * color * sampleAlpha * emissive;
            transmittance *= 1.0 - sampleAlpha;
            if (transmittance < 0.025) break;
          }

          float alpha = (1.0 - transmittance) * uPresence;
          if (alpha < 0.008) discard;
          vec3 color = accumulated / max(0.035, 1.0 - transmittance);
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });

    protoVolume = new THREE.Mesh(
      new THREE.SphereGeometry(1.84, viewport.compact ? 40 : 56, viewport.compact ? 28 : 40),
      material,
    );
    protoVolume.frustumCulled = false;
    protoVolume.renderOrder = 7;
    solarAnchor.add(protoVolume);
  }

  function buildStar() {
    const gravityMaterial = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uFocus: { value: 0 },
      },
      vertexShader: `
        varying vec2 vUv;
        void main() {
          vUv = uv;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uFocus;
        varying vec2 vUv;
        void main() {
          vec2 p = vUv - 0.5;
          float radius = length(p);
          float angle = atan(p.y, p.x);
          float boundary = 1.0 - smoothstep(0.01, 0.038, abs(radius - 0.205));
          float interference =
            sin(angle * 3.0 - uTime * 0.27) * 0.62 +
            sin(angle * 7.0 + uTime * 0.19) * 0.38;
          float fragments = smoothstep(0.08, 0.62, interference);
          vec3 cyan = vec3(0.02, 0.83, 0.9);
          vec3 rose = vec3(1.0, 0.03, 0.36);
          vec3 color = mix(cyan, rose, sin(angle + uTime * 0.08) * 0.5 + 0.5);
          float alpha = boundary * fragments * 0.43 * uFocus;
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
    gravityWell = new THREE.Mesh(new THREE.PlaneGeometry(5.4, 5.4), gravityMaterial);
    gravityWell.scale.y = 0.54;
    gravityWell.rotation.z = -0.17;
    gravityWell.renderOrder = 4;
    solarAnchor.add(gravityWell);

    buildAccretionKnots();
    buildProtoFragments();
    buildProtoVolume();

    const surfaceDetail = useStylizedSun
      ? viewport.compact ? 2 : 3
      : viewport.compact ? 5 : 6;
    const geometry = new THREE.IcosahedronGeometry(1.72, surfaceDetail);
    if (useSynthwaveSun) geometry.computeVertexNormals();
    const surfaceVertexCount = geometry.attributes.position.count;
    if (useSynthwaveSun) {
      const surfaceRandom = mulberry32(0x50facade);
      const barycentrics = new Float32Array(surfaceVertexCount * 3);
      const facetCharges = new Float32Array(surfaceVertexCount);
      const facetPhases = new Float32Array(surfaceVertexCount);
      for (let vertex = 0; vertex < surfaceVertexCount; vertex += 3) {
        const charge = surfaceRandom();
        const phase = surfaceRandom();
        for (let corner = 0; corner < 3; corner += 1) {
          barycentrics[(vertex + corner) * 3 + corner] = 1;
          facetCharges[vertex + corner] = charge;
          facetPhases[vertex + corner] = phase;
        }
      }
      geometry.setAttribute("aBarycentric", new THREE.BufferAttribute(barycentrics, 3));
      geometry.setAttribute("aFacetCharge", new THREE.BufferAttribute(facetCharges, 1));
      geometry.setAttribute("aFacetPhase", new THREE.BufferAttribute(facetPhases, 1));
    }
    const material = new THREE.ShaderMaterial({
      defines: sunDefines,
      transparent: true,
      depthWrite: true,
      depthTest: true,
      blending: THREE.NormalBlending,
      uniforms: {
        uTime: { value: 0 },
        uIgnition: { value: 0 },
        uAssembly: { value: 0 },
        uSurfacePresence: { value: 0 },
        uMass: { value: 0 },
        uImpacts: { value: new THREE.Vector4() },
        uEclipsePhase: { value: 0 },
        uEclipsePresence: { value: 0 },
      },
      vertexShader: `
        uniform float uTime;
        uniform float uIgnition;
        uniform float uAssembly;
        uniform float uMass;
        uniform vec4 uImpacts;
        varying vec3 vRadialNormal;
        varying vec2 vEclipsePoint;
        varying vec3 vPositionObject;
#ifdef HELIOGENESIS_SYNTHWAVE
        attribute vec3 aBarycentric;
        attribute float aFacetCharge;
        attribute float aFacetPhase;
        varying vec3 vFacetNormal;
        varying vec3 vBarycentric;
        varying float vFacetCharge;
        varying float vFacetPhase;
#endif
        ${noiseGlsl}
        void main() {
          vec3 radialNormal = normalize(position);
          vRadialNormal = radialNormal;
#ifdef HELIOGENESIS_SYNTHWAVE
          vFacetNormal = normalize(normal);
          vBarycentric = aBarycentric;
          vFacetCharge = aFacetCharge;
          vFacetPhase = aFacetPhase;
#endif
          float turbulence = fbm(
            radialNormal * 3.4 + vec3(uTime * 0.055, -uTime * 0.036, uTime * 0.024)
          );
          float pulse = sin(uTime * 0.82 + radialNormal.y * 8.0) * 0.018;
          float unstableSurface = mix(1.78, 1.0, uAssembly);
          vec4 impactAlignment = vec4(
            max(0.0, dot(radialNormal, normalize(vec3(-0.72, 0.18, 0.67)))),
            max(0.0, dot(radialNormal, normalize(vec3(0.58, 0.42, 0.69)))),
            max(0.0, dot(radialNormal, normalize(vec3(0.15, -0.72, 0.68)))),
            max(0.0, dot(radialNormal, normalize(vec3(0.75, -0.22, 0.62))))
          );
          vec4 impactCores = pow(impactAlignment, vec4(18.0));
          vec4 impactRings = 1.0 - smoothstep(vec4(0.025), vec4(0.11), abs(impactAlignment - 0.72));
          float impactBulge = dot(uImpacts, impactCores) * 0.4;
          float impactRipple = dot(uImpacts, impactRings) * 0.058;
          vec3 displaced = position + radialNormal *
            ((turbulence - 0.48) * 0.3 * unstableSurface +
              pulse * (0.62 + uAssembly * 0.38) + impactBulge + impactRipple);
          vPositionObject = displaced;
          vec4 viewPosition = modelViewMatrix * vec4(displaced, 1.0);
          vec4 viewCenter = modelViewMatrix * vec4(0.0, 0.0, 0.0, 1.0);
          float viewRadius = length(modelViewMatrix[0].xyz) * 1.72;
          vec4 clipPosition = projectionMatrix * viewPosition;
          vec4 clipCenter = projectionMatrix * viewCenter;
          vec4 clipRight = projectionMatrix * vec4(viewCenter.xyz + vec3(viewRadius, 0.0, 0.0), 1.0);
          vec4 clipUp = projectionMatrix * vec4(viewCenter.xyz + vec3(0.0, viewRadius, 0.0), 1.0);
          vec2 centerNdc = clipCenter.xy / clipCenter.w;
          vec2 eclipseScale = vec2(
            abs(clipRight.x / clipRight.w - centerNdc.x),
            abs(clipUp.y / clipUp.w - centerNdc.y)
          );
          vEclipsePoint = (clipPosition.xy / clipPosition.w - centerNdc) / eclipseScale;
          gl_Position = clipPosition;
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uIgnition;
        uniform float uAssembly;
        uniform float uMass;
        uniform float uSurfacePresence;
        uniform vec4 uImpacts;
        uniform float uEclipsePhase;
        uniform float uEclipsePresence;
        varying vec3 vRadialNormal;
        varying vec2 vEclipsePoint;
        varying vec3 vPositionObject;
#ifdef HELIOGENESIS_SYNTHWAVE
        varying vec3 vFacetNormal;
        varying vec3 vBarycentric;
        varying float vFacetCharge;
        varying float vFacetPhase;
#endif
        ${noiseGlsl}

#ifdef HELIOGENESIS_SYNTHWAVE
        mat2 signalRotation(float angle) {
          float sine = sin(angle);
          float cosine = cos(angle);
          return mat2(cosine, -sine, sine, cosine);
        }

        float phosphorLine(float field, float frequency, float width) {
          return 1.0 - smoothstep(0.0, width, abs(sin(field * frequency)));
        }
#endif

        void main() {
          vec3 n = normalize(vRadialNormal);
          float broad = fbm(n * 4.2 + vec3(uTime * 0.07, 0.0, -uTime * 0.045));
          float cells = fbm(n * 11.0 - vec3(0.0, uTime * 0.11, uTime * 0.04));
          float formationField = broad * 0.58 + cells * 0.42;
          float formationFlicker = sin(n.x * 17.0 + n.y * 11.0 + uTime * 0.9) * 0.035 * (1.0 - uAssembly);
          float threshold = mix(0.71, 0.28, uAssembly);
          float islandSignal = formationField + formationFlicker;
          float islands = smoothstep(threshold - 0.055, threshold + 0.06, islandSignal);
          float ignitionSiteSignal = max(
            dot(n, normalize(vec3(-0.56, 0.42, 0.72))),
            max(
              dot(n, normalize(vec3(0.61, 0.12, 0.78))),
              dot(n, normalize(vec3(0.08, -0.67, 0.74)))
            )
          ) + (broad - 0.5) * 0.42 + (cells - 0.5) * 0.16;
          float ignitionSiteThreshold = mix(0.975, -0.26, smoothstep(0.04, 0.86, uAssembly));
          float ignitionSites = smoothstep(
            ignitionSiteThreshold - 0.065,
            ignitionSiteThreshold + 0.065,
            ignitionSiteSignal
          );
          float cohesion = smoothstep(0.7, 0.96, uAssembly);
          float surfaceMask = mix(max(islands, ignitionSites), 1.0, cohesion);
          float islandFront = 1.0 - smoothstep(0.018, 0.085, abs(islandSignal - threshold));
          float ignitionFront = 1.0 - smoothstep(0.015, 0.075, abs(ignitionSiteSignal - ignitionSiteThreshold));
          float frontier = max(islandFront * 0.58, ignitionFront) * (1.0 - cohesion);
          vec4 impactAlignment = vec4(
            max(0.0, dot(n, normalize(vec3(-0.72, 0.18, 0.67)))),
            max(0.0, dot(n, normalize(vec3(0.58, 0.42, 0.69)))),
            max(0.0, dot(n, normalize(vec3(0.15, -0.72, 0.68)))),
            max(0.0, dot(n, normalize(vec3(0.75, -0.22, 0.62))))
          );
          float impactHeat = dot(uImpacts, pow(impactAlignment, vec4(15.0)));
          vec4 impactRings = 1.0 - smoothstep(vec4(0.02), vec4(0.085), abs(impactAlignment - 0.72));
          float impactWave = dot(uImpacts, impactRings);
          vec2 eclipsePoint = vEclipsePoint;
          vec2 eclipseCenter = vec2(
            mix(-1.68, 1.68, uEclipsePhase),
            sin(uEclipsePhase * 3.14159265) * 0.07 - 0.025
          );
          float eclipseDistance = length(eclipsePoint - eclipseCenter);
          float eclipseRadius = 0.925;
          float occultation = 1.0 - smoothstep(eclipseRadius - 0.018, eclipseRadius + 0.022, eclipseDistance);
          float chromosphere = 1.0 - smoothstep(0.012, 0.062, abs(eclipseDistance - eclipseRadius));
          float eclipseAngle = atan(eclipsePoint.y - eclipseCenter.y, eclipsePoint.x - eclipseCenter.x);
          float beadField = sin(eclipseAngle * 19.0 + broad * 9.0 - uTime * 0.72) * 0.5 + 0.5;
          float beads = pow(max(0.0, beadField), 9.0);
          float stellarLimb = smoothstep(0.82, 0.985, length(eclipsePoint));
          float contactFlash = chromosphere * stellarLimb * (0.42 + beads * 1.5);
          float annularAlignment =
            (1.0 - smoothstep(0.025, 0.24, abs(uEclipsePhase - 0.5))) * uEclipsePresence;
          float filaments = smoothstep(0.48, 0.73, abs(broad - cells * 0.56));
          float facing = max(0.0, dot(n, normalize(vec3(-0.38, 0.5, 1.0))));
          float limb = pow(1.0 - max(0.0, n.z), 1.7);

#ifdef HELIOGENESIS_SYNTHWAVE
          float signalOrder = smoothstep(0.4, 0.9, uAssembly);
          float phaseLock = smoothstep(0.34, 0.8, uIgnition);
          float lockPulse =
            smoothstep(0.38, 0.56, uIgnition) * (1.0 - smoothstep(0.7, 0.88, uIgnition));
          vec3 signalNormal = normalize(vFacetNormal);
          float signalFacing = max(0.0, dot(signalNormal, normalize(vec3(-0.38, 0.5, 1.0))));
          float signalLimb = pow(1.0 - max(0.0, signalNormal.z), 1.7);

          vec3 carrier = signalNormal;
          carrier.xz = signalRotation(uTime * 0.025 + phaseLock * 0.2) * carrier.xz;
          carrier.yz = signalRotation(-0.36) * carrier.yz;
          carrier.xy = signalRotation(0.24) * carrier.xy;
          float carrierNoise = fbm(carrier * 3.1 + vec3(4.8, -uTime * 0.015, 9.1));
          float carrierLongitude = atan(carrier.z, carrier.x);
          float impactTear = (impactHeat * 0.16 + impactWave * 0.09) *
            sin(carrierLongitude * 7.0 - uTime * 1.1);

          float edgeWidth = mix(0.068, 0.044, phaseLock);
          float gridA = 1.0 - smoothstep(0.012, edgeWidth, vBarycentric.x);
          float gridB = 1.0 - smoothstep(0.012, edgeWidth, vBarycentric.y);
          float gridC = 1.0 - smoothstep(0.012, edgeWidth, vBarycentric.z);
          float tearGate = 1.0 - smoothstep(
            0.42,
            0.92,
            abs(sin(vFacetPhase * 17.0 + impactTear * 8.0)) * impactWave
          );
          gridA *= tearGate;
          gridB *= tearGate;
          gridC *= tearGate;
          float goldRaster = gridA;
          float cyanRaster = gridB;
          float roseRaster = gridC;
          float rasterBody = max(gridA, max(gridB, gridC));
          float nodeField = max(gridA * gridB, max(gridB * gridC, gridC * gridA));

          float scanCore = phosphorLine(
            vEclipsePoint.y + sin(vEclipsePoint.x * 4.0 + uTime * 0.24) * 0.006,
            46.0,
            0.085
          );
          float persistenceRaster = rasterBody *
            (0.55 + 0.45 * sin(uTime * 0.38 + vFacetPhase * 13.0));
          float persistenceCore = scanCore * (0.55 + 0.45 * rasterBody);

          float panelCode = vFacetCharge;
          float panelPulse = 0.5 + 0.5 * sin(
            uTime * (0.22 + vFacetPhase * 0.18) + vFacetPhase * 11.0
          );
          float panelCharge = mix(panelCode, panelPulse, 0.24);
          float vectorA = max(gridA, gridB);
          float vectorB = gridC;
          float vectorJunction = pow(max(0.0, nodeField), 0.5);
          float nodeBlink = vectorJunction *
            (0.72 + 0.28 * sin(uTime * 1.3 + vFacetPhase * 18.0));
          float syncFlash = max(impactHeat, impactWave) + lockPulse * rasterBody;
#endif

#ifdef HELIOGENESIS_TRANSMUTATION
          float signalOrder = smoothstep(0.4, 0.9, uAssembly);
#endif

          vec3 crimson = vec3(0.86, 0.012, 0.14);
          vec3 orange = vec3(1.0, 0.20, 0.025);
          vec3 amber = vec3(1.0, 0.64, 0.10);
          vec3 whiteHot = vec3(1.0, 0.96, 0.66);
          vec3 surfaceWhiteHot = whiteHot;
          vec3 proto = mix(vec3(0.3, 0.001, 0.032), vec3(0.66, 0.018, 0.022), broad);
          proto += cells * vec3(0.12, 0.004, 0.028);
          vec3 fire = mix(crimson, orange, smoothstep(0.18, 0.62, broad));
          fire = mix(fire, amber, smoothstep(0.42, 0.86, cells + facing * 0.35));
          fire = mix(fire, whiteHot, smoothstep(0.68, 1.16, broad + cells * 0.52 + facing * 0.28));
          fire += filaments * vec3(0.55, 0.09, 0.015);
          fire = mix(fire, crimson * 0.72, limb * 0.63);
          vec3 firstFire = mix(crimson * 0.94, orange, smoothstep(0.26, 0.78, broad + cells * 0.24));
          fire = mix(firstFire, fire, smoothstep(0.18, 0.58, uAssembly));

#ifdef HELIOGENESIS_SYNTHWAVE
          vec3 pearlHot = vec3(1.0, 0.78, 0.93);
          vec3 panelLilac = vec3(0.62, 0.34, 0.88);
          vec3 panelRose = vec3(1.0, 0.46, 0.72);
          vec3 panelPeach = vec3(1.0, 0.68, 0.5);
          vec3 panelCyan = vec3(0.42, 0.82, 1.0);
          vec3 edgeRose = vec3(1.0, 0.02, 0.52);
          vec3 edgeGold = vec3(1.0, 0.4, 0.07);
          vec3 edgeCyan = vec3(0.02, 0.9, 1.0);
          vec3 edgeMagenta = vec3(1.0, 0.02, 0.72);
          surfaceWhiteHot = pearlHot;
          float cyanSeparation = max(0.0, cyanRaster - max(roseRaster, goldRaster));
          float magentaSeparation = max(0.0, roseRaster - max(cyanRaster, goldRaster));
          float thermalSignal = clamp(
            panelCharge * 0.54 + carrierNoise * 0.28 + signalFacing * 0.18,
            0.0,
            1.0
          );
          vec3 signalSurface = mix(vec3(0.018, 0.004, 0.09), panelLilac, thermalSignal);
          signalSurface = mix(
            signalSurface,
            mix(panelLilac, panelRose, smoothstep(0.18, 0.88, panelCode)),
            0.24
          );
          signalSurface = mix(
            signalSurface,
            panelRose,
            smoothstep(0.42, 0.8, thermalSignal) * 0.64
          );
          signalSurface = mix(
            signalSurface,
            panelPeach,
            smoothstep(0.72, 1.0, thermalSignal) * 0.42
          );
          vec3 opalWash = mix(panelLilac, panelRose, smoothstep(0.18, 0.86, broad));
          opalWash = mix(opalWash, panelCyan, smoothstep(0.64, 0.96, cells) * 0.42);
          signalSurface = mix(signalSurface, opalWash, 0.24 + signalFacing * 0.12);
          signalSurface *= 0.76 + panelCharge * 0.32;

          float currentWarp = (broad - 0.5) * 2.1 + (cells - 0.5) * 0.5;
          float currentPhaseA =
            n.y * 7.6 + n.x * 2.1 + sin(n.z * 4.4 - uTime * 0.09) * 0.45 +
            currentWarp - uTime * 0.12;
          float currentPhaseB =
            n.y * 5.1 - n.z * 3.3 + sin(n.x * 3.7 + uTime * 0.07) * 0.55 -
            currentWarp * 0.65 + uTime * 0.08;
          float currentGateA = 0.2 + smoothstep(
            0.38,
            0.66,
            broad * 0.72 + cells * 0.28 + sin(n.x * 6.0 + n.z * 3.0 + uTime * 0.08) * 0.14
          ) * 0.8;
          float currentGateB = 0.16 + smoothstep(
            0.46,
            0.73,
            broad * 0.46 + cells * 0.54 + sin(n.y * 5.0 - n.x * 3.0 - uTime * 0.06) * 0.12
          ) * 0.84;
          float currentWaveA = sin(currentPhaseA) * 0.5 + 0.5;
          float currentWaveB = sin(currentPhaseB) * 0.5 + 0.5;
          float currentCoreA = pow(currentWaveA, 13.0) * currentGateA;
          float currentCoreB = pow(currentWaveB, 15.0) * currentGateB * 0.76;
          float currentCore = max(currentCoreA, currentCoreB);
          float currentHalo = max(
            pow(currentWaveA, 4.0) * currentGateA,
            pow(currentWaveB, 5.0) * currentGateB * 0.68
          );
          float cyanGhost = max(
            pow(sin(currentPhaseA + 0.13) * 0.5 + 0.5, 14.0) * currentGateA,
            pow(sin(currentPhaseB + 0.11) * 0.5 + 0.5, 16.0) * currentGateB * 0.66
          );
          float magentaGhost = max(
            pow(sin(currentPhaseA - 0.13) * 0.5 + 0.5, 14.0) * currentGateA,
            pow(sin(currentPhaseB - 0.11) * 0.5 + 0.5, 16.0) * currentGateB * 0.66
          );
          float currentPresence = signalOrder * (0.4 + thermalSignal * 0.6) *
            (1.0 - smoothstep(0.22, 1.08, limb));
          float currentBalance = currentCoreB / max(0.001, currentCoreA + currentCoreB);
          vec3 currentRibbon = mix(
            vec3(0.24, 0.78, 1.0),
            vec3(1.0, 0.18, 0.76),
            currentBalance
          );
          vec3 currentUndertow = mix(
            vec3(0.025, 0.008, 0.16),
            vec3(0.16, 0.008, 0.2),
            currentBalance
          );
          float currentShimmer = 0.76 + 0.24 * sin(
            uTime * 1.18 + currentWarp * 4.0 + n.x * 5.0 - n.z * 2.0
          );
          signalSurface = mix(
            signalSurface,
            currentUndertow,
            clamp(currentHalo * currentPresence * 0.3, 0.0, 0.28)
          );
          float currentMask = clamp(
            currentCore * currentPresence * (0.38 + currentShimmer * 0.4),
            0.0, 0.7
          );
          signalSurface = mix(
            signalSurface,
            currentRibbon * (0.76 + thermalSignal * 0.24),
            currentMask
          );
          signalSurface += edgeCyan * cyanGhost * currentPresence * 0.14;
          signalSurface += edgeMagenta * magentaGhost * currentPresence * 0.12;
          signalSurface += pearlHot * currentCore * currentPresence * currentShimmer * 0.24;

          float nacre = sin(
            uTime * 0.28 + broad * 6.0 + carrierNoise * 3.2 + n.y * 2.4
          ) * 0.5 + 0.5;
          float nacrePresence = smoothstep(0.34, 0.82, nacre) *
            (1.0 - signalLimb * 0.36);
          vec3 nacreColor = mix(panelCyan, pearlHot, smoothstep(0.22, 0.78, broad));
          signalSurface += nacreColor * nacrePresence * (0.055 + thermalSignal * 0.065);

          signalSurface += edgeRose * roseRaster * 0.07;
          signalSurface += edgeGold * goldRaster * 0.075;
          signalSurface += edgeCyan * cyanSeparation * 0.48;
          signalSurface += edgeMagenta * magentaSeparation * 0.43;
          signalSurface += pearlHot * scanCore * 0.1;
          signalSurface += edgeMagenta * persistenceRaster * 0.045;
          signalSurface += edgeCyan * persistenceCore * (0.18 + (1.0 - phaseLock) * 0.08);
          signalSurface += (edgeCyan * vectorA + edgeMagenta * vectorB) * 0.03;
          signalSurface += pearlHot * nodeBlink * 0.3;
          signalSurface = mix(
            signalSurface,
            pearlHot * (1.05 + lockPulse * 0.16),
            clamp(syncFlash * 0.5, 0.0, 0.76)
          );
          signalSurface *= 0.62 + signalFacing * 0.38;
          signalSurface = mix(signalSurface, panelLilac * 0.5, signalLimb * 0.46);
          signalSurface +=
            (edgeCyan * cyanSeparation + edgeMagenta * magentaSeparation) * signalLimb * 0.34;
          fire = mix(fire, signalSurface, signalOrder * 0.97);
#endif

#ifdef HELIOGENESIS_TRANSMUTATION
          vec3 pearlHot = vec3(1.0, 0.78, 0.93);
          vec3 panelRose = vec3(1.0, 0.46, 0.72);
          vec3 panelPeach = vec3(1.0, 0.68, 0.5);
          vec3 panelCyan = vec3(0.42, 0.82, 1.0);
          vec3 albedoPearl = vec3(0.72, 0.84, 1.0);
          vec3 alchemicalGold = vec3(1.0, 0.48, 0.06);
          vec3 rubedoRose = vec3(0.96, 0.06, 0.34);
          vec3 nigredoViolet = vec3(0.022, 0.001, 0.06);
          vec3 edgeCyan = vec3(0.02, 0.9, 1.0);
          vec3 edgeMagenta = vec3(1.0, 0.02, 0.72);
          surfaceWhiteHot = pearlHot;
          float atmosphericDrift = fbm(
            n * 1.85 + vec3(uTime * 0.013, -uTime * 0.009, 7.4)
          );
          float slowBreath = sin(
            uTime * 0.22 + atmosphericDrift * 2.2 + n.y * 0.7
          ) * 0.5 + 0.5;
          float lightTemperature = clamp(
            0.62 + (atmosphericDrift - 0.5) * 0.1 + (slowBreath - 0.5) * 0.06,
            0.54,
            0.72
          );
          vec3 resolvedLight = mix(panelPeach, pearlHot, lightTemperature);
          float projectedRadius = clamp(length(vEclipsePoint), 0.0, 1.08);
          float innerLight = pow(max(0.0, 1.0 - projectedRadius), 0.72);

          float currentTurnA = uTime * 0.07 + atmosphericDrift * 0.16;
          float currentTurnB = -uTime * 0.052 + atmosphericDrift * 0.11;
          mat2 currentRotationA = mat2(
            cos(currentTurnA), -sin(currentTurnA),
            sin(currentTurnA), cos(currentTurnA)
          );
          mat2 currentRotationB = mat2(
            cos(currentTurnB), -sin(currentTurnB),
            sin(currentTurnB), cos(currentTurnB)
          );
          vec3 currentA = n;
          vec3 currentB = n;
          currentA.xz = currentRotationA * currentA.xz;
          currentB.yz = currentRotationB * currentB.yz;
          float currentFieldA = fbm(
            currentA * 2.35 + vec3(uTime * 0.018, -uTime * 0.012, 3.8)
          );
          float currentFieldB = fbm(
            currentB * 3.05 + vec3(-uTime * 0.014, 9.2, uTime * 0.017)
          );
          float transmutationStage = pow(clamp(uSurfacePresence, 0.0, 1.0), 1.65);
          float localStage = clamp(
            transmutationStage + (currentFieldA - currentFieldB) * 0.62 +
              (atmosphericDrift - 0.5) * 0.12,
            0.0,
            1.0
          );
          vec3 transmutationColor = mix(
            nigredoViolet,
            albedoPearl,
            smoothstep(0.1, 0.32, localStage)
          );
          transmutationColor = mix(
            transmutationColor,
            alchemicalGold,
            smoothstep(0.26, 0.55, localStage)
          );
          transmutationColor = mix(
            transmutationColor,
            rubedoRose,
            smoothstep(0.56, 0.8, localStage)
          );
          vec3 signalSurface = mix(
            transmutationColor,
            resolvedLight,
            smoothstep(0.89, 1.0, transmutationStage)
          );
          signalSurface = mix(signalSurface, pearlHot, innerLight * 0.12);

          float reactionDistance = abs(currentFieldA - currentFieldB);
          float reactionHalo = 1.0 - smoothstep(0.04, 0.18, reactionDistance);
          float reactionCore = 1.0 - smoothstep(0.014, 0.06, reactionDistance);
          float phaseDistance = min(
            abs(localStage - 0.28),
            min(abs(localStage - 0.55), abs(localStage - 0.78))
          );
          float transmutationFront = 1.0 - smoothstep(0.016, 0.058, phaseDistance);
          float transmutationActive = smoothstep(0.04, 0.35, transmutationStage) *
            (1.0 - smoothstep(0.91, 1.0, transmutationStage));
          float reactionPresence = 0.22 + transmutationActive * 0.9;
          signalSurface *= 1.0 - transmutationActive * 0.16;
          vec3 reactionColor = mix(alchemicalGold, pearlHot, reactionCore * 0.72);
          reactionColor = mix(
            reactionColor,
            panelCyan,
            smoothstep(0.7, 0.94, currentFieldB) * 0.22
          );
          signalSurface = mix(
            signalSurface,
            nigredoViolet * 1.6 + panelRose * 0.08,
            reactionHalo * reactionPresence * 0.25
          );
          signalSurface += reactionColor *
            (reactionHalo * 0.045 + reactionCore * 0.28) * reactionPresence;
          signalSurface = mix(
            signalSurface,
            mix(alchemicalGold, pearlHot, localStage),
            transmutationFront * (0.16 + transmutationActive * 0.62)
          );
          float mercurialThread = 1.0 - smoothstep(
            0.018,
            0.065,
            abs(currentFieldB - 0.68)
          );
          signalSurface += mix(panelCyan, pearlHot, currentFieldA) * mercurialThread *
            (0.035 + transmutationActive * 0.12);

          float chromaticLimb = smoothstep(0.48, 0.99, projectedRadius);
          float spectralBalance = smoothstep(
            -0.58,
            0.58,
            n.x + sin(uTime * 0.075) * 0.12
          );
          vec3 limbSpectrum = mix(panelRose, panelCyan, spectralBalance);
          signalSurface = mix(signalSurface, limbSpectrum, chromaticLimb * 0.18);
          float rimThread = smoothstep(0.8, 1.01, projectedRadius);
          signalSurface += mix(edgeMagenta, edgeCyan, spectralBalance) * rimThread * 0.08;

          float lightResolve = smoothstep(0.07, 0.34, uIgnition);
          float arrivalFlash = smoothstep(0.1, 0.28, uIgnition) *
            (1.0 - smoothstep(0.42, 0.64, uIgnition));
          signalSurface = mix(signalSurface, pearlHot * 1.04, arrivalFlash * 0.16);
          float emissionDither = noise3(n * 47.0 + vec3(2.8, 6.1, 9.7)) - 0.5;
          signalSurface += vec3(emissionDither * 0.006);
          signalSurface *= 0.67 + innerLight * 0.11 + slowBreath * 0.045 +
            (atmosphericDrift - 0.5) * 0.025;
          fire = mix(fire, signalSurface, signalOrder * lightResolve * 0.98);
#endif

          vec3 color = mix(proto, fire, surfaceMask);
          color = mix(color, surfaceWhiteHot, (1.0 - cohesion) * islands * 0.16);
          color = mix(color, surfaceWhiteHot * 1.16, frontier * 0.88);
          color = mix(
            color,
            surfaceWhiteHot * 1.2,
            clamp(impactHeat * 0.92 + impactWave * 0.24, 0.0, 1.0)
          );
#ifdef HELIOGENESIS_SPECTRAL
          float eclipseInterior = smoothstep(0.05, eclipseRadius, eclipseDistance);
          vec3 eclipseShadow = mix(
            vec3(0.002, 0.001, 0.009),
            vec3(0.012, 0.001, 0.022),
            eclipseInterior * 0.48
          );
          float eclipseCoverage = occultation * uEclipsePresence;
#else
          vec3 eclipseShadow = mix(
            vec3(0.003, 0.001, 0.012),
            vec3(0.038, 0.001, 0.055),
            broad
          );
          float eclipseCoverage = occultation * uEclipsePresence * 0.985;
#endif
          color = mix(color, eclipseShadow, eclipseCoverage);
          float liveChromosphere = chromosphere * uEclipsePresence;
          color += vec3(1.0, 0.018, 0.15) * liveChromosphere * (0.38 + beads * 0.58);
          color += surfaceWhiteHot *
            (contactFlash * uEclipsePresence * 1.18 + liveChromosphere * annularAlignment * 0.3);
          float stellarPresence = smoothstep(0.018, 0.14, uMass);
          float alpha = stellarPresence * uSurfacePresence *
            mix(0.78, 1.0, max(surfaceMask, frontier * 0.82));
#ifdef HELIOGENESIS_SPECTRAL
          alpha *= mix(0.93 + facing * 0.07, 1.0, signalOrder);
          alpha = mix(alpha, 1.0, eclipseCoverage);
#else
          alpha *= 0.93 + facing * 0.07;
#endif
          if (alpha < 0.012) discard;
          gl_FragColor = vec4(color * (0.84 + uIgnition * 0.62), alpha);
        }
      `,
    });

    star = new THREE.Mesh(geometry, material);
    star.renderOrder = 8;
    solarAnchor.add(star);

    const atmosphereMaterial = new THREE.ShaderMaterial({
      defines: sunDefines,
      transparent: true,
      depthWrite: false,
      depthTest: false,
      side: THREE.BackSide,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uIgnition: { value: 0 },
      },
      vertexShader: `
        varying vec3 vNormalView;
        void main() {
          vNormalView = normalize(normalMatrix * normal);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uIgnition;
        varying vec3 vNormalView;
        void main() {
          float rim = pow(1.0 - abs(vNormalView.z), 2.35);
#ifdef HELIOGENESIS_SPECTRAL
          vec3 color = mix(vec3(0.68, 0.26, 0.94), vec3(1.0, 0.58, 0.76), rim);
          color = mix(color, vec3(0.36, 0.84, 1.0), pow(rim, 3.0) * 0.24);
#else
          vec3 color = mix(vec3(1.0, 0.08, 0.32), vec3(1.0, 0.55, 0.11), rim);
#endif
          gl_FragColor = vec4(color, rim * 0.6 * uIgnition);
        }
      `,
    });
    atmosphere = new THREE.Mesh(new THREE.SphereGeometry(1.98, 64, 40), atmosphereMaterial);
    atmosphere.renderOrder = 7;
    solarAnchor.add(atmosphere);

    const coronaMaterial = new THREE.ShaderMaterial({
      defines: sunDefines,
      transparent: true,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uIgnition: { value: 0 },
        uEclipseTotality: { value: 0 },
      },
      vertexShader: `
        varying vec2 vUv;
        void main() {
          vUv = uv;
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uIgnition;
        uniform float uEclipseTotality;
        varying vec2 vUv;
        ${noiseGlsl}
        void main() {
          vec2 p = vUv - 0.5;
          float radius = length(p) * 2.0;
          float angle = atan(p.y, p.x);
          float turbulence = fbm(vec3(cos(angle) * 2.3, sin(angle) * 2.3, uTime * 0.045));
          float rayField =
            sin(angle * 11.0 + turbulence * 8.0) * 0.48 +
            sin(angle * 23.0 - turbulence * 5.0 + 1.7) * 0.32 +
            sin(angle * 37.0 + turbulence * 3.0) * 0.2;
          float rays = pow(max(0.0, rayField), 7.0);
          float core = 1.0 - smoothstep(0.28, 0.98 + turbulence * 0.16, radius);
          float crown = (1.0 - smoothstep(0.45, 1.0, radius)) * (0.18 + rays * 0.65);
          float eclipseCrown =
            (1.0 - smoothstep(0.31, 1.0, radius)) * (0.08 + rays * 1.16) * uEclipseTotality;
          float alpha = ((core * 0.2 + crown) * uIgnition + eclipseCrown) *
            (1.0 - smoothstep(0.78, 1.0, radius));
#ifdef HELIOGENESIS_SPECTRAL
          vec3 color = mix(
            vec3(0.66, 0.18, 0.94),
            vec3(1.0, 0.58, 0.76),
            core + turbulence * 0.28
          );
          color = mix(color, vec3(0.38, 0.84, 1.0), rays * 0.18);
#else
          vec3 color = mix(vec3(1.0, 0.03, 0.27), vec3(1.0, 0.58, 0.13), core + turbulence * 0.28);
#endif
          vec3 eclipseColor = mix(
            vec3(0.04, 0.82, 1.0),
            vec3(1.0, 0.035, 0.42),
            sin(angle * 3.0 - uTime * 0.11) * 0.5 + 0.5
          );
          color = mix(color, eclipseColor, uEclipseTotality * (0.42 + rays * 0.38));
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
    corona = new THREE.Mesh(new THREE.PlaneGeometry(8.9, 8.9), coronaMaterial);
    corona.renderOrder = 1;
    solarAnchor.add(corona);
  }

  function buildProminences() {
    const paths = [
      {
        phase: 0.2,
        points: [
          [-1.46, 0.48, -0.24],
          [-2.3, 1.42, -0.08],
          [-1.18, 3.08, 0.18],
          [0.72, 2.76, 0.36],
          [1.48, 0.62, 0.2],
        ],
      },
      {
        phase: 2.4,
        points: [
          [1.5, -0.18, 0.3],
          [2.74, 0.28, 0.54],
          [2.5, 1.88, 0.16],
          [1.24, 1.34, -0.12],
          [0.62, 1.52, -0.26],
        ],
      },
      {
        phase: 4.7,
        points: [
          [-1.34, -0.7, 0.32],
          [-2.46, -1.36, 0.48],
          [-1.34, -2.18, 0.12],
          [0.18, -1.94, -0.18],
          [1.24, -0.92, -0.3],
        ],
      },
    ];
    const group = new THREE.Group();
    const records = [];

    paths.forEach(({ phase, points }, pathIndex) => {
      const curve = new THREE.CatmullRomCurve3(
        points.map(([x, y, z]) => new THREE.Vector3(x, y, z)),
        false,
        "centripetal",
      );

      [
        { radius: 0.07, core: 0 },
        { radius: 0.018, core: 1 },
      ].forEach(({ radius, core }) => {
        const geometry = new THREE.TubeGeometry(
          curve,
          viewport.compact ? 48 : 72,
          radius,
          viewport.compact ? 5 : 7,
          false,
        );
        const material = new THREE.ShaderMaterial({
          transparent: true,
          depthWrite: false,
          depthTest: true,
          side: THREE.DoubleSide,
          blending: THREE.AdditiveBlending,
          uniforms: {
            uTime: { value: 0 },
            uPresence: { value: 0 },
            uEclipse: { value: 0 },
            uShock: { value: 0 },
            uRupture: { value: 0 },
            uPhase: { value: phase },
            uPath: { value: pathIndex },
            uCore: { value: core },
          },
          vertexShader: `
            varying vec2 vUv;
            void main() {
              vUv = uv;
              gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
          `,
          fragmentShader: `
            uniform float uTime;
            uniform float uPresence;
            uniform float uEclipse;
            uniform float uShock;
            uniform float uRupture;
            uniform float uPhase;
            uniform float uPath;
            uniform float uCore;
            varying vec2 vUv;
            void main() {
              float taper = smoothstep(0.0, 0.13, vUv.x) *
                (1.0 - smoothstep(0.82, 1.0, vUv.x));
              float current = pow(
                0.5 + 0.5 * sin(vUv.x * 31.0 - uTime * (2.3 + uPhase * 0.08) + uPhase * 5.0),
                4.0
              );
              float braid = 0.5 + 0.5 * sin(vUv.y * 12.566 + vUv.x * 19.0 + uTime * 1.4);
              float fibers = smoothstep(
                0.18,
                0.88,
                0.5 + 0.5 * sin(vUv.x * 47.0 + braid * 4.2 - uTime * 1.9 + uPhase)
              );
              vec3 cyan = vec3(0.0, 0.38, 0.5);
              vec3 rose = vec3(0.94, 0.006, 0.12);
              vec3 gold = vec3(1.0, 0.43, 0.055);
              vec3 color = mix(cyan, rose, 0.68 + 0.22 * sin(uPhase + vUv.x * 4.6));
              color = mix(color, gold, current * (0.34 + uCore * 0.38));
              color = mix(color, vec3(1.0, 0.78, 0.37), uCore * current * 0.34);
              float layerAlpha = mix(0.14 + braid * fibers * 0.2, 0.38 + current * 0.54, uCore);
              float rupturePath = 1.0 - step(0.5, uPath);
              float magneticDrain = 1.0 - rupturePath * uRupture * smoothstep(0.08, 0.78, vUv.x) * 0.82;
              float ruptureCurrent = rupturePath * uRupture *
                pow(max(0.0, sin(vUv.x * 21.0 - uTime * 4.2)), 6.0);
              color = mix(color, vec3(0.08, 0.82, 1.0), ruptureCurrent * 0.52);
              float alpha = taper * uPresence * layerAlpha *
                (0.68 + uEclipse * 0.72 + uShock * 0.35 + ruptureCurrent * 0.72) *
                magneticDrain;
              if (alpha < 0.008) discard;
              gl_FragColor = vec4(color, alpha);
            }
          `,
        });
        const mesh = new THREE.Mesh(geometry, material);
        mesh.frustumCulled = false;
        mesh.renderOrder = 10 + core;
        group.add(mesh);
        records.push({ mesh, material, phase, pathIndex, core });
      });
    });

    group.visible = false;
    solarAnchor.add(group);
    prominenceField = { group, records };
  }

  function buildCoronalRupture() {
    const paths = [
      [
        [1.24, 0.62, 0.16],
        [2.16, 1.58, 0.34],
        [1.34, 3.04, 0.82],
        [-0.34, 3.92, 1.62],
        [-2.86, 3.46, 3.24],
        [-5.52, 1.54, 6.12],
        [-7.24, -0.56, 9.08],
      ],
      [
        [1.36, 0.34, -0.08],
        [2.48, 1.08, 0.18],
        [2.02, 2.72, 0.72],
        [0.26, 4.42, 1.86],
        [-3.08, 4.64, 4.04],
        [-5.96, 2.06, 7.36],
      ],
    ];
    const group = new THREE.Group();
    const records = [];

    paths.forEach((points, pathIndex) => {
      const curve = new THREE.CatmullRomCurve3(
        points.map(([x, y, z]) => new THREE.Vector3(x, y, z)),
        false,
        "centripetal",
      );
      [
        { radius: pathIndex ? 0.075 : 0.13, core: 0 },
        { radius: pathIndex ? 0.024 : 0.038, core: 1 },
      ].forEach(({ radius, core }) => {
        const geometry = new THREE.TubeGeometry(
          curve,
          viewport.compact ? 72 : 108,
          radius,
          viewport.compact ? 5 : 7,
          false,
        );
        const material = new THREE.ShaderMaterial({
          transparent: true,
          depthWrite: false,
          depthTest: false,
          side: THREE.DoubleSide,
          blending: THREE.AdditiveBlending,
          uniforms: {
            uTime: { value: 0 },
            uPresence: { value: 0 },
            uHead: { value: 0 },
            uSnap: { value: 0 },
            uCore: { value: core },
            uPath: { value: pathIndex },
          },
          vertexShader: `
            uniform float uTime;
            uniform float uSnap;
            uniform float uCore;
            uniform float uPath;
            varying vec2 vUv;
            void main() {
              vUv = uv;
              float magneticWave =
                sin(uv.x * 31.0 - uTime * 2.8 + uPath * 1.7) * 0.62 +
                sin(uv.x * 13.0 + uTime * 1.9 - uPath) * 0.38;
              float travelMask = smoothstep(0.08, 0.42, uv.x);
              vec3 displaced = position + normal * magneticWave * travelMask *
                (0.018 + uSnap * 0.055) * (1.0 - uCore * 0.44);
              gl_Position = projectionMatrix * modelViewMatrix * vec4(displaced, 1.0);
            }
          `,
          fragmentShader: `
            uniform float uTime;
            uniform float uPresence;
            uniform float uHead;
            uniform float uSnap;
            uniform float uCore;
            uniform float uPath;
            varying vec2 vUv;
            void main() {
              float reveal = 1.0 - smoothstep(uHead - 0.018, uHead + 0.045, vUv.x);
              float root = smoothstep(0.0, 0.055, vUv.x);
              float taper = 1.0 - smoothstep(0.76, 1.0, vUv.x);
              float head = 1.0 - smoothstep(0.0, 0.065, abs(vUv.x - uHead));
              float current = pow(
                max(0.0, sin(vUv.x * 43.0 - uTime * (5.4 + uPath * 0.8) + vUv.y * 8.0)),
                5.0
              );
              float braid = 0.5 + 0.5 * sin(vUv.y * 12.566 + vUv.x * 27.0 - uTime * 2.6);
              float fracture = smoothstep(0.25, 0.82, braid + current * 0.44);
              vec3 rose = vec3(1.0, 0.018, 0.27);
              vec3 cyan = vec3(0.0, 0.78, 1.0);
              vec3 gold = vec3(1.0, 0.57, 0.09);
              vec3 color = mix(rose, cyan, braid * (0.28 + uPath * 0.2));
              color = mix(color, gold, current * (0.54 + uCore * 0.32));
              color = mix(color, vec3(1.0, 0.92, 0.62), head * (0.48 + uCore * 0.34));
              float body = mix(
                0.035 + fracture * 0.28 + current * 0.16,
                0.16 + current * 0.62 + fracture * 0.16,
                uCore
              );
              float alpha = reveal * root * uPresence *
                (body * (0.72 + taper * 0.28) + head * (0.42 + uSnap * 0.38));
              if (alpha < 0.008) discard;
              gl_FragColor = vec4(color, alpha);
            }
          `,
        });
        const mesh = new THREE.Mesh(geometry, material);
        mesh.frustumCulled = false;
        mesh.renderOrder = 15 + core + pathIndex * 2;
        group.add(mesh);
        records.push({ mesh, material, core, pathIndex });
      });
    });

    const shellMaterial = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      depthTest: false,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uPresence: { value: 0 },
        uExpansion: { value: 0 },
        uSnap: { value: 0 },
      },
      vertexShader: `
        varying vec3 vNormalView;
        varying vec3 vNormalObject;
        void main() {
          vNormalView = normalize(normalMatrix * normal);
          vNormalObject = normalize(normal);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
      `,
      fragmentShader: `
        uniform float uTime;
        uniform float uPresence;
        uniform float uExpansion;
        uniform float uSnap;
        varying vec3 vNormalView;
        varying vec3 vNormalObject;
        void main() {
          float fresnel = pow(1.0 - abs(vNormalView.z), 3.4);
          float angle = atan(vNormalObject.y, vNormalObject.x);
          float latitude = asin(clamp(vNormalObject.y, -1.0, 1.0));
          float interference =
            sin(angle * 11.0 - uTime * 1.8 + latitude * 7.0) * 0.55 +
            sin(angle * 23.0 + uTime * 1.15 - latitude * 13.0) * 0.3 +
            sin(angle * 37.0 - uTime * 0.72) * 0.15;
          float caustic = smoothstep(0.24, 0.72, interference);
          float spectral = sin(angle * 2.0 + latitude * 5.0 - uTime * 0.35) * 0.5 + 0.5;
          vec3 cyan = vec3(0.0, 0.72, 1.0);
          vec3 rose = vec3(1.0, 0.012, 0.31);
          vec3 gold = vec3(1.0, 0.61, 0.12);
          vec3 color = mix(cyan, rose, spectral);
          color = mix(color, gold, caustic * (0.28 + uSnap * 0.28));
          float membrane = fresnel * (0.16 + caustic * 0.34 + uSnap * 0.24);
          float innerGhost = pow(max(0.0, vNormalView.z), 18.0) * caustic * 0.08;
          float alpha = uPresence * (membrane + innerGhost) *
            (1.0 - smoothstep(0.86, 1.0, uExpansion) * 0.42);
          if (alpha < 0.006) discard;
          gl_FragColor = vec4(color, alpha);
        }
      `,
    });
    const shell = new THREE.Mesh(
      new THREE.SphereGeometry(3.1, viewport.compact ? 32 : 48, viewport.compact ? 20 : 30),
      shellMaterial,
    );
    shell.renderOrder = 14;
    shell.visible = false;
    solarAnchor.add(group, shell);
    ruptureField = { group, records, shell, shellMaterial };
  }

  function buildDocumentTomography() {
    const document = canvas.ownerDocument;
    const descriptors = [
      { selector: "[data-heliogenesis-surface]", kind: "surface", color: 0x00dce5, depth: 2.4 },
      { selector: "[data-heliogenesis-rule]", kind: "rule", color: 0xffbd59, depth: 0.86 },
      { selector: "[data-heliogenesis-callout]", kind: "callout", color: 0x00f0e7, depth: 1.22 },
      { selector: "[data-heliogenesis-code]", kind: "code", color: 0xff327f, depth: 2.15 },
      {
        selector: "[data-heliogenesis-surface] :is(h1, h2, h3)",
        kind: "heading",
        color: 0xff2c77,
        depth: 0.7,
      },
    ];
    const group = new THREE.Group();
    const edgeSourceGeometry = new THREE.BoxGeometry(1, 1, 1);
    const edgeGeometry = new THREE.EdgesGeometry(edgeSourceGeometry);
    edgeSourceGeometry.dispose();
    const fillGeometry = new THREE.BoxGeometry(1, 1, 1);
    const records = [];
    const claimed = new Set();
    const scanGeometry = new THREE.PlaneGeometry(1, 1);
    const scanBars = [
      { color: 0x00f5ef, offset: 0, thickness: 0.018, opacity: 0.22, rotation: -0.018 },
      { color: 0xff2b78, offset: 0.032, thickness: 0.038, opacity: 0.11, rotation: -0.011 },
      { color: 0x8f66ff, offset: 0.04, thickness: 0.24, opacity: 0.035, rotation: -0.014 },
    ].map((settings, index) => {
      const material = new THREE.MeshBasicMaterial({
        color: settings.color,
        transparent: true,
        opacity: 0,
        depthWrite: false,
        depthTest: false,
        side: THREE.DoubleSide,
        blending: THREE.AdditiveBlending,
      });
      const mesh = new THREE.Mesh(scanGeometry, material);
      mesh.frustumCulled = false;
      mesh.renderOrder = 16 + index;
      group.add(mesh);
      return { ...settings, material, mesh };
    });

    descriptors.forEach(({ selector, kind, color, depth }) => {
      document.querySelectorAll(selector).forEach((element) => {
        if (claimed.has(element)) return;
        claimed.add(element);
        const index = records.length;
        const material = new THREE.LineBasicMaterial({
          color,
          transparent: true,
          opacity: 0,
          depthWrite: false,
          depthTest: false,
          blending: THREE.AdditiveBlending,
        });
        const line = new THREE.LineSegments(edgeGeometry, material);
        line.frustumCulled = false;
        line.renderOrder = 12 + index % 3;
        group.add(line);

        let fill = null;
        let fillMaterial = null;
        if (kind === "code") {
          fillMaterial = new THREE.MeshBasicMaterial({
            color: 0x0a0718,
            transparent: true,
            opacity: 0,
            depthWrite: false,
            depthTest: false,
            side: THREE.DoubleSide,
            blending: THREE.NormalBlending,
          });
          fill = new THREE.Mesh(fillGeometry, fillMaterial);
          fill.frustumCulled = false;
          fill.renderOrder = 11;
          group.add(fill);
        }

        records.push({
          element,
          kind,
          line,
          material,
          fill,
          fillMaterial,
          baseDepth: depth,
          baseX: 0,
          baseY: 0,
          width: 0,
          height: 0,
          visible: false,
          delay: index % 6 * 0.012,
          scanAt: 0.48,
          phase: index * 1.61803398875,
          driftX: Math.sin(index * 2.31 + 0.8) * 0.42,
          driftY: Math.cos(index * 1.73 + 0.4) * 0.26,
        });
      });
    });

    group.visible = false;
    scene.add(group);
    tomographyField = { group, records, colliders: [], scanBars, scanBounds: null };
    syncDocumentTomography();
  }

  function syncDocumentTomography() {
    if (!tomographyField) return;
    tomographySynchronizations += 1;
    const colliders = [];
    tomographyField.records.forEach((record) => {
      const rect = record.element.getBoundingClientRect();
      const left = rect.left - viewport.offsetLeft;
      const top = rect.top - viewport.offsetTop;
      const right = left + rect.width;
      const bottom = top + rect.height;
      const visible = rect.width > 2 && rect.height > 2 &&
        right > 0 && bottom > 0 && left < viewport.width && top < viewport.height;
      record.visible = visible;
      record.line.visible = visible;
      if (record.fill) record.fill.visible = visible;
      if (!visible) return;

      record.baseX = ((left + right) * 0.5 / viewport.width - 0.5) * viewport.worldWidth;
      record.baseY = (0.5 - (top + bottom) * 0.5 / viewport.height) * viewport.worldHeight;
      record.width = rect.width / viewport.width * viewport.worldWidth;
      record.height = rect.height / viewport.height * viewport.worldHeight;

      if (record.kind === "code" || record.kind === "callout") {
        colliders.push({
          x: record.baseX,
          y: record.baseY,
          halfWidth: record.width * 0.5,
          halfHeight: record.height * 0.5,
          phase: record.phase,
        });
      }
    });
    tomographyField.colliders = colliders;
    const surface = tomographyField.records.find((record) => record.kind === "surface" && record.visible);
    tomographyField.scanBounds = surface ? {
      x: surface.baseX,
      width: surface.width,
      top: Math.min(viewport.worldHeight * 0.5, surface.baseY + surface.height * 0.5),
      bottom: Math.max(-viewport.worldHeight * 0.5, surface.baseY - surface.height * 0.5),
    } : null;
    if (tomographyField.scanBounds) {
      const { top, bottom } = tomographyField.scanBounds;
      const span = Math.max(0.001, top - bottom);
      tomographyField.records.forEach((record) => {
        const scanPosition = clamp01((top - record.baseY) / span);
        record.scanAt = record.kind === "surface" ? 0.48 : 0.47 + scanPosition * 0.22;
      });
    }
  }

  function updateDocumentTomography(progress, time, moving, rupturePhase, ruptureSnap, presence) {
    if (!tomographyField) return;
    tomographyField.group.visible = moving && presence > 0.002;
    const scanArrival = smoothstep(0.47, 0.69, progress);
    const scanPresence = smoothstep(0.47, 0.52, progress) *
      (1 - smoothstep(0.68, 0.73, progress));
    const scanBounds = tomographyField.scanBounds;
    tomographyField.scanBars.forEach((bar) => {
      const visible = moving && Boolean(scanBounds) && scanPresence > 0.002;
      bar.mesh.visible = visible;
      if (!visible) {
        bar.material.opacity = 0;
        return;
      }
      const y = THREE.MathUtils.lerp(scanBounds.top, scanBounds.bottom, scanArrival) + bar.offset;
      bar.mesh.position.set(scanBounds.x, y, 0.08);
      bar.mesh.rotation.z = bar.rotation;
      bar.mesh.scale.set(scanBounds.width, bar.thickness * (1 + ruptureSnap * 1.8), 1);
      bar.material.opacity = scanPresence * bar.opacity * (1 + ruptureSnap * 0.9);
    });
    tomographyField.records.forEach((record) => {
      if (!record.visible || !moving) {
        record.material.opacity = 0;
        if (record.fillMaterial) record.fillMaterial.opacity = 0;
        return;
      }
      const arrival = smoothstep(record.scanAt, record.scanAt + 0.05, progress);
      const recession = 1 - smoothstep(0.88 + record.delay * 0.4, 0.98, progress);
      const localPresence = arrival * recession;
      const fracture = smoothstep(0.64 + record.delay, 0.9, progress) * rupturePhase;
      const pulse = 0.78 + Math.sin(time * 2.2 + record.phase) * 0.22;
      const depth = record.baseDepth * (0.08 + arrival * 0.92) * (1 + ruptureSnap * 0.24);
      const x = record.baseX + record.driftX * fracture * fracture;
      const y = record.baseY + record.driftY * fracture + Math.sin(time * 0.7 + record.phase) * 0.018;
      const z = -depth * 0.5 - fracture * (0.2 + Math.abs(record.driftX) * 0.36);
      const fractureAngle = record.kind === "surface" ? 0.07 : record.kind === "heading" ? 0.16 : 0.11;

      record.line.position.set(x, y, z);
      record.line.scale.set(record.width, record.height, depth);
      record.line.rotation.set(
        fracture * Math.sin(record.phase) * fractureAngle * 0.72,
        fracture * Math.cos(record.phase * 0.7) * fractureAngle,
        fracture * record.driftX * 0.055,
      );
      record.material.opacity = localPresence * pulse *
        (record.kind === "surface" ? 0.24 : record.kind === "heading" ? 0.48 : 0.38);

      if (record.fill) {
        record.fill.position.copy(record.line.position);
        record.fill.scale.copy(record.line.scale);
        record.fill.rotation.copy(record.line.rotation);
        record.fillMaterial.opacity = localPresence * (0.035 + ruptureSnap * 0.025);
      }
    });
  }

  function deflectFromDocumentation(position, strength, time) {
    if (!tomographyField || strength <= 0.002) return;
    tomographyField.colliders.forEach((collider) => {
      const padding = 0.16;
      const halfWidth = collider.halfWidth + padding;
      const halfHeight = collider.halfHeight + padding;
      const dx = position.x - collider.x;
      const dy = position.y - collider.y;
      const nx = Math.abs(dx) / Math.max(0.001, halfWidth);
      const ny = Math.abs(dy) / Math.max(0.001, halfHeight);
      const proximity = 1 - Math.max(nx, ny);
      if (proximity <= 0) return;
      const force = smoothstep(0, 0.58, proximity) * strength;
      if (nx > ny) {
        position.x += (dx < 0 ? -1 : 1) * force * (0.22 + padding);
        position.y += Math.sin(time * 1.6 + collider.phase) * force * 0.1;
      } else {
        position.y += (dy < 0 ? -1 : 1) * force * (0.18 + padding);
        position.x += Math.cos(time * 1.4 + collider.phase) * force * 0.12;
      }
    });
  }

  function buildRegistry() {
    const ringColors = [0x00dfe5, 0xff2f83, 0xffb14f, 0x9b6dff];
    const rings = viewport.compact ? 6 : 9;
    for (let index = 0; index < rings; index += 1) {
      const radius = 2.7 + index * 0.48 + random() * 0.34;
      const points = [];
      const segments = 180;
      for (let segment = 0; segment < segments; segment += 1) {
        const angle = segment / segments * Math.PI * 2;
        points.push(new THREE.Vector3(Math.cos(angle) * radius, Math.sin(angle) * radius, 0));
      }
      const geometry = new THREE.BufferGeometry().setFromPoints(points);
      const material = new THREE.LineBasicMaterial({
        color: ringColors[index % ringColors.length],
        transparent: true,
        opacity: 0,
        depthWrite: false,
        depthTest: false,
        blending: THREE.AdditiveBlending,
      });
      const ring = new THREE.LineLoop(geometry, material);
      ring.rotation.set(
        random() * Math.PI,
        random() * Math.PI,
        random() * 0.45 - 0.225,
      );
      ring.userData.speed = (random() * 0.11 + 0.025) * (random() < 0.5 ? -1 : 1);
      ring.userData.axis = random() < 0.5 ? "x" : "y";
      ring.renderOrder = 5;
      registryMaterials.push(material);
      registryGroup.add(ring);
    }

    const nodeCount = viewport.compact ? 28 : 52;
    const nodePositions = new Float32Array(nodeCount * 3);
    const nodeColors = new Float32Array(nodeCount * 3);
    for (let index = 0; index < nodeCount; index += 1) {
      const radius = 2.8 + random() * 4.1;
      const theta = random() * Math.PI * 2;
      const phi = Math.acos(2 * random() - 1);
      const color = new THREE.Color(ringColors[index % ringColors.length]);
      nodePositions[index * 3] = Math.sin(phi) * Math.cos(theta) * radius;
      nodePositions[index * 3 + 1] = Math.cos(phi) * radius * 0.72;
      nodePositions[index * 3 + 2] = Math.sin(phi) * Math.sin(theta) * radius;
      nodeColors[index * 3] = color.r;
      nodeColors[index * 3 + 1] = color.g;
      nodeColors[index * 3 + 2] = color.b;
    }
    const nodeGeometry = new THREE.BufferGeometry();
    nodeGeometry.setAttribute("position", new THREE.BufferAttribute(nodePositions, 3));
    nodeGeometry.setAttribute("color", new THREE.BufferAttribute(nodeColors, 3));
    const nodeMaterial = new THREE.PointsMaterial({
      size: viewport.compact ? 0.07 : 0.085,
      vertexColors: true,
      transparent: true,
      opacity: 0,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      sizeAttenuation: true,
    });
    registryMaterials.push(nodeMaterial);
    const nodes = new THREE.Points(nodeGeometry, nodeMaterial);
    nodes.renderOrder = 6;
    registryGroup.add(nodes);
  }

  function makePetalGeometry() {
    const shape = new THREE.Shape();
    shape.moveTo(0, -0.12);
    shape.bezierCurveTo(-0.2, -0.04, -0.24, 0.16, 0, 0.3);
    shape.bezierCurveTo(0.24, 0.16, 0.2, -0.04, 0, -0.12);
    const geometry = new THREE.ShapeGeometry(shape, 7);
    geometry.center();
    return geometry;
  }

  function buildPetals() {
    const geometry = makePetalGeometry();
    const materials = [
      new THREE.MeshBasicMaterial({ color: 0xffa8c1, transparent: true, opacity: 0, side: THREE.DoubleSide, depthTest: false }),
      new THREE.MeshBasicMaterial({ color: 0xff6c76, transparent: true, opacity: 0, side: THREE.DoubleSide, depthTest: false, blending: THREE.AdditiveBlending }),
    ];
    petalField = materials.map((material, groupIndex) => {
      const count = groupIndex === 0 ? (viewport.compact ? 22 : 32) : (viewport.compact ? 13 : 20);
      const mesh = new THREE.InstancedMesh(geometry, material, count);
      mesh.frustumCulled = false;
      mesh.renderOrder = 20 + groupIndex;
      const records = Array.from({ length: count }, () => ({
        nx: random(),
        ny: random(),
        z: -2 + random() * 9,
        delay: 9.4 + random() * 14.5,
        duration: 7.4 + random() * 9.2,
        drift: gaussian(0.8),
        sway: 0.8 + random() * 1.6,
        phase: random() * Math.PI * 2,
        spin: gaussian(1.3),
        scale: 0.34 + random() * 0.62,
      }));
      scene.add(mesh);
      return { mesh, material, records };
    });
  }

  function buildEmbers() {
    const count = viewport.compact ? 150 : 260;
    const positions = new Float32Array(count * 3);
    const colors = new Float32Array(count * 3);
    const palette = [new THREE.Color(0xff3b6f), new THREE.Color(0xffa238), new THREE.Color(0xffe083), new THREE.Color(0x53ece8)];
    const records = [];
    for (let index = 0; index < count; index += 1) {
      const color = palette[Math.floor(random() * palette.length)];
      colors[index * 3] = color.r;
      colors[index * 3 + 1] = color.g;
      colors[index * 3 + 2] = color.b;
      records.push({
        nx: 0.46 + random() * 0.6,
        ny: 0.08 + random() * 0.92,
        z: -2 + random() * 10,
        delay: 9.25 + random() * 17,
        duration: 4 + random() * 9,
        drift: gaussian(0.6),
        rise: random() < 0.72,
        phase: random() * Math.PI * 2,
      });
    }
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute("color", new THREE.BufferAttribute(colors, 3));
    const material = new THREE.PointsMaterial({
      size: viewport.compact ? 0.07 : 0.085,
      vertexColors: true,
      transparent: true,
      opacity: 0,
      depthWrite: false,
      depthTest: false,
      blending: THREE.AdditiveBlending,
      sizeAttenuation: true,
    });
    const points = new THREE.Points(geometry, material);
    points.frustumCulled = false;
    points.renderOrder = 19;
    scene.add(points);
    emberField = { points, geometry, material, records };
  }

  function buildScene() {
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(42, 1, 0.1, 80);
    camera.position.set(0, 0, 18);

    solarAnchor = new THREE.Group();
    accretionGroup = new THREE.Group();
    registryGroup = new THREE.Group();
    accretionGroup.rotation.set(0.54, 0.06, -0.17);
    scene.add(accretionGroup, solarAnchor, registryGroup);

    resizeScene();
    buildHydrogen();
    buildFeeders();
    buildInnerDisk();
    buildAbsorptionDisk();
    buildJets();
    buildFilaments();
    buildStar();
    buildProminences();
    buildCoronalRupture();
    buildRegistry();
    buildPetals();
    buildEmbers();
    buildDocumentTomography();
    resetVisuals();
  }

  function resizeScene() {
    if (!renderer || !camera) return;
    const visualViewport = view.visualViewport;
    viewport.width = visualViewport?.width || view.innerWidth;
    viewport.height = visualViewport?.height || view.innerHeight;
    viewport.offsetLeft = visualViewport?.offsetLeft || 0;
    viewport.offsetTop = visualViewport?.offsetTop || 0;
    viewport.compact = viewport.width < 980 || viewport.height < 610;
    viewport.narrow = viewport.width < 700;
    camera.aspect = viewport.width / viewport.height;
    camera.updateProjectionMatrix();
    const distance = camera.position.z;
    viewport.worldHeight = 2 * Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)) * distance;
    viewport.worldWidth = viewport.worldHeight * camera.aspect;
    const starX = viewport.worldWidth * (viewport.narrow ? 0.38 : viewport.compact ? 0.36 : 0.285);
    const starY = viewport.worldHeight * (viewport.narrow ? 0.14 : 0.145);
    solarAnchor.position.set(starX, starY, 0);
    accretionGroup.position.copy(solarAnchor.position);
    registryGroup.position.copy(solarAnchor.position);
    if (environment) {
      environment.style.top = `${viewport.offsetTop}px`;
      environment.style.left = `${viewport.offsetLeft}px`;
      environment.style.width = `${viewport.width}px`;
      environment.style.height = `${viewport.height}px`;
      environment.style.setProperty(
        "--heliogenesis-sun-x",
        `${(0.5 + starX / viewport.worldWidth) * 100}%`,
      );
      environment.style.setProperty(
        "--heliogenesis-sun-y",
        `${(0.5 - starY / viewport.worldHeight) * 100}%`,
      );
    }
    onSunPosition({
      x: `${viewport.offsetLeft + viewport.width * (0.5 + starX / viewport.worldWidth)}px`,
      y: `${viewport.offsetTop + viewport.height * (0.5 - starY / viewport.worldHeight)}px`,
    });
    renderer.setPixelRatio(Math.min(view.devicePixelRatio || 1, viewport.compact ? 1.35 : 1.65));
    renderer.setSize(viewport.width, viewport.height, false);
    if (hydrogen) hydrogen.material.uniforms.uPixelRatio.value = renderer.getPixelRatio();
    if (feederField) feederField.pointMaterial.uniforms.uPixelRatio.value = renderer.getPixelRatio();
    if (innerDiskField) innerDiskField.uniforms.uPixelRatio.value = renderer.getPixelRatio();
    if (jetField) jetField.material.uniforms.uPixelRatio.value = renderer.getPixelRatio();
    syncDocumentTomography();
  }

  function updatePetals(elapsedSeconds, alpha, collisionStrength) {
    const dummy = updatePetals.dummy || (updatePetals.dummy = new THREE.Object3D());
    petalField.forEach(({ mesh, material, records }, groupIndex) => {
      material.opacity = alpha * (groupIndex === 0 ? 0.68 : 0.78);
      records.forEach((record, index) => {
        const life = (elapsedSeconds - record.delay) / record.duration;
        if (life <= 0 || life >= 1 || motionQuery.matches) {
          dummy.scale.setScalar(0);
        } else {
          const x = (record.nx - 0.5) * viewport.worldWidth + record.drift * life + Math.sin(life * 7 * record.sway + record.phase) * 0.42;
          const y = viewport.worldHeight * (0.72 - life * 1.45) + (record.ny - 0.5) * 1.4;
          dummy.position.set(x, y, record.z);
          deflectFromDocumentation(dummy.position, collisionStrength, elapsedSeconds + record.phase);
          dummy.rotation.set(life * 5.2 + record.phase, life * record.spin * 4.4, Math.sin(life * 8 + record.phase));
          const flutter = 0.55 + Math.abs(Math.sin(life * 10 + record.phase)) * 0.62;
          dummy.scale.set(record.scale * flutter, record.scale, record.scale);
        }
        dummy.updateMatrix();
        mesh.setMatrixAt(index, dummy.matrix);
      });
      mesh.instanceMatrix.needsUpdate = true;
    });
  }

  function updateEmbers(elapsedSeconds, alpha, collisionStrength) {
    const positions = emberField.geometry.attributes.position.array;
    let visible = 0;
    emberField.records.forEach((record, index) => {
      const life = (elapsedSeconds - record.delay) / record.duration;
      const offset = index * 3;
      if (life <= 0 || life >= 1 || motionQuery.matches) {
        positions[offset] = 0;
        positions[offset + 1] = -100;
        positions[offset + 2] = 0;
        return;
      }
      const direction = record.rise ? 1 : -1;
      particleFlowPosition.set(
        (record.nx - 0.5) * viewport.worldWidth + record.drift * life +
          Math.sin(life * 8 + record.phase) * 0.28,
        (record.ny - 0.5) * viewport.worldHeight + direction * life *
          (1.2 + Math.abs(record.drift)),
        record.z,
      );
      deflectFromDocumentation(particleFlowPosition, collisionStrength, elapsedSeconds + record.phase);
      positions[offset] = particleFlowPosition.x;
      positions[offset + 1] = particleFlowPosition.y;
      positions[offset + 2] = particleFlowPosition.z;
      visible += 1;
    });
    emberField.geometry.attributes.position.needsUpdate = true;
    emberField.material.opacity = visible ? alpha * 0.86 : 0;
  }

  function applyVisuals(progress, elapsedSeconds, moving = true) {
    const collapse = smoothstep(0, 1, progress);
    const mass = smoothstep(0.035, 0.88, progress);
    const ignition = smoothstep(0.46, 0.92, progress);
    const assembly = smoothstep(0.12, 0.88, progress);
    const protostar = smoothstep(0.08, 0.5, progress) * 0.24 + ignition * 0.76;
    const compression = moving
      ? smoothstep(0.54, 0.66, progress) * (1 - smoothstep(0.69, 0.76, progress))
      : 0;
    const shockPhase = clamp01((progress - 0.68) / 0.16);
    const shockPulse = moving && shockPhase > 0 && shockPhase < 1
      ? Math.pow(Math.sin(shockPhase * Math.PI), 0.86)
      : 0;
    const volumePresence = moving
      ? smoothstep(0.018, 0.075, progress) * (1 - smoothstep(0.74, 0.9, progress))
      : 0;
    const surfacePresence = moving ? smoothstep(0.69, 0.86, progress) : 1;
    const jetPresence = moving
      ? smoothstep(0.6, 0.7, progress) * (1 - smoothstep(0.9, 0.98, progress))
      : 0;
    const focus = smoothstep(0.05, 0.32, progress) * (1 - smoothstep(0.52, 0.78, progress));
    const registryAlpha = smoothstep(0.56, 0.9, progress);
    const consequenceAlpha = smoothstep(0.61, 0.78, progress);
    const prominencePresence = moving
      ? smoothstep(0.6, 0.74, progress) * (0.68 + ignition * 0.32)
      : 1;
    const rupturePhase = moving ? clamp01((progress - 0.57) / 0.27) : 0;
    const rupturePresence = moving
      ? smoothstep(0.57, 0.625, progress) * (1 - smoothstep(0.87, 0.96, progress))
      : 0;
    const ruptureSnap = moving ? impactPulse(progress, 0.69, 0.105) : 0;
    const ruptureExpansion = smoothstep(0.08, 0.94, rupturePhase);
    const tomographyPresence = moving
      ? smoothstep(0.48, 0.61, progress) * (1 - smoothstep(0.88, 0.98, progress))
      : 0;
    const feederPresence = moving ? smoothstep(0.08, 0.4, progress) : 0;
    const innerDiskPresence = moving ? smoothstep(0.035, 0.18, progress) * 1.12 : 0;
    const dustPresence = moving ? smoothstep(0.12, 0.34, progress) * (0.84 - mass * 0.18) : 0;
    const dopplerPresence = moving ? smoothstep(0.1, 0.32, progress) * 0.94 : 0;
    const lensingPresence = moving
      ? smoothstep(0.4, 0.74, progress) * (0.72 + surfacePresence * 0.28)
      : 0;
    const settledCloudAlpha = 0.34 * (1 - smoothstep(0.72, 1, progress) * 0.48);
    const time = moving ? elapsedSeconds : 18.4;
    const eclipsePhase = moving
      ? clamp01((elapsedSeconds - 13.3) / 8.8)
      : 0.5;
    const eclipsePresence = moving
      ? smoothstep(0, 0.08, eclipsePhase) * (1 - smoothstep(0.92, 1, eclipsePhase))
      : progress > 0.5 ? 1 : 0;
    const eclipseTotality = eclipsePresence *
      (1 - smoothstep(0.025, 0.3, Math.abs(eclipsePhase - 0.5)));
    const impactA = moving ? impactPulse(progress, 0.2, 0.055) : 0;
    const impactB = moving ? impactPulse(progress, 0.41, 0.06) : 0;
    const impactC = moving ? impactPulse(progress, 0.61, 0.065) : 0;
    const impactD = moving ? impactPulse(progress, 0.82, 0.07) : 0;
    const impactEnergy = impactA + impactB + impactC + impactD;
    const retainedRadius =
      0.18 +
      smoothstep(0.04, 0.17, progress) * 0.1 +
      smoothstep(0.15, 0.3, progress) * 0.13 +
      smoothstep(0.28, 0.44, progress) * 0.16 +
      smoothstep(0.42, 0.59, progress) * 0.17 +
      smoothstep(0.57, 0.73, progress) * 0.15 +
      smoothstep(0.71, 0.86, progress) * 0.11;
    const breathing = moving
      ? Math.sin(time * 2.15) * 0.008 * mass * (1 - ignition * 0.62)
      : 0;
    const starScale = retainedRadius + impactEnergy * 0.045 + breathing;
    const displayedStarScale = starScale * (1 - compression * 0.16 + shockPulse * 0.042);

    hydrogen.material.uniforms.uTime.value = time;
    hydrogen.material.uniforms.uCollapse.value = collapse;
    hydrogen.material.uniforms.uGlobalAlpha.value = settledCloudAlpha;
    feederField.pointMaterial.uniforms.uTime.value = time;
    feederField.pointMaterial.uniforms.uPresence.value = feederPresence * 0.9;
    feederField.streakMaterial.uniforms.uTime.value = time;
    feederField.streakMaterial.uniforms.uPresence.value = feederPresence * 0.62;
    feederField.sheathMaterials.forEach((material) => {
      material.uniforms.uTime.value = time;
      material.uniforms.uPresence.value = feederPresence;
    });
    innerDiskField.uniforms.uTime.value = time;
    innerDiskField.uniforms.uPresence.value = innerDiskPresence;
    innerDiskField.uniforms.uStarRadius.value = displayedStarScale * 1.72;
    innerDiskField.uniforms.uMass.value = mass;
    innerDiskField.uniforms.uDoppler.value = dopplerPresence;
    innerDiskField.uniforms.uLensing.value = lensingPresence + rupturePresence * (0.38 + ruptureSnap * 0.72);
    absorptionDisk.material.uniforms.uTime.value = time;
    absorptionDisk.material.uniforms.uPresence.value = dustPresence;
    absorptionDisk.material.uniforms.uStarRadius.value = displayedStarScale * 1.72;
    protoVolume.visible = volumePresence > 0.002;
    protoVolume.material.uniforms.uTime.value = time;
    protoVolume.material.uniforms.uPresence.value = volumePresence;
    protoVolume.material.uniforms.uDensity.value =
      0.84 + mass * 1.04 + impactEnergy * 0.28 + compression * 0.72;
    protoVolume.material.uniforms.uMass.value = mass;
    protoVolume.material.uniforms.uIgnition.value = ignition;
    protoVolume.material.uniforms.uCompression.value = compression;
    protoVolume.material.uniforms.uShockPhase.value = shockPhase;
    protoVolume.material.uniforms.uShockPulse.value = shockPulse;
    protoVolume.material.uniforms.uImpacts.value.set(impactA, impactB, impactC, impactD);
    protoVolume.material.depthWrite = volumePresence > 0.01 &&
      surfacePresence < 0.08 && mass > 0.13;
    star.material.uniforms.uTime.value = time;
    star.material.uniforms.uIgnition.value = ignition;
    star.material.uniforms.uAssembly.value = assembly;
    star.material.uniforms.uSurfacePresence.value = surfacePresence;
    star.material.uniforms.uMass.value = mass;
    star.material.uniforms.uImpacts.value.set(impactA, impactB, impactC, impactD);
    star.material.uniforms.uEclipsePhase.value = eclipsePhase;
    star.material.uniforms.uEclipsePresence.value = eclipsePresence;
    atmosphere.material.uniforms.uIgnition.value = ignition;
    corona.material.uniforms.uTime.value = time;
    corona.material.uniforms.uIgnition.value = protostar;
    corona.material.uniforms.uEclipseTotality.value = eclipseTotality;
    gravityWell.material.uniforms.uTime.value = time;
    gravityWell.material.uniforms.uFocus.value = Math.max(
      focus,
      rupturePresence * (0.26 + ruptureSnap * 0.82),
    );
    ignitionShell.material.uniforms.uTime.value = time;
    ignitionShell.material.uniforms.uPulse.value = shockPulse;
    prominenceField.group.visible = prominencePresence > 0.002;
    prominenceField.group.scale.setScalar(displayedStarScale * (1 + shockPulse * 0.1));
    prominenceField.group.rotation.y = Math.sin(time * 0.13) * 0.055;
    prominenceField.group.rotation.z = Math.sin(time * 0.09) * 0.018;
    prominenceField.records.forEach(({ mesh, material, phase, pathIndex, core }) => {
      material.uniforms.uTime.value = time;
      material.uniforms.uPresence.value = prominencePresence * (core ? 0.96 : 0.8);
      material.uniforms.uEclipse.value = eclipseTotality;
      material.uniforms.uShock.value = shockPulse;
      material.uniforms.uRupture.value = rupturePhase;
      if (pathIndex === 0) {
        mesh.scale.set(1 + rupturePhase * 0.08, 1 + rupturePhase * 0.26, 1 + rupturePhase * 0.13);
        mesh.position.set(-rupturePhase * 0.16, rupturePhase * 0.09, rupturePhase * 0.18);
      } else {
        mesh.scale.setScalar(1);
        mesh.position.set(0, 0, 0);
      }
      mesh.rotation.x = Math.sin(time * 0.17 + phase) * 0.012;
      mesh.rotation.z = Math.sin(time * 0.11 + pathIndex * 1.7) * 0.009;
    });
    ruptureField.group.visible = rupturePresence > 0.002;
    ruptureField.group.scale.setScalar(displayedStarScale * (0.96 + ruptureSnap * 0.06));
    ruptureField.group.rotation.y = Math.sin(time * 0.16) * 0.045;
    ruptureField.group.rotation.z = -0.035 + Math.sin(time * 0.12) * 0.018;
    ruptureField.records.forEach(({ mesh, material, core, pathIndex }) => {
      material.uniforms.uTime.value = time;
      material.uniforms.uPresence.value = rupturePresence * (core ? 1 : 0.78);
      material.uniforms.uHead.value = smoothstep(
        pathIndex ? 0.08 : 0,
        pathIndex ? 0.96 : 0.88,
        rupturePhase,
      );
      material.uniforms.uSnap.value = ruptureSnap;
      mesh.position.y = Math.sin(time * 0.31 + pathIndex * 1.7) * rupturePresence * 0.035;
      mesh.rotation.z = Math.sin(time * 0.19 + pathIndex) * rupturePresence * 0.012;
    });
    ruptureField.shell.visible = rupturePresence > 0.002;
    ruptureField.shellMaterial.uniforms.uTime.value = time;
    ruptureField.shellMaterial.uniforms.uPresence.value = rupturePresence;
    ruptureField.shellMaterial.uniforms.uExpansion.value = ruptureExpansion;
    ruptureField.shellMaterial.uniforms.uSnap.value = ruptureSnap;
    ruptureField.shell.scale.setScalar(
      displayedStarScale * THREE.MathUtils.lerp(0.34, 1.88, ruptureExpansion),
    );
    ruptureField.shell.rotation.y = time * 0.04;
    ruptureField.shell.rotation.z = -time * 0.027;
    updateDocumentTomography(
      progress,
      time,
      moving,
      rupturePhase,
      ruptureSnap,
      tomographyPresence,
    );

    jetField.group.visible = jetPresence > 0.002;
    jetField.material.uniforms.uTime.value = time;
    jetField.material.uniforms.uPresence.value = jetPresence;
    jetField.material.uniforms.uShock.value = shockPulse;
    jetField.material.uniforms.uBaseRadius.value = displayedStarScale * 1.62;
    jetField.plumeRecords.forEach((record) => {
      record.material.uniforms.uTime.value = time;
      record.material.uniforms.uPresence.value = jetPresence;
      record.material.uniforms.uShock.value = shockPulse;
      record.mesh.position.y = record.side *
        (displayedStarScale * 1.58 + record.height * 0.5);
      record.mesh.scale.set(
        0.76 + shockPulse * 0.34,
        0.82 + shockPulse * 0.18,
        0.76 + shockPulse * 0.34,
      );
    });

    star.scale.setScalar(displayedStarScale);
    protoVolume.scale.setScalar(
      displayedStarScale * THREE.MathUtils.lerp(1.42, 1.055, assembly) *
      (1 + impactEnergy * 0.035),
    );
    atmosphere.scale.setScalar(displayedStarScale * THREE.MathUtils.lerp(0.92, 1, ignition));
    corona.scale.setScalar(displayedStarScale * THREE.MathUtils.lerp(0.78, 1, ignition) *
      (1 + eclipseTotality * 0.16));
    ignitionShell.scale.setScalar(displayedStarScale * (0.62 + shockPhase * 1.1));
    ignitionShell.rotation.y = time * 0.13;
    ignitionShell.rotation.z = -time * 0.07;
    star.rotation.y = time * (0.022 + mass * 0.074);
    star.rotation.x = Math.sin(time * 0.08) * 0.09;
    star.material.depthWrite = surfacePresence > 0.52;
    protoVolume.rotation.y = -time * (0.025 + mass * 0.045);
    protoVolume.rotation.x = Math.sin(time * 0.11) * 0.055;
    protoVolume.rotation.z = Math.sin(time * 0.07) * 0.035;
    protoVolume.updateWorldMatrix(true, false);
    volumeCameraLocal.copy(camera.position);
    protoVolume.worldToLocal(volumeCameraLocal);
    protoVolume.material.uniforms.uCameraLocal.value.copy(volumeCameraLocal);
    accretionGroup.rotation.y = time * 0.018 + ruptureSnap * 0.055;
    accretionGroup.rotation.z = -0.17 + Math.sin(time * 0.4) * rupturePresence * 0.018;
    jetField.group.rotation.y = time * 0.026;
    updateAccretionKnots(progress, time, moving, displayedStarScale);
    updateProtoFragments(progress, time, moving, displayedStarScale);

    filamentMaterials.forEach((material, index) => {
      material.uniforms.uTime.value = time;
      material.uniforms.uCollapse.value = collapse;
      material.uniforms.uAlpha.value = 0.055 + (index % 4 === 2 ? 0.035 : 0);
    });

    registryMaterials.forEach((material, index) => {
      material.opacity = registryAlpha * (index === registryMaterials.length - 1 ? 0.86 : 0.22);
    });
    registryGroup.children.forEach((object, index) => {
      if (!object.userData.axis) return;
      object.rotation[object.userData.axis] += object.userData.speed * 0.0045;
      object.rotation.z += Math.sin(time * 0.07 + index) * 0.00018;
    });

    updatePetals(elapsedSeconds, consequenceAlpha, tomographyPresence);
    updateEmbers(elapsedSeconds, consequenceAlpha, tomographyPresence);
  }

  function resetVisuals() {
    if (!renderer || !hydrogen) return;
    applyVisuals(0, 0, false);
    hydrogen.material.uniforms.uGlobalAlpha.value = 0;
    feederField.pointMaterial.uniforms.uPresence.value = 0;
    feederField.streakMaterial.uniforms.uPresence.value = 0;
    feederField.sheathMaterials.forEach((material) => { material.uniforms.uPresence.value = 0; });
    registryMaterials.forEach((material) => { material.opacity = 0; });
    petalField.forEach(({ material }) => { material.opacity = 0; });
    emberField.material.opacity = 0;
    renderer.clear();
  }

  function renderFrame(now) {
    if (mode !== "animated") return;
    const elapsedMs = now - eventStartedAt;
    const elapsedSeconds = elapsedMs / 1000;
    const progress = clamp01(elapsedMs / riseDuration);
    const timelineSeconds = elapsedSeconds * 15000 / riseDuration;
    applyVisuals(progress, timelineSeconds, true);
    draw();
    animationFrame = view.requestAnimationFrame(renderFrame);
  }

  function draw() {
    renderer.render(scene, camera);
    renderedFrames += 1;
  }

  function stop({ clear = true } = {}) {
    if (animationFrame !== null) view.cancelAnimationFrame(animationFrame);
    animationFrame = null;
    mode = "idle";
    if (clear) resetVisuals();
  }

  function start({ rise = 15000 } = {}) {
    const startTime = view.performance.now();
    stop();
    riseDuration = Math.max(1, Number(rise) || 15000);
    eventStartedAt = startTime;
    resizeScene();
    mode = "animated";
    animationFrame = view.requestAnimationFrame(renderFrame);
  }

  function showReduced() {
    stop({ clear: false });
    resizeScene();
    mode = "static";
    applyVisuals(0.88, 18.4, false);
    hydrogen.material.uniforms.uGlobalAlpha.value = 0;
    feederField.pointMaterial.uniforms.uPresence.value = 0;
    feederField.streakMaterial.uniforms.uPresence.value = 0;
    feederField.sheathMaterials.forEach((material) => { material.uniforms.uPresence.value = 0; });
    petalField.forEach(({ material }) => { material.opacity = 0; });
    emberField.material.opacity = 0;
    draw();
  }

  function resize() {
    resizeScene();
    if (mode !== "idle") draw();
  }

  function syncDocumentGeometry() {
    syncDocumentTomography();
    if (mode !== "idle") draw();
  }

  function getTomographyDiagnostics() {
    return Object.freeze({
      flowObstacles: tomographyField?.colliders.length || 0,
      sampledElements: tomographyField?.records.length || 0,
      synchronizations: tomographySynchronizations,
      visible: Boolean(tomographyField?.group.visible),
    });
  }

  function getPhotosphereDiagnostics() {
    const attributes = star?.geometry?.attributes || {};
    const synthwaveShader = Boolean(star?.material?.defines?.HELIOGENESIS_SYNTHWAVE);
    const transmutationShader = Boolean(star?.material?.defines?.HELIOGENESIS_TRANSMUTATION);
    return Object.freeze({
      hasSignalAttributes: Boolean(
        attributes.aBarycentric && attributes.aFacetCharge && attributes.aFacetPhase
      ),
      shaderVariant: synthwaveShader
        ? "synthwave"
        : transmutationShader ? "transmutation" : "natural",
      style: sunStyle,
      vertexCount: attributes.position?.count || 0,
    });
  }

  function disposeSceneResources() {
    const disposedGeometries = new Set();
    const disposedMaterials = new Set();
    const disposedTextures = new Set();
    scene?.traverse((object) => {
      if (object.geometry?.dispose && !disposedGeometries.has(object.geometry)) {
        disposedGeometries.add(object.geometry);
        object.geometry.dispose();
      }
      const materials = Array.isArray(object.material) ? object.material : [object.material];
      materials.filter(Boolean).forEach((material) => {
        if (disposedMaterials.has(material)) return;
        disposedMaterials.add(material);
        Object.values(material).forEach((value) => {
          if (value?.isTexture && !disposedTextures.has(value)) {
            disposedTextures.add(value);
            value.dispose();
          }
        });
        material.dispose();
      });
    });
    scene?.clear();
    renderer?.dispose();
    renderer = null;
    scene = null;
    tomographyField = null;
    tomographySynchronizations = 0;
  }

  function destroy() {
    if (animationFrame !== null) view.cancelAnimationFrame(animationFrame);
    animationFrame = null;
    mode = "idle";
    disposeSceneResources();
  }

  try {
    makeRenderer();
    buildScene();
  } catch (error) {
    disposeSceneResources();
    throw error;
  }

  const quality = viewport.narrow ? "narrow" : viewport.compact ? "compact" : "desktop";

  return Object.freeze({
    destroy,
    getPhotosphereDiagnostics,
    getTomographyDiagnostics,
    quality,
    get renderedFrames() {
      return renderedFrames;
    },
    resize,
    reset: stop,
    showReduced,
    start,
    sunStyle,
    syncDocumentGeometry,
  });
}
