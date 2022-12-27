{ mkDerivation, fetchurl, makeWrapper, lib, php, nix-update-script }:
let
  pname = "psalm";
  version = "5.4.0";
in
mkDerivation {
  inherit pname version;

  src = fetchurl {
    url = "https://github.com/vimeo/psalm/releases/download/${version}/psalm.phar";
    sha256 = "sha256-d5jf68s+LppUDwERQaqr+ry8L+Zmob8VwetYkQ+vIUg=";
  };

  dontUnpack = true;

  nativeBuildInputs = [ makeWrapper ];

  installPhase = ''
    runHook preInstall
    mkdir -p $out/bin
    install -D $src $out/libexec/psalm/psalm.phar
    makeWrapper ${php}/bin/php $out/bin/psalm \
      --add-flags "$out/libexec/psalm/psalm.phar"
    runHook postInstall
  '';

  passthru = {
    updateScript = nix-update-script {
      attrPath = "php.packages.psalm";
    };
  };

  meta = with lib; {
    description = "A static analysis tool for finding errors in PHP applications";
    license = licenses.mit;
    homepage = "https://github.com/vimeo/psalm";
    maintainers = teams.php.members;
  };
}
