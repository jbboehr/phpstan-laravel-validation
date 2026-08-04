<?php /* laravel 9.45.1 commit 99263998eb4e4b5cd0d536737c00cb4ba5f36ff0 */ return [
    'B6t9ZDdKlmckI77_pXRMKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationFactoryTest::testValidateMethodCanBeCalledPublicly:108',
        'data' => [
            'bar' => [
                'baz'
            ]
        ],
        'validated' => [
            'bar' => [
                'baz'
            ]
        ],
        'rules' => [
            'bar' => 'foo'
        ],
        'expandedRules' => [
            'bar' => [
                'foo'
            ]
        ]
    ],
    'lbck30Ic3nl7VG7tdAWfSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnNestedArrays:112',
        'data' => [
            'foo' => [
                'bar' => [
                    'baz' => 'nonEmpty'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                'bar' => [
                    'baz' => 'nonEmpty'
                ]
            ]
        ],
        'rules' => [
            'foo.bar.baz' => 'sometimes|required'
        ],
        'expandedRules' => [
            'foo.bar.baz' => [
                'sometimes',
                'required'
            ]
        ]
    ],
    'Twl85O2IO8G52X-RiuYZsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnArrays:143',
        'data' => [
            'foo' => [
                'bar',
                'baz',
                'moo',
                'pew',
                'boom'
            ]
        ],
        'validated' => [
            'foo' => [
                'bar',
                'baz',
                'moo',
                'pew',
                'boom'
            ]
        ],
        'rules' => [
            'foo' => 'sometimes|required|between:5,10'
        ],
        'expandedRules' => [
            'foo' => [
                'sometimes',
                'required',
                'between:5,10'
            ]
        ]
    ],
    'a9oi3OOVp6Ll21uPQptGww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntThrowOnPass:161',
        'data' => [
            'foo' => 'bar'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'required'
        ],
        'expandedRules' => [
            'foo' => [
                'required'
            ]
        ]
    ],
    'CX2WJ-v2DXFO4RE1LXXrJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUsingNestedValidationRulesPasses:223',
        'data' => [
            'items' => [
                [
                    '|name' => '|ABC123'
                ]
            ]
        ],
        'validated' => [
            'items' => [
                [
                    '|name' => '|ABC123'
                ]
            ]
        ],
        'rules' => [
            'items' => [
                'array'
            ],
            'items.*' => [
                'array',
                [
                    'required_array_keys',
                    '|name'
                ]
            ],
            'items.*.|name' => [
                [
                    'in',
                    '|ABC123'
                ]
            ]
        ],
        'expandedRules' => [
            'items' => [
                'array'
            ],
            'items.0' => [
                'array',
                [
                    'required_array_keys',
                    '|name'
                ]
            ],
            'items.0.|name' => [
                [
                    'in',
                    '|ABC123'
                ]
            ]
        ]
    ],
    'kMGHe6WjurJIF4knjpAJpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:242',
        'data' => [
            'x' => ''
        ],
        'validated' => [
            'x' => ''
        ],
        'rules' => [
            'x' => 'size:10|array|integer|min:5'
        ],
        'expandedRules' => [
            'x' => [
                'size:10',
                'array',
                'integer',
                'min:5'
            ]
        ]
    ],
    '47I0p_PKRVAK8Q79lTeFQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:250',
        'data' => [
            'x' => ''
        ],
        'validated' => [
            'x' => ''
        ],
        'rules' => [
            'x' => 'array'
        ],
        'expandedRules' => [
            'x' => [
                'array'
            ]
        ]
    ],
    'O7_6xDflrkU3GPg6Tqr_tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullable:277',
        'data' => [
            'x' => null,
            'y' => null,
            'z' => null,
            'a' => null,
            'b' => null
        ],
        'validated' => [
            'x' => null,
            'y' => null,
            'z' => null,
            'a' => null,
            'b' => null
        ],
        'rules' => [
            'x' => 'string|nullable',
            'y' => 'integer|nullable',
            'z' => 'numeric|nullable',
            'a' => 'array|nullable',
            'b' => 'bool|nullable'
        ],
        'expandedRules' => [
            'x' => [
                'string',
                'nullable'
            ],
            'y' => [
                'integer',
                'nullable'
            ],
            'z' => [
                'numeric',
                'nullable'
            ],
            'a' => [
                'array',
                'nullable'
            ],
            'b' => [
                'bool',
                'nullable'
            ]
        ]
    ],
    'lFDzKB090fiuhgz7QTwlLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullableMakesNoDifferenceIfImplicitRuleExists:302',
        'data' => [
            'x' => null,
            'y' => null
        ],
        'validated' => [
            'x' => null,
            'y' => null
        ],
        'rules' => [
            'x' => 'nullable|required_with:y|integer',
            'y' => 'nullable|required_with:x|integer'
        ],
        'expandedRules' => [
            'x' => [
                'nullable',
                'required_with:y',
                'integer'
            ],
            'y' => [
                'nullable',
                'required_with:x',
                'integer'
            ]
        ]
    ],
    'hYqXNLY8JjamO_8wTwpUPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testIndexValuesAreReplaced:694',
        'data' => [
            'input' => [
                [
                    'name' => 'Bob'
                ],
                [
                    'name' => 'Jane'
                ]
            ]
        ],
        'validated' => [
            'input' => [
                [
                    'name' => 'Bob'
                ],
                [
                    'name' => 'Jane'
                ]
            ]
        ],
        'rules' => [
            'input.*.name' => 'required'
        ],
        'expandedRules' => [
            'input.0.name' => [
                'required'
            ],
            'input.1.name' => [
                'required'
            ]
        ]
    ],
    'KZV6levZTJH7_2WpqNLu9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPositionValuesAreReplaced:726',
        'data' => [
            'input' => [
                [
                    'name' => 'Bob'
                ],
                [
                    'name' => 'Jane'
                ]
            ]
        ],
        'validated' => [
            'input' => [
                [
                    'name' => 'Bob'
                ],
                [
                    'name' => 'Jane'
                ]
            ]
        ],
        'rules' => [
            'input.*.name' => 'required'
        ],
        'expandedRules' => [
            'input.0.name' => [
                'required'
            ],
            'input.1.name' => [
                'required'
            ]
        ]
    ],
    'O9Z0foUATMW1IgrG5ufmmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArray:935',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => 'Array'
        ],
        'expandedRules' => [
            'foo' => [
                'Array'
            ]
        ]
    ],
    '9qXOaNTT7KShQccyu3yt_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:947',
        'data' => [
            'user' => [
                'name' => 'Duilio',
                'username' => 'duilio'
            ]
        ],
        'validated' => [
            'user' => [
                'name' => 'Duilio',
                'username' => 'duilio'
            ]
        ],
        'rules' => [
            'user' => 'array:name,username'
        ],
        'expandedRules' => [
            'user' => [
                'array:name,username'
            ]
        ]
    ],
    'q5ZcatMrdNVvi9u2Ub-lUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:951',
        'data' => [
            'user' => [
                'name' => 'Duilio'
            ]
        ],
        'validated' => [
            'user' => [
                'name' => 'Duilio'
            ]
        ],
        'rules' => [
            'user' => 'array:name,username'
        ],
        'expandedRules' => [
            'user' => [
                'array:name,username'
            ]
        ]
    ],
    'fRctmGkk7X0XmUO9LEnS_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1025',
        'data' => [
            'password' => 'foo'
        ],
        'validated' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => 'current_password'
        ],
        'expandedRules' => [
            'password' => [
                'current_password'
            ]
        ]
    ],
    'seRMaqH98E4YGgbWO-PsBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1049',
        'data' => [
            'password' => 'foo'
        ],
        'validated' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => 'current_password:custom'
        ],
        'expandedRules' => [
            'password' => [
                'current_password:custom'
            ]
        ]
    ],
    '64w24OAgdmocN69XEevIDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1062',
        'data' => [
            'foo' => [
                [
                    'id' => 1
                ],
                []
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'filled'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'filled'
            ],
            'foo.1.id' => [
                'filled'
            ]
        ]
    ],
    'Q6tSzXHhPOLprAIQJ08_5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1102',
        'data' => [
            'name' => null
        ],
        'validated' => [
            'name' => null
        ],
        'rules' => [
            'name' => 'present|nullable'
        ],
        'expandedRules' => [
            'name' => [
                'present',
                'nullable'
            ]
        ]
    ],
    'OJ6_-zM0THL1e0H8WRBeJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1105',
        'data' => [
            'name' => ''
        ],
        'validated' => [
            'name' => ''
        ],
        'rules' => [
            'name' => 'present'
        ],
        'expandedRules' => [
            'name' => [
                'present'
            ]
        ]
    ],
    'mvL1JEMJLnWthAbhgAmoWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1114',
        'data' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => ''
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => ''
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present'
            ],
            'foo.1.id' => [
                'present'
            ]
        ]
    ],
    '-gYyliVVPVnIpQDGYL_p9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1117',
        'data' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => null
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present'
            ],
            'foo.1.id' => [
                'present'
            ]
        ]
    ],
    'm4DDa5X3VmCgeRYhbilWLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1130',
        'data' => [
            'name' => 'foo'
        ],
        'validated' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => 'Required'
        ],
        'expandedRules' => [
            'name' => [
                'Required'
            ]
        ]
    ],
    'XzPZSj-92TFNYGGoNGec4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1138',
        'data' => [
            'name' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'name' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'name' => 'Required'
        ],
        'expandedRules' => [
            'name' => [
                'Required'
            ]
        ]
    ],
    'Vi4C6FGl1Vliy-FA_KgOcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1143',
        'data' => [
            'files' => [
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })(),
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })()
            ]
        ],
        'validated' => [
            'files' => [
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })(),
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })()
            ]
        ],
        'rules' => [
            'files.0' => 'Required',
            'files.1' => 'Required'
        ],
        'expandedRules' => [
            'files.0' => [
                'Required'
            ],
            'files.1' => [
                'Required'
            ]
        ]
    ],
    'szX3LCORa8HoSPUW1gLOSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1146',
        'data' => [
            'files' => [
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })(),
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })()
            ]
        ],
        'validated' => [
            'files' => [
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })(),
                (static function() {
                    $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                    $object = $class->newInstanceWithoutConstructor();

                    return $object;
                })()
            ]
        ],
        'rules' => [
            'files' => 'Required'
        ],
        'expandedRules' => [
            'files' => [
                'Required'
            ]
        ]
    ],
    'rybhpXvgO3zn5cQPOCygUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1165',
        'data' => [
            'first' => 'Taylor',
            'last' => 'Otwell'
        ],
        'validated' => [
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => 'required_with:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_with:first'
            ]
        ]
    ],
    'Zfhj1JvYgtEMZph-bpSkyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1169',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => ''
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'required_with:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_with:file'
            ]
        ]
    ],
    'syYTzHJ9w-r86Yjl9_6gTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1174',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => 'required_with:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_with:file'
            ]
        ]
    ],
    '-rWJRvaHjQZv4fjFpVQPBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1199',
        'data' => [
            'first' => 'Taylor',
            'last' => ''
        ],
        'validated' => [
            'last' => ''
        ],
        'rules' => [
            'last' => 'required_without:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_without:first'
            ]
        ]
    ],
    'Vh_4Kn2sStZObD5t6UHfvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1208',
        'data' => [
            'first' => 'Taylor',
            'last' => 'Otwell'
        ],
        'validated' => [
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => 'required_without:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_without:first'
            ]
        ]
    ],
    'wiIxnK2rRIWV19MEHlkbKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1211',
        'data' => [
            'last' => 'Otwell'
        ],
        'validated' => [
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => 'required_without:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_without:first'
            ]
        ]
    ],
    'K4fTtnxkTFawYyGjhTzU7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1223',
        'data' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => 'required_without:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without:file'
            ]
        ]
    ],
    'AozFR5ywozVM0H_BP1huGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1228',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => 'required_without:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without:file'
            ]
        ]
    ],
    '9KklbbDNhwScutfFEQX3NA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1233',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => 'required_without:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without:file'
            ]
        ]
    ],
    'N7wwsAE2rmCjKRodu1Bmxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1238',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => 'required_without:file'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without:file'
            ]
        ]
    ],
    '61rGAtu6dcCXWBW0YGCyAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1269',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without:f2,f3',
            'f2' => 'required_without:f1,f3',
            'f3' => 'required_without:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ]
    ],
    'WcC9wuGj4NjZ34miHLjjSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1272',
        'data' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'validated' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without:f2,f3',
            'f2' => 'required_without:f1,f3',
            'f3' => 'required_without:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ]
    ],
    'EFk2DPjCYFl519x_hwf0Cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1275',
        'data' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'validated' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without:f2,f3',
            'f2' => 'required_without:f1,f3',
            'f3' => 'required_without:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ]
    ],
    '_cvytLFernM2WSk7kpRjVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1278',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'rules' => [
            'f1' => 'required_without:f2,f3',
            'f2' => 'required_without:f1,f3',
            'f3' => 'required_without:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ]
    ],
    '8v1uZp2HnfFrSwQvCk8fGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1295',
        'data' => [
            'f1' => 'foo'
        ],
        'validated' => [
            'f1' => 'foo'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'bbmO2U3qdcYO1vSVJFjWCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1298',
        'data' => [
            'f2' => 'foo'
        ],
        'validated' => [
            'f2' => 'foo'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    '9T8HUQSg-UrNCAiRxbUuDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1301',
        'data' => [
            'f3' => 'foo'
        ],
        'validated' => [
            'f3' => 'foo'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'l1FDq_gMvGVTctfqNHopcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1304',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'kMNaoH1QQrguVhONH-nPYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1307',
        'data' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'validated' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'mvBsCrOr73mcCzF71W7WVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1310',
        'data' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'validated' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'QVLVsvmo4AWTcmMSJ4gkwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1313',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'rules' => [
            'f1' => 'required_without_all:f2,f3',
            'f2' => 'required_without_all:f1,f3',
            'f3' => 'required_without_all:f1,f2'
        ],
        'expandedRules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ]
    ],
    'JK-TkjDwDi0DCz35Rx_WBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1324',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'validated' => [
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => 'required_if:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'required_if:first,taylor'
            ]
        ]
    ],
    '70ZV4h4BC-xMN_jTlE5FjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1328',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'validated' => [
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => 'required_if:first,taylor,dayle'
        ],
        'expandedRules' => [
            'last' => [
                'required_if:first,taylor,dayle'
            ]
        ]
    ],
    'uohhrMkCsSSLGPhQ4OqH9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1332',
        'data' => [
            'first' => 'dayle',
            'last' => 'rees'
        ],
        'validated' => [
            'last' => 'rees'
        ],
        'rules' => [
            'last' => 'required_if:first,taylor,dayle'
        ],
        'expandedRules' => [
            'last' => [
                'required_if:first,taylor,dayle'
            ]
        ]
    ],
    'NwnXZs69F1bRGkNzCxmAVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1396',
        'data' => [
            'foo' => null
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'nullable|boolean',
            'baz' => 'nullable|required_if:foo,false'
        ],
        'expandedRules' => [
            'foo' => [
                'nullable',
                'boolean'
            ],
            'baz' => [
                'nullable',
                'required_if:foo,false'
            ]
        ]
    ],
    'dFxeW4ChvMT3tFz7GDifWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1428',
        'data' => [
            'first' => 'sven',
            'last' => 'wittevrongel'
        ],
        'validated' => [
            'last' => 'wittevrongel'
        ],
        'rules' => [
            'last' => 'required_unless:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'required_unless:first,taylor'
            ]
        ]
    ],
    'beOSHH3sNqVC8G_YZuLTvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1448',
        'data' => [
            'bar' => '1'
        ],
        'validated' => [
            'bar' => '1'
        ],
        'rules' => [
            'bar' => 'required_unless:foo,true'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,true'
            ]
        ]
    ],
    'KpG97ToEk3lEIqQ_XlTSEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:1536',
        'data' => [
            'foo' => true,
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'prohibited_if:foo,false'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if:foo,false'
            ]
        ]
    ],
    'U2j9JvtHw1XI-wDMCAIyzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1558',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'validated' => [
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => 'prohibited_unless:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_unless:first,taylor'
            ]
        ]
    ],
    '5wcpg5kFIul3DqeNGIvHvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1566',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'validated' => [
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => 'prohibited_unless:first,taylor,jess'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_unless:first,taylor,jess'
            ]
        ]
    ],
    'r3gvuenBZXNjsi7A5wkOHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1570',
        'data' => [
            'first' => 'jess',
            'last' => 'archer'
        ],
        'validated' => [
            'last' => 'archer'
        ],
        'rules' => [
            'last' => 'prohibited_unless:first,taylor,jess'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_unless:first,taylor,jess'
            ]
        ]
    ],
    'VqPCJ7IC80tT5VKUan97lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1574',
        'data' => [
            'foo' => false,
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'prohibited_unless:foo,false'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_unless:foo,false'
            ]
        ]
    ],
    'Iu3vPd9u6b-Sv5l3Pt_vvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1616',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'prohibits:emails'
        ],
        'expandedRules' => [
            'email' => [
                'prohibits:emails'
            ]
        ]
    ],
    'QRr5hhxze1JJweIbsMi-Fw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1620',
        'data' => [
            'email' => 'foo',
            'other' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'prohibits:email_address,emails'
        ],
        'expandedRules' => [
            'email' => [
                'prohibits:email_address,emails'
            ]
        ]
    ],
    'fypVcDQUQyBCVOFP_mA-bQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:1684',
        'data' => [
            'foo' => [
                1,
                2
            ],
            'bar' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2
            ]
        ],
        'rules' => [
            'foo.*' => 'in_array:bar.*'
        ],
        'expandedRules' => [
            'foo.0' => [
                'in_array:bar.*'
            ],
            'foo.1' => [
                'in_array:bar.*'
            ]
        ]
    ],
    'CsbO-ilhBSYQHLOAeZHCRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:1692',
        'data' => [
            'foo' => [
                [
                    'bar_id' => 1
                ],
                [
                    'bar_id' => 2
                ]
            ],
            'bar' => [
                [
                    'id' => 1,
                    0 => [
                        'id' => 2
                    ]
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'bar_id' => 1
                ],
                [
                    'bar_id' => 2
                ]
            ]
        ],
        'rules' => [
            'foo.*.bar_id' => 'in_array:bar.*.id'
        ],
        'expandedRules' => [
            'foo.0.bar_id' => [
                'in_array:bar.*.id'
            ],
            'foo.1.bar_id' => [
                'in_array:bar.*.id'
            ]
        ]
    ],
    'fvrKwA3UFsEZQzxn3zY6Ew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:1709',
        'data' => [
            'password' => 'foo',
            'password_confirmation' => 'foo'
        ],
        'validated' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => 'Confirmed'
        ],
        'expandedRules' => [
            'password' => [
                'Confirmed'
            ]
        ]
    ],
    'gcf_gdPj4gjYuvbjX_K-UA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:1725',
        'data' => [
            'foo' => 'bar',
            'baz' => 'bar'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Same:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Same:baz'
            ]
        ]
    ],
    'gGetEB3LDGUUsWygnZjTuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:1731',
        'data' => [
            'foo' => null,
            'baz' => null
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'Same:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Same:baz'
            ]
        ]
    ],
    'mBpPPaNlggncuTSWZQZJBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1738',
        'data' => [
            'foo' => 'bar',
            'baz' => 'boom'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Different:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:baz'
            ]
        ]
    ],
    'KlxKIIeSkYrUL8DVYC3iMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1741',
        'data' => [
            'foo' => 'bar',
            'baz' => null
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Different:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:baz'
            ]
        ]
    ],
    'kaDUN2JSeaNoy4-oHwfMfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1744',
        'data' => [
            'foo' => 'bar'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Different:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:baz'
            ]
        ]
    ],
    'aIiPOl7QQ_Bq4oJmTe4yUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1750',
        'data' => [
            'foo' => '1e2',
            'baz' => '100'
        ],
        'validated' => [
            'foo' => '1e2'
        ],
        'rules' => [
            'foo' => 'Different:baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:baz'
            ]
        ]
    ],
    'BYzUhn1SEmJn9UTJg-bHWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1753',
        'data' => [
            'foo' => 'bar',
            'fuu' => 'baa',
            'baz' => 'boom'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Different:fuu,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:fuu,baz'
            ]
        ]
    ],
    'r8i-e5f_YPoEV3ChOG_H2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1756',
        'data' => [
            'foo' => 'bar',
            'baz' => 'boom'
        ],
        'validated' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => 'Different:fuu,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'Different:fuu,baz'
            ]
        ]
    ],
    '7y5_e1Es1ygNLMMkxCygXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1766',
        'data' => [
            'lhs' => 15,
            'rhs' => 10
        ],
        'validated' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => 'numeric|gt:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ]
    ],
    '6iUmpYpYNGRE6kzqj1PKmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1772',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 10
        ],
        'validated' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => 'numeric|gt:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ]
    ],
    'DxMjxMMG-YHGNGJbc6B0OA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1775',
        'data' => [
            'lhs' => '15',
            'rhs' => 10
        ],
        'validated' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => 'numeric|gt:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ]
    ],
    'iXbH60gjPhdmZe5POZ24dA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1781',
        'data' => [
            'lhs' => 15.0
        ],
        'validated' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => 'numeric|gt:10'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ]
    ],
    '0YqpD_wpBQi5DXq-Vz3vMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1787',
        'data' => [
            'lhs' => '15'
        ],
        'validated' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => 'numeric|gt:10'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ]
    ],
    '8LWBFYH3oXyCTXapcNpzMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1790',
        'data' => [
            'lhs' => 'longer string',
            'rhs' => 'string'
        ],
        'validated' => [
            'lhs' => 'longer string'
        ],
        'rules' => [
            'lhs' => 'gt:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'gt:rhs'
            ]
        ]
    ],
    't8bb3VJ4ywVZakMrcf6b1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1803',
        'data' => [
            'lhs' => 15
        ],
        'validated' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => 'numeric|gt:10'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ]
    ],
    'UFPKhJY_Yu6cMEr9EfXnuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThan:1888',
        'data' => [
            'lhs' => [
                'string'
            ],
            'rhs' => [
                1,
                'string'
            ]
        ],
        'validated' => [
            'lhs' => [
                'string'
            ]
        ],
        'rules' => [
            'lhs' => 'lt:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'lt:rhs'
            ]
        ]
    ],
    '7BHbxixelK8d2nu0nZjfAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1905',
        'data' => [
            'lhs' => 15,
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => 'numeric|gte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ]
    ],
    'O9BmWgxh9JAWcoDKvYAmlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1911',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => 'numeric|gte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ]
    ],
    '95FXTaf_E-iiUt5yIaht0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1914',
        'data' => [
            'lhs' => '15',
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => 'numeric|gte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ]
    ],
    '34zA6deDoQdqSsfEw9-kkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1920',
        'data' => [
            'lhs' => 15.0
        ],
        'validated' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => 'numeric|gte:15'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ]
    ],
    'Qk0sTfeRsWcaA4cWcYrlvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1923',
        'data' => [
            'lhs' => '15'
        ],
        'validated' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => 'numeric|gte:15'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ]
    ],
    'yPanFoaRPiImSEIjXneyMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1926',
        'data' => [
            'lhs' => 'longer string',
            'rhs' => 'string'
        ],
        'validated' => [
            'lhs' => 'longer string'
        ],
        'rules' => [
            'lhs' => 'gte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'gte:rhs'
            ]
        ]
    ],
    'e6lR63FpfJKErrQnOI-taQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1939',
        'data' => [
            'lhs' => 15
        ],
        'validated' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => 'numeric|gte:15'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ]
    ],
    'Pi43F1jwC1urJaW7EdrVbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1946',
        'data' => [
            'lhs' => 15,
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => 'numeric|lte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ]
    ],
    'v2eDarCzu90MdlTOkEYOuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1952',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => 'numeric|lte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ]
    ],
    'Xn2di_ZIJbigqZHYwJCyPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1955',
        'data' => [
            'lhs' => '15',
            'rhs' => 15
        ],
        'validated' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => 'numeric|lte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ]
    ],
    'D6Pzr4nYMg20OiDnCo7QcQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1970',
        'data' => [
            'lhs' => [
                'string'
            ],
            'rhs' => [
                1,
                'string'
            ]
        ],
        'validated' => [
            'lhs' => [
                'string'
            ]
        ],
        'rules' => [
            'lhs' => 'lte:rhs'
        ],
        'expandedRules' => [
            'lhs' => [
                'lte:rhs'
            ]
        ]
    ],
    'AyEam_1qc6ZHacQRWRY7Uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2011',
        'data' => [
            'foo' => 'yes'
        ],
        'validated' => [
            'foo' => 'yes'
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'y-xQJfmXgNDGyr6aTVmykg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2014',
        'data' => [
            'foo' => 'on'
        ],
        'validated' => [
            'foo' => 'on'
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'iILMHibovmW82MHcZVl6lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2017',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'jH8gMC9n0xrRFQKktIAl9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2020',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'WrdKeR4oFUEKWHIsHrSLZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2023',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'U2P_5qjnjYoA4qzDU-GaOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2026',
        'data' => [
            'foo' => 'true'
        ],
        'validated' => [
            'foo' => 'true'
        ],
        'rules' => [
            'foo' => 'Accepted'
        ],
        'expandedRules' => [
            'foo' => [
                'Accepted'
            ]
        ]
    ],
    'cFTc4SUN6Iwr5PTLthwHHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2033',
        'data' => [
            'foo' => 'no',
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'required_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ]
    ],
    'AjNrcr8L4OlPXCcu0Hp1tw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2036',
        'data' => [
            'foo' => 'yes',
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'required_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ]
    ],
    'u5QvIWBicJqjNSQkoxh3_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2039',
        'data' => [
            'foo' => 'no',
            'bar' => ''
        ],
        'validated' => [
            'bar' => ''
        ],
        'rules' => [
            'bar' => 'required_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ]
    ],
    'X8tst96XbArEQ5y2cu1Fqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2070',
        'data' => [
            'foo' => 'yes',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'yes'
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'WJYwNJDC0s3aQe9rpwjeFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2073',
        'data' => [
            'foo' => 'on',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'on'
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'pQb6TCaEMihk97MoWHSbvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2076',
        'data' => [
            'foo' => '1',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'F-BxGe1Ey9E4U3Pb25WCzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2079',
        'data' => [
            'foo' => 1,
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'uy6OrUW9Cvs6lrjGNf9wkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2082',
        'data' => [
            'foo' => true,
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'ItPwWtbHASfFz8DN91YcwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2085',
        'data' => [
            'foo' => 'true',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'true'
        ],
        'rules' => [
            'foo' => 'accepted_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ]
    ],
    'doqkxICEM0Z_-0ohHcGcIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2147',
        'data' => [
            'foo' => 'no'
        ],
        'validated' => [
            'foo' => 'no'
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    'V57S3hpctwo7KEd4aIRIUA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2150',
        'data' => [
            'foo' => 'off'
        ],
        'validated' => [
            'foo' => 'off'
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    'Fj5BYpAQmDeQH4Dnil8wXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2153',
        'data' => [
            'foo' => '0'
        ],
        'validated' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    'Xa_VjR18KPpZEGjmGfFLhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2156',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    '3rM5DejNNNY7qUiiP5ZWbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2159',
        'data' => [
            'foo' => false
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    '_Ndx_vINfs2ZHcdKUtcZuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2162',
        'data' => [
            'foo' => 'false'
        ],
        'validated' => [
            'foo' => 'false'
        ],
        'rules' => [
            'foo' => 'Declined'
        ],
        'expandedRules' => [
            'foo' => [
                'Declined'
            ]
        ]
    ],
    'ICJRlxcEYMRLJQRCSh1jkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2190',
        'data' => [
            'foo' => 'no',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'no'
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    '0BAzrHNtOMVoToN_DZ9DIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2193',
        'data' => [
            'foo' => 'off',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'off'
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    'zm_EoiPMuJNGfy9cb6urQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2196',
        'data' => [
            'foo' => 0,
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    '9AGnWf1yDEyLpmhD53i2nA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2199',
        'data' => [
            'foo' => '0',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    '0O5n0cyouRiAxOBYB55iog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2202',
        'data' => [
            'foo' => false,
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    'jE0Xysd2ZNoVaalFHL7nYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2205',
        'data' => [
            'foo' => 'false',
            'bar' => 'aaa'
        ],
        'validated' => [
            'foo' => 'false'
        ],
        'rules' => [
            'foo' => 'declined_if:bar,aaa'
        ],
        'expandedRules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ]
    ],
    'wdBunTHLqvOhtRJLKdFZrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2247',
        'data' => [
            'x' => 'hello world'
        ],
        'validated' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => 'ends_with:world'
        ],
        'expandedRules' => [
            'x' => [
                'ends_with:world'
            ]
        ]
    ],
    'a3yFMb_cxO2iKZnAIBnnfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2251',
        'data' => [
            'x' => 'hello world'
        ],
        'validated' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => 'ends_with:world,hello'
        ],
        'expandedRules' => [
            'x' => [
                'ends_with:world,hello'
            ]
        ]
    ],
    'PJ8WDcIKoWHDZhboCjUpzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntEndWith:2270',
        'data' => [
            'x' => 'hello world'
        ],
        'validated' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => 'doesnt_end_with:hello'
        ],
        'expandedRules' => [
            'x' => [
                'doesnt_end_with:hello'
            ]
        ]
    ],
    '0wtMIsywyeJDCT89z-7llQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:2281',
        'data' => [
            'x' => 'hello world'
        ],
        'validated' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => 'starts_with:hello'
        ],
        'expandedRules' => [
            'x' => [
                'starts_with:hello'
            ]
        ]
    ],
    'jWo5sr-zEo8WXHxBCWhp7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:2289',
        'data' => [
            'x' => 'hello world'
        ],
        'validated' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => 'starts_with:world,hello'
        ],
        'expandedRules' => [
            'x' => [
                'starts_with:world,hello'
            ]
        ]
    ],
    'DplWiLeJcpR6uToaC4DbWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntStartWith:2308',
        'data' => [
            'x' => 'world hello'
        ],
        'validated' => [
            'x' => 'world hello'
        ],
        'rules' => [
            'x' => 'doesnt_start_with:hello'
        ],
        'expandedRules' => [
            'x' => [
                'doesnt_start_with:hello'
            ]
        ]
    ],
    'hcz_hto1ndteABDqVY0epQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateString:2319',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => 'string'
        ],
        'expandedRules' => [
            'x' => [
                'string'
            ]
        ]
    ],
    'joUHsuPlKZkLmTay7pAdmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:2334',
        'data' => [
            'foo' => '[]'
        ],
        'validated' => [
            'foo' => '[]'
        ],
        'rules' => [
            'foo' => 'json'
        ],
        'expandedRules' => [
            'foo' => [
                'json'
            ]
        ]
    ],
    'dDQbXlsinaGYHb3MRU9irQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:2338',
        'data' => [
            'foo' => '{"name":"John","age":"34"}'
        ],
        'validated' => [
            'foo' => '{"name":"John","age":"34"}'
        ],
        'rules' => [
            'foo' => 'json'
        ],
        'expandedRules' => [
            'foo' => [
                'json'
            ]
        ]
    ],
    '9aRK4xmCZxf6WMOzPCyRzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2364',
        'data' => [
            'foo' => false
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'RlPcfYRGduc3Yyf7AMbK_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2367',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'OtQETE23KRjV2XvcbzqSZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2370',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'Va6isceviRiIq83N-uzbZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2373',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'Gm6KnEQbPSA9da3N_IOLtQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2376',
        'data' => [
            'foo' => '0'
        ],
        'validated' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'aIbg0dbwpQCh2_ZL7shrTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2379',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'p_APNS0auAxEiONI_ODWRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2401',
        'data' => [
            'foo' => false
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    '1tpxoRDa8afG69pszlUe-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2404',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    'wKpn2n6-7ZWWj-aT6NSAbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2407',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    '15-qV-RcPeZ9WOqCERbJxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2410',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    'skwBxc6L4wTUVHGRXlCoSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2413',
        'data' => [
            'foo' => '0'
        ],
        'validated' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    '5OEq5QIcP7OqgbrMQOTJig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2416',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    'jjnu2MGYni1vLLTbY37h6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2426',
        'data' => [
            'foo' => '1.23'
        ],
        'validated' => [
            'foo' => '1.23'
        ],
        'rules' => [
            'foo' => 'Numeric'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric'
            ]
        ]
    ],
    'YGMGBbnOyixk59s5nLQwPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2429',
        'data' => [
            'foo' => '-1'
        ],
        'validated' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => 'Numeric'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric'
            ]
        ]
    ],
    '5EMpPwupznsd_0ZlYQgnIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2432',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Numeric'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric'
            ]
        ]
    ],
    'MrEmY58cwHO6QpEo0hgrWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:2445',
        'data' => [
            'foo' => '-1'
        ],
        'validated' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => 'Integer'
        ],
        'expandedRules' => [
            'foo' => [
                'Integer'
            ]
        ]
    ],
    'CRfTpSZLsWMDH_XsJilBKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:2448',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Integer'
        ],
        'expandedRules' => [
            'foo' => [
                'Integer'
            ]
        ]
    ],
    'cIzFj41llsQEK5HMmv9RZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:2461',
        'data' => [
            'foo' => '1.234'
        ],
        'validated' => [
            'foo' => '1.234'
        ],
        'rules' => [
            'foo' => 'Decimal:2,3'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:2,3'
            ]
        ]
    ],
    'sl3kKaf7FRLbzUyYKab4tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:2510',
        'data' => [
            'foo' => '-1'
        ],
        'validated' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => 'Int'
        ],
        'expandedRules' => [
            'foo' => [
                'Int'
            ]
        ]
    ],
    'Ym_hcIHlY2X250MBmStoyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:2513',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Int'
        ],
        'expandedRules' => [
            'foo' => [
                'Int'
            ]
        ]
    ],
    '1gvai6VaQ93mH2mlQG2R0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2520',
        'data' => [
            'foo' => '12345'
        ],
        'validated' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => 'Digits:5'
        ],
        'expandedRules' => [
            'foo' => [
                'Digits:5'
            ]
        ]
    ],
    'XfBKZwGEW9V_644FfMiSTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2533',
        'data' => [
            'foo' => '12345'
        ],
        'validated' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => 'digits_between:1,6'
        ],
        'expandedRules' => [
            'foo' => [
                'digits_between:1,6'
            ]
        ]
    ],
    'qaOvJPEdoPKTZ4p4DB6V8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2546',
        'data' => [
            'foo' => '12345'
        ],
        'validated' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => 'min_digits:1'
        ],
        'expandedRules' => [
            'foo' => [
                'min_digits:1'
            ]
        ]
    ],
    'QJCx8H8HFOs5sQw5I7e4_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2559',
        'data' => [
            'foo' => '12345'
        ],
        'validated' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => 'max_digits:6'
        ],
        'expandedRules' => [
            'foo' => [
                'max_digits:6'
            ]
        ]
    ],
    '1_eEgVDPPwDHhdiFgPwW0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2578',
        'data' => [
            'foo' => 'anc'
        ],
        'validated' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => 'Size:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Size:3'
            ]
        ]
    ],
    'zFprE-6zTOi0O6fBPv6BgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2584',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Numeric|Size:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Size:3'
            ]
        ]
    ],
    '-IJNzbqFgl2DwNjE6uUIBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2587',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => 'Array|Size:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Array',
                'Size:3'
            ]
        ]
    ],
    'TjP6AmLQUPk51abSmioAHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2610',
        'data' => [
            'foo' => 'anc'
        ],
        'validated' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => 'Between:3,5'
        ],
        'expandedRules' => [
            'foo' => [
                'Between:3,5'
            ]
        ]
    ],
    'r2xnOrfj6rzXQP8kU3J9BA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2613',
        'data' => [
            'foo' => 'ancf'
        ],
        'validated' => [
            'foo' => 'ancf'
        ],
        'rules' => [
            'foo' => 'Between:3,5'
        ],
        'expandedRules' => [
            'foo' => [
                'Between:3,5'
            ]
        ]
    ],
    '2keS8WAYM9CWHpmRgH3c6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2616',
        'data' => [
            'foo' => 'ancfs'
        ],
        'validated' => [
            'foo' => 'ancfs'
        ],
        'rules' => [
            'foo' => 'Between:3,5'
        ],
        'expandedRules' => [
            'foo' => [
                'Between:3,5'
            ]
        ]
    ],
    'y-6ItuY6s0RA8eo9P9neNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2623',
        'data' => [
            'foo' => '123'
        ],
        'validated' => [
            'foo' => '123'
        ],
        'rules' => [
            'foo' => 'Numeric|Between:123,200'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Between:123,200'
            ]
        ]
    ],
    'mxLo07FEWsifQ_YDENrosw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2627',
        'data' => [
            'foo' => '123'
        ],
        'validated' => [
            'foo' => '123'
        ],
        'rules' => [
            'foo' => 'Numeric|Between:0,123'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Between:0,123'
            ]
        ]
    ],
    'K93IigWsxyliMm-oyG6ZXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2631',
        'data' => [
            'foo' => '0.02'
        ],
        'validated' => [
            'foo' => '0.02'
        ],
        'rules' => [
            'foo' => 'Numeric|Between:0.01,0.02'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Between:0.01,0.02'
            ]
        ]
    ],
    'ER6Seax78SPe-46ZfxyMDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2634',
        'data' => [
            'foo' => '0.02'
        ],
        'validated' => [
            'foo' => '0.02'
        ],
        'rules' => [
            'foo' => 'Numeric|Between:0.01,0.03'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Between:0.01,0.03'
            ]
        ]
    ],
    '_pLElweyZV9kcNRhFWe6Kg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2640',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Numeric|Between:1,5'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Between:1,5'
            ]
        ]
    ],
    'ku1J188S9Cr2xE7brQ-ukw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2643',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => 'Array|Between:1,5'
        ],
        'expandedRules' => [
            'foo' => [
                'Array',
                'Between:1,5'
            ]
        ]
    ],
    'Ip29OqiBG2jVSWBwzznYxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2667',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Numeric|Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Min:3'
            ]
        ]
    ],
    'TMSnqU7WZa_IGXKtbDTlSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2670',
        'data' => [
            'foo' => 'anc'
        ],
        'validated' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => 'Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Min:3'
            ]
        ]
    ],
    'XP3zIOeNxEag5bvSw0Fz2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2681',
        'data' => [
            'foo' => '2.001'
        ],
        'validated' => [
            'foo' => '2.001'
        ],
        'rules' => [
            'foo' => 'Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Min:3'
            ]
        ]
    ],
    'NhJaKImNBShKF88fYxs0Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2688',
        'data' => [
            'foo' => '5'
        ],
        'validated' => [
            'foo' => '5'
        ],
        'rules' => [
            'foo' => 'Numeric|Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Min:3'
            ]
        ]
    ],
    'DTP7-sCKgBBPsMstTpqPqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2691',
        'data' => [
            'foo' => [
                1,
                2,
                3,
                4
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3,
                4
            ]
        ],
        'rules' => [
            'foo' => 'Array|Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Array',
                'Min:3'
            ]
        ]
    ],
    'nvM3i7n7dzYXfQ_X0pkUdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2714',
        'data' => [
            'foo' => 'anc'
        ],
        'validated' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => 'Max:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Max:3'
            ]
        ]
    ],
    'sP79tL_Y0eqHK8vLOFid4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2721',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Numeric|Max:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Max:3'
            ]
        ]
    ],
    'ZPpl0q6EuwMN5WFPQcgxXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2725',
        'data' => [
            'foo' => '2.001'
        ],
        'validated' => [
            'foo' => '2.001'
        ],
        'rules' => [
            'foo' => 'Numeric|Max:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Max:3'
            ]
        ]
    ],
    'hNPHeQOHa5wVSNJFg1_gGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2732',
        'data' => [
            'foo' => '22'
        ],
        'validated' => [
            'foo' => '22'
        ],
        'rules' => [
            'foo' => 'Numeric|Max:33'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric',
                'Max:33'
            ]
        ]
    ],
    'UrGpXzh-uUcNguXdsxDp2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2735',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => 'Array|Max:4'
        ],
        'expandedRules' => [
            'foo' => [
                'Array',
                'Max:4'
            ]
        ]
    ],
    'W_dglD1EWwnjgK20NSUuww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'e5JprgXuTtjo2j2lGRmMSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    '54vy1MHC9qH4jLJbW6wgGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:10.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.1'
            ]
        ]
    ],
    'CyeTrwQW1Yz07meYglfhYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:10.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.1'
            ]
        ]
    ],
    'qj4WTMjxkv_O7i8Atvjwdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'evp9p0rFS8dYT4Vr88pCXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'mT9Wu5qnqxvr4tNlYDF4eA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.1'
            ]
        ]
    ],
    '112WcvxY6gXV3CgKEaI9CQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'validated' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.1'
            ]
        ]
    ],
    'iQoD3uGvd0ld3sssmjNV4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'NwNoXzhKsTO4EP1bEJiTZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'gGDAzgobLPRMsoheUE4yMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ]
    ],
    'AMXhRNHRR-br-vRFfZDsYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ]
    ],
    'avu68lYWJHTWqpxwzDqwUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20
        ],
        'validated' => [
            'foo' => 20
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'pr4UBfPHAj-CXEnMzN8Gpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20
        ],
        'validated' => [
            'foo' => 20
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'Kaf-_VWvNrsxFKG0yRSzJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:-5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ]
    ],
    'ZEX41vz_HTrohc8xHcuehA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'validated' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => 'multiple_of:-5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ]
    ],
    'zU7s18f6K6Z_SDz5vh7tkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20
        ],
        'validated' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'Rcl9o43TigfmpIFVG-ymYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20
        ],
        'validated' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'avH82WesJBAtp979Q3kjhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    '9TsysaMooH_1EVBXcNjaBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'U2MTYkc0U5ZuvkLkLyW82g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:-5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ]
    ],
    'eNAUWySfF_iS9wWFyWTwbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:-5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ]
    ],
    'byQhFzSofailBNmWBNyj1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20
        ],
        'validated' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'XBvQXokQP4WbKzhSHQRX3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20
        ],
        'validated' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'M0MRP59E2sgql4KOuRWi4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20.0
        ],
        'validated' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'MkL0XWK-c0y6BiUMFRwFcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20.0
        ],
        'validated' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    '_UyxnpJBbdArc9Vr7VGmXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.0
        ],
        'validated' => [
            'foo' => 10.0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    '41lBdtekG8Y8uyfuleCteg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.0
        ],
        'validated' => [
            'foo' => 10.0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    '2PmdLt6AzhgqcX7jwnLjlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20.0
        ],
        'validated' => [
            'foo' => -20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'ZRWpH1dtim48Tg_7JSwvhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20.0
        ],
        'validated' => [
            'foo' => -20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ]
    ],
    'wMAFvO1JX2Et7NVFsDGpyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ]
    ],
    'fJwgznH4Z5zmVi55Q8N6rw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'validated' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => 'multiple_of:5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ]
    ],
    'y71GqUTGnDOeyWE3giOqQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20.0
        ],
        'validated' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'AnbnUYEyIKWRePxJ_lS9Wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20.0
        ],
        'validated' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => 'multiple_of:-10'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ]
    ],
    'aSKRUo5uOtWveu8UaOSH1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    'pts8lEcVliol-plyZrkpbg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    '0S1sKxp8k_vZGB3MFuxfzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.5'
            ]
        ]
    ],
    'm6MyKti9xC1ZM9v9aguGOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.5'
            ]
        ]
    ],
    'Tepia_OkCORYT_tsh2KKDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ]
    ],
    '36eT_qASSniDQtEJIz-rYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ]
    ],
    '28eT1Ew7YgMlGbn1DK1pdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 31.5
        ],
        'validated' => [
            'foo' => 31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    'BBDRR1K9XQ5skqKjaK146Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 31.5
        ],
        'validated' => [
            'foo' => 31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    'pf_bPfoFk1Jc7s2NZRXtVw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ]
    ],
    'AoL7r5ayA-zt4G-Rm0Sbkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ]
    ],
    'tSvJByCLLi7ZWDR7ItW61A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ]
    ],
    'Ld7siktvPxAPTLMHWfOGXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'validated' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ]
    ],
    'j5RQzgGHTnJq0GO2VLQG0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -31.5
        ],
        'validated' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    'D0v6Pbh7iAsbnVeHHyIWhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -31.5
        ],
        'validated' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ]
    ],
    'B21ijLLPq06CxCxSV2831g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ]
    ],
    '-zrX3_AT_a96H1goroY36g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ]
    ],
    '00SpCY9b6WP6S_GdchDvxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ]
    ],
    'EAxuxJ2HSHxU_F_iEsnekQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ]
    ],
    'wSn_rvO7vgr1eiSjWYyFZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ]
    ],
    'g95C6Ua9tjV0JFwYUNL6sg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'validated' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ]
    ],
    'eQYq6thWK083pb6gD_ernw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -31.5
        ],
        'validated' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ]
    ],
    'PFeaBm5Q8ATlEMxwUudMWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -31.5
        ],
        'validated' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => 'multiple_of:-10.5'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ]
    ],
    'px12rgKVva4yWb9S-GY6SA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 2
        ],
        'validated' => [
            'foo' => 2
        ],
        'rules' => [
            'foo' => 'multiple_of:0.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.1'
            ]
        ]
    ],
    'mnwiINP2HgWq55Tnwn02Hg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 2
        ],
        'validated' => [
            'foo' => 2
        ],
        'rules' => [
            'foo' => 'multiple_of:0.1'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.1'
            ]
        ]
    ],
    'OrZ57OXPepx93DsUsmQ0Xw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0.75
        ],
        'validated' => [
            'foo' => 0.75
        ],
        'rules' => [
            'foo' => 'multiple_of:0.05'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.05'
            ]
        ]
    ],
    'vcyxwC3cQAFs46_W0nK4iQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0.75
        ],
        'validated' => [
            'foo' => 0.75
        ],
        'rules' => [
            'foo' => 'multiple_of:0.05'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.05'
            ]
        ]
    ],
    'aW3TE4pYKoTLjqQ2rPK2SA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0.9
        ],
        'validated' => [
            'foo' => 0.9
        ],
        'rules' => [
            'foo' => 'multiple_of:0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ]
    ],
    'aabvhc-LBIWtuTy83-ZW5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0.9
        ],
        'validated' => [
            'foo' => 0.9
        ],
        'rules' => [
            'foo' => 'multiple_of:0.3'
        ],
        'expandedRules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ]
    ],
    'h-1tf57Q5a-xHryrloZOFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3048',
        'data' => [
            'name' => 'foo'
        ],
        'validated' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => 'In:foo,baz'
        ],
        'expandedRules' => [
            'name' => [
                'In:foo,baz'
            ]
        ]
    ],
    'Hp6evQabxVJWW8m5IpIyqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3054',
        'data' => [
            'name' => [
                'foo',
                'qux'
            ]
        ],
        'validated' => [
            'name' => [
                'foo',
                'qux'
            ]
        ],
        'rules' => [
            'name' => 'Array|In:foo,baz,qux'
        ],
        'expandedRules' => [
            'name' => [
                'Array',
                'In:foo,baz,qux'
            ]
        ]
    ],
    '-Fqy3SNtPw4uxiBudXAGyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3057',
        'data' => [
            'name' => [
                'foo,bar',
                'qux'
            ]
        ],
        'validated' => [
            'name' => [
                'foo,bar',
                'qux'
            ]
        ],
        'rules' => [
            'name' => 'Array|In:"foo,bar",baz,qux'
        ],
        'expandedRules' => [
            'name' => [
                'Array',
                'In:"foo,bar",baz,qux'
            ]
        ]
    ],
    'mlOdYIxzADLr6mdMuw44Rw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3060',
        'data' => [
            'name' => 'f"o"o'
        ],
        'validated' => [
            'name' => 'f"o"o'
        ],
        'rules' => [
            'name' => 'In:"f""o""o",baz,qux'
        ],
        'expandedRules' => [
            'name' => [
                'In:"f""o""o",baz,qux'
            ]
        ]
    ],
    'ehPWG6FtLQB6-jJqKEvynQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3063',
        'data' => [
            'name' => 'a,b
c,d'
        ],
        'validated' => [
            'name' => 'a,b
c,d'
        ],
        'rules' => [
            'name' => 'in:"a,b
c,d"'
        ],
        'expandedRules' => [
            'name' => [
                'in:"a,b
c,d"'
            ]
        ]
    ],
    'BfSiQu1vajDlHQdXAvem4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotIn:3076',
        'data' => [
            'name' => 'foo'
        ],
        'validated' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => 'NotIn:bar,baz'
        ],
        'expandedRules' => [
            'name' => [
                'NotIn:bar,baz'
            ]
        ]
    ],
    'cWZo8nMLiahU24Kfkhq8Zg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3099',
        'data' => [
            'foo' => [
                '1',
                '11'
            ]
        ],
        'validated' => [
            'foo' => [
                '1',
                '11'
            ]
        ],
        'rules' => [
            'foo.*' => 'distinct:ignore_case'
        ],
        'expandedRules' => [
            'foo.0' => [
                'distinct:ignore_case'
            ],
            'foo.1' => [
                'distinct:ignore_case'
            ]
        ]
    ],
    'UhAOyHoSCCUuNAcxhjqxdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3102',
        'data' => [
            'foo' => [
                'foo',
                'bar'
            ]
        ],
        'validated' => [
            'foo' => [
                'foo',
                'bar'
            ]
        ],
        'rules' => [
            'foo.*' => 'distinct'
        ],
        'expandedRules' => [
            'foo.0' => [
                'distinct'
            ],
            'foo.1' => [
                'distinct'
            ]
        ]
    ],
    'qGhScVSIl00Yy-rek0YF8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3108',
        'data' => [
            'foo' => [
                'bar' => [
                    'id' => 'qux'
                ],
                'baz' => [
                    'id' => 'QUX'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                'bar' => [
                    'id' => 'qux'
                ],
                'baz' => [
                    'id' => 'QUX'
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'foo.bar.id' => [
                'distinct'
            ],
            'foo.baz.id' => [
                'distinct'
            ]
        ]
    ],
    '2yVBaq4adSCIUmDtPF55Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3114',
        'data' => [
            'foo' => [
                'bar' => [
                    'id' => 1
                ],
                'baz' => [
                    'id' => 2
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                'bar' => [
                    'id' => 1
                ],
                'baz' => [
                    'id' => 2
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'foo.bar.id' => [
                'distinct'
            ],
            'foo.baz.id' => [
                'distinct'
            ]
        ]
    ],
    'WdWJh2DoQw2LGyZvpzgWoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3117',
        'data' => [
            'foo' => [
                'bar' => [
                    'id' => 2
                ],
                'baz' => [
                    'id' => 425
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                'bar' => [
                    'id' => 2
                ],
                'baz' => [
                    'id' => 425
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'distinct:ignore_case'
        ],
        'expandedRules' => [
            'foo.bar.id' => [
                'distinct:ignore_case'
            ],
            'foo.baz.id' => [
                'distinct:ignore_case'
            ]
        ]
    ],
    'BXuFLYawnAMi0TKzlNx-6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3120',
        'data' => [
            'foo' => [
                [
                    'id' => 1,
                    'nested' => [
                        'id' => 1
                    ]
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'distinct'
            ]
        ]
    ],
    'vQx7Yy34RjK118JV7_QeZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3126',
        'data' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => 2
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ],
                [
                    'id' => 2
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'distinct'
            ],
            'foo.1.id' => [
                'distinct'
            ]
        ]
    ],
    'yHFSmQjwqOUC8--j8Hdn7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3132',
        'data' => [
            'cat' => [
                [
                    'prod' => [
                        [
                            'id' => 1
                        ]
                    ]
                ],
                [
                    'prod' => [
                        [
                            'id' => 2
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'cat' => [
                [
                    'prod' => [
                        [
                            'id' => 1
                        ]
                    ]
                ],
                [
                    'prod' => [
                        [
                            'id' => 2
                        ]
                    ]
                ]
            ]
        ],
        'rules' => [
            'cat.*.prod.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'cat.0.prod.0.id' => [
                'distinct'
            ],
            'cat.1.prod.0.id' => [
                'distinct'
            ]
        ]
    ],
    '5NXt5_nS3DyQP8paQnhMEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3135',
        'data' => [
            'cat' => [
                'sub' => [
                    [
                        'prod' => [
                            [
                                'id' => 1
                            ]
                        ]
                    ],
                    [
                        'prod' => [
                            [
                                'id' => 2
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'cat' => [
                'sub' => [
                    [
                        'prod' => [
                            [
                                'id' => 1
                            ]
                        ]
                    ],
                    [
                        'prod' => [
                            [
                                'id' => 2
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'rules' => [
            'cat.sub.*.prod.*.id' => 'distinct'
        ],
        'expandedRules' => [
            'cat.sub.0.prod.0.id' => [
                'distinct'
            ],
            'cat.sub.1.prod.0.id' => [
                'distinct'
            ]
        ]
    ],
    'CFuTmAvb9mZ8L5E1SCR1rg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3149',
        'data' => [
            'foo' => [
                'foo',
                'bar'
            ],
            'bar' => [
                'foo',
                'bar'
            ]
        ],
        'validated' => [
            'foo' => [
                'foo',
                'bar'
            ],
            'bar' => [
                'foo',
                'bar'
            ]
        ],
        'rules' => [
            'foo.*' => 'distinct',
            'bar.*' => 'distinct'
        ],
        'expandedRules' => [
            'foo.0' => [
                'distinct'
            ],
            'foo.1' => [
                'distinct'
            ],
            'bar.0' => [
                'distinct'
            ],
            'bar.1' => [
                'distinct'
            ]
        ]
    ],
    'PKtcP-gnfVpn7DrAG1lrKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3164',
        'data' => [
            'foo' => [
                '0100',
                '100'
            ]
        ],
        'validated' => [
            'foo' => [
                '0100',
                '100'
            ]
        ],
        'rules' => [
            'foo.*' => 'distinct:strict'
        ],
        'expandedRules' => [
            'foo.0' => [
                'distinct:strict'
            ],
            'foo.1' => [
                'distinct:strict'
            ]
        ]
    ],
    'Dc74CQGaNkpRLoCtRluCcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:3198',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'Unique:users'
        ],
        'expandedRules' => [
            'email' => [
                'Unique:users'
            ]
        ]
    ],
    'GsybUJyZ2qAy-FRTaJQapQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:3205',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'Unique:connection.users'
        ],
        'expandedRules' => [
            'email' => [
                'Unique:connection.users'
            ]
        ]
    ],
    'J6MWhCiP79DH_02PPcoZrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUniqueAndExistsSendsCorrectFieldNameToDBWithArrays:3249',
        'data' => [
            [
                'email' => 'foo',
                'type' => 'bar'
            ]
        ],
        'validated' => [
            [
                'email' => 'foo',
                'type' => 'bar'
            ]
        ],
        'rules' => [
            '*.email' => 'unique:users',
            '*.type' => 'exists:user_types'
        ],
        'expandedRules' => [
            '0.email' => [
                'unique:users'
            ],
            '0.type' => [
                'exists:user_types'
            ]
        ]
    ],
    'kYf-RvuNqagzcmqlV-G_XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3275',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'Exists:users'
        ],
        'expandedRules' => [
            'email' => [
                'Exists:users'
            ]
        ]
    ],
    'ROEakCHWfUYZLO2ZNw-U1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3283',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'Exists:users,email,account_id,1,name,taylor'
        ],
        'expandedRules' => [
            'email' => [
                'Exists:users,email,account_id,1,name,taylor'
            ]
        ]
    ],
    'hza2EDvgapwTBNFBJLmarw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3304',
        'data' => [
            'email' => 'foo'
        ],
        'validated' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => 'Exists:connection.users'
        ],
        'expandedRules' => [
            'email' => [
                'Exists:connection.users'
            ]
        ]
    ],
    'DZFWX2SRECNwYhAJ6ieKcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3311',
        'data' => [
            'email' => [
                'foo',
                'foo'
            ]
        ],
        'validated' => [
            'email' => [
                'foo',
                'foo'
            ]
        ],
        'rules' => [
            'email' => 'exists:users,email_addr'
        ],
        'expandedRules' => [
            'email' => [
                'exists:users,email_addr'
            ]
        ]
    ],
    'pdMAVxs5Z9gHkrC3geCdfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExistsIsNotCalledUnnecessarily:3329',
        'data' => [
            'id' => '1'
        ],
        'validated' => [
            'id' => '1'
        ],
        'rules' => [
            'id' => 'Integer|Exists:users,id'
        ],
        'expandedRules' => [
            'id' => [
                'Integer',
                'Exists:users,id'
            ]
        ]
    ],
    'GRnHyL7Ulh3kcJTgj75PsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3339',
        'data' => [
            'ip' => '127.0.0.1'
        ],
        'validated' => [
            'ip' => '127.0.0.1'
        ],
        'rules' => [
            'ip' => 'Ip'
        ],
        'expandedRules' => [
            'ip' => [
                'Ip'
            ]
        ]
    ],
    'RdQWwXK-eCqD7WtRCnvHKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3342',
        'data' => [
            'ip' => '127.0.0.1'
        ],
        'validated' => [
            'ip' => '127.0.0.1'
        ],
        'rules' => [
            'ip' => 'Ipv4'
        ],
        'expandedRules' => [
            'ip' => [
                'Ipv4'
            ]
        ]
    ],
    '9oJND4MLJ4mKmIdaAqNJNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3345',
        'data' => [
            'ip' => '::1'
        ],
        'validated' => [
            'ip' => '::1'
        ],
        'rules' => [
            'ip' => 'Ipv6'
        ],
        'expandedRules' => [
            'ip' => [
                'Ipv6'
            ]
        ]
    ],
    'GGRNzhBxWrYdgE4UJrqowA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3362',
        'data' => [
            'mac' => '01-23-45-67-89-ab'
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-ab'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    '2KdRURWKb4YHws-dh3RU8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3366',
        'data' => [
            'mac' => '01-23-45-67-89-AB'
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-AB'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    'H9kGGsyZuvvHF_wHM3sj4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3370',
        'data' => [
            'mac' => '01-23-45-67-89-aB'
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-aB'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    '-rdeWiPg-cxrR_SVq2VIjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3374',
        'data' => [
            'mac' => '01:23:45:67:89:ab'
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:ab'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    'xt6ySByaZe15LoxRiEaXlA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3378',
        'data' => [
            'mac' => '01:23:45:67:89:AB'
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:AB'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    '91AtbegcPjDBLB4k3_ff_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3382',
        'data' => [
            'mac' => '01:23:45:67:89:aB'
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:aB'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    'WRH4tQCSdXOtGLZ_yg3pcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3394',
        'data' => [
            'mac' => '0123.4567.89ab'
        ],
        'validated' => [
            'mac' => '0123.4567.89ab'
        ],
        'rules' => [
            'mac' => 'mac_address'
        ],
        'expandedRules' => [
            'mac' => [
                'mac_address'
            ]
        ]
    ],
    'esebjHh8FfWGamg7bdc7eA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmail:3429',
        'data' => [
            'x' => 'foo@gmail.com'
        ],
        'validated' => [
            'x' => 'foo@gmail.com'
        ],
        'rules' => [
            'x' => 'Email'
        ],
        'expandedRules' => [
            'x' => [
                'Email'
            ]
        ]
    ],
    'mkqH8aBOeOatEqWYCMmT0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithInternationalCharacters:3435',
        'data' => [
            'x' => 'foo@gmäil.com'
        ],
        'validated' => [
            'x' => 'foo@gmäil.com'
        ],
        'rules' => [
            'x' => 'email'
        ],
        'expandedRules' => [
            'x' => [
                'email'
            ]
        ]
    ],
    'WuFPyR0SZHFCnm666Oi2ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterCheck:3450',
        'data' => [
            'x' => 'example@example.com'
        ],
        'validated' => [
            'x' => 'example@example.com'
        ],
        'rules' => [
            'x' => 'email:filter'
        ],
        'expandedRules' => [
            'x' => [
                'email:filter'
            ]
        ]
    ],
    'QxqJtXYKCcZIfrKgzvVLLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:3466',
        'data' => [
            'x' => 'example@example.com'
        ],
        'validated' => [
            'x' => 'example@example.com'
        ],
        'rules' => [
            'x' => 'email:filter_unicode'
        ],
        'expandedRules' => [
            'x' => [
                'email:filter_unicode'
            ]
        ]
    ],
    'Rh8Bcv0NsWhzTSurnxVvMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:3470',
        'data' => [
            'x' => 'exämple@example.com'
        ],
        'validated' => [
            'x' => 'exämple@example.com'
        ],
        'rules' => [
            'x' => 'email:filter_unicode'
        ],
        'expandedRules' => [
            'x' => [
                'email:filter_unicode'
            ]
        ]
    ],
    'hGBb_X8a5xQep1QJ4ftIzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aaa://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'aaa://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '9ZdQyC0M8ZX-BvN4brIsRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aaas://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'aaas://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'C1v5m6RtH0cObkzHDDvVHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'about://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'about://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ANwc2LD_Q7M7QvRc-RCGeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'acap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'HcIxhnrST3g8p3ursd7vxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acct://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'acct://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'QE6sHpJYloetqD2CoxNhng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acr://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'acr://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'CYi-qLnxXHeAvriSqSjlBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'adiumxtra://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'adiumxtra://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'E4p5RV-BleQuZ_-wKNkGfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'afp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'afp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'VOCl7BC_-NA1p-KD6OHTkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'afs://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'afs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '13FJKDb_kfNEncGPRNMEmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aim://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'aim://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'jO3ohe0PgoOM8GKbH2ssng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'apt://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'apt://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'XcBa3XuNIDdDT6lCMYyrjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'attachment://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'attachment://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Q366lA35mgpfz33dc9qPDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aw://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'aw://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    're_cFMUYYEh7ZNCxDcdESA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'barion://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'barion://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'fhlfiV_Dl7DAhUbRWlqcLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'beshare://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'beshare://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ck0TKOsYb52isRfFJuGIpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'bitcoin://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'bitcoin://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'W38vOIcM_0YIft6caGHFzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'blob://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'blob://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'DnKRcA_my-bFSi2Y4IQ4hQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'bolo://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'bolo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'uHlmtpuc1AQl0EGDRWmQkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'callto://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'callto://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'WREQ9_D9U8sOkx6lUt5GQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'cap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'U9LVuo6zGjhlDoyrpfHrfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'chrome://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'chrome://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'rxq5fX2q7iNZIEeqL_H2jQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'chrome-extension://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'chrome-extension://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'UXldBS0_PZQrg-BWJ0I-lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'cid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'WwBr5ihpwjiDWzTLOON3EA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'coap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'coap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'GdQT7e1IScson-9Mg80XNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'coaps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'coaps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'W32kkfHa6WeXYgsu4h2GDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'com-eventbrite-attendee://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'com-eventbrite-attendee://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'mtOQgSJBn7ZkJ4uFB0lGjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'content://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'content://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'a26n6k15gL0svW3O8MO14A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'crid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'crid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '4awx6JCFN_EilxqEvT4Qzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cvs://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'cvs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'YRBmiBZ_r0jyd8N42sQF1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'data://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'data://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'pY9gEU0Xtsta_PgkvaDMOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dav://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dav://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'XnO6vfQ5P-QYZ1iPhZgyEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dict://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dict://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'EQpkEOOpQIs0grlR3VM3ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dlna-playcontainer://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dlna-playcontainer://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'YFbwdJqfi5R17BU3cVBvWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dlna-playsingle://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dlna-playsingle://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'iQxl4XeBhrS8OHX_QwGOlQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dns://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'saYrtKvqucLGjHokHB8SXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dntp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dntp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'MiE7sz2bg7pdh4Dd0HutcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dtn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dtn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    't2FfM7PgmeXvScs9pdiBsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dvb://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'dvb://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'e2c7kKK0K-4cSPpavmQlSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ed2k://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ed2k://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'S9PLQc73075A1h4_mPHBXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'example://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'example://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ryCPTfyYmtOrenXDrlrcDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'facetime://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'facetime://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'BEfmRT9Ljmf3I429Oy5aUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'fax://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'fax://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'GsrRXOKQv7scy6qqrUSCIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'feed://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'feed://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'a2Wabd-5SRflDsd4zs-P9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'feedready://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'feedready://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'CLf-pb0kXjPNjrjY0fsCyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'file://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'file://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'NHOMtU15YFtRGJPbW9Udhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'filesystem://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'filesystem://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'R--xZYr68lLmU2Oj-sxGnw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'finger://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'finger://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Y27IKeKfnXgbxkPn1eOyIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'fish://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'fish://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'qmG7LFCJpt3fnQ4tVLRmfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ftp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'dXlp-2pwpoVlzbhu5PQdPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'geo://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'geo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'GIhvTY8EYaLPzRQFHrks-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gg://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'gg://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Szm7URgmhDYDjEBAS-IITA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'git://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'git://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'eF2qI3JIP_AW6iljfw-Gfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gizmoproject://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'gizmoproject://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Nucd96WwgyzqYaW8RfDxQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'go://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'go://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '2r_K-X7wc7tjXhnAVcCDTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gopher://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'gopher://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '3JJFYGKZAuJ3YH_D9OE9og' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gtalk://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'gtalk://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'uOOCOYxbuedm5sMVubHqoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'h323://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'h323://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'RXIuwpGd6DOtxWy4vp5dQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ham://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ham://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'FU6nv9DZfh4kSrmpZHlomQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'hcp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'hcp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'HYBh-xk9cEzJtUEPWK2ySA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'http://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '3q4lGEYChMvrtCVjU90l2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'https://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'W-85aNwUBbnyv188G0C8bA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iax://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iax://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'HTpO1-NGkDQbX80sVhWmQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'icap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'icap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'qBflOcEfaCkS_5kY3a9ryg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'icon://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'icon://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'mV6VoELR6qMmLtsqzSu2Mg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'im://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'im://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'rRn9GAovEtLTpqtJPR6RHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'imap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'imap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'w0Q4soj0_zUhVmi--V4wow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'info://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'info://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Sg0Wjc83iJpG1ha9ByiO1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iotdisco://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iotdisco://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'YzSo03J-I85sTng-pXYuxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ipn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'VSGZ6Sd81wVOrRmGNSGHhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ipp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'oLCLnZDKexaxiKTXWEoQMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ipps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'qrP_JzB7J84oDFomFfF1wA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'irc://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'irc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'mnsUOJl8H7ozQAPuG98-Vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'irc6://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'irc6://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'QERlJ23rYUQ5GQFM1aQttw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ircs://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ircs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '-6et0r3Bt77SqOOLIXnvyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iris://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'mCc9o3zlrlKiZN3uvJVZ2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.beep://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iris.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '-BmOSL-k-iVlBDtqTP6Thg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.lwz://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iris.lwz://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'VM0CAVOLAsF0ikJFEbrEiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.xpc://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iris.xpc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'vI79fas9dqCb3XdtCM9txA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.xpcs://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'iris.xpcs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'bWNjgTGqK_evL_iAvkO5dQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'itms://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'itms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'OcgMx8hm3LdJViwAiCey6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jabber://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'jabber://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'diI6gaNbG1MjbOmqksCriA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jar://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'jar://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'J8MaDXo85eILqeuv8ApDOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jms://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'jms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'bO3SD8gs8qTE_lZSxl9Vtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'keyparc://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'keyparc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '6K_dG20rYRYJcjQjKhSBOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'lastfm://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'lastfm://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '-3HuhckOIYDGumVuYSjszg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ldap://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ldap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'sL8GLS6xuatE--oRrmXacw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ldaps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ldaps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'V9OlesM8QhdJHkPQBNDAzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'magnet://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'magnet://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'C_B2XrxrHwb_X5zUe698YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mailserver://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mailserver://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'P2ohZ4A7NnyjQOJNpl_Oqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mailto://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mailto://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '70kikfkeGhkuyLp2-hc1qg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'maps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'maps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'bZb-uhLgvhGw_eU87rc7Gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'market://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'market://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'xnAnpYM_guSvdf9V4EK8-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'message://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'message://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'gKbogMs-tI5W53eBPkek2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'G0wJ1QhCCtWWOjHM7XvFkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mms://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'shLnFfYY_lBigt_ZzpKeZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'modem://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'modem://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'MoGxsrAs5ZYdp0-Jjv9plg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-help://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-help://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '9Y51gem_1fivDvEzYHH8aw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'fkaJcO5vCJcgg80HnlZcyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-airplanemode://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-airplanemode://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ApFgUC1JOxDxgmDxv8Ci7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-bluetooth://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-bluetooth://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'klr2lkEthZzi2_NKjdPJnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-camera://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-camera://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Pxp9VXBkP-lTDj0ydFDqTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-cellular://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-cellular://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'f0FgLjGrAkL9sEvug6qF2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-cloudstorage://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-cloudstorage://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'JRM4HtstBcGhxqDSvzDbWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-emailandaccounts://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-emailandaccounts://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'k544gpky_vUysGtqIiZPCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-language://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-language://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'RQpftieo8UXQXylWzvlA5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-location://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-location://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Bi7Vfam3b0QB2YsVRYrmrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-lock://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-lock://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'SwfgCWyxS3u6NC9GsNJtTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-nfctransactions://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-nfctransactions://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'PCO1rxKKyxFf-hI5XtAMQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-notifications://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-notifications://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'lzA35LJ13XUV67nbT13omA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-power://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-power://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '6vHZ0r4RXLleGWVERS7_iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-privacy://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-privacy://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'QiNmU0cNiNbfr9p6yAl57w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-proximity://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-proximity://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'LurL3NFxe1hCNobwDTXj9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-screenrotation://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-screenrotation://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'CjiAAqINeF46WHHwhM1aLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-wifi://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-wifi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'zNeEVY3bHZbcQfE_rnS8UA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-workplace://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ms-settings-workplace://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'PXO67uzJWuyGs2d3K0kGRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msnim://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'msnim://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'N67zQB7QqcVL0-K5oM4Flw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msrp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'msrp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'dDAWKwvTT1_vMLhycMUyOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msrps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'msrps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'shW3fTOkr489WZKcJ_Q2GQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mtqp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mtqp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'RAT3u4755ZILt_D0zY3osQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mumble://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mumble://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ttDDOhiPtQ59HSKEjKatmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mupdate://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mupdate://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'GCSrjkX15abUYH-KIr0G3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mvn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'mvn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'aum5QyY9KPkZIC5T5hVPlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'news://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'news://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'UsuozlJw-2jA2M-RMn8_PQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nfs://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'nfs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'WZG_yulW4CP1HsHG_R-KHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ni://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ni://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'VkC4K0a-4TZcdjOuVR-b1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nih://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'nih://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'zIcrPf1ucajuR7WmOAfskQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nntp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'nntp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ez27-vKKtFKGDVIoBCv5tA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'notes://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'notes://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'aXDdFnYmn68aUwD4zOKH0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'oid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'oid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'FPouluVEWHz610R7cLaNpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'opaquelocktoken://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'opaquelocktoken://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'zbecBnDHLqsL9F8YLXJH0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pack://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'pack://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Ar9tP_pUJ7nMjgMUKLvxdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'palm://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'palm://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '86JjleNaIrYkXqwHrUNHTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'paparazzi://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'paparazzi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '7G4EBgyyOOPNc6zIlne3nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pkcs11://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'pkcs11://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ejsObz6SvdMa93mN9k46YA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'platform://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'platform://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'lA_VehKX7F_LFVz2FRWvRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pop://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'pop://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '0ajXPcXe0RI1inKtW7Mg0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pres://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'pres://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'kNr6wZayQgWubokln0kxkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'prospero://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'prospero://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'IACgZzQk3G9mD__FACrI8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'proxy://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'proxy://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'e5KBNNfh6-LRHMe0AXw-NA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'psyc://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'psyc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'R1b6sZmJveaQZrY1ysTwSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'query://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'query://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'l8PZhUHIvWrGfFVuzusOdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'redis://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'redis://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'O0VlzhICp4nnrVJFz7cz3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rediss://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rediss://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'HS-u7hYRiEEY9LhXxK0chA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'reload://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'reload://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'aQQe3jqhmb3Bkyl223la5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'res://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'res://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '2-sTu2WusZLnyHNs02gbNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'resource://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'resource://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Ec4HSJGUM4MvwIgxVsGx7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rmi://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rmi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '61ObU0ozTPXemOpIByteAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rsync://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rsync://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'wHDWP6XSd4zpWkl5uiedfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtmfp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rtmfp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    's7PtSvRXxezbEk_PyGKRuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtmp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rtmp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'pvW_cqi1GhWR_V72QU1XXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtsp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rtsp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'xHSjt4JylhBWa7IPS7xWXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtsps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rtsps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'AWD4kndRrjclKcjq138eJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtspu://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'rtspu://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'prXZOfCwmnY2K1YH9Qo9Kg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 's3://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 's3://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'E4XSAfvgmnimRPz0C6MRXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'secondlife://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'secondlife://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'BM1zRuZeKQlyjMA_3Qu1CQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'service://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'service://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'mPGG6t8fZtndSymOwbt0Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'session://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'session://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'j_Olmp-FsstaM7EYXBWqBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sftp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '_EDGEWbVv69wCeXy1HxNiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sgn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sgn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '8jgw77ovjXkvwPtAd2aQgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'shttp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'shttp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '_XIstavq9BebiXdhNsAtlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sieve://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sieve://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'goVKm7If45XR8mKS3gGE4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sip://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sip://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'R669WuymKY0gn8YMCY63CQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sips://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sips://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '_e62FUbbwaeBp4NHE0hOkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'skype://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'skype://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'NUBqiHDGmkFM_g3qMW7Sbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'smb://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'smb://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'yIInmyCv_lClcfypOId9cA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sms://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'sms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'xZV2ZNZJi7VIzCtZCG8D_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'smtp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'smtp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'xvYIq3YGT5qYRDcGcb3XpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'snews://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'snews://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '6BlPD8y1BkMKUVZhlQWngQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'snmp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'snmp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'HqAF73WBYVE3Bp6y-XNazA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soap.beep://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'soap.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '4pAIcPnwhj9ZFQAxSSBPGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soap.beeps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'soap.beeps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'RCcE-sm3YP0ptANHCWG-Lg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soldat://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'soldat://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ECp3bDskjzUENlR978Qpkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'spotify://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'spotify://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'WnK56xy5hHyFaJKPp7UJAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ssh://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ssh://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'dbuE8EzsNVvmzl8SqI7mGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'steam://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'steam://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'XIN6ZkHl4642SDvR74LEgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'stun://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'stun://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '6x8OHsb91RYozV_pQhanSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'stuns://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'stuns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ChsydyrfoDTMJjGXrhezYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'submit://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'submit://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'sYl3tv2-n-giBa6ltQg_Aw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'svn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'svn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'twk5JTOCA0jKPYV6bgvwhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tag://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tag://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Mnd5xdpywwsrZKHFERsGvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'teamspeak://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'teamspeak://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'LtOKon3_mGHmcWSh5UOoUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tel://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tel://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ea-46uMjNvPizO7KS-D2EA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'teliaeid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'teliaeid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'EXVYZ-pXJmt6K1zLHWjz_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'telnet://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'telnet://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'Ejx3-ynPsV7zZkewuJWRaA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tftp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'gU5E3oJ6XpBx4Bufa_ChIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'things://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'things://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'lsZf7W_mCRAj2lNuDY20-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'thismessage://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'thismessage://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'E9HglnKB90pwyE5CAM0Ffg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tip://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tip://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'rHfUULRC3TFEKfwh8q1Qcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tn3270://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tn3270://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'SsW2i3SQEBDGDALRhyFkFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'turn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'turn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '59nBYC-c0aY8FhMfvIBnGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'turns://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'turns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'xxNjAorqk-mkvS1lojMICw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tv://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'tv://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '4M3HMVlMuy7enDzZyx2DeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'udp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'udp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ztw6F2a10SH3el5xH-JsgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'unreal://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'unreal://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'QzYX_xXv48PpuJf4H5M1QA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'urn://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'urn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'OEBb-_h8g7DhCPFWuF6Hkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ut2004://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ut2004://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '45vXzSbR5xbaPLkQknaKKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'vemmi://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'vemmi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ZW7XSaDJD83Y7T60Rcs93g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ventrilo://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ventrilo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '3kpdrQIJDSOcG1SxwJJNdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'videotex://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'videotex://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'NEwKIDjjRwqrPwUStyLN_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'view-source://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'view-source://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'UVPThUSeS91Uu-ccfoUghg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wais://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'wais://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'vJDZtCEF9-LVeOunEZnW6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'webcal://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'webcal://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'W6lLJ9vagnMmN4APahgSrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ws://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ws://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'y1tU83NI_4A-qwuN4C3Qkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wss://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'wss://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '0yAldupD_0ZEIEjugWsqew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wtai://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'wtai://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'dh_s54WhFDiD4kc-NNZPjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wyciwyg://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'wyciwyg://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '-tIf2Tenc7j6-GMf_Qo7ng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xcon://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xcon://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '5_YTgiIvovMQqTitOMclTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xcon-userid://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xcon-userid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'CDkTGy7dQfD7UjAXaufSog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xfire://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xfire://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'lNaGDgry8wpCU4A0363yLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmlrpc.beep://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xmlrpc.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'G_cnNs6qHQHjYqhCRslrQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmlrpc.beeps://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xmlrpc.beeps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'hSukWS_RQIM2VBS88XMiYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmpp://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xmpp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'cM1Im4KswQWAJzFcp3dAbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xri://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'xri://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '9nfEi6irnhttyDlbwIRcRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ymsgr://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'ymsgr://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'AHYps89TFWcTTVW6nmNL7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'z39.50://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'E5Uz6Wc22cuy0leA35hFCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50r://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'z39.50r://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '7XHRH9k-_1fD8le0LJgDow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50s://fully.qualified.domain/path'
        ],
        'validated' => [
            'x' => 'z39.50s://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '7HZyuF2yMFYxV3y-fK-ZPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://a.pl'
        ],
        'validated' => [
            'x' => 'http://a.pl'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'LEaQWNUqI4fZFDLfTYtFJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://localhost/url.php'
        ],
        'validated' => [
            'x' => 'http://localhost/url.php'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '7hvZZqjZe7yKl9L7c9KV-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://local.dev'
        ],
        'validated' => [
            'x' => 'http://local.dev'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '3RJlCS5i4xm7a5ELik3o6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://google.com'
        ],
        'validated' => [
            'x' => 'http://google.com'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'BDuUhQwhmr4z-AcbF-yABA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://www.google.com'
        ],
        'validated' => [
            'x' => 'http://www.google.com'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'jJi1yoSTtG_1gf0D7MdiFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://goog_le.com'
        ],
        'validated' => [
            'x' => 'http://goog_le.com'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'AqHj1P3xmwkrAt-W0eHmTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://google.com'
        ],
        'validated' => [
            'x' => 'https://google.com'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'EoTj2i04BWS-pZRSHlcEGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://illuminate.dev'
        ],
        'validated' => [
            'x' => 'http://illuminate.dev'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '4Dp5ccgPSd6Jnpx-r6fJiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://localhost'
        ],
        'validated' => [
            'x' => 'http://localhost'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '0XdZaWn_j8OJ4NqTORx_FA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/?'
        ],
        'validated' => [
            'x' => 'https://laravel.com/?'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ttLt2CQH6CGtBJOOXcoWJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://президент.рф/'
        ],
        'validated' => [
            'x' => 'http://президент.рф/'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'TlBJ6RpJFs8rFfe31g3FKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://스타벅스코리아.com'
        ],
        'validated' => [
            'x' => 'http://스타벅스코리아.com'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'ZrPfUw-BfD2nHv8ZTMS2-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://xn--d1abbgf6aiiy.xn--p1ai/'
        ],
        'validated' => [
            'x' => 'http://xn--d1abbgf6aiiy.xn--p1ai/'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'BfkKFdFo_vhLkgwbaZ-7Dg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com?'
        ],
        'validated' => [
            'x' => 'https://laravel.com?'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'CkCAwW9B-nsKx7qenkliKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com?q=1'
        ],
        'validated' => [
            'x' => 'https://laravel.com?q=1'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'PLFPPq30ekdsFaO5JTWhkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/?q=1'
        ],
        'validated' => [
            'x' => 'https://laravel.com/?q=1'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '7zFD5CMhX4Il16sYM4YaGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com#'
        ],
        'validated' => [
            'x' => 'https://laravel.com#'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'vGM1dvRDvJj8o_gNmHC7aQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com#fragment'
        ],
        'validated' => [
            'x' => 'https://laravel.com#fragment'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '2kUQOPLJmhY9sM1CrVGIAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/#fragment'
        ],
        'validated' => [
            'x' => 'https://laravel.com/#fragment'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'f_SuEZbx1f3sb9p3EV3xSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain1'
        ],
        'validated' => [
            'x' => 'https://domain1'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'N7A46BcQyVvv1MG4XPwXCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain12/'
        ],
        'validated' => [
            'x' => 'https://domain12/'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'DDjbCaGjK_KZh4jvhZ6PSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain12#fragment'
        ],
        'validated' => [
            'x' => 'https://domain12#fragment'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    '8o_IPbvoOQ1GcZY8hwdZjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain1/path'
        ],
        'validated' => [
            'x' => 'https://domain1/path'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'tK8OOnr4f8fTEQXu8PJcQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain.com/path/%2528failed%2526?param=1#fragment'
        ],
        'validated' => [
            'x' => 'https://domain.com/path/%2528failed%2526?param=1#fragment'
        ],
        'rules' => [
            'x' => 'Url'
        ],
        'expandedRules' => [
            'x' => [
                'Url'
            ]
        ]
    ],
    'cGuT9FS7ZFRtVr9_8W5YuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3906',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:min_width=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:min_width=1'
            ]
        ]
    ],
    'Pr2r5wRgeVe2jsTjVXkOQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3912',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_width=10'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_width=10'
            ]
        ]
    ],
    'a6FqZwT_BONz3POQAofYLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3918',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:min_height=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:min_height=1'
            ]
        ]
    ],
    '2rGxmHBi4OsX8DILTIyljQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3924',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_height=10'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_height=10'
            ]
        ]
    ],
    '0oY_xNdxbyDMyd8taJimpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3930',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:width=3'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:width=3'
            ]
        ]
    ],
    'jtltgN1K3EyjPb6qUMxeSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3933',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:height=2'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:height=2'
            ]
        ]
    ],
    '3Vi1w4HHGEb9tgroy5qDGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3936',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:min_height=2,ratio=3/2'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:min_height=2,ratio=3/2'
            ]
        ]
    ],
    'bUzl8-ynnRWN0n05pMf2KQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3939',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:ratio=1.5'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:ratio=1.5'
            ]
        ]
    ],
    '1B0mF3rUqTGj50uHrsQu0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3953',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:ratio=2/1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:ratio=2/1'
            ]
        ]
    ],
    '8hBPPvAYRMiqqVsSMA247A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3968',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:ratio=2/3'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:ratio=2/3'
            ]
        ]
    ],
    'US0I_u5ZkP55vfHHQbJoxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3975',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'image/svg+xml';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'image/svg+xml';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_width=1,max_height=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ]
    ],
    'n7v-BNVFJj3RdKR11VGtSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3981',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_width=1,max_height=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ]
    ],
    'NdcktDh4sYK4-Y5ifbzoaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3988',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'image/svg';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'image/svg';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_width=1,max_height=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ]
    ],
    'g56191gfchZW7dR1O8_t7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3994',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_width=1,max_height=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ]
    ],
    '7UiW_VZvN7z7EUJEkegRsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFile:4091',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->test = true;
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'file'
        ],
        'expandedRules' => [
            'x' => [
                'file'
            ]
        ]
    ],
    '2Q0QwqNqdDSI3q8Y6t_ulA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:4098',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                'alpha',
                [],
                ''
            ]
        ],
        'expandedRules' => [
            'x' => [
                'alpha',
                [],
                ''
            ]
        ]
    ],
    'lJut0IZSr8mcBZWeWjsTxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:4101',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => '|||required|'
        ],
        'expandedRules' => [
            'x' => [
                '',
                '',
                '',
                'required',
                ''
            ]
        ]
    ],
    'Y56M8uw1EJCdpkYeq0tF4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testAlternativeFormat:4108',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                'alpha',
                [
                    'min',
                    3
                ],
                [
                    'max',
                    10
                ]
            ]
        ],
        'expandedRules' => [
            'x' => [
                'alpha',
                [
                    'min',
                    3
                ],
                [
                    'max',
                    10
                ]
            ]
        ]
    ],
    'PVkOY0FXH3lTrIAyvwOU9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4115',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => 'Alpha'
        ],
        'expandedRules' => [
            'x' => [
                'Alpha'
            ]
        ]
    ],
    'qKiKho0_fR8t2PL3FfCo1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4129',
        'data' => [
            'x' => 'ユニコードを基盤技術と'
        ],
        'validated' => [
            'x' => 'ユニコードを基盤技術と'
        ],
        'rules' => [
            'x' => 'Alpha'
        ],
        'expandedRules' => [
            'x' => [
                'Alpha'
            ]
        ]
    ],
    'p0ZoChSJaYcPZqHXLJ2-Ew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4135',
        'data' => [
            'x' => 'नमस्कार'
        ],
        'validated' => [
            'x' => 'नमस्कार'
        ],
        'rules' => [
            'x' => 'Alpha'
        ],
        'expandedRules' => [
            'x' => [
                'Alpha'
            ]
        ]
    ],
    'aD-Bi-4LywW09fuoDTXBig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4141',
        'data' => [
            'x' => 'Continuación'
        ],
        'validated' => [
            'x' => 'Continuación'
        ],
        'rules' => [
            'x' => 'Alpha'
        ],
        'expandedRules' => [
            'x' => [
                'Alpha'
            ]
        ]
    ],
    '0cpvj53bpMqqgS9fAfjDjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4163',
        'data' => [
            'x' => 'asls13dlks'
        ],
        'validated' => [
            'x' => 'asls13dlks'
        ],
        'rules' => [
            'x' => 'AlphaNum'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaNum'
            ]
        ]
    ],
    'jxahcEV2x0j8xSOtBItJCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4169',
        'data' => [
            'x' => '१२३'
        ],
        'validated' => [
            'x' => '१२३'
        ],
        'rules' => [
            'x' => 'AlphaNum'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaNum'
            ]
        ]
    ],
    'EHKEqO65qiqSudTRPmIAIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4172',
        'data' => [
            'x' => '٧٨٩'
        ],
        'validated' => [
            'x' => '٧٨٩'
        ],
        'rules' => [
            'x' => 'AlphaNum'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaNum'
            ]
        ]
    ],
    'A4E-ylkNc2RJFNCmoPkYCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4175',
        'data' => [
            'x' => 'नमस्कार'
        ],
        'validated' => [
            'x' => 'नमस्कार'
        ],
        'rules' => [
            'x' => 'AlphaNum'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaNum'
            ]
        ]
    ],
    'reeRZ93VOkH3YbWu7KJFhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4182',
        'data' => [
            'x' => 'asls1-_3dlks'
        ],
        'validated' => [
            'x' => 'asls1-_3dlks'
        ],
        'rules' => [
            'x' => 'AlphaDash'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaDash'
            ]
        ]
    ],
    'bwzIyFiE5RREu25nJNuuiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4188',
        'data' => [
            'x' => 'नमस्कार-_'
        ],
        'validated' => [
            'x' => 'नमस्कार-_'
        ],
        'rules' => [
            'x' => 'AlphaDash'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaDash'
            ]
        ]
    ],
    'j8_-8WjWLg2KutrPzjquyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4191',
        'data' => [
            'x' => '٧٨٩'
        ],
        'validated' => [
            'x' => '٧٨٩'
        ],
        'rules' => [
            'x' => 'AlphaDash'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaDash'
            ]
        ]
    ],
    'h8m3pNMFFqST9AA5euqzcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:4204',
        'data' => [
            'foo' => 'UTC'
        ],
        'validated' => [
            'foo' => 'UTC'
        ],
        'rules' => [
            'foo' => 'Timezone'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone'
            ]
        ]
    ],
    'hle8ETdoIZx_KYPWz0ppKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:4207',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => 'Timezone'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone'
            ]
        ]
    ],
    'zuJST-vy-V52v9SOagFXPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4226',
        'data' => [
            'x' => 'asdasdf'
        ],
        'validated' => [
            'x' => 'asdasdf'
        ],
        'rules' => [
            'x' => 'Regex:/^[a-z]+$/i'
        ],
        'expandedRules' => [
            'x' => [
                'Regex:/^[a-z]+$/i'
            ]
        ]
    ],
    'JpXIly-HosFZJzXlR57LfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4233',
        'data' => [
            'x' => 'a,b'
        ],
        'validated' => [
            'x' => 'a,b'
        ],
        'rules' => [
            'x' => 'Regex:/^a,b$/i'
        ],
        'expandedRules' => [
            'x' => [
                'Regex:/^a,b$/i'
            ]
        ]
    ],
    'gpYy_Mmi9IKKh4-MhHxA3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4236',
        'data' => [
            'x' => '12'
        ],
        'validated' => [
            'x' => '12'
        ],
        'rules' => [
            'x' => 'Regex:/^12$/i'
        ],
        'expandedRules' => [
            'x' => [
                'Regex:/^12$/i'
            ]
        ]
    ],
    'u3MOAy4XxwEFoV32s9Xyfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4239',
        'data' => [
            'x' => 12
        ],
        'validated' => [
            'x' => 12
        ],
        'rules' => [
            'x' => 'Regex:/^12$/i'
        ],
        'expandedRules' => [
            'x' => [
                'Regex:/^12$/i'
            ]
        ]
    ],
    'RjcjJ2OFJzbkB8Yprd0x5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4242',
        'data' => [
            'x' => [
                'y' => [
                    'z' => 'james'
                ]
            ]
        ],
        'validated' => [
            'x' => [
                'y' => [
                    'z' => 'james'
                ]
            ]
        ],
        'rules' => [
            'x.*.z' => [
                'Regex:/^(taylor|james)$/i'
            ]
        ],
        'expandedRules' => [
            'x.y.z' => [
                'Regex:/^(taylor|james)$/i'
            ]
        ]
    ],
    'u3k6IHxlCVoaA-vvDI9pUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:4249',
        'data' => [
            'x' => 'foo bar'
        ],
        'validated' => [
            'x' => 'foo bar'
        ],
        'rules' => [
            'x' => 'NotRegex:/[xyz]/i'
        ],
        'expandedRules' => [
            'x' => [
                'NotRegex:/[xyz]/i'
            ]
        ]
    ],
    'prp3gGnAmw8Y_ItrdlvVMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:4256',
        'data' => [
            'x' => 'foo bar'
        ],
        'validated' => [
            'x' => 'foo bar'
        ],
        'rules' => [
            'x' => 'NotRegex:/x{3,}/i'
        ],
        'expandedRules' => [
            'x' => [
                'NotRegex:/x{3,}/i'
            ]
        ]
    ],
    'IvdqUznIy_SR8pfQzIMR_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4264',
        'data' => [
            'x' => '2000-01-01'
        ],
        'validated' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => 'date'
        ],
        'expandedRules' => [
            'x' => [
                'date'
            ]
        ]
    ],
    'g2SPblK1dqFezeGT_W05jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4267',
        'data' => [
            'x' => '01/01/2000'
        ],
        'validated' => [
            'x' => '01/01/2000'
        ],
        'rules' => [
            'x' => 'date'
        ],
        'expandedRules' => [
            'x' => [
                'date'
            ]
        ]
    ],
    '6P0B1CMQfTAGz7ge5RMHBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4279',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2022-12-28 02:48:38.526944',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2022-12-28 02:48:38.526944',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'date'
        ],
        'expandedRules' => [
            'x' => [
                'date'
            ]
        ]
    ],
    'drKzacP6FAvGaNDGjpL5Iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4282',
        'data' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2022-12-28 02:48:38.527281',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2022-12-28 02:48:38.527281',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'date'
        ],
        'expandedRules' => [
            'x' => [
                'date'
            ]
        ]
    ],
    '5HlhNHQ71VnM8UHzPe1lTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4285',
        'data' => [
            'x' => '2000-01-01'
        ],
        'validated' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d'
            ]
        ]
    ],
    'iav1gjtHETLjvBWyKgWTNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4301',
        'data' => [
            'x' => '2013-02'
        ],
        'validated' => [
            'x' => '2013-02'
        ],
        'rules' => [
            'x' => 'date_format:Y-m'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m'
            ]
        ]
    ],
    '5snVeq9_jEHtPJj24Jlf3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4304',
        'data' => [
            'x' => '2000-01-01T00:00:00Atlantic/Azores'
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00Atlantic/Azores'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d\\TH:i:se'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:se'
            ]
        ]
    ],
    '56sWZ7yIvSVkM2ePIjjPOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4307',
        'data' => [
            'x' => '2000-01-01T00:00:00Z'
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00Z'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d\\TH:i:sT'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sT'
            ]
        ]
    ],
    '7x00fMrJioqF36H0RhuT2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4310',
        'data' => [
            'x' => '2000-01-01T00:00:00+0000'
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00+0000'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d\\TH:i:sO'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sO'
            ]
        ]
    ],
    'sYz5y_jNjusXPcODR_o57A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4313',
        'data' => [
            'x' => '2000-01-01T00:00:00+00:30'
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00+00:30'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d\\TH:i:sP'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sP'
            ]
        ]
    ],
    '9jH0gEL0zLPXbBxlosi1rw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4316',
        'data' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'validated' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s'
            ]
        ]
    ],
    '-cVHaoYNs4yosJobGFnwWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4322',
        'data' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'validated' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s,H:i:s'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s,H:i:s'
            ]
        ]
    ],
    'N4FEd_hzd-S2K92jzuyIrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4325',
        'data' => [
            'x' => '17:43:59'
        ],
        'validated' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s,H:i:s'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s,H:i:s'
            ]
        ]
    ],
    'xZEHauQObUmYzZ-NQon32g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4328',
        'data' => [
            'x' => '17:43:59'
        ],
        'validated' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => 'date_format:H:i:s'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i:s'
            ]
        ]
    ],
    'kXJCjkNKt43CcYx1-X6ZtQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4334',
        'data' => [
            'x' => '17:43'
        ],
        'validated' => [
            'x' => '17:43'
        ],
        'rules' => [
            'x' => 'date_format:H:i'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i'
            ]
        ]
    ],
    'FOmfMcF6jPzaQhx8XCpSuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4342',
        'data' => [
            'x' => '2000-01-01'
        ],
        'validated' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => 'date_equals:2000-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:2000-01-01'
            ]
        ]
    ],
    'audVzvDw0bvdSY1xlyZuAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4345',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166897f000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166897f000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'date_equals:2000-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:2000-01-01'
            ]
        ]
    ],
    't5jsQhM6_B-6qUBgeUfclg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4351',
        'data' => [
            'start' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ]),
            'ends' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'ends' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'ends' => 'date_equals:start'
        ],
        'expandedRules' => [
            'ends' => [
                'date_equals:start'
            ]
        ]
    ],
    'lCWxdwWOOARa2OHp8IKdew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4354',
        'data' => [
            'x' => '2022-12-28'
        ],
        'validated' => [
            'x' => '2022-12-28'
        ],
        'rules' => [
            'x' => 'date_equals:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:today'
            ]
        ]
    ],
    'rpgg6IOJRwDuoRuyKlzbqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4363',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|date_equals:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'date_equals:today'
            ]
        ]
    ],
    'qva5ecOI3ziZBzxphqIdkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4372',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s|date_equals:2012-01-01 17:44:00'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'date_equals:2012-01-01 17:44:00'
            ]
        ]
    ],
    '_pFLhpmP-n7bO34R5irwmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4381',
        'data' => [
            'x' => '17:44:00'
        ],
        'validated' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:H:i:s|date_equals:17:44:00'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i:s',
                'date_equals:17:44:00'
            ]
        ]
    ],
    'oWPbURgbd4bH25cSGYWqMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4390',
        'data' => [
            'x' => '17:44'
        ],
        'validated' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => 'date_format:H:i|date_equals:17:44'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i',
                'date_equals:17:44'
            ]
        ]
    ],
    'd42lkc18tlPB3T070iNJ6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4406',
        'data' => [
            'x' => '2018-01-01 00:00:00'
        ],
        'validated' => [
            'x' => '2018-01-01 00:00:00'
        ],
        'rules' => [
            'x' => 'date_equals:now'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:now'
            ]
        ]
    ],
    'LW8c0S1oxdpqmoaHxg9PYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4409',
        'data' => [
            'x' => '2018-01-01'
        ],
        'validated' => [
            'x' => '2018-01-01'
        ],
        'rules' => [
            'x' => 'date_equals:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:today'
            ]
        ]
    ],
    'CsY07S8DOLOqHaC7QcD3uA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4418',
        'data' => [
            'x' => '01/01/2018'
        ],
        'validated' => [
            'x' => '01/01/2018'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|date_equals:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'date_equals:today'
            ]
        ]
    ],
    'gMUYHtD6UmHoyyJ81fVFcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4427',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'date_equals:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:today'
            ]
        ]
    ],
    '3rYiBcUm8m5my17O39J2rA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4436',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166bf85000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166bf85000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'date_equals:today|after:yesterday|before:tomorrow'
        ],
        'expandedRules' => [
            'x' => [
                'date_equals:today',
                'after:yesterday',
                'before:tomorrow'
            ]
        ]
    ],
    'asZPi5_QnBADPhUWyEVzjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4450',
        'data' => [
            'x' => '2000-01-01'
        ],
        'validated' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => 'Before:2012-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'Before:2012-01-01'
            ]
        ]
    ],
    '8fmrzRHRRRnE5vYDVKWF4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4456',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166b65a000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166b65a000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'Before:2012-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'Before:2012-01-01'
            ]
        ]
    ],
    'YhGSvDF05R6Qbs8Vj6y28A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4462',
        'data' => [
            'x' => '2012-01-01'
        ],
        'validated' => [
            'x' => '2012-01-01'
        ],
        'rules' => [
            'x' => 'After:2000-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'After:2000-01-01'
            ]
        ]
    ],
    'zO4XEioKwJwAnHBFq_jB3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4468',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166bf4c000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000005166bf4c000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'After:2000-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'After:2000-01-01'
            ]
        ]
    ],
    'xv61ewA-JJVPF4O7lF1X5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4474',
        'data' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'rules' => [
            'start' => 'After:2000-01-01',
            'ends' => 'After:start'
        ],
        'expandedRules' => [
            'start' => [
                'After:2000-01-01'
            ],
            'ends' => [
                'After:start'
            ]
        ]
    ],
    'BWii5fdMN_bKI9gpvToIzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4480',
        'data' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'rules' => [
            'start' => 'Before:ends',
            'ends' => 'After:start'
        ],
        'expandedRules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ]
    ],
    '8UCK9pUENuUmg8sRYWCP7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4486',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => 'Before:2012-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'Before:2012-01-01'
            ]
        ]
    ],
    'ZQSJzDpOdD4Y1HF1iZy2AA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4489',
        'data' => [
            'start' => \DateTime::__set_state([
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ]),
            'ends' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000051668362000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'start' => \DateTime::__set_state([
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ]),
            'ends' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000051668362000000006dada983',
                'localMonthsOverflow' => null,
                'localYearsOverflow' => null,
                'localStrictModeEnabled' => null,
                'localHumanDiffOptions' => null,
                'localToStringFormat' => null,
                'localSerializer' => null,
                'localMacros' => null,
                'localGenericMacros' => null,
                'localFormatFunction' => null,
                'localTranslator' => null,
                'dumpProperties' => [
                    'date',
                    'timezone_type',
                    'timezone'
                ],
                'dumpLocale' => null,
                'dumpDateProperties' => null,
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'start' => 'Before:ends',
            'ends' => 'After:start'
        ],
        'expandedRules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ]
    ],
    'JTsZziTjkhbUNnJFLmwupw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4492',
        'data' => [
            'start' => '2012-01-01',
            'ends' => \DateTime::__set_state([
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => \DateTime::__set_state([
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'start' => 'Before:ends',
            'ends' => 'After:start'
        ],
        'expandedRules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ]
    ],
    'xpnw7MG8EIOER6pOBqa6sA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4498',
        'data' => [
            'start' => 'today',
            'ends' => 'tomorrow'
        ],
        'validated' => [
            'start' => 'today',
            'ends' => 'tomorrow'
        ],
        'rules' => [
            'start' => 'Before:ends',
            'ends' => 'After:start'
        ],
        'expandedRules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ]
    ],
    'i2KjOn9wav-zOVjLtcVNWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4501',
        'data' => [
            'x' => '2012-01-01 17:43:59'
        ],
        'validated' => [
            'x' => '2012-01-01 17:43:59'
        ],
        'rules' => [
            'x' => 'Before:2012-01-01 17:44|After:2012-01-01 17:43:58'
        ],
        'expandedRules' => [
            'x' => [
                'Before:2012-01-01 17:44',
                'After:2012-01-01 17:43:58'
            ]
        ]
    ],
    'wOJ5e3Dc17bOrfL1hMVRhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4504',
        'data' => [
            'x' => '2012-01-01 17:44:01'
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:01'
        ],
        'rules' => [
            'x' => 'Before:2012-01-01 17:44:02|After:2012-01-01 17:44'
        ],
        'expandedRules' => [
            'x' => [
                'Before:2012-01-01 17:44:02',
                'After:2012-01-01 17:44'
            ]
        ]
    ],
    'kHPQNjg_Nii6qS-2rqffSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4513',
        'data' => [
            'x' => '17:43:59'
        ],
        'validated' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => 'Before:17:44|After:17:43:58'
        ],
        'expandedRules' => [
            'x' => [
                'Before:17:44',
                'After:17:43:58'
            ]
        ]
    ],
    '5vvdLdWwMKKOSREYAZFfBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4516',
        'data' => [
            'x' => '17:44:01'
        ],
        'validated' => [
            'x' => '17:44:01'
        ],
        'rules' => [
            'x' => 'Before:17:44:02|After:17:44'
        ],
        'expandedRules' => [
            'x' => [
                'Before:17:44:02',
                'After:17:44'
            ]
        ]
    ],
    'lIBU0lPWt4WAL8LgGkZtig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4536',
        'data' => [
            'x' => '31/12/2000'
        ],
        'validated' => [
            'x' => '31/12/2000'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|before:31/12/2012'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'before:31/12/2012'
            ]
        ]
    ],
    'PZfWVBuEOp0oMW6dtB-9cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4545',
        'data' => [
            'x' => '31/12/2012'
        ],
        'validated' => [
            'x' => '31/12/2012'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|after:31/12/2000'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'after:31/12/2000'
            ]
        ]
    ],
    '4_ovCqsMZeNUxq65eGWJHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4551',
        'data' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'rules' => [
            'start' => 'date_format:d/m/Y|after:31/12/2000',
            'ends' => 'date_format:d/m/Y|after:start'
        ],
        'expandedRules' => [
            'start' => [
                'date_format:d/m/Y',
                'after:31/12/2000'
            ],
            'ends' => [
                'date_format:d/m/Y',
                'after:start'
            ]
        ]
    ],
    '6YxGNKqVqm1bcCpRFm21Dg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4563',
        'data' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'rules' => [
            'start' => 'date_format:d/m/Y|before:ends',
            'ends' => 'date_format:d/m/Y|after:start'
        ],
        'expandedRules' => [
            'start' => [
                'date_format:d/m/Y',
                'before:ends'
            ],
            'ends' => [
                'date_format:d/m/Y',
                'after:start'
            ]
        ]
    ],
    'G4F0pGgmzsxT8L8S34lbiw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4578',
        'data' => [
            'start' => '31/12/2012',
            'ends' => null
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => null
        ],
        'rules' => [
            'start' => 'date_format:d/m/Y|before:ends',
            'ends' => 'nullable|date_format:d/m/Y|after:start'
        ],
        'expandedRules' => [
            'start' => [
                'date_format:d/m/Y',
                'before:ends'
            ],
            'ends' => [
                'nullable',
                'date_format:d/m/Y',
                'after:start'
            ]
        ]
    ],
    'RFt_A7N2cqLD-GTZqxzkwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4587',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|after:yesterday|before:tomorrow'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'after:yesterday',
                'before:tomorrow'
            ]
        ]
    ],
    'yvdB3sMMRkSecKAIX2Mfvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4596',
        'data' => [
            'x' => '2022-12-28'
        ],
        'validated' => [
            'x' => '2022-12-28'
        ],
        'rules' => [
            'x' => 'after:yesterday|before:tomorrow'
        ],
        'expandedRules' => [
            'x' => [
                'after:yesterday',
                'before:tomorrow'
            ]
        ]
    ],
    'eoCmUgO5ZkrTj0BPaa2lUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4605',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s|before:2012-01-01 17:44:01|after:2012-01-01 17:43:59'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'before:2012-01-01 17:44:01',
                'after:2012-01-01 17:43:59'
            ]
        ]
    ],
    'ExTMKXmSVP4NQSfh8otelw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4614',
        'data' => [
            'x' => '17:44:00'
        ],
        'validated' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:H:i:s|before:17:44:01|after:17:43:59'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i:s',
                'before:17:44:01',
                'after:17:43:59'
            ]
        ]
    ],
    'rUad5pzxY_4tRdMTzIzuLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4623',
        'data' => [
            'x' => '17:44'
        ],
        'validated' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => 'date_format:H:i|before:17:45|after:17:43'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i',
                'before:17:45',
                'after:17:43'
            ]
        ]
    ],
    'uGaf6GM1gDPKUpG6JLEbeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4643',
        'data' => [
            'x' => '2012-01-15'
        ],
        'validated' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => 'before_or_equal:2012-01-15'
        ],
        'expandedRules' => [
            'x' => [
                'before_or_equal:2012-01-15'
            ]
        ]
    ],
    '91qJRgJ2z0Y0YF8_ImtnLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4646',
        'data' => [
            'x' => '2012-01-15'
        ],
        'validated' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => 'before_or_equal:2012-01-16'
        ],
        'expandedRules' => [
            'x' => [
                'before_or_equal:2012-01-16'
            ]
        ]
    ],
    'ziabIJYvH996Zm6Ue7Kf9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4652',
        'data' => [
            'x' => '15/01/2012'
        ],
        'validated' => [
            'x' => '15/01/2012'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|before_or_equal:15/01/2012'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:15/01/2012'
            ]
        ]
    ],
    'O18-qb59NcD1j6rsWDTrLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4658',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|before_or_equal:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:today'
            ]
        ]
    ],
    'bCqtg7p1hdznzrcXAK4WGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4661',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|before_or_equal:tomorrow'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:tomorrow'
            ]
        ]
    ],
    'OmlETYncMPCMy6z0lUNmUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4667',
        'data' => [
            'x' => '2012-01-15'
        ],
        'validated' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => 'after_or_equal:2012-01-15'
        ],
        'expandedRules' => [
            'x' => [
                'after_or_equal:2012-01-15'
            ]
        ]
    ],
    'iOSC5tAgfdtVbldjPUXw0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4670',
        'data' => [
            'x' => '2012-01-15'
        ],
        'validated' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => 'after_or_equal:2012-01-14'
        ],
        'expandedRules' => [
            'x' => [
                'after_or_equal:2012-01-14'
            ]
        ]
    ],
    'imbSBXMkgYCMri-Zem36UA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4676',
        'data' => [
            'x' => '15/01/2012'
        ],
        'validated' => [
            'x' => '15/01/2012'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|after_or_equal:15/01/2012'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:15/01/2012'
            ]
        ]
    ],
    'YIHSzZzUrBSlMhHsX80pAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4682',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|after_or_equal:today'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:today'
            ]
        ]
    ],
    '7pvWPZswrqSnTDdXWFUXuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4685',
        'data' => [
            'x' => '28/12/2022'
        ],
        'validated' => [
            'x' => '28/12/2022'
        ],
        'rules' => [
            'x' => 'date_format:d/m/Y|after_or_equal:yesterday'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:yesterday'
            ]
        ]
    ],
    'bWzMUM6-eGbxSMWmv9A79g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4691',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:Y-m-d H:i:s|before_or_equal:2012-01-01 17:44:00|after_or_equal:2012-01-01 17:44:00'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'before_or_equal:2012-01-01 17:44:00',
                'after_or_equal:2012-01-01 17:44:00'
            ]
        ]
    ],
    'FkzLHd03GQfanohnv5VScg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4700',
        'data' => [
            'x' => '17:44:00'
        ],
        'validated' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => 'date_format:H:i:s|before_or_equal:17:44:00|after_or_equal:17:44:00'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i:s',
                'before_or_equal:17:44:00',
                'after_or_equal:17:44:00'
            ]
        ]
    ],
    'vG7_cSiTby6KSkHJsi4bEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4709',
        'data' => [
            'x' => '17:44'
        ],
        'validated' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => 'date_format:H:i|before_or_equal:17:44|after_or_equal:17:44'
        ],
        'expandedRules' => [
            'x' => [
                'date_format:H:i',
                'before_or_equal:17:44',
                'after_or_equal:17:44'
            ]
        ]
    ],
    'tUobV-X1QYiOsrhcwP7f-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4718',
        'data' => [
            'foo' => '2012-01-14',
            'bar' => '2012-01-15'
        ],
        'validated' => [
            'foo' => '2012-01-14'
        ],
        'rules' => [
            'foo' => 'before_or_equal:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'before_or_equal:bar'
            ]
        ]
    ],
    '_thqRdwBq_0vcn1hw6Aebg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4721',
        'data' => [
            'foo' => '2012-01-15',
            'bar' => '2012-01-15'
        ],
        'validated' => [
            'foo' => '2012-01-15'
        ],
        'rules' => [
            'foo' => 'before_or_equal:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'before_or_equal:bar'
            ]
        ]
    ],
    'gZddsV4F5TEZUsNqDW6dXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4730',
        'data' => [
            'foo' => '2012-01-15 11:00',
            'bar' => null
        ],
        'validated' => [
            'foo' => '2012-01-15 11:00',
            'bar' => null
        ],
        'rules' => [
            'foo' => 'date_format:Y-m-d H:i|before_or_equal:bar',
            'bar' => 'date_format:Y-m-d H:i|nullable'
        ],
        'expandedRules' => [
            'foo' => [
                'date_format:Y-m-d H:i',
                'before_or_equal:bar'
            ],
            'bar' => [
                'date_format:Y-m-d H:i',
                'nullable'
            ]
        ]
    ],
    'fO3FLCeM0K2Rou9v89lwkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomDependentValidators:5115',
        'data' => [
            [
                'name' => 'Jamie',
                'age' => 27
            ]
        ],
        'validated' => [
            [
                'name' => 'Jamie'
            ]
        ],
        'rules' => [
            '*.name' => 'dependent_rule:*.age'
        ],
        'expandedRules' => [
            '0.name' => [
                'dependent_rule:*.age'
            ]
        ]
    ],
    '0dEwUd0EN5yyBx2VxuZLOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5145',
        'data' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'validated' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'rules' => [
            'foo' => 'Array',
            'foo.*' => 'Numeric|Min:4|Max:16'
        ],
        'expandedRules' => [
            'foo' => [
                'Array'
            ],
            'foo.0' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ],
            'foo.1' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ],
            'foo.2' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ]
        ]
    ],
    '5ZfJ9VIoUyP3kDrRzz1SRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5159',
        'data' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'validated' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'rules' => [
            'foo' => 'Array',
            'foo.*' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'Array'
            ],
            'foo.0' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ],
            'foo.1' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ],
            'foo.2' => [
                'Numeric',
                'Min:4',
                'Max:16'
            ]
        ]
    ],
    'kmUemK1ipUjkpMhQtwu8Ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5165',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo' => 'Array',
            'foo.*.name' => 'Required|String'
        ],
        'expandedRules' => [
            'foo' => [
                'Array'
            ],
            'foo.0.name' => [
                'Required',
                'String'
            ],
            'foo.1.name' => [
                'Required',
                'String'
            ]
        ]
    ],
    '-LPhX_8Y5XV5LQRiWp774Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5182',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo' => 'Array',
            'foo.*.name' => [
                'Required',
                'String'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'Array'
            ],
            'foo.0.name' => [
                'Required',
                'String'
            ],
            'foo.1.name' => [
                'Required',
                'String'
            ]
        ]
    ],
    'Rwz23xPLesS0Y89BFexEEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:5301',
        'data' => [
            'foo' => [
                'bar' => 'valid'
            ],
            'foo\\.bar' => 'zxc'
        ],
        'validated' => [
            'foo.bar' => 'zxc'
        ],
        'rules' => [
            'foo\\.bar' => 'required'
        ],
        'expandedRules' => [
            'foo\\.bar' => [
                'required'
            ]
        ]
    ],
    'd1eJJNP3ILVisVYrbPFVfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5321',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ],
        'rules' => [
            'foo' => 'required_without:bar.foo\\.bar'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without:bar.foo\\.bar'
            ]
        ]
    ],
    'KNGXGWKHAn26QZMTwPYwkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5324',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ],
        'rules' => [
            'foo' => 'required_without_all:bar.foo\\.bar'
        ],
        'expandedRules' => [
            'foo' => [
                'required_without_all:bar.foo\\.bar'
            ]
        ]
    ],
    'Ow12aO6vEjanoRFrAKZ_KQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5327',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ],
        'rules' => [
            'foo' => 'same:bar.foo\\.bar'
        ],
        'expandedRules' => [
            'foo' => [
                'same:bar.foo\\.bar'
            ]
        ]
    ],
    'BGZWI_1NqvqAieOPq-i1Mw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5330',
        'data' => [
            'foo' => '',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'required_unless:bar.foo\\.bar,valid'
        ],
        'expandedRules' => [
            'foo' => [
                'required_unless:bar.foo\\.bar,valid'
            ]
        ]
    ],
    'XF3qmZWD0RFUxo4QZNVQtA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPassingSlashVulnerability:5349',
        'data' => [
            'matrix' => [
                '\\' => [
                    1
                ],
                '1\\' => [
                    1
                ]
            ]
        ],
        'validated' => [
            'matrix' => [
                '\\' => [
                    1
                ],
                '1\\' => [
                    1
                ]
            ]
        ],
        'rules' => [
            'matrix.*.*' => 'integer'
        ],
        'expandedRules' => [
            'matrix.\\.0' => [
                'integer'
            ],
            'matrix.1\\.0' => [
                'integer'
            ]
        ]
    ],
    'tgElSA4JAWcxTcdOILQiGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:5375',
        'data' => [
            'matrix' => [
                '\\' => [
                    1
                ],
                '1\\' => [
                    1
                ]
            ]
        ],
        'validated' => [
            'matrix' => [
                '\\' => [
                    1
                ],
                '1\\' => [
                    1
                ]
            ]
        ],
        'rules' => [
            'matrix.*.*' => 'integer'
        ],
        'expandedRules' => [
            'matrix.\\.0' => [
                'integer'
            ],
            'matrix.1\\.0' => [
                'integer'
            ]
        ]
    ],
    'CuzX03pU2mRdm5lbdfOrtQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:5390',
        'data' => [
            'foo\\.bar' => 'valid'
        ],
        'validated' => [
            'foo.bar' => 'valid'
        ],
        'rules' => [
            'foo\\.bar' => 'required|in:valid'
        ],
        'expandedRules' => [
            'foo\\.bar' => [
                'required',
                'in:valid'
            ]
        ]
    ],
    'nfIBaMJaSX45wmXAGaTSuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testImplicitEachWithAsterisksWithArrayValues:5406',
        'data' => [
            'foo' => [
                'bar\\.baz' => ''
            ]
        ],
        'validated' => [
            'foo' => [
                'bar.baz' => ''
            ]
        ],
        'rules' => [
            'foo' => 'required'
        ],
        'expandedRules' => [
            'foo' => [
                'required'
            ]
        ]
    ],
    'k6HWpXvNJo2JpzIhyHKuAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNestedArrayWithCommonParentChildKey:5430',
        'data' => [
            'products' => [
                [
                    'price' => 2,
                    'options' => [
                        [
                            'price' => 1
                        ]
                    ]
                ],
                [
                    'price' => 2,
                    'options' => [
                        [
                            'price' => 0
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'products' => [
                [
                    'price' => 2
                ],
                [
                    'price' => 2
                ]
            ]
        ],
        'rules' => [
            'products.*.price' => 'numeric|min:1'
        ],
        'expandedRules' => [
            'products.0.price' => [
                'numeric',
                'min:1'
            ],
            'products.1.price' => [
                'numeric',
                'min:1'
            ]
        ]
    ],
    'UiUf4o3u0SnQL76dtAv1xg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:5458',
        'data' => [
            'foo' => [
                [
                    'password' => 'foo0',
                    'password_confirmation' => 'foo0'
                ],
                [
                    'password' => 'foo1',
                    'password_confirmation' => 'foo1'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'password' => 'foo0'
                ],
                [
                    'password' => 'foo1'
                ]
            ]
        ],
        'rules' => [
            'foo.*.password' => 'confirmed'
        ],
        'expandedRules' => [
            'foo.0.password' => [
                'confirmed'
            ],
            'foo.1.password' => [
                'confirmed'
            ]
        ]
    ],
    'f047XaSCxwSImmyIrmREDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:5477',
        'data' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'password' => 'bar0',
                            'password_confirmation' => 'bar0'
                        ],
                        [
                            'password' => 'bar1',
                            'password_confirmation' => 'bar1'
                        ]
                    ]
                ],
                [
                    'bar' => [
                        [
                            'password' => 'bar2',
                            'password_confirmation' => 'bar2'
                        ],
                        [
                            'password' => 'bar3',
                            'password_confirmation' => 'bar3'
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'password' => 'bar0'
                        ],
                        [
                            'password' => 'bar1'
                        ]
                    ]
                ],
                [
                    'bar' => [
                        [
                            'password' => 'bar2'
                        ],
                        [
                            'password' => 'bar3'
                        ]
                    ]
                ]
            ]
        ],
        'rules' => [
            'foo.*.bar.*.password' => 'confirmed'
        ],
        'expandedRules' => [
            'foo.0.bar.0.password' => [
                'confirmed'
            ],
            'foo.0.bar.1.password' => [
                'confirmed'
            ],
            'foo.1.bar.0.password' => [
                'confirmed'
            ],
            'foo.1.bar.1.password' => [
                'confirmed'
            ]
        ]
    ],
    '4ld4aLGWwqVI8gFi-EyMnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:5517',
        'data' => [
            'foo' => [
                [
                    'name' => 'foo',
                    'last' => 'bar'
                ],
                [
                    'name' => 'bar',
                    'last' => 'foo'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'foo'
                ],
                [
                    'name' => 'bar'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'different:foo.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'different:foo.*.last'
            ],
            'foo.1.name' => [
                'different:foo.*.last'
            ]
        ]
    ],
    'NO0Nw1EhfGyw0qrWQxZySw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:5530',
        'data' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'name' => 'foo',
                            'last' => 'bar'
                        ],
                        [
                            'name' => 'bar',
                            'last' => 'foo'
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'name' => 'foo'
                        ],
                        [
                            'name' => 'bar'
                        ]
                    ]
                ]
            ]
        ],
        'rules' => [
            'foo.*.bar.*.name' => [
                'different:foo.*.bar.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.bar.0.name' => [
                'different:foo.*.bar.*.last'
            ],
            'foo.0.bar.1.name' => [
                'different:foo.*.bar.*.last'
            ]
        ]
    ],
    'dwQ2HOlRG7Ll5fjYwu00ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:5570',
        'data' => [
            'foo' => [
                [
                    'name' => 'foo',
                    'last' => 'foo'
                ],
                [
                    'name' => 'bar',
                    'last' => 'bar'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'foo'
                ],
                [
                    'name' => 'bar'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'same:foo.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'same:foo.*.last'
            ],
            'foo.1.name' => [
                'same:foo.*.last'
            ]
        ]
    ],
    'heXNkvASGtbQIMKqiZ65EA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:5583',
        'data' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'name' => 'foo',
                            'last' => 'foo'
                        ],
                        [
                            'name' => 'bar',
                            'last' => 'bar'
                        ]
                    ]
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'bar' => [
                        [
                            'name' => 'foo'
                        ],
                        [
                            'name' => 'bar'
                        ]
                    ]
                ]
            ]
        ],
        'rules' => [
            'foo.*.bar.*.name' => [
                'same:foo.*.bar.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.bar.0.name' => [
                'same:foo.*.bar.*.last'
            ],
            'foo.0.bar.1.name' => [
                'same:foo.*.bar.*.last'
            ]
        ]
    ],
    '0IQvPVKMGd42UrpMXpyP8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:5623',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required'
            ],
            'foo.1.name' => [
                'Required'
            ]
        ]
    ],
    'v7ahVnqqN6h0_-k28-vQ2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:5632',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required'
            ],
            'foo.1.name' => [
                'Required'
            ]
        ]
    ],
    'hxDfr8JQ35LsafSVST9D0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:5672',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'foo'
                ],
                [
                    'last' => 'bar'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_if:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ]
    ],
    'ItMq7exSgoHoTZ523ZLQ4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:5681',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'foo'
                ],
                [
                    'last' => 'bar'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_if:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ]
    ],
    'g8InC7hMR-oSFPTOwQ3z2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredUnless:5721',
        'data' => [
            'foo' => [
                [
                    'name' => null,
                    'last' => 'foo'
                ],
                [
                    'name' => 'second',
                    'last' => 'bar'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => null
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_unless:foo.*.last,foo'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_unless:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_unless:foo.*.last,foo'
            ]
        ]
    ],
    'wR_FV77Hb8gzJAPSiCIc1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:5770',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'last'
                ],
                [
                    'name' => 'second',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_with:foo.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_with:foo.*.last'
            ],
            'foo.1.name' => [
                'Required_with:foo.*.last'
            ]
        ]
    ],
    'W4WJT-sfgxgZdHFONSrJvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:5779',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'last'
                ],
                [
                    'name' => 'second',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_with:foo.*.last'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_with:foo.*.last'
            ],
            'foo.1.name' => [
                'Required_with:foo.*.last'
            ]
        ]
    ],
    'vTUk7XKyRb9O07YF1FOmpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:5827',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'last',
                    'middle' => 'middle'
                ],
                [
                    'name' => 'second',
                    'last' => 'last',
                    'middle' => 'middle'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    '47rURYvv5qk2E4U0r4HNpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:5836',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'last' => 'last',
                    'middle' => 'middle'
                ],
                [
                    'name' => 'second',
                    'last' => 'last',
                    'middle' => 'middle'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    'IzawEKdsxgx4xSMHThpmJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:5876',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'middle' => 'middle'
                ],
                [
                    'name' => 'second',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    'jABk4w8HjRCJDKXqEqg9Bw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:5885',
        'data' => [
            'foo' => [
                [
                    'name' => 'first',
                    'middle' => 'middle'
                ],
                [
                    'name' => 'second',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    'R9-4lER6eBGuWYskxaULJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:5926',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => null,
                    'middle' => 'middle'
                ],
                [
                    'name' => null,
                    'middle' => 'middle',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => null
                ],
                [
                    'name' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.2.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    'i-cMJNctK7PYbxvFIHkrRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:5937',
        'data' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => null,
                    'middle' => 'middle'
                ],
                [
                    'name' => null,
                    'middle' => 'middle',
                    'last' => 'last'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ],
                [
                    'name' => null
                ],
                [
                    'name' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ]
        ],
        'expandedRules' => [
            'foo.0.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.2.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ]
        ]
    ],
    '2oknNJYOONctIWY0WPCMkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:5974',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'start' => '2016-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.*.start' => [
                'before:foo.*.end'
            ]
        ],
        'expandedRules' => [
            'foo.0.start' => [
                'before:foo.*.end'
            ]
        ]
    ],
    'IdeAXXGx-VrcNKHrqr8DNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:5988',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.*.end' => [
                'after:foo.*.start'
            ]
        ],
        'expandedRules' => [
            'foo.0.end' => [
                'after:foo.*.start'
            ]
        ]
    ],
    'TCoxWtys8PYyI7EBgJ5fbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedData:6641',
        'data' => [
            'first' => 'john',
            'preferred' => 'john',
            'last' => 'doe',
            'type' => 'admin'
        ],
        'validated' => [
            'first' => 'john',
            'preferred' => 'john'
        ],
        'rules' => [
            'first' => 'required',
            'preferred' => 'required'
        ],
        'expandedRules' => [
            'first' => [
                'required'
            ],
            'preferred' => [
                'required'
            ]
        ]
    ],
    'ZF_CWklYXAr3rJ4dGhLlyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedRules:6656',
        'data' => [
            'nested' => [
                'foo' => 'bar',
                'baz' => ''
            ],
            'array' => [
                1,
                2
            ]
        ],
        'validated' => [
            'nested' => [
                'foo' => 'bar'
            ],
            'array' => [
                1,
                2
            ]
        ],
        'rules' => [
            'nested.foo' => 'required',
            'array.*' => 'integer'
        ],
        'expandedRules' => [
            'nested.foo' => [
                'required'
            ],
            'array.0' => [
                'integer'
            ],
            'array.1' => [
                'integer'
            ]
        ]
    ],
    'iRposlUarSpbQ2ACeBuXLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedChildRules:6669',
        'data' => [
            'nested' => [
                'foo' => 'bar',
                'with' => 'extras',
                'type' => 'admin'
            ]
        ],
        'validated' => [
            'nested' => [
                'foo' => 'bar'
            ]
        ],
        'rules' => [
            'nested.foo' => 'required'
        ],
        'expandedRules' => [
            'nested.foo' => [
                'required'
            ]
        ]
    ],
    'zYT_Jzdh9sH7S-2QascAcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedArrayRules:6682',
        'data' => [
            'nested' => [
                [
                    'bar' => 'baz',
                    'with' => 'extras',
                    'type' => 'admin'
                ],
                [
                    'bar' => 'baz2',
                    'with' => 'extras',
                    'type' => 'admin'
                ]
            ]
        ],
        'validated' => [
            'nested' => [
                [
                    'bar' => 'baz'
                ],
                [
                    'bar' => 'baz2'
                ]
            ]
        ],
        'rules' => [
            'nested.*.bar' => 'required'
        ],
        'expandedRules' => [
            'nested.0.bar' => [
                'required'
            ],
            'nested.1.bar' => [
                'required'
            ]
        ]
    ],
    '-N10CCeProHanGZo0i7T1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAndValidatedData:6695',
        'data' => [
            'first' => 'john',
            'preferred' => 'john',
            'last' => 'doe',
            'type' => 'admin'
        ],
        'validated' => [
            'first' => 'john',
            'preferred' => 'john'
        ],
        'rules' => [
            'first' => 'required',
            'preferred' => 'required'
        ],
        'expandedRules' => [
            'first' => [
                'required'
            ],
            'preferred' => [
                'required'
            ]
        ]
    ],
    'poh5sDoW9i8ChCBIC_INrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ],
        'validated' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    '2seK6eO5GEPjzfpdY_HctA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ],
        'validated' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'Ao6Kdm4UNd97_G126WoSpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ],
        'validated' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'xEdnVKHrzLIn6FsTbBLezg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'e60d3f48-95d7-4d8d-aad0-856f29a27da2'
        ],
        'validated' => [
            'foo' => 'e60d3f48-95d7-4d8d-aad0-856f29a27da2'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    '0Wl7Ddayvr1cjw9TMcbbrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-11e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-11e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'KEGFWUjUEzU-vbyT_w9VDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    '1NmKDuFgzI7tTQKUNlgplw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-31e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-31e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'n0CtPFwkcfI-QweS5RS2sA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-41e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-41e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'AJl2Vw_rZhdWThHQfAElSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-51e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-51e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'qWpwOrpV78x9vyF-F-lDgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'FF6F8CB0-C57D-11E1-9B21-0800200C9A66'
        ],
        'validated' => [
            'foo' => 'FF6F8CB0-C57D-11E1-9B21-0800200C9A66'
        ],
        'rules' => [
            'foo' => 'uuid'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid'
            ]
        ]
    ],
    'qe0jIzx-hTa6VNk5dD8ZxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidAscii:6782',
        'data' => [
            'foo' => 'Dusseldorf'
        ],
        'validated' => [
            'foo' => 'Dusseldorf'
        ],
        'rules' => [
            'foo' => 'ascii'
        ],
        'expandedRules' => [
            'foo' => [
                'ascii'
            ]
        ]
    ],
    'Oiw9YaRobscku5Wa_EP4BQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUlid:6796',
        'data' => [
            'foo' => '01gd6r360bp37zj17nxb55yv40'
        ],
        'validated' => [
            'foo' => '01gd6r360bp37zj17nxb55yv40'
        ],
        'rules' => [
            'foo' => 'ulid'
        ],
        'expandedRules' => [
            'foo' => [
                'ulid'
            ]
        ]
    ],
    'IlxulFqVffs8RLwNIZU5Pg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointment' => false
        ],
        'validated' => [
            'has_appointment' => false
        ],
        'rules' => [
            'has_appointment' => [
                'required',
                'bool'
            ],
            'appointment_date' => [
                'exclude_if:has_appointment,false',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_appointment' => [
                'required',
                'bool'
            ]
        ]
    ],
    '-G-qRQQ-4pUahzkmSA1dYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'cat' => 'Tom'
        ],
        'validated' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ],
            'mouse' => [
                'exclude_if:cat,Tom',
                'required',
                'file'
            ]
        ],
        'expandedRules' => [
            'cat' => [
                'required',
                'string'
            ]
        ]
    ],
    'jKa5MxMRFV5DAwE-8Tw-vg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointment' => true,
            'appointment_date' => '2021-03-08'
        ],
        'validated' => [
            'has_appointment' => true,
            'appointment_date' => '2021-03-08'
        ],
        'rules' => [
            'has_appointment' => [
                'nullable',
                'bool'
            ],
            'appointment_date' => [
                'exclude_if:has_appointment,null',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_appointment' => [
                'nullable',
                'bool'
            ],
            'appointment_date' => [
                'exclude_if:has_appointment,null',
                'required',
                'date'
            ]
        ]
    ],
    'zjHL5Zy14YO6L_XykRkLXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointment' => true,
            'appointment_date' => '2019-12-13'
        ],
        'validated' => [
            'has_appointment' => true,
            'appointment_date' => '2019-12-13'
        ],
        'rules' => [
            'has_appointment' => [
                'required',
                'bool'
            ],
            'appointment_date' => [
                'exclude_if:has_appointment,false',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_appointment' => [
                'required',
                'bool'
            ],
            'appointment_date' => [
                'exclude_if:has_appointment,false',
                'required',
                'date'
            ]
        ]
    ],
    'ufQ6HQ1lVQFSgKtbCedDJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_no_appointments' => true
        ],
        'validated' => [
            'has_no_appointments' => true
        ],
        'rules' => [
            'has_no_appointments' => [
                'required',
                'bool'
            ],
            'has_doctor_appointment' => [
                'exclude_if:has_no_appointments,true',
                'required',
                'bool'
            ],
            'doctor_appointment_date' => [
                'exclude_if:has_no_appointments,true',
                'exclude_if:has_doctor_appointment,false',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_no_appointments' => [
                'required',
                'bool'
            ]
        ]
    ],
    'mxpO6OZahEUPVkYGEiuQkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_no_appointments' => false,
            'has_doctor_appointment' => false
        ],
        'validated' => [
            'has_no_appointments' => false,
            'has_doctor_appointment' => false
        ],
        'rules' => [
            'has_no_appointments' => [
                'required',
                'bool'
            ],
            'has_doctor_appointment' => [
                'exclude_if:has_no_appointments,true',
                'required',
                'bool'
            ],
            'doctor_appointment_date' => [
                'exclude_if:has_no_appointments,true',
                'exclude_if:has_doctor_appointment,false',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_no_appointments' => [
                'required',
                'bool'
            ],
            'has_doctor_appointment' => [
                'exclude_if:has_no_appointments,true',
                'required',
                'bool'
            ]
        ]
    ],
    'KZk8wZCvpxPKDI_y-InYaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false,
            'appointments' => []
        ],
        'validated' => [
            'has_appointments' => false
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ],
            'appointments.*' => [
                'exclude_if:has_appointments,false',
                'required',
                'date'
            ]
        ],
        'expandedRules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ]
    ],
    'r1nUUU3fVgRezscuJItbZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false,
            'appointments' => [
                []
            ]
        ],
        'validated' => [
            'has_appointments' => false
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ],
            'appointments.*.date' => [
                'exclude_if:has_appointments,false',
                'required',
                'date'
            ],
            'appointments.*.name' => [
                'exclude_if:has_appointments,false',
                'required',
                'string'
            ]
        ],
        'expandedRules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ]
    ],
    'ENDgDO2UOXwysQMjU-Ac6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false
        ],
        'validated' => [
            'has_appointments' => false
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ],
            'appointments' => [
                'exclude_if:has_appointments,false',
                'required',
                'array'
            ],
            'appointments.*.date' => [
                'required',
                'date'
            ],
            'appointments.*.name' => [
                'required',
                'string'
            ]
        ],
        'expandedRules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ]
    ],
    'f1-xSjoxqlfnwV0KxlxGOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false
        ],
        'validated' => [
            'has_appointments' => false
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ],
            'appointments.*.date' => [
                'required',
                'date'
            ],
            'appointments' => [
                'exclude_if:has_appointments,false',
                'required',
                'array'
            ]
        ],
        'expandedRules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ]
    ],
    '_45WtmAvmwlJVYKnuCcJcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'vehicles' => [
                [
                    'type' => 'car',
                    'wheels' => 4
                ],
                [
                    'type' => 'boat'
                ]
            ]
        ],
        'validated' => [
            'vehicles' => [
                [
                    'type' => 'car',
                    'wheels' => 4
                ],
                [
                    'type' => 'boat'
                ]
            ]
        ],
        'rules' => [
            'vehicles.*.type' => 'required|in:car,boat',
            'vehicles.*.wheels' => 'exclude_if:vehicles.*.type,boat|required|numeric'
        ],
        'expandedRules' => [
            'vehicles.0.type' => [
                'required',
                'in:car,boat'
            ],
            'vehicles.1.type' => [
                'required',
                'in:car,boat'
            ],
            'vehicles.0.wheels' => [
                'exclude_if:vehicles.*.type,boat',
                'required',
                'numeric'
            ]
        ]
    ],
    't7-5SRGgz5J8AeOC5tePXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'vehicles' => [
                [
                    'type' => 'car',
                    'wheels' => [
                        [
                            'color' => 'red',
                            'shape' => 'square'
                        ],
                        [
                            'color' => 'blue'
                        ],
                        [
                            'color' => 'red',
                            'shape' => 'round',
                            'junk' => 'no rule, still present'
                        ],
                        [
                            'color' => 'blue'
                        ]
                    ]
                ],
                [
                    'type' => 'boat'
                ]
            ]
        ],
        'validated' => [
            'vehicles' => [
                [
                    'type' => 'car',
                    'wheels' => [
                        [
                            'color' => 'red',
                            'shape' => 'square'
                        ],
                        [
                            'color' => 'blue'
                        ],
                        [
                            'color' => 'red',
                            'shape' => 'round',
                            'junk' => 'no rule, still present'
                        ],
                        [
                            'color' => 'blue'
                        ]
                    ]
                ],
                [
                    'type' => 'boat'
                ]
            ]
        ],
        'rules' => [
            'vehicles.*.type' => 'required|in:car,boat',
            'vehicles.*.wheels' => 'exclude_if:vehicles.*.type,boat|required|array',
            'vehicles.*.wheels.*.color' => 'required|in:red,blue',
            'vehicles.*.wheels.*.shape' => 'exclude_unless:vehicles.*.wheels.*.color,red|required|in:square,round'
        ],
        'expandedRules' => [
            'vehicles.0.type' => [
                'required',
                'in:car,boat'
            ],
            'vehicles.1.type' => [
                'required',
                'in:car,boat'
            ],
            'vehicles.0.wheels' => [
                'exclude_if:vehicles.*.type,boat',
                'required',
                'array'
            ],
            'vehicles.0.wheels.0.color' => [
                'required',
                'in:red,blue'
            ],
            'vehicles.0.wheels.1.color' => [
                'required',
                'in:red,blue'
            ],
            'vehicles.0.wheels.2.color' => [
                'required',
                'in:red,blue'
            ],
            'vehicles.0.wheels.3.color' => [
                'required',
                'in:red,blue'
            ],
            'vehicles.0.wheels.0.shape' => [
                'exclude_unless:vehicles.*.wheels.*.color,red',
                'required',
                'in:square,round'
            ],
            'vehicles.0.wheels.2.shape' => [
                'exclude_unless:vehicles.*.wheels.*.color,red',
                'required',
                'in:square,round'
            ]
        ]
    ],
    'v-6o4TNFq87FfmRNmZRaXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExclude:7184',
        'data' => [
            'has_appointment' => false
        ],
        'validated' => [
            'has_appointment' => false
        ],
        'rules' => [
            'has_appointment' => [
                'required',
                'bool'
            ],
            'appointment_date' => [
                'exclude'
            ]
        ],
        'expandedRules' => [
            'has_appointment' => [
                'required',
                'bool'
            ]
        ]
    ],
    '9x_znbNO2uqxWgvdY45zUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7207',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'rules' => [
            'users' => 'array',
            'users.*.name' => 'string'
        ],
        'expandedRules' => [
            'users' => [
                'array'
            ],
            'users.0.name' => [
                'string'
            ]
        ]
    ],
    'phS7PA86OM-ch5i96FGzcQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7216',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed'
                ]
            ]
        ],
        'rules' => [
            'users' => 'array',
            'users.*.name' => 'string'
        ],
        'expandedRules' => [
            'users' => [
                'array'
            ],
            'users.0.name' => [
                'string'
            ]
        ]
    ],
    'FmPzu4GZVEXMdj9y1zIk7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7225',
        'data' => [
            'admin' => [
                'name' => 'Mohamed',
                'location' => 'cairo'
            ],
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'validated' => [
            'admin' => [
                'name' => 'Mohamed'
            ],
            'users' => [
                [
                    'name' => 'Mohamed'
                ]
            ]
        ],
        'rules' => [
            'admin' => 'array',
            'admin.name' => 'string',
            'users' => 'array',
            'users.*.name' => 'string'
        ],
        'expandedRules' => [
            'admin' => [
                'array'
            ],
            'admin.name' => [
                'string'
            ],
            'users' => [
                'array'
            ],
            'users.0.name' => [
                'string'
            ]
        ]
    ],
    'dJo7JSQaZ4JlFay7gqWveA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7234',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'rules' => [
            'users' => 'array'
        ],
        'expandedRules' => [
            'users' => [
                'array'
            ]
        ]
    ],
    '0GBF_yYD5Xn5FDsWWDyUCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7243',
        'data' => [
            'users' => [
                'mohamed',
                'zain'
            ]
        ],
        'validated' => [
            'users' => [
                'mohamed',
                'zain'
            ]
        ],
        'rules' => [
            'users' => 'array',
            'users.*' => 'string'
        ],
        'expandedRules' => [
            'users' => [
                'array'
            ],
            'users.0' => [
                'string'
            ],
            'users.1' => [
                'string'
            ]
        ]
    ],
    '81aHHoP-m6YCqOSqXGZCUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7252',
        'data' => [
            'users' => [
                'admins' => [
                    [
                        'name' => 'mohamed',
                        'job' => 'dev'
                    ]
                ],
                'unvalidated' => 'foobar'
            ]
        ],
        'validated' => [
            'users' => [
                'admins' => [
                    [
                        'name' => 'mohamed'
                    ]
                ]
            ]
        ],
        'rules' => [
            'users' => 'array',
            'users.admins' => 'array',
            'users.admins.*.name' => 'string'
        ],
        'expandedRules' => [
            'users' => [
                'array'
            ],
            'users.admins' => [
                'array'
            ],
            'users.admins.0.name' => [
                'string'
            ]
        ]
    ],
    'O8PNA-KVc-7Cp2SmAfZA6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7261',
        'data' => [
            'users' => [
                1,
                2,
                3
            ]
        ],
        'validated' => [
            'users' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'users' => 'array|max:10'
        ],
        'expandedRules' => [
            'users' => [
                'array',
                'max:10'
            ]
        ]
    ],
    'RdC6knJ3BPWVa0GX9Qq-yA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7272',
        'data' => [
            'cat' => 'Felix'
        ],
        'validated' => [
            'cat' => 'Felix'
        ],
        'rules' => [
            'cat' => 'required|string',
            'mouse' => 'exclude_unless:cat,Tom|required|string'
        ],
        'expandedRules' => [
            'cat' => [
                'required',
                'string'
            ]
        ]
    ],
    'MGow35Fk3_eC1aJaadtGcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7280',
        'data' => [
            'cat' => 'Felix'
        ],
        'validated' => [
            'cat' => 'Felix'
        ],
        'rules' => [
            'cat' => 'required|string',
            'mouse' => 'exclude_unless:cat,Tom|required|string'
        ],
        'expandedRules' => [
            'cat' => [
                'required',
                'string'
            ]
        ]
    ],
    'gjeI1LD6CarT5dj1ghmICw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7288',
        'data' => [
            'cat' => 'Tom',
            'mouse' => 'Jerry'
        ],
        'validated' => [
            'cat' => 'Tom',
            'mouse' => 'Jerry'
        ],
        'rules' => [
            'cat' => 'required|string',
            'mouse' => 'exclude_unless:cat,Tom|required|string'
        ],
        'expandedRules' => [
            'cat' => [
                'required',
                'string'
            ],
            'mouse' => [
                'exclude_unless:cat,Tom',
                'required',
                'string'
            ]
        ]
    ],
    'DFTCsVoSmDblLirijRpK-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7304',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'nullable',
            'bar' => 'exclude_unless:foo,null'
        ],
        'expandedRules' => [
            'foo' => [
                'nullable'
            ]
        ]
    ],
    '016oZehKmE8FNRL2FBO_1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7314',
        'data' => [
            'bar' => 'Hello'
        ],
        'validated' => [
            'bar' => 'Hello'
        ],
        'rules' => [
            'bar' => 'exclude_unless:foo,null'
        ],
        'expandedRules' => [
            'bar' => [
                'exclude_unless:foo,null'
            ]
        ]
    ],
    'XH6v1ZQ0dRuLy1I3O5sSBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeValuesAreReallyRemoved:7340',
        'data' => [
            'cat' => 'Tom'
        ],
        'validated' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => 'required|string',
            'mouse' => 'exclude_if:cat,Tom|required|string'
        ],
        'expandedRules' => [
            'cat' => [
                'required',
                'string'
            ]
        ]
    ],
    'F5IpPpW1EM6yDV7qMG0msg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeWithValuesAreReallyRemoved:7369',
        'data' => [
            'cat' => 'Tom'
        ],
        'validated' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => 'string',
            'mouse' => 'string|exclude_with:cat'
        ],
        'expandedRules' => [
            'cat' => [
                'string'
            ]
        ]
    ],
    'EjDXhPyyi-1tnDtcU8_Uww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWhenHasKeys:7443',
        'data' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ],
        'validated' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ],
        'rules' => [
            'baz' => [
                'array',
                'required_array_keys:foo,fee,laa'
            ]
        ],
        'expandedRules' => [
            'baz' => [
                'array',
                'required_array_keys:foo,fee,laa'
            ]
        ]
    ],
    '055fVZ6ksJDXdEWXpZsYCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWithPartialMatch:7466',
        'data' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ],
        'validated' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ],
        'rules' => [
            'baz' => [
                'array',
                'required_array_keys:foo,fee'
            ]
        ],
        'expandedRules' => [
            'baz' => [
                'array',
                'required_array_keys:foo,fee'
            ]
        ]
    ]
];