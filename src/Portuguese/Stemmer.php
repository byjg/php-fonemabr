<?php

namespace ByJG\WordProcess\Portuguese;

class Stemmer
{
    /**
     * @param array $protectedWords  Words that are never stemmed regardless of any rule.
     *                               Useful for proper nouns, domain terms, acronyms, etc.
     *                               e.g. ['solar', 'neural', 'docker']
     *
     * @param array $extraExceptions Additional per-suffix exceptions merged with the
     *                               hardcoded ones. Keyed by suffix string.
     *                               e.g. ['ar' => ['icular', 'nuclear'],
     *                                     'or' => ['tensor', 'vector']]
     */
    public function __construct(
        private readonly array $protectedWords  = [],
        private readonly array $extraExceptions = []
    ) {}

    /**
     * Stem a Portuguese word using the RSLP algorithm.
     * Orengo, V.M. & Huyck, C. (2001). A Stemming Algorithm for the Portuguese Language.
     *
     * Steps applied in order:
     *   1. Plural reduction
     *   2. Feminine reduction
     *   3. Adverb reduction
     *   4. Augmentative/diminutive reduction
     *   5. Noun suffix reduction  (mutually exclusive with step 6)
     *   6. Verb suffix reduction
     *   7. Vowel removal
     */
    public function stem(string $word): string
    {
        $word = mb_strtolower(trim($word));

        if (in_array($word, $this->protectedWords, true)) {
            return $word;
        }

        $word = $this->applyStep($word, $this->pluralRules());
        $word = $this->applyStep($word, $this->feminineRules());
        $word = $this->applyStep($word, $this->adverbRules());
        $word = $this->applyStep($word, $this->augmentativeRules());

        $prev = $word;
        $word = $this->applyStep($word, $this->nounRules());
        if ($word === $prev) {
            $word = $this->applyStep($word, $this->verbRules());
        }

        $word = $this->applyStep($word, $this->vowelRules());

        return $word;
    }

    /**
     * Try each rule against the end of $word.
     * Rules format: [suffix, min_stem_length, replacement, exceptions[], stem_must_not_end_in = null]
     * Returns on first match; returns $word unchanged if no rule matches.
     */
    protected function applyStep(string $word, array $rules): string
    {
        foreach ($rules as $rule) {
            [$suffix, $minLength, $replacement, $exceptions] = $rule;
            $stemExclusion = $rule[4] ?? null;

            $allExceptions = array_merge($exceptions, $this->extraExceptions[$suffix] ?? []);
            if (in_array($word, $allExceptions, true)) {
                continue;
            }
            $suffixLen = mb_strlen($suffix);
            $wordLen   = mb_strlen($word);
            if ($wordLen > $suffixLen && mb_substr($word, -$suffixLen) === $suffix) {
                $stem = mb_substr($word, 0, $wordLen - $suffixLen);
                if (mb_strlen($stem) >= $minLength) {
                    if ($stemExclusion !== null && str_ends_with($stem, $stemExclusion)) {
                        continue;
                    }
                    return $stem . $replacement;
                }
            }
        }
        return $word;
    }

