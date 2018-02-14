<?php

namespace ByJG;

use ByJG\Convert\FromUTF8;

class FonemaBR
{

    public function getRules()
    {
        $rules = new Rules();
        $rules
            ->add(" ", " ", true)
            ->add("B", "B", true)
            ->add("C", "K", true)
            ->add("CH", "X", true)
            ->add("CE", "SSE", true)
            ->add("CI", "SSI", true)
            ->add("CC", "KSS", true)
            ->add("D", "D", true)
            ->add("^ES", "EX", true)
            ->addSame("^EX", "^ES")
            ->add("F", "F", true)
            ->add("GA", "GA", false)
            ->add("GE", "JE", false)
            ->add("GI", "JI", false)
            ->add("GO", "GO", false)
            ->add("GU", "GU", true)
            ->add("GH", "G", true)
            ->add("G", "G", true)
            ->add("H", "", true)
            ->add("J", "J", true)
            ->addSame("K", "C")
            ->add("L", "L", true)
            ->add("NA", "NA", true)
            ->add("NE", "NE", true)
            ->add("NI", "NI", true)
        ;

        $rules
            ->add("NO", "NO", true)
            ->add("NU", "NU", true)
            ->add("NH", "N", true)
            ->add("N", "M", false)
            ->add("N$", "M", false)
            ->add("M", "M", true)
            ->add("PH", "F", true)
            ->add("P", "P", true)
            ->add("Q", "K", false)
            ->add("R", "R", true)
            ->add("RR", "RR", true)
            ->add("R$", "", false)
            ->add("SX", "X", true)
            ->add("SS", "SS", true)
            ->add("SH", "X", true)
            ->add("^SA", "SSA", false)
            ->add("^SE", "SSE", false)
            ->add("^SI", "SSI", false)
            ->add("^SO", "SSO", false)
            ->add("^SU", "SSU", false)
            ->add("S", "Z", true)
            ->add("T", "T", true)
            ->add("V", "V", true)
            ->add("X", "X", true)
            ->add("WA", "VA", false)
        ;

        $rules
            ->add("WE", "VE", false)
            ->add("WI", "VI", false)
            ->add("WO", "VO", false)
            ->add("WU", "VU", false)
            ->add("W", "U", true)
            ->add("Z", "S", true)
            ->add("ZA", "ZA", false)
            ->add("ZE", "ZE", false)
            ->add("ZI", "ZI", false)
            ->add("ZO", "ZO", false)
            ->add("ZU", "ZU", false)
        ;

        return $rules;
    }

    /**
     * @param $text
     * @return string
     */
    public function convert($text)
    {
        $rules = $this->getRules();

        $text = strtoupper(FromUTF8::onlyAscii(FromUTF8::removeAccent($text)));
        $result = "";

        $ipos = 0;
        $expectVogal = true;
        while ($ipos < strlen($text)) {
            $first = isset($text[$ipos - 1]) ? "" : '^';
            $previous = isset($text[$ipos - 1]) ? $text[$ipos - 1] : '^';
            $current = $text[$ipos];
            $next = isset($text[$ipos + 1]) ? $text[$ipos + 1] : '$';

            if ($current === $previous) {
                $ipos += 1;
                continue;
            }

            $rule = $rules->get($first, $current, $next);
            if ($expectVogal && $rule["move"] === 1 && strpos("AEIOU", $current) !== false) {
                $rule = [
                    "default" => $current,
                    "move" => 1,
                    "vogal" => true
                ];
            }

            $result .= $rule["default"];
            $ipos += $rule["move"];
            $expectVogal = $rule["vogal"];
        }

        return $result;
    } // END_METHOD
}
