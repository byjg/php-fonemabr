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
            [ "ambulancia", "MBLMSS" ],
            [ "anbulancia", "MBLMSS" ],
            [ "enfase", "MFZ" ],
            [ "emfase", "MFZ" ],
            [ "pitomba", "PTMB" ],
            [ "pitonba", "PTMB" ],
            [ "praça", "PRSS" ],
            [ "praças", "PRSSZ" ],
            [ "PRAÇA", "PRSS" ],
            [ "vago", "VG" ],
            [ "vigor", "VG" ],
            [ "vagrant", "VGRMT" ],
            [ "arranhado", "RRND" ],
            [ "arranado", "RRND" ],
            [ "brazilia", "BRZL" ],
            [ "brasilia", "BRZL" ],
            [ "brasil", "BRZL" ],
            [ "hortolândia", "RTLMD" ],
            [ "chave", "XV" ],
            [ "chavear", "XV" ],
            [ "chuva", "XV" ],
            [ "exclarecido", "XKLRSSD" ],
            [ "esclarecido", "XKLRSSD" ],
            [ "mexe", "MX" ],
            [ "mesa", "MZ" ],
            [ "caça", "KSS" ],
            [ "casa", "KZ" ],
            [ "facção", "FKSS" ],
            [ "sessão", "SSSS" ],
            [ "seção", "SSSS" ],
            [ "áéíóúàèìòùãẽĩõũâêîôûäëïöü", "" ],
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
        $this->assertEquals($expected, $fonema->converte($input));
    }
}
