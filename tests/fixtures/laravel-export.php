<?php /* laravel commit 99263998eb */ return [
    'C2z_3riYkMjs6AFmhztuEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationExistsRuleTest::testItChoosesValidRecordsUsingWhereNotRule:223',
        'data' => [
            'id' => 4
        ],
        'rules' => [
            'id' => [
                'exists:users,id,type,"!baz"'
            ]
        ],
        'validated' => [
            'id' => 4
        ]
    ],
    'A8wFHDkLMG0WCAhxbuvArA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationFactoryTest::testValidateMethodCanBeCalledPublicly:108',
        'data' => [
            'bar' => [
                'baz'
            ]
        ],
        'rules' => [
            'bar' => [
                'foo'
            ]
        ],
        'validated' => [
            'bar' => [
                'baz'
            ]
        ]
    ],
    'i5Rt7KI5tMMxeprrYf-fPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnNestedArrays:112',
        'data' => [
            'foo' => [
                'bar' => [
                    'baz' => 'nonEmpty'
                ]
            ]
        ],
        'rules' => [
            'foo.bar.baz' => [
                'sometimes',
                'required'
            ]
        ],
        'validated' => [
            'foo' => [
                'bar' => [
                    'baz' => 'nonEmpty'
                ]
            ]
        ]
    ],
    'Zat4hAWAAk-ndVznCuyWBw' => [
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
        'rules' => [
            'foo' => [
                'sometimes',
                'required',
                'between:5,10'
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
        ]
    ],
    'qdyPm3-Jrc4xg0qaRoll4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntThrowOnPass:161',
        'data' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => [
                'required'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    '0AK2DG310A3cd2DrFeQ8gA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUsingNestedValidationRulesPasses:223',
        'data' => [
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
        ],
        'validated' => [
            'items' => [
                [
                    '|name' => '|ABC123'
                ]
            ]
        ]
    ],
    'o2Q97nq2T_kK9P6pOs6z_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:242',
        'data' => [
            'x' => ''
        ],
        'rules' => [
            'x' => [
                'size:10',
                'array',
                'integer',
                'min:5'
            ]
        ],
        'validated' => [
            'x' => ''
        ]
    ],
    'DXPDxYywwA52wfGS2f9vIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:250',
        'data' => [
            'x' => ''
        ],
        'rules' => [
            'x' => [
                'array'
            ]
        ],
        'validated' => [
            'x' => ''
        ]
    ],
    'LQ8D6NK284XNqmlvyM5jPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullable:277',
        'data' => [
            'x' => null,
            'y' => null,
            'z' => null,
            'a' => null,
            'b' => null
        ],
        'rules' => [
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
        ],
        'validated' => [
            'x' => null,
            'y' => null,
            'z' => null,
            'a' => null,
            'b' => null
        ]
    ],
    'SuO7HayipaJYjoLBEV9cng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullableMakesNoDifferenceIfImplicitRuleExists:302',
        'data' => [
            'x' => null,
            'y' => null
        ],
        'rules' => [
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
        ],
        'validated' => [
            'x' => null,
            'y' => null
        ]
    ],
    'EGvtsdWfW5VTHAzeZV40Dg' => [
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
        'rules' => [
            'input.0.name' => [
                'required'
            ],
            'input.1.name' => [
                'required'
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
        ]
    ],
    'RQ9nqpPLVlM2RR7BZncnXw' => [
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
        'rules' => [
            'input.0.name' => [
                'required'
            ],
            'input.1.name' => [
                'required'
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
        ]
    ],
    'ZtGfSKXpb0HVOGUw6pIuQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArray:935',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => [
                'Array'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ]
    ],
    'h0kbEUJ-1mfVB5JJYdbDFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:947',
        'data' => [
            'user' => [
                'name' => 'Duilio',
                'username' => 'duilio'
            ]
        ],
        'rules' => [
            'user' => [
                'array:name,username'
            ]
        ],
        'validated' => [
            'user' => [
                'name' => 'Duilio',
                'username' => 'duilio'
            ]
        ]
    ],
    'qvKioKhHUa81dz-TSV53kw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:951',
        'data' => [
            'user' => [
                'name' => 'Duilio'
            ]
        ],
        'rules' => [
            'user' => [
                'array:name,username'
            ]
        ],
        'validated' => [
            'user' => [
                'name' => 'Duilio'
            ]
        ]
    ],
    'gJ294SxytBDvBkkCOIInqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1025',
        'data' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => [
                'current_password'
            ]
        ],
        'validated' => [
            'password' => 'foo'
        ]
    ],
    't_c79fGOQlkpQVhTx5OCdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1049',
        'data' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => [
                'current_password:custom'
            ]
        ],
        'validated' => [
            'password' => 'foo'
        ]
    ],
    '-a-wKi9U6f8SsS9We8kjmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1062',
        'data' => [
            'foo' => [
                [
                    'id' => 1
                ],
                []
            ]
        ],
        'rules' => [
            'foo.0.id' => [
                'filled'
            ],
            'foo.1.id' => [
                'filled'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ]
            ]
        ]
    ],
    'waury10AkLx3uDz1r7KfkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1102',
        'data' => [
            'name' => null
        ],
        'rules' => [
            'name' => [
                'present',
                'nullable'
            ]
        ],
        'validated' => [
            'name' => null
        ]
    ],
    '7Tki9ozMWqLB2Cpal7GwzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1105',
        'data' => [
            'name' => ''
        ],
        'rules' => [
            'name' => [
                'present'
            ]
        ],
        'validated' => [
            'name' => ''
        ]
    ],
    'nPFxPe7_JdpA9WfVX_Bxyw' => [
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
        'rules' => [
            'foo.0.id' => [
                'present'
            ],
            'foo.1.id' => [
                'present'
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
        ]
    ],
    'lZ2XR3Jwz4DrAzZpH1gUlQ' => [
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
        'rules' => [
            'foo.0.id' => [
                'present'
            ],
            'foo.1.id' => [
                'present'
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
        ]
    ],
    'peI-_tZSPzoAX24Wy2_1RA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1130',
        'data' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => [
                'Required'
            ]
        ],
        'validated' => [
            'name' => 'foo'
        ]
    ],
    'EeMl5AZlK5tuHDaPc9MPag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1138',
        'data' => [
            'name' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'name' => [
                'Required'
            ]
        ],
        'validated' => [
            'name' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'QyudaIUXDRHqE-OdWaZL3A' => [
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
        'rules' => [
            'files.0' => [
                'Required'
            ],
            'files.1' => [
                'Required'
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
        ]
    ],
    'aSSx1L5Dujx9w-bh5kfaFg' => [
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
        'rules' => [
            'files' => [
                'Required'
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
        ]
    ],
    'H1_vw6i9wtk9goFhboeb-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1165',
        'data' => [
            'first' => 'Taylor',
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => [
                'required_with:first'
            ]
        ],
        'validated' => [
            'last' => 'Otwell'
        ]
    ],
    'jx-m7C4BD-vtF-H-J1F_nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1169',
        'data' => [
            'file' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })(),
            'foo' => ''
        ],
        'rules' => [
            'foo' => [
                'required_with:file'
            ]
        ],
        'validated' => [
            'foo' => ''
        ]
    ],
    'ZBbpL4Dl3YSQdMelXpyLGQ' => [
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
        'rules' => [
            'foo' => [
                'required_with:file'
            ]
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'oXsyYhNU8pFlvFVIPbN-Uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1199',
        'data' => [
            'first' => 'Taylor',
            'last' => ''
        ],
        'rules' => [
            'last' => [
                'required_without:first'
            ]
        ],
        'validated' => [
            'last' => ''
        ]
    ],
    '4IvNyWu6IWdJ9N9CJfHcYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1208',
        'data' => [
            'first' => 'Taylor',
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => [
                'required_without:first'
            ]
        ],
        'validated' => [
            'last' => 'Otwell'
        ]
    ],
    '7J2Tw2G3942IiNnhY4zvIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1211',
        'data' => [
            'last' => 'Otwell'
        ],
        'rules' => [
            'last' => [
                'required_without:first'
            ]
        ],
        'validated' => [
            'last' => 'Otwell'
        ]
    ],
    '-wCZjOxB2vtAuXngAHrfdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1223',
        'data' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'foo' => [
                'required_without:file'
            ]
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'HNoKGCgOwVRxDQfrguautA' => [
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
        'rules' => [
            'foo' => [
                'required_without:file'
            ]
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'zXryyQL7YsDo4txGSjJf9A' => [
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
        'rules' => [
            'foo' => [
                'required_without:file'
            ]
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'JInC5DB-001mnmTpa9MWdg' => [
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
        'rules' => [
            'foo' => [
                'required_without:file'
            ]
        ],
        'validated' => [
            'foo' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    '0WAqShQPiM4eIQZ4ce0NNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1269',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ]
    ],
    'pJdiAn9pL3d3d6z-3Ielkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1272',
        'data' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ]
    ],
    'a4Hy55v7Yj9ABGuses0PNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1275',
        'data' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ],
        'validated' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ]
    ],
    'GqJKHDjeKnRUpzpYUnNoKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1278',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'rules' => [
            'f1' => [
                'required_without:f2,f3'
            ],
            'f2' => [
                'required_without:f1,f3'
            ],
            'f3' => [
                'required_without:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ]
    ],
    'pNmrxCDKbj4W1JGntiR3Zg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1295',
        'data' => [
            'f1' => 'foo'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo'
        ]
    ],
    '42GpugKdY-siDcGEI268XQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1298',
        'data' => [
            'f2' => 'foo'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f2' => 'foo'
        ]
    ],
    'Dm3vDztpMvUL_TlSIkxvEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1301',
        'data' => [
            'f3' => 'foo'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f3' => 'foo'
        ]
    ],
    '0DtDF1NeuXroyAISpcmMyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1304',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar'
        ]
    ],
    'R4VOInlO_QYIyyMEhvIqXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1307',
        'data' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f3' => 'bar'
        ]
    ],
    'eUtuBTD9Nir3sKbFkefgcQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1310',
        'data' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f2' => 'foo',
            'f3' => 'bar'
        ]
    ],
    'YfBuQvFLnEL1kP9WTa1yzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1313',
        'data' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ],
        'rules' => [
            'f1' => [
                'required_without_all:f2,f3'
            ],
            'f2' => [
                'required_without_all:f1,f3'
            ],
            'f3' => [
                'required_without_all:f1,f2'
            ]
        ],
        'validated' => [
            'f1' => 'foo',
            'f2' => 'bar',
            'f3' => 'baz'
        ]
    ],
    'x50AT-bnvX91FIu4-8lPvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1324',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => [
                'required_if:first,taylor'
            ]
        ],
        'validated' => [
            'last' => 'otwell'
        ]
    ],
    'w9ZPuxSfbtz5wSkVmDluhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1328',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => [
                'required_if:first,taylor,dayle'
            ]
        ],
        'validated' => [
            'last' => 'otwell'
        ]
    ],
    'yvKUiwkVfv56-pc-uwaEjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1332',
        'data' => [
            'first' => 'dayle',
            'last' => 'rees'
        ],
        'rules' => [
            'last' => [
                'required_if:first,taylor,dayle'
            ]
        ],
        'validated' => [
            'last' => 'rees'
        ]
    ],
    'qRgIcB9Yhg9c1HalFwFGBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1396',
        'data' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => [
                'nullable',
                'boolean'
            ],
            'baz' => [
                'nullable',
                'required_if:foo,false'
            ]
        ],
        'validated' => [
            'foo' => null
        ]
    ],
    'uEryqaA1nZvsb0c7k7kkJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1428',
        'data' => [
            'first' => 'sven',
            'last' => 'wittevrongel'
        ],
        'rules' => [
            'last' => [
                'required_unless:first,taylor'
            ]
        ],
        'validated' => [
            'last' => 'wittevrongel'
        ]
    ],
    'PwkjIlNnUQpFWSG4koiPFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1448',
        'data' => [
            'bar' => '1'
        ],
        'rules' => [
            'bar' => [
                'required_unless:foo,true'
            ]
        ],
        'validated' => [
            'bar' => '1'
        ]
    ],
    'XmU0AsUpPv_oB4ujkOHAQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:1536',
        'data' => [
            'foo' => true,
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => [
                'prohibited_if:foo,false'
            ]
        ],
        'validated' => [
            'bar' => 'baz'
        ]
    ],
    '0qUZouMQgFHd2fs9bKvdTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1558',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => [
                'prohibited_unless:first,taylor'
            ]
        ],
        'validated' => [
            'last' => 'otwell'
        ]
    ],
    'MEqP53PiORw7Dw35gaymSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1566',
        'data' => [
            'first' => 'taylor',
            'last' => 'otwell'
        ],
        'rules' => [
            'last' => [
                'prohibited_unless:first,taylor,jess'
            ]
        ],
        'validated' => [
            'last' => 'otwell'
        ]
    ],
    '8j3jhytJYwDFAENqhfMEow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1570',
        'data' => [
            'first' => 'jess',
            'last' => 'archer'
        ],
        'rules' => [
            'last' => [
                'prohibited_unless:first,taylor,jess'
            ]
        ],
        'validated' => [
            'last' => 'archer'
        ]
    ],
    'JOwA6BKVDGvLi2ZOgov6iA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1574',
        'data' => [
            'foo' => false,
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => [
                'prohibited_unless:foo,false'
            ]
        ],
        'validated' => [
            'bar' => 'baz'
        ]
    ],
    'ccPzIeE93-V6AeqJJUcEGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1616',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'prohibits:emails'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'JNhzPASE0-YKpLTuqioGWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1620',
        'data' => [
            'email' => 'foo',
            'other' => 'foo'
        ],
        'rules' => [
            'email' => [
                'prohibits:email_address,emails'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'o0MQb4CkyYOy6N9qNWF-HA' => [
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
        'rules' => [
            'foo.0' => [
                'in_array:bar.*'
            ],
            'foo.1' => [
                'in_array:bar.*'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2
            ]
        ]
    ],
    '-c9zjBmWbwHpm8xZGwnnlQ' => [
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
        'rules' => [
            'foo.0.bar_id' => [
                'in_array:bar.*.id'
            ],
            'foo.1.bar_id' => [
                'in_array:bar.*.id'
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
        ]
    ],
    'ecLCJw7fvmfwixXqnK2ICw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:1709',
        'data' => [
            'password' => 'foo',
            'password_confirmation' => 'foo'
        ],
        'rules' => [
            'password' => [
                'Confirmed'
            ]
        ],
        'validated' => [
            'password' => 'foo'
        ]
    ],
    'qJc50uuqaiPap4zaPM4G3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:1725',
        'data' => [
            'foo' => 'bar',
            'baz' => 'bar'
        ],
        'rules' => [
            'foo' => [
                'Same:baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    '5TVAgBgvPEk0PeUTGfjvzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:1731',
        'data' => [
            'foo' => null,
            'baz' => null
        ],
        'rules' => [
            'foo' => [
                'Same:baz'
            ]
        ],
        'validated' => [
            'foo' => null
        ]
    ],
    'GDGqMbtTsnUUkn6JPKOOAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1738',
        'data' => [
            'foo' => 'bar',
            'baz' => 'boom'
        ],
        'rules' => [
            'foo' => [
                'Different:baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    'XstJHB-g_WQv7aIDiNF-gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1741',
        'data' => [
            'foo' => 'bar',
            'baz' => null
        ],
        'rules' => [
            'foo' => [
                'Different:baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    'rQn5koPtxD9wOtIRRTFikQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1744',
        'data' => [
            'foo' => 'bar'
        ],
        'rules' => [
            'foo' => [
                'Different:baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    'rix3Y8fxWe1AT13K9VYBDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1750',
        'data' => [
            'foo' => '1e2',
            'baz' => '100'
        ],
        'rules' => [
            'foo' => [
                'Different:baz'
            ]
        ],
        'validated' => [
            'foo' => '1e2'
        ]
    ],
    'FFMxyxCgBCzC6r4JIA7eKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1753',
        'data' => [
            'foo' => 'bar',
            'fuu' => 'baa',
            'baz' => 'boom'
        ],
        'rules' => [
            'foo' => [
                'Different:fuu,baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    'MIGooyKh52Y9CRyeVhPAeA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:1756',
        'data' => [
            'foo' => 'bar',
            'baz' => 'boom'
        ],
        'rules' => [
            'foo' => [
                'Different:fuu,baz'
            ]
        ],
        'validated' => [
            'foo' => 'bar'
        ]
    ],
    '_SHdd6oYc8S1VdGNCPZZ3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1766',
        'data' => [
            'lhs' => 15,
            'rhs' => 10
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15
        ]
    ],
    'vkR-2DoCS6c4JEp1Jl3U-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1772',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 10
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15.0
        ]
    ],
    'D_yOJ-xTwQBn2WQ6PB7zZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1775',
        'data' => [
            'lhs' => '15',
            'rhs' => 10
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:rhs'
            ]
        ],
        'validated' => [
            'lhs' => '15'
        ]
    ],
    'd0PJmb6S2aDatUTULHlCNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1781',
        'data' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ],
        'validated' => [
            'lhs' => 15.0
        ]
    ],
    'rZJBxsYTlRcR9W-hTWkPhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1787',
        'data' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ],
        'validated' => [
            'lhs' => '15'
        ]
    ],
    'lUf_zBuhR9vNkjcPlFsogQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1790',
        'data' => [
            'lhs' => 'longer string',
            'rhs' => 'string'
        ],
        'rules' => [
            'lhs' => [
                'gt:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 'longer string'
        ]
    ],
    'QGP25m_cQio2HE_E0YRDVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:1803',
        'data' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gt:10'
            ]
        ],
        'validated' => [
            'lhs' => 15
        ]
    ],
    'Oplbt_avRbDUKoS_A1Ebuw' => [
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
        'rules' => [
            'lhs' => [
                'lt:rhs'
            ]
        ],
        'validated' => [
            'lhs' => [
                'string'
            ]
        ]
    ],
    'I0UEmlm8t7wiUDQ7O2778g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1905',
        'data' => [
            'lhs' => 15,
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15
        ]
    ],
    'SsBq20io2UH3ej6pIa8k1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1911',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15.0
        ]
    ],
    'JDMysAWAmS8wENQqve65jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1914',
        'data' => [
            'lhs' => '15',
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => '15'
        ]
    ],
    'DYMdMMkGFL-97jynPhyMVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1920',
        'data' => [
            'lhs' => 15.0
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ],
        'validated' => [
            'lhs' => 15.0
        ]
    ],
    'SO0bWeHy_4kmhAm1-mcoXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1923',
        'data' => [
            'lhs' => '15'
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ],
        'validated' => [
            'lhs' => '15'
        ]
    ],
    '3IG7CxXSyyx0f6vbbgvHoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1926',
        'data' => [
            'lhs' => 'longer string',
            'rhs' => 'string'
        ],
        'rules' => [
            'lhs' => [
                'gte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 'longer string'
        ]
    ],
    'dpxwL8DK2tlT4gLRFemimQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:1939',
        'data' => [
            'lhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'gte:15'
            ]
        ],
        'validated' => [
            'lhs' => 15
        ]
    ],
    '14-smHeWRAzx4guJbRlMZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1946',
        'data' => [
            'lhs' => 15,
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15
        ]
    ],
    'KWYMrfIaq4ex3K662NMWog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1952',
        'data' => [
            'lhs' => 15.0,
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => 15.0
        ]
    ],
    'Wawwwci0aZYeIfdkRQqMcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:1955',
        'data' => [
            'lhs' => '15',
            'rhs' => 15
        ],
        'rules' => [
            'lhs' => [
                'numeric',
                'lte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => '15'
        ]
    ],
    'ZoW8vWNdvw0OrUnOnAvtsw' => [
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
        'rules' => [
            'lhs' => [
                'lte:rhs'
            ]
        ],
        'validated' => [
            'lhs' => [
                'string'
            ]
        ]
    ],
    'VrzcIXWTEqBdX-1ThkAlRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2011',
        'data' => [
            'foo' => 'yes'
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => 'yes'
        ]
    ],
    'LB3AjQ9_A1BbrsGTt9NqEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2014',
        'data' => [
            'foo' => 'on'
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => 'on'
        ]
    ],
    'QJscmXMsJ4nzC224CjusfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2017',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'rDMLb1ifHahSmgL_xAX4DA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2020',
        'data' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => 1
        ]
    ],
    'EYeCZcfZQvubNPZay1eJJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2023',
        'data' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => true
        ]
    ],
    'xLH11HqcatDtDd6hffxpNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2026',
        'data' => [
            'foo' => 'true'
        ],
        'rules' => [
            'foo' => [
                'Accepted'
            ]
        ],
        'validated' => [
            'foo' => 'true'
        ]
    ],
    'FaUzxdVrGHjk-m0afS6VvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2033',
        'data' => [
            'foo' => 'no',
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ],
        'validated' => [
            'bar' => 'baz'
        ]
    ],
    'wN3f-HnBhU-piYfwe4FwJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2036',
        'data' => [
            'foo' => 'yes',
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ],
        'validated' => [
            'bar' => 'baz'
        ]
    ],
    'YTt3OZBaIIUqdQfM7aoAMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2039',
        'data' => [
            'foo' => 'no',
            'bar' => ''
        ],
        'rules' => [
            'bar' => [
                'required_if_accepted:foo'
            ]
        ],
        'validated' => [
            'bar' => ''
        ]
    ],
    'AacK14dB2FnhZdVrgMr0NQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2070',
        'data' => [
            'foo' => 'yes',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'yes'
        ]
    ],
    'qShw8HjIo0WhjtUdfq5BNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2073',
        'data' => [
            'foo' => 'on',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'on'
        ]
    ],
    'IpiHPl5PLU8eGXONOp8nTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2076',
        'data' => [
            'foo' => '1',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'obQ7yA5BkaIIi6tV71v_ag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2079',
        'data' => [
            'foo' => 1,
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 1
        ]
    ],
    '-kR0T4PIOhbOMasjTCauEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2082',
        'data' => [
            'foo' => true,
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => true
        ]
    ],
    'Fzr9bL27NK94vuEMn_Gm_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2085',
        'data' => [
            'foo' => 'true',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'accepted_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'true'
        ]
    ],
    'GA8_AoUZGUOLtjarR65mHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2147',
        'data' => [
            'foo' => 'no'
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => 'no'
        ]
    ],
    'Q28hF4jukXUhF867DhJB_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2150',
        'data' => [
            'foo' => 'off'
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => 'off'
        ]
    ],
    'UwBQoo4qfhLPckSJLKoaJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2153',
        'data' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => '0'
        ]
    ],
    'lRPNNp6OsBREEsfSEUow7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2156',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'hjj_J-29Do7VgkqwMwx8PQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2159',
        'data' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => false
        ]
    ],
    'IFaRUZ3L93RU6Se1M-aZHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2162',
        'data' => [
            'foo' => 'false'
        ],
        'rules' => [
            'foo' => [
                'Declined'
            ]
        ],
        'validated' => [
            'foo' => 'false'
        ]
    ],
    'D22-HihLzsc56VipboKlxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2190',
        'data' => [
            'foo' => 'no',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'no'
        ]
    ],
    '-R48RRilP3iwWpx6LCJwYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2193',
        'data' => [
            'foo' => 'off',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'off'
        ]
    ],
    'JV6kc1AjIxL3LMnozItFmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2196',
        'data' => [
            'foo' => 0,
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'U6WzwQQI0016POfXa5zgEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2199',
        'data' => [
            'foo' => '0',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => '0'
        ]
    ],
    'DMF8dVVfTaQxy-EnwuV0OQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2202',
        'data' => [
            'foo' => false,
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => false
        ]
    ],
    'L6lWCrgyJfEx6uDfovjrtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2205',
        'data' => [
            'foo' => 'false',
            'bar' => 'aaa'
        ],
        'rules' => [
            'foo' => [
                'declined_if:bar,aaa'
            ]
        ],
        'validated' => [
            'foo' => 'false'
        ]
    ],
    'un49QwcIhvZWPiHjg8EGqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2247',
        'data' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => [
                'ends_with:world'
            ]
        ],
        'validated' => [
            'x' => 'hello world'
        ]
    ],
    'PHJzaETWGuCTJbkcTDe9bQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2251',
        'data' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => [
                'ends_with:world,hello'
            ]
        ],
        'validated' => [
            'x' => 'hello world'
        ]
    ],
    'f1xE9aQURq1ZvAvAJvViOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntEndWith:2270',
        'data' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => [
                'doesnt_end_with:hello'
            ]
        ],
        'validated' => [
            'x' => 'hello world'
        ]
    ],
    'HXIqDa-JyaYOY2W7cnjVUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:2281',
        'data' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => [
                'starts_with:hello'
            ]
        ],
        'validated' => [
            'x' => 'hello world'
        ]
    ],
    'hqSJSD2b_xhLCVare9MZFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:2289',
        'data' => [
            'x' => 'hello world'
        ],
        'rules' => [
            'x' => [
                'starts_with:world,hello'
            ]
        ],
        'validated' => [
            'x' => 'hello world'
        ]
    ],
    'SXCmZ2Ide_qo5G2L4VS9Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntStartWith:2308',
        'data' => [
            'x' => 'world hello'
        ],
        'rules' => [
            'x' => [
                'doesnt_start_with:hello'
            ]
        ],
        'validated' => [
            'x' => 'world hello'
        ]
    ],
    'nok4I4jqeGnkAKbKSeZpCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateString:2319',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                'string'
            ]
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ]
    ],
    'zWTgllpalakTpjqytIP1vA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:2334',
        'data' => [
            'foo' => '[]'
        ],
        'rules' => [
            'foo' => [
                'json'
            ]
        ],
        'validated' => [
            'foo' => '[]'
        ]
    ],
    'ZBxeWmmkMqScYqK0uk63wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:2338',
        'data' => [
            'foo' => '{"name":"John","age":"34"}'
        ],
        'rules' => [
            'foo' => [
                'json'
            ]
        ],
        'validated' => [
            'foo' => '{"name":"John","age":"34"}'
        ]
    ],
    '7gQgR8pssg6j6VTNyu5MRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2364',
        'data' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => false
        ]
    ],
    'ahArUEu_gUWgiMaqTWknOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2367',
        'data' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => true
        ]
    ],
    'wh-kL2UMWB3yzFbpWCOeXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2370',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'FP8AVidOBeKtMMqBgXdMAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2373',
        'data' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => 1
        ]
    ],
    'z3hY4ELXsqP1Bl4jAKBBGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2376',
        'data' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => '0'
        ]
    ],
    'OlzDmuc54SC0vqkIC6G6fQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:2379',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'Boolean'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    's78SyyqkVrOb89O9DBa3dQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2401',
        'data' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => false
        ]
    ],
    '4LwZNRLf1SzKIE1QQDuswA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2404',
        'data' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => true
        ]
    ],
    'DzILjhFbl4cUgIuQsmZGyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2407',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    't9iEUv_Vvu-_HRminjz_kw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2410',
        'data' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => 1
        ]
    ],
    'tPboZqPq2qgJkspep7S2Cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2413',
        'data' => [
            'foo' => '0'
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => '0'
        ]
    ],
    'xOEiGRAN3s69sVYUI_l4IA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:2416',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'Bool'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'mF5-nKvFUPdpZB2nxbGYjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2426',
        'data' => [
            'foo' => '1.23'
        ],
        'rules' => [
            'foo' => [
                'Numeric'
            ]
        ],
        'validated' => [
            'foo' => '1.23'
        ]
    ],
    'SurVouB5Y5jrbfDBPkACfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2429',
        'data' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => [
                'Numeric'
            ]
        ],
        'validated' => [
            'foo' => '-1'
        ]
    ],
    '1q2-gw4VTQFHMYevk7zj6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:2432',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Numeric'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'lQzCeqswSSkExhxq7j5jvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:2445',
        'data' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => [
                'Integer'
            ]
        ],
        'validated' => [
            'foo' => '-1'
        ]
    ],
    'LPV9MVTd2AxPoaWesuxVjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:2448',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Integer'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'sZrbSC-DhR2D2txvqLH6BA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:2461',
        'data' => [
            'foo' => '1.234'
        ],
        'rules' => [
            'foo' => [
                'Decimal:2,3'
            ]
        ],
        'validated' => [
            'foo' => '1.234'
        ]
    ],
    'LSF67HFWOsNqKTKw__qgdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:2510',
        'data' => [
            'foo' => '-1'
        ],
        'rules' => [
            'foo' => [
                'Int'
            ]
        ],
        'validated' => [
            'foo' => '-1'
        ]
    ],
    '1jHP0X0vGkQBnh3ks8s0Kw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:2513',
        'data' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => [
                'Int'
            ]
        ],
        'validated' => [
            'foo' => '1'
        ]
    ],
    'lWfMQunqWc1Dj0DkHFLp4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2520',
        'data' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => [
                'Digits:5'
            ]
        ],
        'validated' => [
            'foo' => '12345'
        ]
    ],
    'etk_REKUrHyHwceK7VVBLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2533',
        'data' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => [
                'digits_between:1,6'
            ]
        ],
        'validated' => [
            'foo' => '12345'
        ]
    ],
    'BRDU6hKiwnS3qr1a1Ucu2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2546',
        'data' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => [
                'min_digits:1'
            ]
        ],
        'validated' => [
            'foo' => '12345'
        ]
    ],
    'gmLfkz1IaH_or5GfFiAZEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:2559',
        'data' => [
            'foo' => '12345'
        ],
        'rules' => [
            'foo' => [
                'max_digits:6'
            ]
        ],
        'validated' => [
            'foo' => '12345'
        ]
    ],
    'E6-p-tkcyuFgsmvT5E3r4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2578',
        'data' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => [
                'Size:3'
            ]
        ],
        'validated' => [
            'foo' => 'anc'
        ]
    ],
    'SaaPFn9PQfcEIXeuLC_utA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2584',
        'data' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Size:3'
            ]
        ],
        'validated' => [
            'foo' => '3'
        ]
    ],
    'ARl27IjtAqsqMXF3MA8Dzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:2587',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => [
                'Array',
                'Size:3'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ]
    ],
    'JZ63P_UgheHTN48rsXWycg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2610',
        'data' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => [
                'Between:3,5'
            ]
        ],
        'validated' => [
            'foo' => 'anc'
        ]
    ],
    'E9Zxehg-FA7VD4W5BaDEjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2613',
        'data' => [
            'foo' => 'ancf'
        ],
        'rules' => [
            'foo' => [
                'Between:3,5'
            ]
        ],
        'validated' => [
            'foo' => 'ancf'
        ]
    ],
    'FWM_UVPDet38BMXDMsGwbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2616',
        'data' => [
            'foo' => 'ancfs'
        ],
        'rules' => [
            'foo' => [
                'Between:3,5'
            ]
        ],
        'validated' => [
            'foo' => 'ancfs'
        ]
    ],
    '0YP32n7GJDIswqyfxPLu7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2623',
        'data' => [
            'foo' => '123'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Between:123,200'
            ]
        ],
        'validated' => [
            'foo' => '123'
        ]
    ],
    'WKdSOkuz7DFeuUMoAPNq6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2627',
        'data' => [
            'foo' => '123'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Between:0,123'
            ]
        ],
        'validated' => [
            'foo' => '123'
        ]
    ],
    'WTVFbBXbX0kSG_onhuJ9dA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2631',
        'data' => [
            'foo' => '0.02'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Between:0.01,0.02'
            ]
        ],
        'validated' => [
            'foo' => '0.02'
        ]
    ],
    'GwK-e9Cqdojmn_75Z9aKAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2634',
        'data' => [
            'foo' => '0.02'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Between:0.01,0.03'
            ]
        ],
        'validated' => [
            'foo' => '0.02'
        ]
    ],
    'UK_1ksddZhNXDzEvelT6ng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2640',
        'data' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Between:1,5'
            ]
        ],
        'validated' => [
            'foo' => '3'
        ]
    ],
    'KurQr09fg434QhL7_ZSLvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:2643',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => [
                'Array',
                'Between:1,5'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ]
    ],
    'BjsFtTrvv50g93jEKP56hw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2667',
        'data' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Min:3'
            ]
        ],
        'validated' => [
            'foo' => '3'
        ]
    ],
    'T4d_mNEHcrPtTKZwKPoU6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2670',
        'data' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => [
                'Min:3'
            ]
        ],
        'validated' => [
            'foo' => 'anc'
        ]
    ],
    'NzMR2lDYVbwtxG6AkpeiPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2681',
        'data' => [
            'foo' => '2.001'
        ],
        'rules' => [
            'foo' => [
                'Min:3'
            ]
        ],
        'validated' => [
            'foo' => '2.001'
        ]
    ],
    '954rxhlB_J7zmTj0j8NbrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2688',
        'data' => [
            'foo' => '5'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Min:3'
            ]
        ],
        'validated' => [
            'foo' => '5'
        ]
    ],
    '3Hafkezy4u7FnDAfTEvsxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:2691',
        'data' => [
            'foo' => [
                1,
                2,
                3,
                4
            ]
        ],
        'rules' => [
            'foo' => [
                'Array',
                'Min:3'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3,
                4
            ]
        ]
    ],
    'p0V4Ps6MthUW6mttN8P6Cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2714',
        'data' => [
            'foo' => 'anc'
        ],
        'rules' => [
            'foo' => [
                'Max:3'
            ]
        ],
        'validated' => [
            'foo' => 'anc'
        ]
    ],
    'AMrGZ0BIRQu878FT5WSTpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2721',
        'data' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Max:3'
            ]
        ],
        'validated' => [
            'foo' => '3'
        ]
    ],
    'nvKjMC3b1wFSVvrMdUZruw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2725',
        'data' => [
            'foo' => '2.001'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Max:3'
            ]
        ],
        'validated' => [
            'foo' => '2.001'
        ]
    ],
    '6di7WX3KzU4dvu2IP2oipg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2732',
        'data' => [
            'foo' => '22'
        ],
        'rules' => [
            'foo' => [
                'Numeric',
                'Max:33'
            ]
        ],
        'validated' => [
            'foo' => '22'
        ]
    ],
    'q1_CZEvcxWzARuiegIIr4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:2735',
        'data' => [
            'foo' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'foo' => [
                'Array',
                'Max:4'
            ]
        ],
        'validated' => [
            'foo' => [
                1,
                2,
                3
            ]
        ]
    ],
    'uxT8w-at6bH4o0UZauajgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'OW9H7TRPJoPe8GroXkKqxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    '7yZzsZXeJoospMeOGvCxHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.1'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'qz1SWxZEKXeOU_tnF9fX2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.1'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'cOgWbaRpJ3uW1bLwCz21sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'YVxpKjWyMx-IBx-y88Tcgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    'PHUTa1Xl32W-bc_kb5O29w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.1'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    '4YArgq4Tr-3OvDproke5Qw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.1'
            ]
        ],
        'validated' => [
            'foo' => 0
        ]
    ],
    '-YbmDJKajgoMs_W9YRdpUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    'PzJV1_9Lbp6WsCc0T-gqDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    'fxlMNucMFP6Ob49cP69TzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    'xBPUasiDLAylA6QwaRr51g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    'kaYJJN7t_h_eXMbQcjb1Kg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 20
        ]
    ],
    'hdhDnfeOsyg-KF9WrWqxvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 20
        ]
    ],
    'GEEe3kfy4Tp6wnN4mETH_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    '9lDR3zpx6scdPKulqsIY1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ],
        'validated' => [
            'foo' => 10
        ]
    ],
    'Ws4l8Fj_5vqWR--cb0v3Iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => -20
        ]
    ],
    'G3a9mPGGEEEwhxeS9Kat2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => -20
        ]
    ],
    'axRIMtPmhjnBZVP3sQd27w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'RPRAqaMiFQhAalYvvCc6ow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'zOzzNZTrQpn0sCywUGHr4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'VERf28BHAWKV6A95jjBBoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-5'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'EEatRmeUkSNBUM-cAMYEHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => -20
        ]
    ],
    '21l4ywpQ1UHmjBYmjkUMGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => -20
        ]
    ],
    'YD871OQhfDmmSaZ8V3iZ9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 20.0
        ]
    ],
    'AaGAeVQMutZm82N1gM4ZJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => 20.0
        ]
    ],
    'Ma2eEYgnJBZkWWo4_t-LYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 10.0
        ]
    ],
    'PQfrWVvHkULZPwugpqBrrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 10.0
        ]
    ],
    'uEfcGzXt46HlBcB3-eo3vg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => -20.0
        ]
    ],
    'y_fKBo7LK0_BF3419r48jg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10'
            ]
        ],
        'validated' => [
            'foo' => -20.0
        ]
    ],
    'TihRBTVp7XGh-D8fjxKx9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'eELHYrBSD4wJghhLJ4RnAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10
        ],
        'rules' => [
            'foo' => [
                'multiple_of:5'
            ]
        ],
        'validated' => [
            'foo' => -10
        ]
    ],
    'erQ6dvIu1NqpSQYMWTevuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 20.0
        ]
    ],
    '7nEdTQJtXk4i5JKA14jfBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 20.0
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10'
            ]
        ],
        'validated' => [
            'foo' => 20.0
        ]
    ],
    'ex6P5sFKCtu2eZ5-8cBQDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'fhM8HhUTUayZoOdnQPLXQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'OOBjXr8jVm_Iq50dxa1oqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'Zexj3AsYtzA0uWEW-YdVmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'pw8LWv9e3aWPu8Kal4YS0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'L_YQwby3a25aRjjCZBctWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'TD2TwJLjJB6jodpyUcLXBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => 31.5
        ]
    ],
    'D6nfrO6KEQwDgN0QBHpDaA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => 31.5
        ]
    ],
    'LsrJogYQ0OfATzqCBhcQ3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'S8nmyWF8BdsRNg_VZSIgKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'dqxWScAHoLQheYctKHJMqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    '3XxaFX6lK-R6PTw-_SRt6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ],
        'validated' => [
            'foo' => 10.5
        ]
    ],
    'GtgeMMchjfCdkwUnEUHk5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => -31.5
        ]
    ],
    'PiH8XUeuZffb1GgtgBX8XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:10.5'
            ]
        ],
        'validated' => [
            'foo' => -31.5
        ]
    ],
    '-jXWoTatitkmTUhN2b4tbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    'bXy3M5Dqz4U5sfZC3WP7wQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    'hXLbKA9xEof_4sPKLQI02A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    '_I6UH5iF65HcZoa9IiYM7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.5'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    'ScOCNjuPvv1R42ShS_B64w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    'lG5OLLCXGRxKMvnEved5Gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -10.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-0.3'
            ]
        ],
        'validated' => [
            'foo' => -10.5
        ]
    ],
    'hrPcBTyberpf3ChBwErDXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ],
        'validated' => [
            'foo' => -31.5
        ]
    ],
    'EOatGt-7vqeKq6cL0IpmUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => -31.5
        ],
        'rules' => [
            'foo' => [
                'multiple_of:-10.5'
            ]
        ],
        'validated' => [
            'foo' => -31.5
        ]
    ],
    'cQwW53L3tigvC3tb2gBoWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 2
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.1'
            ]
        ],
        'validated' => [
            'foo' => 2
        ]
    ],
    'MB5mT4siJ8w-u71U-oSgUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 2
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.1'
            ]
        ],
        'validated' => [
            'foo' => 2
        ]
    ],
    'uqPTCqVv6Xa9wDP5T8X3yQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0.75
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.05'
            ]
        ],
        'validated' => [
            'foo' => 0.75
        ]
    ],
    '4yfBhcadg_oplrT0IoQC5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0.75
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.05'
            ]
        ],
        'validated' => [
            'foo' => 0.75
        ]
    ],
    '3lv6jfgNFAF1obIdRAGxmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2772',
        'data' => [
            'foo' => 0.9
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ],
        'validated' => [
            'foo' => 0.9
        ]
    ],
    'iZ5YzKWau_CLxVwhJkp22Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:2773',
        'data' => [
            'foo' => 0.9
        ],
        'rules' => [
            'foo' => [
                'multiple_of:0.3'
            ]
        ],
        'validated' => [
            'foo' => 0.9
        ]
    ],
    'i8ZMBsp-BT0q6M9zMft_Pg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3048',
        'data' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => [
                'In:foo,baz'
            ]
        ],
        'validated' => [
            'name' => 'foo'
        ]
    ],
    'gDmSmqXwhWHxXlEMi83kgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3054',
        'data' => [
            'name' => [
                'foo',
                'qux'
            ]
        ],
        'rules' => [
            'name' => [
                'Array',
                'In:foo,baz,qux'
            ]
        ],
        'validated' => [
            'name' => [
                'foo',
                'qux'
            ]
        ]
    ],
    '5Qn6f2X60WSzV01Ve0Y59w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3057',
        'data' => [
            'name' => [
                'foo,bar',
                'qux'
            ]
        ],
        'rules' => [
            'name' => [
                'Array',
                'In:"foo,bar",baz,qux'
            ]
        ],
        'validated' => [
            'name' => [
                'foo,bar',
                'qux'
            ]
        ]
    ],
    'oVANTu8egCfkxXh9QthLiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3060',
        'data' => [
            'name' => 'f"o"o'
        ],
        'rules' => [
            'name' => [
                'In:"f""o""o",baz,qux'
            ]
        ],
        'validated' => [
            'name' => 'f"o"o'
        ]
    ],
    'BGWGNP-3I0wIqPkNnRau9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3063',
        'data' => [
            'name' => 'a,b
c,d'
        ],
        'rules' => [
            'name' => [
                'in:"a,b
c,d"'
            ]
        ],
        'validated' => [
            'name' => 'a,b
c,d'
        ]
    ],
    '3xSbpDj8-wabLCrCzOZhOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotIn:3076',
        'data' => [
            'name' => 'foo'
        ],
        'rules' => [
            'name' => [
                'NotIn:bar,baz'
            ]
        ],
        'validated' => [
            'name' => 'foo'
        ]
    ],
    'lOQUvzhzsF7HIX5RxW5ScA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3099',
        'data' => [
            'foo' => [
                '1',
                '11'
            ]
        ],
        'rules' => [
            'foo.0' => [
                'distinct:ignore_case'
            ],
            'foo.1' => [
                'distinct:ignore_case'
            ]
        ],
        'validated' => [
            'foo' => [
                '1',
                '11'
            ]
        ]
    ],
    'flQWEOk9CRVGn4RntpdS3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3102',
        'data' => [
            'foo' => [
                'foo',
                'bar'
            ]
        ],
        'rules' => [
            'foo.0' => [
                'distinct'
            ],
            'foo.1' => [
                'distinct'
            ]
        ],
        'validated' => [
            'foo' => [
                'foo',
                'bar'
            ]
        ]
    ],
    'GahR-fN682O8ZWoYA8w4cQ' => [
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
        'rules' => [
            'foo.bar.id' => [
                'distinct'
            ],
            'foo.baz.id' => [
                'distinct'
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
        ]
    ],
    'E9JwIKZQ4yPYt8L0akF-Jw' => [
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
        'rules' => [
            'foo.bar.id' => [
                'distinct'
            ],
            'foo.baz.id' => [
                'distinct'
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
        ]
    ],
    'uBJd3-XouF4M9rWfZSpKig' => [
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
        'rules' => [
            'foo.bar.id' => [
                'distinct:ignore_case'
            ],
            'foo.baz.id' => [
                'distinct:ignore_case'
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
        ]
    ],
    'JUk-sXkyDgKOswQLam8U9A' => [
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
        'rules' => [
            'foo.0.id' => [
                'distinct'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => 1
                ]
            ]
        ]
    ],
    'o7e3K4Py7ZyO4DGHTbgxDA' => [
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
        'rules' => [
            'foo.0.id' => [
                'distinct'
            ],
            'foo.1.id' => [
                'distinct'
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
        ]
    ],
    'PVroAygwzpdX_tg3W0nDaA' => [
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
        'rules' => [
            'cat.0.prod.0.id' => [
                'distinct'
            ],
            'cat.1.prod.0.id' => [
                'distinct'
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
        ]
    ],
    'lV3BWuaswpJXqEJM9R11Mw' => [
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
        'rules' => [
            'cat.sub.0.prod.0.id' => [
                'distinct'
            ],
            'cat.sub.1.prod.0.id' => [
                'distinct'
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
        ]
    ],
    'sT7UK2Na45Tvh5iVm22iiw' => [
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
        'rules' => [
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
        ]
    ],
    'zdL8oJ1z8wnB-tXOW9ZXWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:3164',
        'data' => [
            'foo' => [
                '0100',
                '100'
            ]
        ],
        'rules' => [
            'foo.0' => [
                'distinct:strict'
            ],
            'foo.1' => [
                'distinct:strict'
            ]
        ],
        'validated' => [
            'foo' => [
                '0100',
                '100'
            ]
        ]
    ],
    'KcHkwDWB3wKfGMR9vqnhGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:3198',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'Unique:users'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    '7Jh2BdlS5mn88pE_ERXWdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:3205',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'Unique:connection.users'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'wmCtcdi2IniBOzBauuC0QQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUniqueAndExistsSendsCorrectFieldNameToDBWithArrays:3249',
        'data' => [
            [
                'email' => 'foo',
                'type' => 'bar'
            ]
        ],
        'rules' => [
            '0.email' => [
                'unique:users'
            ],
            '0.type' => [
                'exists:user_types'
            ]
        ],
        'validated' => [
            [
                'email' => 'foo',
                'type' => 'bar'
            ]
        ]
    ],
    'IMtfZpQne5s0Tb9ETq97AQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3275',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'Exists:users'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'tavXar6hz9Cj3a1bNrK6Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3283',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'Exists:users,email,account_id,1,name,taylor'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'zc6Dhb27zJQnNfzkJfIFAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3304',
        'data' => [
            'email' => 'foo'
        ],
        'rules' => [
            'email' => [
                'Exists:connection.users'
            ]
        ],
        'validated' => [
            'email' => 'foo'
        ]
    ],
    'VfGSe2a5ZsnXVPfbK0EHkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:3311',
        'data' => [
            'email' => [
                'foo',
                'foo'
            ]
        ],
        'rules' => [
            'email' => [
                'exists:users,email_addr'
            ]
        ],
        'validated' => [
            'email' => [
                'foo',
                'foo'
            ]
        ]
    ],
    'wH0bhkVnzQXi1WSTpoDx3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExistsIsNotCalledUnnecessarily:3329',
        'data' => [
            'id' => '1'
        ],
        'rules' => [
            'id' => [
                'Integer',
                'Exists:users,id'
            ]
        ],
        'validated' => [
            'id' => '1'
        ]
    ],
    'yG8bcAVYN3xul3ZK-SbW1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3339',
        'data' => [
            'ip' => '127.0.0.1'
        ],
        'rules' => [
            'ip' => [
                'Ip'
            ]
        ],
        'validated' => [
            'ip' => '127.0.0.1'
        ]
    ],
    'K_jcDhFnFYqdKFIGOwQS4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3342',
        'data' => [
            'ip' => '127.0.0.1'
        ],
        'rules' => [
            'ip' => [
                'Ipv4'
            ]
        ],
        'validated' => [
            'ip' => '127.0.0.1'
        ]
    ],
    'ea5E7e-Kip43l7b99OoZ8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:3345',
        'data' => [
            'ip' => '::1'
        ],
        'rules' => [
            'ip' => [
                'Ipv6'
            ]
        ],
        'validated' => [
            'ip' => '::1'
        ]
    ],
    '_ZU7Sw0JNeD2r2sTEaeLbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3362',
        'data' => [
            'mac' => '01-23-45-67-89-ab'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-ab'
        ]
    ],
    '9k4HpUGdQI-_4TWAW_FMsw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3366',
        'data' => [
            'mac' => '01-23-45-67-89-AB'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-AB'
        ]
    ],
    'P4zF75ZthwToE0c8BkHDug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3370',
        'data' => [
            'mac' => '01-23-45-67-89-aB'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01-23-45-67-89-aB'
        ]
    ],
    'qjvvWKVzyF6F1vBs363AHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3374',
        'data' => [
            'mac' => '01:23:45:67:89:ab'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:ab'
        ]
    ],
    'cxuvoRhRkpHRUpR45sVemg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3378',
        'data' => [
            'mac' => '01:23:45:67:89:AB'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:AB'
        ]
    ],
    'qNZOXYtBBMAYHsxgKqMH_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3382',
        'data' => [
            'mac' => '01:23:45:67:89:aB'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '01:23:45:67:89:aB'
        ]
    ],
    '3p-9p6U2Z_qG3ysmqFTu6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:3394',
        'data' => [
            'mac' => '0123.4567.89ab'
        ],
        'rules' => [
            'mac' => [
                'mac_address'
            ]
        ],
        'validated' => [
            'mac' => '0123.4567.89ab'
        ]
    ],
    'jWv9YDRv-vYlkJ4wFud1VA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmail:3429',
        'data' => [
            'x' => 'foo@gmail.com'
        ],
        'rules' => [
            'x' => [
                'Email'
            ]
        ],
        'validated' => [
            'x' => 'foo@gmail.com'
        ]
    ],
    'hcnZgVyRGZtYiX90g1BNCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithInternationalCharacters:3435',
        'data' => [
            'x' => 'foo@gmäil.com'
        ],
        'rules' => [
            'x' => [
                'email'
            ]
        ],
        'validated' => [
            'x' => 'foo@gmäil.com'
        ]
    ],
    'Qx8UaTSvcNQbLqEMi-DDzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterCheck:3450',
        'data' => [
            'x' => 'example@example.com'
        ],
        'rules' => [
            'x' => [
                'email:filter'
            ]
        ],
        'validated' => [
            'x' => 'example@example.com'
        ]
    ],
    'hkmiinElR0uWuLyEoMiFtg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:3466',
        'data' => [
            'x' => 'example@example.com'
        ],
        'rules' => [
            'x' => [
                'email:filter_unicode'
            ]
        ],
        'validated' => [
            'x' => 'example@example.com'
        ]
    ],
    'J5MkAfWJRUSzh1jM4IgZvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:3470',
        'data' => [
            'x' => 'exämple@example.com'
        ],
        'rules' => [
            'x' => [
                'email:filter_unicode'
            ]
        ],
        'validated' => [
            'x' => 'exämple@example.com'
        ]
    ],
    'IOtMU8Ph8i7JLqVuBGK5yw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aaa://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'aaa://fully.qualified.domain/path'
        ]
    ],
    'Nx-GALmm9UEZudvziFQDdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aaas://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'aaas://fully.qualified.domain/path'
        ]
    ],
    '-vLUVRaLVqc3krphwTPrYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'about://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'about://fully.qualified.domain/path'
        ]
    ],
    'dR-OuHJMPUcGCGTb5IVJyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'acap://fully.qualified.domain/path'
        ]
    ],
    'AAh120eRXjl47lP8QgA7-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acct://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'acct://fully.qualified.domain/path'
        ]
    ],
    'GLVdC7GsyGPfi4UILLN7XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'acr://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'acr://fully.qualified.domain/path'
        ]
    ],
    'vUSs9740pttiyQND4BSX2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'adiumxtra://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'adiumxtra://fully.qualified.domain/path'
        ]
    ],
    'CqAR-MmBdObLjwLRuhxQmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'afp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'afp://fully.qualified.domain/path'
        ]
    ],
    'gtGVPTnmtQa0QtBfGmBM4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'afs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'afs://fully.qualified.domain/path'
        ]
    ],
    'GfOU-YoqtiP_oUzSpVNYNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aim://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'aim://fully.qualified.domain/path'
        ]
    ],
    'NrL7G3lLDhntyi4g8fHwvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'apt://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'apt://fully.qualified.domain/path'
        ]
    ],
    'WfcFYjxNNK_wwJnmFvU9fA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'attachment://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'attachment://fully.qualified.domain/path'
        ]
    ],
    'Fdm_WWUnvoYfvmXp3BZlfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'aw://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'aw://fully.qualified.domain/path'
        ]
    ],
    'jrHMQ_BDv-rfWStPCAWFeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'barion://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'barion://fully.qualified.domain/path'
        ]
    ],
    '_g8eG2qlNvj9Zp47ZQfPtg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'beshare://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'beshare://fully.qualified.domain/path'
        ]
    ],
    'Ink9hufFZ6pgEnnsLBt5Sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'bitcoin://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'bitcoin://fully.qualified.domain/path'
        ]
    ],
    '3Zt1BH86JbZg2MSYl6LG-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'blob://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'blob://fully.qualified.domain/path'
        ]
    ],
    'ZJeq29JhqXtSKNe3SPApng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'bolo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'bolo://fully.qualified.domain/path'
        ]
    ],
    'PIsMWd_oxRnrifKmil7xkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'callto://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'callto://fully.qualified.domain/path'
        ]
    ],
    'Bf1Npk6EnB1wtFUDo6CmfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'cap://fully.qualified.domain/path'
        ]
    ],
    'FZSI70PbZ6BV5Pnu5kHxaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'chrome://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'chrome://fully.qualified.domain/path'
        ]
    ],
    'kfTQZvZvzxWPTZxAp0DU2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'chrome-extension://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'chrome-extension://fully.qualified.domain/path'
        ]
    ],
    'sB0h-vTvkmm3Co0Ro7gUQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'cid://fully.qualified.domain/path'
        ]
    ],
    '7GbyL26hasGiEMSBfC7aCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'coap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'coap://fully.qualified.domain/path'
        ]
    ],
    '6yE1LzDv93Q3L_KqyO2R-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'coaps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'coaps://fully.qualified.domain/path'
        ]
    ],
    'AD_gWIBtfzZA_Cm_ukbEMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'com-eventbrite-attendee://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'com-eventbrite-attendee://fully.qualified.domain/path'
        ]
    ],
    'ook9EZKAnwYFwTA-wJGErA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'content://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'content://fully.qualified.domain/path'
        ]
    ],
    'z9a5ucFNp2QqPC3CNVUlEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'crid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'crid://fully.qualified.domain/path'
        ]
    ],
    'sDGJDUkcehYQfn6XpTp9tA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'cvs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'cvs://fully.qualified.domain/path'
        ]
    ],
    'Ho8M9aYquydFS1EP3WWIcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'data://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'data://fully.qualified.domain/path'
        ]
    ],
    'W9h6Fug8OuKKUmtt1-h3DQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dav://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dav://fully.qualified.domain/path'
        ]
    ],
    'HnsPIUd1LTSr-113yOXqrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dict://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dict://fully.qualified.domain/path'
        ]
    ],
    '6Bf-qyaKPnOYtKlltZV4KQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dlna-playcontainer://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dlna-playcontainer://fully.qualified.domain/path'
        ]
    ],
    'clmq6U1wCgDPxNE-C2uNxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dlna-playsingle://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dlna-playsingle://fully.qualified.domain/path'
        ]
    ],
    'mmQt2JPbVKtxIA4dAWH2VA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dns://fully.qualified.domain/path'
        ]
    ],
    'bMYGbB_cBQ3OZLkcjr9Bpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dntp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dntp://fully.qualified.domain/path'
        ]
    ],
    'JfAQmjr7azJYdyDh3bQucw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dtn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dtn://fully.qualified.domain/path'
        ]
    ],
    'ntOS5xpmDwuo2-ZTDomxeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'dvb://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'dvb://fully.qualified.domain/path'
        ]
    ],
    'NgfU1Zy-dtxzsM7OwcQKzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ed2k://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ed2k://fully.qualified.domain/path'
        ]
    ],
    'eiS2HfzcyxWfiG3s8psFMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'example://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'example://fully.qualified.domain/path'
        ]
    ],
    'M1LA5WljD-9bGzHNKSv4iA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'facetime://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'facetime://fully.qualified.domain/path'
        ]
    ],
    '_xjKZT27ba1P-MKhj4ww7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'fax://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'fax://fully.qualified.domain/path'
        ]
    ],
    '-POykzQAso9zwMBREMe26g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'feed://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'feed://fully.qualified.domain/path'
        ]
    ],
    'WVnOhXApPcoW2_-N4zIc8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'feedready://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'feedready://fully.qualified.domain/path'
        ]
    ],
    'L3pT0bKORrv1XlNLUJvRBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'file://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'file://fully.qualified.domain/path'
        ]
    ],
    'ot5s0G28g9pECVwLAhRg9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'filesystem://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'filesystem://fully.qualified.domain/path'
        ]
    ],
    'jDh8A7U1cE1fv2KMtGVmBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'finger://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'finger://fully.qualified.domain/path'
        ]
    ],
    'bFYrrw2oOUoIfeGpPCJbeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'fish://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'fish://fully.qualified.domain/path'
        ]
    ],
    '8q0T70jW3BOB_R_RwPwfKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ftp://fully.qualified.domain/path'
        ]
    ],
    'Mt-cKZF1TNb95YKk-EcaYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'geo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'geo://fully.qualified.domain/path'
        ]
    ],
    'j7ZywOG2zppElCrAUGyTmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gg://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'gg://fully.qualified.domain/path'
        ]
    ],
    '1NHonrShzrG1tmXeKDeRfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'git://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'git://fully.qualified.domain/path'
        ]
    ],
    'zPDqdra7eKVCDtkBrYQ2_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gizmoproject://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'gizmoproject://fully.qualified.domain/path'
        ]
    ],
    'ymff4WBlcpeVTX8gbFYYBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'go://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'go://fully.qualified.domain/path'
        ]
    ],
    'LdPlkEbzWzFyS7TXY4RjCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gopher://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'gopher://fully.qualified.domain/path'
        ]
    ],
    'W54De6hUsgnqj0IyhA0BeA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'gtalk://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'gtalk://fully.qualified.domain/path'
        ]
    ],
    'Qh1iGYDb_aiOEhc3TvKqBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'h323://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'h323://fully.qualified.domain/path'
        ]
    ],
    'ULcogtw0sxPw1cmtY2pwig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ham://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ham://fully.qualified.domain/path'
        ]
    ],
    'SzSFLshABesoAwut2-FNSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'hcp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'hcp://fully.qualified.domain/path'
        ]
    ],
    'EsjrGr8fWgmqSBCc7RiqAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://fully.qualified.domain/path'
        ]
    ],
    'LmaNRFODIDIqOHf7tP5AqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://fully.qualified.domain/path'
        ]
    ],
    'jhhNBW4MCFZyr20gKGriPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iax://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iax://fully.qualified.domain/path'
        ]
    ],
    'jrd0GLHuwJ5a71J2Cnc_3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'icap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'icap://fully.qualified.domain/path'
        ]
    ],
    'R6in2kOKjz_4X8-lVYhWQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'icon://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'icon://fully.qualified.domain/path'
        ]
    ],
    '1SF6wzvRdpzzF9lWarj6Cw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'im://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'im://fully.qualified.domain/path'
        ]
    ],
    'iciduV9LO18ctiYlk6qFgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'imap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'imap://fully.qualified.domain/path'
        ]
    ],
    'kN83SoAb_2mp6zZVrwmk-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'info://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'info://fully.qualified.domain/path'
        ]
    ],
    'gVpmqmQPdGkgeEyCBx5d9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iotdisco://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iotdisco://fully.qualified.domain/path'
        ]
    ],
    'yz1Yv1EbYpC2zdl78x4QFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ipn://fully.qualified.domain/path'
        ]
    ],
    'gQ0Xl_5imOvQuGHrmoB9IQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ipp://fully.qualified.domain/path'
        ]
    ],
    'xj1OsuhITzQrV6Lq43N8_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ipps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ipps://fully.qualified.domain/path'
        ]
    ],
    't94zpB2S-ZJoo7qiWkurrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'irc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'irc://fully.qualified.domain/path'
        ]
    ],
    '02odxmAJlEjDRSq4zty0rQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'irc6://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'irc6://fully.qualified.domain/path'
        ]
    ],
    '8TSQ5Y5bGJyXxvSp8vZg7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ircs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ircs://fully.qualified.domain/path'
        ]
    ],
    'Cxh9hUX1FD56U4q1hYSHsQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iris://fully.qualified.domain/path'
        ]
    ],
    '4V5Py7AJKQeLL-3Mu5mDWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iris.beep://fully.qualified.domain/path'
        ]
    ],
    'Gg44kcUun5Qncu80h-VAqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.lwz://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iris.lwz://fully.qualified.domain/path'
        ]
    ],
    'Brh6nYkxzKopnbJ8Nzfabw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.xpc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iris.xpc://fully.qualified.domain/path'
        ]
    ],
    'J0wcCGl8hcLRMLUpgEH3IA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'iris.xpcs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'iris.xpcs://fully.qualified.domain/path'
        ]
    ],
    'cbY2y1h174JAKmCBdnkpDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'itms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'itms://fully.qualified.domain/path'
        ]
    ],
    'isA8Tg7L8ax91DDqZbjp3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jabber://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'jabber://fully.qualified.domain/path'
        ]
    ],
    'TvkLRBA53FsTV07LZv3mTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jar://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'jar://fully.qualified.domain/path'
        ]
    ],
    'VqlQzRZbZwru5j2XNwT0-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'jms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'jms://fully.qualified.domain/path'
        ]
    ],
    'wp34ClTaYwBe8M-UfIIHgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'keyparc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'keyparc://fully.qualified.domain/path'
        ]
    ],
    'x2VCJhKtK1L6KJn_peqxlw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'lastfm://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'lastfm://fully.qualified.domain/path'
        ]
    ],
    'F-ewqI3m3-iaJ18m4AQPzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ldap://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ldap://fully.qualified.domain/path'
        ]
    ],
    'I3uvCC5BaN-953HbSSQ4cQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ldaps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ldaps://fully.qualified.domain/path'
        ]
    ],
    'EKkRPA4BAUGvFh4mikqhNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'magnet://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'magnet://fully.qualified.domain/path'
        ]
    ],
    'CFUYNbjRzb2ae0k5MMyZmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mailserver://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mailserver://fully.qualified.domain/path'
        ]
    ],
    'phGOGo7wjqiTBJge1rghYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mailto://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mailto://fully.qualified.domain/path'
        ]
    ],
    '-r5-VoiYfogNy0129lqNiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'maps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'maps://fully.qualified.domain/path'
        ]
    ],
    '0Xt2lyV38gsIhzgl5JktiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'market://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'market://fully.qualified.domain/path'
        ]
    ],
    'PzMFpRNSiJud9wfJoCfx5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'message://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'message://fully.qualified.domain/path'
        ]
    ],
    'myxYq5PyUz6dhC9o76KDPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mid://fully.qualified.domain/path'
        ]
    ],
    'N8Pq2Ch7Zzo4lHl0NcET2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mms://fully.qualified.domain/path'
        ]
    ],
    'IaMjwJ7lseSY278tugr44Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'modem://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'modem://fully.qualified.domain/path'
        ]
    ],
    'uBypRTicbDs1LOZK_VO7oA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-help://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-help://fully.qualified.domain/path'
        ]
    ],
    'F114aC9ThD8LDZvHdUiqrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings://fully.qualified.domain/path'
        ]
    ],
    '1FCH3nZrH7XtuWdG0ToUGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-airplanemode://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-airplanemode://fully.qualified.domain/path'
        ]
    ],
    'j4DUHMCgSNYZBxbq6R51Sg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-bluetooth://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-bluetooth://fully.qualified.domain/path'
        ]
    ],
    'QoIAhVH6_x4YQvQ7qnbf7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-camera://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-camera://fully.qualified.domain/path'
        ]
    ],
    '-H6nPR3vrG_NPU9GfhWGsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-cellular://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-cellular://fully.qualified.domain/path'
        ]
    ],
    '1AZXXGRdP6Y5SKmDf3-naw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-cloudstorage://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-cloudstorage://fully.qualified.domain/path'
        ]
    ],
    'UbaD_hpmg2I_mJ6zbiO8yA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-emailandaccounts://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-emailandaccounts://fully.qualified.domain/path'
        ]
    ],
    'IhIvXKny6149AkSf0Ajeeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-language://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-language://fully.qualified.domain/path'
        ]
    ],
    'kdnYYxDPMzkftp56mBEqfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-location://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-location://fully.qualified.domain/path'
        ]
    ],
    'UUTLm0OS8lRAoAIIx7ggfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-lock://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-lock://fully.qualified.domain/path'
        ]
    ],
    'NuSVwP7ab0Ikhng9ty1BMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-nfctransactions://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-nfctransactions://fully.qualified.domain/path'
        ]
    ],
    'LNH234B5rFEgaXs23m1zbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-notifications://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-notifications://fully.qualified.domain/path'
        ]
    ],
    '0PbsGH0kMh0V0qKb7UmGHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-power://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-power://fully.qualified.domain/path'
        ]
    ],
    'r-e-Mo1vtORzOmmsHcd2Bg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-privacy://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-privacy://fully.qualified.domain/path'
        ]
    ],
    'r2toTLhAfmhjrxytxCn45g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-proximity://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-proximity://fully.qualified.domain/path'
        ]
    ],
    'pJnagbyJBlmpbdDysyhCvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-screenrotation://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-screenrotation://fully.qualified.domain/path'
        ]
    ],
    'QpNuN12Xt4Lu112Yaxz5tQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-wifi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-wifi://fully.qualified.domain/path'
        ]
    ],
    'mr0DgXzUCvwgnv65-yfz2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ms-settings-workplace://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ms-settings-workplace://fully.qualified.domain/path'
        ]
    ],
    'yFZ2onL3ENvKQ-G0lU-_TQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msnim://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'msnim://fully.qualified.domain/path'
        ]
    ],
    'L6AguB63fduH9MALkAA-DQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msrp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'msrp://fully.qualified.domain/path'
        ]
    ],
    'WnZcmedyjHssycMpuInlow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'msrps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'msrps://fully.qualified.domain/path'
        ]
    ],
    'vzdqVSs0ZsGyT1CO48hlPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mtqp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mtqp://fully.qualified.domain/path'
        ]
    ],
    'ytWHc0UsJI3I8dkIR9gtJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mumble://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mumble://fully.qualified.domain/path'
        ]
    ],
    '9YGxNBjLWe2wvFZbOqnZWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mupdate://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mupdate://fully.qualified.domain/path'
        ]
    ],
    'J2WoA0d9Ofqo4ItEzvKDJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'mvn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'mvn://fully.qualified.domain/path'
        ]
    ],
    '3bSmf3XRz86upd7mSrZX7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'news://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'news://fully.qualified.domain/path'
        ]
    ],
    'T7py7f0GV2XD2aySPzyJnw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nfs://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'nfs://fully.qualified.domain/path'
        ]
    ],
    'yURYn2uuAHoB9XCxDpLnOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ni://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ni://fully.qualified.domain/path'
        ]
    ],
    'dc50ZjHvKs4QiVjuT5Gj1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nih://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'nih://fully.qualified.domain/path'
        ]
    ],
    'K-aYka-l6mojLQNUczvUAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'nntp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'nntp://fully.qualified.domain/path'
        ]
    ],
    '5VOsWnAPCsy6_0vtj92J9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'notes://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'notes://fully.qualified.domain/path'
        ]
    ],
    'i9943I3q6ihdwbW-Cx0yvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'oid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'oid://fully.qualified.domain/path'
        ]
    ],
    'yH1HJ49-E27ORcNxATCghQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'opaquelocktoken://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'opaquelocktoken://fully.qualified.domain/path'
        ]
    ],
    'tvn-i6QvrTUcDSSKwLjD8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pack://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'pack://fully.qualified.domain/path'
        ]
    ],
    'j6iPO9_F6uWl_gqiCopFLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'palm://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'palm://fully.qualified.domain/path'
        ]
    ],
    'Radg5kUgDoStYqSMKi_n3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'paparazzi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'paparazzi://fully.qualified.domain/path'
        ]
    ],
    '5rA7VMA4HSrxjyhlwK2Lmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pkcs11://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'pkcs11://fully.qualified.domain/path'
        ]
    ],
    'Fu9CrtRhL7jc2oG5nmcooQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'platform://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'platform://fully.qualified.domain/path'
        ]
    ],
    'zDG99fWiDGqGOdmKsSXkNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pop://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'pop://fully.qualified.domain/path'
        ]
    ],
    'FYHDKBmZ0nPnJ5rnuZPHuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'pres://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'pres://fully.qualified.domain/path'
        ]
    ],
    '-Iu2x2LMe8DcbyONck72Ow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'prospero://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'prospero://fully.qualified.domain/path'
        ]
    ],
    'NjK7x0eMrgjHbOXTOQXt8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'proxy://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'proxy://fully.qualified.domain/path'
        ]
    ],
    'lweOS8NY_5z18I3tKFALeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'psyc://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'psyc://fully.qualified.domain/path'
        ]
    ],
    'IVqe0VsNDHdbHmOrz1jR-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'query://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'query://fully.qualified.domain/path'
        ]
    ],
    '0ZvgxRsdU_xxZEyzF3TgfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'redis://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'redis://fully.qualified.domain/path'
        ]
    ],
    'aVPu7t3G1SrN21_7GOqhvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rediss://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rediss://fully.qualified.domain/path'
        ]
    ],
    'sylpOiQ8ecSZ1Tw2OpgYUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'reload://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'reload://fully.qualified.domain/path'
        ]
    ],
    'POHzoheOP2aqZg1SsEjx7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'res://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'res://fully.qualified.domain/path'
        ]
    ],
    'M7wqW2VvfMX9lyq7773X9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'resource://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'resource://fully.qualified.domain/path'
        ]
    ],
    'VGtrkcUneUHrn848j25Xlw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rmi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rmi://fully.qualified.domain/path'
        ]
    ],
    '-a7um5PlhdbTp-MJxY75gg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rsync://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rsync://fully.qualified.domain/path'
        ]
    ],
    'gZZEI9CFICS_HXjtbdC37w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtmfp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rtmfp://fully.qualified.domain/path'
        ]
    ],
    'JAloh3sBinYGFV05XHjbBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtmp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rtmp://fully.qualified.domain/path'
        ]
    ],
    'OoL6tXGeLEDuf3tdTpd-WA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtsp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rtsp://fully.qualified.domain/path'
        ]
    ],
    '3QU9s6DYtCBhZXMF3a7IUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtsps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rtsps://fully.qualified.domain/path'
        ]
    ],
    '8xRwWTHx6MiAo7XINC4EAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'rtspu://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'rtspu://fully.qualified.domain/path'
        ]
    ],
    'TPo7AylXJ49Xi9sYFo9MrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 's3://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 's3://fully.qualified.domain/path'
        ]
    ],
    'yvl4B_INb-Pk_ATieDAG9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'secondlife://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'secondlife://fully.qualified.domain/path'
        ]
    ],
    'lNVbc9Vw7Kw2E-WnnYdnSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'service://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'service://fully.qualified.domain/path'
        ]
    ],
    'uuUG7Srq6ZfRwXMHgymZFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'session://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'session://fully.qualified.domain/path'
        ]
    ],
    'RVs-KB71SPlJPtjinlWTDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sftp://fully.qualified.domain/path'
        ]
    ],
    'Ok8fBGt4nepxd-7aMUGQWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sgn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sgn://fully.qualified.domain/path'
        ]
    ],
    'rlfapwQdyMD883W69-2g6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'shttp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'shttp://fully.qualified.domain/path'
        ]
    ],
    'w0gRwmEQ42OoVLervUqolA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sieve://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sieve://fully.qualified.domain/path'
        ]
    ],
    'OVVpaTIFc-9Mmpe-O7NdWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sip://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sip://fully.qualified.domain/path'
        ]
    ],
    'ciMa4vHsq7sFfKaZTrzrbg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sips://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sips://fully.qualified.domain/path'
        ]
    ],
    'A6aGTIKHDyA_cGe4IMpOHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'skype://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'skype://fully.qualified.domain/path'
        ]
    ],
    'vl_nkBp8mp78pyNM3nVyHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'smb://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'smb://fully.qualified.domain/path'
        ]
    ],
    '4LXG10o0QE9eQc1UFsjCZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'sms://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'sms://fully.qualified.domain/path'
        ]
    ],
    'JuagAk0w4rfTzroZ7xf8Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'smtp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'smtp://fully.qualified.domain/path'
        ]
    ],
    'tfBmczT2gFiG9Wnr7YogvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'snews://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'snews://fully.qualified.domain/path'
        ]
    ],
    'kTnQ1fy336HlKgZLA0NrFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'snmp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'snmp://fully.qualified.domain/path'
        ]
    ],
    '9GIQYj-jiqY4glx0JimCvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soap.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'soap.beep://fully.qualified.domain/path'
        ]
    ],
    'vkxJy5xGGfPRuA_7A1F1hQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soap.beeps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'soap.beeps://fully.qualified.domain/path'
        ]
    ],
    'tAhJ8ZSQaWYpwHkb3izaog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'soldat://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'soldat://fully.qualified.domain/path'
        ]
    ],
    'D-1aFcQtDK5wW-4Wz2UJWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'spotify://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'spotify://fully.qualified.domain/path'
        ]
    ],
    'tYKAvXRvKRa3FknPn9RZ0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ssh://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ssh://fully.qualified.domain/path'
        ]
    ],
    'p-XWm_Zh4nj4F2ODeM_npA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'steam://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'steam://fully.qualified.domain/path'
        ]
    ],
    'bgAGF9Qz4P9IGcN3dnmZMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'stun://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'stun://fully.qualified.domain/path'
        ]
    ],
    '530C5ibRDKHnoAZy_O_9Sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'stuns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'stuns://fully.qualified.domain/path'
        ]
    ],
    'vhXE8ZfUoMrsBowiNPEgHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'submit://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'submit://fully.qualified.domain/path'
        ]
    ],
    '_DvuoVc9OO6NjDfqnqRU-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'svn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'svn://fully.qualified.domain/path'
        ]
    ],
    'N2lAFlFwYHIDm994Euh1JA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tag://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tag://fully.qualified.domain/path'
        ]
    ],
    'a0rUr5VoroUm--zJSNsGHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'teamspeak://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'teamspeak://fully.qualified.domain/path'
        ]
    ],
    'iCZhePbqp3EzzTClEMrMxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tel://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tel://fully.qualified.domain/path'
        ]
    ],
    'TV39NRQ5HFhqX1qO30GCoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'teliaeid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'teliaeid://fully.qualified.domain/path'
        ]
    ],
    '7fn6EiKJJ-w-k4oDHE0jvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'telnet://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'telnet://fully.qualified.domain/path'
        ]
    ],
    'POiVryED2R5xY-1rrMEc-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tftp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tftp://fully.qualified.domain/path'
        ]
    ],
    'UQoVl3quNgCDKKfhUVDQNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'things://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'things://fully.qualified.domain/path'
        ]
    ],
    'MytrNO7B2vWj8_qkfGj2Mg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'thismessage://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'thismessage://fully.qualified.domain/path'
        ]
    ],
    'Ef7d7Gx5YcFoeSlWcy7lcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tip://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tip://fully.qualified.domain/path'
        ]
    ],
    'JK4PAfLURP6ZGywit0rXww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tn3270://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tn3270://fully.qualified.domain/path'
        ]
    ],
    'mba7alB8UMJBaZSkyL75zA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'turn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'turn://fully.qualified.domain/path'
        ]
    ],
    'TiSKr7TfUJVIhLojOn7jTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'turns://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'turns://fully.qualified.domain/path'
        ]
    ],
    'W1je1x_95Dw1elZnBVidow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'tv://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'tv://fully.qualified.domain/path'
        ]
    ],
    'tqMoCHlRBLK-dFjdJttjsw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'udp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'udp://fully.qualified.domain/path'
        ]
    ],
    'AwwER2kAfEP0acCVhs-l4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'unreal://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'unreal://fully.qualified.domain/path'
        ]
    ],
    'CgfUclpNCrRKM19bQvGM5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'urn://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'urn://fully.qualified.domain/path'
        ]
    ],
    'YWyufgiVePVzLvjFoH77UA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ut2004://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ut2004://fully.qualified.domain/path'
        ]
    ],
    'LWuNg8rJmOXj7zDtIGfryg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'vemmi://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'vemmi://fully.qualified.domain/path'
        ]
    ],
    '5dMukxwnc5wD2eKIrOqBRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ventrilo://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ventrilo://fully.qualified.domain/path'
        ]
    ],
    'lEYB9djIy40oX9n2AyF1sg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'videotex://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'videotex://fully.qualified.domain/path'
        ]
    ],
    'DS0ceke5EunO_UgIifIfRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'view-source://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'view-source://fully.qualified.domain/path'
        ]
    ],
    'YgFaVZxpODYGJxtgNfL_6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wais://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'wais://fully.qualified.domain/path'
        ]
    ],
    'Ja7i4EU2UxfXlbGJFR-UmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'webcal://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'webcal://fully.qualified.domain/path'
        ]
    ],
    'VO4r3ASFJ0p40vXYFEoALA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ws://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ws://fully.qualified.domain/path'
        ]
    ],
    '85jMC0Ce-jWdHdBAQ7ghPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wss://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'wss://fully.qualified.domain/path'
        ]
    ],
    'a9H1noykan4HiW1Yu-ewSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wtai://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'wtai://fully.qualified.domain/path'
        ]
    ],
    'L14HV8-0U5KIGFYWDDDiwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'wyciwyg://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'wyciwyg://fully.qualified.domain/path'
        ]
    ],
    'P7HqIjptEW0Dw2DBErVW3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xcon://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xcon://fully.qualified.domain/path'
        ]
    ],
    'GnsRsRM2eBSv--WB4EJjCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xcon-userid://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xcon-userid://fully.qualified.domain/path'
        ]
    ],
    'A63vS5RLDZlfroxvF012Fw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xfire://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xfire://fully.qualified.domain/path'
        ]
    ],
    'HRpKk8NX3VjDOIIqEZz46Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmlrpc.beep://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xmlrpc.beep://fully.qualified.domain/path'
        ]
    ],
    'JmDZuYLqW1fCV8J26DLB3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmlrpc.beeps://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xmlrpc.beeps://fully.qualified.domain/path'
        ]
    ],
    'KZmVzHbkEvI3vqv5tTaeHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xmpp://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xmpp://fully.qualified.domain/path'
        ]
    ],
    '9vNJVhS451R02CC9STRWbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'xri://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'xri://fully.qualified.domain/path'
        ]
    ],
    '8rRqUXyMt8i1D9tnmWo6Vg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'ymsgr://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'ymsgr://fully.qualified.domain/path'
        ]
    ],
    'tg-aUOIpvSOSn0Jlwtzujw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'z39.50://fully.qualified.domain/path'
        ]
    ],
    '7QV94gYKhkrsTzfXkieuUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50r://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'z39.50r://fully.qualified.domain/path'
        ]
    ],
    '44aOORcpYhYIP-hHF9MsFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'z39.50s://fully.qualified.domain/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'z39.50s://fully.qualified.domain/path'
        ]
    ],
    'ooeD88CEaeo6bjQ0P2XQAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://a.pl'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://a.pl'
        ]
    ],
    'd2zlj5huHPbu2DI55kRb7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://localhost/url.php'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://localhost/url.php'
        ]
    ],
    'XS_v_afBAhoRtYUxBgamMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://local.dev'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://local.dev'
        ]
    ],
    'S47dm5S-plM3rgvw12n36w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://google.com'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://google.com'
        ]
    ],
    'ju2iOTquiKsk7Jq0nz5iCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://www.google.com'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://www.google.com'
        ]
    ],
    '5QlmkyWkm9yP0dRiF3NiJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://goog_le.com'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://goog_le.com'
        ]
    ],
    'DN7aVswahYXtT1w8by43lQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://google.com'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://google.com'
        ]
    ],
    'DZqiiqMWFNczAZSfJ5RDww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://illuminate.dev'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://illuminate.dev'
        ]
    ],
    'gyIgPhTXcljdb3CNNd2RBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://localhost'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://localhost'
        ]
    ],
    'mEfWOq5fn-FecVDkUJjllw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/?'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com/?'
        ]
    ],
    'cUL-KFPfOUjIvwx0thxKZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://президент.рф/'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://президент.рф/'
        ]
    ],
    'kz3Ttzp428-cAhfXZSGo9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://스타벅스코리아.com'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://스타벅스코리아.com'
        ]
    ],
    'L3KnrralE-I8uOlKAZ909Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'http://xn--d1abbgf6aiiy.xn--p1ai/'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'http://xn--d1abbgf6aiiy.xn--p1ai/'
        ]
    ],
    'hMPdYjHFInMI9awPGkGMjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com?'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com?'
        ]
    ],
    'hW3ch7chvygNfSzic9mJAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com?q=1'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com?q=1'
        ]
    ],
    'MGN_ZyyGhN3FXpeJWwrPbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/?q=1'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com/?q=1'
        ]
    ],
    'r9dDlrCnGAOHa2ovUj9Waw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com#'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com#'
        ]
    ],
    '9LnpXkFh4iT3mA24qLj9DA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com#fragment'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com#fragment'
        ]
    ],
    'Mm0MXNLRYuQJ35-FPlGDng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://laravel.com/#fragment'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://laravel.com/#fragment'
        ]
    ],
    'PdVQ05524KX2MhFvd1NVJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain1'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://domain1'
        ]
    ],
    'B-4EjbBQz_LqvCyqi31H0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain12/'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://domain12/'
        ]
    ],
    'cChe6oOvU5x3I8SkVN-Lgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain12#fragment'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://domain12#fragment'
        ]
    ],
    'cTbHqojsFT4aNUUzpPKdNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain1/path'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://domain1/path'
        ]
    ],
    'AZrZGu-4pgDDqOHqwpePvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:3494',
        'data' => [
            'x' => 'https://domain.com/path/%2528failed%2526?param=1#fragment'
        ],
        'rules' => [
            'x' => [
                'Url'
            ]
        ],
        'validated' => [
            'x' => 'https://domain.com/path/%2528failed%2526?param=1#fragment'
        ]
    ],
    '0gn3t4qRFVuSoMUBujqCxw' => [
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
        'rules' => [
            'x' => [
                'dimensions:min_width=1'
            ]
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
        ]
    ],
    'gAe5ZdC1bYiksJKV5k_Qqg' => [
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
        'rules' => [
            'x' => [
                'dimensions:max_width=10'
            ]
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
        ]
    ],
    'qWOl-e569NhzmX9QTWkjTA' => [
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
        'rules' => [
            'x' => [
                'dimensions:min_height=1'
            ]
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
        ]
    ],
    '0MDavmpp-rJ9UdxTw3cLNw' => [
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
        'rules' => [
            'x' => [
                'dimensions:max_height=10'
            ]
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
        ]
    ],
    '1Io3-tzpXCF4wrmUSGnCpA' => [
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
        'rules' => [
            'x' => [
                'dimensions:width=3'
            ]
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
        ]
    ],
    'bRZw_NBT_fAbjNexGTD69g' => [
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
        'rules' => [
            'x' => [
                'dimensions:height=2'
            ]
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
        ]
    ],
    'p6AP4-bP3V7uTwA7RA0-0Q' => [
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
        'rules' => [
            'x' => [
                'dimensions:min_height=2,ratio=3/2'
            ]
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
        ]
    ],
    '-6LhLgVGRZW6R2Euh_jVrA' => [
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
        'rules' => [
            'x' => [
                'dimensions:ratio=1.5'
            ]
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
        ]
    ],
    'Ury_DaqNUotm3xnk150vFg' => [
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
        'rules' => [
            'x' => [
                'dimensions:ratio=2/1'
            ]
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
        ]
    ],
    'MhNnBQe8XKQUo3mt6uRLPA' => [
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
        'rules' => [
            'x' => [
                'dimensions:ratio=2/3'
            ]
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
        ]
    ],
    'C9m8QTn9t2G6WOIBJY9K5Q' => [
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
        'rules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
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
        ]
    ],
    'HoX2bIrfsQz6n76y9xjSQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3981',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    '4CpQjpBv-9uWvgmqn3P-Sw' => [
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
        'rules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
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
        ]
    ],
    'jL8eQsbDNVyQeO91xsOgoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:3994',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ],
        'rules' => [
            'x' => [
                'dimensions:max_width=1,max_height=1'
            ]
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\File::class);
                $object = $class->newInstanceWithoutConstructor();

                return $object;
            })()
        ]
    ],
    'j73Oae4wOMS2Tf0bOVJfYQ' => [
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
        'rules' => [
            'x' => [
                'file'
            ]
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
        ]
    ],
    'RLchKXqZ9p5sjpQSeMzCmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:4098',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                'alpha',
                [],
                ''
            ]
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ]
    ],
    '521xr_hr3-bPq-_NgIq4XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:4101',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                '',
                '',
                '',
                'required',
                ''
            ]
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ]
    ],
    'j_Sr5XHAUOB0hDr0azN8mA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testAlternativeFormat:4108',
        'data' => [
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
        'validated' => [
            'x' => 'aslsdlks'
        ]
    ],
    '5YLeke5sE77LMFLlrsvZaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4115',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => [
                'Alpha'
            ]
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ]
    ],
    'SHn9sihcXFd5PgXxmo_E7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4129',
        'data' => [
            'x' => 'ユニコードを基盤技術と'
        ],
        'rules' => [
            'x' => [
                'Alpha'
            ]
        ],
        'validated' => [
            'x' => 'ユニコードを基盤技術と'
        ]
    ],
    '3-bQQS2uiVr_O0xs8YmzZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4135',
        'data' => [
            'x' => 'नमस्कार'
        ],
        'rules' => [
            'x' => [
                'Alpha'
            ]
        ],
        'validated' => [
            'x' => 'नमस्कार'
        ]
    ],
    'qclQOkcaWo2HjsuBF-KEKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:4141',
        'data' => [
            'x' => 'Continuación'
        ],
        'rules' => [
            'x' => [
                'Alpha'
            ]
        ],
        'validated' => [
            'x' => 'Continuación'
        ]
    ],
    'hh0sFShj9DWKetdN1xMPGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4163',
        'data' => [
            'x' => 'asls13dlks'
        ],
        'rules' => [
            'x' => [
                'AlphaNum'
            ]
        ],
        'validated' => [
            'x' => 'asls13dlks'
        ]
    ],
    'noQ8u9fR73tm2M8PtNlVxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4169',
        'data' => [
            'x' => '१२३'
        ],
        'rules' => [
            'x' => [
                'AlphaNum'
            ]
        ],
        'validated' => [
            'x' => '१२३'
        ]
    ],
    'opQlHgsLNjPIjRORfjX5ZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4172',
        'data' => [
            'x' => '٧٨٩'
        ],
        'rules' => [
            'x' => [
                'AlphaNum'
            ]
        ],
        'validated' => [
            'x' => '٧٨٩'
        ]
    ],
    'mze8X93vPPtum9tESozU5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:4175',
        'data' => [
            'x' => 'नमस्कार'
        ],
        'rules' => [
            'x' => [
                'AlphaNum'
            ]
        ],
        'validated' => [
            'x' => 'नमस्कार'
        ]
    ],
    'ZuHdhpmpuP1eN5OiRvuHhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4182',
        'data' => [
            'x' => 'asls1-_3dlks'
        ],
        'rules' => [
            'x' => [
                'AlphaDash'
            ]
        ],
        'validated' => [
            'x' => 'asls1-_3dlks'
        ]
    ],
    'QOcFp85nw2eZkOHLiKLMAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4188',
        'data' => [
            'x' => 'नमस्कार-_'
        ],
        'rules' => [
            'x' => [
                'AlphaDash'
            ]
        ],
        'validated' => [
            'x' => 'नमस्कार-_'
        ]
    ],
    '1sql0l8v8MH_C5TsSVOLgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:4191',
        'data' => [
            'x' => '٧٨٩'
        ],
        'rules' => [
            'x' => [
                'AlphaDash'
            ]
        ],
        'validated' => [
            'x' => '٧٨٩'
        ]
    ],
    'VIQoq9zyE6y7oXa0AV7NMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:4204',
        'data' => [
            'foo' => 'UTC'
        ],
        'rules' => [
            'foo' => [
                'Timezone'
            ]
        ],
        'validated' => [
            'foo' => 'UTC'
        ]
    ],
    'qp3wZ4f3y9Ndgfwqaq_dSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:4207',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => [
                'Timezone'
            ]
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ]
    ],
    'JMKHMx3Q00m8JTgf6_B9Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4226',
        'data' => [
            'x' => 'asdasdf'
        ],
        'rules' => [
            'x' => [
                'Regex:/^[a-z]+$/i'
            ]
        ],
        'validated' => [
            'x' => 'asdasdf'
        ]
    ],
    '4fgVxzqYho8eVlYLYD3uhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4233',
        'data' => [
            'x' => 'a,b'
        ],
        'rules' => [
            'x' => [
                'Regex:/^a,b$/i'
            ]
        ],
        'validated' => [
            'x' => 'a,b'
        ]
    ],
    '9KaMhe7KajnJ8p_lu7gCLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4236',
        'data' => [
            'x' => '12'
        ],
        'rules' => [
            'x' => [
                'Regex:/^12$/i'
            ]
        ],
        'validated' => [
            'x' => '12'
        ]
    ],
    'UDkR8lWOK2QU8s-Nsd_fNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4239',
        'data' => [
            'x' => 12
        ],
        'rules' => [
            'x' => [
                'Regex:/^12$/i'
            ]
        ],
        'validated' => [
            'x' => 12
        ]
    ],
    'LujqvRd2HykfmjcbTB2Wbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:4242',
        'data' => [
            'x' => [
                'y' => [
                    'z' => 'james'
                ]
            ]
        ],
        'rules' => [
            'x.y.z' => [
                'Regex:/^(taylor|james)$/i'
            ]
        ],
        'validated' => [
            'x' => [
                'y' => [
                    'z' => 'james'
                ]
            ]
        ]
    ],
    'axtyjYbi9baLQ4o1fpesIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:4249',
        'data' => [
            'x' => 'foo bar'
        ],
        'rules' => [
            'x' => [
                'NotRegex:/[xyz]/i'
            ]
        ],
        'validated' => [
            'x' => 'foo bar'
        ]
    ],
    'cpu788hbhnvO0SU9Add_FA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:4256',
        'data' => [
            'x' => 'foo bar'
        ],
        'rules' => [
            'x' => [
                'NotRegex:/x{3,}/i'
            ]
        ],
        'validated' => [
            'x' => 'foo bar'
        ]
    ],
    'iPYMipNuhLLn91hDanIMXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4264',
        'data' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => [
                'date'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01'
        ]
    ],
    '1lRsAbWlgJ4VyJIUxe9yHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4267',
        'data' => [
            'x' => '01/01/2000'
        ],
        'rules' => [
            'x' => [
                'date'
            ]
        ],
        'validated' => [
            'x' => '01/01/2000'
        ]
    ],
    '7RNOdCGYOSSKcjmmB6aU6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4279',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2022-12-27 23:29:12.680714',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => [
                'date'
            ]
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2022-12-27 23:29:12.680714',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    '3zOqreO0qxbmLBPs0iyHRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4282',
        'data' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2022-12-27 23:29:12.681117',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => [
                'date'
            ]
        ],
        'validated' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2022-12-27 23:29:12.681117',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    'KARohtSgLxdCJonY5gOJAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4285',
        'data' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01'
        ]
    ],
    '_50bn8eRIb_Cp1pDIfE_fA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4301',
        'data' => [
            'x' => '2013-02'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m'
            ]
        ],
        'validated' => [
            'x' => '2013-02'
        ]
    ],
    'UuDXuguB3nG45nqYsZwh9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4304',
        'data' => [
            'x' => '2000-01-01T00:00:00Atlantic/Azores'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:se'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00Atlantic/Azores'
        ]
    ],
    'VE1rIFAlT93PdWEYhJ-LIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4307',
        'data' => [
            'x' => '2000-01-01T00:00:00Z'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sT'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00Z'
        ]
    ],
    'pgiKkRRFmc0qVRA5Ce_Kxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4310',
        'data' => [
            'x' => '2000-01-01T00:00:00+0000'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sO'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00+0000'
        ]
    ],
    'V4EYRr6cN6Zk9rGWXZsuLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4313',
        'data' => [
            'x' => '2000-01-01T00:00:00+00:30'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d\\TH:i:sP'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01T00:00:00+00:30'
        ]
    ],
    'mgGusz8DWvwCEmxbutYu9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4316',
        'data' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01 17:43:59'
        ]
    ],
    'cgBqtSPCCYLEvrhhy5nUYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4322',
        'data' => [
            'x' => '2000-01-01 17:43:59'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s,H:i:s'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01 17:43:59'
        ]
    ],
    'klm__h3QsCRxMNqqb-DfKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4325',
        'data' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s,H:i:s'
            ]
        ],
        'validated' => [
            'x' => '17:43:59'
        ]
    ],
    '5m01SywJNHt6aABf5495bg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4328',
        'data' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i:s'
            ]
        ],
        'validated' => [
            'x' => '17:43:59'
        ]
    ],
    'lTPaGeeK1Dkh1KqBgUXaHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:4334',
        'data' => [
            'x' => '17:43'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i'
            ]
        ],
        'validated' => [
            'x' => '17:43'
        ]
    ],
    'meBozjEI0nJLj-I50bYDUA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4342',
        'data' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => [
                'date_equals:2000-01-01'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01'
        ]
    ],
    'W9PEwo4_BUiLTRQmFmAP8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4345',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e940a4000000001a8256f6',
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
            'x' => [
                'date_equals:2000-01-01'
            ]
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e940a4000000001a8256f6',
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
        ]
    ],
    'EFsaiYMP84_2d04t_u92eA' => [
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
        'rules' => [
            'ends' => [
                'date_equals:start'
            ]
        ],
        'validated' => [
            'ends' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    'fsMRH7B2WdHba8BvS7vu6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4354',
        'data' => [
            'x' => '2022-12-27'
        ],
        'rules' => [
            'x' => [
                'date_equals:today'
            ]
        ],
        'validated' => [
            'x' => '2022-12-27'
        ]
    ],
    'H1QkkaIT2HhKZQsmpgfYPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4363',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'date_equals:today'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'i9dHJpvMkQgF45uk4KuszQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4372',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'date_equals:2012-01-01 17:44:00'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ]
    ],
    'eG4kzqQgQOgnMMYtohygNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4381',
        'data' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i:s',
                'date_equals:17:44:00'
            ]
        ],
        'validated' => [
            'x' => '17:44:00'
        ]
    ],
    'PFTpmtsVGtXMvkB26Q1z_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:4390',
        'data' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i',
                'date_equals:17:44'
            ]
        ],
        'validated' => [
            'x' => '17:44'
        ]
    ],
    'IbRR6CLZk12gQaB1wbF_ug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4406',
        'data' => [
            'x' => '2018-01-01 00:00:00'
        ],
        'rules' => [
            'x' => [
                'date_equals:now'
            ]
        ],
        'validated' => [
            'x' => '2018-01-01 00:00:00'
        ]
    ],
    'Abz6ISt1wH_k6oW7UHO5Ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4409',
        'data' => [
            'x' => '2018-01-01'
        ],
        'rules' => [
            'x' => [
                'date_equals:today'
            ]
        ],
        'validated' => [
            'x' => '2018-01-01'
        ]
    ],
    'zz-dUBmcFaUuwzoXjtDVMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4418',
        'data' => [
            'x' => '01/01/2018'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'date_equals:today'
            ]
        ],
        'validated' => [
            'x' => '01/01/2018'
        ]
    ],
    'EeTN8GQ3n9uYo6yYGCa_LA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4427',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => [
                'date_equals:today'
            ]
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2018-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    'neO1SxSxLqoAUkWz5BkQdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:4436',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e94098000000001a8256f6',
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
            'x' => [
                'date_equals:today',
                'after:yesterday',
                'before:tomorrow'
            ]
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e94098000000001a8256f6',
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
        ]
    ],
    'TAT89RJIJYDYz_qhIKsOtg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4450',
        'data' => [
            'x' => '2000-01-01'
        ],
        'rules' => [
            'x' => [
                'Before:2012-01-01'
            ]
        ],
        'validated' => [
            'x' => '2000-01-01'
        ]
    ],
    'Cz1DSjEMU3sY1HtPd-eOcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4456',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e940ca000000001a8256f6',
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
            'x' => [
                'Before:2012-01-01'
            ]
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e940ca000000001a8256f6',
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
        ]
    ],
    '0LH9p8NlQJOZWfQUB53xEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4462',
        'data' => [
            'x' => '2012-01-01'
        ],
        'rules' => [
            'x' => [
                'After:2000-01-01'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01'
        ]
    ],
    'k55wg0Nsv2KM1rZSmp3pPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4468',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e9404e000000001a8256f6',
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
            'x' => [
                'After:2000-01-01'
            ]
        ],
        'validated' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000027e9404e000000001a8256f6',
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
        ]
    ],
    'bQN1ZMzPGLcO2mHdIoyPpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4474',
        'data' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'rules' => [
            'start' => [
                'After:2000-01-01'
            ],
            'ends' => [
                'After:start'
            ]
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ]
    ],
    'EA2yKZfmwZUz0gjq7-Ew3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4480',
        'data' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ],
        'rules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => '2013-01-01'
        ]
    ],
    'VEVz-dqQwV9OHvMcDLXmIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4486',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'x' => [
                'Before:2012-01-01'
            ]
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2000-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    'N2INpmzDHo6P8FyC3zuvuQ' => [
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
                'constructedObjectId' => '0000000027e94a78000000001a8256f6',
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
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
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
                'constructedObjectId' => '0000000027e94a78000000001a8256f6',
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
        ]
    ],
    'HVRAq6pjYorb9MbPTynMJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4492',
        'data' => [
            'start' => '2012-01-01',
            'ends' => \DateTime::__set_state([
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'rules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ],
        'validated' => [
            'start' => '2012-01-01',
            'ends' => \DateTime::__set_state([
                'date' => '2013-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ]
    ],
    'Y6HjIoOu9whAlNiFqqltVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4498',
        'data' => [
            'start' => 'today',
            'ends' => 'tomorrow'
        ],
        'rules' => [
            'start' => [
                'Before:ends'
            ],
            'ends' => [
                'After:start'
            ]
        ],
        'validated' => [
            'start' => 'today',
            'ends' => 'tomorrow'
        ]
    ],
    'y4NsvYH2GLvQh9-qSWpFSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4501',
        'data' => [
            'x' => '2012-01-01 17:43:59'
        ],
        'rules' => [
            'x' => [
                'Before:2012-01-01 17:44',
                'After:2012-01-01 17:43:58'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01 17:43:59'
        ]
    ],
    'tzREUfrKVrkXkHdqrQGIwA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4504',
        'data' => [
            'x' => '2012-01-01 17:44:01'
        ],
        'rules' => [
            'x' => [
                'Before:2012-01-01 17:44:02',
                'After:2012-01-01 17:44'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:01'
        ]
    ],
    'HXzmQBik06hyVqWOGhZOFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4513',
        'data' => [
            'x' => '17:43:59'
        ],
        'rules' => [
            'x' => [
                'Before:17:44',
                'After:17:43:58'
            ]
        ],
        'validated' => [
            'x' => '17:43:59'
        ]
    ],
    '3QqoJYk75PdbUJ_ny_4SwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:4516',
        'data' => [
            'x' => '17:44:01'
        ],
        'rules' => [
            'x' => [
                'Before:17:44:02',
                'After:17:44'
            ]
        ],
        'validated' => [
            'x' => '17:44:01'
        ]
    ],
    'oTS_SFDmV2uyznTpq6Mtzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4536',
        'data' => [
            'x' => '31/12/2000'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'before:31/12/2012'
            ]
        ],
        'validated' => [
            'x' => '31/12/2000'
        ]
    ],
    'kxJVH9i6Uz29fhs2QZT9kA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4545',
        'data' => [
            'x' => '31/12/2012'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'after:31/12/2000'
            ]
        ],
        'validated' => [
            'x' => '31/12/2012'
        ]
    ],
    '5Vp4rza1FtavU3w9_Rn1EA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4551',
        'data' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'rules' => [
            'start' => [
                'date_format:d/m/Y',
                'after:31/12/2000'
            ],
            'ends' => [
                'date_format:d/m/Y',
                'after:start'
            ]
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ]
    ],
    'dZvY-hKp-_gsASZZPLZPew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4563',
        'data' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ],
        'rules' => [
            'start' => [
                'date_format:d/m/Y',
                'before:ends'
            ],
            'ends' => [
                'date_format:d/m/Y',
                'after:start'
            ]
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => '31/12/2013'
        ]
    ],
    'Q5QtSPV4tGoowZVTfZP53w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4578',
        'data' => [
            'start' => '31/12/2012',
            'ends' => null
        ],
        'rules' => [
            'start' => [
                'date_format:d/m/Y',
                'before:ends'
            ],
            'ends' => [
                'nullable',
                'date_format:d/m/Y',
                'after:start'
            ]
        ],
        'validated' => [
            'start' => '31/12/2012',
            'ends' => null
        ]
    ],
    'OkUdwStjGWOleKFwJCkDLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4587',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'after:yesterday',
                'before:tomorrow'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'Lzw5ZG2s_pkWgRLunFtlJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4596',
        'data' => [
            'x' => '2022-12-27'
        ],
        'rules' => [
            'x' => [
                'after:yesterday',
                'before:tomorrow'
            ]
        ],
        'validated' => [
            'x' => '2022-12-27'
        ]
    ],
    '_1_IGBOAUf9SyjOpD6X1dg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4605',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'before:2012-01-01 17:44:01',
                'after:2012-01-01 17:43:59'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ]
    ],
    'rsWxmEYluwnGb1rRKjjPGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4614',
        'data' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i:s',
                'before:17:44:01',
                'after:17:43:59'
            ]
        ],
        'validated' => [
            'x' => '17:44:00'
        ]
    ],
    'ERulO08LvVBZfAMj5jRl6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:4623',
        'data' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i',
                'before:17:45',
                'after:17:43'
            ]
        ],
        'validated' => [
            'x' => '17:44'
        ]
    ],
    '4RFK3lXssHRefwXa1JQZAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4643',
        'data' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => [
                'before_or_equal:2012-01-15'
            ]
        ],
        'validated' => [
            'x' => '2012-01-15'
        ]
    ],
    'ULf3JAxOoT34az6K6aYxhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4646',
        'data' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => [
                'before_or_equal:2012-01-16'
            ]
        ],
        'validated' => [
            'x' => '2012-01-15'
        ]
    ],
    'Zb_Gfhsy3_TIyVC5b4xl2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4652',
        'data' => [
            'x' => '15/01/2012'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:15/01/2012'
            ]
        ],
        'validated' => [
            'x' => '15/01/2012'
        ]
    ],
    'M0eLT-97KcC5CwBjH4x0FA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4658',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:today'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'DRCVawE9erOYlfV5armO_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4661',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'before_or_equal:tomorrow'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'z35WntfJ9z9918WbGv8WPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4667',
        'data' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => [
                'after_or_equal:2012-01-15'
            ]
        ],
        'validated' => [
            'x' => '2012-01-15'
        ]
    ],
    '6qSB2OKBM0sEbSAryCwadA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4670',
        'data' => [
            'x' => '2012-01-15'
        ],
        'rules' => [
            'x' => [
                'after_or_equal:2012-01-14'
            ]
        ],
        'validated' => [
            'x' => '2012-01-15'
        ]
    ],
    'kqzm0iLPaXIFrlQXo9d_Qg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4676',
        'data' => [
            'x' => '15/01/2012'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:15/01/2012'
            ]
        ],
        'validated' => [
            'x' => '15/01/2012'
        ]
    ],
    'LL58CLp0tkdG1KQvq0DvZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4682',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:today'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'Asb3YINtMhOcWqwkAVSN9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4685',
        'data' => [
            'x' => '27/12/2022'
        ],
        'rules' => [
            'x' => [
                'date_format:d/m/Y',
                'after_or_equal:yesterday'
            ]
        ],
        'validated' => [
            'x' => '27/12/2022'
        ]
    ],
    'NFrPcS2qu-AjIe2HXIuK7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4691',
        'data' => [
            'x' => '2012-01-01 17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:Y-m-d H:i:s',
                'before_or_equal:2012-01-01 17:44:00',
                'after_or_equal:2012-01-01 17:44:00'
            ]
        ],
        'validated' => [
            'x' => '2012-01-01 17:44:00'
        ]
    ],
    'bpRSug79Vp9DrNwhTusxNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4700',
        'data' => [
            'x' => '17:44:00'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i:s',
                'before_or_equal:17:44:00',
                'after_or_equal:17:44:00'
            ]
        ],
        'validated' => [
            'x' => '17:44:00'
        ]
    ],
    'Jdll4d0D9Ewj7pbW0aTmPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4709',
        'data' => [
            'x' => '17:44'
        ],
        'rules' => [
            'x' => [
                'date_format:H:i',
                'before_or_equal:17:44',
                'after_or_equal:17:44'
            ]
        ],
        'validated' => [
            'x' => '17:44'
        ]
    ],
    'T445vsVmjiFdSdHkrLP2TQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4718',
        'data' => [
            'foo' => '2012-01-14',
            'bar' => '2012-01-15'
        ],
        'rules' => [
            'foo' => [
                'before_or_equal:bar'
            ]
        ],
        'validated' => [
            'foo' => '2012-01-14'
        ]
    ],
    '9lkUiozF40D9ExYvQh5UXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4721',
        'data' => [
            'foo' => '2012-01-15',
            'bar' => '2012-01-15'
        ],
        'rules' => [
            'foo' => [
                'before_or_equal:bar'
            ]
        ],
        'validated' => [
            'foo' => '2012-01-15'
        ]
    ],
    'adTmTF_jcKxGGJZR0AD8cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:4730',
        'data' => [
            'foo' => '2012-01-15 11:00',
            'bar' => null
        ],
        'rules' => [
            'foo' => [
                'date_format:Y-m-d H:i',
                'before_or_equal:bar'
            ],
            'bar' => [
                'date_format:Y-m-d H:i',
                'nullable'
            ]
        ],
        'validated' => [
            'foo' => '2012-01-15 11:00',
            'bar' => null
        ]
    ],
    'XGq8cO4RB6XXm4Onu8HsMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSometimesImplicitEachWithAsterisksBeforeAndAfter:4976',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.0.start' => [
                'before:foo.*.end'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'start' => '2016-04-19'
                ]
            ]
        ]
    ],
    'mnbn_frfBIKbVj-CTO3nYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSometimesImplicitEachWithAsterisksBeforeAndAfter:4986',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.0.start' => [
                'before:foo.*.end'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'start' => '2016-04-19'
                ]
            ]
        ]
    ],
    'cz03pSzB7J6LCnrAMvXkcQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSometimesImplicitEachWithAsterisksBeforeAndAfter:5007',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.0.end' => [
                'after:foo.*.start'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'end' => '2017-04-19'
                ]
            ]
        ]
    ],
    'UaDKorq_6zVb4WpFeKsk7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomDependentValidators:5115',
        'data' => [
            [
                'name' => 'Jamie',
                'age' => 27
            ]
        ],
        'rules' => [
            '0.name' => [
                'dependent_rule:*.age'
            ]
        ],
        'validated' => [
            [
                'name' => 'Jamie'
            ]
        ]
    ],
    'dpX8ZSykvuw4oi3mcJp-WQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5145',
        'data' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'rules' => [
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
        ],
        'validated' => [
            'foo' => [
                5,
                10,
                15
            ]
        ]
    ],
    '10agDDM9ws3iaAR-6nrE9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:5159',
        'data' => [
            'foo' => [
                5,
                10,
                15
            ]
        ],
        'rules' => [
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
        ],
        'validated' => [
            'foo' => [
                5,
                10,
                15
            ]
        ]
    ],
    'TXEK-wKUkdd8-pPzOWcdXg' => [
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
        'rules' => [
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
        ]
    ],
    'Dcps124jTmqaOtMLbn26lQ' => [
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
        'rules' => [
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
        ]
    ],
    'sm_WR9Vr7KwLvj4zNRVWMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:5301',
        'data' => [
            'foo' => [
                'bar' => 'valid'
            ],
            'foo\\.bar' => 'zxc'
        ],
        'rules' => [
            'foo\\.bar' => [
                'required'
            ]
        ],
        'validated' => [
            'foo.bar' => 'zxc'
        ]
    ],
    'G32XyaTuxL0TIyVoWsXyJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5321',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'rules' => [
            'foo' => [
                'required_without:bar.foo\\.bar'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ]
    ],
    'WMPFbs6gIj7DI8ryplr3VA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5324',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'rules' => [
            'foo' => [
                'required_without_all:bar.foo\\.bar'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ]
    ],
    'upYhCSXwkQ0mhO_Vju7CEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5327',
        'data' => [
            'foo' => 'valid',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'rules' => [
            'foo' => [
                'same:bar.foo\\.bar'
            ]
        ],
        'validated' => [
            'foo' => 'valid'
        ]
    ],
    'Av4msLOHWVRp_8Evss5usw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:5330',
        'data' => [
            'foo' => '',
            'bar' => [
                'foo\\.bar' => 'valid'
            ]
        ],
        'rules' => [
            'foo' => [
                'required_unless:bar.foo\\.bar,valid'
            ]
        ],
        'validated' => [
            'foo' => ''
        ]
    ],
    'Wy3pTLbh7xP_iRFcwHfwcg' => [
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
        'rules' => [
            'matrix.\\.0' => [
                'integer'
            ],
            'matrix.1\\.0' => [
                'integer'
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
        ]
    ],
    '8dtptvQik93f_Nbfn2wgVQ' => [
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
        'rules' => [
            'matrix.\\.0' => [
                'integer'
            ],
            'matrix.1\\.0' => [
                'integer'
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
        ]
    ],
    '72RENvjpOxT9tNt2m_ylXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:5390',
        'data' => [
            'foo\\.bar' => 'valid'
        ],
        'rules' => [
            'foo\\.bar' => [
                'required',
                'in:valid'
            ]
        ],
        'validated' => [
            'foo.bar' => 'valid'
        ]
    ],
    'tu1dRWYiXwYim-iZ0hCgCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testImplicitEachWithAsterisksWithArrayValues:5406',
        'data' => [
            'foo' => [
                'bar\\.baz' => ''
            ]
        ],
        'rules' => [
            'foo' => [
                'required'
            ]
        ],
        'validated' => [
            'foo' => [
                'bar.baz' => ''
            ]
        ]
    ],
    'IL3G_O0VYn7nOfMnVCC9ng' => [
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
        'rules' => [
            'products.0.price' => [
                'numeric',
                'min:1'
            ],
            'products.1.price' => [
                'numeric',
                'min:1'
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
        ]
    ],
    'NEtpXYmFWpKZVFKEyYb3Mw' => [
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
        'rules' => [
            'foo.0.password' => [
                'confirmed'
            ],
            'foo.1.password' => [
                'confirmed'
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
        ]
    ],
    'Y8GEpzSdW6gYI3YB5NOnVg' => [
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
        'rules' => [
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
        ]
    ],
    'ZCvT0QqCnyyoiVIp4HAXFQ' => [
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
        'rules' => [
            'foo.0.name' => [
                'different:foo.*.last'
            ],
            'foo.1.name' => [
                'different:foo.*.last'
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
        ]
    ],
    'dv95nrpFkn0C1epo2EL9ZQ' => [
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
        'rules' => [
            'foo.0.bar.0.name' => [
                'different:foo.*.bar.*.last'
            ],
            'foo.0.bar.1.name' => [
                'different:foo.*.bar.*.last'
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
        ]
    ],
    '5HSQKgSyEVt2rJCukoE41w' => [
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
        'rules' => [
            'foo.0.name' => [
                'same:foo.*.last'
            ],
            'foo.1.name' => [
                'same:foo.*.last'
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
        ]
    ],
    'MNZPnzDV9_NirOmRt2F2rQ' => [
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
        'rules' => [
            'foo.0.bar.0.name' => [
                'same:foo.*.bar.*.last'
            ],
            'foo.0.bar.1.name' => [
                'same:foo.*.bar.*.last'
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
        ]
    ],
    'QivdN2pfR9h1bBZ75hDnUg' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required'
            ],
            'foo.1.name' => [
                'Required'
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
        ]
    ],
    'NzFcdiat64Xn1Id7IwfYhw' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required'
            ],
            'foo.1.name' => [
                'Required'
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
        ]
    ],
    'g6z_3RNWRqWnipSUGSRRZg' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_if:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ]
            ]
        ]
    ],
    'lpaATet2phvnJJ7M_48Row' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_if:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_if:foo.*.last,foo'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'name' => 'first'
                ]
            ]
        ]
    ],
    'NxBc_0uuH4j6byT9CUNALg' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_unless:foo.*.last,foo'
            ],
            'foo.1.name' => [
                'Required_unless:foo.*.last,foo'
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
        ]
    ],
    'p0rjpmDfe_e70OcYsT0NQA' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_with:foo.*.last'
            ],
            'foo.1.name' => [
                'Required_with:foo.*.last'
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
        ]
    ],
    'rAbNHKotQmUmRGxOx_ylNQ' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_with:foo.*.last'
            ],
            'foo.1.name' => [
                'Required_with:foo.*.last'
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
        ]
    ],
    'dcvlckdWs2oja-RrPDynfg' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
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
        ]
    ],
    'Tf9BqUaiNe5w1ECTiH0mhg' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_with_all:foo.*.last,foo.*.middle'
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
        ]
    ],
    'ss21F8KJZibjaXLTBN05-g' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without:foo.*.last,foo.*.middle'
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
        ]
    ],
    'i4G4XU_Z4hHKAlDVivi7eQ' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_without:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without:foo.*.last,foo.*.middle'
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
        ]
    ],
    'BfJ9_M2nE6gNo8Db4R2BsA' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.2.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
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
        ]
    ],
    '67NtffV9LEozTXVVb7Tm1A' => [
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
        'rules' => [
            'foo.0.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.1.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
            ],
            'foo.2.name' => [
                'Required_without_all:foo.*.last,foo.*.middle'
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
        ]
    ],
    'BlBfRhvixF3wM2qQx9vs5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:5974',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.0.start' => [
                'before:foo.*.end'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'start' => '2016-04-19'
                ]
            ]
        ]
    ],
    'o0p9MQcUhSdUOniE0JHmIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:5988',
        'data' => [
            'foo' => [
                [
                    'start' => '2016-04-19',
                    'end' => '2017-04-19'
                ]
            ]
        ],
        'rules' => [
            'foo.0.end' => [
                'after:foo.*.start'
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'end' => '2017-04-19'
                ]
            ]
        ]
    ],
    'UzQ2syRvWb6UgFcnQYE-pA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedData:6641',
        'data' => [
            'first' => 'john',
            'preferred' => 'john',
            'last' => 'doe',
            'type' => 'admin'
        ],
        'rules' => [
            'first' => [
                'required'
            ],
            'preferred' => [
                'required'
            ]
        ],
        'validated' => [
            'first' => 'john',
            'preferred' => 'john'
        ]
    ],
    'X5Hd9Ei-CLvdyTO5gUFN9g' => [
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
        'rules' => [
            'nested.foo' => [
                'required'
            ],
            'array.0' => [
                'integer'
            ],
            'array.1' => [
                'integer'
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
        ]
    ],
    'vl8Zy7PuP_vaBrD4gbqsFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedChildRules:6669',
        'data' => [
            'nested' => [
                'foo' => 'bar',
                'with' => 'extras',
                'type' => 'admin'
            ]
        ],
        'rules' => [
            'nested.foo' => [
                'required'
            ]
        ],
        'validated' => [
            'nested' => [
                'foo' => 'bar'
            ]
        ]
    ],
    'QI-03BR36-5DgRzyoeJ8vg' => [
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
        'rules' => [
            'nested.0.bar' => [
                'required'
            ],
            'nested.1.bar' => [
                'required'
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
        ]
    ],
    'wRCwaQUMQYuz9oheArgPOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAndValidatedData:6695',
        'data' => [
            'first' => 'john',
            'preferred' => 'john',
            'last' => 'doe',
            'type' => 'admin'
        ],
        'rules' => [
            'first' => [
                'required'
            ],
            'preferred' => [
                'required'
            ]
        ],
        'validated' => [
            'first' => 'john',
            'preferred' => 'john'
        ]
    ],
    'E303cT-W2R3DQsWARNnb3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ]
    ],
    'xctJoB9iGL_Ch7ex5gVUjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ]
    ],
    'Wvds17Hho1WAmsXVuwRORw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ]
    ],
    'fNfmsazV8489DXHBx1sdOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'e60d3f48-95d7-4d8d-aad0-856f29a27da2'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'e60d3f48-95d7-4d8d-aad0-856f29a27da2'
        ]
    ],
    'KGNy1Y1ttBUyMxWNiLP5-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-11e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-11e1-9b21-0800200c9a66'
        ]
    ],
    'ScBkwRLI7wnaqVHhUfYwbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ]
    ],
    'lNrhL7nWj_kvaldeCl1XIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-31e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-31e1-9b21-0800200c9a66'
        ]
    ],
    '5dLKZHFA93WuMg8QUuyK7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-41e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-41e1-9b21-0800200c9a66'
        ]
    ],
    'WCc-x6RlCWvyHuW0apbThg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-51e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-51e1-9b21-0800200c9a66'
        ]
    ],
    'l8m2qoTQ1V4w7XWn0Pnj3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:6733',
        'data' => [
            'foo' => 'FF6F8CB0-C57D-11E1-9B21-0800200C9A66'
        ],
        'rules' => [
            'foo' => [
                'uuid'
            ]
        ],
        'validated' => [
            'foo' => 'FF6F8CB0-C57D-11E1-9B21-0800200C9A66'
        ]
    ],
    'TM5CRKKSpffkiKE56UAkZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidAscii:6782',
        'data' => [
            'foo' => 'Dusseldorf'
        ],
        'rules' => [
            'foo' => [
                'ascii'
            ]
        ],
        'validated' => [
            'foo' => 'Dusseldorf'
        ]
    ],
    'rcwBhWa9zgeO91NlQGfkaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUlid:6796',
        'data' => [
            'foo' => '01gd6r360bp37zj17nxb55yv40'
        ],
        'rules' => [
            'foo' => [
                'ulid'
            ]
        ],
        'validated' => [
            'foo' => '01gd6r360bp37zj17nxb55yv40'
        ]
    ],
    'uodkJFF1FJY4pp8bJcLFvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointment' => false
        ],
        'rules' => [
            'has_appointment' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_appointment' => false
        ]
    ],
    '4ruiDXS2wBWMhfGQntv4RQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Tom'
        ]
    ],
    '-ptH-eZXbuaihUQY_pATUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
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
        'validated' => [
            'has_appointment' => true,
            'appointment_date' => '2021-03-08'
        ]
    ],
    'cca_xir51oMK8Jt0HP3dzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
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
        'validated' => [
            'has_appointment' => true,
            'appointment_date' => '2019-12-13'
        ]
    ],
    '0Kjme0TLZ3CCBoZDcaJadw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_no_appointments' => true
        ],
        'rules' => [
            'has_no_appointments' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_no_appointments' => true
        ]
    ],
    'lz1rbnhe5dku_V8l7GUsjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
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
            ]
        ],
        'validated' => [
            'has_no_appointments' => false,
            'has_doctor_appointment' => false
        ]
    ],
    'e24UquiAnlqI8wl5HxcRng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false,
            'appointments' => []
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_appointments' => false
        ]
    ],
    'zrE3P1nqOgwDB1BgzXBn_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false,
            'appointments' => [
                []
            ]
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_appointments' => false
        ]
    ],
    'lecyKTG0qSkwSemZxFSJdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:7027',
        'data' => [
            'has_appointments' => false
        ],
        'rules' => [
            'has_appointments' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_appointments' => false
        ]
    ],
    '5bcQr2Z_D79fi5EThmvrrg' => [
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
        'rules' => [
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
        ]
    ],
    'xut-MslCB_L3k7rueFakhA' => [
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
        'rules' => [
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
        ]
    ],
    'bwUo7darxCHZRcrVq4VjXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExclude:7184',
        'data' => [
            'has_appointment' => false
        ],
        'rules' => [
            'has_appointment' => [
                'required',
                'bool'
            ]
        ],
        'validated' => [
            'has_appointment' => false
        ]
    ],
    'nj2Luukxn1SFbuN_0Taqrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7207',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'rules' => [
            'users' => [
                'array'
            ],
            'users.0.name' => [
                'string'
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ]
    ],
    'G3nzZNcGSI0ne6ozFcBHZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7216',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'rules' => [
            'users' => [
                'array'
            ],
            'users.0.name' => [
                'string'
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed'
                ]
            ]
        ]
    ],
    'ICdjblbIyvKAJT1O1tGGDw' => [
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
        'rules' => [
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
        ]
    ],
    'kf4UvsxpMU-LzHCgy4f7bA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7234',
        'data' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ],
        'rules' => [
            'users' => [
                'array'
            ]
        ],
        'validated' => [
            'users' => [
                [
                    'name' => 'Mohamed',
                    'location' => 'cairo'
                ]
            ]
        ]
    ],
    'cmqOzUpf35RVIwoDkHTf-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7243',
        'data' => [
            'users' => [
                'mohamed',
                'zain'
            ]
        ],
        'rules' => [
            'users' => [
                'array'
            ],
            'users.0' => [
                'string'
            ],
            'users.1' => [
                'string'
            ]
        ],
        'validated' => [
            'users' => [
                'mohamed',
                'zain'
            ]
        ]
    ],
    'CVlNwWTgpkB4W6KR_IL8WQ' => [
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
        'rules' => [
            'users' => [
                'array'
            ],
            'users.admins' => [
                'array'
            ],
            'users.admins.0.name' => [
                'string'
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
        ]
    ],
    'HCpteKD8sfOyJzHSUOysvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:7261',
        'data' => [
            'users' => [
                1,
                2,
                3
            ]
        ],
        'rules' => [
            'users' => [
                'array',
                'max:10'
            ]
        ],
        'validated' => [
            'users' => [
                1,
                2,
                3
            ]
        ]
    ],
    '8OKdi8LBCO4kmG8hpZENpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7272',
        'data' => [
            'cat' => 'Felix'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Felix'
        ]
    ],
    'FXkgtcj8gddCkF6IvN3JqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7280',
        'data' => [
            'cat' => 'Felix'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Felix'
        ]
    ],
    'zPBZKK5dJgmCPDDUwretmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7288',
        'data' => [
            'cat' => 'Tom',
            'mouse' => 'Jerry'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ],
            'mouse' => [
                'exclude_unless:cat,Tom',
                'required',
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Tom',
            'mouse' => 'Jerry'
        ]
    ],
    'evCXBPv_5UXyDNqrFuweiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7304',
        'data' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => [
                'nullable'
            ]
        ],
        'validated' => [
            'foo' => true
        ]
    ],
    'iiFxELrgpchc_Ynv8atKyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:7314',
        'data' => [
            'bar' => 'Hello'
        ],
        'rules' => [
            'bar' => [
                'exclude_unless:foo,null'
            ]
        ],
        'validated' => [
            'bar' => 'Hello'
        ]
    ],
    'mwN1yvjFKsCVIKyE8J6HOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeValuesAreReallyRemoved:7340',
        'data' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => [
                'required',
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Tom'
        ]
    ],
    'V7orT8HWbr0m8xqUvtGvvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeWithValuesAreReallyRemoved:7369',
        'data' => [
            'cat' => 'Tom'
        ],
        'rules' => [
            'cat' => [
                'string'
            ]
        ],
        'validated' => [
            'cat' => 'Tom'
        ]
    ],
    '5Hm4MOM_hOtz3nH4MT7uNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWhenHasKeys:7443',
        'data' => [
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
        'validated' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ]
    ],
    'uEB4JJjovmBZGS4SY4GkDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWithPartialMatch:7466',
        'data' => [
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
        'validated' => [
            'baz' => [
                'foo' => 'bar',
                'fee' => 'faa',
                'laa' => 'lee'
            ]
        ]
    ]
];