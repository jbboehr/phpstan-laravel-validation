{
  lib,
  php,
  stdenv,
  autoreconfHook,
  fetchurl,
  buildPecl,
}:
buildPecl rec {
  pname = "uopz";
  version = "7.1.1";
  sha256 = "1qzcab8fkxgb02ag2fpr1gicy1dggs9kfxkjjlsgwxhc6jjm1yjh";
  buildInputs = [];
  doCheck = false; # failing on PHP 8.1
  checkTarget = "test";
  checkFlagsArray = ["REPORT_EXIT_STATUS=1" "NO_INTERACTION=1"];
  makeFlags = ["phpincludedir=$(dev)/include"];
  outputs = ["out" "dev"];
}
