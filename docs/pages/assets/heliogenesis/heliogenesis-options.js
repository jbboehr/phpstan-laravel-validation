export const DEFAULT_SUN_STYLE = "synthwave";
export const SUN_STYLES = Object.freeze([DEFAULT_SUN_STYLE, "transmutation", "natural"]);

export function resolveSunStyle(value = DEFAULT_SUN_STYLE) {
  if (!SUN_STYLES.includes(value)) {
    throw new TypeError(`Heliogenesis sunStyle must be one of: ${SUN_STYLES.join(", ")}.`);
  }
  return value;
}
