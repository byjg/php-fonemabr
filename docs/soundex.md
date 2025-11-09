---
sidebar_position: 3
---

# Soundex

The Soundex class provides a phonetic hash code for Portuguese words, similar to the classic Soundex algorithm but adapted for Brazilian Portuguese. It generates a 4-character code that represents the phonetic sound of a word.

## Basic Usage

```php
use ByJG\WordProcess\Portuguese\Soundex;

echo Soundex::process('brasília'); // Output: B625
echo Soundex::process('brazilia'); // Output: B625
echo Soundex::process('brasil');   // Output: B625
```

All three variations produce the same Soundex code, making them easily groupable as phonetically similar words.

:::tip
Soundex is particularly useful for indexing and searching when you need to group phonetically similar words together.
:::

## How It Works

The Soundex algorithm:

1. **Applies Metaphone first**: Converts the word to its phonetic representation
2. **Keeps the first letter**: Preserves the initial character
3. **Maps consonants to digits**: Converts similar-sounding consonants to the same number
4. **Generates 4-character code**: Creates a fixed-length code for comparison

### Consonant Mapping

The algorithm maps consonants to digits based on their phonetic similarity:

| Digit | Consonants |
|-------|------------|
| 1 | B, F, P, V |
| 2 | C, G, J, K, Q, S, X, Z |
| 3 | D, T |
| 4 | L |
| 5 | M, N |
| 6 | R |

## Examples

### Brand Names

Different spellings of brand names generate the same Soundex code:

```php
echo Soundex::process('nike');    // Output: N200
echo Soundex::process('niky');    // Output: N200
echo Soundex::process('niki');    // Output: N200
echo Soundex::process('nyke');    // Output: N200
echo Soundex::process('naique'); // Output: N200
```

### Common Words

Words with similar pronunciation share the same code:

```php
// Fruit variations
echo Soundex::process('melancia');  // Output: M452
echo Soundex::process('melansia');  // Output: M452
echo Soundex::process('melanssia'); // Output: M452

// Name variations
echo Soundex::process('Michael');   // Output: M240
echo Soundex::process('Maichael');  // Output: M240
echo Soundex::process('Mychael');   // Output: M240
```

### Real-World Examples

```php
// Family names
echo Soundex::process('Jackson');   // Output: J250
echo Soundex::process('Jacksom');   // Output: J250
echo Soundex::process('Jeckson');   // Output: J250

// Color variations
echo Soundex::process('marrom');    // Output: M650
echo Soundex::process('marron');    // Output: M650
echo Soundex::process('maron');     // Output: M650
echo Soundex::process('marom');     // Output: M650

// Places
echo Soundex::process('wellington'); // Output: U453
echo Soundex::process('uelintom');   // Output: U453

// Common words
echo Soundex::process('casa');      // Output: K200
echo Soundex::process('caça');      // Output: K200
echo Soundex::process('caçar');     // Output: K200
```

## Method Reference

### `process(string $text): string`

Generates a Soundex code for the given Portuguese text.

**Parameters:**
- `$text` (string): The text to process

**Returns:**
- (string): A 4-character Soundex code (1 letter + 3 digits, padded with zeros if needed)

**Example:**
```php
$code = Soundex::process('casa');
echo $code; // Output: K200
```

## Comparison: Soundex vs Metaphone

| Feature | Soundex | Metaphone |
|---------|---------|-----------|
| Output | Fixed 4-character code | Variable-length phonetic representation |
| Use case | Grouping similar words | Full-text approximate search |
| Precision | Less precise, groups more broadly | More precise, maintains more detail |
| Database indexing | Ideal for indexed columns | Better for full-text search |

### When to use Soundex

- **Duplicate detection**: Finding potential duplicates in databases
- **Name matching**: Matching names with different spellings
- **Grouping**: Organizing similar-sounding items together
- **Indexed searches**: When you need fast lookups with database indexes

### When to use Metaphone

- **Full-text search**: When you need to search entire phrases or sentences
- **Detailed matching**: When you need more precision in phonetic matching
- **Display purposes**: When you want to show the phonetic representation
- **Custom rules**: When you need to understand or modify the transformation rules