    // -------------------------------------------------------------------------
    // Step 1 – Plural
    // -------------------------------------------------------------------------
    protected function pluralRules(): array
    {
        return [
            ["ns",   1, "m",   []],
            ["ções", 3, "ção", []],
            ["ões",  3, "ão",  []],
            ["ães",  1, "ão",  []],
            ["ais",  3, "al",  []],
            ["éis",  2, "el",  []],
            ["eis",  3, "el",  ["mais"]],
            ["óis",  2, "ol",  []],
            ["is",   2, "il",  ["lápis", "cais", "mais", "criais", "férias", "pais", "país", "más", "mas"]],
            ["les",  4, "l",   []],
            ["res",  3, "r",   ["ires"]],
            ["s",    3, "",    ["aliás", "pois", "mas", "menos", "férias", "ferias", "pais", "país", "mais", "criais", "lápis", "cais"]],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 2 – Feminine
    // -------------------------------------------------------------------------
    protected function feminineRules(): array
    {
        return [
            ["ona",  3, "ão",   ["abandona", "acetona", "cortisona", "detona", "iona", "lona", "maratona", "mona", "patrona", "zona"]],
            ["ora",  3, "or",   []],
            ["na",   4, "no",   ["abandona", "banana", "campana", "digna", "dona", "lona", "mana", "mona", "patrona", "persona", "plana", "rana", "sultana"]],
            ["inha", 3, "inho", []],
            ["esa",  3, "ês",   []],
            ["osa",  3, "oso",  []],
            ["íaca", 3, "íaco", []],
            ["ica",  3, "ico",  []],
            ["ada",  2, "ado",  []],
            ["ida",  3, "ido",  []],
            ["ída",  3, "ido",  []],
            ["ima",  3, "imo",  []],
            ["iva",  3, "ivo",  []],
            ["eira", 3, "eiro", []],
            ["ã",    2, "ão",   ["irmã", "maçã", "amanhã"]],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 3 – Adverb
    // -------------------------------------------------------------------------
    protected function adverbRules(): array
    {
        return [
            ["mente", 4, "", ["experimente"]],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 4 – Augmentative / Diminutive
    // -------------------------------------------------------------------------
    protected function augmentativeRules(): array
    {
        return [
            ["díssimo",    5, "", []],
            ["abilíssimo", 5, "", []],
            ["íssimo",     3, "", []],
            ["ésimo",      3, "", []],
            ["érrimo",     4, "", []],
            ["zinho",      2, "", []],
            ["zinha",      2, "", []],
            ["adíssimo",   3, "", []],
            ["inho",       3, "", ["caminho", "cominho"]],
            ["inha",       3, "", ["rainha", "linha", "minha"]],
            // stemExclusion "ç": prevents stripping "ão" from -ção words (comunicação, estação, etc.)
            ["ão",         4, "", ["feijão", "verão", "razão", "formação", "coração", "posição", "nação", "ação", "pão", "leão", "mão", "chão", "estação", "relação", "educação", "situação", "irmão", "cidadão"], "ç"],
            ["ona",        3, "", []],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 5 – Noun suffixes
    // -------------------------------------------------------------------------
    protected function nounRules(): array
    {
        return [
            ["encialista", 4, "", []],
            ["alista",     5, "", []],
            ["agem",       3, "", ["coragem", "chantagem", "vantagem", "carruagem"]],
            ["iamento",    3, "", []],
            ["amento",     3, "", ["firmamento", "fundamento", "departamento"]],
            ["imento",     3, "", []],
            ["mento",      6, "", ["firmamento", "fundamento", "departamento", "complemento", "instrumento", "documento"]],
            ["alismo",     4, "", []],
            ["ivismo",     4, "", []],
            ["ismo",       3, "", ["cinismo"]],
            ["abilidade",  5, "", []],
            ["idade",      4, "", []],
            ["icionista",  4, "", []],
            ["cionista",   4, "", []],
            ["ionista",    4, "", []],
            ["ista",       4, "", []],
            ["auta",       4, "", []],
            ["quice",      4, "", []],
            ["ice",        4, "", ["cúmplice"]],
            ["ícia",       3, "", []],
            ["icia",       3, "", []],
            ["ência",      3, "", []],
            ["ância",      4, "", ["ambulância"]],
            ["ncia",       4, "", []],
            ["edade",      4, "", []],
            ["ade",        3, "", []],
            ["tura",       4, "", ["acupuntura", "costura", "cultura", "literatura", "temperatura", "tintura", "tortura", "mistura"]],
            ["ura",        4, "", ["acupuntura", "costura", "cultura", "literatura", "temperatura", "tintura", "tortura", "mistura"]],
            ["ator",       3, "", []],
            ["edor",       3, "", []],
            ["idor",       4, "", ["ouvidor"]],
            ["dor",        4, "", ["ouvidor"]],
            ["sor",        4, "", ["assessor"]],
            ["atória",     3, "", []],
            ["ória",       3, "", ["categoria", "memória", "teoria", "vitória"]],
            ["oria",       3, "", ["categoria", "memória", "teoria", "vitória"]],
            ["ário",       3, "", ["voluntário", "armário", "salário"]],
            ["ario",       3, "", ["voluntário", "armário", "salário"]],
            ["ério",       3, "", []],
            ["erio",       3, "", []],
            ["ativo",      4, "", []],
            ["tivo",       4, "", []],
            ["ivo",        4, "", []],
            ["ante",       2, "", ["elefante", "gigante", "instante", "possante", "restaurante", "volante"]],
            ["ável",       2, "", ["afável", "razoável", "potável", "vulnerável"]],
            ["ível",       3, "", []],
            ["vel",        5, "", ["nível"]],
            ["bil",        3, "vel", []],
            ["ional",      4, "", []],
            ["ição",       3, "", []],
            ["ção",        3, "", []],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 6 – Verb suffixes  (only applied when Step 5 made no change)
    // -------------------------------------------------------------------------
    protected function verbRules(): array
    {
        return [
            ["aríamos",  2, "", []],
            ["eríamos",  2, "", []],
            ["iríamos",  3, "", []],
            ["ássemos",  2, "", []],
            ["êssemos",  2, "", []],
            ["íssemos",  3, "", []],
            ["áramos",   2, "", []],
            ["éramos",   3, "", []],
            ["íramos",   3, "", []],
            ["ávamos",   2, "", []],
            ["ásseis",   2, "", []],
            ["êsseis",   3, "", []],
            ["ísseis",   3, "", []],
            ["áveis",    2, "", []],
            ["aremos",   2, "", []],
            ["eremos",   3, "", []],
            ["iremos",   3, "", []],
            ["ariam",    2, "", []],
            ["eriam",    3, "", []],
            ["iriam",    3, "", []],
            ["assem",    2, "", []],
            ["essem",    3, "", []],
            ["issem",    3, "", []],
            ["aram",     2, "", []],
            ["eram",     3, "", []],
            ["iram",     3, "", []],
            ["avam",     2, "", ["agravam"]],
            ["arem",     2, "", ["jovem"]],
            ["erem",     3, "", []],
            ["irem",     3, "", []],
            ["arão",     2, "", []],
            ["erão",     3, "", []],
            ["irão",     2, "", []],
            ["ando",     2, "", []],
            ["endo",     3, "", []],
            ["indo",     3, "", []],
            ["ondo",     3, "", []],
            ["ados",     2, "", []],
            ["idos",     3, "", []],
            ["idas",     3, "", []],
            ["amos",     2, "", []],
            ["emos",     2, "", []],
            ["imos",     2, "", []],
            ["arás",     2, "", []],
            ["aras",     2, "", []],
            ["erás",     3, "", []],
            ["eras",     3, "", []],
            ["irás",     3, "", []],
            ["arei",     2, "", []],
            ["erei",     3, "", []],
            ["irei",     3, "", []],
            ["asse",     2, "", []],
            ["esse",     3, "", []],
            ["isse",     3, "", []],
            ["aste",     2, "", []],
            ["este",     3, "", ["agreste", "celeste", "faroeste"]],
            ["iste",     4, "", []],
            ["ares",     3, "", []],
            ["eres",     3, "", []],
            ["ires",     3, "", []],
            ["ava",      2, "", ["agrava"]],
            ["ara",      2, "", []],
            ["era",      3, "", []],
            ["ira",      3, "", []],
            ["ada",      2, "", []],
            ["ida",      3, "", []],
            ["ado",      2, "", []],
            ["ido",      3, "", []],
            ["ou",       2, "", []],
            ["eu",       2, "", []],   // 3rd person past -er verbs: correu, vendeu
            ["iu",       2, "", []],   // 3rd person past -ir verbs: partiu, abriu
            ["ei",       3, "", []],
            ["ará",      2, "", []],   // future -ar: falará, buscará
            ["erá",      2, "", []],   // future -er: venderá
            ["irá",      2, "", []],   // future -ir: partirá
            // After plural strips "s" from -amos/-emos/-imos, the verb step sees -amo/-emo/-imo.
            // These rules catch those intermediate forms (min=3 protects short stems like "r" in "ramo").
            ["amo",      3, "", []],
            ["emo",      3, "", []],
            ["imo",      3, "", []],
            ["ar",       2, "", ["ajudar", "altar", "jantar", "lunar", "pular", "solar", "usual", "vulgar"]],
            ["er",       2, "", []],
            ["ir",       2, "", ["agir", "dividir", "garantir", "reunir", "sorrir", "surgir"]],
        ];
    }

    // -------------------------------------------------------------------------
    // Step 7 – Vowel removal
    // -------------------------------------------------------------------------
    protected function vowelRules(): array
    {
        return [
            ["bil", 2, "vel", []],
            ["gue", 2, "g",   []],
            ["á",   3, "a",   []],
            ["ê",   3, "e",   []],
            ["ó",   3, "o",   []],
            ["a",   3, "",    []],
            ["e",   3, "",    []],
            ["o",   3, "",    []],
            ["i",   3, "",    []],
        ];
    }
}
