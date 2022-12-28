{
  description = "jbboehr/laravel-validator-phpstan-plugin";
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs";
    flake-utils = {
      url = "github:numtide/flake-utils";
    };
    pre-commit-hooks = {
      # url = "github:cachix/pre-commit-hooks.nix";
      url = "github:jbboehr/pre-commit-hooks.nix/phpstan-psalm";
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
        pkgs = nixpkgs.legacyPackages.${system};
        php = pkgs.php80;
        phpstan = pkgs.callPackage ./nix/phpstan.nix { inherit (pkgs.stdenv) mkDerivation; };
        psalm = pkgs.callPackage ./nix/psalm.nix { inherit (pkgs.stdenv) mkDerivation; };
        phpWithXdebug = php.withExtensions ({ enabled, all }: enabled ++ [ all.xdebug ]);
        phpWithUopz = php.withExtensions ({ enabled, all }: enabled ++ [ (pkgs.callPackage ./nix/uopz.nix { inherit (php) buildPecl; }) ]);
        src = gitignore.lib.gitignoreSource ./.;

        makePackage = php: (pkgs.callPackage ./nix/composer-project.nix {
          inherit php;
          phpPackages = php.packages;
        }) src;

        defaultPackage = makePackage pkgs.php80;

        pre-commit-check = pre-commit-hooks.lib.${system}.run {
          src = "${defaultPackage}/libexec/source";
          hooks = {
            actionlint.enable = true;
            nixpkgs-fmt.enable = true;
            nixpkgs-fmt.excludes = [ "\/vendor\/" ];
            phpcs.enable = true;
            psalm.enable = true;
            phpstan.enable = true;
            shellcheck.enable = true;
          };
          settings = {
            phpstan.binPath = "${php}/bin/php -d phar.require_hash=0 ${phpstan}/bin/phpstan";
            psalm.binPath = "${php}/bin/php -d phar.require_hash=0 ${psalm}/bin/psalm --no-cache";
            # these are not currently working
            #psalm.binPath = "${php}/bin/php -d phar.require_hash=0 ${defaultPackage}/libexec/source/vendor/bin/psalm";
            #phpstan.binPath = "${php}/bin/php -d phar.require_hash=0 ${defaultPackage}/libexec/source/vendor/bin/phpstan";
          };
        };

        makeCheck = php: (makePackage php).overrideAttrs (oldAttrs: {
          postInstall = ''
            (cd $out/libexec/$sourceRoot && ${php}/bin/php ./vendor/bin/phpunit)
          '';
        });
      in
      rec {
        packages.default = defaultPackage;

        checks = {
          inherit pre-commit-check;
          php80 = makeCheck pkgs.php80;
          php81 = makeCheck pkgs.php81;
          php82 = makeCheck pkgs.php82;
        };

        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            actionlint
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
            export PHP_WITH_XDEBUG="${phpWithXdebug}/bin/php"
            export PHP_WITH_UOPZ="${phpWithUopz}/bin/php"
            export PHPUNIT_WITH_XDEBUG="$PHP_WITH_XDEBUG -d xdebug.mode=coverage ./vendor/bin/phpunit"
            export XDEBUG_MODE=coverage
          '';
        };

        formatter = pkgs.nixpkgs-fmt;
      });
}
