### 安装 (install)
```
composer require angletf/php-verify
```

### 引入自动加载和命名空间 (Introduces automatic loading and namespaces)
```php
include_once "../vendor/autoload.php";
use angletf\Verify;
```

### 注册规则 (Registration rules)
```php
$rule = [
    'name' => [
        'type' => 'string',                 //必须字段 Required fields
        'length' => 3,                      //该字符长度要求为3 Character length requirement 3
        'regex' => '/\w+/',                 //正则规则 Regex rules
        'default' => 'tlf123',              //默认参数 default parameters
        'error' => [
            'lack' => 'no name argument',   //必须字段 Required fields
            'type' => 'type mismatch',      //必须字段 Required fields
            'length' => 'name length mismatch',
            'regex' => 'regex mismatch',
        ],
    ],
    'age' => [
        'type' => 'int',
        'max' => 50,
        'min' => 0,
        'error' => [
            'lack' => 'no age argument',
            'type' => 'type mismatch',
            'max' => 'over max age',
            'min' => 'over min age',
        ],
    ],
    'money' => [
        'type' => 'float',
        'max' => 500,
        'min' => 0,
        'default' =>0,
        'error' => [
            'lack' => 'no money argument',
            'type' => 'type mismatch',
            'max' => 'over max money',
            'min' => 'over min money',
        ],
    ],
    'role' => [
            'type' => 'array',
            'count' => 3,
            'error' => [
                'lack' => 'no role argument',
                'type' => 'type mismatch',
                'count' => 'argument role count mismatch',
            ],
        ]
];

#注册规则 (Registration rules)
Verify::registerRule($rule);
```

### 快速使用 (Quick to use)

```php
#伪造一个post请求
$_POST = [
    'name' => 'age',
    'age' => '23',
    'role' => [
        1, 2, 3
    ]
    //'money' => '100' use default value
];

$check = new Verify($_POST);

if(!$check->checkParams(['name', 'age', 'money'], $args)){
    #verify failure
    echo $check->getError();
    return;
}

#按照规则的顺序返回
list($name, $age, $money) = $args;

#string(3) "age", int(23), double(0)
var_dump($name, $age, $money);
```

### 注册规则参数说明 (Registration rule parameter specification)

**规则名&参数名 (Rule name & parameter name)**

规则注册接收一个二维数组, key是规则名 同时也是等待验证的参数名, error中的lack和type都是必填字段,
The rule registration receives a two-dimensional array. The key is the name of the rule and also the parameter name to be verified. Lack and type in error are required fields.
```php
[
    '规则名&验证参数名' => [
        'type' => '变量类型(必填)',
        '这个type支持所支持的规则' => '规则匹配值',
        ...
        'error' => [
            'lack' => '这个参数缺失(必填)',
            'type' => '这个类型不正确(必填)',
            '对应的规则' => '错误返回的值'
            ....
        ]
    ]   
]
```


**type支持的类型 (type supported parameter values)**

|type|可使用的规则名称|
|---|---|
|string|`length`, `regex`, `default`|
|float|`min`, `max`, `default`|
|int|`min`, `max`, `default`|
|array|`count`|

**错误信息返回 (return error message)**

error是数组对应匹配规则报错的自定义信息, 如果有匹配失败则返回某个规则的错误用户自定义信息, 不限于字符串
Error is the custom information reported by the array corresponding to the matching rule. If there is a matching failure, the error user custom information of a rule will be returned, which is not limited to strings

**Verify方法 (Verify method) **

|method|argument|
|---|---|
|Verify::registerRule|void Verify::registerRule ( array )|
|Verify::checkParams|bool Verify::checkParams ( array, &array )|

