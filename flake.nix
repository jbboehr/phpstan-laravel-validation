{
  description = "jbboehr/laravel-validator-phpstan-plugin";
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-26.05";
    systems.url = "github:nix-systems/default";
    flake-utils = {
      url = "github:numtide/flake-utils";
      inputs.systems.follows = "systems";
    };
    phps = {
      url = "github:fossar/nix-phps";
      inputs.utils.follows = "flake-utils";
    };
    git-hooks = {
      url = "github:cachix/git-hooks.nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    gitignore = {
      url = "github:hercules-ci/gitignore.nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    agent-badge = {
      url = "github:jbboehr/agent-badge.ts";
      inputs.flake-utils.follows = "flake-utils";
      inputs.gitignore.follows = "gitignore";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    nix-github-actions = {
      url = "github:nix-community/nix-github-actions";
      inputs.nixpkgs.follows = "nixpkgs";
    };
  };

  outputs = {
    self,
    nixpkgs,
    systems,
    flake-utils,
    phps,
    git-hooks,
    gitignore,
    agent-badge,
    nix-github-actions,
  }:
    flake-utils.lib.eachDefaultSystem (system: let
      buildEnv = {
        php,
        withPcov ? true,
      }:
        php.buildEnv {
          extraConfig = "memory_limit = 2G";
          extensions = {
            enabled,
            all,
          }:
            enabled ++ (pkgs.lib.optionals withPcov [all.pcov]);
        };
      pkgs = nixpkgs.legacyPackages.${system};
      src = gitignore.lib.gitignoreSource ./.;
      vendorHashes = import ./nix/vendor-hashes.nix;

      phpVersions = {
        php81 = buildEnv {
          php = phps.packages.${system}.php81;
          withPcov = false;
        };
        php82 = buildEnv {
          php = pkgs.php82;
          withPcov = false;
        };
        php83 = buildEnv {
          php = pkgs.php83;
          withPcov = false;
        };
        php84 = buildEnv {
          php = pkgs.php84;
          withPcov = false;
        };
        php85 = buildEnv {
          php = pkgs.php85;
          withPcov = false;
        };
      };
      mutationPhp = buildEnv {
        php = pkgs.php85;
        withPcov = true;
      };

      rootComposerSource = pkgs.lib.fileset.toSource {
        root = ./.;
        fileset = ./composer.json;
      };

      mkComposerClosure = {
        name,
        php ? phpVersions.php85,
        composerSource,
        composerJson,
        composerJsonContents ? builtins.readFile composerJson,
        composerLock,
        vendorHash,
        composerNoPlugins ? true,
        composerNoScripts ? true,
      }: let
        fingerprint = builtins.hashString "sha256" (
          composerJsonContents
          + builtins.hashFile "sha256" composerLock
        );
        closureId = builtins.substring 0 12 fingerprint;
        version = "dev-develop";
        vendorPname = "phpstan-laravel-validation-${name}-${closureId}-vendor";
        repository = php.mkComposerRepository {
          pname = "phpstan-laravel-validation-${name}-${closureId}-dependencies";
          inherit version composerLock vendorHash composerNoPlugins composerNoScripts;
          src = composerSource;
          composerNoDev = false;
        };
        vendor = pkgs.stdenvNoCC.mkDerivation {
          pname = vendorPname;
          inherit version composerLock composerNoPlugins composerNoScripts;
          src = composerSource;
          nativeBuildInputs = [
            php
            php.packages.composer-local-repo-plugin
            php.composerHooks.composerInstallHook
          ];
          composerRepository = repository;
          composerNoDev = false;
          COMPOSER_ROOT_VERSION = "dev-develop";
          COMPOSER_DISABLE_NETWORK = "1";
        };
      in {
        inherit composerJson composerLock repository vendor vendorPname;
      };

      rootClosure = mkComposerClosure {
        name = "root";
        composerSource = rootComposerSource;
        composerJson = ./composer.json;
        composerLock = ./composer.lock;
        vendorHash = vendorHashes.root;
      };

      laravel11Closure = mkComposerClosure {
        name = "laravel11";
        composerSource = rootComposerSource;
        composerJson = ./composer.json;
        composerLock = ./nix/composer-locks/laravel11.lock;
        vendorHash = vendorHashes.laravel11;
      };
      laravel12Closure = mkComposerClosure {
        name = "laravel12";
        composerSource = rootComposerSource;
        composerJson = ./composer.json;
        composerLock = ./nix/composer-locks/laravel12.lock;
        vendorHash = vendorHashes.laravel12;
      };
      laravel13Closure = mkComposerClosure {
        name = "laravel13";
        composerSource = rootComposerSource;
        composerJson = ./composer.json;
        composerLock = ./nix/composer-locks/laravel13.lock;
        vendorHash = vendorHashes.laravel13;
      };
      minimumPhpstanClosure = mkComposerClosure {
        name = "phpstan-minimum";
        composerSource = rootComposerSource;
        composerJson = ./composer.json;
        composerLock = ./nix/composer-locks/phpstan-minimum.lock;
        vendorHash = vendorHashes.phpstan-minimum;
      };
      larastanClosure = mkComposerClosure {
        name = "larastan";
        composerSource = ./nix/consumers/larastan;
        composerJson = ./nix/consumers/larastan/composer.json;
        composerLock = ./nix/consumers/larastan/composer.lock;
        vendorHash = vendorHashes.larastan;
      };
      infectionClosure = mkComposerClosure {
        name = "infection";
        php = mutationPhp;
        composerSource = ./tools/infection;
        composerJson = ./tools/infection/composer.json;
        composerLock = ./tools/infection/composer.lock;
        vendorHash = vendorHashes.infection;
        composerNoPlugins = false;
        composerNoScripts = false;
      };

      prepareProject = closure: ''
        mkdir project
        cp -R ${src}/. project/
        chmod -R u+w project
        cd project

        cp ${closure.composerJson} composer.json
        cp ${closure.composerLock} composer.lock
        chmod u+w composer.json composer.lock

        sharedVendor=${closure.vendor}/share/php/${closure.vendorPname}/vendor
        cp -R "$sharedVendor" vendor
        chmod -R u+w vendor
        sed -i "s|${closure.vendor}/share/php/${closure.vendorPname}|$PWD|g" \
          vendor/composer/autoload_*.php \
          vendor/composer/installed.php \
          vendor/composer/installed.json

        export HOME="$TMPDIR/home"
        export XDG_CACHE_HOME="$TMPDIR/cache"
        mkdir -p \
          "$HOME" \
          "$XDG_CACHE_HOME" \
          "$TMPDIR/bin" \
          "$TMPDIR/usr/bin"
        ln -s ${pkgs.bash}/bin/bash "$TMPDIR/bin/bash"
        ln -s ${pkgs.coreutils}/bin/env "$TMPDIR/usr/bin/env"
        export PATH="$TMPDIR/bin:$PATH"
      '';

      mkProjectCheck = {
        name,
        php ? phpVersions.php85,
        closure ? rootClosure,
        command,
        junitReport ? false,
        nativeBuildInputs ? [],
      }: let
        runCommand =
          if pkgs.stdenv.isLinux
          then ''${pkgs.proot}/bin/proot -b "$TMPDIR/usr/bin/env:/usr/bin/env" ${pkgs.bash}/bin/bash -euo pipefail -c ${pkgs.lib.escapeShellArg command}''
          else ''
            ${pkgs.bash}/bin/bash -euo pipefail -c ${pkgs.lib.escapeShellArg command}
          '';
      in
        pkgs.runCommand "phpstan-laravel-validation-${name}" {
          nativeBuildInputs = [php php.packages.composer pkgs.bash] ++ nativeBuildInputs;
          passthru = {inherit junitReport;};
        } ''
          ${prepareProject closure}
          patchShebangs vendor/bin scripts
          ${pkgs.lib.optionalString junitReport ''
            export PHPUNIT_JUNIT_REPORT="$TMPDIR/phpstan-laravel-validation-junit/phpunit-junit.xml"
            mkdir -p "$(dirname "$PHPUNIT_JUNIT_REPORT")"
          ''}
          ${runCommand}
          mkdir -p "$out"
          ${pkgs.lib.optionalString junitReport ''
            mkdir -p "$out/reports"
            cp "$PHPUNIT_JUNIT_REPORT" "$out/reports/phpunit-junit.xml"
          ''}
          touch "$out/passed"
        '';

      mkSourceCheck = {
        name,
        php ? phpVersions.php85,
        command,
        nativeBuildInputs ? [],
      }:
        pkgs.runCommand "phpstan-laravel-validation-${name}" {
          nativeBuildInputs = [php] ++ nativeBuildInputs;
        } ''
          mkdir project
          cp -R ${src}/. project/
          chmod -R u+w project
          cd project
          export HOME="$TMPDIR/home"
          mkdir -p "$HOME"
          ${command}
          mkdir -p "$out"
          touch "$out/passed"
        '';

      auditProfileNames = [
        "10.0.0"
        "10.32.1"
        "10.33.0"
        "10.34.0"
        "10-latest"
        "11.0.0"
        "11.22.0"
        "11.23.0"
        "11-latest"
        "12.0.0"
        "12.21.0"
        "12.22.0"
        "12.39.0"
        "12.40.0"
        "12-latest"
        "13.0.0"
        "13.3.0"
        "13.4.0"
        "13.20.0"
        "13.21.0"
        "13.23.0"
        "13.24.0"
        "13-latest"
      ];

      auditMajor = profile: builtins.substring 0 2 profile;
      auditConstraint = profile: let
        major = auditMajor profile;
      in
        if pkgs.lib.hasSuffix "-latest" profile
        then "^${major}.0"
        else profile;
      auditPhp = profile: let
        major = auditMajor profile;
      in
        if major == "10"
        then phpVersions.php81
        else if major == "13"
        then phpVersions.php83
        else phpVersions.php82;
      auditManifest = profile:
        builtins.toJSON {
          name = "phpstan-laravel-validation/version-audit";
          description = "Nix Laravel runtime for the inference audit";
          type = "project";
          license = "AGPL-3.0-or-later";
          require = {"laravel/framework" = auditConstraint profile;};
          minimum-stability = "stable";
          prefer-stable = true;
          config = {
            allow-plugins = false;
            sort-packages = true;
          };
        };
      auditClosure = profile: let
        manifestContents = auditManifest profile;
        manifest = pkgs.writeTextDir "composer.json" manifestContents;
      in
        mkComposerClosure {
          name = "audit-${profile}";
          php = auditPhp profile;
          composerSource = manifest;
          composerJson = "${manifest}/composer.json";
          composerJsonContents = manifestContents;
          composerLock = ./nix/composer-locks/audit/${profile}.lock;
          vendorHash = vendorHashes.audit.${profile};
        };
      auditCheck = profile: let
        php = auditPhp profile;
        closure = auditClosure profile;
      in
        mkProjectCheck {
          name = "laravel-audit-${profile}";
          inherit php;
          command = ''
            ${php}/bin/php scripts/conditional-presence-rule-audit.php \
              --laravel-autoload=${closure.vendor}/share/php/${closure.vendorPname}/vendor/autoload.php
            ${php}/bin/php scripts/inference-audit.php \
              --laravel-autoload=${closure.vendor}/share/php/${closure.vendorPname}/vendor/autoload.php \
              --baseline=${profile}
          '';
        };
      auditChecks = builtins.listToAttrs (map (profile: {
          name = "laravel-audit-${profile}";
          value = auditCheck profile;
        })
        auditProfileNames);

      dateParserProfileNames = [
        "11.40.0"
        "11.41.0"
        "11.43.1"
        "11.43.2"
      ];
      dateParserManifest = profile:
        builtins.toJSON {
          name = "phpstan-laravel-validation/date-parser-audit";
          description = "Nix Laravel runtime for the Date rule parser audit";
          type = "project";
          license = "AGPL-3.0-or-later";
          require = {"laravel/framework" = profile;};
          minimum-stability = "stable";
          prefer-stable = true;
          config = {
            allow-plugins = false;
            sort-packages = true;
          };
        };
      dateParserClosure = profile: let
        manifestContents = dateParserManifest profile;
        manifest = pkgs.writeTextDir "composer.json" manifestContents;
      in
        mkComposerClosure {
          name = "date-parser-${profile}";
          php = phpVersions.php82;
          composerSource = manifest;
          composerJson = "${manifest}/composer.json";
          composerJsonContents = manifestContents;
          composerLock = ./nix/composer-locks/date-parser/${profile}.lock;
          vendorHash = vendorHashes.dateParser.${profile};
        };
      dateParserCheck = profile: let
        php = phpVersions.php82;
        closure = dateParserClosure profile;
      in
        mkSourceCheck {
          name = "date-parser-${profile}";
          inherit php;
          command = ''
            ${php}/bin/php scripts/date-rule-parser-audit.php \
              --laravel-autoload=${closure.vendor}/share/php/${closure.vendorPname}/vendor/autoload.php
          '';
        };
      dateParserChecks = builtins.listToAttrs (map (profile: {
          name = "date-parser-${profile}";
          value = dateParserCheck profile;
        })
        dateParserProfileNames);

      ciComposerClosures =
        [
          rootClosure
          laravel11Closure
          laravel12Closure
          laravel13Closure
          minimumPhpstanClosure
          larastanClosure
          infectionClosure
        ]
        ++ map auditClosure auditProfileNames
        ++ map dateParserClosure dateParserProfileNames;

      mutationShardDefinitions = builtins.fromJSON (builtins.readFile ./.github/infection-shards.json);
      mutationShardNames = builtins.attrNames mutationShardDefinitions;
      mutationShard = shard: let
        definition = mutationShardDefinitions.${shard};
        paths = pkgs.lib.concatMapStringsSep " " pkgs.lib.escapeShellArg definition.paths;
      in
        mkProjectCheck {
          name = "mutation-${shard}";
          php = mutationPhp;
          command = ''
            sharedInfectionVendor=${infectionClosure.vendor}/share/php/${infectionClosure.vendorPname}/vendor
            mkdir -p tools/infection
            cp -rs "$sharedInfectionVendor" tools/infection/vendor

            export COMPOSER_PROCESS_TIMEOUT=1800
            set +e
            ${mutationPhp}/bin/php tools/infection/vendor/bin/infection \
              --configuration=infection.json5.dist \
              --with-uncovered \
              --only-covering-test-cases \
              --log-verbosity=all \
              --logger-github=false \
              --logger-summary-json=infection-summary.json \
              --min-msi=0 \
              --min-covered-msi=0 \
              --threads=${toString definition.threads} \
              ${paths}
            infectionStatus=$?
            set -e

            if [ "$infectionStatus" -ne 0 ]; then
              for report in infection.log infection-summary.log; do
                if [ -f "$report" ]; then
                  echo "Infection report: $report"
                  sed -n "1,240p" "$report"
                fi
              done
              exit "$infectionStatus"
            fi

            mkdir -p "$out/reports"
            cp \
              infection.log \
              infection-summary.json \
              infection-summary.log \
              "$out/reports/"
          '';
        };
      mutationShards = builtins.listToAttrs (map (shard: {
          name = shard;
          value = mutationShard shard;
        })
        mutationShardNames);
      mutationShardPackages = builtins.listToAttrs (map (shard: {
          name = "mutation-${shard}";
          value = mutationShards.${shard};
        })
        mutationShardNames);
      mutationReportPaths =
        map (
          shard: "${shard}=${mutationShards.${shard}}/reports/infection-summary.json"
        )
        mutationShardNames;
      mutationReportAggregator = pkgs.writeShellApplication {
        name = "aggregate-infection-reports";
        text = ''
          exec ${phpVersions.php85}/bin/php ${./scripts/aggregate-infection-reports.php} "$@"
        '';
      };

      pre-commit-check = git-hooks.lib.${system}.run {
        inherit src;
        hooks = {
          actionlint.enable = true;
          alejandra.enable = true;
          alejandra.excludes = ["\/vendor\/"];
          shellcheck.enable = true;
        };
      };

      makeShell = {
        php,
        withPcov ? true,
      }: let
        php' = buildEnv {inherit php withPcov;};
        phpWithUopz = php.withExtensions ({
          enabled,
          all,
        }:
          enabled ++ [(pkgs.callPackage ./nix/uopz.nix {inherit (php') buildPecl;})]);
      in
        pkgs.mkShell {
          buildInputs = with pkgs; [
            actionlint
            agent-badge.packages.${system}.default
            alejandra
            gnumake
            mdbook
            mdl
            php'
            php'.packages.composer
            pre-commit
          ];
          shellHook = ''
            ${pre-commit-check.shellHook}
            export PATH="$PWD/vendor/bin:$PATH"
            export PHP_WITH_UOPZ="${phpWithUopz}/bin/php"
            export PHP_WITH_PCOV="${php'}/bin/php"
            export PHPUNIT_WITH_PCOV="$PHP_WITH_PCOV -d memory_limit=512M -d pcov.directory=$PWD -dpcov.exclude="~vendor~" ./vendor/bin/phpunit"
          '';
        };
    in rec {
      checks =
        {
          inherit pre-commit-check;

          phpunit-php81 = mkProjectCheck {
            name = "phpunit-php81";
            php = phpVersions.php81;
            junitReport = true;
            command = "php vendor/bin/phpunit --exclude-group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-php82 = mkProjectCheck {
            name = "phpunit-php82";
            php = phpVersions.php82;
            junitReport = true;
            command = "php vendor/bin/phpunit --exclude-group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-php83 = mkProjectCheck {
            name = "phpunit-php83";
            php = phpVersions.php83;
            junitReport = true;
            command = "php vendor/bin/phpunit --exclude-group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-php84 = mkProjectCheck {
            name = "phpunit-php84";
            php = phpVersions.php84;
            junitReport = true;
            command = "php vendor/bin/phpunit --exclude-group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-php85 = mkProjectCheck {
            name = "phpunit-php85";
            php = phpVersions.php85;
            junitReport = true;
            command = "php vendor/bin/phpunit --exclude-group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };

          form-request-result-cache = mkProjectCheck {
            name = "form-request-result-cache";
            php = phpVersions.php85;
            junitReport = true;
            command = "php vendor/bin/phpunit --group form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };

          phpunit-laravel11 = mkProjectCheck {
            name = "phpunit-laravel11";
            php = phpVersions.php82;
            closure = laravel11Closure;
            junitReport = true;
            command = "LARAVEL_AUDIT_BASELINE=11-latest php vendor/bin/phpunit --exclude-group documentation,form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-laravel12 = mkProjectCheck {
            name = "phpunit-laravel12";
            php = phpVersions.php82;
            closure = laravel12Closure;
            junitReport = true;
            command = "LARAVEL_AUDIT_BASELINE=12-latest php vendor/bin/phpunit --exclude-group documentation,form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
          phpunit-laravel13 = mkProjectCheck {
            name = "phpunit-laravel13";
            php = phpVersions.php83;
            closure = laravel13Closure;
            junitReport = true;
            command = "LARAVEL_AUDIT_BASELINE=13-latest php vendor/bin/phpunit --exclude-group documentation,form-request-result-cache --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };

          phpstan = mkProjectCheck {
            name = "phpstan";
            command = "php vendor/bin/phpstan analyse --no-progress";
          };
          php-cs-fixer = mkProjectCheck {
            name = "php-cs-fixer";
            command = "php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff";
          };
          docs-format = mkProjectCheck {
            name = "docs-format";
            command = "composer docs:format";
          };
          documentation = mkSourceCheck {
            name = "documentation";
            nativeBuildInputs = [pkgs.gnumake pkgs.mdbook];
            command = "make docs-check";
          };
          composer-validate = mkSourceCheck {
            name = "composer-validate";
            nativeBuildInputs = [phpVersions.php85.packages.composer];
            command = "composer validate --strict";
          };
          php-lint = mkSourceCheck {
            name = "php-lint";
            php = phpVersions.php81;
            command = ''
              while IFS= read -r -d "" file; do
                php -l "$file" >/dev/null
              done < <(find runtime src scripts tests -type f -name "*.php" -print0)
            '';
          };
          infection-shards = mkSourceCheck {
            name = "infection-shards";
            php = phpVersions.php81;
            command = "php scripts/infection-shards.php >/dev/null";
          };

          consumer-phpstan-minimum = mkProjectCheck {
            name = "consumer-phpstan-minimum";
            php = phpVersions.php81;
            closure = minimumPhpstanClosure;
            junitReport = true;
            command = ''
              php vendor/bin/phpstan analyse --no-progress
              php vendor/bin/phpunit --exclude-group documentation --no-coverage \
                --log-junit "$PHPUNIT_JUNIT_REPORT"
            '';
          };
          consumer-larastan = mkProjectCheck {
            name = "consumer-larastan";
            php = phpVersions.php85;
            closure = larastanClosure;
            junitReport = true;
            command = "php vendor/bin/phpunit --group larastan --fail-on-skipped --no-coverage --log-junit \"$PHPUNIT_JUNIT_REPORT\"";
          };
        }
        // auditChecks
        // dateParserChecks;

      packages =
        mutationShardPackages
        // {
          ci-dependencies =
            pkgs.linkFarm
            "phpstan-laravel-validation-ci-dependencies"
            (map (closure: {
                name = closure.vendorPname;
                path = closure.vendor;
              })
              ciComposerClosures);

          mutation-report = mutationReportAggregator;

          mutation = pkgs.runCommand "phpstan-laravel-validation-mutation" {} ''
            reports=(
              ${pkgs.lib.concatStringsSep "\n" mutationReportPaths}
            )

            mkdir -p "$out/shards"
            set -o pipefail
            ${mutationReportAggregator}/bin/aggregate-infection-reports \
              "''${reports[@]}" | tee "$out/infection-summary.json"
            ${pkgs.lib.concatMapStringsSep "\n" (shard: ''
                ln -s ${mutationShards.${shard}}/reports "$out/shards/${shard}"
              '')
              mutationShardNames}

            touch "$out/passed"
          '';
        };

      legacyPackages = pkgs.lib.optionalAttrs (system == "x86_64-linux") {
        githubActions = let
          generated = nix-github-actions.lib.mkGithubMatrix {
            checks = {
              x86_64-linux =
                self.checks.x86_64-linux
                // mutationShardPackages;
            };
            attrPrefix = "legacyPackages.x86_64-linux.githubActions.checks";
          };
        in
          generated
          // {
            matrix =
              generated.matrix
              // {
                include =
                  map (
                    entry:
                      entry
                      // {
                        junit = generated.checks.${entry.system}.${entry.name}.junitReport or false;
                      }
                  )
                  generated.matrix.include;
              };
          };
      };

      devShells = rec {
        php81 = makeShell {php = phps.packages.${system}.php81;};
        php82 = makeShell {php = pkgs.php82;};
        php83 = makeShell {php = pkgs.php83;};
        php84 = makeShell {php = pkgs.php84;};
        php85 = makeShell {php = pkgs.php85;};
        default = php85;
      };

      formatter = pkgs.alejandra;
    });
}
