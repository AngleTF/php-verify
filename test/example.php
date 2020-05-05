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
    'name' => 'MTE=',
    'age' => '22'
];

$rule = [
    'name' => [
        'type' => 'base64',
        'error' => [
            'lack' => '没有{V_PARAM}参数',
            'type' => '{V_PARAM}不是{V_DATA}类型',
            'regex' => '{V_PARAM}匹配失败',
            'min' => '{V_PARAM}最小不能超过{V_DATA}位',
            'max' => '{V_PARAM}最大不能超过{V_DATA}位',
            'length' => '{V_PARAM}不是{V_DATA}位'
        ],
    ],
    'age' => [
        'type' => 'int',
        'min' => 20,
        'max' => 99,
        'default' => 20,
        'error' => [
            'type' => '{V_PARAM}不是{V_DATA}类型',
            'min' => '{V_PARAM}最小不能超过{V_DATA}',
            'max' => '{V_PARAM}最大不能超过{V_DATA}',
            'length' => '{V_PARAM}不是{V_DATA}位'
        ],
    ],
];


try {

    $vInst = Verify::registerRule($rule);

    if (!$vInst->checkParams($_POST, ['name', 'age'], $args, false)) {
        echo $vInst->getError();
        return;
    }

    list($name, $age) = $args;

    var_dump($name);
    var_dump($age);


} catch (\Exception $e) {
    //handle an exception
    echo 'Exception: ' . $e->getMessage();
}




