<?php

namespace ByJG\WordProcess\Portuguese;


use ByJG\WordProcess\Php80;

class Soundex
{
    public static function process($text)
    {
        $phoneme = new Metaphone();
        $text = $phoneme->convert($text);

        if (empty($text)) {
            return "";
        }

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
                if (Php80::str_contains($value, $text[$i]) && !Php80::str_ends_with($soundexCode, $key)) {
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
