<?php

namespace Tests;

use ByJG\WordProcess\Portuguese\Metaphone;
use ByJG\WordProcess\Portuguese\Soundex;

class MetaphoneBRTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public static function dataProviderMetaphone(): array
    {
        return [
            [ "ambulancia", "AMBULAMSSIA" ],
            [ "anbulancia", "AMBULAMSSIA" ],
            [ "BALA", "BALA" ],
            [ "bala", "BALA" ],
            [ "fome", "FOME" ],
            [ "fama", "FAMA" ],
            [ "ffffffammmmmmmmmma", "FAMA" ],
            [ "aviao", "AVIAUM" ],
            [ "enfase", "EMFAZE" ],
            [ "emfase", "EMFAZE" ],
            [ "pitomba", "PITOMBA" ],
            [ "pitonba", "PITOMBA" ],
            [ "praça", "PRASSA" ],
            [ "praças", "PRASSAZ" ],
            [ "vago", "VAGO" ],
            [ "vigor", "VIGO" ],
            [ "vagrant", "VAGRAMT" ],
            [ "arranhado", "ARRANHADO" ],
            [ "arranado", "ARRANADO" ],
            [ "brazilia", "BRAZILIA" ],
            [ "brasilia", "BRAZILIA" ],
            [ "brasil", "BRAZIL" ],
            [ "hortolândia", "ORTOLAMDIA" ],
            [ "chave", "XAVE" ],
            [ "chavear", "XAVEA" ],
            [ "chuva", "XUVA" ],
            [ "exclarecido", "ESKLARESSIDO" ],
            [ "esclarecido", "ESKLARESSIDO" ],
            [ "mexe", "MEXE" ],
            [ "mesa", "MEZA" ],
            [ "caça", "KASSA" ],
            [ "casa", "KAZA" ],
            [ "SAPO", "SAPO" ],
            [ "pesca", "PESKA" ],
            [ "facção", "FAKSSAUM" ],
            [ "sessão", "CESSAUM" ],
            [ "seção", "CESSAUM" ],
            [ "áéíóúàèìòùãẽĩõũâêîôûäëïöü", "AEIOUAEIOUAOUAEIOUAEIOU" ],
            ["pães", "PAUM"],
            ["pão", "PAUM"],
            ["avião", "AVIAUM"],
            ["aviões", "AVIAUM"],
            ["queijo", "KEIJO"],
            ["boqueira", "BOKEIRA"],
            ["danilo", "DANILO"],
            ["marilene", "MARILENE"],
            ['gelo', 'JELO'],
            ['gola', 'GOLA'],
            ["gueixa", "GUEIXA"],
            ["gheixa", "GUEIXA"],
            ["wellington", "UELINTOM"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dataProviderMetaphone')]
    public function testMetaphone($input, $expected): void
    {
        $fonema = new Metaphone();
        $actual = $fonema->convert($input);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public static function dataProviderSoudex(): array
    {
        return [
            ["N200", "nike"],
            ["N200", "niky,"],
            ["N200", "niki,"],
            ["N200", "nyke,"],
            ["N200", "naique"],
            ["M452", "melancia"],
            ["M452", "melansia,"],
            ["M452", "melanssia"],
            ["M240", "Michael"],
            ["M240", "Maichael,"],
            ["M240", "Mychael"],
            ["J250", "Jackson"],
            ["J250", "Jacksom,"],
            ["J250", "Jeckson"],
            ["M650", "marrom"],
            ["M650", "marron,"],
            ["M650", "maron,"],
            ["M650", "marom"],
            ["U453", "wellington"],
            ["U453", "uelintom"],
            ["K200", "casa"],
            ["K200", "caça"],
            ["K200", "caçar"],
        ];
    }
    
    #[\PHPUnit\Framework\Attributes\DataProvider('dataProviderSoudex')]
    public function testSoundex($expected, $input): void
    {
        $this->assertEquals($expected, Soundex::process($input));
    }
}
