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
    'name' => 'tao',
    'age' => '23',
    'roles' => [
        1, 2, 3
    ]
    //'money' => '100.1'
];

$rule = [
    'name' => [
        'type' => 'string',
        'regex' => '/.{2,10}/',
        'error' => [
            'lack' => 'name不存在',
            'type' => '类型错误',
            'regex' => '匹配失败',
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
    ],
    'roles' => [
        'type' => 'array',
        'count' => 3,
        'error' => [
            'lack' => '没有role参数',
            'type' => 'role类型不匹配',
            'count' => 'count数量不正确',
        ],
    ]
];


Verify::registerRule($rule);

$check = new Verify($_POST);

try{
    if(!$check->checkParams(['name', 'age', 'money', 'roles'], $args)){
        echo $check->getError();
        return;
    }

    list($name, $age, $money, $roles) = $args;

    //string(3) "tao"
    //int(23)
    //double(0)
    //array(3) {...}
    var_dump($name, $age, $money, $roles);

}catch (\Exception $e){
    //handle an exception
    echo 'Exception: ' . $e->getMessage();
}


