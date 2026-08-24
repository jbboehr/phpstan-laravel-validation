/* global path_to_root */

(function () {
    "use strict";

    // mdBook only supplies headings for the active page. Keep this outline
    // synchronized with public h2/h3 headings.
    const headingsByChapter = {
        "index.html": [{ id: "start-here", title: "Start here" }],
        "getting-started.html": [
            { id: "installation", title: "Installation" },
            { id: "first-analysis", title: "First analysis" },
            { id: "common-first-options", title: "Common first options" },
            { id: "compatibility", title: "Compatibility" },
            { id: "next", title: "Next" },
        ],
        "guides/inferred-types.html": [
            { id: "soundness", title: "Soundness" },
            { id: "presence-is-separate-from-the-value-type", title: "Presence is separate from the value type" },
            { id: "nested-arrays-and-wildcards", title: "Nested arrays and wildcards" },
            { id: "when-inference-stays-conservative", title: "When inference stays conservative" },
            { id: "input-refinement-after-validate", title: "Input refinement after validate()" },
        ],
        "guides/parsing-validated-output.html": [
            { id: "installation-and-compatibility", title: "Installation and compatibility" },
            { id: "basic-use", title: "Basic use" },
            {
                id: "exact-parser-grammars",
                title: "Exact parser grammars",
                children: [
                    { id: "parseinteger", title: "Parse::integer()" },
                    { id: "parsefloat", title: "Parse::float()" },
                    { id: "parsestring", title: "Parse::string()" },
                    { id: "parseboolean", title: "Parse::boolean()" },
                    { id: "parseaccepted", title: "Parse::accepted()" },
                    { id: "parsedeclined", title: "Parse::declined()" },
                    { id: "parseenum", title: "Parse::enum()" },
                    { id: "parsedatetime", title: "Parse::dateTime()" },
                    { id: "parsetimezone", title: "Parse::timezone()" },
                ],
            },
            { id: "presence-and-adjacent-laravel-rules", title: "Presence and adjacent Laravel rules" },
            { id: "which-values-each-phase-observes", title: "Which values each phase observes" },
            { id: "formrequests", title: "FormRequests" },
            { id: "lifecycle-and-soundness-limits", title: "Lifecycle and soundness limits" },
        ],
        "guides/laravel-validation-and-type-safety.html": [
            { id: "tldr", title: "TL;DR" },
            { id: "laravel-validation-is-not-typed-parsing", title: "Laravel validation is not typed parsing" },
            { id: "optionality-changes-the-accepted-value-domain", title: "Optionality changes the accepted value domain" },
            {
                id: "validation-is-also-projection",
                title: "Validation is also projection",
                children: [
                    { id: "exclusion-rules-remove-accepted-input", title: "Exclusion rules remove accepted input" },
                    { id: "nested-rules-decide-which-keys-survive", title: "Nested rules decide which keys survive" },
                ],
            },
            {
                id: "rules-are-runtime-programs-not-static-schemas",
                title: "Rules are runtime programs, not static schemas",
                children: [
                    { id: "cross-field-rules-require-correlated-types", title: "Cross-field rules require correlated types" },
                    { id: "wildcards-are-quantified-traversal", title: "Wildcards are quantified traversal" },
                    { id: "the-language-is-open-ended-at-runtime", title: "The language is open-ended at runtime" },
                    {
                        id: "one-string-language-combines-unrelated-responsibilities",
                        title: "One string language combines unrelated responsibilities",
                    },
                ],
            },
            { id: "soundness-versus-precision", title: "Soundness versus precision" },
            { id: "what-static-analysis-can-salvage", title: "What static analysis can salvage" },
            { id: "architectural-alternatives-for-new-code", title: "Architectural alternatives for new code" },
            { id: "verification-methodology", title: "Verification methodology" },
            { id: "conclusion", title: "Conclusion" },
        ],
        "guides/form-requests.html": [
            { id: "what-is-inferred", title: "What is inferred" },
            { id: "lifecycle-hooks", title: "Lifecycle hooks" },
            { id: "trusted-classes", title: "Trusted classes" },
            { id: "discovery", title: "Discovery" },
            { id: "validatedkey-and-safe", title: "validated($key) and safe()" },
            { id: "residual-assumptions", title: "Residual assumptions" },
        ],
        "guides/custom-rules.html": [
            { id: "attribute", title: "Attribute" },
            { id: "phpdoc", title: "PHPDoc" },
            { id: "configuration", title: "Configuration" },
            { id: "what-a-contract-does-not-do", title: "What a contract does not do" },
        ],
        "reference/configuration.html": [
            { id: "laravelversion", title: "laravelVersion" },
            { id: "assumehttpinputnormalization", title: "assumeHttpInputNormalization" },
            { id: "includeunvalidatedarraykeys", title: "includeUnvalidatedArrayKeys" },
            { id: "experimentalconditionalpresenceinference", title: "experimentalConditionalPresenceInference" },
            { id: "form-requests", title: "Form requests" },
            { id: "custom-rules", title: "Custom rules" },
        ],
        "reference/entry-points.html": [
            { id: "validator-mutation-and-contract-invalidation", title: "Validator mutation" },
            { id: "input-refinement", title: "Input refinement" },
        ],
        "reference/static-resolvability.html": [
            { id: "what-must-be-visible", title: "What must be visible" },
            { id: "shared-conservative-fallbacks", title: "Shared conservative fallbacks" },
            { id: "lookalike-factories", title: "Lookalike factories" },
            { id: "empty-and-false-builder-conditions", title: "Empty and false builder conditions" },
            { id: "custom-predicates-without-a-contract", title: "Custom predicates without a contract" },
        ],
        "reference/validation-rules.html": [
            { id: "exact-accepted-sets", title: "Exact accepted sets" },
            { id: "native-strings", title: "Native strings" },
            { id: "coercive-text", title: "Coercive text" },
            { id: "dates", title: "Dates" },
            { id: "numbers", title: "Numbers" },
            { id: "arrays", title: "Arrays" },
            { id: "files", title: "Files" },
            { id: "enum", title: "Enum" },
            { id: "neutral-rules", title: "Neutral rules" },
            { id: "conservative-mixed-fallbacks", title: "Conservative mixed fallbacks" },
            { id: "adjacent-rule-refinement", title: "Adjacent-rule refinement" },
        ],
        "reference/rule-builders.html": [
            { id: "enum", title: "Enum" },
            { id: "rulein", title: "Rule::in()" },
            { id: "rulenotin", title: "Rule::notIn()" },
            { id: "literal-conditional-builders", title: "Literal conditional builders" },
            { id: "rulearray", title: "Rule::array()" },
            { id: "rulearraykeys", title: "Rule::arrayKeys()" },
            { id: "array-predicates", title: "Array predicates" },
            { id: "numeric-builders", title: "Numeric builders" },
            { id: "string-builders", title: "String builders" },
            { id: "date-builders", title: "Date builders" },
            { id: "dimensions", title: "Dimensions" },
            { id: "file-builders", title: "File builders" },
            { id: "database-builders", title: "Database builders" },
        ],
        "reference/presence-and-projection.html": [
            { id: "presence", title: "Presence" },
            { id: "nested-reconstruction", title: "Nested reconstruction" },
            { id: "wildcards", title: "Wildcards" },
            { id: "conditional-presence", title: "Conditional presence" },
            { id: "numeric-rule-keys", title: "Numeric rule keys" },
            { id: "literal-integer-keys-in-output", title: "Literal integer keys in output" },
        ],
        "reference/limitations.html": [
            { id: "value-families", title: "Value families" },
            { id: "custom-rules", title: "Custom rules" },
            { id: "formrequest-lifecycle", title: "FormRequest lifecycle" },
            { id: "application-execution", title: "Application execution" },
            { id: "mixed-factory-modes", title: "Mixed factory modes" },
            { id: "validator-aliases-and-lifecycle-state", title: "Validator aliases and lifecycle state" },
            { id: "what-the-test-suite-does-not-prove", title: "What the test suite does not prove" },
        ],
        "reference/laravel-versions.html": [
            { id: "contract-changes-in-the-portable-corpus", title: "Contract changes in the portable corpus" },
            {
                id: "rules-and-builders-introduced-in-the-supported-range",
                title: "Rules and builders introduced in the supported range",
            },
            { id: "how-version-is-chosen", title: "How version is chosen" },
        ],
        "reference/validation-rule-coverage.html": [
            { id: "scope-and-evidence", title: "Scope and evidence" },
            { id: "status-definitions", title: "Status definitions" },
            { id: "summary", title: "Summary" },
            { id: "rules-with-direct-accepted-value-inference", title: "Rules with direct accepted-value inference" },
            { id: "explicitly-neutral-rules", title: "Explicitly neutral rules" },
            { id: "rules-currently-falling-back-to-mixed", title: "Rules currently falling back to mixed" },
            { id: "presence-and-output-shape-findings", title: "Presence and output-shape findings" },
            { id: "built-in-rule-objects-and-fluent-builders", title: "Built-in rule objects and fluent builders" },
            {
                id: "prioritized-work",
                title: "Prioritized work",
                children: [
                    {
                        id: "1-extend-the-experimental-conditional-presence-model",
                        title: "1. Extend the experimental conditional presence model",
                    },
                    {
                        id: "2-complete-statically-resolvable-built-in-builders",
                        title: "2. Complete statically resolvable built-in builders",
                    },
                ],
            },
            { id: "what-the-survey-did-not-find", title: "What the survey did not find" },
            { id: "relevant-project-evidence", title: "Relevant project evidence" },
        ],
        "contributing/testing.html": [
            { id: "choose-the-smallest-useful-test", title: "Choose the smallest useful test" },
            { id: "adding-or-changing-inference", title: "Adding or changing inference" },
            { id: "focused-runtime-cases", title: "Focused runtime cases" },
            { id: "static-inference-fixtures", title: "Static inference fixtures" },
            { id: "named-property-catalogs", title: "Named property catalogs" },
            { id: "deterministic-audit-cases", title: "Deterministic audit cases" },
            {
                id: "portable-cross-version-matrix",
                title: "Portable cross-version matrix",
                children: [{ id: "nix-profile-shell-convenience", title: "Nix profile-shell convenience" }],
            },
            { id: "before-submitting-an-inference-change", title: "Before submitting an inference change" },
        ],
        "contributing/laravel-version-inference-audit.html": [
            { id: "result", title: "Result" },
            { id: "what-was-audited", title: "What was audited" },
            { id: "important-version-boundaries", title: "Important version boundaries" },
            { id: "where-uncertainty-remains", title: "Where uncertainty remains" },
            { id: "builder-boundary-evidence", title: "Builder-boundary evidence" },
            { id: "audited-releases", title: "Audited releases" },
            { id: "method", title: "Method" },
            { id: "inventory", title: "Inventory" },
            {
                id: "findings",
                title: "Findings",
                children: [
                    {
                        id: "laravel-12-preserves-top-level-numeric-rule-keys",
                        title: "Laravel 12 preserves top-level numeric rule keys",
                    },
                    { id: "laravel-1222-changes-integerstrict", title: "Laravel 12.22 changes integer:strict" },
                    { id: "laravel-134-changes-ascii", title: "Laravel 13.4 changes ascii" },
                    { id: "hex_color-has-two-release-boundaries", title: "hex_color has two release boundaries" },
                    { id: "extensions-begins-in-laravel-1034", title: "extensions begins in Laravel 10.34" },
                    { id: "encoding-begins-in-laravel-1240", title: "encoding begins in Laravel 12.40" },
                    { id: "base64-begins-in-laravel-1321", title: "base64 begins in Laravel 13.21" },
                    {
                        id: "the-rulearray-builder-begins-in-laravel-117",
                        title: "The Rule::array() builder begins in Laravel 11.7",
                    },
                    { id: "array_keys-begins-in-laravel-1324", title: "array_keys begins in Laravel 13.24" },
                    {
                        id: "three-array-predicates-have-mid-major-introductions",
                        title: "Three array predicates have mid-major introductions",
                    },
                    {
                        id: "http-normalization-also-has-a-known-major-boundary",
                        title: "HTTP normalization also has a known major boundary",
                    },
                    {
                        id: "laravel-1123-changes-list-output-projection",
                        title: "Laravel 11.23 changes list output projection",
                    },
                    {
                        id: "parameterized-array-rules-preserve-the-parent-value",
                        title: "Parameterized array rules preserve the parent value",
                    },
                    {
                        id: "no-additional-portable-boundary-was-observed",
                        title: "No additional portable boundary was observed",
                    },
                ],
            },
            { id: "reverse-direction-precision-audit", title: "Reverse-direction precision audit" },
            { id: "ci-enforcement", title: "CI enforcement" },
            { id: "reproducing-the-audit", title: "Reproducing the audit" },
            { id: "possible-future-cross-version-seed-sweeps", title: "Possible future cross-version seed sweeps" },
            { id: "possible-future-fuzzing", title: "Possible future fuzzing" },
            { id: "version-aware-implementation", title: "Version-aware implementation" },
        ],
        "contributing/development.html": [
            { id: "documentation", title: "Documentation" },
            { id: "mutation-testing", title: "Mutation testing" },
            { id: "nix-dependency-hashes", title: "Nix dependency hashes" },
            { id: "ci", title: "CI" },
            { id: "downstream-investigations", title: "Downstream investigations" },
        ],
    };

    function createHeadingList(pageUrl, headings) {
        const list = document.createElement("ol");
        list.classList.add("section");

        for (const heading of headings) {
            const item = document.createElement("li");
            item.classList.add("header-item");

            const wrapper = document.createElement("span");
            wrapper.classList.add("chapter-link-wrapper");

            const link = document.createElement("a");
            link.href = `${pageUrl}#${heading.id}`;
            link.textContent = heading.title;

            wrapper.append(link);
            item.append(wrapper);

            if (heading.children) {
                item.classList.add("expanded");
                item.append(createHeadingList(pageUrl, heading.children));
            }

            list.append(item);
        }

        return list;
    }

    function expandSidebarSections() {
        document.querySelectorAll("#mdbook-sidebar .chapter-item, #mdbook-sidebar .header-item").forEach(function (item) {
            item.classList.add("expanded");
        });
    }

    function injectInactivePageOutlines() {
        const chapterLinks = document.querySelectorAll("#mdbook-sidebar .chapter-item > .chapter-link-wrapper > a");

        for (const [chapterPath, headings] of Object.entries(headingsByChapter)) {
            const pageUrl = new URL(path_to_root + chapterPath, document.location.href);
            const chapterLink = Array.from(chapterLinks).find(function (link) {
                return new URL(link.href, document.location.href).href === pageUrl.href;
            });

            if (!chapterLink || chapterLink.classList.contains("active")) {
                continue;
            }

            const container = document.createElement("div");
            container.classList.add("page-outline");
            container.append(createHeadingList(pageUrl.href, headings));
            chapterLink.parentElement.after(container);
        }
    }

    function mountWideNavigation() {
        const navigation = document.querySelector(".nav-wide-wrapper");
        const title = document.querySelector("#mdbook-menu-bar .menu-title");

        if (!navigation || !title || navigation.querySelector(".wide-navigation-title")) {
            return;
        }

        const previous = navigation.querySelector(".nav-chapters.previous");
        const next = navigation.querySelector(".nav-chapters.next");

        if (!previous) {
            const placeholder = document.createElement("span");
            placeholder.classList.add("wide-navigation-placeholder");
            navigation.prepend(placeholder);
        }

        const navigationTitle = title.cloneNode(true);
        navigationTitle.classList.remove("menu-title");
        navigationTitle.classList.add("wide-navigation-title");
        navigation.insertBefore(navigationTitle, next);

        if (!next) {
            const placeholder = document.createElement("span");
            placeholder.classList.add("wide-navigation-placeholder");
            navigation.append(placeholder);
        }

        document.documentElement.classList.add("wide-navigation-mounted");
    }

    function addHeliogenesisStylesheet(assetRoot, filename) {
        const stylesheet = document.createElement("link");
        stylesheet.rel = "stylesheet";
        stylesheet.href = new URL(filename, assetRoot).href;

        const loaded = new Promise(function (resolve, reject) {
            stylesheet.addEventListener("load", resolve, { once: true });
            stylesheet.addEventListener("error", reject, { once: true });
        });

        document.head.append(stylesheet);

        return { element: stylesheet, loaded };
    }

    function markHeliogenesisDocument() {
        const addedAttributes = [];
        const mark = function (element, attribute) {
            if (!element || element.hasAttribute(attribute)) {
                return;
            }

            element.setAttribute(attribute, "");
            addedAttributes.push([element, attribute]);
        };

        const world = document.querySelector("#mdbook-page-wrapper") ?? document.body;
        mark(world, "data-heliogenesis-world");

        for (const selector of ["#mdbook-menu-bar", "#mdbook-sidebar"]) {
            mark(document.querySelector(selector), "data-heliogenesis-chrome");
        }

        const surface = document.querySelector("#mdbook-content > main");
        mark(surface, "data-heliogenesis-surface");

        if (surface) {
            surface.querySelectorAll("blockquote, .warning, aside").forEach(function (element) {
                mark(element, "data-heliogenesis-callout");
            });
            surface.querySelectorAll("pre").forEach(function (element) {
                mark(element, "data-heliogenesis-code");
            });
            surface.querySelectorAll("h1").forEach(function (element) {
                mark(element, "data-heliogenesis-rule");
            });
        }

        return function () {
            for (const [element, attribute] of addedAttributes) {
                element.removeAttribute(attribute);
            }
        };
    }

    async function mountHeliogenesis() {
        const controls = document.querySelector("#mdbook-menu-bar .right-buttons");
        if (!controls) {
            return;
        }

        const stylesheets = [];
        let trigger = null;
        let heliogenesis = null;
        let unmarkDocument = function () {};

        try {
            const assetRoot = new URL(path_to_root + "assets/heliogenesis/", document.location.href);
            stylesheets.push(addHeliogenesisStylesheet(assetRoot, "heliogenesis.css"));
            stylesheets.push(addHeliogenesisStylesheet(assetRoot, "heliogenesis-document.css"));
            unmarkDocument = markHeliogenesisDocument();

            trigger = document.createElement("button");
            trigger.id = "second-sun";
            trigger.type = "button";
            trigger.title = "Dawn the Second Sun";
            trigger.setAttribute("aria-label", "Dawn the Second Sun");
            trigger.hidden = true;
            controls.prepend(trigger);

            const moduleUrl = new URL("heliogenesis.js", assetRoot);
            const [, , module] = await Promise.all([
                stylesheets[0].loaded,
                stylesheets[1].loaded,
                import(moduleUrl.href),
            ]);
            heliogenesis = new module.Heliogenesis({ trigger });
            heliogenesis.mount();
            trigger.hidden = false;
        } catch (error) {
            try {
                heliogenesis?.destroy();
            } catch (disposalError) {
                console.error("Unable to dispose of Heliogenesis after a mount failure.", disposalError);
            }

            trigger?.remove();
            for (const stylesheet of stylesheets) {
                stylesheet.element.remove();
            }
            unmarkDocument();
            console.error("Unable to mount Heliogenesis.", error);
        }
    }

    async function mountDocumentLooksBack() {
        const stylesheets = [];
        let controller = null;

        try {
            const assetRoot = new URL(path_to_root + "assets/document-looks-back/", document.location.href);
            stylesheets.push(addHeliogenesisStylesheet(assetRoot, "document-looks-back.css"));

            const moduleUrl = new URL("document-looks-back.js", assetRoot);
            const [, module] = await Promise.all([
                stylesheets[0].loaded,
                import(moduleUrl.href),
            ]);
            const root = document.querySelector("#mdbook-content > main");
            controller = new module.DocumentLooksBack({
                root: root ?? document.body,
                selector: "p, li",
            });
            controller.mount();
            window.documentLooksBack = controller;
        } catch (error) {
            try {
                controller?.destroy();
            } catch (disposalError) {
                console.error("Unable to dispose of Document Looks Back after a mount failure.", disposalError);
            }

            for (const stylesheet of stylesheets) {
                stylesheet.element.remove();
            }
            if (window.documentLooksBack === controller) {
                delete window.documentLooksBack;
            }
            console.error("Unable to mount Document Looks Back.", error);
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        injectInactivePageOutlines();
        expandSidebarSections();
        window.requestAnimationFrame(expandSidebarSections);
        mountWideNavigation();
        void mountHeliogenesis();
        void mountDocumentLooksBack();
    });
})();
