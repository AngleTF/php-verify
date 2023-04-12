<?php
namespace angletf;

class Verify
{

    public static ?Verify $instance = null;

    /**
     * 定义全局验证规则
     * @var array
     */
    protected array $rules = [];


    /**
     * 当前的错误信息
     * @var VerifyError|null
     */
    protected VerifyError|null $verifyError = null;


    /**
     * 验证器类映射
     * @var array
     */
    protected array $classMap = [];

    protected array $globalVar = [
        '{PARAM}',
        '{V_NAME}',
        '{DATA}',
    ];

    protected string $separator = ',';

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
            if (str_contains($name, $this->separator)) {
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
    public function setRule(string $name, array $rule): void
    {

        $name = trim($name);

        if (empty($name)) {
            return;
        }

        foreach ($rule as $validator => $attrs) {

            $class_name = __NAMESPACE__ . '\validator\\' . ucfirst($validator);

            if (!class_exists($class_name)) {
                throw new \Exception("validator not exists: $class_name");
            }

            $this->classMap[$validator] = $class_name;
        }

        $this->rules[$name] = $rule;
    }

    /**
     * 注册规则, 并且返回实例
     * @param array $rules
     * @return Verify
     * @throws \Exception
     */
    public static function registerRule(array $rules = []): Verify
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($rules);
        }
        return self::$instance;
    }


    /**
     * @throws \Exception
     */
    public function checkParams(array $data, array $params, &$args): bool
    {

        $validated = [];
        foreach ($params as $param_name) {

            $rule = $this->rules[$param_name] ?? null;
            $param_data = $data[$param_name] ?? null;

            if ($rule === null) {
                throw new \Exception("Please register the rule for `{$param_name}`");
            }

            foreach ($rule as $validator_name => $attrs) {
                $validator = new $this->classMap[$validator_name];
                foreach ($attrs as $attr => $v) {
                    $validator->$attr = $v;
                }

                if (!$validator->check($param_data)) {
                    $this->verifyError = self::createError($param_name, $param_data, $validator_name, $validator);
                    return false;
                }

                if (!$validator->next()) {
                    break;
                }
            }

            $validated[] = $param_data;
        }

        $args = $validated;
        return true;
    }

    private function replaceVal(string $err_message, array $replace): string
    {
        return str_replace($this->globalVar, $replace, $err_message);
    }

    public function getError(): ?\Exception
    {
        if ( $this->verifyError === null) return null;

        return $this->verifyError->validator->error;
    }

    public function getVerifyError(): ?VerifyError
    {
        return $this->verifyError;
    }

    public static function createError($param_name, $param_data, $validator_name, $validator): VerifyError
    {
        $err = new VerifyError();
        $err->paramData = $param_data;
        $err->paramName = $param_name;
        $err->validatorName = $validator_name;
        $err->validator = $validator;
        return $err;
    }
}