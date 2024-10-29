<?php

namespace ByJG\WordProcess\Portuguese;

use ByJG\WordProcess\Rules;


class Metaphone
{

    public function getRules(): Rules
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
            ->addPreRule("Z{2,}", "Z")
            ->addPreRule("Y", "I")
        ;

        $rules
            ->add("AO$", "AUM")
            ->add("AOS$", "AUM")
            ->add("AE$", "AUM")
            ->add("AES$", "AUM")
            ->add("B", "B", true)
            ->add("C([AOU])", "K{1}")
            ->add("C([EI])", "SS{1}")
            ->add("CL", "KL", true)
            ->add("CH", "X", true)
            ->add("C([^AEIOU])", "K{1}")
            ->add("D", "D")
            ->add("^EX", "ES")
            ->add("^EX", "EZ", true)
            ->add("F", "F", true)
            ->add("G([AOU])", "G{1}", false)
            ->add("G([EI])", "J{1}", false)
            ->add("GH", "GU", true)
            ->add("^H", "", true)
            ->add("H([^AEIOU])", "{1}")
            ->add("J", "J", true)
            ->add("L", "L", true)
            ->add("LH", "LH", true)
            ->add("NG", "N")
            ->add("N", "N", true)
            ->add("NH", "NH", true)
            ->add("N$", "M", false)
            ->add("N", "M")
            ->add("M", "M", true)
            ->add("OES$", "AUM")
            ->add("PH", "F", true)
            ->add("P", "P", true)
            ->add("QU", "K", true)
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
            ->add("^W", "U", true)
            ->add("W", "V", true)
        ;

        return $rules;
    }

    /**
     * @param string $text
     * @return string
     */
    public function convert(string $text): string
    {
        return $this->getRules()->parse($text);
    } // END_METHOD
}
