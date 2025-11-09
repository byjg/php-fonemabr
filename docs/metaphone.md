---
sidebar_position: 2
---

# Metaphone

The Metaphone class provides a phonetic simplification of Portuguese words, making it easier to implement approximate search functionality that is resilient to spelling variations and vowel differences.

:::info
Despite the name "Fonema" (phoneme), this class is not a faithful representation of Brazilian phonemes but rather a practical simplification for search purposes.
:::

## Basic Usage

```php
use ByJG\WordProcess\Portuguese\Metaphone;

$metaphone = new Metaphone();
echo $metaphone->convert('brasília'); // Output: BRAZILIA
echo $metaphone->convert('brazilia'); // Output: BRAZILIA
```

Both variations produce the same output, making them searchable as equivalent terms.

## Examples

### Spelling Variations

Words with different spellings but similar pronunciation will generate the same phonetic representation:

```php
$metaphone = new Metaphone();

// Different spellings, same output
echo $metaphone->convert('ambulancia'); // AMBULAMSSIA
echo $metaphone->convert('anbulancia'); // AMBULAMSSIA

// Case insensitive
echo $metaphone->convert('BALA');       // BALA
echo $metaphone->convert('bala');       // BALA

// Accent variations
echo $metaphone->convert('praça');      // PRASSA
echo $metaphone->convert('praças');     // PRASSAZ
```

### Vowel Approximation

The algorithm normalizes vowels and common consonant variations:

```php
$metaphone = new Metaphone();

echo $metaphone->convert('fome');       // FOME
echo $metaphone->convert('fama');       // FAMA

// Multiple repeated letters are simplified
echo $metaphone->convert('ffffffammmmmmmmmma'); // FAMA
```

### Common Portuguese Patterns

The Metaphone handles common Portuguese phonetic patterns:

```php
$metaphone = new Metaphone();

// Nasal sounds
echo $metaphone->convert('pão');        // PAUM
echo $metaphone->convert('pães');       // PAUM
echo $metaphone->convert('avião');      // AVIAUM
echo $metaphone->convert('aviões');     // AVIAUM

// S/Z variations
echo $metaphone->convert('mesa');       // MEZA
echo $metaphone->convert('casa');       // KAZA
echo $metaphone->convert('caça');       // KASSA

// CH/X sounds
echo $metaphone->convert('chave');      // XAVE
echo $metaphone->convert('chuva');      // XUVA
echo $metaphone->convert('mexe');       // MEXE

// Ç cedilla
echo $metaphone->convert('sessão');     // CESSAUM
echo $metaphone->convert('seção');      // CESSAUM
echo $metaphone->convert('facção');     // FAKSSAUM
```

## How It Works

The Metaphone class applies a series of phonetic rules to simplify Portuguese words:

1. **Pre-processing**: Removes accents and normalizes repeated letters
2. **Pattern matching**: Applies Portuguese-specific phonetic rules
3. **Output**: Returns an uppercase simplified representation

The rules handle common Portuguese phonetic patterns including:
- Nasal sounds (ão, ães, ões → AUM)
- Consonant variations (c/ç/s/ss/z)
- Silent letters (h in most positions)
- Compound sounds (ch, lh, nh, qu)

## Method Reference

### `convert(string $text): string`

Converts a Portuguese word or phrase into its phonetic representation.

**Parameters:**
- `$text` (string): The text to convert (single word or phrase)

**Returns:**
- (string): The phonetic representation in uppercase

**Example:**
```php
$metaphone = new Metaphone();
$result = $metaphone->convert('wellington');
echo $result; // Output: UELINTOM
```

### `getRules(): Rules`

Returns the internal Rules object with all phonetic transformation rules. This is primarily for internal use but can be accessed if you need to customize the rules.

**Returns:**
- (Rules): The rules object containing all transformation patterns
