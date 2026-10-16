# Changelog - Version 7.0

> **Status: in development.** This document tracks changes landing on the `7.0` branch.
> Nothing here is released yet, and the contents may still change.

## Breaking Changes

- None.

## New Features

### Portuguese stemmer (RSLP)

`ByJG\WordProcess\Portuguese\Stemmer` implements the RSLP algorithm — Orengo, V.M. & Huyck, C.
(2001), *A Stemming Algorithm for the Portuguese Language* — reducing a word to its stem in seven
ordered steps: plural, feminine, adverb, augmentative/diminutive, noun suffix, verb suffix, and
vowel removal.

```php
$stemmer = new Stemmer();
$stemmer->stem('meninas');   // menin
```

Two constructor hooks avoid editing the rule tables for real corpora: `$protectedWords` for terms
that must never be stemmed (proper nouns, acronyms, domain terms), and `$extraExceptions` to extend
the per-suffix exception lists.

This complements `Metaphone` rather than replacing it: Metaphone normalises how a word *sounds*,
the stemmer normalises its *morphology*.

## Bug Fixes

### `^EX` before a vowel produced `ES` instead of `EZ`

The two `^EX` rules are separate entries — `add()` appends the vowel group when `$vogal` is true,
so the keys are `^EX` and `^EX([AEIOU])` — and the first match wins. The unconditional rule was
registered first, so it consumed every `^EX`, and the vowel rule never fired.

| Word | Before | After |
|---|---|---|
| `exame` | `ESAME` | `EZAME` |
| `exemplo` | `ESEMPLO` | `EZEMPLO` |
| `exato` | `ESATO` | `EZATO` |

Portuguese voices *ex* before a vowel (*e-ZA-me*) but not before a consonant, so the specific rule
must be registered before the general one. Words such as `exclarecido` are unaffected.

### Accented vowels outside Latin-1 were dropped

`byjg/convert` 7.0 removes the accent of every Latin letter, not only the Latin-1 ones. Vowels
such as `ẽ` and `ĩ` became `?` and were then removed; they now keep their vowel.

| Input | Before | After |
|---|---|---|
| `ẽ`, `ĩ` | (dropped) | `E`, `I` |

## Requirements

- PHP 8.3, 8.4, 8.5 and 8.6 are now supported: `"php": ">=8.3 <8.7"`.
  The previous `<8.6` upper bound excluded PHP 8.6, since `<8.6` is exclusive.

### ByJG dependencies

- `byjg/convert` is now `^7.0`.

While 7.0 is unreleased these resolve to `7.0.x-dev` from each component's
`7.0` branch, via `minimum-stability: dev` with `prefer-stable: true`.

## Toolchain

- PHPUnit updated to `^12.5`.
- Psalm is installed as `psalm/phar` instead of `vimeo/psalm`.

  `vimeo/psalm` lists the PHP versions it supports and no published release includes
  8.6, so as a dev dependency it made `composer install` fail on the 8.6 build job
  before any test ran. `psalm/phar` requires only `php ^8.2` and bundles its own
  dependencies, so it installs on every PHP version in the matrix and cannot conflict
  with the project's. Psalm itself still refuses to *run* on 8.6, which is why the
  Psalm job uses 8.5. `composer psalm` runs it.

- PHPUnit 13 is deliberately **not** used. It requires PHP `>=8.4.1`, breaking the
  8.3 floor.

## Continuous Integration

- The build matrix now includes PHP 8.6.
- The Psalm job runs on PHP 8.5.

## Housekeeping

- `phpunit.xml.dist` renamed to `phpunit.xml`.
