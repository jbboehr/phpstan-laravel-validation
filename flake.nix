{
  description = "jbboehr/laravel-validator-phpstan-plugin";
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-23.05";
    flake-utils = {
      url = "github:numtide/flake-utils";
    };
    pre-commit-hooks = {
      url = "github:cachix/pre-commit-hooks.nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    gitignore = {
      url = "github:hercules-ci/gitignore.nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
  };

  outputs = { self, nixpkgs, flake-utils, pre-commit-hooks, gitignore }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        buildEnv = php: php.buildEnv { extraConfig = "memory_limit = 2G"; };
        pkgs = nixpkgs.legacyPackages.${system};
        php = buildEnv pkgs.php81;
        phpstan = pkgs.callPackage ./nix/phpstan.nix { inherit (pkgs.stdenv) mkDerivation; };
        psalm = pkgs.callPackage ./nix/psalm.nix { inherit (pkgs.stdenv) mkDerivation; };
        phpWithPcov = php.withExtensions ({ enabled, all }: enabled ++ [ all.pcov ]);
        phpWithUopz = php.withExtensions ({ enabled, all }: enabled ++ [ (pkgs.callPackage ./nix/uopz.nix { inherit (php) buildPecl; }) ]);
        src = gitignore.lib.gitignoreSource ./.;

        makePackage = php: (pkgs.callPackage ./nix/composer-project.nix {
          inherit php;
          phpPackages = php.packages;
        }) src;

        php81Package = makePackage (buildEnv pkgs.php81);
        php82Package = makePackage (buildEnv pkgs.php82);

        pre-commit-check = pre-commit-hooks.lib.${system}.run {
          src = "${php81Package}/libexec/source";
          hooks = {
            actionlint.enable = true;
            nixpkgs-fmt.enable = true;
            nixpkgs-fmt.excludes = [ "\/vendor\/" ];
            # https://github.com/cachix/pre-commit-hooks.nix/pull/344
            #phpcs.enable = true;
            shellcheck.enable = true;
          };
        };

        makeCheck = package: package.overrideAttrs (oldAttrs: {
          postInstall = ''
            (cd $out/libexec/$sourceRoot && ${php}/bin/php ./vendor/bin/phpunit)
          '';
        });
      in
      rec {
        packages = rec {
          php81 = php81Package;
          php82 = php82Package;
          default = php81;
        };

        checks = {
          inherit pre-commit-check;
          php81 = makeCheck packages.php81;
          php82 = makeCheck packages.php82;
        };

        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            actionlint
            mdl
            nixpkgs-fmt
            php
            php.packages.composer
            phpstan
            psalm
            pre-commit
          ];
          shellHook = ''
            ${pre-commit-check.shellHook}
            export PATH="$PWD/vendor/bin:$PATH"
            export PHP_WITH_PCOV="${phpWithPcov}/bin/php"
            export PHP_WITH_UOPZ="${phpWithUopz}/bin/php"
            export PHPUNIT_WITH_PCOV="$PHP_WITH_PCOV -d memory_limit=512M -d pcov.directory=$PWD -dpcov.exclude="~vendor~" ./vendor/bin/phpunit"
          '';
        };

        formatter = pkgs.nixpkgs-fmt;
      });
}
