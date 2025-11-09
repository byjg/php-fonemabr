---
sidebar_position: 4
---

# Use Cases

This page provides practical examples of how to implement Fonema BR in real-world applications.

## Database Implementation

A common approach is to create an additional field in your database to store the phonetic representation. This enables fast phonetic searches alongside exact searches.

### Database Schema Example

```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    name_phonetic VARCHAR(255),
    name_soundex VARCHAR(4),
    -- other fields...
    INDEX idx_name (name),
    INDEX idx_phonetic (name_phonetic),
    INDEX idx_soundex (name_soundex)
);
```

### Storing Phonetic Data

```php
use ByJG\WordProcess\Portuguese\Metaphone;
use ByJG\WordProcess\Portuguese\Soundex;

function saveProduct(PDO $db, string $name, float $price): void
{
    $metaphone = new Metaphone();

    $sql = "INSERT INTO products (name, name_phonetic, name_soundex, price)
            VALUES (:name, :phonetic, :soundex, :price)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'phonetic' => $metaphone->convert($name),
        'soundex' => Soundex::process($name),
        'price' => $price
    ]);
}

// Usage
saveProduct($db, 'melancia', 15.99);
```

### Searching with Phonetic Data

```php
function searchProducts(PDO $db, string $query): array
{
    $metaphone = new Metaphone();
    $soundex = Soundex::process($query);
    $phoneticQuery = $metaphone->convert($query);

    $sql = "SELECT * FROM products
            WHERE name LIKE :exact
               OR name_phonetic = :phonetic
               OR name_soundex = :soundex";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'exact' => "%{$query}%",
        'phonetic' => $phoneticQuery,
        'soundex' => $soundex
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Will find "melancia", "melansia", "melanssia", etc.
$results = searchProducts($db, 'melancia');
```

## Multi-tier Search Strategy

Implement a search that tries multiple strategies for better user experience:

```php
use ByJG\WordProcess\Portuguese\Metaphone;
use ByJG\WordProcess\Portuguese\Soundex;

class ProductSearch
{
    private PDO $db;
    private Metaphone $metaphone;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->metaphone = new Metaphone();
    }

    public function search(string $query): array
    {
        // 1. Try exact match first
        $results = $this->exactSearch($query);
        if (!empty($results)) {
            return $results;
        }

        // 2. Try partial match
        $results = $this->partialSearch($query);
        if (!empty($results)) {
            return $results;
        }

        // 3. Try phonetic match
        $results = $this->phoneticSearch($query);
        if (!empty($results)) {
            return $results;
        }

        // 4. Try Soundex match (most permissive)
        return $this->soundexSearch($query);
    }

    private function exactSearch(string $query): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name = :query");
        $stmt->execute(['query' => $query]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function partialSearch(string $query): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name LIKE :query");
        $stmt->execute(['query' => "%{$query}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function phoneticSearch(string $query): array
    {
        $phonetic = $this->metaphone->convert($query);
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name_phonetic = :phonetic");
        $stmt->execute(['phonetic' => $phonetic]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function soundexSearch(string $query): array
    {
        $soundex = Soundex::process($query);
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name_soundex = :soundex");
        $stmt->execute(['soundex' => $soundex]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

## Duplicate Detection

Find potential duplicates in your database:

```php
use ByJG\WordProcess\Portuguese\Soundex;

