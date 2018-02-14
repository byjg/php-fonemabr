<?php

namespace ByJG;

class Rules
{
    public $rulesList = [];

    public function add($char, $default, $vogal = true)
    {
        $this->rulesList[$char] = [
            "default" => $default,
            "vogal" => $vogal,
            "move" => strlen(str_replace("^", "", $char))
        ];

        return $this;
    }

    public function addSame($char, $sameAs)
    {
        $this->rulesList[$char] = $this->rulesList[$sameAs];

        return $this;
    }

    public function get($previous, $char, $next)
    {
        $check = [
            "$previous$char$next",
            "$previous$char",
            "$char$next",
            "$char"
        ];

        foreach ($check as $item) {
            if (isset($this->rulesList[$item])) {
                return $this->rulesList[$item];
            }
        }

        return [
            "default" => '',
            "vogal" => false,
            "move" => 1
        ];
    }
}
