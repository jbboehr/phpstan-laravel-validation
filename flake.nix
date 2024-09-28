{
  description = "jbboehr/laravel-validator-phpstan-plugin";
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-23.11";
    nixpkgs-unstable.url = "github:nixos/nixpkgs/nixos-unstable";
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

  outputs = {
    self,
    nixpkgs,
    nixpkgs-unstable,
    flake-utils,
    pre-commit-hooks,
    gitignore,
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
      pkgs-unstable = nixpkgs-unstable.legacyPackages.${system};
      src = gitignore.lib.gitignoreSource ./.;

      pre-commit-check = pre-commit-hooks.lib.${system}.run {
        inherit src;
        hooks = {
          actionlint.enable = true;
          alejandra.enable = true;
          alejandra.excludes = ["\/vendor\/"];
          # https://github.com/cachix/pre-commit-hooks.nix/pull/344
          #phpcs.enable = true;
          shellcheck.enable = true;
        };
      };

      makeShell = {
        php,
        withPcov ? true,
      }: let
        php' = buildEnv {inherit php withPcov;};
        phpWithUopz = php'; # disable this for now
        #        phpWithUopz = php.withExtensions ({
        #          enabled,
        #          all,
        #        }: enabled ++ [(pkgs.callPackage ./nix/uopz.nix {inherit (php') buildPecl;})]);
      in
        pkgs.mkShell {
          buildInputs = with pkgs; [
            actionlint
            mdl
            nixpkgs-fmt
            php'
            php'.packages.composer
            pre-commit
          ];
          shellHook = ''
            ${pre-commit-check.shellHook}
            export PATH="$PWD/vendor/bin:$PATH"
            export PHP_WITH_UOPZ="${phpWithUopz}/bin/php"
            export PHPUNIT_WITH_PCOV="$PHP_WITH_PCOV -d memory_limit=512M -d pcov.directory=$PWD -dpcov.exclude="~vendor~" ./vendor/bin/phpunit"
          '';
        };
    in rec {
      checks = {
        inherit pre-commit-check;
      };

      devShells = rec {
        php81 = makeShell {php = pkgs.php81;};
        php82 = makeShell {php = pkgs.php82;};
        php83 = makeShell {php = pkgs.php83;};
        php84 = makeShell {
          php = pkgs-unstable.php84;
          withPcov = false;
        };
        default = php81;
      };

      formatter = pkgs.alejandra;
    });
}
