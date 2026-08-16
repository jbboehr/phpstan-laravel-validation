import { DEFAULT_SUN_STYLE, SUN_STYLES, resolveSunStyle } from "./heliogenesis-options.js";

const DEFAULT_TIMINGS = Object.freeze({
  standard: Object.freeze({ rise: 15000, hold: 9000, return: 7000 }),
  reduced: Object.freeze({ rise: 1400, hold: 3000, return: 1900 }),
});

const STATUS_TEXT = Object.freeze({
  dawning: "A second light is forming.",
  radiant: "The Second Sun is present.",
  receding: "The second light is receding.",
  idle: "The temporary environmental event has ended.",
  unavailable: "The visual event could not initialize in this browser.",
});

function resolveElement(value, document, name) {
  const element = typeof value === "string" ? document.querySelector(value) : value;
  if (!(element instanceof document.defaultView.Element)) {
    throw new TypeError(`Heliogenesis requires a valid ${name}.`);
  }
  return element;
}

function mergeTimings(overrides = {}) {
  return {
    standard: { ...DEFAULT_TIMINGS.standard, ...overrides.standard },
    reduced: { ...DEFAULT_TIMINGS.reduced, ...overrides.reduced },
  };
}

function createLayer(document) {
  const environment = document.createElement("div");
  environment.className = "heliogenesis-environment";
  environment.dataset.heliogenesisEnvironment = "";
  environment.setAttribute("aria-hidden", "true");

  const worldLight = document.createElement("div");
  worldLight.className = "heliogenesis-world-light";

  const horizon = document.createElement("div");
  horizon.className = "heliogenesis-horizon";

  const canvas = document.createElement("canvas");
  canvas.className = "heliogenesis-stage";

  const ignitionFront = document.createElement("div");
  ignitionFront.className = "heliogenesis-ignition-front";

  const vigil = document.createElement("div");
  vigil.className = "heliogenesis-vigil";

  environment.append(worldLight, horizon, canvas, ignitionFront, vigil);
  return { environment, canvas };
}

function createStatus(document) {
  const status = document.createElement("span");
  status.className = "heliogenesis-sr-only";
  status.dataset.heliogenesisStatus = "";
  status.setAttribute("role", "status");
  status.setAttribute("aria-live", "polite");
  return status;
}

/**
 * Mount and control one Dawning of the Second Sun event in a browser document.
 */
export class Heliogenesis extends EventTarget {
  constructor({
    trigger,
    root,
    mount,
    status,
    timings,
    motionQuery,
    sunStyle,
  } = {}) {
    super();
    const document = trigger?.ownerDocument || globalThis.document;
    if (!document) throw new Error("Heliogenesis requires a browser document.");

    this.document = document;
    this.trigger = resolveElement(trigger, document, "trigger element");
    if (!(this.trigger instanceof document.defaultView.HTMLButtonElement)) {
      throw new TypeError("The Heliogenesis trigger must be a button element.");
    }
    this.root = root ? resolveElement(root, document, "state root") : document.documentElement;
    this.mountNode = mount ? resolveElement(mount, document, "mount element") : document.body;
    this.status = status ? resolveElement(status, document, "status element") : createStatus(document);
    this.ownsStatus = !status;
    this.timings = mergeTimings(timings);
    this.motionQuery = motionQuery || document.defaultView.matchMedia("(prefers-reduced-motion: reduce)");
    this.sunStyle = resolveSunStyle(sunStyle);

    this.state = "idle";
    this.scene = null;
    this.preparing = null;
    this.layer = null;
    this.canvas = null;
    this.abortController = null;
    this.timers = new Set();
    this.resizeTimer = null;
    this.documentSyncTimer = null;
    this.mounted = false;
    this.failed = false;
    this.failure = null;
    this.previousRootState = this.root.getAttribute("data-heliogenesis-state");
    this.previousRootIgnition = this.root.getAttribute("data-heliogenesis-ignition");
    this.previousRootRise = this.root.style.getPropertyValue("--heliogenesis-rise");
    this.previousRootRisePriority = this.root.style.getPropertyPriority("--heliogenesis-rise");
    this.previousRootSunX = this.root.style.getPropertyValue("--heliogenesis-sun-x");
    this.previousRootSunXPriority = this.root.style.getPropertyPriority("--heliogenesis-sun-x");
    this.previousRootSunY = this.root.style.getPropertyValue("--heliogenesis-sun-y");
    this.previousRootSunYPriority = this.root.style.getPropertyPriority("--heliogenesis-sun-y");
    this.initialDisabled = Boolean(this.trigger.disabled);
    this.initialPressed = this.trigger.getAttribute("aria-pressed");
    this.hadTriggerHook = this.trigger.hasAttribute("data-heliogenesis-trigger");
    this.hadUnavailableHook = this.trigger.hasAttribute("data-heliogenesis-unavailable");
  }

