# Fonema BR

[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg.com-brightgreen.svg)](http://opensource.byjg.com)
[![Build Status](https://travis-ci.com/byjg/fonemabr.svg?branch=master)](https://travis-ci.com/byjg/fonemabr)

O Fonema BR tem por objetivo criar uma simplificação de palavras de tal forma que erros de ortografia e
vogais não interfiram na busca. Dessa forma, é possível criar sistemas de buscas mais aproximados com o 
brasileiro e aumentar a assertividade da busca.

**Observação**: Apesar do nome "Fonema" a classe não é uma representação fiel dos fonemas brasileiros sendo
apenas uma simplificação.

*Nem todas as situações foram testadas. Caso encontre alguma divergência, por favor, sinta-se à vontade para
fazer um pull request*

## Exemplos

### Metafone

```php
$metaphone = new \ByJG\WordProcess\Portuguese\Metaphone();

echo $metaphone->convert('brasília');
echo $metaphone->convert('brazilia');
```

### Soundex

```php
$soundex = new \ByJG\WordProcess\Portuguese\Soundex();
echo $soundex->process('brasília');
echo $soundex->process('brazilia');
echo $soundex->process('brasil');
```

## Sugestão de usos:

Uma possível utilização é criar um segundo campo no banco de dados no qual o fonema será armazenado. 
Sempre que salvar a palavra original você também salva a palavra com fonema.

Dessa forma você poderá pesquisar tanto a palavra original quanto a palavra simplifica com o Fonema.

