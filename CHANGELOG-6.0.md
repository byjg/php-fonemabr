# Changelog - Version 6.0

## Overview

Version 6.0 is a major update that modernizes the codebase with support for PHP 8.3-8.5, PHPUnit 11, and Psalm 6, while adding comprehensive documentation and improving code quality standards.

## New Features

### Documentation
- **Comprehensive Docusaurus documentation support**: Added detailed documentation files covering installation, metaphone, soundex, and use cases
  - `docs/install.md`: Installation guide
  - `docs/metaphone.md`: Metaphone usage and examples
  - `docs/soundex.md`: Soundex usage and examples
  - `docs/use-cases.md`: Practical use cases and implementation examples
- **Enhanced README**: Updated with links to detailed documentation and better examples

### Testing & Quality Assurance
- **PHPUnit 11 support**: Updated test suite to be compatible with PHPUnit 10.5 and 11.5
  - Modernized PHPUnit configuration with strict error handling
  - Added detailed test output with `testdox` mode
  - Enabled all warnings, notices, and deprecations to fail tests
- **Enhanced Psalm configuration**:
  - Upgraded error level from 4 to 3 for stricter analysis
  - Support for Psalm 6.13
  - Improved cache configuration

### GitHub Actions
- **Improved CI/CD**: Enhanced PHPUnit workflow configuration for better automated testing

## Breaking Changes

| Before (5.x) | After (6.x) | Description |
|--------------|-------------|-------------|
| PHP >= 8.1 < 8.4 | PHP >= 8.3 < 8.6 | Minimum PHP version increased to 8.3, added support for PHP 8.5 |
| `byjg/convert: ^5.0` | `byjg/convert: ^6.0` | Updated to version 6.0 of the convert dependency |
| `phpunit/phpunit: ^9.6` | `phpunit/phpunit: ^10.5\|^11.5` | Updated to PHPUnit 10.5 or 11.5 for modern testing |
| `vimeo/psalm: ^5.9` | `vimeo/psalm: ^5.9\|^6.13` | Added support for Psalm 6.13 |
| PHPUnit `@dataProvider` docblock | PHPUnit `#[DataProvider]` attribute | Migrated to PHP 8 attributes |
| Data provider instance methods | Data provider static methods | Data providers must now be static methods |
| PHPUnit `whitelist` filter | PHPUnit `source` coverage | Updated code coverage configuration syntax |
| Psalm error level 4 | Psalm error level 3 | Stricter static analysis requirements |

## Bug Fixes

- **Psalm static analysis errors**: Fixed null coalescing issues in `Rules.php`
  - Added null coalescing operator for `FromUTF8::onlyAscii()` return value (line 78)
  - Added null coalescing operator for `preg_replace()` return value (line 101)
- **PHPUnit 11 compatibility**: Migrated test annotations to PHP 8 attributes
  - Changed `@dataProvider` docblock annotations to `#[DataProvider]` attributes
  - Updated data provider methods to be static
  - Removed redundant docblock comments from test methods

## Configuration Updates

### PHPUnit Configuration (`phpunit.xml.dist`)
- Added XML schema validation
- Enabled colored output (`colors="true"`)
- Enabled test documentation output (`testdox="true"`)
- Added strict error handling:
  - `failOnWarning="true"`
  - `failOnNotice="true"`
  - `failOnDeprecation="true"`
  - `failOnPhpunitDeprecation="true"`
- Display details on all types of issues (deprecations, errors, notices, warnings)
- Updated coverage configuration from `filter/whitelist` to `source/include`

### Psalm Configuration (`psalm.xml`)
- Increased strictness from error level 4 to 3
- Added cache directory configuration (`/tmp/psalm`)
- Removed tests directory from analysis (focus on source code only)

### Composer Scripts
Added convenience scripts:
```json
"scripts": {
    "test": "vendor/bin/phpunit",
    "psalm": "vendor/bin/psalm --threads=1"
}
```

## Path to Upgrade from 5.x to 6.x

### Step 1: Update Dependencies
```bash
# Update composer.json requirements
composer require "php:>=8.3 <8.6"
composer require "byjg/convert:^6.0"
composer require --dev "phpunit/phpunit:^10.5|^11.5"
composer require --dev "vimeo/psalm:^5.9|^6.13"

# Or simply update composer.json and run
composer update
```

### Step 2: Update PHPUnit Configuration
1. Replace your `phpunit.xml.dist` with the new format
2. Change `<filter><whitelist>` to `<source><include>`
3. Add strict error handling flags if desired

### Step 3: Update Test Files
1. Convert all `@dataProvider` annotations to `#[DataProvider('methodName')]` attributes
2. Make all data provider methods static:
   ```php
   // Before
   public function dataProviderExample(): array

   // After
   public static function dataProviderExample(): array
   ```
3. Remove redundant docblocks that only contain `@dataProvider` and parameter type hints

### Step 4: Update Psalm Configuration
1. Update `psalm.xml` to use error level 3 (or keep level 4 if needed)
2. Add cache directory configuration
3. Consider removing tests from analysis

### Step 5: Fix Null Safety Issues
Review your code for potential null return values, especially:
- `preg_replace()` calls - add `?? $fallback` where appropriate
- `FromUTF8::onlyAscii()` and similar method calls

### Step 6: Run Tests and Static Analysis
```bash
composer test
composer psalm
```

### Step 7: Verify PHP Version
Ensure your environment is running PHP 8.3 or higher:
```bash
php -v
```

## Notes

- The library maintains backward compatibility in terms of public API
- All public methods and their signatures remain unchanged
- The main breaking changes are in dependencies and development tooling
- No changes to the core metaphone and soundex algorithms
