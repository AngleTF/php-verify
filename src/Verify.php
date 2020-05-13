<?php
/**
 *  +-------------+-----------------+-----------+------------------+
 *  |   Author    |      Date       |  version  |   E-mail         |
 *  +-------------+-----------------+-----------+------------------+
 *  |  Tao lifeng | 2018/6/16 17:22 |   1.0     | 742592958@qq.com |
 *  +-------------+-----------------+-----------+------------------+
 *  |                       Abstract                               |
 *  +--------------------------------------------------------------+
 *  |
 *  |   参数验证类
 *  |
 *  +--------------------------------------------------------------+
 */

namespace angletf;

class Verify
{

    public static $instance = null;

    /**
     * 用户传入的错误返回规则
     * @var mixed
     */
    protected $verifyError = [];

    /**
     * 用户传入的默认规则
     * @var array
     */
    protected $verifyDefault = [];

    /**
     * 定义全局验证规则
     * @var array
     */
    protected $rules = [];


    /**
     * 当前的错误信息
     * @var null
     */
    protected $currentErrorMessage = null;


    /**
     * 验证器类映射
     * @var array
     */
    protected $classMap = [];

    protected $globalVar = [
        '{V_PARAM}',
        '{V_NAME}',
        '{V_DATA}',
    ];

    protected $separator = ',';

    /**
     * Verify constructor.
     * @param $rules
     * @throws \Exception
     */
    private function __construct($rules)
    {

        //模块分类
        foreach ($rules as $name => $rule) {

            //检查名字是否具有多参数验证
            if (strpos($name, $this->separator) !== false) {
                foreach (explode($this->separator, $name) as $new_name) {
                    $this->setRule($new_name, $rule);
                }
            } else {
                $this->setRule($name, $rule);
            }
        }
    }

    /**
     * 设置规则
     * @param $name
     * @param $rule
     * @throws \Exception
     */
    public function setRule($name, $rule)
    {

        $name = trim($name);

        if (empty($name)) {
            return;
        }

        $has_default = false;

        if (!isset($rule['error'])) {
            throw new \Exception('Missing error configuration item');
        }

        $this->verifyError[$name] = $rule['error'];
        unset($rule['error']);

        if (isset($rule['default'])) {
            $this->verifyDefault[$name] = $rule['default'];
            $has_default = true;
            unset($rule['default']);
        }

        foreach ($rule as $validator => $item) {

            $class_name = __NAMESPACE__ . '\validator\\' . ucfirst($validator);

            if (!class_exists($class_name)) {
                throw new \Exception("validator not exists: $class_name");
            }

            $this->classMap[$validator] = $class_name;

            if (!isset($this->verifyError[$name][$validator])) {
                throw new \Exception("Please set {$name} rule, `{$validator}` error handle");
            }
        }

        //检测error.lack参数, 有default参数会忽略lack验证
        if (!$has_default && !isset($this->verifyError[$name]['lack'])) {
            throw new \Exception("Please set {$name} rule, `lack` error handle");
        }

        $this->rules[$name] = $rule;
    }

    /**
     * 注册规则, 并且返回实例
     * @param array $rules
     * @return Verify|null
     * @throws \Exception
     */
    public static function registerRule($rules = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($rules);
        }
        return self::$instance;
    }


    public function checkParams($data, $params, &$args, $convert = true)
    {
        $validated = [];

        foreach ($params as $param_name) {

            if (!isset($this->rules[$param_name])) {
                throw new \Exception("Please register the rule for {$param_name}");
            }

            //如果有默认值
            if (!isset($data[$param_name]) && isset($this->verifyDefault[$param_name])) {
                $validated[] = $this->verifyDefault[$param_name];
                continue;
            }

            if (!isset($data[$param_name])) {
                $this->currentErrorMessage = $this->injectVar($this->verifyError[$param_name]['lack'], $param_name);
                return false;
            }

            foreach ($this->rules[$param_name] as $validator => $rule_value) {
                if (!$this->classMap[$validator]::check($data[$param_name], $rule_value, $convert)) {
                    $this->currentErrorMessage = $this->injectVar($this->verifyError[$param_name][$validator], $param_name, $validator, $rule_value);
                    return false;
                }
            }

            $validated[] = $data[$param_name];
        }

        $args = $validated;
        return true;
    }

    public function injectVar($err_message, $param_name, $validator = null, $rule_value = null)
    {
        if (is_string($err_message)) {
            return $this->replaceStrVal($err_message, $param_name, $validator, $rule_value);
        }

        if (is_array($err_message) && isset($err_message[0])) {
            if (is_string($err_message[0])) {
                $err_message[0] = $this->replaceStrVal($err_message[0], $param_name, $validator, $rule_value);
                return $err_message;
            }
        }

        return $err_message;
    }

    public function replaceStrVal($err_message, $param_name, $validator = null, $rule_value = null)
    {
        return str_replace($this->globalVar, [
            $param_name,
            $validator,
            $rule_value
        ], $err_message);
    }

    public function getError()
    {
        return $this->currentErrorMessage;
    }
}






