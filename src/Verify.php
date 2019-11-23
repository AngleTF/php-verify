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

    /**
     * 用户传入的错误返回值
     * @var mixed
     */
    public $error;

    /**
     * 定义全局验证规则
     * @var array
     */
    public static $rule = [];

    /**
     * 存放默认值的键值
     * @var array
     */
    public $defaultKey = [];

    /**
     * 需要忽略的方法名
     * @var array
     */
    private $ignore = ['error', 'type', 'default', 'filter'];

    /**
     * 支持的验证类型
     * @var array
     */
    public $supportVerifyTypes = ['string', 'int', 'float', 'array'];

    /**
     * 等待验证的参数
     * @var array
     */
    public $verifyParams = [];

    /**
     * Filter 对象
     * @var null
     */
    private $filter = null;

    public function __construct($verify)
    {
        $this->verifyParams = $verify;
        $this->filter = new Filter();
    }

    public static function registerRule($rule = [])
    {
        self::$rule = array_merge(self::$rule, $rule);
    }

    /**
     * 验证参数
     * @param array $params 需要验证的参数
     * @param array $args 返回的参数
     * @return bool
     * @throws \Exception
     */
    public function checkParams($params, &$args)
    {
        $verify = $this->verifyParams;

        if (count(array_diff($params, array_keys(self::$rule))) != 0) {
            throw new \Exception('Please register the rules');
        }

        $lack_arr = array_diff($params, array_keys($verify));

        foreach ($lack_arr as $k) {
            if (!isset(self::$rule[$k]['default'])) {
                $this->error = self::$rule[$k]['error']['lack'];
                return false;
            }
            $verify[$k] = self::$rule[$k]['default'];
            $this->defaultKey[] = $k;
        }

        $validated = [];

        foreach ($params as $k) {

            //如果使用的是默认值, 或者不存在规则配置 则不需要进行验证
            if (in_array($k, $this->defaultKey) || !isset(self::$rule[$k])) {
                $validated[] = $verify[$k];
                continue;
            }

            $type = isset(self::$rule[$k]['type']) ? self::$rule[$k]['type'] : 'string';
            //验证支持的类型模型
            if (!in_array($type, $this->supportVerifyTypes)) {
                throw new \Exception("Unknown validation model");
            }

            //过滤字符串参数
            $this->filter->stringFilter($verify[$k],
                isset(self::$rule[$k]['filter']) ? self::$rule[$k]['filter'] : []);

            $class_name = __NAMESPACE__ . '\types\Verify' . ucfirst($type);

            $rule = self::$rule[$k];

            //验证类型
            if (!$this->checkType($type, $verify[$k], $verify_val)) {
                $this->error = $rule['error']['type'];
                return false;
            }

            $verify[$k] = $verify_val;

            foreach ($rule as $rk => $rv) {
                if (in_array($rk, $this->ignore) ||
                    !is_callable([$class_name, 'verify' . ucfirst($rk)], true, $callback_name)) {
                    continue;
                }
                if (call_user_func($callback_name, $verify_val, $rv) !== true) {
                    $this->error = $rule['error'][$rk];
                    return false;
                }
            }

            $validated[] = $verify[$k];
        }

        $args = $validated;

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    private function checkType($type, $verify_val, &$filter_val)
    {
        switch ($type) {
            case 'string':
                if (!is_string($verify_val)) {
                    return false;
                }
                $filter_val = $verify_val;
                break;
            case 'int':
                $filter_var = filter_var($verify_val, FILTER_VALIDATE_INT);
                if ($filter_var === false) {
                    return false;
                }
                $filter_val = $filter_var;
                break;
            case 'float':
                $filter_var = filter_var($verify_val, FILTER_VALIDATE_FLOAT);
                if ($filter_var === false) {
                    return false;
                }
                $filter_val = $filter_var;
                break;
            case 'array':
                if (!is_array($verify_val)) {
                    return false;
                }
                $filter_val = $verify_val;
                break;
        }
        return true;
    }

}






