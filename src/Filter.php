<?php


namespace angletf;


class Filter
{

    /**
     * 默认的过滤规则
     * @var array
     */
    public $defaultFilterRule = [
        'trim' => ["\n", "\r", "\t", "\0", "\x0B", "\x20"],
        'replace' => []
    ];

    public $newFilterRule = [];

    public function stringFilter(&$str, $rule = []){

        if (!is_string($str)) {
            return;
        }

        $this->newFilterRule = array_merge($this->defaultFilterRule, $rule);

        $this->trim($str);
    }

    public function trim(&$str){
        $trim_condition = join('', $this->newFilterRule['trim']);
        $str = trim($str, $trim_condition);
    }

}