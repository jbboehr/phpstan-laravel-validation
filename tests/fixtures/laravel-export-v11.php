<?php /* laravel 11.55.0 commit dc7ec34ae95bacf4a63b96ec81482b4f3e702289 */ return [
    'sn7KL9U9TKZObc8bsTUMIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationArrayRuleTest::testArrayValidation:50',
        'data' => [
            'foo' => [
                'bar'
            ]
        ],
        'validated' => [
            'foo' => [
                'bar'
            ]
        ],
        'rules' => [
            'foo' => 'array'
        ],
        'expandedRules' => [
            'foo' => [
                'array'
            ]
        ]
    ],
    'C3Ef-hqsvi-P8hH4kI6h7A' => [
        'location' => 'Tests\\Unit\\Rules\\ValidationDateRuleTest::testDateValidation:136',
        'data' => [
            'date' => '2024-02-01'
        ],
        'validated' => [
            'date' => '2024-02-01'
        ],
        'rules' => [
            'date' => 'date|after:2024-01-01|before:2025-01-01'
        ],
        'expandedRules' => [
            'date' => [
                'date',
                'after:2024-01-01',
                'before:2025-01-01'
            ]
        ]
    ],
    'qcoWx2Jx2b9Fup5UtNVB_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationExceptionTest::testExceptionSummarizesZeroErrors:15',
        'data' => [],
        'validated' => [],
        'rules' => [],
        'expandedRules' => []
    ],
    '2mAGj-MmZ3ut1jD8ciefHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationExceptionTest::testExceptionErrorZeroErrors:115',
        'data' => [],
        'validated' => [],
        'rules' => [],
        'expandedRules' => []
    ],
    'kJYlDSFwxlzX6IXZomgHkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationExcludeIfTest::testExcludeIfRuleValidation:79',
        'data' => [
            'bar' => 'BAR'
        ],
        'validated' => [
            'bar' => 'BAR'
        ],
        'rules' => [
            'foo' => 'exclude',
            'bar' => 'nullable'
        ],
        'expandedRules' => [
            'bar' => [
                'nullable'
            ]
        ]
    ],
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
    'ZelKlnPR3yf5IGWwes8SIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationInRuleTest::testInRuleValidation:84',
        'data' => [
            'x' => 'foo'
        ],
        'validated' => [
            'x' => 'foo'
        ],
        'rules' => [
            'x' => 'in:"foo","bar"'
        ],
        'expandedRules' => [
            'x' => [
                'in:"foo","bar"'
            ]
        ]
    ],
    'KOYfgW3ESRX0ly_mKwr3qg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNotInRuleTest::testNotInRuleValidation:80',
        'data' => [
            'x' => 'foo'
        ],
        'validated' => [
            'x' => 'foo'
        ],
        'rules' => [
            'x' => 'not_in:"bar","baz"'
        ],
        'expandedRules' => [
            'x' => [
                'not_in:"bar","baz"'
            ]
        ]
    ],
    'hyiD7rmPYh34af2nWxFXDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:190',
        'data' => [
            'numeric' => '50'
        ],
        'validated' => [
            'numeric' => '50'
        ],
        'rules' => [
            'numeric' => 'numeric|between:10,100'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'between:10,100'
            ]
        ]
    ],
    'ZxLj5mfw6rXFejB8iPzOZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:200',
        'data' => [
            'numeric' => '50',
            'some_field' => '100'
        ],
        'validated' => [
            'numeric' => '50'
        ],
        'rules' => [
            'numeric' => 'numeric|different:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'different:some_field'
            ]
        ]
    ],
    'Uf7JvHLqpuQACkdkuOaTFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:210',
        'data' => [
            'numeric' => '10'
        ],
        'validated' => [
            'numeric' => '10'
        ],
        'rules' => [
            'numeric' => 'numeric|integer|digits:2'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'integer',
                'digits:2'
            ]
        ]
    ],
    'Hz1CQFf2VB5op-41Kf_-iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:220',
        'data' => [
            'numeric' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|integer|digits_between:2,4'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'integer',
                'digits_between:2,4'
            ]
        ]
    ],
    'pZciMjHKjzLAj6rvJ3EVqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:230',
        'data' => [
            'numeric' => '100',
            'some_field' => '10'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|gt:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'gt:some_field'
            ]
        ]
    ],
    'RX7rk7MtDqYeLHajAaY_Lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:240',
        'data' => [
            'numeric' => '100',
            'some_field' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|gte:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'gte:some_field'
            ]
        ]
    ],
    'Vvf2k92kqgu1RusxPd-sKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:250',
        'data' => [
            'numeric' => '10'
        ],
        'validated' => [
            'numeric' => '10'
        ],
        'rules' => [
            'numeric' => 'numeric|integer'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'integer'
            ]
        ]
    ],
    '10UrmbSg8In8PgFTKTr81g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:260',
        'data' => [
            'numeric' => '100',
            'some_field' => '150'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|lt:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'lt:some_field'
            ]
        ]
    ],
    'DSwjJVVaPQB_RGh5CKSdKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:270',
        'data' => [
            'numeric' => '100',
            'some_field' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|lte:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'lte:some_field'
            ]
        ]
    ],
    'uLk7cOd48BTL82JoHUEwcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:280',
        'data' => [
            'numeric' => '200'
        ],
        'validated' => [
            'numeric' => '200'
        ],
        'rules' => [
            'numeric' => 'numeric|max:200'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'max:200'
            ]
        ]
    ],
    'tKCdKtCOD_1dH0K4P2pVRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:290',
        'data' => [
            'numeric' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|max_digits:3'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'max_digits:3'
            ]
        ]
    ],
    'gc2jR6Wfn5BPTMmhaDAsbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:300',
        'data' => [
            'numeric' => '10'
        ],
        'validated' => [
            'numeric' => '10'
        ],
        'rules' => [
            'numeric' => 'numeric|min:2'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'min:2'
            ]
        ]
    ],
    'rmm-kRbVKbX6TMVT17NBBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:310',
        'data' => [
            'numeric' => '10'
        ],
        'validated' => [
            'numeric' => '10'
        ],
        'rules' => [
            'numeric' => 'numeric|min_digits:2'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'min_digits:2'
            ]
        ]
    ],
    'NvsHmliUVAw2qUVSJWdznA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:320',
        'data' => [
            'numeric' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|multiple_of:10'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'multiple_of:10'
            ]
        ]
    ],
    '_XdnJ6s0g4t96_DCB5E7zw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:330',
        'data' => [
            'numeric' => '100',
            'some_field' => '100'
        ],
        'validated' => [
            'numeric' => '100'
        ],
        'rules' => [
            'numeric' => 'numeric|same:some_field'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'same:some_field'
            ]
        ]
    ],
    '5ilo2XzIU7sb7zBehadQzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:340',
        'data' => [
            'numeric' => '10'
        ],
        'validated' => [
            'numeric' => '10'
        ],
        'rules' => [
            'numeric' => 'numeric|integer|size:10'
        ],
        'expandedRules' => [
            'numeric' => [
                'numeric',
                'integer',
                'size:10'
            ]
        ]
    ],
    'QxeP9A8zOP9QuoT_0_Ybmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationProhibitedIfTest::testProhibitedIfRuleValidation:74',
        'data' => [
            'y' => 'foo'
        ],
        'validated' => [],
        'rules' => [
            'x' => 'prohibited'
        ],
        'expandedRules' => [
            'x' => [
                'prohibited'
            ]
        ]
    ],
    'p3IxH2DkmoR-vLEJeBYTwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnNestedArrays:118',
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
    'dsk8h7vqRzWoN20gnoqsUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnArrays:149',
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
    '4VTfKj7wmSJLy-R-sk7x2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntThrowOnPass:167',
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
    'BOSmwqMWnCsSgkDwaDJQIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:196',
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
    'giZppKakNcTPgyhW8x9YFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:199',
        'data' => [],
        'validated' => [],
        'rules' => [],
        'expandedRules' => []
    ],
    'dB5wP8tMEsGeAZZz4Tov3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:202',
        'data' => [
            'foo' => 'bar',
            'baz' => 'qux'
        ],
        'validated' => [
            'foo' => 'bar',
            'baz' => 'qux'
        ],
        'rules' => [
            'foo' => 'required',
            'baz' => 'required'
        ],
        'expandedRules' => [
            'foo' => [
                'required'
            ],
            'baz' => [
                'required'
            ]
        ]
    ],
    'GLGPvkVyrznrFp4Bnz7PyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testHasFailedValidationRules:213',
        'data' => [
            'foo' => 'bar',
            'baz' => 'bar'
        ],
        'validated' => [
            'foo' => 'bar',
            'baz' => 'bar'
        ],
        'rules' => [
            'foo' => 'Same:baz',
            'baz' => 'Required'
        ],
        'expandedRules' => [
            'foo' => [
                'Same:baz'
            ],
            'baz' => [
                'Required'
            ]
        ]
    ],
    'Z_a1FzjCTjiUNLDb__PKXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testHasNotFailedValidationRules:230',
        'data' => [
            'foo' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'name' => 'Confirmed'
        ],
        'expandedRules' => [
            'name' => [
                'Confirmed'
            ]
        ]
    ],
    '7zoShGxEpmC5dTo2F4cNFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesCanSkipRequiredRules:239',
        'data' => [],
        'validated' => [],
        'rules' => [
            'name' => 'sometimes|required'
        ],
        'expandedRules' => [
            'name' => [
                'sometimes',
                'required'
            ]
        ]
    ],
    '4KsZyT6jddeIwksXjM4w0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testInValidatableRulesReturnsValid:248',
        'data' => [
            'foo' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'name' => 'Confirmed'
        ],
        'expandedRules' => [
            'name' => [
                'Confirmed'
            ]
        ]
    ],
    'RhT2sCMpYEE7zzyZr9lWPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUsingNestedValidationRulesPasses:268',
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
    'fL_D00pVi5kd8C8DkislVw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:287',
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
    'mldfgVVIGBkcA0UCUhhhFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:290',
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
    'Iqshj9zjSTM00nCREY71yw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:293',
        'data' => [
            'x' => ''
        ],
        'validated' => [
            'x' => ''
        ],
        'rules' => [
            'x' => 'integer'
        ],
        'expandedRules' => [
            'x' => [
                'integer'
            ]
        ]
    ],
    'Ucw-be9-EwIAccvBnhdmFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:296',
        'data' => [
            'x' => ''
        ],
        'validated' => [
            'x' => ''
        ],
        'rules' => [
            'x' => 'min:5'
        ],
        'expandedRules' => [
            'x' => [
                'min:5'
            ]
        ]
    ],
    'cBPr3QS1x-Jxl42Ufrb4-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:304',
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
    'kJSoXgokZsMcnNiat7_uIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:319',
        'data' => [],
        'validated' => [],
        'rules' => [
            'x' => 'string',
            'y' => 'numeric',
            'z' => 'integer',
            'a' => 'boolean',
            'b' => 'array'
        ],
        'expandedRules' => [
            'x' => [
                'string'
            ],
            'y' => [
                'numeric'
            ],
            'z' => [
                'integer'
            ],
            'a' => [
                'boolean'
            ],
            'b' => [
                'array'
            ]
        ]
    ],
    'BEVPKszzmxalD7dADnCxjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullable:331',
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
    'ikq311mHrjlihFch5IC3Qw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayNullableWithUnvalidatedArrayKeys:356',
        'data' => [
            'x' => null
        ],
        'validated' => [
            'x' => null
        ],
        'rules' => [
            'x' => 'array|nullable',
            'x.key' => 'string'
        ],
        'expandedRules' => [
            'x' => [
                'array',
                'nullable'
            ],
            'x.key' => [
                'string'
            ]
        ]
    ],
    'gyZt0a-DL5BzG6zNegd02w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullableMakesNoDifferenceIfImplicitRuleExists:378',
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
    'ifCiZSR8xyvSGdv469J_PA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testIndexValuesAreReplaced:817',
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
    'vqxo-qJeLlkioRmTxDpEPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPositionValuesAreReplaced:849',
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
    'MjGe9X6xzoRu7z49jW7Scg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArray:1074',
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
    'ZIml058Q_KpxF0nyBrjnzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateList:1085',
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
            'foo' => 'list'
        ],
        'expandedRules' => [
            'foo' => [
                'list'
            ]
        ]
    ],
    '2QMI0Ye7vHToLI9KydKjaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:1103',
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
    'M06dPB6vFTREkP2AE5sAEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:1107',
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
    '6dLJuGHgpnbARu8A4s_-JA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1181',
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
    'KSSdW-uyQyJ4ctOgm9-Nng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1205',
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
    'pwfiI3CEvX4XZGwhwSiSXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1212',
        'data' => [],
        'validated' => [],
        'rules' => [
            'name' => 'filled'
        ],
        'expandedRules' => [
            'name' => [
                'filled'
            ]
        ]
    ],
    'M8mxxaaFnyAnmuW6Bo-aGw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1218',
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
    '3rOJSJDWU_CPAxeqqFmOMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1258',
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
    'nCGjtb12H4uF0_4wvWXw0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1261',
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
    'Ap1hMzCou0O1LTBMbMIUQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1270',
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
    'j5tTUxclQlt2TWeNmQLneQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1273',
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
    '-NX-326INbOq9lKp2015kA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1286',
        'data' => [
            'bar' => 1,
            'foo' => null
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'present_if:bar,2'
        ],
        'expandedRules' => [
            'foo' => [
                'present_if:bar,2'
            ]
        ]
    ],
    'ImUkEuGsG80RpauxvYceqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1289',
        'data' => [
            'bar' => 1,
            'foo' => ''
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'present_if:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_if:bar,1'
            ]
        ]
    ],
    'r0eS-Sy_mqeD6JHyOvOo1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1296',
        'data' => [
            'bar' => 1,
            'foo' => [
                [
                    'id' => '',
                    'name' => 'a'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_if:bar,1'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_if:bar,1'
            ]
        ]
    ],
    'y9dcY_-Rokc1QtKCduY1DQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1299',
        'data' => [
            'bar' => 1,
            'foo' => [
                [
                    'id' => null,
                    'name' => 'a'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_if:bar,1'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_if:bar,1'
            ]
        ]
    ],
    'U1mtmZCYDGIKoMUU9bj_bg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1302',
        'data' => [
            'bar' => 1,
            'foo' => '2'
        ],
        'validated' => [
            'foo' => '2'
        ],
        'rules' => [
            'foo' => 'present_if:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_if:bar,1'
            ]
        ]
    ],
    'pGfot8As5FTwsC_Nyprw6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1305',
        'data' => [
            'bar' => 2
        ],
        'validated' => [],
        'rules' => [
            'foo' => 'present_if:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_if:bar,1'
            ]
        ]
    ],
    'g3d4n3Q-_3HzJHsa3szGEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1318',
        'data' => [
            'bar' => 2,
            'foo' => null
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    'G7dPs9NXPjgHBo6oOLDVzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1321',
        'data' => [
            'bar' => 2,
            'foo' => ''
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    'Z-nvZKiFw25ONjB55MKRag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1328',
        'data' => [
            'bar' => 2,
            'foo' => [
                [
                    'id' => '',
                    'name' => 'a'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    'nfCRnGVprj_wcss35xAxaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1331',
        'data' => [
            'bar' => 2,
            'foo' => [
                [
                    'id' => null,
                    'name' => 'a'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    '7tZcMvRjNbnTLay1fuMQ6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1334',
        'data' => [
            'bar' => 2,
            'foo' => '2'
        ],
        'validated' => [
            'foo' => '2'
        ],
        'rules' => [
            'foo' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    'jtGwJ11hj36h2aHyZxKOVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1337',
        'data' => [
            'bar' => 1
        ],
        'validated' => [],
        'rules' => [
            'foo' => 'present_unless:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'present_unless:bar,1'
            ]
        ]
    ],
    '0JxV5_P9sBCcmt9XbwD3Ug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1346',
        'data' => [
            'foo' => 1,
            'bar' => 2
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with:bar'
            ]
        ]
    ],
    'BaEtAkPresByzkRo32mCqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1349',
        'data' => [
            'foo' => null,
            'bar' => 2
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with:bar'
            ]
        ]
    ],
    '1pe24s1UJAqZT-8V9jNaig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1352',
        'data' => [
            'foo' => '',
            'bar' => 2
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with:bar'
            ]
        ]
    ],
    'UQm8jk-Dg_2LT56IgUpWkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1359',
        'data' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ],
            'bar' => 2
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_with:bar'
            ]
        ]
    ],
    'U5NbzTFnjD9AlSTXpPhaLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1362',
        'data' => [
            'foo' => [
                [
                    'id' => null
                ]
            ],
            'bar' => 2
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_with:bar'
            ]
        ]
    ],
    'KSIlpftpnqmBa-bz4zqY6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1365',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'present_with:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with:bar'
            ]
        ]
    ],
    'y1I-TG3P8W-9Cevn9Pxg3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1378',
        'data' => [
            'foo' => 1,
            'bar' => 2,
            'baz' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    '4bIY_8tVickxxaXnX-_Zjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1381',
        'data' => [
            'foo' => null,
            'bar' => 2,
            'baz' => 1
        ],
        'validated' => [
            'foo' => null
        ],
        'rules' => [
            'foo' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    'fo_rmATaG5Ggp5OnUNqfCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1384',
        'data' => [
            'foo' => '',
            'bar' => 2,
            'baz' => 1
        ],
        'validated' => [
            'foo' => ''
        ],
        'rules' => [
            'foo' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    'B7ACCoLvNce8Z3ShXGh8fw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1391',
        'data' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ],
            'bar' => 2,
            'baz' => 1
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => ''
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    'C51f799Yvr1wyIrcO5BZCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1394',
        'data' => [
            'foo' => [
                [
                    'id' => null
                ]
            ],
            'bar' => 2,
            'baz' => 1
        ],
        'validated' => [
            'foo' => [
                [
                    'id' => null
                ]
            ]
        ],
        'rules' => [
            'foo.*.id' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo.0.id' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    'VDEbNPDhdjkp1QV2fBewkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1397',
        'data' => [
            'foo' => 1,
            'bar' => 2
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'present_with_all:bar,baz'
        ],
        'expandedRules' => [
            'foo' => [
                'present_with_all:bar,baz'
            ]
        ]
    ],
    'bUbuSdmwUD7Wo2wecJWy4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1414',
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
    'VKig4pc1O6SbsHMcbQDOdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1422',
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
    'Wox3RrIbScSmarVGM9nH9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1427',
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
    'H3DaGuO2j0dGjliuMBTXFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1430',
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
    '1SGbNyu2ESVP8JGcZozLmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1443',
        'data' => [
            'first' => ''
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_with:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_with:first'
            ]
        ]
    ],
    'ywvEqiWh4diNhZlkcArUYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1446',
        'data' => [],
        'validated' => [],
        'rules' => [
            'last' => 'required_with:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_with:first'
            ]
        ]
    ],
    'AnJi_WFU0PsYzi0ZuV71uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1449',
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
    'QHHxSDAAXfbljbviIJOHdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1453',
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
    'D1jJPzVMbvqFbXKuuzTw5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1458',
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
    'PQzRPo2G1C_viK4En7NDjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithAll:1470',
        'data' => [
            'first' => 'foo'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_with_all:first,foo'
        ],
        'expandedRules' => [
            'last' => [
                'required_with_all:first,foo'
            ]
        ]
    ],
    'Sr8LAyTumvGWpaiTcoEymQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1480',
        'data' => [
            'first' => 'Taylor'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_without:first'
        ],
        'expandedRules' => [
            'last' => [
                'required_without:first'
            ]
        ]
    ],
    'Ph8VMi5dzK3kUzPW2G68GQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1483',
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
    'YhSEoU7LmEXn4vccH94Muw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1492',
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
    'EE-0atyRweyxGA6EgweYeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1495',
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
    'rkJVBsBqvWL5ZgH_ZT-8iA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1507',
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
    '5ZpQHZFY7hkFYXSevcysFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1512',
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
    '_h0TO8mrdBOU-IXgFaXSyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1517',
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
    '1Auvw5xJkK1mxTu3inTB5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1522',
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
    'w2h0r2NH1y0L616yjrg_Sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1553',
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
    'Xc_D2q5HhjH8Tm5EbsGfyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1556',
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
    'O6wj5pFS_-eN9F55Z50tRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1559',
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
    'HHr-42NHujn5D5p2Xc97hQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1562',
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
    'w_vE9PkFLq_K8mgi_AWBWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1579',
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
    '-c8_iSdJtsA6ut2YDJdC5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1582',
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
    '2Lh19MPVHaN6CxdbqWn_Hg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1585',
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
    'QEaFMpLGKOhUN_iulvNEtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1588',
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
    'ja4pelGNKJKA3ITMiw0YLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1591',
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
    'jYoQ4menZPj2ptg56hXZBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1594',
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
    'i1uglL9I4r7_V4CZ6pouug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1597',
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
    'rF-iNDHmX51jaZSkejMZmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1608',
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
    'U3LEe1nokxPuxeFYWlKApQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1612',
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
    '45IciqFddOZN_uonH4-mIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1616',
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
    'zCVp_njzcedloRMhKoehSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1620',
        'data' => [
            'foo' => true
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_if:foo,false'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if:foo,false'
            ]
        ]
    ],
    'CTJ5P2pX436TurjQD_d9_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1624',
        'data' => [
            'foo' => true
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_if:foo,null'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if:foo,null'
            ]
        ]
    ],
    '9ksLktYmHDIY06mLWcP7Sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1673',
        'data' => [],
        'validated' => [],
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
    'j_XXXMJzX3ct4MJQUAXk-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1680',
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
    'aj1e_bJBVuI_FOjJLla7kA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1687',
        'data' => [],
        'validated' => [],
        'rules' => [
            'foo' => 'nullable|boolean',
            'baz' => 'nullable|required_if:foo,null'
        ],
        'expandedRules' => [
            'foo' => [
                'nullable',
                'boolean'
            ],
            'baz' => [
                'nullable',
                'required_if:foo,null'
            ]
        ]
    ],
    'asIerOtC43Txj0PL5pCynA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1705',
        'data' => [
            'foo' => 'bar',
            'customfield' => [
                1 => 'taylor'
            ]
        ],
        'validated' => [
            'customfield' => [
                1 => 'taylor'
            ],
            'foo' => 'bar'
        ],
        'rules' => [
            'customfield' => [
                'nullable',
                'array'
            ],
            'foo' => 'required_if:customfield.1,taylor'
        ],
        'expandedRules' => [
            'customfield' => [
                'nullable',
                'array'
            ],
            'foo' => [
                'required_if:customfield.1,taylor'
            ]
        ]
    ],
    'aRsIHiYE8W1AKCHt0EXYJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1739',
        'data' => [
            'first' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_unless:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'required_unless:first,taylor'
            ]
        ]
    ],
    'K-zmsCHgwBcm0w03h8q11Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1743',
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
    '0_5MXuCRZqGOAayfFZZ2VQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1747',
        'data' => [
            'first' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_unless:first,taylor,sven'
        ],
        'expandedRules' => [
            'last' => [
                'required_unless:first,taylor,sven'
            ]
        ]
    ],
    'eL67_BnmqTtuKq_-qeQaDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1751',
        'data' => [
            'first' => 'sven'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'required_unless:first,taylor,sven'
        ],
        'expandedRules' => [
            'last' => [
                'required_unless:first,taylor,sven'
            ]
        ]
    ],
    'IoIgx55NaIi_vGhbMknSgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1755',
        'data' => [
            'foo' => false
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,false'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,false'
            ]
        ]
    ],
    'rtrisZikrTbmiEGKFsSAZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1763',
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
    '9EcFOZ3XnvBs7iSMmZWgnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1771',
        'data' => [],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,null'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,null'
            ]
        ]
    ],
    'VRRmMeZuY-ILY4NrOTyYhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1779',
        'data' => [
            'foo' => '0'
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,0'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,0'
            ]
        ]
    ],
    'pPpyULquP1TBCGfO8585-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1783',
        'data' => [
            'foo' => 0
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,0'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,0'
            ]
        ]
    ],
    'S_3zA2Wdl2ekDFKRTE0xGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1787',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,1'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,1'
            ]
        ]
    ],
    'eowYaXvPjEdyzFNAfx6agA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1791',
        'data' => [
            'foo' => 1
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'required_unless:foo,1'
        ],
        'expandedRules' => [
            'bar' => [
                'required_unless:foo,1'
            ]
        ]
    ],
    '_pK62I74hGdYZYnkHhGnWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:1806',
        'data' => [],
        'validated' => [],
        'rules' => [
            'name' => 'prohibited'
        ],
        'expandedRules' => [
            'name' => [
                'prohibited'
            ]
        ]
    ],
    'HiCY_9b0Tuzc2H2M4YgfbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:1809',
        'data' => [
            'last' => 'bar'
        ],
        'validated' => [],
        'rules' => [
            'name' => 'prohibited'
        ],
        'expandedRules' => [
            'name' => [
                'prohibited'
            ]
        ]
    ],
    '4Atwhrth6COLY4CY2KPZAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:1816',
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
            'name' => 'prohibited'
        ],
        'expandedRules' => [
            'name' => [
                'prohibited'
            ]
        ]
    ],
    'ATe5Yi5IkIevv7YBC70pZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:1839',
        'data' => [
            'first' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'prohibited_if:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_if:first,taylor'
            ]
        ]
    ],
    'oe6neHQaJowl3gZctgBzZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:1847',
        'data' => [
            'first' => 'taylor'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'prohibited_if:first,taylor,jess'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_if:first,taylor,jess'
            ]
        ]
    ],
    'bKd7nxEjKMHcE4yRbbWOQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:1851',
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
    'PD_1dlXluzsPqE01FXb15w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:1873',
        'data' => [
            'foo' => 'on',
            'bar' => ''
        ],
        'validated' => [
            'bar' => ''
        ],
        'rules' => [
            'bar' => 'prohibited_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_accepted:foo'
            ]
        ]
    ],
    'ovTUgBa5G6V2jgw7WSgzrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:1881',
        'data' => [
            'foo' => 1,
            'bar' => null
        ],
        'validated' => [
            'bar' => null
        ],
        'rules' => [
            'bar' => 'prohibited_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_accepted:foo'
            ]
        ]
    ],
    'cwXa4lwueCMhAJKWeFaB8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:1889',
        'data' => [
            'foo' => true
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'prohibited_if_accepted:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_accepted:foo'
            ]
        ]
    ],
    'SLpQVGnqLYIKF2vUZxLBlA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:1907',
        'data' => [
            'foo' => 'off',
            'bar' => ''
        ],
        'validated' => [
            'bar' => ''
        ],
        'rules' => [
            'bar' => 'prohibited_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_declined:foo'
            ]
        ]
    ],
    'PsuGLgxAGcQ4YNXDy0r0Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:1915',
        'data' => [
            'foo' => 0,
            'bar' => null
        ],
        'validated' => [
            'bar' => null
        ],
        'rules' => [
            'bar' => 'prohibited_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_declined:foo'
            ]
        ]
    ],
    'b5wYFv4cgzvo--LSVrHV1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:1923',
        'data' => [
            'foo' => false
        ],
        'validated' => [],
        'rules' => [
            'bar' => 'prohibited_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'prohibited_if_declined:foo'
            ]
        ]
    ],
    '3to-bwT4g5JorHG4SVjuLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1941',
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
    'Jn8luNQajiec9UtuNU6hzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1945',
        'data' => [
            'first' => 'jess'
        ],
        'validated' => [],
        'rules' => [
            'last' => 'prohibited_unless:first,taylor'
        ],
        'expandedRules' => [
            'last' => [
                'prohibited_unless:first,taylor'
            ]
        ]
    ],
    'XgMKHmWH6gBH5uQ8UgrPaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1949',
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
    'ru8Gm3o39L1hogSHoro2Iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1953',
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
    '-AFutAWQteQ574D_abOlSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:1957',
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
    'WKmitAhk8NAuTc9aqThrCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1979',
        'data' => [
            'email' => 'foo',
            'emails' => []
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
    'p3dzr5cMpGR1_DZy3-Q2dQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1983',
        'data' => [
            'email' => 'foo',
            'emails' => ''
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
    'QyFT_97jG587sKsJGKDXxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1987',
        'data' => [
            'email' => 'foo',
            'emails' => null
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
    'm9rVWnxYYiwonxCsZoW0lQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:1999',
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
    'k8ds1jVvL-C3vNxCvFSZLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2003',
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
    '44AFZ1jtELnt8eE95l1FEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [],
        'validated' => [],
        'rules' => [
            'p' => 'prohibited'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited'
            ]
        ]
    ],
    'jXKdR0RaxxwkiSnwvyRkwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'p' => ''
        ],
        'validated' => [
            'p' => ''
        ],
        'rules' => [
            'p' => 'prohibited'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited'
            ]
        ]
    ],
    'FqcYRZsqGtqRqRMvUJp_IQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'p' => ' '
        ],
        'validated' => [
            'p' => ' '
        ],
        'rules' => [
            'p' => 'prohibited'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited'
            ]
        ]
    ],
    'mPivP8PzNDxZIynL4f45mg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'p' => null
        ],
        'validated' => [
            'p' => null
        ],
        'rules' => [
            'p' => 'prohibited'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited'
            ]
        ]
    ],
    'RLoPVVmVqz4h3KcOoq5gsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'p' => []
        ],
        'validated' => [
            'p' => []
        ],
        'rules' => [
            'p' => 'prohibited'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited'
            ]
        ]
    ],
    'q_0VwTSPUgiyAYAZ1e5Y-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 1
        ],
        'validated' => [],
        'rules' => [
            'p' => 'prohibited_if:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_if:bar,1'
            ]
        ]
    ],
    'R3X77XjDzt8HD5RBuAAfoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 1,
            'p' => ''
        ],
        'validated' => [
            'p' => ''
        ],
        'rules' => [
            'p' => 'prohibited_if:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_if:bar,1'
            ]
        ]
    ],
    'iKHWNtEuVJfjL3VZkIFbeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 1,
            'p' => ' '
        ],
        'validated' => [
            'p' => ' '
        ],
        'rules' => [
            'p' => 'prohibited_if:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_if:bar,1'
            ]
        ]
    ],
    'yyonkxSyq3bDL3pJTSleXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 1,
            'p' => null
        ],
        'validated' => [
            'p' => null
        ],
        'rules' => [
            'p' => 'prohibited_if:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_if:bar,1'
            ]
        ]
    ],
    'FInGp2HPM9sWO0HoO3U-zg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 1,
            'p' => []
        ],
        'validated' => [
            'p' => []
        ],
        'rules' => [
            'p' => 'prohibited_if:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_if:bar,1'
            ]
        ]
    ],
    'EKWDJsBKHzpdEP5chpL0gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2
        ],
        'validated' => [],
        'rules' => [
            'p' => 'prohibited_unless:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_unless:bar,1'
            ]
        ]
    ],
    'AahxXTAbj1D8NDvUAy0qdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => ''
        ],
        'validated' => [
            'p' => ''
        ],
        'rules' => [
            'p' => 'prohibited_unless:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_unless:bar,1'
            ]
        ]
    ],
    '3T--RtGAJ63YB7jf6pmMpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => ' '
        ],
        'validated' => [
            'p' => ' '
        ],
        'rules' => [
            'p' => 'prohibited_unless:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_unless:bar,1'
            ]
        ]
    ],
    'vhQSMtcuw36xCNv4CVRAhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => null
        ],
        'validated' => [
            'p' => null
        ],
        'rules' => [
            'p' => 'prohibited_unless:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_unless:bar,1'
            ]
        ]
    ],
    '1dKTy1sYD1IwpaFS7rWAbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => []
        ],
        'validated' => [
            'p' => []
        ],
        'rules' => [
            'p' => 'prohibited_unless:bar,1'
        ],
        'expandedRules' => [
            'p' => [
                'prohibited_unless:bar,1'
            ]
        ]
    ],
    'gSeJ8g1ONLq4mu6cGkb8YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [],
        'validated' => [],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'cI1SgflMmsva7S7-ZAKKGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => ''
        ],
        'validated' => [
            'p' => ''
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    '5CHaG_2VvPe4Un3DmN4HVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => ' '
        ],
        'validated' => [
            'p' => ' '
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'qrUlrCi7GW68nZCYcQ4Fkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => null
        ],
        'validated' => [
            'p' => null
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'l_zYH96zJGge6qEB0CTWeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => 2,
            'p' => []
        ],
        'validated' => [
            'p' => []
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'G_FgpGxDtIIKnuqVI2SRyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'p' => 'foo'
        ],
        'validated' => [
            'p' => 'foo'
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'pX96ZmVPw_5pey0fPF-P_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => '',
            'p' => 'foo'
        ],
        'validated' => [
            'p' => 'foo'
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'EZhVVWT8EohIZP7hygi43g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => ' ',
            'p' => 'foo'
        ],
        'validated' => [
            'p' => 'foo'
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'j-FEUA_6xHBCV46rq1vlgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => null,
            'p' => 'foo'
        ],
        'validated' => [
            'p' => 'foo'
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    '9pKQz2_I9id7cFd6woFopg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2028',
        'data' => [
            'bar' => [],
            'p' => 'foo'
        ],
        'validated' => [
            'p' => 'foo'
        ],
        'rules' => [
            'p' => 'prohibits:bar'
        ],
        'expandedRules' => [
            'p' => [
                'prohibits:bar'
            ]
        ]
    ],
    'QTIzziBOwZuRrM0mTuXavw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:2142',
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
    'sds-hyRdaYYxF3LFBGhE7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:2150',
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
    'NWqqbrKzab41mYmtn-XTVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2161',
        'data' => [
            'color' => '#FFF'
        ],
        'validated' => [
            'color' => '#FFF'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'W7I_KvNf5nfQlgJ3LFSiUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2163',
        'data' => [
            'color' => '#FFFF'
        ],
        'validated' => [
            'color' => '#FFFF'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'p1NDDIEK2OBWsxUyoefaWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2165',
        'data' => [
            'color' => '#FFFFFF'
        ],
        'validated' => [
            'color' => '#FFFFFF'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'IhExqLs3HZwNB_p3jyZGcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2167',
        'data' => [
            'color' => '#FF000080'
        ],
        'validated' => [
            'color' => '#FF000080'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'bqMljl1LLbebeb4HlIL71Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2169',
        'data' => [
            'color' => '#FF000080'
        ],
        'validated' => [
            'color' => '#FF000080'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'Els0hisP0FWhETYGTtkBNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2171',
        'data' => [
            'color' => '#00FF0080'
        ],
        'validated' => [
            'color' => '#00FF0080'
        ],
        'rules' => [
            'color' => 'hex_color'
        ],
        'expandedRules' => [
            'color' => [
                'hex_color'
            ]
        ]
    ],
    'A9SGmmK9T-1ZhJJGJ6osqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:2198',
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
    'osNALmuNSOinQIIVUM9LUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:2204',
        'data' => [
            'password' => 'foo',
            'passwordConfirmation' => 'foo'
        ],
        'validated' => [
            'password' => 'foo'
        ],
        'rules' => [
            'password' => 'Confirmed:passwordConfirmation'
        ],
        'expandedRules' => [
            'password' => [
                'Confirmed:passwordConfirmation'
            ]
        ]
    ],
    'aeEhqJEvjSnJMjkHamxS2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:2220',
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
    'wbfJV4cX-ZqcUfzkNTIkZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:2226',
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
    'JKANgKxfcbtSoWRP0SzPYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2233',
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
    'EJ4B1PcKsmNo8U-j9E5vFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2236',
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
    '0FyRSYmn1Zm6rfkRUY1mhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2239',
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
    'jHoMKjEbk0DTy07AlAF6iQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2245',
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
    'mui0l70xp51fA_8mzcwh7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2248',
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
    'U1mmwtLY5E1ieF_OItc3LQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2251',
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
    't3zjNRfmyQnD0HXgy2loRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2261',
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
    'eE4kOqfcZQfRubvWxNNG5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2267',
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
    'D40adwaB8_4UbHjnGY5eCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2270',
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
    '9mKceDN3wzg3q8WeeBXpEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2276',
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
    '-MaCCXlK4A8ysNnE5paNcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2282',
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
    'bdPnZMCrW72m3AQnoiMOpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2285',
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
    'HEoG6pOaKjFRsc0hPfewzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2298',
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
    '_W46mnj-jLnSUZizWQ8tkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThan:2383',
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
    'PSOgg2uoHEBXfVKft7uJBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2400',
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
    'Iyh2iT-8ZQmbN2FuD2Y8qg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2406',
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
    'cLnZL_gFy-cZxT2NHHpEmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2409',
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
    'osZmNMECESZc25ateRQc7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2415',
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
    'Z22qgNyG4fa4a7ZfrGKK5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2418',
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
    'RUrQxgSXFbPsELzjIGQhoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2421',
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
    'Hmf_jy0zFdlCRMGqHOzCZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2434',
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
    'CU_TLUd9TZr7DTeClK2ohw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2441',
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
    'DQpJPDI_2SjJbVQXGJLW3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2447',
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
    'To-KVmwkVKqaXMkiegOrww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2450',
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
    '5Y7m_tVcgPakgoZ-5zUGwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2465',
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
    'uHA_DhfAJ0QIztBrfwbo4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2506',
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
    'ov3hMyF1RgfQWyknEOIohg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2509',
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
    'xij42ZJHV-_eLrmB4PgJTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2512',
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
    'Gy8_bf48clroXADA9-yIgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2515',
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
    '3aJ5D6L68qLB55LN_GrRzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2518',
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
    '5orkSU2I_5Uj_ffRuKZpFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2521',
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
    'NAO3SeN1JjhctDwFPkGUmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2528',
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
    'BT-sXa_MevGdr9p6Fw9Y4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2531',
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
    'SXIyzyzPsglNe8YYO3c_UQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2534',
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
    '_r5snw7QJv3SFRKyU-pStw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2565',
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
    'dKQcWoPEcbqfAdqpgz_JVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2568',
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
    'ReXdU3YG9u6YSGtyo7Rccw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2571',
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
    'EaQvbhVL9-bWaJDMTQ9z2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2574',
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
    'sphcr272z20hX4KwenL6Dw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2577',
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
    'frKmz_pZiNGtGcHKbA_H8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2580',
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
    '70gm9YM1jewC9LuwYIkN6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2618',
        'data' => [
            'foo' => 'yes',
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'required_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_declined:foo'
            ]
        ]
    ],
    '_2mniomkz-DELzxw_oGSQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2621',
        'data' => [
            'foo' => 'no',
            'bar' => 'baz'
        ],
        'validated' => [
            'bar' => 'baz'
        ],
        'rules' => [
            'bar' => 'required_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_declined:foo'
            ]
        ]
    ],
    'UP-BK--HtIndSFkBoOV66g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2624',
        'data' => [
            'foo' => 'yes',
            'bar' => ''
        ],
        'validated' => [
            'bar' => ''
        ],
        'rules' => [
            'bar' => 'required_if_declined:foo'
        ],
        'expandedRules' => [
            'bar' => [
                'required_if_declined:foo'
            ]
        ]
    ],
    'q5LlXnw6pukIqI3mrWysVw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2658',
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
    '-WcwqBz-_g-wRi2m1XqC2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2661',
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
    'OAK0kNhpJYjPfuWzsnCvfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2664',
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
    'eoWBaQA8TDbpgbGv6qOoVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2667',
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
    'YjIrPtVPfPfYD4Mv0cBOew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2670',
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
    'slw0VXAIReoB2DlthuUfLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2673',
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
    'q7sicfI4uLDHfh1dCic1Pw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissing:2712',
        'data' => [
            'bar' => 'bar'
        ],
        'validated' => [],
        'rules' => [
            'foo' => 'missing'
        ],
        'expandedRules' => [
            'foo' => [
                'missing'
            ]
        ]
    ],
    'sotH5BiBeVFQEMMqEYe3Kg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingIf:2751',
        'data' => [
            'foo' => 'foo',
            'bar' => '2'
        ],
        'validated' => [
            'foo' => 'foo'
        ],
        'rules' => [
            'foo' => 'missing_if:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_if:bar,1'
            ]
        ]
    ],
    '_w07Opg3EfBUvaNwzMfrRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingUnless:2794',
        'data' => [
            'foo' => 'foo',
            'bar' => '1'
        ],
        'validated' => [
            'foo' => 'foo'
        ],
        'rules' => [
            'foo' => 'missing_unless:bar,1'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_unless:bar,1'
            ]
        ]
    ],
    'xpSJ9AEmXlhgK-KyOEDAOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWith:2807',
        'data' => [
            'bar' => '2'
        ],
        'validated' => [],
        'rules' => [
            'foo' => 'missing_with:baz,bar'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_with:baz,bar'
            ]
        ]
    ],
    'B-2sZXT8XwwKeoEbI4f0nQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWith:2840',
        'data' => [
            'foo' => 'foo',
            'qux' => '1'
        ],
        'validated' => [
            'foo' => 'foo'
        ],
        'rules' => [
            'foo' => 'missing_with:baz,bar'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_with:baz,bar'
            ]
        ]
    ],
    'aeOYV_BMe3N-fpLGkhF2nA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWithAll:2853',
        'data' => [
            'bar' => '2',
            'baz' => '2'
        ],
        'validated' => [],
        'rules' => [
            'foo' => 'missing_with_all:baz,bar'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_with_all:baz,bar'
            ]
        ]
    ],
    'bvIjx1Tiof3l22DS6Pr_gQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWithAll:2886',
        'data' => [
            'foo' => [],
            'bar' => '2',
            'qux' => '2'
        ],
        'validated' => [
            'foo' => []
        ],
        'rules' => [
            'foo' => 'missing_with_all:baz,bar'
        ],
        'expandedRules' => [
            'foo' => [
                'missing_with_all:baz,bar'
            ]
        ]
    ],
    'ewZof81jgNzbc4X4sMjJZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2918',
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
    '351hiIlvEM0xWu3-GgV8xg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2921',
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
    'NGzb88wMfaUuljkWRZjtzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2924',
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
    't6S86lqkmz3Q21MLZR0NDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2927',
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
    'K0fcx_vXpn29mlZg8doY7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2930',
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
    '_j-kkRLlYRUwWD7sOvjE9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:2933',
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
    'ANDoaWivZVHq4JbMaoIFNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2975',
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
    '6JARR8jLwTxsTxyoRbrGgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:2979',
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
    'MyG_RMBaHnJDja9oJC6bnw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntEndWith:2998',
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
    'tTH2RBTkIn8ZY_JRTQPcsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:3009',
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
    'cOnGgF7Mkj5-wIIq-twz_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:3017',
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
    'O84ApCilSqViMpIrETTnUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntStartWith:3036',
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
    '1ZmsK21rkZD6vA4-rp4e9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateString:3047',
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
    'Ox1dSiwLyAZIeZOPmkahfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:3062',
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
    'jqjMY0ER1_ufftJ3iDB9xQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:3066',
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
    'rCBcbH6GQLVSxQY_jpTviQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3097',
        'data' => [],
        'validated' => [],
        'rules' => [
            'foo' => 'Boolean'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean'
            ]
        ]
    ],
    'xnio1_i1mDjDZfiGC_2pPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3100',
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
    '297VlM5YDmh1G1YKv-_ZzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3103',
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
    'B5jMFS4r77sWN48Io2DAjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3106',
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
    'oaWXgs_q_Ra39Ys9_bJv0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3109',
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
    'rXdRWyCLXQ_-SvAp6Ql31A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3112',
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
    'g1k61U16v9d0iqzgG0KVSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3115',
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
    'cH8woy3P26aFc9K7z7ct5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3134',
        'data' => [],
        'validated' => [],
        'rules' => [
            'foo' => 'Bool'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool'
            ]
        ]
    ],
    'NM51F2mCH1wdC7V9fELPaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3137',
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
    'EuSP2yqEXQvbD47mZfRAzw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3140',
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
    'CJR4uS41kyFSXKNCB2zU6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3143',
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
    '3MhTX_o24Oe-BwKL_UZ9eg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3146',
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
    '9kalR2JIqQ_cUQgrUcsNog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3149',
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
    'dPd3eXpOFHsw-ndKmf-Kqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3152',
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
    'LO3xbSMsVpyl5OxwPLY36A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3162',
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
    'ivcS_pDpT3XlIldHKNPOZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3165',
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
    'fWUVvi4uD2RoRbrlGIFM9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3168',
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
    'oZ8_fry0I15G9fxVjZLBPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:3181',
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
    'NDPBcILXQRP0dAEGsXYqRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:3184',
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
    'GWN65Ps7FTaQdh9InAowPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3197',
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
    '9-CsPmG7SnI4waLoe_Swjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3200',
        'data' => [
            'foo' => '-1.234'
        ],
        'validated' => [
            'foo' => '-1.234'
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
    'bdztr0sqyUbZYV8PlRhYxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3203',
        'data' => [
            'foo' => '1.23'
        ],
        'validated' => [
            'foo' => '1.23'
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
    'zGhroxCmGAJj6483XzaDdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3206',
        'data' => [
            'foo' => '+1.23'
        ],
        'validated' => [
            'foo' => '+1.23'
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
    '8Qmybh2Oxn4s68meNFLoRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3212',
        'data' => [
            'foo' => '1.23'
        ],
        'validated' => [
            'foo' => '1.23'
        ],
        'rules' => [
            'foo' => 'Decimal:2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:2'
            ]
        ]
    ],
    'h3lB_aOrUJvq71K_T73OXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3215',
        'data' => [
            'foo' => '-1.23'
        ],
        'validated' => [
            'foo' => '-1.23'
        ],
        'rules' => [
            'foo' => 'Decimal:2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:2'
            ]
        ]
    ],
    'm0MkJSiLG4_xAhKJEF9Ftg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3224',
        'data' => [
            'foo' => '1'
        ],
        'validated' => [
            'foo' => '1'
        ],
        'rules' => [
            'foo' => 'Decimal:0,1'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,1'
            ]
        ]
    ],
    'C6EQXFa46KIvYFIWoBIVlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3227',
        'data' => [
            'foo' => '1.2'
        ],
        'validated' => [
            'foo' => '1.2'
        ],
        'rules' => [
            'foo' => 'Decimal:0,1'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,1'
            ]
        ]
    ],
    'ByCW7Ls0EqQoFi6_PLSecA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3230',
        'data' => [
            'foo' => '-1.2'
        ],
        'validated' => [
            'foo' => '-1.2'
        ],
        'rules' => [
            'foo' => 'Decimal:0,1'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,1'
            ]
        ]
    ],
    'tXm_b2Oc-iKMxaSVIABWdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3236',
        'data' => [
            'foo' => '1.8888888888'
        ],
        'validated' => [
            'foo' => '1.8888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:10'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:10'
            ]
        ]
    ],
    'PElOs65--HA18LBQEvNd1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3239',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20'
            ]
        ]
    ],
    'rmTbtsa3kOOIRFvyXwyROQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3245',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Min:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Min:1.88888888888888888888'
            ]
        ]
    ],
    'nJ1iWNAXdblE96i4p-O4tQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3248',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Max:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Max:1.88888888888888888888'
            ]
        ]
    ],
    'sXPiraC0lAAm1CjbDC-CKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3260',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Size:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Size:1.88888888888888888888'
            ]
        ]
    ],
    'yujooa_lYHCpOpc8bcUd-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3266',
        'data' => [
            'foo' => '1.88888888888888888887'
        ],
        'validated' => [
            'foo' => '1.88888888888888888887'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Between:1.88888888888888888886,1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Between:1.88888888888888888886,1.88888888888888888888'
            ]
        ]
    ],
    'UDuTvZbEbe6MhEktjmpNkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3272',
        'data' => [
            'foo' => '1.88888888888888888889'
        ],
        'validated' => [
            'foo' => '1.88888888888888888889'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Gt:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Gt:1.88888888888888888888'
            ]
        ]
    ],
    'RWXwQ8bKXmH0o63-DjCUsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3278',
        'data' => [
            'foo' => '1.88888888888888888889',
            'bar' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888889'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Gt:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Gt:bar'
            ]
        ]
    ],
    'ep_swAuIolXWFKqRw_q-Bw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3284',
        'data' => [
            'foo' => '1.88888888888888888887'
        ],
        'validated' => [
            'foo' => '1.88888888888888888887'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Lt:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Lt:1.88888888888888888888'
            ]
        ]
    ],
    'gRTon2f5B2iGjJ5NX9yoMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3290',
        'data' => [
            'foo' => '1.88888888888888888887',
            'bar' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888887'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Lt:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Lt:bar'
            ]
        ]
    ],
    'UHsP4bwEGTZBv3GkYtMZVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3296',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Gte:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Gte:1.88888888888888888888'
            ]
        ]
    ],
    'oedM_IcPgtlhETVlg1o58g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3302',
        'data' => [
            'foo' => '1.88888888888888888888',
            'bar' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Gte:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Gte:bar'
            ]
        ]
    ],
    'xA5eIxmeHXJUShTKZm0-XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3308',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Lte:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Lte:1.88888888888888888888'
            ]
        ]
    ],
    'du4W6R-iCJrOsbuDt379Iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3314',
        'data' => [
            'foo' => '1.88888888888888888888',
            'bar' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Lte:bar'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Lte:bar'
            ]
        ]
    ],
    '_ij_Gs2Jt6F7p8PKMLSv2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3320',
        'data' => [
            'foo' => '1.88888888888888888888'
        ],
        'validated' => [
            'foo' => '1.88888888888888888888'
        ],
        'rules' => [
            'foo' => 'Decimal:20|Max:1.88888888888888888888'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:20',
                'Max:1.88888888888888888888'
            ]
        ]
    ],
    'b_YVbPHIuvJ6liKKXB-tYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3350',
        'data' => [
            'foo' => '+123'
        ],
        'validated' => [
            'foo' => '+123'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'zehvziFlK4d3YcvPvj2qFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3352',
        'data' => [
            'foo' => '-123'
        ],
        'validated' => [
            'foo' => '-123'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'DdfN-vYGmjCO24AUMWomIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3354',
        'data' => [
            'foo' => '+123.'
        ],
        'validated' => [
            'foo' => '+123.'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'cTAHFWK7dm2NOxBkq7CsJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3356',
        'data' => [
            'foo' => '-123.'
        ],
        'validated' => [
            'foo' => '-123.'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'tw1VIY8pdTpgzRORH4jIpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3358',
        'data' => [
            'foo' => '123.'
        ],
        'validated' => [
            'foo' => '123.'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'Vhhy_ics09Pa6I7-XsyyqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3360',
        'data' => [
            'foo' => '123.'
        ],
        'validated' => [
            'foo' => '123.'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'ZFZRU1-yx6846HtO58NE0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3362',
        'data' => [
            'foo' => '123.34'
        ],
        'validated' => [
            'foo' => '123.34'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'YhHTnBe7uHg6M570EdqXpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3364',
        'data' => [
            'foo' => '123.34'
        ],
        'validated' => [
            'foo' => '123.34'
        ],
        'rules' => [
            'foo' => 'Decimal:0,2'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,2'
            ]
        ]
    ],
    'E4lTKzd0vqvHGVTapOctMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:3377',
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
    'u5DPObXw2qdqPPKeFRkDcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:3380',
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
    'v4kz5qu1azgZo22SNDNgvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3387',
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
    'xRha6FmFggeB0C18M1to4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3400',
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
    'IsFY-QjShvkCIhaDjej8nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3413',
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
    'WBIg9i6TYyExiwnWXUUVfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3426',
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
    'qiZ-1nQRKn-JCueFsF-a5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3445',
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
    'z56CgFHn8Dh7VdMn8w36XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3451',
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
    'nhsTDpdA2vYX4qaBTc-Uwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3457',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Decimal:0|Size:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0',
                'Size:3'
            ]
        ]
    ],
    'tP2JzA-63tPKOLNfaeM-vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3463',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Integer|Size:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Integer',
                'Size:3'
            ]
        ]
    ],
    'eZuXjfAVOR3GEDHpu6wnDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3466',
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
    'vw4RgjDrF0Dk6ExWIUTNuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3489',
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
    '4z3iPjW7M56Ml5HZHV2wNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3492',
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
    'l7P7cnCdnNihjMr6TmbfCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3495',
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
    'bBdJ8zFV41xnMVsbSoQxZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3502',
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
    'g5Yng3LebI7wVE4pvWSI4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3506',
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
    '6NOH46PkW4I55dRGE6Mujw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3510',
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
    '9dvQU71LHqkvJKz2fP3ACw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3513',
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
    'kYiJ_1NhlNniF99-vlNaVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3519',
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
    'RgrgHfi-Z971TJs3fwLm0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3522',
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
    'GTKJJbgjR0sd_CdwzEBTrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3546',
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
    'WbiTUQbHXYzYQMF25Ni9dA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3549',
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
    'xLLTe485r6_yl5owyZxdfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3560',
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
    'QJ6FsfdBGjz428ef5krGWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3568',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Decimal:0|Min:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0',
                'Min:3'
            ]
        ]
    ],
    'E60f2hUsL6-F7t-if2gHgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3578',
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
    'tQUOq0dWhtYax2ri-r_4Cw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3581',
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
    '8WSAsn93PLXOOeONgdpYXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3604',
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
    'cczNpn-4F9OSKv_xq_C3vQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3611',
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
    'KvWfJ0adsWP3vWr-aj8oqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3615',
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
    'BJpK2WkAca6N6E1nTfHjgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3626',
        'data' => [
            'foo' => '3'
        ],
        'validated' => [
            'foo' => '3'
        ],
        'rules' => [
            'foo' => 'Decimal:0|Max:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0',
                'Max:3'
            ]
        ]
    ],
    'eelzGaf9QXXG8b4VPXLLlQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3630',
        'data' => [
            'foo' => '2.001'
        ],
        'validated' => [
            'foo' => '2.001'
        ],
        'rules' => [
            'foo' => 'Decimal:0,3|Max:3'
        ],
        'expandedRules' => [
            'foo' => [
                'Decimal:0,3',
                'Max:3'
            ]
        ]
    ],
    '_UTzusFMa7B8PSxbcmZX-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3633',
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
    'BmM0wvZntJDzY_Iz8IeOnw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3636',
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
    '_Zsu-sH8_5SHXYAoZU0E5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'JmNyj48SBV3PzHilcXoC7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '1wOqC-oyTjrQLS4Asvz-9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'm7ZKLDTTH8v9_vJe-5--0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '9KqfwtcgFGA9sVZHY_WdMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'sutJyXSccUU5qiAi142uYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'fEQ5DAURh5b39jSpd4qKkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'ZHP65GWR9XBAy0GwNfZFOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'JrvKia7F1z8yKLMKr9nTKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'i9dk19rvaovOgvX7tw0FmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '_YvBotVchNJxVP8bDnpnLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'onT9HPwNokHNAwBQkdRNhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'Yzc_T2sZqYTbBM0BWUH8Ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'MHoKOpS3qdEHaCk5A22YCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'by3z7F_MHHiPUcnfatz1DA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'rGHvMNyRH1KvFW_YOeVatQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '09Nz0G4SMZvIjgBWFgZQdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'tdCjcsMOJr9PvVw-bLxVjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'vHWmcmYXwBRYUTACkiUgKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '-s9gQXF24Y8p1vHdrnKI7w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'tWO4Ys8kVGG0jLK1UVeBHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '0APqICD8P3LF1uG8cvWgHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'sNWatEsf8bTEESc2MQ35LA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '-euyqyuuGlq0ei3S2um_6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '9GpsrWXW2Dpi2bbTxGx9YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'IdIjgh2bdusJg2s5PS11pA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '2dUXz0SDZhlEPsOGVt_gYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '2UkhmgluUGALMWzPtikiQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'xS67m89dXWxcz65B3A1_Qw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'Lfk4sGTzlB8gPjznEyikBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'vlXoV4MMsLWYO7AGEeQEVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '_N3WHdoZ54jLEeDFTT_-Ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'xXgqahLd2OMrk2qbsm3c_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '3QMyk-Ms3ul90RB_sez_zA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'j_T1DswN-4gbA4BqrsG9ZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'lzc933cOzfKFoJ9ARerfxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '6FfZkCHgJ8rniddIn7OLDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'Ssa6q6s-fX-5KScOeLfF9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'E_v0sMw-GkyrM5E3VdXJww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '9YLAH2JAMRIFYMefDmdkeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'BsP5qdwSVVckEusHJcesww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '07zL574A3k9CDMWTwfSb_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '0Mnd9e5wY6hTJZDWRvwP_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'dowVt7Q5B9r7hkPhNn3UMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '5C3J2Ga6uUOW5jYc242nsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'npUBnJqqbTFdf1MFcHFFcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'b9FZnA1NHva4lV7UljOPfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'GfJdkaDNXBCqFLR4PuAXXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'znHjz06Cu0JJ5Bl8aEdagg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'fBog53ikb7t9pKgc6ZolMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'FL3_FiYb35qrVLL-EehKEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'RFRRC6Iy0072lt9a8Acoaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    '0zsjDKFCSvXKcxnUOxRzSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '4_sH8C7uzPBRRQ99iEfOUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'Ys2e45os64-hTvRKtgF9Yg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'YK2t6v0I64srWk3Y1j08SQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'Nxv7yfgMZYQPYktsFvGzJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'm4dFjHMTUifutzYgSYNQ8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'hR4kD-0dnP5Igxza-ZOEtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    '5L19BboLu103sfyvlOmn4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'WDKo_s1ZO609A1pysHqthg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3672',
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
    'XM_mOmJbG_uu6e3MT-c_lQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3673',
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
    'b-YLsLVrugO9GJpaGFdxFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateContains:3954',
        'data' => [
            'name' => [
                'foo',
                'bar'
            ]
        ],
        'validated' => [
            'name' => [
                'foo',
                'bar'
            ]
        ],
        'rules' => [
            'name' => 'contains:foo'
        ],
        'expandedRules' => [
            'name' => [
                'contains:foo'
            ]
        ]
    ],
    'ZH6EoevvyYQAnjv95sR5hQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateContains:3957',
        'data' => [
            'name' => [
                'foo',
                'bar'
            ]
        ],
        'validated' => [
            'name' => [
                'foo',
                'bar'
            ]
        ],
        'rules' => [
            'name' => 'contains:foo,bar'
        ],
        'expandedRules' => [
            'name' => [
                'contains:foo,bar'
            ]
        ]
    ],
    'EMlcrzlMu0aEDq7uVAtCNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3971',
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
    'b1jPTsZIQF_UUosRzu-rXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3977',
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
    'AaGxrAWACaNJuBUDW7b8Ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3980',
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
    '5ZF9jrJij2FjKUClIkP9QQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3983',
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
    'zHKtB_JrFogrqTOV1vBFmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:3986',
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
    '-aCrasxPvZkHQC9WPSf-Uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotIn:3999',
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
    '78qPPLJ0P8WF-vOeuueXMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4022',
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
    '7onKThumeis-CPtIUVi-Vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4025',
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
    'kU4t48wjbQH7jhXGSBhCmA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4031',
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
    'eMzzze4M0V0IW0rk6XzXFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4037',
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
    'x2n8K81e7cyLVf-J9T5P3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4040',
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
    'GCnzAZJMCVKMNzGMmi5plA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4043',
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
    '3tWwfhjAg79BHsBAPYk2mQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4049',
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
    '4Qiw1l9VfnF0X7VRnRGN1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4055',
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
    'UNe3wcXY_3u0hGlYgmoTFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4058',
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
    'M7hVDhlrxcFDp0EP2vnCUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4072',
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
    'L9mOWRUZH0GIfM5aA8mYlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4087',
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
    'qNoJUwaWf-HA9XlvnnqtjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:4121',
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
    '5PFjZSgyrwb1CupL-TKxwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:4128',
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
    'MkzCoNgF_lsKz_HRwVUScQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUniqueAndExistsSendsCorrectFieldNameToDBWithArrays:4172',
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
    's8hYGjj4choLPXAv3BgahA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4198',
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
    'f-dKdtvE95tYnbQ8nLhF0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4206',
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
    '5Kbtda_C6SVN5voXjcRonw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4227',
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
    'Z6IMw4Es3IF4ILyRM9CXwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4234',
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
    'nHl_3nc8ldmwu7kXAPxwaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExistsIsNotCalledUnnecessarily:4252',
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
    'QAhcYH2i4okZeZpfCYbIXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4422',
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
    'W7YgnN_KY6knH_jIcVGtCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4425',
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
    '4_tj8pb1XS8dKxZMvECurw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4428',
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
    'DU28WQ1R6KMqCn98CG5AQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4445',
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
    'EWVGyViV0nF6Fzbe87hT5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4449',
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
    'k6ZFS-oA3i3o0M9FoeinuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4453',
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
    'fG8jCp0sOkKBy084ByT8YA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4457',
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
    'zvdexTjozJP4brBzr6mrIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4461',
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
    'ltLqqFNZDfUdW89FskJlEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4465',
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
    '305uQqnNZ8X4Po4SaOfZNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4477',
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
    '8PVLVQGZ3ItxDZcFJ4vi6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmail:4512',
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
    'BYYUqfaEVzUSgKppOLfP2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithInternationalCharacters:4518',
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
    'NeCmum10cfxIhowxK4LnLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterCheck:4533',
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
    'sBXuq33cAz2EpKAeRuCrdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:4549',
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
    'U3jKvIHbkgU7pfuA3Fnj8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:4553',
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
    'pPc8sdhJe71Uq5-NmEVluA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithProtocols:4576',
        'data' => [
            'x' => 'foo://bar'
        ],
        'validated' => [
            'x' => 'foo://bar'
        ],
        'rules' => [
            'x' => 'url:https,foo'
        ],
        'expandedRules' => [
            'x' => [
                'url:https,foo'
            ]
        ]
    ],
    'L2A0Q-tGL1ewCW9DZj3vcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithProtocols:4583',
        'data' => [
            'x' => 'http://localhost'
        ],
        'validated' => [
            'x' => 'http://localhost'
        ],
        'rules' => [
            'x' => 'url'
        ],
        'expandedRules' => [
            'x' => [
                'url'
            ]
        ]
    ],
    '1PciJXZ_cVMbvRUSuiQCng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'KMclkgBDKOuipJEMPHvhPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'eSrGkJ_Emw8nALE7z96vDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'SXfdaMG1HR5_TSMtjc3MCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'E6SzeWpecojRSm6Bnb8rcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'SnsqZOK8kgtm5FRBDTahGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5A6sA41PJrsogFujDxWTvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'MDNTJIiZJkPtmpGmtqqheg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'HrqAv7ATVMaeoCu5IYk7yA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'K4XsdXGmdGv4EKrRwxAP7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'PyL73s0plLLwIpSQYmiZsw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'COVon6m9G-dmWpC8QWCuKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'XPHlAji6xFGlivUzVVgBXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'JkY3Bq85QfHPanqrzU2w9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ZTz3jJWgcZat42zdAsF8Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vltt8P5CJknD1cll_xPcxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '1coimDKSLrdsEQywCyM9WA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'foH6XxiKiSHdMWmERjDS_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'rNR7AGyPcfqnEiiutpkuvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5JSWamr1SMNZCGAT_byjKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'D17SJOOK9DK3tVlK7Cf15Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'AmNhaH2SKKKOqd9MlPitew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'zLWFGpZa1fj9a_hr8JeVhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    't1RYDmNDzK5zer-OsTUrEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'yfqEfKYHdBYH3tdogWPRkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'kVyWb_SrjMinOMNZgKtQEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Sog3D7bL2Ad1M6Nxo0BNZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '7l1gRKwUyfXPZpEdBQXLlA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'PuLFivrwGb0Aen5Nuj0dYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ZTKXGrPSqS_wtyFXxxFy6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'AktTTfn4yRAPV8Eg6S9mbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '9gb-x1k6NdxG0ZysNWA8Lg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'c91SkBruRAdChtppKD8ZgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '-kwKYO1Yu5YhonY-nhL72Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_KBPmQvU8-ivlz2cxY_Z2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'LVHmJKPILugt8ZazLA8qzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'EXmmZHU8hxFRGC71We50TQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'u82Zwsb7paIuesOlPMlV5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_ujXF0yi907w7NUEsqzppA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'B8N6v4N03gL_vfM0A6mVYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'rVSAIPwg2v1BKlfCZ0j5gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'uBdXCvwu2uxnPVUfuPFdxw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'd7B2gVJkFIDt-3q3l8CkPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'os8AZEDnavThlHZBjZhdOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'T_lrS0Lvv2zjUu6fMdFdng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '1SLviBZl98El1FtnH-AmGw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '7GkBEYkeZRecuOLIeMEohQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '3fdPDalvB2u_irpHcuhxgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '64zKqcl6w3xbk3KIzscY-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'KS7wU4WWRtNwoVQRPhi6Uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'gIEyJw_OGqCtZ0F3_v7WQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'odKWvFk55fuEZZJwEa-4rg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '2wPTpPihrYC7vGkr__JviQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'XLxLxq2PiPbMHm8smqkrHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'xn11URt7r6oj8ERW8LkAhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'wMGn9ZunLZ8aLBvUnOqYyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'CI9-vIOkYiNRZTKscQXxow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'qauKMVuC6O3o60sYw_ZbWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'rItbIl6pCrWIXeymjHJmDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'UKoLu4JDYhQr3UvDjQt63A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NTokjZZAnDTEk6NP1q_hcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'tCJTx6ggSEvBVUXrd_KwUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_3CIyGYcA5VtzX0jSM9P8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'yAa5vy30TszbbPAGqEq4IA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Er3J-AxbspE6_CjHR0O9JA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'bFEkKgml4M1P0xittrac9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'dNUnbfQ2DXFbdClB_S1D1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'sNjrx2nWeimJ6pUjMwqWWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Zr2R0rkblkODowq6QXzOpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NYtZ29sIWOcnpi2CkSH4HA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '2dH9av1Cjtt-JTsKkbg4TA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '6-QpTywNqX1FJ-HCr3ohJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'cIkz5Uf8QRf19c0-BE6Z6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'UjhmcEVzmyak0yr8mkS6WA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '4Xt8MGWKp7qwjjJi8Fh7YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'eHv0cMCsrHCywvoua8Zb9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'jLtY5svNQ5EnPFIjvUyepg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '7MAJjw9fuARpDD-xj4be5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ER2X_76pUffYG09FNaC9Vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '2kJUBkXQoxNCbY81ygfGxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'qE5M36S1t3ACr9erT3WNZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'i6_ozpEK4c-EjnQP_7nqiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NHPrN4Dry9KMDKua_RRSYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'pBgzF-YU-yzeRQz2gqA9rA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'TosBUJY6UniLCmJO1OLLRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'IaUoAxMgqnqzBKhAXsEvVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'P0aC6Wbh8p0_Ft7Q-ZUlkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RwPobwBSlqZ2EbeH0wa10g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vtQDYvcrhn14VR-vdgxAPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'yCflMHbdfXQ9e2ClecCDQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'M5PXjRlQ-i7E4ctm8vPFmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'R43rbfBWtQ6CisnXXMHc6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'qOOgAu8qBNCsj7FfoFkYcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'r4znyhr1kyNNds6mcjDGmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '7LD_K1yXv-Gw99xMguKwyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'lSZuywuR1ezncfaHd5w9Zw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'BrJVQOcyY0RJZOQXPPAYTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    's5hWip3o0nWxQCBhWnPWMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'BZsXH-oNhG1wer7vClVa2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'mNdlbDpxckYYIKuVL4BpsQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vMhqDYxAFhW5JJvyN-n5OQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'KZtLZDydV8OwIPwyo5IlpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'fQoxDRiiXnIR-J530NhY1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Ckw6BrmLyGoom-lz1PSWdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'F9lRJAq7uNE6XsYqXvRM0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'teP_8YGr9ukSWCQFPRUuaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'AHyRjUkHwX9R-0usNb0b3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'nC4bDcBBGQagtZQXahbtGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'r8na5ukhF4_1k51-6b1EaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5O9U8QuRGZiaH_623z96cQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'aJ6mUYWbSdabXcOksA-anQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '2ejM53es56E7T9LaIVReow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    't2w8CNK47i_-IagKbBA32A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '3Tfkhnmm0wHEE4tyQuVyNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'KizZ4AM-7uZKlnMoJKFZ3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '-VQdiTxHTCvmnZ1PhKgC6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'DdKdkamDfPfBvm1YgJbWzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'oj4juUdFrr8_2NPUm6ex5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'JbVjIQsQZD_qGECt7TX0jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'QRRuadphNBP-2JXXOBQvpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'zNRRkxrzAaSNsBjXuHen7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'bNWmDapPYePyK8pSnQ9qAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'SxPEsjkMSyrchBfG8flvTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'xl8dW_Sj3ghMf68F5YYuOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ph-sOPMrNpYxgnjoUrCklg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'FvLJeznQjOJg4WlYfdQ88Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ovc9JUixpc5IWgHFrWMMIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'EWq1-5xsh3GJpIDx2r6FHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '-N38Sgkb5bWxCRmyYY3OJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '8_0LzUrIfzuSKjjkocyang' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '51B1cAIXLyQ2g6FHMukEfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'kXrkIQxL5IkbypWgRuHkTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'dgO86lGj3koiT6BTEKjVew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'PLTlxJ1rTAAO1kEvXwTJ5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'aQBA_1xlZj0EpVm94HMYOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'QtDuGLHd_fYF1WaW9ntQKg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '1Z7gp18AZQotSZ-ZpsSuSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '839KYpsD4wYeD6uagY4VrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'PJNIRHWeBfIVTvfl8RESwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'wtr6T6PPzSJcWtsD416p9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'YOnas6sMkgsG9-J4EhD5XQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'b_PWdPnIPwbNw2jzbkIB_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'SQNlqPyO5IlJSOoJnuBCQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'M2FFstLxagYzLAsLHhtlig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RXdeRn1U4avgwONqbHC2KQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RREcabfk41O1uUrfYK-gmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NFao4NngaXf-Do4exSu_GA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'GuNM8tLtK6VPtW_mfYgKUA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '1dWbI5uzI48caILKtKOPpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'o2Nw26eE_cN6i5POuaVKiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NTxpCPnehOfaqmrSFJlJuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '4M_kfpYZ3RuZttiX_WJ2Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'egX2dywhYy03MMYTwBy3yg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5ZCkDeGYjimE9hc5nPmKng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'HN6e_EtJJReU-CUP1wogQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'F6shUGWr0Za1oEK6SeOuhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'GLnUAWfnm6DvHRXEBb8sjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '6_EuZ_tuk-qFTyk2WdqSwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vZDZLiGq8k2MwXQpRiqQHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'gEkvcytgBTr-yHQkTQ9rDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'xSXY903NnpxeTCK-zQiwPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'BqMxhtHjleuEQiYrbwAllg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'HMbdDXoj6PMc3YBPcDYbfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Loe49hlQi33S9O6fT3vhKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'MpuhJU4zgtVygxwku2VZiw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'uEtuUiCFoEjjzk3Y000Usw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'H0GMUIpbpI5WEHGLhW_jXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'bVSDzXHJw-jMJYXsSkSavg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'eWYWTZSHBqKF5A_wu3-hvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5V47UoU54QTMUEhxNPGsHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'bCwsgEKVdp4dnwx-qxRXGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'bDeJzcDQy-hrP0yKqhbj5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'jKukJ2QC5Sa_5AyUTlE71A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'GwbFNyqn0vLJ7O300h41sA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RZ7P7ww4r93sz7a3oWz_YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vZqfLO_6-sj8fjy6JYn0uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ZKo5HonbyrqUcRk8Hr_OfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'HEjUcadPrMj3B5vwgtu6LQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'n3mxdtQEUTazG1I4My4UjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'v6-lpj3S2G8BaStfJjU-bQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'DljfKbe88IkJyTj4TY2kww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'QwSdzE3KUxMa_9tzOqcNiQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'dW7HHYwP2Vb_a7TiRrHVIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'LW_lHNBeXeCRPOOaI1vTgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'T9i6ye-1A-xJlfM6wBBnMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'peWg2RCbCr9luocApxCL6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'z5BMaIiRTE3WdhZdB4pESQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_8PeD6rNGgG7bT11tavsvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_9kj1ldxPmCkYeFWiduwEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'rH5m6AoIsqNrURc-weTvwA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '8k5L6U7mHtT6jz8B2WR1rg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '1oOMSsvHJFAluqCtJs_-fA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'T9pdVjgO1hXra-DBlJE78g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '5cM4AG_lnuObDUPd5XTkMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'nA8Fd57FoFdzE6zxRacrOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'DlmugEjR0DtDkQsri3HdwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '6on-3iCX03WcvVx4uWNBdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'MNtlw5dzP5nttwe-qYj4Ow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'S_Gl1aE51Flgm3X-iuvBsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RARgUR75jq0O9KfKByldRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ONEOR31oMuUt0cImEKQfRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ixSfXsL-bUntEBt6nPJsMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'qU4DsV4aRHAfgiS6pzAivg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'iTVGCIv1wj2S-6C4gpNtug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'cE7yJEZKOU2J0Vk3M2jGug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'g8-vA2KKlaijcSdk7UnLlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Hy5WUoGvNqajJaAtIda2Og' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'G0xeaKq_kqcZZIsTUYVkTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'clNv1FuXwu-Zyz8JgGHzag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NkX1a4xCgX3C-IA9C4_rbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'Dx19fKXL70mWQZkG7sB4Dg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'GPCtNUW_f-ViMHMoGWKo6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ZawiYPX0uQjMBszpZqf_2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'vgu2nAKR6Z8r_yrLsuEGHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'fvf3HakraAOjfC2CewByMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'SRAshP38ZV7FnQgIT8dJHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'wJ4rD0Tv2AjjlFceyiYBEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'nt4lcSGjhfpkokSb5s14kA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'ufq6frpweNk6alC-XAEqUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'V1TBYt8HlKVucgRoy5z7Kw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'hC7mpRMBzCgo9siHEz8gIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'qiQCGEzLAZq8i_W1oDicrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'NV__FyuhEekpVJ-J1FYJPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'jpR2YkNVhJcZ6nhzFHIFqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'nQJIRHQvmyg3aEfRTcVuag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '0Kp8_sahcFYFqUA8TGtflQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'hirU_KecJhUKu9hzAB0djg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'cksai0AysxwrBlv01KYprw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'gw3V8lw_RwUYWHl7V7jsmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '_i1OnS44bShtu25r22wBUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'abYAHYqaRW4rO1sUze9f-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'OLKOxNJA2P6cMuvw0tBGYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'froJfLIdoq7OtvMSFpDxpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'EHW2S6vZh0evhC0TsA_ngw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'A5o1_8FvwkrvqAhCL2Ge2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'RkB9S3C5oh-beVtlxLxgLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'sG5XLI8mm-iLiiOfBZaAsw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'mXb4x28Yn-qxKriFT3iMgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'GxwoOzeCIf9xqOiYSQCQ-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    'okp015HUqxY8WWrICwzOqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '4jbQOgMEtrfM67A0ZsQ08g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4591',
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
    '71o6mJs3v95XeeW3QZ3_hA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:4881',
        'data' => [
            'x' => 'http://google.com'
        ],
        'validated' => [
            'x' => 'http://google.com'
        ],
        'rules' => [
            'x' => 'active_url'
        ],
        'expandedRules' => [
            'x' => [
                'active_url'
            ]
        ]
    ],
    'vcB_f6ZQvgOo5cueXP6pyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:4881',
        'data' => [
            'x' => 'http://www.google.com'
        ],
        'validated' => [
            'x' => 'http://www.google.com'
        ],
        'rules' => [
            'x' => 'active_url'
        ],
        'expandedRules' => [
            'x' => [
                'active_url'
            ]
        ]
    ],
    'N2W1cLi_p02W8YcfEG4xtA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:4881',
        'data' => [
            'x' => 'http://www.google.com/about'
        ],
        'validated' => [
            'x' => 'http://www.google.com/about'
        ],
        'rules' => [
            'x' => 'active_url'
        ],
        'expandedRules' => [
            'x' => [
                'active_url'
            ]
        ]
    ],
    'bnm1rDQHqV_E17nPvctBrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:4999',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'kGrxIh5lg3zvzXCRzL-qSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5005',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    '7-du8DukrasGkZ-tg7fI9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5011',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    '6_qRYkThbIvClzQPboPFoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5017',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'HdnaCTU7iDfoZIWsjBAr3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5023',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'nKIZge65pMix3_t45jn2pQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5026',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'F1zDN1mcVQZtWrJji1PUTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5029',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    '0a8tPgp0lp7RrcP7yiZp7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5032',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'prytbcSyyhFdyAeRmLuCaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5046',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:max_ratio=2/5'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:max_ratio=2/5'
            ]
        ]
    ],
    'GHORlk0Dwpyvbi3vyQKd7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5054',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'ZE1gL3zvl02NSyG9TFJzNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5069',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    'VBXlfb0jdsjh5HP3kIBlBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5076',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg+xml';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg+xml';
                    $this->error = 0;
                    $this->test = true;
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
    '9OJCqH4Aun3F12aljvruSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5082',
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
    'VE1wnYXsHtiNnCQ26lIwaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5089',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg';
                    $this->error = 0;
                    $this->test = true;
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
    '6B7yunIqHzIkComDC8Fr0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5095',
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
    'Y6mWfX1UHHvh4IQsBCGQwA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5107',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:min_ratio=1'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:min_ratio=1'
            ]
        ]
    ],
    'ocmV29n7TSMlhp8l9pDE4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5118',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'rules' => [
            'x' => 'dimensions:ratio=16/9'
        ],
        'expandedRules' => [
            'x' => [
                'dimensions:ratio=16/9'
            ]
        ]
    ],
    'K26eff56mIVsFgGPuxmYCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFile:5237',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
                })->bindTo($object, \Symfony\Component\HttpFoundation\File\UploadedFile::class)();

                return $object;
            })()
        ],
        'validated' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->test = true;
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
    '77SNgIYMWHJdxz74IFcjGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:5244',
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
    'TMk6MQtGSpoFAZ_mj4AGnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:5247',
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
    '2vCWfdObQRenQ8uh9SVuIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testAlternativeFormat:5254',
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
    '06k4YYZa77TIcAVcCiOk2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5261',
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
    'a5EZgQONAG-zx_qytzKsxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5275',
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
    'GRkaxxrJhWY5U8xM2ySkgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5281',
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
    '4oULkLlxcJtp-daGM-E3xg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5287',
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
    'bivO-D06PyD08jTluUHrTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5312',
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
    'z0-7ArMUNgdz6oe-edFivQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5318',
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
    'KSrsVYpmuLz3X2BFNArPZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5321',
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
    'IZ_N_IZDCHhU-k84T2bZ2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5324',
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
    'vVe_05BAnqix3Zfalr2AzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5334',
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
    '9jGzG6ig5_GUtvneqbCmEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5340',
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
    'dmpQXo_0l_Vf19RM9pLKIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5343',
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
    '493cmRetOjBmpfoqqjuIGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaWithAsciiOption:5353',
        'data' => [
            'x' => 'aslsdlks'
        ],
        'validated' => [
            'x' => 'aslsdlks'
        ],
        'rules' => [
            'x' => 'Alpha:ascii'
        ],
        'expandedRules' => [
            'x' => [
                'Alpha:ascii'
            ]
        ]
    ],
    'brZo_HmY3g2ecSKNmHePew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNumWithAsciiOption:5404',
        'data' => [
            'x' => 'asls13dlks'
        ],
        'validated' => [
            'x' => 'asls13dlks'
        ],
        'rules' => [
            'x' => 'AlphaNum:ascii'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaNum:ascii'
            ]
        ]
    ],
    'MWtcK-nY7QHN2H7FQTZ2Bw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDashWithAsciiOption:5429',
        'data' => [
            'x' => 'asls1-_3dlks'
        ],
        'validated' => [
            'x' => 'asls1-_3dlks'
        ],
        'rules' => [
            'x' => 'AlphaDash:ascii'
        ],
        'expandedRules' => [
            'x' => [
                'AlphaDash:ascii'
            ]
        ]
    ],
    'ZAuqDCfS3pVIHtRvzmp4ow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5457',
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
    'QXY8PReXgs-q8y8gpCt7RA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5460',
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
    '5RLv3E0EmCBOit_8nxkn9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5463',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
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
    'Q2EWhxzw_oWCg0C2barcNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAfricaOption:5494',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => 'Timezone:Africa'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Africa'
            ]
        ]
    ],
    'H1-VqD9gRSfKzuBXLtY8HA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAmericaOption:5531',
        'data' => [
            'foo' => 'America/New_York'
        ],
        'validated' => [
            'foo' => 'America/New_York'
        ],
        'rules' => [
            'foo' => 'Timezone:America'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:America'
            ]
        ]
    ],
    'yBfs5lMYgvDy-ikhFCw3tA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAntarcticaOption:5565',
        'data' => [
            'foo' => 'Antarctica/Mawson'
        ],
        'validated' => [
            'foo' => 'Antarctica/Mawson'
        ],
        'rules' => [
            'foo' => 'Timezone:Antarctica'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Antarctica'
            ]
        ]
    ],
    '0ucl1LCKnWNRprsktYwhrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithArcticOption:5599',
        'data' => [
            'foo' => 'Arctic/Longyearbyen'
        ],
        'validated' => [
            'foo' => 'Arctic/Longyearbyen'
        ],
        'rules' => [
            'foo' => 'Timezone:Arctic'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Arctic'
            ]
        ]
    ],
    'fTWcZYFey32LBZeuVIOGYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAsiaOption:5633',
        'data' => [
            'foo' => 'Asia/Tokyo'
        ],
        'validated' => [
            'foo' => 'Asia/Tokyo'
        ],
        'rules' => [
            'foo' => 'Timezone:Asia'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Asia'
            ]
        ]
    ],
    'ZC33DgGLTnYMu4T7DI9-ew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAtlanticOption:5667',
        'data' => [
            'foo' => 'Atlantic/Cape_Verde'
        ],
        'validated' => [
            'foo' => 'Atlantic/Cape_Verde'
        ],
        'rules' => [
            'foo' => 'Timezone:Atlantic'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Atlantic'
            ]
        ]
    ],
    '82AVAyJYG2MKnCzD78B9Bw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAustraliaOption:5701',
        'data' => [
            'foo' => 'Australia/Sydney'
        ],
        'validated' => [
            'foo' => 'Australia/Sydney'
        ],
        'rules' => [
            'foo' => 'Timezone:Australia'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Australia'
            ]
        ]
    ],
    'hOECv4CtjAdyDKegLPzFrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithEuropeOption:5735',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
        ],
        'rules' => [
            'foo' => 'Timezone:Europe'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Europe'
            ]
        ]
    ],
    '1XBNAU_QmDVK-ETnH_-kNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithIndianOption:5769',
        'data' => [
            'foo' => 'Indian/Christmas'
        ],
        'validated' => [
            'foo' => 'Indian/Christmas'
        ],
        'rules' => [
            'foo' => 'Timezone:Indian'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Indian'
            ]
        ]
    ],
    'yW3ywwBEPwDiYHH5Rqbncw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPacificOption:5803',
        'data' => [
            'foo' => 'Pacific/Fiji'
        ],
        'validated' => [
            'foo' => 'Pacific/Fiji'
        ],
        'rules' => [
            'foo' => 'Timezone:Pacific'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Pacific'
            ]
        ]
    ],
    '0jocAzugogFmCDajWeHgZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithUTCOption:5831',
        'data' => [
            'foo' => 'UTC'
        ],
        'validated' => [
            'foo' => 'UTC'
        ],
        'rules' => [
            'foo' => 'Timezone:UTC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:UTC'
            ]
        ]
    ],
    's9miozLclG-bOsU9JFCsmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:5862',
        'data' => [
            'foo' => 'UTC'
        ],
        'validated' => [
            'foo' => 'UTC'
        ],
        'rules' => [
            'foo' => 'Timezone:All'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All'
            ]
        ]
    ],
    'feh_FUM1mEJQG65ACjaHxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:5865',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => 'Timezone:All'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All'
            ]
        ]
    ],
    'hSJfFvRNZpqsWwmcCJ_cVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:5868',
        'data' => [
            'foo' => 'Indian/Christmas'
        ],
        'validated' => [
            'foo' => 'Indian/Christmas'
        ],
        'rules' => [
            'foo' => 'Timezone:All'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All'
            ]
        ]
    ],
    'iBy0AH4S950OWHrubYvNfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:5871',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
        ],
        'rules' => [
            'foo' => 'Timezone:All'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All'
            ]
        ]
    ],
    'Ilyl01BTqkjEzenqHor87Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5899',
        'data' => [
            'foo' => 'UTC'
        ],
        'validated' => [
            'foo' => 'UTC'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'tE0yhnCvdupWkoiZHzihGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5902',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'HbrnF5XUgdlrpvRWH1rt1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5905',
        'data' => [
            'foo' => 'Indian/Christmas'
        ],
        'validated' => [
            'foo' => 'Indian/Christmas'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'Uh4THSLh45EMd_1hbNbG_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5908',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'ovYH-8MCnJMErA9KJaRHCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5911',
        'data' => [
            'foo' => 'Europe/Kiev'
        ],
        'validated' => [
            'foo' => 'Europe/Kiev'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    '0Qhzn2cKgV2PRMnNJLteMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5917',
        'data' => [
            'foo' => 'GMT'
        ],
        'validated' => [
            'foo' => 'GMT'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'UlV_en36S7Qa0dLBLkfXLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:5920',
        'data' => [
            'foo' => 'GB'
        ],
        'validated' => [
            'foo' => 'GB'
        ],
        'rules' => [
            'foo' => 'Timezone:All_with_BC'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:All_with_BC'
            ]
        ]
    ],
    'ybyvGKhGLkML8-KA5eHstw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:5939',
        'data' => [
            'foo' => 'Africa/Windhoek'
        ],
        'validated' => [
            'foo' => 'Africa/Windhoek'
        ],
        'rules' => [
            'foo' => 'Timezone:Per_country,NA'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Per_country,NA'
            ]
        ]
    ],
    'DbevFwLsJso27Iyv1GlsbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:5945',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
        ],
        'rules' => [
            'foo' => 'Timezone:Per_country,UA'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Per_country,UA'
            ]
        ]
    ],
    '80LEmMHtuSwJSYXadlD5Mg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:5948',
        'data' => [
            'foo' => 'Europe/Kyiv'
        ],
        'validated' => [
            'foo' => 'Europe/Kyiv'
        ],
        'rules' => [
            'foo' => 'Timezone:Per_country,ua'
        ],
        'expandedRules' => [
            'foo' => [
                'Timezone:Per_country,ua'
            ]
        ]
    ],
    'ap0DGxzffVAMm5aS-bHI4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:5967',
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
    '3siO4XRkLMG4OxiZ8L1OZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:5974',
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
    'VdI94Nw5AXRHw61WTECqVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:5977',
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
    'nje4n3i9hdZ2SeRs4MiZWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:5980',
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
    'gX6-zRWTJFR6EBfujZvbaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:5983',
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
    'mXDewt83UFwLhMJz5wIo2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:5990',
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
    'sfioVniccFPOI6Ei1Axefg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:5997',
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
    'V1NE4RLg0lOKiNJLDSy5LQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6005',
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
    '8r9B7GMr0VTqDaEGZcSg_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6008',
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
    '9PKAPWb5-h_MDqgIOQsk0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6020',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2026-08-03 07:13:40.491240',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2026-08-03 07:13:40.491240',
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
    'XE6Zb4lNXyYyjZOoKlRVuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6023',
        'data' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2026-08-03 07:13:40.491355',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2026-08-03 07:13:40.491355',
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
    '-qNRYKOEktf1CF0lDAq3WA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6026',
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
    'TnkOVJkkMt3yXLSc7P7ZDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6045',
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
    'i8svfq3480sTRP5q6MKGjg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6048',
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
    'C3nfJak3It81bml3ROzopw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6051',
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
    'pR3NcTghjlpQ90MVn7nz3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6054',
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
    '14zziW-mrbJ3Am6R-ZHwqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6057',
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
    'UfYW0HIRs_ObzoLmHi8tyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6060',
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
    'hNRuqnz3leyJMfjcWl1z_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6066',
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
    '1B6Apvf2ZmLygXljl_F-qQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6069',
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
    'iKnpzAMqFa06LNe_cSOW-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6072',
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
    'KW4DvRkAd9pxjw8Z8u9rMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6078',
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
    'beEm3cBNws6pgffovrUyGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6086',
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
    'rJZxLaNPmDzDJ1EYn3kM-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6089',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000000000402c0000000000000000',
                'clock' => null,
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
                'constructedObjectId' => '000000000000402c0000000000000000',
                'clock' => null,
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
    'sfgKubAxkyCI37IMdZKeRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6095',
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
    'yeRvSzRCTpBffVFlKugg2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6098',
        'data' => [
            'x' => '2026-08-03'
        ],
        'validated' => [
            'x' => '2026-08-03'
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
    'v1Une0gCUPkEFko52JJwgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6107',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    'NY6PFo-rzegdbtll2BGVeA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6116',
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
    'Hugd-lQ6ulbv0oYOsa2RzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6125',
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
    'vqLF8yL9LQ3w-X8V4bE-HQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6134',
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
    'cKyN2dvrcCmX9Qukt57c6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6150',
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
    'T-JvN8YkEToOetYpGLEW7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6153',
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
    'wJgstczlI9Gv4W2EyVnovg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6162',
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
    'dSmRSZE6vDxpPZd0_2MF7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6171',
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
    'i1Nyg6xoxwYxPgXRW9d3YQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6180',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '00000000000046b00000000000000000',
                'clock' => null,
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
                'constructedObjectId' => '00000000000046b00000000000000000',
                'clock' => null,
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
    'e-5p1FVvcMsWQv_iKa_oSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6194',
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
    'kd7dS-uW2Tr8ZKSMHxdB6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6200',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000000002fa70000000000000000',
                'clock' => null,
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
                'constructedObjectId' => '0000000000002fa70000000000000000',
                'clock' => null,
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
    'P1TNG3DZDm8KiXjrNN2gbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6206',
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
    'A0etNTvTJKrxlmLm5CICIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6212',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000000003f880000000000000000',
                'clock' => null,
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
                'constructedObjectId' => '0000000000003f880000000000000000',
                'clock' => null,
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
    'bJvLadjTOa-pWNXPyQHanA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6218',
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
    'hXrEwNS1RlCrwCnksIim6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6224',
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
    'SE4aKvCrBlirzbMPSfVxFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6230',
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
    'gG4rkoGuZK8KEKVxZijaDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6233',
        'data' => [
            'start' => \DateTime::__set_state([
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ]),
            'ends' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '000000000000342b0000000000000000',
                'clock' => null,
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
                'constructedObjectId' => '000000000000342b0000000000000000',
                'clock' => null,
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
    '5QAAhsYT9ao3SvtnBvkSsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6236',
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
    'H9lyrB_Wo8ExE3p3PClfNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6242',
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
    'X2-jN42KMTDtH712k5vXUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6245',
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
    'JF5ngrM5KgbCoY8uMctdYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6248',
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
    'bRJe7HBbyyGDZkv5AXeFpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6257',
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
    '0gSVKJOSO1pnhRxzXDxskw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6260',
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
    'r4bmQkVyl2QFnOLafw8E6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6269',
        'data' => [
            'x' => '0001-01-01T00:00'
        ],
        'validated' => [
            'x' => '0001-01-01T00:00'
        ],
        'rules' => [
            'x' => 'before:1970-01-01'
        ],
        'expandedRules' => [
            'x' => [
                'before:1970-01-01'
            ]
        ]
    ],
    '67-uW4u6d9NYKrW9rCvhlQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6286',
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
    'UCSDfKvlc7fQR3XnoVE5Eg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6295',
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
    'bnucIlX8FJjGkB_MqbUyXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6301',
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
    'S6_kigw4HCtbgJyzjUyDzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6313',
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
    'SiY5ZIUMxWB6Nsuiu-GhIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6328',
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
    'blH9zY-Cx-xGBD71jrApRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6337',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    'aJPYq9IUV7Dgq7rR5uoTVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6346',
        'data' => [
            'x' => '2026-08-03'
        ],
        'validated' => [
            'x' => '2026-08-03'
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
    'VpqlFTZ-qTAT9Tx9XnkJvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6355',
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
    'JTydxtCCE8wkM_yzG40wRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6364',
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
    'Rlc8Ibmg6qmP1GAJ_fFS7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6373',
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
    'K8svOFVzGlGt76O10Rccgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6388',
        'data' => [
            'from' => '2020-08-05',
            'to' => '2020-06-08'
        ],
        'validated' => [
            'from' => '2020-08-05',
            'to' => '2020-06-08'
        ],
        'rules' => [
            'from' => 'date_format:Y-m-d|before:to',
            'to' => 'date_format:Y-d-m'
        ],
        'expandedRules' => [
            'from' => [
                'date_format:Y-m-d',
                'before:to'
            ],
            'to' => [
                'date_format:Y-d-m'
            ]
        ]
    ],
    'biGcy_PUV6IKlgt7dTNgCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6391',
        'data' => [
            'from' => '2020-05-08',
            'to' => '2020-08-06'
        ],
        'validated' => [
            'from' => '2020-05-08',
            'to' => '2020-08-06'
        ],
        'rules' => [
            'from' => 'date_format:Y-m-d',
            'to' => 'date_format:Y-d-m|after:from'
        ],
        'expandedRules' => [
            'from' => [
                'date_format:Y-m-d'
            ],
            'to' => [
                'date_format:Y-d-m',
                'after:from'
            ]
        ]
    ],
    'BX-x-SwZIDYoge2eSNSb5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6399',
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
    'ESMH6cI_uT_baBLQlIEDWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6402',
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
    'naPagViAKKV4kGWnsDOBrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6408',
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
    'vdM_F-M5k4l0Hz4RdszWFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6414',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    'IgzMYrkmW2eiYkMU-Xx6GA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6417',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    'Cw4rH8ZmMzG4C5_SVGCKwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6423',
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
    'CMo2ktzY5XuG-RDDsCbFcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6426',
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
    'tVPDxuYOYq6xM0n09imWAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6432',
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
    'txu4xw32ztVCE7vWC7k7KA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6438',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    'KbWWS3tQRh7wnF7cvL4YPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6441',
        'data' => [
            'x' => '03/08/2026'
        ],
        'validated' => [
            'x' => '03/08/2026'
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
    '_Y7aUiflJfTCre6LrJzHWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6447',
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
    'gHonKz_8ZEvolpTbBokp5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6456',
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
    'FvYB_JJzCA-LXObCBmnc7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6465',
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
    'fzBJa49DmIFZ2tLkPRPp5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6474',
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
    'vDGdVx5BAUOIIx8vj1VjnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6477',
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
    'w3zMbLSB0O1Qx7ip0shNyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6486',
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
    '3qDIVQRd-Xurbs3sx7hAog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6495',
        'data' => [
            'from' => '2020-08-05',
            'to' => '2020-05-08'
        ],
        'validated' => [
            'from' => '2020-08-05',
            'to' => '2020-05-08'
        ],
        'rules' => [
            'from' => 'date_format:Y-m-d|before_or_equal:to',
            'to' => 'date_format:Y-d-m'
        ],
        'expandedRules' => [
            'from' => [
                'date_format:Y-m-d',
                'before_or_equal:to'
            ],
            'to' => [
                'date_format:Y-d-m'
            ]
        ]
    ],
    'dCBLf0mh9rpNGFc0F0e47w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6498',
        'data' => [
            'from' => '2020-05-08',
            'to' => '2020-08-05'
        ],
        'validated' => [
            'from' => '2020-05-08',
            'to' => '2020-08-05'
        ],
        'rules' => [
            'from' => 'date_format:Y-m-d',
            'to' => 'date_format:Y-d-m|after_or_equal:from'
        ],
        'expandedRules' => [
            'from' => [
                'date_format:Y-m-d'
            ],
            'to' => [
                'date_format:Y-d-m',
                'after_or_equal:from'
            ]
        ]
    ],
    'KZS21c30hNlcWMUH450NNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomImplicitValidators:6862',
        'data' => [],
        'validated' => [],
        'rules' => [
            'implicit_rule' => 'foo'
        ],
        'expandedRules' => [
            'implicit_rule' => [
                'foo'
            ]
        ]
    ],
    'UbGOEM_aoFWn6ur-qqleSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomDependentValidators:6877',
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
    'BnPgNpidfOWboG7MOjKjpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:6907',
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
    'C3gc4-DCU4VRfwFbXIpKWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:6921',
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
    'vV9BTxMGhSdXta_yx3ZSyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:6927',
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
    'fitl8_wh3jVx1wjjhrK7jQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:6944',
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
    '0XiFQZbsOZ1kspmFSiy4GQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesOnArraysInImplicitRules:6963',
        'data' => [
            [
                'bar' => 'baz'
            ]
        ],
        'validated' => [],
        'rules' => [
            '*.foo' => 'sometimes|required|string'
        ],
        'expandedRules' => [
            '0.foo' => [
                'sometimes',
                'required',
                'string'
            ]
        ]
    ],
    'cn9aimC6S0Ur4RzB4nRlVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:6989',
        'data' => [],
        'validated' => [],
        'rules' => [
            'names.*.first' => 'required'
        ],
        'expandedRules' => []
    ],
    'MLNKdY7tSV4CaB5rs6MU3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:7021',
        'data' => [
            'names' => [
                [
                    'second' => '2'
                ]
            ]
        ],
        'validated' => [],
        'rules' => [
            'names.*.first' => 'sometimes|required'
        ],
        'expandedRules' => [
            'names.0.first' => [
                'sometimes',
                'required'
            ]
        ]
    ],
    'iJQA17l1R2MDqkaE_ycgQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:7063',
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
    'dMFg0S5zpHVrq6uVz0LMMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7083',
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
    'tADd4GRJYjUQOAq2-qQ9rQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7086',
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
    '4UjITbttCXTk2xfQZy2QWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7089',
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
    '89ShmWMyoDt49SELjFMKDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7092',
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
    'aqmQFzlIYfO_4UaTmjOsLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPassingSlashVulnerability:7111',
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
    '7Lk-mGgNO9HvN7txDybhwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:7137',
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
    'Rtx_Zy5VeBa3NiQ_sBHlfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:7152',
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
    'bs3Q4m3ugaiN6TmdYRD6aA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testImplicitEachWithAsterisksWithArrayValues:7188',
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
    'ZrQ7HGenG_Ip3tQ3XucFlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNestedArrayWithCommonParentChildKey:7212',
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
    'DeFw4EkYm2rBqUfFVxRBEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:7240',
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
    'yywJ0eg2nZoOu9boikNI-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:7259',
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
    'up8P0nmyAsEXwPvzIsXSlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:7299',
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
    'jLK4OfdBjN9Y-ljo_2VjhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:7312',
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
    'fNJrsb1joMGP4LYB5V3ebw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:7352',
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
    'et7RpuNOzARD56fXIl42qA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:7365',
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
    'csny1Utxfj_AxzqmqKXAMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:7405',
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
    'C595dNQtlFLKSn1P2OrjCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:7414',
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
    'csDrUvGECq6uzkK_1qslhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:7454',
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
    'dIKdNxmkUTry96BatryINg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:7463',
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
    'eyaBFKs2cEcfqv2Dkh2eOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredUnless:7503',
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
    'h3Zlg-BlCdrmXUgwL9fD0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredUnless:7512',
        'data' => [
            'foo' => [
                [
                    'name' => null,
                    'last' => 'foo'
                ],
                [
                    'name' => 'second',
                    'last' => 'foo'
                ]
            ]
        ],
        'validated' => [],
        'rules' => [
            'foo.*.bar.*.name' => [
                'Required_unless:foo.*.bar.*.last,foo'
            ]
        ],
        'expandedRules' => []
    ],
    'EP4iI2gLIzIg3ulWrMvxXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:7552',
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
    'E0uxLb-Fm7FoKUwDfdk3_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:7561',
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
    'PzwsrfNt5p8UEYniSVsuZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:7609',
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
    'aczeS4VOEp6KFU79TfXsoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:7618',
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
    'PgvNV1nm_6RYMaE-uz7MGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:7658',
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
    'dAJxhOAmcntanVkySOKkyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:7667',
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
    'gZ1Bwory6AakiHFBOrCh0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:7708',
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
    'P7QHnt_XNA1qe0uJGUdlcQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:7719',
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
    'orIEVLU12Spq0pTDOYKD9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:7756',
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
    'zeTtp8nM9TTww7u4_yhCFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:7770',
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
    '15L3ogEQ2eUtPRgrfPWjqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedData:8423',
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
    'wNiz1JGIsjcOkRSdLol9QA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedRules:8438',
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
    'tEDI8PCWQnHrPHLdAOAHUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedChildRules:8451',
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
    '3hOENqUy2KEQR2IekaALLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedArrayRules:8464',
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
    'vzgJfXNfuOHxyyGTWkH_uQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAndValidatedData:8477',
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
    '-_vLRFFt6hYYhx4UXXtyIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    '-rrk_TYpmmofziTEbpRP4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'zqrPtfKC2cPEq7GNs5CZQA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    '1an_gh9-M1CfbKPX1YeGdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'aKpKAzpZimUXR17S6TLBBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'TsfvjZStQUSXyOjR11LkZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'mpMlQHwKf0BVVh9CMEGHvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    '9GBGzOY2IgrH0Qyo2oExtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'hh4wy_ciy1P0RCd5uh3Prg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'GPTZIKOiSsIXSAbC44YGpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8513',
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
    'u0UzSuLkE1kTf-3DF9humw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidAscii:8560',
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
    'hwULn8EpT82mI_ymJ_r6fQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUlid:8574',
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
    'Ytpvhq3gFV5YLUOw80wNMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    '1PcsMYQJAImWPa23BI231w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'vCERNGKSoEye_DW1Fluurw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'eInAuMee0DRGFEcY-bXvWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'j8-xBRssaSsFs0NGO1oF5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'jJiPh5Ngy2b3UqBE6gE6-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'uMgfg845bjU-O5-Fh-MYLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'mi7vE6U2qvUYa6AhBsJuKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'sINwIZ1wLqKHgIGOObjV_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'iH4J8mJQM4JiGRv5-lHq3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'zDbpL3fQz9WbZNyyYaSIeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'f2ftsqk5XnfoiEXVRexu1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:8803',
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
    'n-34jZnOhd4g247OB3QZTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExclude:8956',
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
    'kpWem6d0k5doSeh0WaErrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeBeforeADependentRule:8985',
        'data' => [
            'profile_id' => null
        ],
        'validated' => [
            'profile_id' => null
        ],
        'rules' => [
            'type' => [
                'required',
                'string',
                'exclude'
            ],
            'profile_id' => [
                'nullable',
                'required_if:type,profile',
                'integer'
            ]
        ],
        'expandedRules' => [
            'profile_id' => [
                'nullable',
                'required_if:type,profile',
                'integer'
            ]
        ]
    ],
    'ReNd-XFGBVA7gzOGcqAWAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9012',
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
    'dntu3_1TsoAPR3KNebI9Fg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9021',
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
    'Tt-kQckqz8rBvxm2GIYUzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9030',
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
    'QkDVwHsGq2HIgQn2uvecWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9039',
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
    'qDAbcNz9NEdofQeWDlqBjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9048',
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
    '85DtVSlT4Yyey30yMgybrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9057',
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
    'bdjyRUXyeNs0tSoDTH9VYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9066',
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
    'fbffEWT6xdZxjhHRvbyhdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9075',
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
            'users' => 'list',
            'users.*.name' => 'string'
        ],
        'expandedRules' => [
            'users' => [
                'list'
            ],
            'users.0.name' => [
                'string'
            ]
        ]
    ],
    'E8kEct4pMSK7QM1Na1ws-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9086',
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
    '87GpfEOyaVa0h5EYNrnPRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9094',
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
    'rgtlcafZIqHUEDxv6PJAXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9102',
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
    'ApnfBuElf-VXDX2B8TqotQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9118',
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
    'DN7SptVtxf1RbjKUpvPi2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9123',
        'data' => [],
        'validated' => [],
        'rules' => [
            'bar' => 'exclude_unless:foo,true'
        ],
        'expandedRules' => []
    ],
    'vRGp12Cs2ZlBogDcAP7DSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9128',
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
    'ifmi6MdtarDyNzMJQ_Rn6A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeValuesAreReallyRemoved:9154',
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
    'hVl9cA6XdcS1qp0BYLaRPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeWithValuesAreReallyRemoved:9183',
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
    '2LvFkZDJPqL2wa8IckhhYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWhenHasKeys:9257',
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
    'PQ-aRHo8yoBMOAQHayL5RA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWithPartialMatch:9280',
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
    ],
    'gaCLlkUceyWIH5-WU2gg5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItTrimsSpaceFromParameters:9452',
        'data' => [
            'min' => ' 20 ',
            'min_str' => ' abc ',
            'multiple_of' => ' 0.5 ',
            'between' => '
 5 
',
            'between_str' => ' abc ',
            'gt' => '	5 ',
            'gt_field' => '	5 ',
            'gt_str' => ' abc ',
            'lt' => '	5 ',
            'lt_field' => '	5 ',
            'lt_str' => ' abc ',
            'gte' => '	5 ',
            'gte_field' => '	5 ',
            'gte_str' => ' abc ',
            'lte' => '	5 ',
            'lte_field' => '	5 ',
            'lte_str' => ' abc ',
            'max' => ' 20 ',
            'max_str' => ' abc ',
            'size' => ' 20 ',
            'size_str' => ' abc ',
            'foo' => '4',
            ' foo' => ' 5',
            ' foo ' => ' 6 ',
            'foo_str' => 'abcd',
            ' foo_str' => ' abcd',
            ' foo_str ' => ' abcd '
        ],
        'validated' => [
            'min' => ' 20 ',
            'min_str' => ' abc ',
            'multiple_of' => ' 0.5 ',
            'between' => '
 5 
',
            'between_str' => ' abc ',
            'gt' => '	5 ',
            'gt_field' => '	5 ',
            'gt_str' => ' abc ',
            'lt' => '	5 ',
            'lt_field' => '	5 ',
            'lt_str' => ' abc ',
            'gte' => '	5 ',
            'gte_field' => '	5 ',
            'gte_str' => ' abc ',
            'lte' => '	5 ',
            'lte_field' => '	5 ',
            'lte_str' => ' abc ',
            'max' => ' 20 ',
            'max_str' => ' abc ',
            'size' => ' 20 ',
            'size_str' => ' abc '
        ],
        'rules' => [
            'min' => 'numeric|min: 20',
            'min_str' => 'min: 5',
            'multiple_of' => 'multiple_of:0.25 ',
            'between' => 'numeric|between:	 4, 6
',
            'between_str' => 'between:	 5, 6
',
            'gt' => 'numeric|gt: 4',
            'gt_field' => 'numeric|gt:foo',
            'gt_str' => 'gt:foo_str',
            'lt' => 'numeric|lt: 6',
            'lt_field' => 'numeric|lt: foo ',
            'lt_str' => 'lt: foo_str ',
            'gte' => 'numeric|gte: 5',
            'gte_field' => 'numeric|gte: foo',
            'gte_str' => 'gte: foo_str',
            'lte' => 'numeric|lte: 5',
            'lte_field' => 'numeric|lte: foo',
            'lte_str' => 'lte: foo_str',
            'max' => 'numeric|max: 20',
            'max_str' => 'max: 5',
            'size' => 'numeric|size: 20',
            'size_str' => 'size: 5'
        ],
        'expandedRules' => [
            'min' => [
                'numeric',
                'min: 20'
            ],
            'min_str' => [
                'min: 5'
            ],
            'multiple_of' => [
                'multiple_of:0.25 '
            ],
            'between' => [
                'numeric',
                'between:	 4, 6
'
            ],
            'between_str' => [
                'between:	 5, 6
'
            ],
            'gt' => [
                'numeric',
                'gt: 4'
            ],
            'gt_field' => [
                'numeric',
                'gt:foo'
            ],
            'gt_str' => [
                'gt:foo_str'
            ],
            'lt' => [
                'numeric',
                'lt: 6'
            ],
            'lt_field' => [
                'numeric',
                'lt: foo '
            ],
            'lt_str' => [
                'lt: foo_str '
            ],
            'gte' => [
                'numeric',
                'gte: 5'
            ],
            'gte_field' => [
                'numeric',
                'gte: foo'
            ],
            'gte_str' => [
                'gte: foo_str'
            ],
            'lte' => [
                'numeric',
                'lte: 5'
            ],
            'lte_field' => [
                'numeric',
                'lte: foo'
            ],
            'lte_str' => [
                'lte: foo_str'
            ],
            'max' => [
                'numeric',
                'max: 20'
            ],
            'max_str' => [
                'max: 5'
            ],
            'size' => [
                'numeric',
                'size: 20'
            ],
            'size_str' => [
                'size: 5'
            ]
        ]
    ],
    'TSXG4aLSa7EOO9hBNDs71g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0e+1000'
        ],
        'validated' => [
            'foo' => '1.0e+1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ]
    ],
    '5b6b5rgtS2wgRJghsA062A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0E+1000'
        ],
        'validated' => [
            'foo' => '1.0E+1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ]
    ],
    'snzcEICuSjlyHm_tlrT9QA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0e1000'
        ],
        'validated' => [
            'foo' => '1.0e1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ]
    ],
    'VPoimFq1UrPLWkBrlgPeSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0E1000'
        ],
        'validated' => [
            'foo' => '1.0E1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'min:3'
            ]
        ]
    ],
    'txoGyLC9Rn_P_mjsOzrzGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0e-1000'
        ],
        'validated' => [
            'foo' => '1.0e-1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ]
    ],
    'qt0h5wFFA4rxpG4OBjy6Ew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9557',
        'data' => [
            'foo' => '1.0E-1000'
        ],
        'validated' => [
            'foo' => '1.0E-1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ]
    ],
    '-3klIVowfG_O2UDVwU1Clg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItCanConfigureAllowedExponentRange:9585',
        'data' => [
            'foo' => '1.0e-1000'
        ],
        'validated' => [
            'foo' => '1.0e-1000'
        ],
        'rules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ],
        'expandedRules' => [
            'foo' => [
                'numeric',
                'max:3'
            ]
        ]
    ]
];