  mount() {
    if (this.mounted) return this;
    if (this.document.querySelector("[data-heliogenesis-environment]")) {
      throw new Error("Only one Heliogenesis environment may be mounted in a document.");
    }

    const { environment, canvas } = createLayer(this.document);
    this.layer = environment;
    this.canvas = canvas;
    this.mountNode.append(environment);
    if (this.ownsStatus) this.mountNode.append(this.status);

    this.abortController = new this.document.defaultView.AbortController();
    const { signal } = this.abortController;
    this.trigger.dataset.heliogenesisTrigger = "";
    this.trigger.setAttribute("aria-pressed", "false");
    this.trigger.addEventListener("click", () => void this.activate(), { signal });
    this.trigger.addEventListener("pointerenter", () => {
      if (!this.failed) void this.prepare().catch((error) => this.fail(error));
    }, { signal });
    this.trigger.addEventListener("focus", () => {
      if (!this.failed) void this.prepare().catch((error) => this.fail(error));
    }, { signal });
    this.document.defaultView.addEventListener("resize", () => this.queueResize(), { signal });
    this.document.defaultView.addEventListener("scroll", () => this.queueDocumentSync(), {
      signal,
      passive: true,
    });
    this.document.defaultView.visualViewport?.addEventListener("resize", () => this.queueResize(), { signal });
    this.document.defaultView.visualViewport?.addEventListener("scroll", () => this.queueResize(), { signal });
    this.document.addEventListener("visibilitychange", () => {
      if (this.document.hidden && this.state !== "idle") this.reset();
    }, { signal });
    this.motionQuery.addEventListener("change", () => {
      if (this.state !== "idle") this.reset();
    }, { signal });

    this.mounted = true;
    this.setState("idle", { announce: false });
    return this;
  }

  async prepare() {
    if (!this.mounted) throw new Error("Mount Heliogenesis before preparing it.");
    if (this.failed) throw this.failure || new Error("Heliogenesis is unavailable.");
    if (this.scene) return this.scene;
    if (this.preparing) return this.preparing;

    this.preparing = import("./heliogenesis-scene.js")
      .then(({ createHeliogenesisScene }) => {
        if (!this.mounted) return null;
        this.scene = createHeliogenesisScene({
          canvas: this.canvas,
          reducedMotion: () => this.motionQuery.matches,
          sunStyle: this.sunStyle,
          onSunPosition: ({ x, y }) => {
            this.root.style.setProperty("--heliogenesis-sun-x", x);
            this.root.style.setProperty("--heliogenesis-sun-y", y);
          },
        });
        return this.scene;
      })
      .finally(() => {
        this.preparing = null;
      });

    return this.preparing;
  }

  async activate() {
    if (!this.mounted || this.state !== "idle" || this.failed || this.trigger.disabled) return false;
    const reduced = this.motionQuery.matches;
    const timing = reduced ? this.timings.reduced : this.timings.standard;

    this.clearTimers();
    this.setTimingStyle(timing);
    this.trigger.disabled = true;
    this.trigger.setAttribute("aria-pressed", "true");
    this.setState("dawning");

    try {
      const scene = await this.prepare();
      if (!scene || !this.mounted || this.state !== "dawning") return false;
      this.setIgnition(true);
      if (reduced) scene.showReduced();
      else scene.start({ rise: timing.rise });
    } catch (error) {
      this.fail(error);
      return false;
    }

    this.schedule(() => {
      this.setIgnition(false);
      this.setState("radiant");
    }, timing.rise);
    this.schedule(() => this.setState("receding"), timing.rise + timing.hold);
    this.schedule(() => this.reset(), timing.rise + timing.hold + timing.return);
    return true;
  }

  reset({ announce = true } = {}) {
    this.clearTimers();
    this.clearDebouncedTimers();
    this.setIgnition(false);
    if (this.failed) {
      if (this.state !== "idle") this.setState("idle", { announce: false });
      this.status.textContent = STATUS_TEXT.unavailable;
      this.trigger.disabled = true;
      this.trigger.setAttribute("aria-pressed", "false");
      return;
    }
    this.scene?.reset();
    this.setState("idle", { announce });
    this.trigger.disabled = this.failed || this.initialDisabled;
    this.trigger.setAttribute("aria-pressed", "false");
  }

