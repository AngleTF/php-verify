<?php

include_once "../vendor/autoload.php";

use angletf\Verify;
use angletf\VerifyType;

$_POST = [
    'name' => 'hello',
    //'desc' => 'I\'m a good person',
    'age' => '22'
];

const EType = new Exception("{PARAM}不是正确的类型{V_NAME} 数据: {DATA}", 1);
const ERequire = new Exception("{PARAM}没有传入", 2);
const EFormat = new Exception("{PARAM}格式不正确", 3);
const ELen = new Exception("{PARAM}长度不正确", 4);

$rule = [
    //参数必传
    'age' => [
        'required' => [
            'value' => false,
            'default' => '99',
            'error' => ERequire
        ],
    ],
    'desc' => [

    ],
    'name' => [
        'required' => [
            'value' => true,
            'default' => "lifeng",
            'error' => ERequire
        ],
        'type' => [
            'value' => VerifyType::String,
            'error' => EType
        ],
        'regex' => [
            'value' => '/^\w+$/',
            'error' => EFormat
        ],
        'min' => [
            'value' => 1,
            'error' => ELen
        ],
        'max' => [
            'value' => 20,
            'error' => ELen
        ],
//        'len' => [
//            'value' => 11,
//            'error' => ELen
//        ],
    ],
];


try {

    $instance = Verify::registerRule($rule);

    if (!$instance->checkParams($_POST, ['name', 'age', 'desc'], $args, false)) {
        $e = $instance->getVerifyError();
        echo $instance->getError()->getMessage();
        var_dump($e);
        return;
    }

    list($name, $age, $desc) = $args;

    var_dump($name);
    var_dump($age);
    var_dump($desc);


} catch (\Exception $e) {
    //handle an exception
    echo 'Exception: ' . $e->getMessage();
}




