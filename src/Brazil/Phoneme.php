<?php

namespace ByJG\WordProcess\Brazil;

use ByJG\WordProcess\Rules;

class Phoneme
{

    public function getRules()
    {
        $rules = new Rules();

        $rules
            ->addPreRuleBeforeAll("(ç|Ç)", "SS");

        $rules
            ->addPreRule("A{2,}", "A")
            ->addPreRule("B{2,}", "B")
            ->addPreRule("C{2,}", "C")
            ->addPreRule("D{2,}", "D")
            ->addPreRule("E{2,}", "E")
            ->addPreRule("F{2,}", "F")
            ->addPreRule("G{2,}", "G")
            ->addPreRule("H{2,}", "H")
            ->addPreRule("I{2,}", "I")
            ->addPreRule("J{2,}", "J")
            ->addPreRule("K{2,}", "K")
            ->addPreRule("L{2,}", "L")
            ->addPreRule("M{2,}", "M")
            ->addPreRule("N{2,}", "N")
            ->addPreRule("O{2,}", "O")
            ->addPreRule("P{2,}", "P")
            ->addPreRule("Q{2,}", "Q")
            ->addPreRule("R+([^AEIOU])", "R\$1")
            ->addPreRule("R{2,}([AEIOU])", "RR\$1")
            ->addPreRule("S{2,}([BCDFGHJKLMNPQRSTVWXZ])", "S\$1")
            ->addPreRule("S{2,}([AEIOUY])", "SS\$1")
            ->addPreRule("T{2,}", "T")
            ->addPreRule("U{2,}", "U")
            ->addPreRule("V{2,}", "V")
            ->addPreRule("W{2,}", "W")
            ->addPreRule("X{2,}", "X")
            ->addPreRule("Y{2,}", "Y")
            ->addPreRule("Z{2,}", "Z");

        $rules
            ->add("AO$", "AUM")
            ->add("AOS$", "AUM")
            ->add("AE$", "AUM")
            ->add("AES$", "AUM")
            ->add("B", "B", true)
            ->add("CA", "KA")
            ->add("CL", "KL", true)
            ->add("CH", "X", true)
            ->add("CE", "SSE")
            ->add("CI", "SSI")
            ->add("CO", "KO")
            ->add("CU", "KU")
            ->add("C([^AEIOU])", "K{1}")
            ->add("D", "D")
            ->add("^ES", "EX")
            ->add("^EX", "EZ", true)
            ->addSame("^EX", "^ES")
            ->add("F", "F", true)
            ->add("GA", "GA", false)
            ->add("GE", "JE", false)
            ->add("GI", "JI", false)
            ->add("GO", "GO", false)
            ->add("GU", "GU", true)
            ->add("GH", "G", true)
            ->add("^H", "", true)
            ->add("H([^AEIOU])", "{1}")
            ->add("J", "J", true)
            ->add("L", "L", true)
            ->add("LH", "LH", true)
            ->add("N", "N", true)
            ->add("NH", "NH", true)
            ->add("N$", "M", false)
            ->add("N", "M")
            ->add("M", "M", true)
            ->add("OES$", "AUM")
            ->add("PH", "F", true)
            ->add("P", "P", true)
            ->add("Q", "K", false)
            ->add("R", "R", true)
            ->add("RR", "RR", true)
            ->add("R$", "", false)
            ->add("SX", "X", true)
            ->add("SS", "SS", true)
            ->add("SH", "X", true)
            ->add("^SA", "SA", false)
            ->add("^SE", "CE", false)
            ->add("^SI", "CI", false)
            ->add("^SO", "SO", false)
            ->add("^SU", "SU", false)
            ->add("S", "Z", true)
            ->add("S$", "Z")
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
            ->add("Y", "I")
        ;

        return $rules;
    }

    /**
     * @param $text
     * @return string
     */
    public function convert($text)
    {
        return $this->getRules()->parse($text);
    } // END_METHOD
}
