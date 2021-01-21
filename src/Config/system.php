<?php
return [
    [
        'key' => 'sales.paymentmethods.sumup',
        'name' => 'SumUp',
        'sort' => 100,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'Título',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'Descrição',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ],[
                'name' => 'type',
                'title' => 'Tipo de Checkout',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Redirect',
                        'value' => 'redirect'
                    ], [
                        'title' => 'Lightbox',
                        'value' => 'lightbox'
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'email_address',
                'title' => 'SumUp Email',
                'type' => 'text',
                'validation' => 'required',
                'info' => 'Endereço de email utilizado na conta do SumUp'
            ],[
                'name' => 'client_id',
                'title' => 'Cliente Id',
                'type' => 'text',
                'validation' => 'required',
                'info' => 'Client_ID gerado na sua conta SumUp, para descobrir como pegar seu Token acesse esse link: https://developer.sumup.com/docs/register-app/'
            ],[
                'name' => 'client_secret',
                'title' => 'Cliente Secret',
                'type' => 'text',
                'validation' => 'required',
                'info' => 'Token gerado na sua conta SumUp, para descobrir como pegar seu Token acesse esse link: https://developer.sumup.com/docs/register-app/'
            ],[
                'name' => 'code',
                'title' => 'Code',
                'type' => 'text',
                'info' => 'Code generated automated not required'
            ],[
                'name' => 'authorization',
                'title' => 'Autorização',
                'type' => 'boolean',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true,
            ],[
                'name' => 'sandbox',
                'title' => 'admin::app.admin.system.sandbox',
                'type' => 'boolean',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true,
            ],
//            [
//                'name' => 'no_interest',
//                'title' => 'Quantidade de Parcelas sem Juros',
//                'type' => 'select',
//                'options' => [
//                    [
//                        'title' => '0',
//                        'value' => 0
//                    ],[
//                        'title' => '1',
//                        'value' => 1
//                    ], [
//                        'title' => '2',
//                        'value' => 2
//                    ], [
//                        'title' => '3',
//                        'value' => 3
//                    ], [
//                        'title' => '4',
//                        'value' => 4
//                    ], [
//                        'title' => '5',
//                        'value' => 5
//                    ], [
//                        'title' => '6',
//                        'value' => 6
//                    ], [
//                        'title' => '7',
//                        'value' => 7
//                    ], [
//                        'title' => '8',
//                        'value' => 8
//                    ], [
//                        'title' => '9',
//                        'value' => 9
//                    ], [
//                        'title' => '10',
//                        'value' => 10
//                    ], [
//                        'title' => '11',
//                        'value' => 11
//                    ], [
//                        'title' => '12',
//                        'value' => 12
//                    ]
//                ],
//                'validation' => 'required'
//            ], [
//                'name' => 'max_installments',
//                'title' => 'Quantidade Máxima de Parcelas',
//                'type' => 'select',
//                'options' => [
//                    [
//                        'title' => '1',
//                        'value' => 1
//                    ], [
//                        'title' => '2',
//                        'value' => 2
//                    ], [
//                        'title' => '3',
//                        'value' => 3
//                    ], [
//                        'title' => '4',
//                        'value' => 4
//                    ], [
//                        'title' => '5',
//                        'value' => 5
//                    ], [
//                        'title' => '6',
//                        'value' => 6
//                    ], [
//                        'title' => '7',
//                        'value' => 7
//                    ], [
//                        'title' => '8',
//                        'value' => 8
//                    ], [
//                        'title' => '9',
//                        'value' => 9
//                    ], [
//                        'title' => '10',
//                        'value' => 10
//                    ], [
//                        'title' => '11',
//                        'value' => 11
//                    ], [
//                        'title' => '12',
//                        'value' => 12
//                    ]
//                ],
//                'validation' => 'required'
//            ],
            [
                'name' => 'debug',
                'title' => 'Debug log?',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Ativo',
                        'value' => true
                    ], [
                        'title' => 'Inativo',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ],[
                'name' => 'active',
                'title' => 'admin::app.admin.system.status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Ativo',
                        'value' => true
                    ], [
                        'title' => 'Inativo',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ]
        ]
    ]
];