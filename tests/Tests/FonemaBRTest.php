<?php

namespace Tests;

use ByJG\FonemaBR;

if (!class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class FonemaBRTest extends \PHPUnit\Framework\TestCase
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
            [ "enfase", "EMFAZE" ],
            [ "emfase", "EMFAZE" ],
            [ "pitomba", "PITOMBA" ],
            [ "pitonba", "PITOMBA" ],
            [ "praça", "PRAKA" ],
            [ "praças", "PRAKAZ" ],
            [ "PRAÇA", "PRAKA" ],
            [ "vago", "VAGO" ],
            [ "vigor", "VIGO" ],
            [ "vagrant", "VAGRAMT" ],
            [ "arranhado", "ARRANADO" ],
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
            [ "caça", "KAKA" ],
            [ "casa", "KAZA" ],
            [ "SAPO", "SSAPO" ],
            [ "pesca", "PEZKA" ],
            [ "facção", "FAKSSAO" ],
            [ "sessão", "SSESSAO" ],
            [ "seção", "SSEKAO" ],
            [ "áéíóúàèìòùãẽĩõũâêîôûäëïöü", "AEIOUAEIOUAOUAEIOUAEIOU" ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param $input
     * @param $expected
     */
    public function testFonema($input, $expected)
    {
        $fonema = new FonemaBR();
        $result = $fonema->convert($input);
        $this->assertEquals($expected, $result);
    }
}
