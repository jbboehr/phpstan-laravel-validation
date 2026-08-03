{
  lib,
  php,
  stdenv,
  autoreconfHook,
  fetchFromGitHub,
  buildPecl,
}:
buildPecl rec {
  pname = "uopz";
  # No tagged release since 7.1.1 (2021), which fails to build on PHP >=8.4
  # (`ZEND_EXIT` was undeclared until php/php-src's opcode changes were
  # matched here). Pin to a post-7.1.1 master commit that fixes it instead.
  version = "7.1.1-unstable-2025-09-14";
  src = fetchFromGitHub {
    owner = "krakjoe";
    repo = "uopz";
    rev = "14c8fc2d6eff14ec9acd926b9cab85d6961c64ac";
    hash = "sha256-BmnVdM3vDRamT63cJOeYQk6EgXnUZ2NGfjMfd39TTAI=";
  };
  buildInputs = [];
  doCheck = false; # failing on PHP 8.1
  checkTarget = "test";
  checkFlagsArray = ["REPORT_EXIT_STATUS=1" "NO_INTERACTION=1"];
  makeFlags = ["phpincludedir=$(dev)/include"];
  outputs = ["out" "dev"];
}
