<?php

namespace Tests;

use ByJG\WordProcess\Portuguese\Stemmer;
use PHPUnit\Framework\TestCase;

class StemmerTest extends TestCase
{
    private Stemmer $stemmer;

    protected function setUp(): void
    {
        $this->stemmer = new Stemmer();
    }

    // -------------------------------------------------------------------------
    // Plural reduction — singular and plural must produce the same stem
    // -------------------------------------------------------------------------
    public static function pluralProvider(): array
    {
        return [
            ["cas",     "casa"],
            ["cas",     "casas"],
            ["funil",   "funil"],
            ["funil",   "funis"],
            ["nacional", "nacionais"],   // ais → al → "nacional"; "l" not in vowel rules, stays
            ["nacional", "nacional"],
            ["papel",   "papéis"],      // éis → el → papel; "l" not in vowel rules
            ["papel",   "papel"],
            ["menu",    "menus"],       // "u" not in vowel rules; stays "menu"
            ["menu",    "menu"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pluralProvider')]
    public function testPlural(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Feminine / masculine — both forms must produce the same stem
    // -------------------------------------------------------------------------
    public static function feminineProvider(): array
    {
        return [
            ["bonit",   "bonito"],
            ["bonit",   "bonita"],
            ["bonit",   "bonitos"],
            ["bonit",   "bonitas"],
            ["profes",  "professor"],
            ["profes",  "professora"],
            ["profes",  "professoras"],
            ["gostos",  "gostoso"],
            ["gostos",  "gostosa"],
            // "francês"/"francesa" do NOT converge in RSLP (known limitation):
            // plural strips "s" from "francês" → "francê" → vowel "ê→e" → "france"
            // feminine converts "francesa" → "francês" (already past plural step) → stays "francês"
            ["france",  "francês"],
            ["francês", "francesa"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('feminineProvider')]
    public function testFeminine(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Adverb reduction
    // -------------------------------------------------------------------------
    public static function adverbProvider(): array
    {
        return [
            ["feliz",   "felizmente"],
            ["facil",   "facilmente"],
            // "rapidamente" → adverb strips "mente" → "rapida"
            // then verb rule "ida" fires (treating it as a verb past participle form) → "rap"
            // This is a known RSLP over-stemming case.
            ["rap",     "rapidamente"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('adverbProvider')]
    public function testAdverb(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Augmentative / diminutive reduction
    // -------------------------------------------------------------------------
    public static function augmentativeProvider(): array
    {
        return [
            ["cas",     "casinha"],
            ["menin",   "menininho"],   // "inho" strips → "menin"; "n" not in vowel rules
            ["grand",   "grandíssimo"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('augmentativeProvider')]
    public function testAugmentative(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Noun suffix reduction
    // -------------------------------------------------------------------------
    public static function nounProvider(): array
    {
        return [
            ["amiz",    "amizade"],
            ["amiz",    "amizades"],
            ["facil",   "facilidade"],
            ["facil",   "facilidades"],
            ["comunic", "comunicação"],
            ["comunic", "comunicações"],
            ["corr",    "corredor"],    // edor rule: stem "corr" (4≥3) → "corr"
            ["alun",    "aluno"],
            ["alun",    "aluna"],
            ["alun",    "alunos"],
            ["alun",    "alunas"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('nounProvider')]
    public function testNoun(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Verb suffix reduction
    // -------------------------------------------------------------------------
    public static function verbProvider(): array
    {
        return [
            // All conjugations of "correr" must share the stem "corr"
            ["corr", "correr"],
            ["corr", "correndo"],
            ["corr", "corrida"],
            ["corr", "corridas"],
            ["corr", "corrido"],
            ["corr", "corridos"],
            ["corr", "correu"],
            ["corr", "corremos"],
            ["corr", "correram"],
            // "falar"
            ["fal",  "falar"],
            ["fal",  "falando"],
            ["fal",  "falou"],
            ["fal",  "falei"],
            ["fal",  "falamos"],
            ["fal",  "falaram"],
            ["fal",  "falará"],
            ["fal",  "falado"],
            // "partir"
            ["part", "partir"],
            ["part", "partindo"],
            ["part", "partiu"],
            ["part", "partimos"],
            ["part", "partiram"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('verbProvider')]
    public function testVerb(string $expected, string $word): void
    {
        $this->assertEquals($expected, $this->stemmer->stem($word));
    }

    // -------------------------------------------------------------------------
    // Exception injection
    // -------------------------------------------------------------------------
    public function testProtectedWordsAreNeverStemmed(): void
    {
        $stemmer = new Stemmer(protectedWords: ['solar', 'neural', 'docker']);

        $this->assertEquals('solar',  $stemmer->stem('solar'));   // would give "sol" without protection
        $this->assertEquals('neural', $stemmer->stem('neural'));  // would give "neur"
        $this->assertEquals('docker', $stemmer->stem('docker'));  // would give "dock"

        // Words not in the list are still stemmed normally
        $this->assertEquals('fal', $stemmer->stem('falar'));
    }

    public function testExtraExceptionsExtendPerSuffixRules(): void
    {
        $stemmer = new Stemmer(extraExceptions: ['ar' => ['nuclear', 'muscular']]);

        $this->assertEquals('nuclear',  $stemmer->stem('nuclear'));   // protected via extraExceptions
        $this->assertEquals('muscular', $stemmer->stem('muscular'));  // protected via extraExceptions

        // Other "ar" words still stem normally
        $this->assertEquals('fal', $stemmer->stem('falar'));
        $this->assertEquals('corr', $stemmer->stem('correr'));
    }

    public function testDefaultConstructorBehaviourUnchanged(): void
    {
        $default = new Stemmer();
        $custom  = new Stemmer([], []);

        $this->assertEquals($default->stem('falar'),   $custom->stem('falar'));
        $this->assertEquals($default->stem('correndo'), $custom->stem('correndo'));
    }

    // -------------------------------------------------------------------------
    // Key property: different forms of the same word → same stem
    // -------------------------------------------------------------------------
    public function testWordFamilyConvergence(): void
    {
        $stemmer = $this->stemmer;

        // "comunicar" family
        $stems = array_unique(array_map(
            fn($w) => $stemmer->stem($w),
            ["comunicar", "comunicando", "comunicação", "comunicações", "comunicado"]
        ));
        $this->assertCount(1, $stems, "comunicar family: " . implode(', ', $stems));

        // "falar" family
        $stems = array_unique(array_map(
            fn($w) => $stemmer->stem($w),
            ["falar", "falando", "falou", "falei", "falamos", "falado"]
        ));
        $this->assertCount(1, $stems, "falar family: " . implode(', ', $stems));

        // "correr" family
        $stems = array_unique(array_map(
            fn($w) => $stemmer->stem($w),
            ["correr", "correndo", "corrida", "corrido", "correu", "corremos"]
        ));
        $this->assertCount(1, $stems, "correr family: " . implode(', ', $stems));
    }
}
