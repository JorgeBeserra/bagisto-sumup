<?php
return [
    'sumup'  => [
        'code'              => 'sumup',
        'title'             => 'SumUp',
        'description'       => 'Pague sua compra com SumUp',
        'class'             => \Jorgebeserra\Sumup\Payment\SumUp::class,
        'active'            => true,
//        'no_interest'       => 5,
        'type'              => 'redirect',
//        'max_installments'  => 10,
        'debug'              => false,
        'sort'              => 100,
    ],
];