### 安装
```
composer require angletf/php-verify
```

### 引入自动加载和命名空间
```php
include_once "../vendor/autoload.php";
use angletf\Verify;
```

### 注册参数规则

**规则名&参数名**

name, age, money 分别是规则名, 也是参数名, 其中规则下的type, error中的lack和type都是必填字段,
type目前仅支持 `string`, `int`, `float`

**type支持的参数**
1. type为string类型可以使用`length`, `regex`, `default`
2. type为float类型可以使用`min`, `max`, `default`
3. type为int类型可以使用`min`, `max`, `default`

**错误信息返回**

error是数组对应匹配规则报错的自定义信息, 如果有匹配失败则返回某个规则的错误用户自定义信息

```php
$rule = [
    'name' => [
        'type' => 'string',                 //必须字段
        'length' => 3,                      //该字符长度要求为3
        'regex' => '/\w+/',                 //正则规则
        'default' => 'tlf123',              //默认参数
        'error' => [
            'lack' => '没有name参数',         //必须字段
            'type' => 'name类型不匹配',       //必须字段
            'length' => 'name长度不符合',
            'regex' => 'name正则匹配失败',
        ],
    ],
    'age' => [
        'type' => 'int',
        'max' => 50,
        'min' => 0,
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
        'default' =>0,
        'error' => [
            'lack' => '没有money参数',
            'type' => 'money类型不匹配',
            'max' => 'money大于最大值',
            'min' => 'money小于最小值',
        ],
    ]
];

Verify::registerRule($rule);
```

### 使用

```php
#伪造一个post请求
$_POST = [
    'name' => 'age',
    'age' => '23',
    //'money' => '100' 使用默认值
];

#传入需要验证的值
$check = new Verify($_POST);

#第一个参数是使用的规则, 同时也是需要验证的参数值, 第二个参数则是 正确后返回的参数
#返回值是bool, 代表验证是否成功
if(!$check->checkParams(['name', 'age', 'money'], $args)){
    #验证失败, 获取用户自定义的报错信息
    echo $check->getError();
    return;
}

#按照规则的顺序返回
list($name, $age, $money) = $args;

#string(3) "age", int(23), double(0)
var_dump($name, $age, $money);
```

