---
sidebar_position: 4
---

# Stemmer

The Stemmer class reduces a Portuguese word to its stem using the RSLP algorithm
(Orengo, V.M. & Huyck, C. (2001), *A Stemming Algorithm for the Portuguese Language*).

It complements Metaphone rather than replacing it: Metaphone normalises how a word *sounds*,
the stemmer normalises its *morphology*, so inflections of the same word share one stem.

## Basic Usage

```php
use ByJG\WordProcess\Portuguese\Stemmer;

$stemmer = new Stemmer();
echo $stemmer->stem('casas');     // cas
echo $stemmer->stem('correndo');  // corr
echo $stemmer->stem('correram');  // corr
echo $stemmer->stem('falou');     // fal
```

The input is trimmed and lowercased (`mb_strtolower`) before the rules are applied.

## How It Works

The steps are applied in this order; within a step, the first matching suffix wins:

1. Plural reduction
2. Feminine reduction
3. Adverb reduction
4. Augmentative/diminutive reduction
5. Noun suffix reduction
6. Verb suffix reduction (only when step 5 made no change)
7. Vowel removal

## Customizing

The constructor accepts two optional arrays, so real corpora can be handled without editing
the rule tables:

```php
$stemmer = new Stemmer(
    protectedWords: ['solar', 'neural', 'docker'],
    extraExceptions: ['ar' => ['nuclear', 'muscular']]
);

echo $stemmer->stem('solar');    // solar  (never stemmed)
echo $stemmer->stem('nuclear');  // nuclear (the "ar" rule skips it)
echo $stemmer->stem('falar');    // fal
```

- `$protectedWords`: words that are never stemmed, for proper nouns, acronyms and domain
  terms. They are compared after lowercasing, so list them in lowercase.
- `$extraExceptions`: extra exceptions per suffix, keyed by the suffix of the rule, merged with
  the built-in ones.

## Method Reference

### `stem(string $word): string`

Returns the stem of a single word.
