<?php

namespace ByJG\WordProcess;

use ByJG\Convert\FromUTF8;

class Rules
{
    public $preRulesBeforeAll = [];
    public $preRules = [];
    public $rulesBegin = [];
    public $rulesList = [];
    public $rulesEnd = [];

    public function addPreRule($char, $value)
    {
        $this->preRules[$char] = $value;
        return $this;
    }
    public function addPreRuleBeforeAll($char, $value)
    {
        $this->preRulesBeforeAll[$char] = $value;
        return $this;
    }

    public function add($char, $value, $vogal = false)
    {
        if ($vogal) {
            $char = "$char([AEIOU])";
            $value = "$value{1}";
        }

        if ($char[0] == "^") {
            $this->rulesBegin[$char] = $value;
            return $this;
        }

        if (substr($char, -1) == "$") {
            $this->rulesEnd[$char] = $value;
            return $this;
        }

        $this->rulesList[$char] = $value;

        return $this;
    }

    public function addSame($char, $sameAs)
    {
        if ($char[0] == "^") {
            $this->rulesBegin[$char] = $this->rulesBegin[$sameAs];
            return $this;
        }

        if (substr($char, -1) == "$") {
            $this->rulesEnd[$char] = $this->rulesEnd[$sameAs];
            return $this;
        }

        $this->rulesList[$char] = $this->rulesList[$sameAs];

        return $this;
    }

    public function parse($text)
    {
        if (str_contains($text, " ")) {
            $words = explode(" ", $text);
            $result = [];
            foreach ($words as $word) {
                $result[] = $this->parse($word);
            }
            return implode(" ", $result);
        }

        // Prepare the text
        $text = $this->findAndReplaceRulesSimple($this->preRulesBeforeAll, strtoupper($text));
        $text = $this->findAndReplaceRulesSimple($this->preRules, strtoupper(FromUTF8::onlyAscii(FromUTF8::removeAccent($text))));

        // Parse the text
        list($beginText, $text) = $this->findAndReplaceRules($this->rulesBegin, $text);
        $parsedText = "";
        list($endText, $text) = $this->findAndReplaceRules($this->rulesEnd, $text);

        while ($text != "") {
            list($textFound, $text) = $this->findAndReplaceRules($this->rulesList, $text, true);
            if (empty($textFound)) {
                $parsedText .= $text[0];
                $text = substr($text, 1);
            } else {
                $parsedText .= $textFound;
            }
        }

        return $beginText . $parsedText . $endText;
    }

    protected function findAndReplaceRulesSimple($ruleList, $text)
    {
        foreach ($ruleList as $char => $value) {
            $text = preg_replace("/$char/", $value, $text);
        }
        return $text;
    }

    protected function findAndReplaceRules($ruleList, $text, $fromBegin = false)
    {
        $newText = "";
        foreach ($ruleList as $rule => $value) {
            if ($fromBegin) {
                $rule = "^$rule";
            }
            if (preg_match("/$rule/", $text, $matches)) {
                $newText = $this->replaceText($value, $matches);
                $text = preg_replace("/$rule/", "", $text);
                break;
            }
        }
        return [$newText, $text];
    }

    protected function replaceText($text, $matches)
    {
        $result = $text;
        foreach ($matches as $key => $value) {
            $result = str_replace("{" . $key . "}", $value, $result);
        }
        return $result;
    }

}
