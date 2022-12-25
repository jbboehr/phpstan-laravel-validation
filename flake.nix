{
  description = "jbboehr/laravel-validator-phpstan-plugin";
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs";
    flake-utils = {
      url = "github:numtide/flake-utils";
    };
    composer2nix = {
      url = "github:svanderburg/composer2nix";
      flake = false;
    };
    pre-commit-hooks = {
      # url = "github:cachix/pre-commit-hooks.nix";
      url = "github:jbboehr/pre-commit-hooks.nix/phpcs";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    gitignore = {
      url = "github:hercules-ci/gitignore.nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
  };

  outputs = { self, nixpkgs, flake-utils, composer2nix, pre-commit-hooks, gitignore }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};
        php = pkgs.php80;
        phpWithXdebug = php.withExtensions ({ enabled, all }: enabled ++ [ all.xdebug ]);

        src = pkgs.lib.cleanSourceWith {
          filter = (path: type: (builtins.all (x: x != baseNameOf path)
            [ ".idea" ".git" ".github" "ci.nix" ".ci" "nix" "default.nix" "flake.nix" "flake.lock" ]));
          src = gitignore.lib.gitignoreSource ./.;
        };

        pre-commit-check = pre-commit-hooks.lib.${system}.run {
          inherit src;
          hooks = {
            nixpkgs-fmt.enable = true;
            phpcs.enable = true;
          };
        };

        makePackage = php: (
          (import ./nix/composition.nix) {
            inherit system pkgs php;
            phpPackages = php.packages;
          });

        makeCheck = php: (makePackage php).override {
          inherit src;
          doCheck = true;
          postInstall = ''
            if [ -n "$doCheck" ]; then
              ./vendor/bin/phpunit || exit 1
            fi
          '';
        };
      in
      rec {
        packages.default = (makePackage pkgs.php).override { inherit src; };

        checks = {
          inherit pre-commit-check;
          php80 = makeCheck pkgs.php80;
          php81 = makeCheck pkgs.php81;
          php82 = makeCheck pkgs.php82;
        };

        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            (pkgs.callPackage (import composer2nix) { })
            nixpkgs-fmt
            php
            php.packages.composer
            pre-commit
          ];
          shellHook = ''
            ${pre-commit-check.shellHook}
            export PATH="$PWD/vendor/bin:$PATH"
            export PHP_WITH_XDEBUG="${phpWithXdebug}/bin/php"
            export PHPUNIT_WITH_XDEBUG="$PHP_WITH_XDEBUG -d xdebug.mode=coverage ./vendor/bin/phpunit"
            export XDEBUG_MODE=coverage
            export COMPOSER2NIX="composer2nix --symlink-dependencies --composer-env=nix/composer-env.nix --composition=nix/composition.nix --output=nix/php-packages.nix"
          '';
        };

        formatter = pkgs.nixpkgs-fmt;
      });
}
