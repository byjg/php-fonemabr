<?php

namespace Tests;

use ByJG\WordProcess\Brazil\Phoneme;
use ByJG\WordProcess\Brazil\Soundex;

if (!class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class PhonemeBRTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
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
            [ "exclarecido", "EXKLARESSIDO" ],
            [ "esclarecido", "EXKLARESSIDO" ],
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
            ["Ana Carolina Kuhnen", 'a']
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $input
     * @param $expected
     */
    public function testFonema($input, $expected)
    {
        $fonema = new Phoneme();
        $actual = $fonema->convert($input);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     * @param $input
     * @param $expected
     */
    public function testSoundex($input, $expected)
    {
        $this->assertEquals($input, Soundex::process($input));
    }
}
