<?php

namespace ByJG\WordProcess\Portuguese;

class Soundex
{
    public static function process($text)
    {
        $phoneme = new Metaphone();
        $text = $phoneme->convert($text);

        $soundexMap = [
            "1" => "BFPV",
            "2" => "CGJKQSXZ",
            "3" => "DT",
            "4" => "L",
            "5" => "MN",
            "6" => "R",
        ];

        $soundexCode = $text[0];

        for ($i = 1; $i < strlen($text); $i++) {
            foreach ($soundexMap as $key => $value) {
                if (str_contains($value, $text[$i]) && !str_ends_with($soundexCode, $key)) {
                    $soundexCode = $soundexCode . $key;
                    if (strlen($soundexCode) == 4) {
                        return $soundexCode;
                    }
                }
            }
        }

        return str_pad($soundexCode, 4, "0");
    }
}