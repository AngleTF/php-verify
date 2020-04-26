### 安装
```
composer require angletf/php-verify
```

### 引入自动加载和命名空间
```php
include_once "../vendor/autoload.php";
use angletf\Verify;
```

### 注册规则
```php
$rule = [
    'name' => [
        'type' => 'string',
        'regex' => '/^.{3}$/u',
        'min' => 1,
        'max' => 4,
        'length' => 3,
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
    $vInst = Verify::registerRule($rule);

    //第一个参数是需要验证的数组($_POST, $_GET)
    //第二个参数是需要验证哪些参数
    //第三个参数是按照第二个参数的顺序返回参数, 是一个引用类型
    //如果验证失败, 则输出规则中对应的信息
    if (!$vInst->checkParams($_POST, ['name', 'age'], $args)) {
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

```

### 注册规则参数说明

**规则名&参数名**

规则注册接收一个二维数组, error中的lack是必填字段, 当有default验证器时可以忽略这个选项
```php
[
    '验证参数名' => [
        '验证器1' => '验证器需要确认的规范值',
        '验证器2' => '验证器需要确认的规范值',
        ...
        'error' => [
            'lack' => '这个参数缺失(必填), 当有default验证器时可以忽略这个选项',
            '验证器1' => '当验证器1验证不通过时返回的错误信息',
            '验证器2' => '当验证器2验证不通过时返回的错误信息',
            ....
        ]
    ]
    ...
]
```


**验证器**

|验证器名|可允许的值|
|---|---|
|type|`string`, `float`, `int`, `array`|
|regex|正则, 例如'/^.{3}$/u'|
|min|参数不能小于xxx, 如果验证对象是数字类型则验证大小, 如果是字符类型则使用`utf-8`编码验证长度|
|max|参数不能大于xxx, 如果验证对象是数字类型则验证大小, 如果是字符类型则使用`utf-8`编码验证长度|
|length|验证参数长度, 如果验证对象是数字则转化为字符, 使用`utf-8`编码验证长度|
|default|当验证中没有这个参数时, 会使用默认值返回, 默认值不会经过验证|

**错误信息返回**

error是数组对应匹配规则报错的自定义信息, 如果有匹配失败则返回某个规则的错误用户自定义信息, 不限于字符串

error规则中可以注入的参数

|参数|介绍|
|---|---|
|{V_PARAM}|当前验证的参数|
|{V_NAME}|验证器的名字|
|{V_DATA}|验证器的规范值|




**Verify API**

|方法|介绍|
|---|---|
|Verify Verify::registerRule ( array rules)|注册规则, 并且返回实例|
|bool Verify::checkParams (array request_params, array check_params, &array result)|验证参数|

