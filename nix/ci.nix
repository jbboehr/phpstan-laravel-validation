let
  generateTestsForPlatform = { system, pkgs, php }:
    pkgs.recurseIntoAttrs {
      pkg = (import ./composition.nix {
        inherit pkgs php system;
        phpPackages = php.packages;
      }).override {
        src = ./..;
        doCheck = true;
        postInstall = ''
          if [ -n "$doCheck" ]; then
            ./vendor/bin/phpunit || exit 1
          fi
        '';
      };
    };
in
builtins.mapAttrs
  (system: _v:
  let
    path = builtins.fetchTarball {
      url = https://github.com/NixOS/nixpkgs/archive/release-22.11.tar.gz;
      name = "nixpkgs-22.11";
    };
    pkgs = import (path) { inherit system; };
  in
  pkgs.recurseIntoAttrs {
    php80 = generateTestsForPlatform {
      inherit pkgs system;
      php = pkgs.php80;
    };

    php81 = generateTestsForPlatform {
      inherit pkgs system;
      php = pkgs.php81;
    };

    php82 = generateTestsForPlatform {
      inherit pkgs system;
      php = pkgs.php82;
    };
  })
{
  x86_64-linux = { };
  # Uncomment to test build on macOS too
  # x86_64-darwin = {};
}
