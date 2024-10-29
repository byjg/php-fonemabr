<?php

namespace ByJG\WordProcess;

use ByJG\Convert\FromUTF8;

class Rules
{
    public array $preRulesBeforeAll = [];
    public array $preRules = [];
    public array $rulesBegin = [];
    public array $rulesList = [];
    public array $rulesEnd = [];

    public function addPreRule(string $char, string $value): static
    {
        $this->preRules[$char] = $value;
        return $this;
    }
    public function addPreRuleBeforeAll(string $char, string $value): static
    {
        $this->preRulesBeforeAll[$char] = $value;
        return $this;
    }

    public function add(string $char, string $value, $vogal = false): static
    {
        if ($vogal) {
            $char = "$char([AEIOU])";
            $value = "$value{1}";
        }

        if ($char[0] == "^") {
            $this->rulesBegin[$char] = $value;
            return $this;
        }

        if (str_ends_with($char, "$")) {
            $this->rulesEnd[$char] = $value;
            return $this;
        }

        $this->rulesList[$char] = $value;

        return $this;
    }

    public function addSame(string $char, string $sameAs): static
    {
        if ($char[0] == "^") {
            $this->rulesBegin[$char] = $this->rulesBegin[$sameAs];
            return $this;
        }

        if (str_ends_with($char, "$")) {
            $this->rulesEnd[$char] = $this->rulesEnd[$sameAs];
            return $this;
        }

        $this->rulesList[$char] = $this->rulesList[$sameAs];

        return $this;
    }

    public function parse(string $text): string
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

    protected function findAndReplaceRulesSimple(array $ruleList, string $text): string
    {
        foreach ($ruleList as $char => $value) {
            $text = preg_replace("/$char/", $value, $text);
        }
        return $text;
    }

    /**
     * @param array $ruleList
     * @param string $text
     * @param bool $fromBegin
     * @return array
     */
    protected function findAndReplaceRules(array $ruleList, string $text, bool $fromBegin = false): array
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

    /**
     * @param string $text
     * @param array $matches
     * @return string
     */
    protected function replaceText(string $text, array $matches): string
    {
        $result = $text;
        foreach ($matches as $key => $value) {
            $result = str_replace("{" . $key . "}", $value, $result);
        }
        return $result;
    }

}
