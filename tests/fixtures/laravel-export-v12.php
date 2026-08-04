<?php /* laravel commit 727a8ea294 */ return [
    'v5HxOhCe6aU8-GLsDqnJCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationArrayRuleTest::testArrayValidation:71',
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
    'eWGh4XPwGyYrsNVma0pFcA' => [
        'location' => 'unknown',
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
    '3uXTfD6GE42CGGGUZ8G00Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationFactoryTest::testValidateMethodCanBeCalledPublicly:103',
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
    '6rOeU_WOiyWzaCBTH0eROw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationInArrayKeysTest::testInArrayKeysValidation:18',
        'data' => [
            'foo' => [
                'first_key' => 'bar',
                'second_key' => 'baz'
            ]
        ],
        'validated' => [
            'foo' => [
                'first_key' => 'bar',
                'second_key' => 'baz'
            ]
        ],
        'rules' => [
            'foo' => 'in_array_keys:first_key,third_key'
        ],
        'expandedRules' => [
            'foo' => [
                'in_array_keys:first_key,third_key'
            ]
        ]
    ],
    'YgFJ-YSNg9JGC8SUBFH6Lg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationInArrayKeysTest::testInArrayKeysValidation:22',
        'data' => [
            'foo' => [
                'first_key' => 'bar',
                'second_key' => 'baz'
            ]
        ],
        'validated' => [
            'foo' => [
                'first_key' => 'bar',
                'second_key' => 'baz'
            ]
        ],
        'rules' => [
            'foo' => 'in_array_keys:first_key,second_key'
        ],
        'expandedRules' => [
            'foo' => [
                'in_array_keys:first_key,second_key'
            ]
        ]
    ],
    'YhuSs1UU-gyQWZE4FxwPGw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationInArrayKeysTest::testInArrayKeysValidationWithNestedArrays:48',
        'data' => [
            'foo' => [
                'first_key' => [
                    'nested' => 'value'
                ],
                'second_key' => 'baz'
            ]
        ],
        'validated' => [
            'foo' => [
                'first_key' => [
                    'nested' => 'value'
                ],
                'second_key' => 'baz'
            ]
        ],
        'rules' => [
            'foo' => 'in_array_keys:first_key,third_key'
        ],
        'expandedRules' => [
            'foo' => [
                'in_array_keys:first_key,third_key'
            ]
        ]
    ],
    '_vP1N18Fzj79IYXZJbHsJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationInArrayKeysTest::testInArrayKeysValidationWithNestedArrays:58',
        'data' => [
            'foo' => [
                'first' => [
                    'nested_key' => 'value'
                ]
            ]
        ],
        'validated' => [
            'foo' => [
                'first' => [
                    'nested_key' => 'value'
                ]
            ]
        ],
        'rules' => [
            'foo.first' => 'in_array_keys:nested_key'
        ],
        'expandedRules' => [
            'foo.first' => [
                'in_array_keys:nested_key'
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
    'h91wm5eU2OKFH6KDOWyptg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:193',
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
    'REq61OjKO1J9WKfXqNfQFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:203',
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
    'Zctwcn6meis21zbe9cpIag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:213',
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
    '6uTUFGZ_2Wbh7OQ-dv6QuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:223',
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
    'h2jf5ZI4xA26HAXUZc8axQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:233',
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
    'tOwJvDUFdRE4vnqhc6HZkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:243',
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
    '7AiuoIraDHgYwCu6Af5vLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:253',
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
    'dqcvd2BoF_mOI-kcydtl_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:263',
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
    'RDlCwP8FDvtuFEfmxv7c-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:273',
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
    'zdmkN1JLDMcAnk09yYidLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:283',
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
    'y9fpxP0GhJPbo3ey9KM7QA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:293',
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
    'SDxi6dls65JTVHV39nyAGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:303',
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
    'giGgUDZ_XcmP6GRAb93nDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:313',
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
    'Emtvpy7fIXAk8Mk30Gwl_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:323',
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
    'XyzAp_FwoRrAzXxYU2Xidg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:333',
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
    '3MI7EDU0KhjmLx3jqp-v2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationNumericRuleTest::testNumericValidation:343',
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
    'L_JLIS7VCQ4NTQRftNDF8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationRuleDoesntContainTest::testDoesntContainValidation:75',
        'data' => [
            'roles' => [
                'admin',
                'user'
            ]
        ],
        'validated' => [
            'roles' => [
                'admin',
                'user'
            ]
        ],
        'rules' => [
            'roles' => 'doesnt_contain:editor'
        ],
        'expandedRules' => [
            'roles' => [
                'doesnt_contain:editor'
            ]
        ]
    ],
    'AyAV-jtVkzdWikDlrWxCQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnNestedArrays:138',
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
    '5Ga1EAwGMgmDgWkfYGYygQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesWorksOnArrays:169',
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
    'G8Btcr7YkeMNN_why-g3Ag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntThrowOnPass:187',
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
    'GLlp1qhX2ZMlynD7bw1IjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:216',
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
    'a0DqpduW2qSzGW6a4QCWlQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:219',
        'data' => [],
        'validated' => [],
        'rules' => [],
        'expandedRules' => []
    ],
    'Tg-JRGCvETna5r_Tk0et_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatedDoesntThrowOnPass:222',
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
    'RkplvwqImIJa1ZPXwiuKzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testHasFailedValidationRules:233',
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
    'YAOqm6A-B_i0PzTNU0qjWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testHasNotFailedValidationRules:250',
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
    'jg02K_twCuK_qKFNeHwmfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesCanSkipRequiredRules:259',
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
    'zfqiBk7OvRDtq9WYKWL0SQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testInValidatableRulesReturnsValid:268',
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
    'AbZXXyvCRfJk_cdAtIoxag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUsingNestedValidationRulesPasses:288',
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
    'z1AknNn50QvhaF9QUuWnag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:307',
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
    'Ha_3pNda4dTTyYXryyB3qQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:310',
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
    'Ypumtg2cl7OXtE_2oIEw-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:313',
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
    'UOgrycGHGvuxC3eJfOFPFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmptyStringsAlwaysPasses:316',
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
    'ZNu-ZBQfOqMvHTvRZ8uTAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:324',
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
    'nygTFpWucqF89tpOl7tcHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyExistingAttributesAreValidated:339',
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
    'm2QJ8nAdK-nNBIa-LJ2MLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullable:351',
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
    'DRuOCDZu6UUo2IQ2t_m2tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayNullableWithUnvalidatedArrayKeys:376',
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
    'PhNd2cJfHxdH5AobJ9W0zA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNullableMakesNoDifferenceIfImplicitRuleExists:398',
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
    'qxv_9iHa86QkZUse_uXLOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testIndexValuesAreReplaced:988',
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
    'GMVsj8kek_mK8d9O5_SFMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPositionValuesAreReplaced:1020',
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
    'o96eaNHpTJkgF-yMiNLkIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testOrdinalPositionValuesAreReplaced:1049',
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
    'Uqj4diUOmekyCetbbgHkew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArray:1275',
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
    'pJMq8l8982Rxsg2b2DbEpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateList:1286',
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
    'GvDDV2Wxtmi8z98oeOslgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:1304',
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
    'EXYcjK69hiXyFBum8RF3QA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateArrayKeys:1308',
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
    'XCDKiLVqd1H8sa8OB_jMGw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1382',
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
    '2lfGNtUTp1Jlp95J4ZcjbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateCurrentPassword:1406',
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
    'ilq7N58l5VvY3R77LO-PPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1413',
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
    'MqxcQ5Pyrl_XKP8YZdfUHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFilled:1419',
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
    'IkChpmISmLfNc22p7OLYxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1459',
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
    '0qAS1GjlfUXG6gtn7e0Feg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1462',
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
    'A11cPhNQgoSIzU7kCpkI8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1471',
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
    'yBbjfVBw38gzGOU0Tbgedw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresent:1474',
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
    'wsAXGdTl_fJEoWaC9JeZtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1487',
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
    '98HgBkj9B2IKjzCawkkmDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1490',
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
    'HiPc0_BxMWojlg7XgkLSNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1497',
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
    'ycuADjbTqfpDhfAwOIDLkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1500',
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
    'OlWNV6rwyr-6IQTtbXOJkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1503',
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
    'uYJHALP0wLhAWRvl2VWMOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentIf:1506',
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
    '_XH1l_hQzbGewRjn3PXU-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1519',
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
    '831rNDiQ-f2XQgPWvoiSGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1522',
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
    'HJ05VwJljzZq3wZnV32iIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1529',
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
    'mrApHUfT6P9VsTPhdDSrog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1532',
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
    'Az4GGvCsFlwZtW75JLQrsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1535',
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
    'CK1nIksQg9QFtp0arV9DBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentUnless:1538',
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
    'kENZwQmEfJsu4GSUPl5Amg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1547',
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
    'trCx0YA8vWD7U4BOzPWNgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1550',
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
    'yJjptPt1z7RGooeJeafgCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1553',
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
    'dH-1DodPwGo59wYd3xr3UQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1560',
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
    's_VZYCb3uY3TYfeM0IIAwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1563',
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
    'uZpvxuI8jkgjjyFhpjBYDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWith:1566',
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
    'GvPnwkqBtfIxOVdW40HkJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1579',
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
    'xyQCqz63AzU9wT7AklSEfw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1582',
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
    'vGf4b5KQoc8TViFNzj_tmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1585',
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
    '2p___66yRU4NPllSW9aBeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1592',
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
    'M0sUKxt1VTd-us07gkvj8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1595',
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
    'IzMU8KbZYGsZNLXBZv43zQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidatePresentWithAll:1598',
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
    'NgO5YRSRYaNtngB0SmwaBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1615',
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
    'hAUq25b_5eiRJtSvDMN9ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1623',
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
    'QzpEdx9dyjgpccfhL9vOQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1628',
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
    'lKc8Sj__fULETE3jdxU5cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequired:1631',
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
    'xSTOGRwLy-dBpLUfa09ggQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1644',
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
    '3hS7aRh8tkvd1YZXkcx-8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1647',
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
    'a7FTKSzgu64Uq0XIpy75Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1650',
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
    'Qc_JuLzZhSP-FV9kBy8Gmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1654',
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
    '3_AuT9KaZUd2tljp0ZM0EQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWith:1659',
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
    'yhi3dbvER7eNPcbui11aEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithAll:1671',
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
    'gIqPemOcHClOHzYErSRcyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1681',
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
    'uF2VRYzCMYhE_AC-m-iKNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1684',
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
    'lgGN-e4C5MLaVYJ19e83lQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1693',
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
    '0XWvDFM7bNURT-7LoFGloA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1696',
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
    'Ui0mmmgzqy6Hk-NW8oMRnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1708',
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
    'elhY1DyQc-x6-snQYgfMYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1713',
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
    'UIGWTsO7MUg7BgBToNTdIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1718',
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
    'Fe8nSIzgKE0sikEQbOq03A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredWithout:1723',
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
    'o26WUonh7vm1UHMPvpnZ6Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1754',
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
    'rbCwHsI6Qa3JExykWZUWGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1757',
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
    'pXLwE0LrgntB0vqyHlDB9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1760',
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
    'HjyjUcMmWhmhYdeD_vOUnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutMultiple:1763',
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
    'iQVpGL7G_M1VB3L18aQbng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1780',
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
    'C7XsYGSBCJR-9h9_zT4Nvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1783',
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
    'BAOu5SC5Rf2HUpw4B_QhIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1786',
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
    'DtOBKmDvL2jYXWxsDiyrVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1789',
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
    'iHZIgMLvOsBkOeaUJjUreQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1792',
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
    '3j6QSW-aL57LUrou5xiLww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1795',
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
    'uac8s1QKJ7a1qCF58j68Ng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredWithoutAll:1798',
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
    '4zgVPLIM9wehG1l08rNWsQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1809',
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
    'H6ozr6mDOptRU4WaIODT3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1813',
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
    'g2Ui07Pw58lvjYhh1EJcWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1817',
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
    'iDTg7PrMqUloGy0pXU5tVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1821',
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
    'NBIbZ81M6ik39ttJ96MfuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1825',
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
    'skF_AcQQgZxmgZB2rz1hOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1874',
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
    'UODpgOzl2PvLUjPGcrKZyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1881',
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
    'WcbsxYJm5gHDq1wY4p90Uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1888',
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
    'XKQGBCXp6Y8vUPn8wZS9yw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredIf:1906',
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
    'nq3LuxbcIhTPS7W-7lmY1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1940',
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
    'sT2v3z7t9D1Ll7EXpQ9egw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1944',
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
    'LEb3bdKdyI5tV5_PY4ewMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1948',
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
    'B9ac7hnxxSVtNUMqktlOqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1952',
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
    'F0AhhbVCKs85KVOsZoCN0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1956',
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
    '-_9pQ078PqW_Ni5vPhQZSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1964',
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
    'hCrRXM2tlVdncyICFDi-Jg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1972',
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
    'cxmIME5Z-PzYEeHANOAJqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1980',
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
    'qXDuWrPZZLexdwViCPw2tw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1984',
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
    'DxqYmG7FUWX9a8Ni8uqw8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1988',
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
    'bFFpzw1Y85DXaVhgR71o2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testRequiredUnless:1992',
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
    'su5yCVUeKVD9aq8KmcEoaw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:2007',
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
    'syP6Rlxne7rYKSlC-9uT1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:2010',
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
    'ZJZ5-_Y0Uu4m9OqPw1DZXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibited:2017',
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
    'fybh6c5H5ZH6VEKYUm-lIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:2040',
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
    'DNheJCZvUzpJrPG9f-XKuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:2048',
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
    'N1d9XVaeUPxHfN7BxFpebg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedIf:2052',
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
    'zc__pmHTgW3UTsP_iLleYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:2074',
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
    '9sCBPLAzNU3_DknSxMbYuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:2082',
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
    '7MoL_heF2G6_uEED5o31Tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedAcceptedIf:2090',
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
    'appuwTStyKQcCuXRttB5lA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:2108',
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
    'vIxfrVMi2VLwTsbknMcPcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:2116',
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
    '0cF7B9vkhcIw89ZhV9ZbTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateProhibitedDeclinedIf:2124',
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
    'ftz0h5dGQm7UBowWMRdSqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:2142',
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
    'mZCI_3VK2bdKNwbmvFxsXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:2146',
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
    '2LLgzOWJMiJvz8PXFBYuEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:2150',
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
    'SxaZeO_ooLd2uR32zAtb7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:2154',
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
    'yaLynmsmw1wdQg897I5JlQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedUnless:2158',
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
    '_Glmmj-Jy-Z1UkDhN6Ctcg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2180',
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
    'rIklBoPdvpjfsPDJ-7-9Kw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2184',
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
    'OUFhs7Z4ZWbvAX2al-58aA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2188',
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
    'jQr8H9JOgV7R4PeEMOwexA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2200',
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
    'yqinsF9Rt-5TDVJJmJ3a4A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibits:2204',
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
    'QJiJLysIqeRdkP0DF6v2KQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'NeTTPFwPM0cO5mlyARTgXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'DseeuhkHFntv_snt2BnQqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'hG4UvN5J6iJrel7ik_5Khw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'xogRGX3hvgvO4LlVaWo1-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'ibeivfWUfn3RgJ1kDVDopA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'fpYLucVH-5UmP3AwLTib1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'CvMs2tBfJo6CsUY_6xFcJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'leGBhNV2q-OnZXx82zBQgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'TUpR54YOZN4JEKtcY2IbJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'RjtURBxFtQnf4G_GcAtGPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'CuOUrN0P1X6u8osBYoHgrQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    '9hL1oTw8NPwD_jaFap8TIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'DmgMqDJ6AotJ115B3JGQHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'Ffd0ZLswzCtUxKk9IWLKvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'QvxyEp8AU7UURZifb0vQLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'skPF1sFWo2uR06BWGYbuPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    '2pq3ik_GsQe77eI-_qQE3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'HAzhfyasYR_SXwacZC7dgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'FRPMR4y6rMFzHunWmC5kMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'Q9x9iQ0Q9wdmevdQAFITvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'oAmX6QAG9bsrM0Q16d9z4g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'yzwjcoSWV-EPSt1XDNwnDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    '0zR7Fo4IjiMuC8q5dU2rhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'cW-ovUfU_ibocgnCp5GpvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testProhibitedRulesAreConsistent:2229',
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
    'M_Sb1XfFADMAc1Wlp91ALw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:2343',
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
    'rhhltrpst9OrOL2xFi0kAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInArray:2351',
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
    '5fJ5jiCRQaGmE51s30QyCQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2362',
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
    'wF0k9HE-Amz46yezCGDgTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2364',
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
    '3YQbV5arhbwGgYQFmRe9Vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2366',
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
    'B0AUd2-Gusq19T0ZDgRAgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2368',
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
    'QvIR8xDqrFSTdOJoEGZFvw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2370',
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
    'Pr54dKQrgZvGptYyD_NEVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateHexColor:2372',
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
    'MJ3gHLw8u4Sl3s5sz0YyAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:2399',
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
    'dDMdB5Da5xfvpGg3gGSZZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateConfirmed:2405',
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
    'MrLljClMfOYBbHYL6TTPhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:2421',
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
    '7m0Cx1Ex4tbu17Bx3UaJJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSame:2427',
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
    '5ExSIvZtMp6YeQSkvprRRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2434',
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
    'S3cIjZrH6i4GSZvUZsAlaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2437',
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
    'm37NFCRqrvztPG67jXi3cg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2440',
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
    'eHt5I158ECN6NvYKTAO--A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2446',
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
    '8D9SKKsLsSQL4BC1Cxif1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2449',
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
    'vDz1LsmctLkheHLlkU51GA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDifferent:2452',
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
    'xDYtipiiR8hCQ0PwYoVrZw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2462',
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
    'vMWcXwE3Y8lQCvlFDK42UQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2468',
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
    'Hc5kAx-sr7_Estk_jAMEYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2471',
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
    'RQNV_PntOaHyGF8slRM3ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2477',
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
    'tm2anAP2Fa5qr1N_YPIArw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2483',
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
    '31IsQJYoCOq5dwllY2JDXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2486',
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
    'Wi8R73LxMkmj9tCpfZOKEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThan:2499',
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
    'RimbO6u8dszZi8-ffoUVrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThan:2584',
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
    'KfSyDNLO2fNyLgfiJXbsNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2601',
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
    '1Ugk_3FMA4DfK28Y0Nislw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2607',
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
    'lIHcjjmTDHK-vP9QzMtsWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2610',
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
    'rHOpdWRPE8tb9nxWZx2jXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2616',
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
    'EgGY4YVSD5jx8UgwAXUKuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2619',
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
    'Ft7Dg5E5RkjnzTBhh-QLog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2622',
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
    'y9beJnDhCTGl3ZPCFdHtsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testGreaterThanOrEqual:2635',
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
    'skiAZZmrOtxflSKZJUxkfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2642',
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
    '2x9AmGroSXH4i3gg5MXcrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2648',
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
    'M7lNOE0mc5DxaoN53g-A0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2651',
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
    'ovmUdws3feaPBqr1Svws2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testLessThanOrEqual:2666',
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
    'KpDjVgm0c0risVImJRkRgw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2707',
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
    '7iS0WLW4VroPJrBGtJeEpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2710',
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
    'sjxTgGvw5G6WnlPzRh7yoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2713',
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
    '-8Fq-XF2e2YkhkIdYL40XQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2716',
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
    'DitUWKA9ALMAq8hEW50pzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2719',
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
    'yLRMI5onfrToJ-qha1soLQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAccepted:2722',
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
    'eIX1nEo95hPswySHFHTr1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2729',
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
    '10C5Iu5mjaOrFE22cRy81A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2732',
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
    'ppEsmgVIGs3VB_G7Fw2uvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredAcceptedIf:2735',
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
    'RMhobhbTpqALGo6UaWaU9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2766',
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
    '4s-ulAXcFyLR_HrvVcMIxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2769',
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
    'iEb_fplMtTDRrXHCLCdzrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2772',
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
    'q4h2c81xo7MVydWWF_8uCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2775',
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
    'mygXv8TPy_Y9O-P-xEa0ew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2778',
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
    'l0Q3gZF0kmxDZx_cVom__w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAcceptedIf:2781',
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
    '3D1pRKoUZ2zpNdNcrtpkSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2819',
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
    'UpB8PC4TCuvMYe0DMOxhzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2822',
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
    'tb-tzOgwCcrzTUSep6Mr1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRequiredIfDeclined:2825',
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
    'WUPKryLIJsTDhlfXDATvXA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2859',
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
    'm-2opaMQW52sUJKooOvS9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2862',
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
    'E2zxOTRe7M9Z2_3n5aE4UQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2865',
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
    'oOmhay8EyaV87zaNdJQ1JQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2868',
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
    'svsReUcps-BOfW3EZC8xKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2871',
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
    'xIOqVsCv4TmXUg28uEnQ6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclined:2874',
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
    'EbZqTuNEpMM-h2lGpIl94A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissing:2913',
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
    'DC-Z-tbyKjgD-gMMLeA-TA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingIf:2952',
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
    'YcuZrXUlpMXPGrc0Z1Xaog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingUnless:2995',
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
    'nHNzwiuGz8j2QwOgTcoUQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWith:3008',
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
    'GOfhfE-GqTHNmczbpt-dvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWith:3041',
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
    'sLPzEQ4TA00Hri_tMUJpKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWithAll:3054',
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
    'PuWxHKyJC8al80IRPrqqdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMissingWithAll:3087',
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
    'kb2o5YLSTpBfZwX_LFU5Hw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3119',
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
    '4SXoRfAEz_RHGqG9VT-G8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3122',
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
    '1fwJJJ2K9FRxdCcNr10aMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3125',
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
    'd-Xy_foTlDnM_nbtZIanzA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3128',
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
    'dzC_Y8oqsgQ8rsN-SykaPg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3131',
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
    'uCxx60_iNq04NsPZqaBQTg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDeclinedIf:3134',
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
    'CPUCCR38gLTNlJKBYOW5Yg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:3176',
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
    '9WWgy7s77Ak-U7woBqQY_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEndsWith:3180',
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
    'InawmVR3W25q1qtaL9R1Tw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntEndWith:3199',
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
    'VfWZk2xR855m676IG62VPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:3210',
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
    'Vu6ozMSAx67ED3MOVXV_Mw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateStartsWith:3218',
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
    'PqyROV5kKhvxB-m-9ldlBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDoesntStartWith:3237',
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
    'W3Fl0g78BSSwp6yQ8sH1sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateString:3248',
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
    'H090Sy36CiqHvJbTGMp1Lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:3263',
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
    '6J7JZOyJed3WDSOF_wiJwA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateJson:3267',
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
    'qUSFNgw-TwIEcvasdE93rA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3298',
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
    'JxoUCfRe9_R2_BhtbaQcMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3301',
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
    '1NAh_xt_Fy6rJ3YWpqzaSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3304',
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
    'd4I6iqZUDwcK-Z0-F6i4nQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3307',
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
    'E6HPcwGpomjRrs2gqipaOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3310',
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
    'KoxEelLjb6O1gqol1Fio1g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3313',
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
    'QaPkqDY7eMnD68a3E99-XA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolean:3316',
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
    'vBVaPmjhUdl6SBXwhGELdg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBooleanStrict:3324',
        'data' => [
            'foo' => false
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'Boolean:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean:strict'
            ]
        ]
    ],
    'fyyKrTYRwTibAAvgtBIFTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBooleanStrict:3327',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'Boolean:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean:strict'
            ]
        ]
    ],
    'bQhG_eYcGC8sfS2is4A1jA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBooleanStrict:3342',
        'data' => [],
        'validated' => [],
        'rules' => [
            'foo' => 'Boolean:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Boolean:strict'
            ]
        ]
    ],
    '83fuS4q39w4HnHsmDIufOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3373',
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
    '393VthrNkZ3Qgq4xD3fcZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3376',
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
    'pe_NAsfa0ywRQGCmXxQe3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3379',
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
    'Pqv1XWrF8L3ml49uXHolbw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3382',
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
    'LMlxcEY4aTWeZ4nCtscJOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3385',
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
    'Ll2iLXG00rMs6-EcBHyZWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3388',
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
    'uWOcslZoEameIVn82vxwEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBool:3391',
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
    'NAT0MbYrt4ula2HCCyVi2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolStrict:3399',
        'data' => [
            'foo' => false
        ],
        'validated' => [
            'foo' => false
        ],
        'rules' => [
            'foo' => 'Bool:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool:strict'
            ]
        ]
    ],
    'hv2wINVo1MTyfvy6Hg2hyw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolStrict:3402',
        'data' => [
            'foo' => true
        ],
        'validated' => [
            'foo' => true
        ],
        'rules' => [
            'foo' => 'Bool:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool:strict'
            ]
        ]
    ],
    'GIx3N-9WVIvQb5LTTMQn9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBoolStrict:3417',
        'data' => [],
        'validated' => [],
        'rules' => [
            'foo' => 'Bool:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Bool:strict'
            ]
        ]
    ],
    'TbUI4kLcpnm2TbpXlAIBKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3439',
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
    '0P9Z_nZwJ-ENecYiX7QF8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3442',
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
    'TydcOhoXS0gE5IVLeT0n8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumeric:3445',
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
    'ntZo3BQKVDN46g1iYdddsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumericStrict:3464',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'Numeric:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric:strict'
            ]
        ]
    ],
    'pJIo2Ou29YLjqTeuteta-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNumericStrict:3467',
        'data' => [
            'foo' => 0.1
        ],
        'validated' => [
            'foo' => 0.1
        ],
        'rules' => [
            'foo' => 'Numeric:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Numeric:strict'
            ]
        ]
    ],
    'OIFM4GDg4Lh7LDLgOX4ulA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:3480',
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
    'CkTHd5h6uwH7DVJo8DqmBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInteger:3483',
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
    'yKoeYrb8aHpQeb33GEtlrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIntegerStrict:3505',
        'data' => [
            'foo' => 1
        ],
        'validated' => [
            'foo' => 1
        ],
        'rules' => [
            'foo' => 'Integer:strict'
        ],
        'expandedRules' => [
            'foo' => [
                'Integer:strict'
            ]
        ]
    ],
    'PmN1VlHfDmVXzyecpKDphg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3518',
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
    'QeQrgDXkVZz9o2qvk5IbiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3521',
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
    'JdRs8TrsC67PzcedaWDGZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3524',
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
    'rjgg8fN2igLLFRdo7fuCiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3527',
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
    'TMWTcshR0yggffuae8nIKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3533',
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
    '8O4cyi6g0Qh-nlt1ODsMiQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3536',
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
    'bdhGxbtJcXmvm4VIHjR-Wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3545',
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
    'P0E-tGq_McgeFi5JrH9L3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3548',
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
    'VQcIIlu2qBKORnmlbc7JBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3551',
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
    'vFqVHeA7NRvvu-R3Ngdm9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3557',
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
    '3OvGjSoFexiDfGiopBo39Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3560',
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
    '45msUQUvfYNMbxA_CavaPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3566',
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
    'rgkXYguFvn5FanUTwQ1R1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3569',
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
    'ghTEA_YD8Xl9jgfRp_t1gQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3581',
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
    '8evcQ_kjT8Jk6y-4Zl1pLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3587',
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
    'Lg4qsYPPOaGwkIUGGPZMpg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3593',
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
    'bUkkeSlZLwW65yD1JTM6tw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3599',
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
    'CUa7_UgdhDn5hinCc-3vNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3605',
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
    'moxtOVtm9JMi5eiuzToiSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3611',
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
    '398DRZsf4iUDcrCBooMY0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3617',
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
    'VaZ0HKmbRuIJfMbiN1QfMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3623',
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
    'ITp1ToX0UpYNwYbMMNjjLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3629',
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
    'i1Hp69m5ebK_dx8os2HHvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3635',
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
    'F1jMqV6LTbFfOZkhwxUAtQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3641',
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
    'lA7bmpkovMSRzjDPHHwqRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3671',
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
    'nKMoKb3LUPh2KyStHEGrsw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3673',
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
    'N7P0ZsXhHzZKoJAJpljNfA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3675',
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
    '0timA9kHc488gzwAl6c1cA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3677',
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
    'Q71BCeyCdV0tuz0HPUkZJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3679',
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
    'ygcziBAsNfmwqpS0iEPm2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3681',
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
    'oRg7nWaJnt8Ut71ISh1vSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3683',
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
    'ytZMfRFn68APhqn4qoM4_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDecimal:3685',
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
    'bVBEoODLWggw6Ae7l4ldBw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:3698',
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
    '9cNUm-U-Jl63DpvYnYwb5w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateInt:3701',
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
    '_o5BMtSu646A7RdHBTGkEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3708',
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
    'qQZo6JNFi-0x4gPx3P2gmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3721',
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
    'fdHh00_eV_Pu78PPhLT72w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3734',
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
    'cUSj0PQI_4lENK8VTrWU-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDigits:3747',
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
    'nGMRr9iogVSmEmWY9uJqMA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3766',
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
    'VNClCkVEbmC-VBgINIQ7rQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3772',
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
    'INKzTUQz4RRJs05FhVUxDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3778',
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
    'U8yWBmhRs6w1SljybmOIHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3784',
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
    'd1SpP17pWJC9NaCJFqNz_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateSize:3787',
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
    'Y6WKUFuOnKuox8gbmT9jYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3810',
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
    'IaRu7XScdmh2eu1ldcrtjA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3813',
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
    'DefkpxlBnjJhYvhX80mczA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3816',
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
    'PnaHmyMG52hwSAhCUv32Wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3823',
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
    'cE_fQvBsG1etHAwCY3AG_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3827',
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
    'bQOcRXETQzvzJC9dlSh5kg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3831',
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
    '5hpMD_kHeQVRKMyiH1amCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3834',
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
    'H5JFVzNCi01Oxk9Ws-TKCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3840',
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
    'C0veI63WiD6A5oloZtl6Vg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateBetween:3843',
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
    'zNREy0onU8HsCOPTGaccxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3867',
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
    'WVstF9z-dE479VlodVZUGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3870',
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
    'aCbjTM7fXxuhJxqRO40nbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3881',
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
    'QWlPzolNlG29-csAzDn84g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3889',
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
    '6ovgbbLTDLnRsZbnx-9l7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3899',
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
    'OpzXnUPJ1dsFVod6XVwvLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMin:3902',
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
    'x4Dk-FiHPsZZNZWYBI_AWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3925',
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
    'cYJxPkueKPxzE2rqhg7_qQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3932',
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
    '_Ax97cF_Lsa86IVvtU_IFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3936',
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
    '81QVDwRfrE52WwYa-O7BPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3947',
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
    'EAQm6wN03LuS6mk__AVeEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3951',
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
    'x9lGRbAW188l8QSg3--NNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3954',
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
    'xkwwcYWnlh_82vQHHPMNAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMax:3957',
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
    'wj721CXL6c82sFPpk7wKRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'H7DuKKyYXeyIid1A7YDNhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '1lD7iOEzlLIA3Yndckxeng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'rYq5CZbK3gqBiG_bljLvWw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '-wIECDgTZ-fwoxxLyJVcwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'p6TOwW5Rt2jV-ESjWh1Mhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'SP5sE1yS8fIaPMcHgDL1hg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '1GxOFjNnj0v22Eu7UsB2sQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'KbqTyPWe7tAczEXVCnkbTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '0fLy8_m6U1JfIbCOmbfb5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '4w5iIeSq82ehE_ZOVGzykQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'KGv_r_0nifH4npp-yQXvAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'pmI2WO3q4Uotv_DRBxW4Cw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'lrtsEdqdMBfyTPKqS5vtyQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'eEdZHRq9zVHegMKPr4hTVw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '4Y5VoAYLtmCH1RjH-9-e2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'PNiQLoKY8NZx8Z6_j-I2Wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'KTRO0kbX4N4-lamT4yLIKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'ojdctLXdKZ5QdQYRpGbGMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'SDPT87VlmiwId1v8a98_Nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'ol81IQGIM7VMQHEZTw79Ww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'xTAk3ENdaMTdclfXdh1V_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'qBgokf3rbpCsQDen0xCofQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'DXMO42Qg775A5pzFujudCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '6VRpZLkogkiWIy0LF4wO9Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '94GmiyOpZBmFTJ2hMhLQug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'jGXlMShaCdlGLeAstIz3gg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'AwtTCXnTeCuiTQXYbDkQsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'hLZdZSzGKFIEJPln6JojDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'UzlAALFKEQLRMjphNvZ82g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '0i91OusMfjBubV4aQJWv-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'acC4CKtB3O3snmn9_OnuxA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    's-wv_f_02aRK-voJH711NQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'EFd4ElpA5SPQ07-LtvkeQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'APN-AnMu5MWph3JLmsSAFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'y-2p7aAlqc4C_ioBHoHIjw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'UXfdEO5YhGf7wxeTKOWQNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'AO0B7j4s2ciTWLiQXJ0j7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'IxgaWeDaIGEUZ8Dj0pf_ng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'MbsUa72HNEFrsU22Dgy7GQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'U8YsztQpFWUq9nmLr8KE3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'tt0aCuPOUrxxns__ruaqEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'vdg_zrgbxGa0jDhcovG0VA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'uXTSGYvgkE1eHPZU0cDeWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'hLwwxL4I88lpPv36e_k6zw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'IkNdMhocOFNMnVGNk8fTww' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'JuomkwU-zEF1o_vyGmujVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '4w21pJuUbC2MNzKYGXeCMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'l690n_2UYwlYa6_37Y2yFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'c2VSzxQM2MoCios4_9JwUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    '54P1uNgnW7C_Zu1vlsf9qQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'fsTCSn8NeC1_jpIVHiBB8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'KHujZk1qE7L-osLvf44-3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'PvFI4Qv7Z9KBK9qczFkWqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'Cg32PcFTN5yTjiz51YvsUA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'fTr3-lb4q7aoYya45QuzqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'hEOlfIREFOfuscLyilmAMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '7asGp9as5cMdonJmYF4Vfg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'p8y8vEa2oBdmF-7Ev_yLFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    '67k9VEdC5qftZlEZXoLq4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'S0Aqrf2i2Iv-nA6mRv0EEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3993',
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
    'EjI51ry12_3Tlp9y3zFM4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMultipleOf:3994',
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
    'bXThz4gQ2fEXGuxZo9PDAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateContains:4275',
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
    '-W842HVE7rl5v_wn4Bej7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateContains:4278',
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
    '8GWsVEcpB56b9McOneZghA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:4292',
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
    '93H90YB0GrWIOx0F9xn39A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:4298',
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
    '14xqSAbUDjp6-GQrVLuZ2A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:4301',
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
    'fzuAoZe0xKRF1TgiQKn_BA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:4304',
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
    'hh7EwbSvCd5Djls2RBt87g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIn:4307',
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
    'hB6vCmne_fBOhciatOwzvA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotIn:4320',
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
    'a4gtr7h4ZZbgRJj4fFHosA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4343',
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
    'zMQGFVMWm0r_FbxjEVsytQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4346',
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
    'elnxKZwhsbrXSUeK45xUnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4352',
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
    'gqToptZYFM5up97buiGbHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4358',
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
    'wH6r_rTQeDYeMblAHP8G5Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4361',
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
    'NXpn5BAZ9uZ7QnKglC90Aw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4364',
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
    'BUGil7C_uIRlrBS_q7nTjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4370',
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
    'sM7z8OwZEfnZxv6vtdSNIw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4376',
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
    'afmb10lOx7exkvKnvCTtAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4379',
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
    'F4GTS-NKNupvl4UZI1Lcsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4393',
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
    'BbbyB5eV9Oms4JdD3DJdYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDistinct:4408',
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
    'MTWEfSO35DuoOjmbMsr56Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:4442',
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
    '8okqoGWh-f7xsMKHdOm-rg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUnique:4449',
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
    'zjzEkGuP2gzQPE4NCB8xXQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUniqueAndExistsSendsCorrectFieldNameToDBWithArrays:4493',
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
    'uRa9GeJoCDOIGqbzA16xnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4519',
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
    'shNPktK3Zraf64EDgNu1uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4527',
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
    'PIfE28ZJm9Du47DQP_-nkg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4548',
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
    'qB1RsMl5zFkmqWhe1liTGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExists:4555',
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
    'CIZ2AJ81oY2x6Wzw3teiiQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidationExistsIsNotCalledUnnecessarily:4573',
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
    '-QNr-j_aL6x72hxmXDqrQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4743',
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
    'syUTwHR_et_9rsC1Lf_rQg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4746',
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
    '2dazYGU4t1OmKWsn66KhKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateIp:4749',
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
    'mS2wr8qqWohok88xmmLaoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4766',
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
    'wQwYKRylvBAeSFxpZXT8_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4770',
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
    'ztJ5eQe9b0tqrtEGhSkj7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4774',
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
    'InGv_EY8GdAZjOGBqyv5XQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4778',
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
    '0KIyKWF-ihizYDR8bngElw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4782',
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
    'sQ409c3gKVoJWJEEVDL4Nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4786',
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
    'ZBa_esQRZy8spFwIOxDB8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateMacAddress:4798',
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
    'vDSiSZMkKso2npRRacYeKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmail:4833',
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
    'CKbSBJKuNLVRjAYS0TTsRA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithInternationalCharacters:4842',
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
    'vi0fEcaheEMCJvrHiEr7sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterCheck:4857',
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
    'QhORTpbLkksjnJBNWtF5BQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:4873',
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
    'n9WFlejS7ZXDpD91c8b1MA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateEmailWithFilterUnicodeCheck:4877',
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
    'b-aiGGw4Iyc_7bG4h_rrtA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithProtocols:4900',
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
    'aJNjVmZ_PFgwwk_IQoibpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithProtocols:4907',
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
    '3__tnkbeBUkW3EuriVoMJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'fr0SsUgobgoBZ17fTjOaeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '5jxYdxMd_5V-yCyGhk7WpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Q7JA64mgGNVppFTxtbbb8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'IdeWrNu8zhkREdsHgtz8Ag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'LJMyglDoUgaijViwykqWTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Fd2w-1R1CfCndwbB0t1SfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'SpunxNzWRu26BktJoqQ2uw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'TWI9MmrbSYt5GSfiBRvzZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'zue-a65lCFwE0FnFPY07ug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'jPVCI6tL-w-irxrWdslI3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'SPhMRoCP5w5WQ4jqr2e0Mw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ydc-Cm8xEuwCPfnkr0os2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '38mBUopwBXgItous1ow9PA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'dzKEcYHZQKfreW6xsUedbQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'upiA2IspCJssiZjGYK_Cag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'vfbPLmJCDSZFxTOV1KyDCA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'WGEYIzzMLeAvMneM64IXNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'xzgP5LPSgCic2RZ0Sjl5RA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'LfRuYEWTbtZIbs-jUPFn9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Mxz_8lDKuUpSQi_7hCppiw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'swBGRLMAU4oBYuKpgYEwpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '-TJk3zvfeEVsJYPAyA3FTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'vc5PIsdRT0J9In3i0XkQEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Rsh9ldE2vUTnqD_yI1talg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'SZ0xXNTmswOy5fMqoecNdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'vV4aFnhvC9TcN32D3Wyo7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Awq5zhHFyjsuvr8nwB_SiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '4ithsBIVVIykoehJvsB60A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'hrh7JjilYIPWENGoRrMmJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'x7LXjr9ZNbPRFHvJKbt5CA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Uk4K441PmacnJfkWmxTKNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '4_WG1TpZfrc3_xxUuIcpIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Xi6OwxMkOhZT4T8dwhmDpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'b8eO1gYSuAgqq645wmB84w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'iNWNJrhocU6colgvDb5ybw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'GQzJPuLi4073YHS7G-Qb8g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Sa_6PaoaGz4anK11NS9ULw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'SCCJq-WZlt1cVB5lnE7Bwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pqF1rycr978H-lCIB2UGAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'XrKXFCvHvXpHfZkY1lkI-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'sY6zzYmDUNXxi2zh1IgnfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'xnBb5rNXbAyL5eGA8tQ1zg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Xsq5k0D73QTEbwgbJok8Nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'rigfdqwXNSZRH09HA6LoKw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Hm5tGcZzt24yPHZkkp-scQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '3CCUvXRN-bLIfmyOEd9olQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'rlUTMEgvF8thNnNh0rMYeQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'PF-0-3-CHEreyt50iMf5aw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'eHM5N1ESYaIXpEylfXi1iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'veXXIijZM9ESHVMSgfz9wg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ac1xERXWWsYUvZPTT8kH0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '1RAxEHJD4ihQ5wL-oJXM0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'OUlAehokx6zqh4CWRAvTAA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pjRiO4o5DTEGQ45iHL14-w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ESaam5lbIE0K0VP9c0-wow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_GBw__V2RPEUU8BdNqRk5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'zHmsZQRdZ7RhV5ZsDAsm8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Hyv5EiO3y2rlH_BzSlvKog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'XyQaUqXCNf1dSSTN3bXf3Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'VTGB5PsOo1GSJAw5NamBMQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Lal5mdIF1U-O7mazNRejoA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'M2LgJTa7wTKIWckJM_nezA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'iHK3FVW7aXzIoJGzTIEwDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '6JmdLhv27_bCG8DwKWJgzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '448HlTDoK37loBFcsW-FDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'wK-bzT5MnJz6WIrQL2D2Iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '1krj2R83FwT8r8tA9Bdz5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'XfcPGi1zCPRZP7WOTm2Aew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Oha5B1TvPa9j2CaOhYB2MA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'QKDFotBTBlKzUJW5ONRiuw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '1JnuymZI10wbrTFDTlJGYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'KnALwUDzDJUvYEj6inDQGQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '1OXAcuMWKn5Oc290NaP9kA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '3WOid47c5MOlrNSyseop-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'di3Zd0uEVWc7GGJtjjns-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'CiUltRI_1jVpopV3imJt5g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'oARMmXliqUb9n3eSwFILyA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'wUJutrPFNGCLxyHLC95ebQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'm5mHWhLw7Wm66pUXGPUzQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'LdcoYvoblMbmiEDbW8NLew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '-xLhPi0vn9LKJmv6DDYyJA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '-tR7Z-AEElMvaKtlowrt2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Uz2CVv4fO-CxgQpksl0eaA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ZykuTfFPB2qDnObNM6jQiQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'uAWtUSo5DbXwFwXwcfqDNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'umVq0KrfB6inwVxhPgOG1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Nzk0pjpk7YbIO6ZwqS4fhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'nBeUX7QQDejSkEoXJ6Qiiw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '5YloZBvA8G7RlnIK_1Rffw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '14OSuumJ25QKlbH8NzksmQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'QpjF5DwAecjbFwlXNcKMSQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'bajF9V6Dabf6naR_sF08rg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'SBZu2nhx3z2GhOMhDf6QDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'QMD2ANMRhOFHPAhFFKEtWA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '5ybpJZf5_YYG-U3iPGBCNA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_zOnzwhkdDofogp2axdIHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'wXPsWlc6TnL4UeW2e0lsLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'JzhyKctqnYn52EuymWi0BQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'dzAS8bSP_SPtoSV7hnEjwg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'm460F98U0uSvmX0I89_5iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'KgxrQeR8ZxMdN83wmR6wzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Uk1bpjwUf6u5MN7QpFg_tQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'IRig5LiluiroGEfA_mFR9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '955aPQ7zma4bCo7uKz_7tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '0u6vqSg2IJz8_WV-Q3wRtw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'gj3iA0RPQcx0G8BpY33kLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'DROmqTdDfd37lizhY_TCgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'PZil2q-RAayBEqb8TT_brg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'IjRTwwUQNM7CAQSxDuvMRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'wc1kZIIHVyj1xSeCKDvlZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Qv8BTypdbsVriYwjl7jlZQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'qsBLPhMnhoOPrxL-9zo0ng' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_pf6m6Xj0M0aY69Mjcn0PQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'nkEQ77mEBWYEHdxJKKYb8A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'RLasd0b9eeqE6Tkfq8OyKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '-SjGKHJid9nHF5C-9vX52Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'UZLJvDjoFr7GKaAABnuLVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pkX-4dOez9ii3yff7sZDYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'zEmfxZY2HJX3MYeyuZfPAQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'DRzCLZw8NJST-0kQiNV5jQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ViFdvpdb00ouEbXIyXFf9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Zn603IUxHTpnkqHEdMM7HA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_fby8mNVwop1sZZRU83PEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'f7rzVKm1KdKebA2_1VS2UQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'fydTFrOgM9kEtEUz2uhBlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '2WjtcqKUtCHkOkuhgr4XYg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'w-L5D-znc1WTBc8ThODftg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'PEHTGIU5O4_KhSj82hTwbg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Insi0uOANNgWbokrpErPgA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'hVoICGa9k4sgTITbgwTz_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'n6mJHLpKJhOL_l4R-PdxnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'aOyMZQ81QJtvCp_WHKIPEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'M-Z50QvUsYPhvK3AJah-GQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'jM7cS01wFqU1XUOYgluNVw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'vhVvD-Z-Y_n7gMeZyv2NVA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    't1aNE_-zAN86KRC0kpgALw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'iRyOdI1P-EsD9UjRdA7MPw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ftJpWeq5eLQMD5pVH18JqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'OMVlhj_QdfFZyDlPS64C1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'lfqRigKhtI6Zx_ffyQqjGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'xc7GyycDqpjtlOGcETsR6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ciNMGnWWLYwnH4O-ITP2bQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'DmGPQNzuZUmaoHKfm1jXzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'iwAW1akoYZUggE2uvmMY1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '6X15LjfdyFXCK750-G1qew' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '7FzKtRWuiSxzprXYW2iqrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'AtHFysX1TtA1TkA2Aujc-g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'kS-CPLT-t8IOboDsvEsDNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'KY44BzSPHzxwexu-57LK3A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'yeRMmeQ4542056jmNcgSRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'abzo8ErbZ3JwYcZ6nPrZ_w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'JIwwEJPvTQF5MNdoFgFRwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'DMA-MMFiX5TsZva_5WxHFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'F8zF5U_7yhfoCJRpMWdVlA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ah_CXcHzRckYcLt_5Ay_GA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pOOcb91N_Y-ciKcDG82urg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'mRGJFgjgDVa2-qLlbLd5rw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'JyZLtajKNCKVLd4qXtHxmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'VgXuvRYzSp8OqXMJCTxgxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'inxGMJ8KWtzIy0RvysLFDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    's097QFYiIhJCI0DbwhkknQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'QA9XolTlC4_tTgcaVY22HA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'uJdYSyetR-cZ-nMiNvvVnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pYJoQnV4bOKAc8Il4O5NdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'aJzD5_VSLsr4BaO_63stTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'aqR1tx_AwWEcoNsAenrBJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Wfk-HN_5ipHlHTwcPllQjQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Ts8kfqhvXG7MkOLiXp41tg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ktb51GPaUzTrAx5PqCHwAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'mL-gXp_DANh2BeCVD5HqDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '4vFo443Dr7FeGPrdFNg6DA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    't62nKHvGpuvhKH1xngWfCg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '6b7ghBU6pGQ1mKwoG_yk3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '7X5vFyUrUfMrOj_wSmQS3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ALTIx_OUSwWUB4mkuh4R-A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '1Fntn6F-fhZwWlEaB_oDlw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'exNwASUc-MJFEqqfihDIEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '4z655VaYwJJqJ8LZJcAebQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'dsCc919SETTVXRBjFDFdfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'eBss3wwrGHA33i9Bca6q9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '13G1yxRIeyd39AK-lHDHkQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Fbhf3_qeHiVU0h6OvZIv5A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'IylG2u8Q8AyJy2O4eC9v8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'JE4toCEwn8OtWyGZKWGBHQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'anmWQ2IL-NTKAfqh8VxfFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_JyThZjyddUeY_BfmdAJXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'BfmCVBd3TlW-S2uDVnRtSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '2BBXBka0oFzwSZrQWVHomQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'xJECiRS8TAuo9B-N1onYDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'AVLYLXEibhmpcRraLQuPeg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'tq7hoS-TtUrG-5Dv3ATr0A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'PkhDLrHUxJ5bxZodG39ANA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'pZcbwZUu691gU6hY4TjOOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'lqMncUfA7XdNblWsl9uabQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'W8S2LQz7nLCGK3rCyM6VvQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'L7A2fbGsHDiit5uW5aIJBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'U86QfM5v2uo4ej2C3CF5ug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'bw3D7NlYVah2HXpwPzjcEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'yZNtMxcyUeUmLu-4hsAc0w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'fRWuZ6C9oCpBkjEUdaGZbA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'oWkoAlf2pNElGAUlv3KWgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '3wxEbv_YAmtFUvYlpDRAUQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'd0B8BASkJsDxhY2hDHauHw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'mptJNmYfnvS4mxo14jx1Xg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '_ain57mgmphF3YTD3-WnYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ztSgjuxY3K9ERgoz3RxNEw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'OhskVC91_ECHWob8F_vH9w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'MmMeM71HvbNmAMRuPgnNSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'g8t81cnW6CGoXD9hDhc91Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'rWWNSto6GfAf6V88R1i8EA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'WFpPxtnMU0bkl5Vujox2Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'lwWmP2k1blxt6fTxlR1cIQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '-pMjHEb8g82ISj7dPPkEOw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'gbBv6DsUj7i7nhd8ZYCFqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ufdxkSPLVJYTpC1W3pWhHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'iw5S93yRUn-I2pXJhWhQhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '610xM7qB_ralxkKfs8Hufg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'YORW5HXpnbGimErrarqULg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'd2MEV1f-U5Ei8fxV1m7J6w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '3npHGQsGcM3qlflRk1sypw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'uN9vA5uWgRNe0HnX9cVLAg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'I8n7Sx0u-wTtK6nqog9t0Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '2uCfF3Xwbwxt18wz2HfKnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'xSpsRUYoec333bE-zM3kMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '8yhaPR-tuHHB158OmXcZzQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'GeevMHuGDDJLILmi8IbeeA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'ugaVh6EWcaijRiR_wQfv7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '0gluHoyHzysIYDFBKurdBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'vJ0p9uJoRV4nlXMu4nw9QQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'XmKsGAXHLJYef7l4XOdbmw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'rkAN-8kJKTCtbW6dULsDrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'CnVaiTTdLRg3AOYMQb9flg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'eCDeeageaXo4VzX_9OCJtg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'HJj5CCIv51ny0ofh8aoY4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'cQY4NQhIo6muW9WOmD_wWQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '6kA4py4JtZExfpwyav6DIA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Cmu226y6oranhkY51Q44cA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'bkll-OXpvJ6B_R_fFSUrTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    '6NS5iWCM2QVpg00LSyXSGw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'Rb3OrRydpmKs2OtT8QeJ9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateUrlWithValidUrls:4915',
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
    'MSiRtR_BBL0DjugWejrqxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:5205',
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
    'N3IdNU81_HLViRoF9zHbFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:5205',
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
    'Fc-JgSbpEVBD3KyjBXet7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateActiveUrl:5205',
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
    '5cz6BG5LVq1KPJsd66CDoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5329',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'axDO3ZXrw6crnTpbKkAwXw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5335',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'DUoiaFgGvXl9EqrTNvawnQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5341',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'ybBQ4nTSAMdgUz6wi11TLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5347',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'HScOLAIVDTn7ArN4liQlaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5353',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'SZ5T9lr4w7wL5ATKmmHDgQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5356',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'HI_1TaI6cyz7Aace_J-tPQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5359',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    '3cF3jgcCGKUA9UXQUj4Yvg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5362',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'SLiRh-OWc7RlQGH5Nt5JkA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5376',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'LVlyQD-ZGkKFSbbf_p7InA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5384',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'zdBEiGIrsNpbWsAMPYRkLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5399',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'R8fczClJz717_atw3pML8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5406',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg+xml';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'dO1NmbLu0OO90U50DI1bZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5412',
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
    'q8ONwUZ4nzB_DU766LrnFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5419',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'image/svg';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'R7eEiFpiBgTP55CeCbKlWg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5425',
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
    'DMgb2qMfi-L9O3tSnPm2dQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5437',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'EGBk6wecSSmoVWeAwk6Dhg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImageDimensions:5448',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'sTinUxQz9u8BwSAL0wHS2Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateFile:5567',
        'data' => [
            'x' => (static function() {
                $class = new \ReflectionClass(\Symfony\Component\HttpFoundation\File\UploadedFile::class);
                $object = $class->newInstanceWithoutConstructor();

                (function() {
                    $this->originalName = '';
                    $this->mimeType = 'application/octet-stream';
                    $this->error = 0;
                    $this->originalPath = '';
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
                    $this->originalPath = '';
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
    'GWamoXGgb4ekKfnVJRGaTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:5574',
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
    '3D1zcLEA3K3ClCdQYw__wA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testEmptyRulesSkipped:5577',
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
    'pK-wucqboai9Z1ZrtBoOMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testAlternativeFormat:5584',
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
    '4oKrawophsZ_CR6DIgjeEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testNumericKeys:5591',
        'data' => [
            3 => 'aslsdlks'
        ],
        'validated' => [
            3 => 'aslsdlks'
        ],
        'rules' => [
            3 => 'required'
        ],
        'expandedRules' => [
            3 => [
                'required'
            ]
        ]
    ],
    'hHACoDd-tFBsrrZhcgAfzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5614',
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
    'EF59xQS7ruKcjBzOCl24mA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5628',
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
    'cDGbIKynQbYE6Y8R3uTZoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5634',
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
    'ny0sotaAjezggYGoOBad7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlpha:5640',
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
    '3qIW9F4VXtUZs6nuI7Hk0g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5665',
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
    'Uq0FRLZQrhBJ_Tkc_fHxiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5671',
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
    'zujj0VS9tS94SYoGxf6yRw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5674',
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
    'gPHD_BVbymN_w16RK6XUDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNum:5677',
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
    'jLPAHHlFVoN__xgdiOiZNQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5687',
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
    '340l6drnUpOw9Sl_W12xNg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5693',
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
    'PXlYOcXYp2R3X2HiJy6qUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDash:5696',
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
    'TsDGFcp2paMGNoA1ljEdBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaWithAsciiOption:5706',
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
    'n064l8wjdAlruEJlSrdWow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaNumWithAsciiOption:5757',
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
    'nIr7zMfjOVTlIp8nvxSaTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAlphaDashWithAsciiOption:5782',
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
    'EyhzEjQD-T0N5Fjq-dBJLA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5810',
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
    'isV28fdUAurgDumV0bwdDw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5813',
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
    '7lmPmr_VjCszCR-O9KWeow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezone:5816',
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
    '7NCXTwXEOkTzRqpAV8xjpA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAfricaOption:5847',
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
    'DnoNwW_H80uSI0fZ3lkiaQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAmericaOption:5884',
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
    'qs_vA-gPYKXwmiM0gy-6GA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAntarcticaOption:5918',
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
    'RHSUfcoVSXM0F_x8h57J9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithArcticOption:5952',
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
    'xtLYUEcSqhkoQeJOHWlQqg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAsiaOption:5986',
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
    '3pkrblsf8ewIjb6ywk4GNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAtlanticOption:6020',
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
    'ODxlvBeJgF488eVTHQO3Jw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAustraliaOption:6054',
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
    '2DhfL5EQkhjtMu9XDSXw7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithEuropeOption:6088',
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
    'DMRrUA_kk42VR2SpM0_-bA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithIndianOption:6122',
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
    'gZVGK9_zVopf4JZmbw2sxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPacificOption:6156',
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
    'f6jSCt2rejfov2n8mcwqIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithUTCOption:6184',
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
    'PHqjsPDdRaospgWZLD6cSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:6215',
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
    'yMHTgjIJG8nNe8ocXoWSSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:6218',
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
    'ULkQhOjWyO0RVjYdQtQ-DA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:6221',
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
    'k9p6OBYMWiY2L0RIl5tD1w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllOption:6224',
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
    'YYE-96FNCmZ6xTuqu8R97w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6252',
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
    'vs0xbSDXXXmENUe8eHVJ-Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6255',
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
    'FQfrb74fAk1UNC9O9UjvFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6258',
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
    'QPaFGA-TN2PuKwv5vKUoYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6261',
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
    'RcLeCnCWl27PPNPHVzCLzg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6264',
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
    'NEyL_X_gDnst6r2OZvwnXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6270',
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
    'rjkfSoSMwNvjbKDUOnhylA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithAllWithBCOption:6273',
        'data' => [
            'foo' => 'Europe/London'
        ],
        'validated' => [
            'foo' => 'Europe/London'
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
    'kplxo07rUPJRBTpRfq2eZg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:6292',
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
    '_BNlY3qgPQFk_HMtK8qs4w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:6298',
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
    'Hcjv-UzoHk30dwo5g1B48g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateTimezoneWithPerCountryOptionWithoutSpecifyingCountry:6301',
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
    'zn1Bz3u315cWGXo_xpw7qA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:6320',
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
    'X9MBI2eQpAwTg_HH0qXyAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:6327',
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
    'XG0b7y4rua-5jG6En8AsfQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:6330',
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
    'QM0kU7FBBs26F36LmgRNqA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:6333',
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
    'pl4vdL0hrc734LTijjSTuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateRegex:6336',
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
    'TJXR1CQijFEMR4woH9OHtQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:6343',
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
    'zGI51ybzq7pBL1t_MRM_RQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNotRegex:6350',
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
    'DJxooNXEoPpMUEMd-0rjVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6358',
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
    'RoJ8ZOe0FDAB9lvLQPMI2w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6361',
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
    '1CUlg-ZZB_puJrM0fMFhsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6373',
        'data' => [
            'x' => \DateTime::__set_state([
                'date' => '2026-08-03 07:14:46.010554',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTime::__set_state([
                'date' => '2026-08-03 07:14:46.010554',
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
    'wM4xU4jH7xB2PAesl_1FLw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6376',
        'data' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2026-08-03 07:14:46.010679',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ])
        ],
        'validated' => [
            'x' => \DateTimeImmutable::__set_state([
                'date' => '2026-08-03 07:14:46.010679',
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
    'QKZN87W3YRnKWxM9aNC_qw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6379',
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
    'Ac0JachY4iPCiWfYYNgThw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6398',
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
    '1FgP6BdWxWTDYsEahcR7lw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6401',
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
    'VvROWTNr6QRu9aQskQFi6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6404',
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
    'X3SZTxgUxBRHt__rsibnxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6407',
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
    '89_V4qJkcnW_oK_sHVFIog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6410',
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
    '1S2YVbH1_zQx5S4P_XhuwA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6413',
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
    'eYoXeZM68Ty72Cj8BVwgxQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6419',
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
    'ZHLSVfWG97uNAWiBhfU5iw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6422',
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
    '5LG4xDFye-udQwu9mNnyOA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6425',
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
    'QiLdxXV9uUvvWqB_1ao-Jg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateDateAndFormat:6431',
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
    'SvBueaGZCAR5f3K75nEZ8w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6439',
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
    'C_kZ8DvjPXh7fNhjLeIrdQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6442',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '00000000000046060000000000000000',
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
                'constructedObjectId' => '00000000000046060000000000000000',
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
    'BHzDCY6SquWJOXG-KTrRog' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6448',
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
    'slTj-Y4-pir_0sQe8rTCdA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6451',
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
    'QOa-O45SG1vQ-abSUzroNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6460',
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
    'VLrAdut8o_3yww5j4If7vQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6469',
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
    'yZmV3D1jhjoDkAOEElSpFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6478',
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
    'QYwJ7v_7-WuwHIQTKQsaKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEquals:6487',
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
    'TAbjKXN8xTDLOuWdIMeDwQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6503',
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
    'P5DjCnxbLvzzxIPAGNVAVg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6506',
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
    'AXDHwOkNwIEnfK8tfV6ttQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6515',
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
    'mDVu5v1T2agLshPghkVKYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6524',
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
    'Pk8R_uJVFyESgGJDoZt-jA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testDateEqualsRespectsCarbonTestNowWhenParameterIsRelative:6533',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '00000000000018fd0000000000000000',
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
                'constructedObjectId' => '00000000000018fd0000000000000000',
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
    'YK-EV-7hIBFuGcVCH0wXAw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6547',
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
    'UzxZy5xUnMNOrvTa9D2ekg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6553',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '00000000000044280000000000000000',
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
                'constructedObjectId' => '00000000000044280000000000000000',
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
    'qbxMjBuyhaKTSCBbLIsAKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6559',
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
    'Jo-Vfurd68XBaGhFB4F4ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6565',
        'data' => [
            'x' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '0000000000000c980000000000000000',
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
                'constructedObjectId' => '0000000000000c980000000000000000',
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
    'k5-SByZ3ykh1f-yI4bpkow' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6571',
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
    'gncxM8DIG-jWETO7tye5ig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6577',
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
    'fJMQj8iuNCPWEFk6-Q1wgg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6583',
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
    'soe_luUGXvgcm5qd5Cjuqw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6586',
        'data' => [
            'start' => \DateTime::__set_state([
                'date' => '2012-01-01 00:00:00.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC'
            ]),
            'ends' => \Illuminate\Support\Carbon::__set_state([
                'endOfTime' => false,
                'startOfTime' => false,
                'constructedObjectId' => '00000000000009250000000000000000',
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
                'constructedObjectId' => '00000000000009250000000000000000',
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
    '60v8bRGM_xvRJ6UfGrv6Mw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6589',
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
    'ufkLR0GjJA2O-ROwYUTXSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6595',
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
    'BlaFDPE0H_ALY1yCJPJKUw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6598',
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
    'FBXC14K8rF3F5mJ7q8GQhw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6601',
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
    'XbFmU3diLiBRf3Rl-nO47g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6610',
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
    'Qgr4gS-fg99Cl5WNr8bgoQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6613',
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
    'yN02NlEISDKlm3BmxOFAlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfter:6622',
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
    'WRjORvpGfbnzdNb0ejHEZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6639',
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
    'VRJQscZhmfw4d12ePNNhJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6648',
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
    'UK3UCe7tYGht_XjgD9-_aA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6654',
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
    'jT-sDfrvq9pZgqq8zJZgUg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6666',
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
    'ZaHF5luMpo519QM1lZOrKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6681',
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
    'Vp91AQKGI7T8m9q3byTVNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6690',
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
    'YTCnrgxD8Owd-yLCVhyPFw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6699',
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
    'hg7wIejoVu5xcN_f58t7gQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6708',
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
    'u4Ji-uIm7Whv7YHA0zCFcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6717',
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
    'p-YMIGzFGyaUsA5ijy1gDA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6726',
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
    'Ylq52QbebciWj3MjikYXrg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6741',
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
    'KWoNYgRJwjn97y3Dodrx6g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testBeforeAndAfterWithFormat:6744',
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
    'V18w1r9kIIDT_lFTRk9yig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6752',
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
    'vdkfwxKK8FzaGu5aqYoXig' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6755',
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
    'YAf8GjqwUa0zGifE4inx2g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6761',
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
    'KuNm3C4LHqiJ6rr188Trsg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6767',
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
    '4iZo1xBaSYPyNwHJFTFgGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6770',
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
    'rgJ_5Qa0rPFjwaNniF7E7Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6776',
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
    'DJDUzn-NR-G8KNfq6hOiQw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6779',
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
    'Wz-viZKQC2AtR0xLvuF-3g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6785',
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
    'dk5BpBUoqLVobykImDGkhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6791',
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
    '-W9zBLPZRtg08oUmDYwyiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6794',
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
    'RwR1pSao8lidGYx5ia38Sg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6800',
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
    'o8IQiJlMgm8XTnX2uo8BhA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6809',
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
    'GTniLWoQWzwcRLO1smTZGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6818',
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
    '9plYjmUO2BezhOEWvHh7qA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6827',
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
    '0xm5-A8AJAlbRkSuTFJANw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6830',
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
    'MRWjQMCJgyycwn1voJl1zQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6839',
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
    'PruSh7HculWbNtkxkSZUBA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6848',
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
    'j6udUgaA9jMQbMG6nCXUQQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWeakBeforeAndAfter:6851',
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
    'n-CnVdTPKJSKzFKzl2078g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomImplicitValidators:7215',
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
    '5nDNXAbiZSPMupnLZUgIJQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testCustomDependentValidators:7230',
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
    'eVdPOejsj-7taKacwGSthQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:7260',
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
    'Ir1VMYnkHUu0jA64ayCHuQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:7274',
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
    'zZQVRWJvysM6RLfdciQ5xw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:7280',
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
    'mxC3cUw3XLsNYnRsrGLkrw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisks:7297',
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
    'azeRGd1HVbXflygAjWMCxg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testSometimesOnArraysInImplicitRules:7316',
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
    '9m9_tot5KFVzp9vIjl7NYA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:7342',
        'data' => [],
        'validated' => [],
        'rules' => [
            'names.*.first' => 'required'
        ],
        'expandedRules' => []
    ],
    'q4y4GnuUB1sy7whtQy9StA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksForRequiredNonExistingKey:7374',
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
    'awYMw9NAhNlZwrgjmRfvdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:7416',
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
    'p1_zNh1y86wSljEwuxzKNw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:7425',
        'data' => [
            'foo\\.bar' => 'valid'
        ],
        'validated' => [
            'foo.bar' => 'valid'
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
    '6LG_Jr4hZmyX-WPKk-kW7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDot:7429',
        'data' => [
            'foo\\.bar' => 'valid'
        ],
        'validated' => [
            'foo.bar' => 'valid'
        ],
        'rules' => [
            'foo\\.bar' => [
                'required'
            ]
        ],
        'expandedRules' => [
            'foo\\.bar' => [
                'required'
            ]
        ]
    ],
    'S4g5HtmRYNXi2bqD4NQKCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7452',
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
    'BHpPCocTvcFVdQYeMt9Iyg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7455',
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
    '7aJA3YhiSx8cC0nSAg1WiA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7458',
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
    '2wKy6sdiPtqJgqYDdgdqCw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testParsingArrayKeysWithDotWhenTestingExistence:7461',
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
    'C9sKunKytJoxXmyQtwbjKQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPassingSlashVulnerability:7480',
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
    'itl1Ik5CV2aAyjHWQ63nLg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:7506',
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
    'usP6NXmI6XN3Ylxh7I53aQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testPlaceholdersAreReplaced:7521',
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
    'pD2XaVEv3lNVhr5bKfnI1Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testImplicitEachWithAsterisksWithArrayValues:7557',
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
    'bS16hq5cfXSP4k4wDssAqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateNestedArrayWithCommonParentChildKey:7581',
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
    'IHwyIX5GCLpa8LXoAYBpPA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:7609',
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
    'XuYw2bqYkTytFVHsRpUB7g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksConfirmed:7628',
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
    'tcOa86B7SKpe-3nBNflGUA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:7668',
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
    'f1pVgVnTl9KvwsyrL0ED_Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksDifferent:7681',
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
    '2P9_8xWUNwGfwMQFbKtXlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:7721',
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
    'J1rJYPUsg-PQlsKadjfJcA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksSame:7734',
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
    'EP7AdXWPHL3NgGQUiYa62A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:7774',
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
    'NQKc_PSLtXZ9ykiY5hUrOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequired:7783',
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
    'VDtYHfAxKRzEHsZ7eFce4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:7823',
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
    'r45WKXTImVbnOEuGc1Dzmg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredIf:7832',
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
    'XoECVhOFVpb1DFUnCtLSFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredUnless:7872',
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
    'XtqgZjHF2XVZ_s2aC5IcrA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredUnless:7881',
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
    '64Ynn5mRMfEyHSKZJfZrSA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:7921',
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
    'nQ7B89mnBTZpsu-c9GCMsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWith:7930',
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
    '0Xzsfxu0HusH7RX3Q5WMOQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:7978',
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
    'c-9dbRN5nWXTVSZp_XRtXg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithAll:7987',
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
    'uVGBMI5QFP92euS1mzN9vA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:8027',
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
    'r3J3piU-fljhgovHgYj0hQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithout:8036',
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
    '62vu_XWLu1d_px0x4n4Z8Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:8077',
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
    '5SSYE2r2uZdJsmij506fTA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksRequiredWithoutAll:8088',
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
    'iK9oeBCIrwRwPKK4KF_5Nw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:8125',
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
    'Dcoqh2Mgl8i60aw1rFwF_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateImplicitEachWithAsterisksBeforeAndAfter:8139',
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
    'ubkzOAIXMpeTU08hF1PHlA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedData:8792',
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
    'LjbO0ce6rVpUaJ8V-F2b_A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedRules:8807',
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
    'ze8-oHzrH93J7bMNFmwMEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedChildRules:8820',
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
    '0y0lmi4svL7rVF4_3F1dRg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateReturnsValidatedDataNestedArrayRules:8833',
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
    'RxfdxDn2CNZfLj-Fss77mg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateAndValidatedData:8846',
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
    'vLUOValWQGgHBCiKmF-KJg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'RF2LN5x6IdBOiG-xOkJonA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    '67GUHSU5asUtu3HhayNJVQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    's7Dt70cA_XGdkyShoxk5hw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    '-NEJWiUS3_Bcc7rLcjerEQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    '0Nd_zPrmcaTkuMwv5lPacQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'wtFdtCfU3hGrdLF9Qr3uSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'bdHqedUs_rpX5rzI_UZpnA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'xH2d9kNHWGN2qAHxve1OMw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'nlL1lpvP6lovMzf-dZXsRQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUuid:8882',
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
    'y-XpYhJSegP41tQ7YdwTsA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
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
    'Pz3BymGs3IiRUox3Su1rGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ],
        'validated' => [
            'foo' => '00000000-0000-0000-0000-000000000000'
        ],
        'rules' => [
            'foo' => 'uuid:0'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:0'
            ]
        ]
    ],
    'le3SV01pEaHw0UjRZGCtDQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
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
    'BlWDl-PtvTjmvK3E8Lmalg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ],
        'validated' => [
            'foo' => '145a1e72-d11d-11e8-a8d5-f2801f1b9fd1'
        ],
        'rules' => [
            'foo' => 'uuid:1'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:1'
            ]
        ]
    ],
    'SrMeWwvrPM80krNp1JsO4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
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
    'apGdBZDeZGx3f-D21jebIg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ],
        'validated' => [
            'foo' => 'ff6f8cb0-c57d-21e1-9b21-0800200c9a66'
        ],
        'rules' => [
            'foo' => 'uuid:2'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:2'
            ]
        ]
    ],
    'Zj5es04HHWIEnpBcCuyR7A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '76a4ba72-cc4e-3e1d-b52d-856382f408c3'
        ],
        'validated' => [
            'foo' => '76a4ba72-cc4e-3e1d-b52d-856382f408c3'
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
    '_Ch7o3qY4DFOMYisAYKU1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '76a4ba72-cc4e-3e1d-b52d-856382f408c3'
        ],
        'validated' => [
            'foo' => '76a4ba72-cc4e-3e1d-b52d-856382f408c3'
        ],
        'rules' => [
            'foo' => 'uuid:3'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:3'
            ]
        ]
    ],
    '_f4tC1QRtdPs4FaOasLA9A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
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
    'gQv3jxBi2CYZiKXojGiFGg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ],
        'validated' => [
            'foo' => 'a0a2a2d2-0b87-4a18-83f2-2529882be2de'
        ],
        'rules' => [
            'foo' => 'uuid:4'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:4'
            ]
        ]
    ],
    'xq9fJi1C69dmbiOYBPp_sw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'd3b2b5a9-d433-5c58-b038-4fa13696e357'
        ],
        'validated' => [
            'foo' => 'd3b2b5a9-d433-5c58-b038-4fa13696e357'
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
    'xk4B8zH3y-Bgre9V3EwX_g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'd3b2b5a9-d433-5c58-b038-4fa13696e357'
        ],
        'validated' => [
            'foo' => 'd3b2b5a9-d433-5c58-b038-4fa13696e357'
        ],
        'rules' => [
            'foo' => 'uuid:5'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:5'
            ]
        ]
    ],
    'Z7pifJaNKmC55VuVtwiPFg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '1ef97d97-b5ab-67d8-9f12-5600051f1387'
        ],
        'validated' => [
            'foo' => '1ef97d97-b5ab-67d8-9f12-5600051f1387'
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
    'plN7DwFc7YDLj0621cvKqQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '1ef97d97-b5ab-67d8-9f12-5600051f1387'
        ],
        'validated' => [
            'foo' => '1ef97d97-b5ab-67d8-9f12-5600051f1387'
        ],
        'rules' => [
            'foo' => 'uuid:6'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:6'
            ]
        ]
    ],
    'pChxqZDKjXhHoCttU-XXEA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '0192e4b9-92eb-7aec-8707-1becfb1e3eb7'
        ],
        'validated' => [
            'foo' => '0192e4b9-92eb-7aec-8707-1becfb1e3eb7'
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
    '9NbE88wNsa2M0Py0rMvXSg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '0192e4b9-92eb-7aec-8707-1becfb1e3eb7'
        ],
        'validated' => [
            'foo' => '0192e4b9-92eb-7aec-8707-1becfb1e3eb7'
        ],
        'rules' => [
            'foo' => 'uuid:7'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:7'
            ]
        ]
    ],
    'GzW-2DXx9p8yh3G0bX4dYQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '07e80a1f-1629-831f-811f-c595103c91b5'
        ],
        'validated' => [
            'foo' => '07e80a1f-1629-831f-811f-c595103c91b5'
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
    'CObNizopDpQmw-vpxm7jKA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => '07e80a1f-1629-831f-811f-c595103c91b5'
        ],
        'validated' => [
            'foo' => '07e80a1f-1629-831f-811f-c595103c91b5'
        ],
        'rules' => [
            'foo' => 'uuid:8'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:8'
            ]
        ]
    ],
    'xEyeg_TJslokzFwWmEvy1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'
        ],
        'validated' => [
            'foo' => 'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'
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
    'uYfjvUg67o_td3B3tNXAMg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithUuidWithVersionConstraint:8898',
        'data' => [
            'foo' => 'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'
        ],
        'validated' => [
            'foo' => 'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'
        ],
        'rules' => [
            'foo' => 'uuid:max'
        ],
        'expandedRules' => [
            'foo' => [
                'uuid:max'
            ]
        ]
    ],
    'yoUhPpV9Is-GRnHtUd0gHA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidAscii:8987',
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
    'A8ZipwXK7ggsgcwbVRnMpQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testValidateWithValidUlid:9001',
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
    'eci0T2URFjqk-N1fM03x9g' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'vU5dgZwjuPmhTfHheaf1ag' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    '9NwiIXh3YUceRrmwOLtebA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'Hyj0woouc3mqgOXlOHV6gw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'q0Us5oUzTkOUbjylEppfpw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'y0yk2g12E33fbcIV81YaHg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'mZh_JzSThOHaWk0G7c4Ahg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'FT9HHSAPRplu9b-KLhKEYw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    '4d47_retA4vYQcZAW2G9pg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'OhYVQ0LgFOyiAk08DgIg1A' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'VLpBa84c-mkFKVxmmYP56w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'UCEC51JyhNXkg8VuRgnqdw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeIf:9230',
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
    'tEuZ6pPoQ7AhCcRkpvi-fQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExclude:9383',
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
    'FOdHXHXBtAPjSn9IkEKpTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeBeforeADependentRule:9412',
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
    '4P6vRmzbCjexSQojZ1HbTw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9439',
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
    'j6UjtAxdUhuFn_g3kSMSGA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9448',
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
    'PwkJywi_HtxpMSdOShY_lQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9457',
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
    'Wq8AIarlXbR5eu2Q3wnZ4Q' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9466',
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
    'jW2zV4mr_JjubExV5MajDg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9475',
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
    'KghrLuIQiSq5T6fuARPLBQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9484',
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
    'uXJCYOoVB2H1dK0E5DYaEg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9493',
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
    'QMzockMpPEiftZYrGycwhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludingArrays:9502',
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
    'po6PFgnzqIGEaV-HYfJxhQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9513',
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
    'NZHsN0UCl62ZFxBR5xSeuA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9521',
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
    'WiPfg2jt9Vuhrx1EWbOiSw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9529',
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
    'ocPLpWKlp833NainJNv0NQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9545',
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
    '1siZCLPuCXLwLzM8PhDnlw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9550',
        'data' => [],
        'validated' => [],
        'rules' => [
            'bar' => 'exclude_unless:foo,true'
        ],
        'expandedRules' => []
    ],
    'T5tzGmOM57MnBRprgn4BFA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeUnless:9555',
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
    'OgOH-_4ouVwRuBo2KjNUTQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeValuesAreReallyRemoved:9581',
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
    'Z1Ab-TzzNduvbsgS2l2Wiw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testExcludeWithValuesAreReallyRemoved:9610',
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
    'ecn-IZitJC-QyeutI3O0vw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWhenHasKeys:9684',
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
    't-_jREveibVrJDPka-WiOg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testArrayKeysValidationPassedWithPartialMatch:9707',
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
    '3fJGTajdOhQa8kyOo5iZFQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItTrimsSpaceFromParameters:9879',
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
    'jXnWqMpJGWSMnwDXnFsqJw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    'YquCDodot6VvL6IQ-84Fkw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    'FiAZQfyTVqjSltXakSPEcw' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    'HYPPN8e-beE-hECytsDvZA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    'aHyKbmAhpA2VPvDPPtdozQ' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    '1CB8j0BAeoeEI1UwCLVhtA' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItAllowsScientificNotationWithinRange:9984',
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
    'VaSeBmZhP4GIbA9C4gmwug' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testItCanConfigureAllowedExponentRange:10012',
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
    'kn0-Pj5XRwJ8fYS0zA-U3w' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWhenFails:10072',
        'data' => [
            'text' => 'abc'
        ],
        'validated' => [
            'text' => 'abc'
        ],
        'rules' => [
            'text' => 'string|max:5'
        ],
        'expandedRules' => [
            'text' => [
                'string',
                'max:5'
            ]
        ]
    ],
    'W-FPYcPBtshpQTYCtEZOlg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWhenFails:10080',
        'data' => [
            'text' => 'abc'
        ],
        'validated' => [
            'text' => 'abc'
        ],
        'rules' => [
            'text' => 'string|max:5'
        ],
        'expandedRules' => [
            'text' => [
                'string',
                'max:5'
            ]
        ]
    ],
    '9IqDor8mElHqFl12S4uQBg' => [
        'location' => 'Illuminate\\Tests\\Validation\\ValidationValidatorTest::testWhenPasses:10094',
        'data' => [
            'text' => 'abc'
        ],
        'validated' => [
            'text' => 'abc'
        ],
        'rules' => [
            'text' => 'string|max:5'
        ],
        'expandedRules' => [
            'text' => [
                'string',
                'max:5'
            ]
        ]
    ]
];