function findPotentialDuplicates(PDO $db, string $tableName, string $fieldName): array
{
    $sql = "SELECT {$fieldName}, COUNT(*) as count
            FROM (
                SELECT {$fieldName},
                       -- Assuming you have a soundex column
                       {$fieldName}_soundex
                FROM {$tableName}
            ) grouped
            GROUP BY {$fieldName}_soundex
            HAVING count > 1";

    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Find potential duplicate customer names
$duplicates = findPotentialDuplicates($db, 'customers', 'name');
```

## Name Matching

Match user input against a list of known names:

```php
use ByJG\WordProcess\Portuguese\Soundex;

class NameMatcher
{
    private array $knownNames = [];
    private array $soundexIndex = [];

    public function addName(string $name): void
    {
        $this->knownNames[] = $name;
        $soundex = Soundex::process($name);

        if (!isset($this->soundexIndex[$soundex])) {
            $this->soundexIndex[$soundex] = [];
        }
        $this->soundexIndex[$soundex][] = $name;
    }

    public function findMatches(string $input): array
    {
        $soundex = Soundex::process($input);
        return $this->soundexIndex[$soundex] ?? [];
    }
}

// Usage
$matcher = new NameMatcher();
$matcher->addName('Michael Jackson');
$matcher->addName('Mychael Jakson');
$matcher->addName('Maichael Jackson');

// All variations will match
$matches = $matcher->findMatches('Michael Jackson');
// Returns: ['Michael Jackson', 'Mychael Jakson', 'Maichael Jackson']
```

## Autocomplete with Phonetic Support

Enhance autocomplete to handle spelling mistakes:

```php
use ByJG\WordProcess\Portuguese\Metaphone;

class PhoneticAutocomplete
{
    private PDO $db;
    private Metaphone $metaphone;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->metaphone = new Metaphone();
    }

    public function getSuggestions(string $input, int $limit = 10): array
    {
        $phonetic = $this->metaphone->convert($input);

        $sql = "SELECT DISTINCT name
                FROM products
                WHERE name LIKE :input
                   OR name_phonetic LIKE :phonetic
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'input' => $input . '%',
            'phonetic' => $phonetic . '%',
            'limit' => $limit
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

// Usage
$autocomplete = new PhoneticAutocomplete($db);

// User types "keijo" instead of "queijo"
$suggestions = $autocomplete->getSuggestions('keijo');
// Will return: ['queijo', 'queijo minas', 'queijo prato', ...]
```

## Full-Text Search Integration

Combine with full-text search for better results:

```php
use ByJG\WordProcess\Portuguese\Metaphone;

function hybridSearch(PDO $db, string $query): array
{
    $metaphone = new Metaphone();
    $phonetic = $metaphone->convert($query);

    // MySQL full-text search example
    $sql = "SELECT *,
            MATCH(name, description) AGAINST(:query) as relevance_exact,
            IF(name_phonetic = :phonetic, 10, 0) as relevance_phonetic
            FROM products
            WHERE MATCH(name, description) AGAINST(:query)
               OR name_phonetic = :phonetic
            ORDER BY (relevance_exact + relevance_phonetic) DESC
            LIMIT 20";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'query' => $query,
        'phonetic' => $phonetic
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

## Caching Phonetic Conversions

For high-performance applications, cache phonetic conversions:

```php
use ByJG\WordProcess\Portuguese\Metaphone;

class CachedPhonetic
{
    private Metaphone $metaphone;
    private array $cache = [];

    public function __construct()
    {
        $this->metaphone = new Metaphone();
    }

    public function convert(string $text): string
    {
        $key = strtolower($text);

        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->metaphone->convert($text);
        }

        return $this->cache[$key];
    }

    public function clearCache(): void
    {
        $this->cache = [];
    }
}
```

## Best Practices

### 1. Index Your Phonetic Fields

Always create indexes on phonetic columns for better performance:

```sql
CREATE INDEX idx_name_phonetic ON products(name_phonetic);
CREATE INDEX idx_name_soundex ON products(name_soundex);
```

### 2. Update Phonetic Data with Triggers

Keep phonetic data synchronized automatically:

```sql
DELIMITER //
CREATE TRIGGER products_before_insert
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    -- You'll need to call PHP/application layer for actual conversion
    -- This is a placeholder showing the concept
    SET NEW.name_phonetic = UPPER(NEW.name);
END//
DELIMITER ;
```

:::tip
Since MySQL doesn't have built-in Metaphone for Portuguese, update phonetic fields in your application layer before saving.
:::

### 3. Choose the Right Algorithm

- **Use Metaphone** for: User-facing search, autocomplete, detailed matching
- **Use Soundex** for: Duplicate detection, grouping, indexed lookups, name matching

### 4. Combine with Other Strategies

Don't rely solely on phonetic matching. Combine with:
- Levenshtein distance for typo tolerance
- Full-text search for content matching
- Exact and partial matching for precision
