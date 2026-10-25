---
sidebar_key: fonemabr
tags: [php, text-manipulation, text-classification]
---

# Fonema BR

Busca que entende o português do Brasil: "brasília" e "brazilia" viram a mesma chave, apesar dos erros de ortografia.

[![Sponsor](https://img.shields.io/badge/Sponsor-%23ea4aaa?logo=githubsponsors&logoColor=white&labelColor=0d1117)](https://github.com/sponsors/byjg)
[![Build Status](https://github.com/byjg/php-fonemabr/actions/workflows/phpunit.yml/badge.svg?branch=master)](https://github.com/byjg/php-fonemabr/actions/workflows/phpunit.yml)
[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg-success.svg)](https://opensource.byjg.com)
[![Install MCP Server](https://img.shields.io/badge/Install-MCP_Server-8A2BE2?logo=modelcontextprotocol&logoColor=white)](https://opensource.byjg.com/docs/ai/mcpserver-byjg-docs/)
[![GitHub source](https://img.shields.io/badge/Github-source-informational?logo=github)](https://github.com/byjg/php-fonemabr/)
[![GitHub license](https://img.shields.io/github/license/byjg/php-fonemabr.svg)](https://opensource.byjg.com/license/)
[![GitHub release](https://img.shields.io/github/release/byjg/php-fonemabr.svg)](https://github.com/byjg/php-fonemabr/releases/)

**Observação**: Apesar do nome "Fonema" a classe não é uma representação fiel dos fonemas brasileiros sendo
apenas uma simplificação.

*Nem todas as situações foram testadas. Caso encontre alguma divergência, por favor, sinta-se à vontade para
fazer um pull request*

## Por quê

O `soundex()` e o `metaphone()` do PHP foram feitos para o inglês. No português, "brasília",
"brazilia" e "brasil" deveriam ser encontrados juntos, e não são: cada erro de ortografia ou
de acento vira uma busca sem resultado.

O Fonema BR aplica as regras do português brasileiro. Grave a chave junto com o texto e
busque pela chave:

```php
(new Metaphone())->convert('brasília');   // BRAZILIA
(new Metaphone())->convert('brazilia');   // BRAZILIA
Soundex::process('brasil');               // B625
```

Também inclui um stemmer, para buscar "correndo" e "corrida" pela mesma raiz.

## Exemplos

### Metafone

```php
$metaphone = new \ByJG\WordProcess\Portuguese\Metaphone();

echo $metaphone->convert('brasília'); // Output: BRAZILIA
echo $metaphone->convert('brazilia'); // Output: BRAZILIA
```

[More details](docs/metaphone.md)

### Soundex

```php
use ByJG\WordProcess\Portuguese\Soundex;

echo Soundex::process('brasília'); // Output: B625
echo Soundex::process('brazilia'); // Output: B625
echo Soundex::process('brasil');   // Output: B625
```

[More details](docs/soundex.md)

### Stemmer

```php
use ByJG\WordProcess\Portuguese\Stemmer;

$stemmer = new Stemmer();
echo $stemmer->stem('correndo'); // Output: corr
echo $stemmer->stem('correram'); // Output: corr
```

[More details](docs/stemmer.md)

## Use Cases

Uma possível utilização é criar um segundo campo no banco de dados no qual o fonema será armazenado.
Sempre que salvar a palavra original você também salva a palavra com fonema.

Dessa forma você poderá pesquisar tanto a palavra original quanto a palavra simplifica com o Fonema.

[More details and examples](docs/use-cases.md)

## Installation

```bash
composer require byjg/fonemabr
```

[More details](docs/install.md)

## Running Tests

```bash
vendor/bin/phpunit
```

----
[Open source ByJG](https://opensource.byjg.com)
