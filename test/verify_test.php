<?php
/**
 *  +-------------+-----------------+-----------+------------------+
 *  |   Author    |      Date       |  version  |   E-mail         |
 *  +-------------+-----------------+-----------+------------------+
 *  |  Tao lifeng | 2019/2/15 0:10 |   1.0     | 742592958@qq.com |
 *  +-------------+-----------------+-----------+------------------+
 *  |                       Abstract                               |
 *  +--------------------------------------------------------------+
 *  |
 *  |
 *  |
 *  +--------------------------------------------------------------+
 */

include_once "../vendor/autoload.php";

use angletf\Verify;

$_POST = [
    'name' => 'age',
    'age' => '23',
    //'money' => '100.1'
];

$rule = [
    'name' => [
        'type' => 'string',                  //必须字段
        'length' => 3,
        'regex' => '/\w+/',
        'default' => 'tlf123',
        'error' => [
            'lack' => '没有name参数',          //必须字段
            'type' => 'name类型不匹配',        //必须字段
            'length' => 'name长度不符合',
            'regex' => 'name正则匹配失败',
        ],
    ],
    'age' => [
        'type' => 'int',
        'max' => 50,
        'min' => 0,
        'default' => 1,
        'error' => [
            'lack' => '没有age参数',
            'type' => 'age类型不匹配',
            'max' => 'age大于最大值',
            'min' => 'age小于最小值',
        ],
    ],
    'money' => [
        'type' => 'float',
        'max' => 500,
        'min' => 0,
        'default' => 0.0,
        'error' => [
            'lack' => '没有money参数',
            'type' => 'money类型不匹配',
            'max' => 'money大于最大值',
            'min' => 'money小于最小值',
        ],
    ]
];


Verify::registerRule($rule);

$check = new Verify();

if(!$check->checkParams($_POST, ['name', 'age', 'money'], $args)){
    echo $check->getError();
    return;
}

list($name, $age, $money) = $args;
var_dump($name, $age, $money);