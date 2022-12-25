{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "nikic/php-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nikic-php-parser-f59bbe44bf7d96f24f3e2b4ddc21cd52c1d2adbc";
        src = fetchurl {
          url = "https://api.github.com/repos/nikic/PHP-Parser/zipball/f59bbe44bf7d96f24f3e2b4ddc21cd52c1d2adbc";
          sha256 = "0lsw925jy1y1vvsx5da6abn1ky7g6whaxx9g524jgqgbaljrjhxm";
        };
      };
    };
    "phpstan/phpstan" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-d03bccee595e2146b7c9d174486b84f4dc61b0f2";
        src = fetchurl {
          url = "https://api.github.com/repos/phpstan/phpstan/zipball/d03bccee595e2146b7c9d174486b84f4dc61b0f2";
          sha256 = "0zs8ap5h6ki4jlkgwgr9hv50cxwhnyi2v4axpm2wbhib91nk5h1q";
        };
      };
    };
  };
  devPackages = {
    "doctrine/inflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-inflector-d9d313a36c872fd6ee06d9a6cbcf713eaa40f024";
        src = fetchurl {
          url = "https://api.github.com/repos/doctrine/inflector/zipball/d9d313a36c872fd6ee06d9a6cbcf713eaa40f024";
          sha256 = "1z6y0mxqadarw76whppcl0h0cj7p5n6k7mxihggavq46i2wf7nhj";
        };
      };
    };
    "doctrine/instantiator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-instantiator-10dcfce151b967d20fde1b34ae6640712c3891bc";
        src = fetchurl {
          url = "https://api.github.com/repos/doctrine/instantiator/zipball/10dcfce151b967d20fde1b34ae6640712c3891bc";
          sha256 = "1m6pw3bb8v04wqsysj8ma4db8vpm9jnd7ddh8ihdqyfpz8pawjp7";
        };
      };
    };
    "doctrine/lexer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-lexer-c268e882d4dbdd85e36e4ad69e02dc284f89d229";
        src = fetchurl {
          url = "https://api.github.com/repos/doctrine/lexer/zipball/c268e882d4dbdd85e36e4ad69e02dc284f89d229";
          sha256 = "12g069nljl3alyk15884nd1jc4mxk87isqsmfj7x6j2vxvk9qchs";
        };
      };
    };
    "egulias/email-validator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "egulias-email-validator-f88dcf4b14af14a98ad96b14b2b317969eab6715";
        src = fetchurl {
          url = "https://api.github.com/repos/egulias/EmailValidator/zipball/f88dcf4b14af14a98ad96b14b2b317969eab6715";
          sha256 = "1w0440d8ifasx647wci5ydbqsgxyxpf4z9ksvl999604lj0zr7di";
        };
      };
    };
    "fruitcake/php-cors" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "fruitcake-php-cors-58571acbaa5f9f462c9c77e911700ac66f446d4e";
        src = fetchurl {
          url = "https://api.github.com/repos/fruitcake/php-cors/zipball/58571acbaa5f9f462c9c77e911700ac66f446d4e";
          sha256 = "18xm69q4dk9zqfwgp938y2byhlyy9lr5x5qln4k2mg8cq8xr2sm1";
        };
      };
    };
    "illuminate/collections" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-collections-7a8afa0875d7de162f30865d9fae33c8fb235fa2";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/collections/zipball/7a8afa0875d7de162f30865d9fae33c8fb235fa2";
          sha256 = "10snkn5kkl4734257jj9j418cdcxilhy6kwpdr4mydyar5ilnshg";
        };
      };
    };
    "illuminate/conditionable" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-conditionable-5b40f51ccb07e0e7b1ec5559d8db9e0e2dc51883";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/conditionable/zipball/5b40f51ccb07e0e7b1ec5559d8db9e0e2dc51883";
          sha256 = "1klyf4d92mbdlg7yxcsvawdnsl4978n0dp2v4fld2y6ifq78h02y";
        };
      };
    };
    "illuminate/container" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-container-8ca3036459e26dc7cdedaf0f882b625757cc341e";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/container/zipball/8ca3036459e26dc7cdedaf0f882b625757cc341e";
          sha256 = "0pm5g5y7vidld1h5aimliiz2jq5jy5b1cxdvn5096dq869gryiwd";
        };
      };
    };
    "illuminate/contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-contracts-c7cc6e6198cac6dfdead111f9758de25413188b7";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/contracts/zipball/c7cc6e6198cac6dfdead111f9758de25413188b7";
          sha256 = "1r4zcwn7qvwpfr3j9li0b0w50w2cfsg5n7qhriqnbxzfv7jm9hkx";
        };
      };
    };
    "illuminate/filesystem" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-filesystem-9923cb717f5505b84200fb78feba1c2f2fe9fe83";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/filesystem/zipball/9923cb717f5505b84200fb78feba1c2f2fe9fe83";
          sha256 = "1fy3chy9vscjk7v5i4p30l8v34y7ff26ziayb5x8n7a3z73cs0l3";
        };
      };
    };
    "illuminate/http" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-http-5a0284d6bff14a233c2280f0782be4b5bc6b03ab";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/http/zipball/5a0284d6bff14a233c2280f0782be4b5bc6b03ab";
          sha256 = "0k7nk0hpy4bp5j3im27l4m4xram800dzbbjzr8wb3d85cqx5rcsr";
        };
      };
    };
    "illuminate/macroable" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-macroable-e3bfaf6401742a9c6abca61b9b10e998e5b6449a";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/macroable/zipball/e3bfaf6401742a9c6abca61b9b10e998e5b6449a";
          sha256 = "0qxlwav9kix7r695xvqv4isa2rkclx55y2y6jmvjzyqp337qmmjx";
        };
      };
    };
    "illuminate/session" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-session-1e5d31bf7c6ed5844cedd4c36cf8251ec677309e";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/session/zipball/1e5d31bf7c6ed5844cedd4c36cf8251ec677309e";
          sha256 = "1syxbfynflbblc6rjd4bnnb5pbm3bfcr1d6wyjpwkvkllr60a5n4";
        };
      };
    };
    "illuminate/support" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-support-d7f7c07e35a2c09cbeeddc0168826cf05a2eeb84";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/support/zipball/d7f7c07e35a2c09cbeeddc0168826cf05a2eeb84";
          sha256 = "0sh85ki9046p4a7lkrv7jqwpvjmgjm4i0fx2m39szf6pqfshgm9k";
        };
      };
    };
    "illuminate/translation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-translation-53d0cd548a8ad64eaf12d539bf39ee4189dcee56";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/translation/zipball/53d0cd548a8ad64eaf12d539bf39ee4189dcee56";
          sha256 = "0gxj1p4j0kvpb0g7jw52ngsxwi70a16wzfhv4dm6l7fn4i44ag3n";
        };
      };
    };
    "illuminate/validation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "illuminate-validation-a0f19e0bb4b4896b04b34a875d569c61b3b00ace";
        src = fetchurl {
          url = "https://api.github.com/repos/illuminate/validation/zipball/a0f19e0bb4b4896b04b34a875d569c61b3b00ace";
          sha256 = "0scaqxhv08s6dqyrz031lq2rdv5xz1spsi57y0fv6n8c34hhlzby";
        };
      };
    };
    "myclabs/deep-copy" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "myclabs-deep-copy-14daed4296fae74d9e3201d2c4925d1acb7aa614";
        src = fetchurl {
          url = "https://api.github.com/repos/myclabs/DeepCopy/zipball/14daed4296fae74d9e3201d2c4925d1acb7aa614";
          sha256 = "11593chczjw8k5jix2mj9v31lg5jgpxqrkhp27bxd96aajapqd9w";
        };
      };
    };
    "nesbot/carbon" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nesbot-carbon-889546413c97de2d05063b8cb7b193c2531ea211";
        src = fetchurl {
          url = "https://api.github.com/repos/briannesbitt/Carbon/zipball/889546413c97de2d05063b8cb7b193c2531ea211";
          sha256 = "0rpqjf1ms4fpx454icdiiyqj1p78jgyf82bmbbzwpycj5q26kxsh";
        };
      };
    };
    "phar-io/manifest" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phar-io-manifest-97803eca37d319dfa7826cc2437fc020857acb53";
        src = fetchurl {
          url = "https://api.github.com/repos/phar-io/manifest/zipball/97803eca37d319dfa7826cc2437fc020857acb53";
          sha256 = "107dsj04ckswywc84dvw42kdrqd4y6yvb2qwacigyrn05p075c1w";
        };
      };
    };
    "phar-io/version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phar-io-version-4f7fd7836c6f332bb2933569e566a0d6c4cbed74";
        src = fetchurl {
          url = "https://api.github.com/repos/phar-io/version/zipball/4f7fd7836c6f332bb2933569e566a0d6c4cbed74";
          sha256 = "0mdbzh1y0m2vvpf54vw7ckcbcf1yfhivwxgc9j9rbb7yifmlyvsg";
        };
      };
    };
    "phpstan/phpstan-php-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-php-parser-1c7670dd92da864b5d019f22d9f512a6ae18b78e";
        src = fetchurl {
          url = "https://api.github.com/repos/phpstan/phpstan-php-parser/zipball/1c7670dd92da864b5d019f22d9f512a6ae18b78e";
          sha256 = "11dzwxnznmr9scxmb0p2lswszpca274nxs3wzyb2iypnrsldc1br";
        };
      };
    };
    "phpstan/phpstan-phpunit" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-phpunit-54a24bd23e9e80ee918cdc24f909d376c2e273f7";
        src = fetchurl {
          url = "https://api.github.com/repos/phpstan/phpstan-phpunit/zipball/54a24bd23e9e80ee918cdc24f909d376c2e273f7";
          sha256 = "15zlilbc92fbv5vywmrqz7jxa21hfbkngij3jl2sqdawzn95i4ya";
        };
      };
    };
    "phpstan/phpstan-strict-rules" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-strict-rules-23e5f377ee6395a1a04842d3d6ed4bd25e7b44a6";
        src = fetchurl {
          url = "https://api.github.com/repos/phpstan/phpstan-strict-rules/zipball/23e5f377ee6395a1a04842d3d6ed4bd25e7b44a6";
          sha256 = "1fxl75wnznx23g2awchlanql8av3awbz275hx8l23102wqik8460";
        };
      };
    };
    "phpunit/php-code-coverage" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-code-coverage-e4bf60d2220b4baaa0572986b5d69870226b06df";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/php-code-coverage/zipball/e4bf60d2220b4baaa0572986b5d69870226b06df";
          sha256 = "1gnbjqsxnmpdn8lfhdz8azzakpbvf5pglygmhcb95jb34k2qs69v";
        };
      };
    };
    "phpunit/php-file-iterator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-file-iterator-cf1c2e7c203ac650e352f4cc675a7021e7d1b3cf";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/php-file-iterator/zipball/cf1c2e7c203ac650e352f4cc675a7021e7d1b3cf";
          sha256 = "1407d8f1h35w4sdikq2n6cz726css2xjvlyr1m4l9a53544zxcnr";
        };
      };
    };
    "phpunit/php-invoker" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-invoker-5a10147d0aaf65b58940a0b72f71c9ac0423cc67";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/php-invoker/zipball/5a10147d0aaf65b58940a0b72f71c9ac0423cc67";
          sha256 = "1vqnnjnw94mzm30n9n5p2bfgd3wd5jah92q6cj3gz1nf0qigr4fh";
        };
      };
    };
    "phpunit/php-text-template" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-text-template-5da5f67fc95621df9ff4c4e5a84d6a8a2acf7c28";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/php-text-template/zipball/5da5f67fc95621df9ff4c4e5a84d6a8a2acf7c28";
          sha256 = "0ff87yzywizi6j2ps3w0nalpx16mfyw3imzn6gj9jjsfwc2bb8lq";
        };
      };
    };
    "phpunit/php-timer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-timer-5a63ce20ed1b5bf577850e2c4e87f4aa902afbd2";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/php-timer/zipball/5a63ce20ed1b5bf577850e2c4e87f4aa902afbd2";
          sha256 = "0g1g7yy4zk1bidyh165fsbqx5y8f1c8pxikvcahzlfsr9p2qxk6a";
        };
      };
    };
    "phpunit/phpunit" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-phpunit-a2bc7ffdca99f92d959b3f2270529334030bba38";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/phpunit/zipball/a2bc7ffdca99f92d959b3f2270529334030bba38";
          sha256 = "0nz6133v1hf6n6pibg6h5fh0xxrjajcx3x6bma2sxzqx06b5dihp";
        };
      };
    };
    "psr/container" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-container-c71ecc56dfe541dbd90c5360474fbc405f8d5963";
        src = fetchurl {
          url = "https://api.github.com/repos/php-fig/container/zipball/c71ecc56dfe541dbd90c5360474fbc405f8d5963";
          sha256 = "1mvan38yb65hwk68hl0p7jymwzr4zfnaxmwjbw7nj3rsknvga49i";
        };
      };
    };
    "psr/event-dispatcher" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-event-dispatcher-dbefd12671e8a14ec7f180cab83036ed26714bb0";
        src = fetchurl {
          url = "https://api.github.com/repos/php-fig/event-dispatcher/zipball/dbefd12671e8a14ec7f180cab83036ed26714bb0";
          sha256 = "05nicsd9lwl467bsv4sn44fjnnvqvzj1xqw2mmz9bac9zm66fsjd";
        };
      };
    };
    "psr/log" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-log-fe5ea303b0887d5caefd3d431c3e61ad47037001";
        src = fetchurl {
          url = "https://api.github.com/repos/php-fig/log/zipball/fe5ea303b0887d5caefd3d431c3e61ad47037001";
          sha256 = "0a0rwg38vdkmal3nwsgx58z06qkfl85w2yvhbgwg45anr0b3bhmv";
        };
      };
    };
    "psr/simple-cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-simple-cache-764e0b3939f5ca87cb904f570ef9be2d78a07865";
        src = fetchurl {
          url = "https://api.github.com/repos/php-fig/simple-cache/zipball/764e0b3939f5ca87cb904f570ef9be2d78a07865";
          sha256 = "0hgcanvd9gqwkaaaq41lh8fsfdraxmp2n611lvqv69jwm1iy76g8";
        };
      };
    };
    "sebastian/cli-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-cli-parser-442e7c7e687e42adc03470c7b668bc4b2402c0b2";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/cli-parser/zipball/442e7c7e687e42adc03470c7b668bc4b2402c0b2";
          sha256 = "074qzdq19k9x4svhq3nak5h348xska56v1sqnhk1aj0jnrx02h37";
        };
      };
    };
    "sebastian/code-unit" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-code-unit-1fc9f64c0927627ef78ba436c9b17d967e68e120";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/code-unit/zipball/1fc9f64c0927627ef78ba436c9b17d967e68e120";
          sha256 = "04vlx050rrd54mxal7d93pz4119pas17w3gg5h532anfxjw8j7pm";
        };
      };
    };
    "sebastian/code-unit-reverse-lookup" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-code-unit-reverse-lookup-ac91f01ccec49fb77bdc6fd1e548bc70f7faa3e5";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/code-unit-reverse-lookup/zipball/ac91f01ccec49fb77bdc6fd1e548bc70f7faa3e5";
          sha256 = "1h1jbzz3zak19qi4mab2yd0ddblpz7p000jfyxfwd2ds0gmrnsja";
        };
      };
    };
    "sebastian/comparator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-comparator-fa0f136dd2334583309d32b62544682ee972b51a";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/comparator/zipball/fa0f136dd2334583309d32b62544682ee972b51a";
          sha256 = "0m8ibkwaxw2q5v84rlvy7ylpkddscsa8hng0cjczy4bqpqavr83w";
        };
      };
    };
    "sebastian/complexity" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-complexity-739b35e53379900cc9ac327b2147867b8b6efd88";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/complexity/zipball/739b35e53379900cc9ac327b2147867b8b6efd88";
          sha256 = "1y4yz8n8hszbhinf9ipx3pqyvgm7gz0krgyn19z0097yq3bbq8yf";
        };
      };
    };
    "sebastian/diff" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-diff-3461e3fccc7cfdfc2720be910d3bd73c69be590d";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/diff/zipball/3461e3fccc7cfdfc2720be910d3bd73c69be590d";
          sha256 = "0967nl6cdnr0v0z83w4xy59agn60kfv8gb41aw3fpy1n2wpp62dj";
        };
      };
    };
    "sebastian/environment" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-environment-1b5dff7bb151a4db11d49d90e5408e4e938270f7";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/environment/zipball/1b5dff7bb151a4db11d49d90e5408e4e938270f7";
          sha256 = "0qhpamp9hi00zh7warf3mfbfrrpj1rdci90nnzibvii0vdp98691";
        };
      };
    };
    "sebastian/exporter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-exporter-ac230ed27f0f98f597c8a2b6eb7ac563af5e5b9d";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/exporter/zipball/ac230ed27f0f98f597c8a2b6eb7ac563af5e5b9d";
          sha256 = "1a6yj8v8rwj3igip8xysdifvbd7gkzmwrj9whdx951pdq7add46j";
        };
      };
    };
    "sebastian/global-state" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-global-state-0ca8db5a5fc9c8646244e629625ac486fa286bf2";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/global-state/zipball/0ca8db5a5fc9c8646244e629625ac486fa286bf2";
          sha256 = "1csrfa5b7ivza712lfmbywp9jhwf4ls5lc0vn812xljkj7w24kg1";
        };
      };
    };
    "sebastian/lines-of-code" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-lines-of-code-c1c2e997aa3146983ed888ad08b15470a2e22ecc";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/lines-of-code/zipball/c1c2e997aa3146983ed888ad08b15470a2e22ecc";
          sha256 = "0fay9s5cm16gbwr7qjihwrzxn7sikiwba0gvda16xng903argbk0";
        };
      };
    };
    "sebastian/object-enumerator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-object-enumerator-5c9eeac41b290a3712d88851518825ad78f45c71";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/object-enumerator/zipball/5c9eeac41b290a3712d88851518825ad78f45c71";
          sha256 = "11853z07w8h1a67wsjy3a6ir5x7khgx6iw5bmrkhjkiyvandqcn1";
        };
      };
    };
    "sebastian/object-reflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-object-reflector-b4f479ebdbf63ac605d183ece17d8d7fe49c15c7";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/object-reflector/zipball/b4f479ebdbf63ac605d183ece17d8d7fe49c15c7";
          sha256 = "0g5m1fswy6wlf300x1vcipjdljmd3vh05hjqhqfc91byrjbk4rsg";
        };
      };
    };
    "sebastian/recursion-context" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-recursion-context-cd9d8cf3c5804de4341c283ed787f099f5506172";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/recursion-context/zipball/cd9d8cf3c5804de4341c283ed787f099f5506172";
          sha256 = "1k0ki1krwq6329vsbw3515wsyg8a7n2l83lk19pdc12i2lg9nhpy";
        };
      };
    };
    "sebastian/resource-operations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-resource-operations-0f4443cb3a1d92ce809899753bc0d5d5a8dd19a8";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/resource-operations/zipball/0f4443cb3a1d92ce809899753bc0d5d5a8dd19a8";
          sha256 = "0p5s8rp7mrhw20yz5wx1i4k8ywf0h0ximcqan39n9qnma1dlnbyr";
        };
      };
    };
    "sebastian/type" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-type-fb3fe09c5f0bae6bc27ef3ce933a1e0ed9464b6e";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/type/zipball/fb3fe09c5f0bae6bc27ef3ce933a1e0ed9464b6e";
          sha256 = "1llm99gn3kgizqsa4qhdnav58saf3yxi64jsbpirs0cg11kqlx37";
        };
      };
    };
    "sebastian/version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-version-c6c1022351a901512170118436c764e473f6de8c";
        src = fetchurl {
          url = "https://api.github.com/repos/sebastianbergmann/version/zipball/c6c1022351a901512170118436c764e473f6de8c";
          sha256 = "1bs7bwa9m0fin1zdk7vqy5lxzlfa9la90lkl27sn0wr00m745ig1";
        };
      };
    };
    "squizlabs/php_codesniffer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "squizlabs-php_codesniffer-1359e176e9307e906dc3d890bcc9603ff6d90619";
        src = fetchurl {
          url = "https://api.github.com/repos/squizlabs/PHP_CodeSniffer/zipball/1359e176e9307e906dc3d890bcc9603ff6d90619";
          sha256 = "0bgb02zirxg0swfil3hacp7ry4cv5rwnbjkrj4l3hqln97cvmn21";
        };
      };
    };
    "symfony/deprecation-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-deprecation-contracts-26954b3d62a6c5fd0ea8a2a00c0353a14978d05c";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/deprecation-contracts/zipball/26954b3d62a6c5fd0ea8a2a00c0353a14978d05c";
          sha256 = "1wlaj9ngbyjmgr92gjyf7lsmjfswyh8vpbzq5rdzaxjb6bcsj3dp";
        };
      };
    };
    "symfony/error-handler" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-error-handler-f000c166cb3ee32c4c822831a79260a135cd59b5";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/error-handler/zipball/f000c166cb3ee32c4c822831a79260a135cd59b5";
          sha256 = "1bncrddzfnpqvnbchpw5c449w443wpb4iy8rcbmww0sa9v928vx0";
        };
      };
    };
    "symfony/event-dispatcher" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-5c85b58422865d42c6eb46f7693339056db098a8";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/event-dispatcher/zipball/5c85b58422865d42c6eb46f7693339056db098a8";
          sha256 = "1ifflv5n33bdzlsvfh2gz4fvn6pq9wr313pd0fb4ngqd8m2kk2xp";
        };
      };
    };
    "symfony/event-dispatcher-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-contracts-7bc61cc2db649b4637d331240c5346dcc7708051";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/event-dispatcher-contracts/zipball/7bc61cc2db649b4637d331240c5346dcc7708051";
          sha256 = "1crx2j4g5jn904fwk7919ar9zpyfd5bvgm80lmyg15kinsjm3w4i";
        };
      };
    };
    "symfony/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-finder-09cb683ba5720385ea6966e5e06be2a34f2568b1";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/finder/zipball/09cb683ba5720385ea6966e5e06be2a34f2568b1";
          sha256 = "0bdslrxfpq935g42jvwwnqvkjajrpv1n6wdyng2cis8wf17yzn44";
        };
      };
    };
    "symfony/http-foundation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-foundation-86eec2c66d00a2dd03d84352cd10b12df73101ec";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/http-foundation/zipball/86eec2c66d00a2dd03d84352cd10b12df73101ec";
          sha256 = "0n853b68gn3xf5z8r49ym6nnynn1if7h0q94hhr6xig0razjc8j0";
        };
      };
    };
    "symfony/http-kernel" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-kernel-8ba1344821807ad51f230f0d01e0fa8f366e4abb";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/http-kernel/zipball/8ba1344821807ad51f230f0d01e0fa8f366e4abb";
          sha256 = "1w2fyrh3dr3m9wm0wn1zlaac7m99nwk4n467x0lml2r4s3aj03hd";
        };
      };
    };
    "symfony/mime" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-mime-ad9878bede5707cdf5ff7f5c86d82a921bbbfe1c";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/mime/zipball/ad9878bede5707cdf5ff7f5c86d82a921bbbfe1c";
          sha256 = "0a64cs6m4s6l87cbgysxrf92j60iwmh90iw5q1gdrlgbpij66r8d";
        };
      };
    };
    "symfony/polyfill-ctype" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-ctype-5bbc823adecdae860bb64756d639ecfec17b050a";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-ctype/zipball/5bbc823adecdae860bb64756d639ecfec17b050a";
          sha256 = "0vyv70z1yi2is727d1mkb961w5r1pb1v3wy1pvdp30h8ffy15wk6";
        };
      };
    };
    "symfony/polyfill-intl-idn" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-intl-idn-639084e360537a19f9ee352433b84ce831f3d2da";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-intl-idn/zipball/639084e360537a19f9ee352433b84ce831f3d2da";
          sha256 = "1i2wcsbfbwdyrx8545yrrvbdaf4l2393pjvg9266q74611j6pzxj";
        };
      };
    };
    "symfony/polyfill-intl-normalizer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-intl-normalizer-19bd1e4fcd5b91116f14d8533c57831ed00571b6";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-intl-normalizer/zipball/19bd1e4fcd5b91116f14d8533c57831ed00571b6";
          sha256 = "1d80jph5ykiw6ydv8fwd43s0aglh24qc1yrzds2f3aqanpbk1gr2";
        };
      };
    };
    "symfony/polyfill-mbstring" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-mbstring-8ad114f6b39e2c98a8b0e3bd907732c207c2b534";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-mbstring/zipball/8ad114f6b39e2c98a8b0e3bd907732c207c2b534";
          sha256 = "1ym84qp609i50lv4vkd4yz99y19kaxd5kmpdnh66mxx1a4a104mi";
        };
      };
    };
    "symfony/polyfill-php72" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-php72-869329b1e9894268a8a61dabb69153029b7a8c97";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-php72/zipball/869329b1e9894268a8a61dabb69153029b7a8c97";
          sha256 = "1h0lbh8d41sa4fymmw03yzws3v3z0lz4lv1kgcld7r53i2m3wfwp";
        };
      };
    };
    "symfony/polyfill-php80" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-php80-7a6ff3f1959bb01aefccb463a0f2cd3d3d2fd936";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/polyfill-php80/zipball/7a6ff3f1959bb01aefccb463a0f2cd3d3d2fd936";
          sha256 = "16yydk7rsknlasrpn47n4b4js8svvp4rxzw99dkav52wr3cqmcwd";
        };
      };
    };
    "symfony/translation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-translation-6f99eb179aee4652c0a7cd7c11f2a870d904330c";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/translation/zipball/6f99eb179aee4652c0a7cd7c11f2a870d904330c";
          sha256 = "007rfhxpijbv3h2nz64ay5pawxgj1l4lfyprhx4n5f16fcr9ahml";
        };
      };
    };
    "symfony/translation-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-translation-contracts-acbfbb274e730e5a0236f619b6168d9dedb3e282";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/translation-contracts/zipball/acbfbb274e730e5a0236f619b6168d9dedb3e282";
          sha256 = "1r496j63a6q3ch0ax76qa1apmc4iqf41zc8rf6yn8vkir3nzkqr0";
        };
      };
    };
    "symfony/var-dumper" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-var-dumper-72af925ddd41ca0372d166d004bc38a00c4608cc";
        src = fetchurl {
          url = "https://api.github.com/repos/symfony/var-dumper/zipball/72af925ddd41ca0372d166d004bc38a00c4608cc";
          sha256 = "0b7wykblnqx3scbykya10r6nkpfvzks6sl2gpkwdrns8s32qnxc4";
        };
      };
    };
    "theseer/tokenizer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "theseer-tokenizer-34a41e998c2183e22995f158c581e7b5e755ab9e";
        src = fetchurl {
          url = "https://api.github.com/repos/theseer/tokenizer/zipball/34a41e998c2183e22995f158c581e7b5e755ab9e";
          sha256 = "1za4a017kjb4rw2ydglip4bp5q2y7mfiycj3fvnp145i84jc7n0q";
        };
      };
    };
    "voku/portable-ascii" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "voku-portable-ascii-b56450eed252f6801410d810c8e1727224ae0743";
        src = fetchurl {
          url = "https://api.github.com/repos/voku/portable-ascii/zipball/b56450eed252f6801410d810c8e1727224ae0743";
          sha256 = "0gwlv1hr6ycrf8ik1pnvlwaac8cpm8sa1nf4d49s8rp4k2y5anyl";
        };
      };
    };
  };
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "jbboehr-phpstan-laravel-validation";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "AGPL-3.0-or-later";
  };
}
