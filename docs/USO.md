# Guia de uso

O pacote aceita CNPJ em duas representacoes:

- 14 caracteres sem mascara, com 12 posicoes alfanumericas e 2 digitos verificadores.
- Mascara exata `AA.AAA.AAA/AAAA-DV`.

```php
use Elavora\Api\DataTypes\Brazil\Cnpj;

$cnpj = Cnpj::from('12.ABC.345/01DE-35');

echo $cnpj->value(); // 12ABC34501DE35
```

Letras minusculas sao aceitas e normalizadas para maiusculas. Espacos, texto adicional, caracteres nao ASCII, mascaras parciais e valores que nao sejam `string` sao rejeitados.

Para verificar uma entrada sem criar uma instancia:

```php
if (Cnpj::isValid($entrada)) {
    $cnpj = Cnpj::from($entrada);
}
```

## Validacao do pacote

Execute os comandos a partir da raiz do clone:

```bash
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer update --no-interaction --no-progress --prefer-dist
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer check
```
