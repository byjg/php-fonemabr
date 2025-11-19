---
sidebar_position: 1
---

# Installation

## Requirements

- PHP 8.1 or higher

## Installing via Composer

```bash
composer require byjg/fonemabr
```

## Getting Started

After installation, you can immediately start using the Metaphone and Soundex classes:

```php
<?php
require_once 'vendor/autoload.php';

use ByJG\WordProcess\Portuguese\Metaphone;
use ByJG\WordProcess\Portuguese\Soundex;

// Create a Metaphone instance
$metaphone = new Metaphone();
echo $metaphone->convert('brasília'); // Output: BRAZILIA

// Use Soundex static method
echo Soundex::process('brasília'); // Output: B625
```