  destroy() {
    if (!this.mounted) return;
    this.clearTimers();
    this.clearDebouncedTimers();
    this.abortController.abort();
    this.scene?.destroy();
    this.layer.remove();
    if (this.ownsStatus) this.status.remove();

    if (this.previousRootState === null) this.root.removeAttribute("data-heliogenesis-state");
    else this.root.setAttribute("data-heliogenesis-state", this.previousRootState);
    if (this.previousRootIgnition === null) this.root.removeAttribute("data-heliogenesis-ignition");
    else this.root.setAttribute("data-heliogenesis-ignition", this.previousRootIgnition);
    if (this.previousRootRise) {
      this.root.style.setProperty(
        "--heliogenesis-rise",
        this.previousRootRise,
        this.previousRootRisePriority,
      );
    } else {
      this.root.style.removeProperty("--heliogenesis-rise");
    }
    if (this.previousRootSunX) {
      this.root.style.setProperty(
        "--heliogenesis-sun-x",
        this.previousRootSunX,
        this.previousRootSunXPriority,
      );
    } else {
      this.root.style.removeProperty("--heliogenesis-sun-x");
    }
    if (this.previousRootSunY) {
      this.root.style.setProperty(
        "--heliogenesis-sun-y",
        this.previousRootSunY,
        this.previousRootSunYPriority,
      );
    } else {
      this.root.style.removeProperty("--heliogenesis-sun-y");
    }
    this.trigger.disabled = this.initialDisabled;
    if (this.initialPressed === null) this.trigger.removeAttribute("aria-pressed");
    else this.trigger.setAttribute("aria-pressed", this.initialPressed);
    if (!this.hadTriggerHook) this.trigger.removeAttribute("data-heliogenesis-trigger");
    if (!this.hadUnavailableHook) this.trigger.removeAttribute("data-heliogenesis-unavailable");

    this.scene = null;
    this.layer = null;
    this.canvas = null;
    this.mounted = false;
  }

  schedule(callback, delay) {
    const timer = this.document.defaultView.setTimeout(() => {
      this.timers.delete(timer);
      callback();
    }, Math.max(0, delay));
    this.timers.add(timer);
  }

  clearTimers() {
    this.timers.forEach((timer) => this.document.defaultView.clearTimeout(timer));
    this.timers.clear();
  }

  clearDebouncedTimers() {
    if (this.resizeTimer !== null) {
      this.document.defaultView.clearTimeout(this.resizeTimer);
    }
    if (this.documentSyncTimer !== null) {
      this.document.defaultView.clearTimeout(this.documentSyncTimer);
    }
    this.resizeTimer = null;
    this.documentSyncTimer = null;
  }

  setTimingStyle(timing) {
    const rise = `${Math.max(1, Number(timing.rise) || DEFAULT_TIMINGS.standard.rise)}ms`;
    this.root.style.setProperty("--heliogenesis-rise", rise);
    this.layer?.style.setProperty("--heliogenesis-rise", rise);
  }

  queueResize() {
    if (!this.scene) return;
    if (this.documentSyncTimer !== null) {
      this.document.defaultView.clearTimeout(this.documentSyncTimer);
      this.documentSyncTimer = null;
    }
    if (this.resizeTimer !== null) this.document.defaultView.clearTimeout(this.resizeTimer);
    this.resizeTimer = this.document.defaultView.setTimeout(() => {
      this.resizeTimer = null;
      this.scene?.resize();
    }, 140);
  }

  queueDocumentSync() {
    if (!this.scene || this.state === "idle" || this.resizeTimer !== null) return;
    if (this.documentSyncTimer !== null) {
      this.document.defaultView.clearTimeout(this.documentSyncTimer);
    }
    this.documentSyncTimer = this.document.defaultView.setTimeout(() => {
      this.documentSyncTimer = null;
      if (this.state !== "idle") this.scene?.syncDocumentGeometry();
    }, 140);
  }

  setState(nextState, { announce = true } = {}) {
    this.state = nextState;
    this.root.dataset.heliogenesisState = nextState;
    if (this.layer) this.layer.dataset.heliogenesisState = nextState;
    if (announce) this.status.textContent = STATUS_TEXT[nextState] || "";

    this.dispatchLifecycle(nextState);
  }

  setIgnition(active) {
    this.root.toggleAttribute("data-heliogenesis-ignition", active);
    this.layer?.toggleAttribute("data-heliogenesis-ignition", active);
  }

  dispatchLifecycle(name, extraDetail = {}) {
    const EventConstructor = this.document.defaultView.CustomEvent;
    const eventName = `heliogenesis:${name}`;
    const detail = { heliogenesis: this, state: this.state, ...extraDetail };
    this.dispatchEvent(new EventConstructor(eventName, { detail }));
    this.root.dispatchEvent(new EventConstructor(eventName, { detail }));
  }

  fail(error) {
    if (this.failed) return;
    this.failed = true;
    this.failure = error instanceof Error ? error : new Error(String(error));
    this.clearTimers();
    this.clearDebouncedTimers();
    this.setIgnition(false);
    if (this.scene) {
      try {
        this.scene.destroy();
      } catch (disposalError) {
        console.error("Unable to completely dispose Heliogenesis after failure.", disposalError);
      }
      this.scene = null;
    }
    if (this.state !== "idle") this.setState("idle", { announce: false });
    else {
      this.root.dataset.heliogenesisState = "idle";
      if (this.layer) this.layer.dataset.heliogenesisState = "idle";
    }
    this.status.textContent = STATUS_TEXT.unavailable;
    this.trigger.disabled = true;
    this.trigger.dataset.heliogenesisUnavailable = "";
    this.trigger.setAttribute("aria-pressed", "false");
    this.dispatchLifecycle("unavailable", { error: this.failure });
    console.error("Unable to initialize Heliogenesis.", error);
  }
}

export { DEFAULT_SUN_STYLE, DEFAULT_TIMINGS, SUN_STYLES };
