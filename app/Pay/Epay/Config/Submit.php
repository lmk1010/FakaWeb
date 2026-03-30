<?php
declare(strict_types=1);

return [
    [
        'title' => '接口地址',
        'name' => 'api',
        'type' => 'input',
        'required' => true,
        'placeholder' => 'https://zpayz.cn'
    ],
    [
        'title' => '商户ID(PID)',
        'name' => 'pid',
        'type' => 'input',
        'required' => true,
        'placeholder' => '请输入商户ID'
    ],
    [
        'title' => '商户密钥(PKEY)',
        'name' => 'key',
        'type' => 'input',
        'required' => true,
        'placeholder' => '请输入商户密钥'
    ],
    [
        'title' => '站点名称',
        'name' => 'sitename',
        'type' => 'input',
        'placeholder' => 'jiminaishop'
    ],
    [
        'title' => '订单标题',
        'name' => 'order_name',
        'type' => 'input',
        'placeholder' => '商品订单'
    ],
    [
        'title' => '签名方式',
        'name' => 'sign_type',
        'type' => 'radio',
        'dict' => [
            ['id' => 'MD5', 'name' => 'MD5']
        ],
        'default' => 'MD5'
    ]
];
