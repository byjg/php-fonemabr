# Fonema BR

O Fonema BR tem por objetivo criar uma simplificação de palavras de tal forma que erros de ortografia e
vogais não interfiram na busca. Dessa forma, é possível criar sistemas de buscas mais aproximados com o 
brasileiro e aumentar a assertividade da busca.

**Observação**: Apesar do nome "Fonema" a classe não é uma representação fiel dos fonemas brasileiros sendo
apenas uma simplificação.

*Nem todas as situações foram testadas. Caso encontre alguma divergência, por favor, sinta-se à vontade para
fazer um pull request*

## Exemplos

```php
$fonema = new \ByJG\FonemaBR();

// Abaixo, será produzido o mesmo resultado: BRZL
$fonema->converte('brasília');
$fonema->converte('brazilia');
$fonema->converte('brasil');
```

## Sugestão de usos:

Uma possível utilização é criar um segundo campo no banco de dados no qual o fonema será armazenado. 
Assim sempre que salvar a palavra original você também salva a palavra com fonema. 

Assim, você poderá pesquisar tanto a palavra original quanto a palavra simplifica com o Fonema. 
