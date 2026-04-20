<?php
declare(strict_types=1);

return [
    'name' => '易支付',
    'description' => '兼容易支付接口（支付宝/微信/QQ钱包）',
    'version' => '1.0.0',
    'author' => 'CharityDoing',
    'options' => [
        'alipay' => '支付宝',
        'wxpay' => '微信支付',
        'qqpay' => 'QQ钱包'
    ],
    'callback' => [
        \App\Consts\Pay::IS_SIGN => true,
        \App\Consts\Pay::IS_STATUS => true,
        \App\Consts\Pay::FIELD_STATUS_KEY => 'trade_status',
        \App\Consts\Pay::FIELD_STATUS_VALUE => 'TRADE_SUCCESS',
        \App\Consts\Pay::FIELD_ORDER_KEY => 'out_trade_no',
        \App\Consts\Pay::FIELD_AMOUNT_KEY => 'money',
        \App\Consts\Pay::FIELD_RESPONSE => 'success'
    ]
];
