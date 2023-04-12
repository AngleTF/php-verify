### 安装
```
composer require angletf/php-verify
```

### 引入自动加载和命名空间
```php
include_once "../vendor/autoload.php";
use angletf\Verify;
```

### 注册规则&错误
```php
const EType = new Exception("参数类型错误", 1);
const ERequire = new Exception("参数没有传入", 2);
const EFormat = new Exception("参数格式不正确", 3);
const ELen = new Exception("name参数长度不正确", 4);


$rule = [
    'age' => [
        //age参数不是必传, 如果没有传入 设置默认值为 '99'
        //也可以将默认值设置为 `NULL` 在程序中进行判断
        'required' => [
            'value' => false,
            'default' => '99',
            'error' => ERequire
        ],
    ],
    'desc' => [

    ],
    'name' => [
        //name 参数必传
        'required' => [
            'value' => true,
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
    ],
];
```

### 快速使用

```php
//伪造一个post请求
$_POST = [
    'name' => 'tao',
    'age' => '22'
];



try {
    
    //注册规则, 并且返回实例
    $instance = Verify::registerRule($rule);

    //第一个参数是需要验证的数组($_POST, $_GET)
    //第二个参数是需要验证哪些参数
    //第三个参数是按照第二个参数的顺序返回参数, 是一个引用类型
    //如果验证失败, 则输出规则中对应的信息
    if (!$instance->checkParams($_POST, ['name', 'age', 'desc'], $args)) {
        echo $instance->getError()->getMessage();
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

```

### 注册规则参数说明

**规则名&参数名**

> 规则注册接收一个二维数组
```php
[
    '参数名' => [
        '验证器1' => [
            'value' => '验证器1的value',
            'error' => '当验证失败时, 验证器1返回的错误, Exception类型'
        ],
        '验证器2' => [
        ...
        ]
    ]
    ...
]
```


**required验证器**

| 参数名     | 允许的值                                           |
|---------|------------------------------------------------|
| value   | 是否为必须参数, 如果为true, 则必须传入, 如果为false, 则未传入时将赋予默认值 |
| error   | Exception错误                                    |
| default | 默认值                                            |


**type验证器**

| 参数名     | 允许的值                                           |
|---------|------------------------------------------------|
| value   | `VerifyType::String`, `VerifyType::Float`, `VerifyType::Int`, `VerifyType::Array`, `VerifyType::Json`, `VerifyType::Base64` |
| error   | Exception错误                                    |

**regex验证器**

| 参数名     | 允许的值        |
|---------|-------------|
| value   | 正则内容        |
| error   | Exception错误 |

**min验证器**

| 参数名     | 允许的值        |
|---------|-------------|
| value   | 参数不能小于xxx, 如果验证对象是数字类型则验证大小, 如果是字符类型则使用`utf-8`编码验证长度         |
| error   | Exception错误 |

**max验证器**

| 参数名     | 允许的值        |
|---------|-------------|
| value   | 参数不能小于xxx, 如果验证对象是数字类型则验证大小, 如果是字符类型则使用`utf-8`编码验证长度         |
| error   | Exception错误 |

**len验证器**

| 参数名     | 允许的值        |
|---------|-------------|
| value   | 验证参数长度, 如果验证对象是数字则转化为字符, 使用`utf-8`编码验证长度    |
| error   | Exception错误 |

**错误信息返回**

> 通过 checkParams 方法, 来判断验证是否通过, 如果未通过 可以打印error来获取精准的信息

```php

if (!$instance->checkParams($_POST, ['name', 'age', 'desc'], $args)) {
    //返回你在规则中提供的 error => \Exception
    var_dump($instance->getError());
    return;
}
```

**Verify API**

| 方法类型 | 方法                                                                         |介绍|
|------|----------------------------------------------------------------------------|---|
| 静态   | registerRule ( array rules): Verify                                        |注册规则, 并且返回实例|
| 实例   | checkParams (array request_params, array check_params, &array result):bool | 验证参数                                |

