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
      inputs.nixpkgs.follows = "nixpkgs";
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
    akashi = {
      url = "github:jbboehr/akashi.php/master";
      inputs.flake-utils.follows = "flake-utils";
      inputs.gitignore.follows = "gitignore";
      inputs.nixpkgs.follows = "nixpkgs";
      inputs.pre-commit-hooks.follows = "git-hooks";
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
    akashi,
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
            akashi.packages.${system}.agent-badge
            alejandra
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
      checks = {
        inherit pre-commit-check;
      };

      devShells = rec {
        php81 = makeShell {php = phps.packages.${system}.php81;};
        php82 = makeShell {php = pkgs.php82;};
        php83 = makeShell {php = pkgs.php83;};
        php84 = makeShell {php = pkgs.php84;};
        php85 = makeShell {php = pkgs.php85;};
        default = php81;
      };

      formatter = pkgs.alejandra;
    });
}
