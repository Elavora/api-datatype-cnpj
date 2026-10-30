# Guia de uso

DataType CNPJ para aplicacoes Elavora API.

## Instalacao

```bash
composer require elavora/api-datatype-cnpj
```

## Quando usar

- Validar e normalizar valores antes de chegar na regra de negocio.
- Evitar passar strings soltas entre services, DTOs e persistencia.
- Reutilizar a mesma validacao em controllers, comandos e testes.

## Exemplo rapido

```php
use Elavora\Api\DataTypes\Brazil\Cnpj;

$cnpj = Cnpj::from('12.345.678/0001-95');

$cnpj->value();                  // '12345678000195'
(string) $cnpj;                  // '12345678000195'
json_encode($cnpj);              // '"12345678000195"'
Cnpj::isValid('12345678000195');  // true
```

O construtor não é público: crie sempre por `Cnpj::from()`.

## Versão e compatibilidade

| Item | Valor |
|---|---|
| Última versão antes do upgrade | `0.2.0` (tag legada `v0.2.0`) |
| Versão do upgrade | `1.0.0` |
| PHP | `>=8.3` |
| `elavora/api-datatype-core` | `^2.0` |

As versões seguintes saem do fluxo central de release: veja [Versões e release](AUTOMACAO.md#versões-e-release).
A versão `1.0.0` atualiza o pacote para o `elavora/api-datatype-core` `^2.0` (que traz o framework 2) e exige PHP 8.3
ou superior.

**Quebra de compatibilidade:** no `elavora/api-datatype-core` 2 o construtor de `AbstractDataType` é
`final protected`, então `new Cnpj('12345678000195')` deixa de funcionar fora da própria classe. Troque por
`Cnpj::from('12345678000195')`. `Cnpj::isValid()` e `value()` continuam iguais.

### `composer.json`

O `composer.json` do pacote deve ter:

- `require.php` = `">=8.3"` e `require.elavora/api-datatype-core` = `"^2.0"`
- `extra.branch-alias.dev-main` = `"1.0-dev"`
- `autoload.psr-4` = `"Elavora\\Api\\DataTypes\\Brazil\\": "src/"` (sem mudança)
- `require-dev.phpunit/phpunit` = `"^10.5 || ^11.0 || ^12.0"` (sem mudança)
- `scripts.test` = `"phpunit -c phpunit.xml"` (sem mudança)
- `require-dev`, `scripts` e `config` de qualidade (`elavora/code-standards`, PHPStan, `lint`, `analyse`, `check`):
  veja [`composer.json` em Automação](AUTOMACAO.md#composerjson)
- nenhum `repositories` (nem `path`, nem `vcs`)
- `composer validate --strict --no-check-publish` sem erros.

## Criação e entrada inválida

`Elavora\Api\DataTypes\Brazil\Cnpj::from(mixed $value): static` valida o valor com `isValid()` e devolve um `Cnpj`
com o valor normalizado (só os 14 dígitos). Se o valor for inválido, lança `\InvalidArgumentException` com a mensagem
`Valor invalido para Elavora\Api\DataTypes\Brazil\Cnpj.`

```php
use Elavora\Api\DataTypes\Brazil\Cnpj;

try {
    Cnpj::from('12345678000196'); // dígito verificador errado
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // Valor invalido para Elavora\Api\DataTypes\Brazil\Cnpj.
}
```

Métodos da instância: `value(): string|int|bool|float|null` (aqui sempre `string`), `jsonSerialize()` (mesmo valor) e
`__toString()`.

## Validação do CNPJ

`Elavora\Api\DataTypes\Brazil\Cnpj::isValid(mixed $value): bool` não lança exceção. O valor é convertido em texto e
tudo o que não é dígito é removido. Ele é válido quando sobram exatamente 14 dígitos, não são todos iguais e os dois
últimos são os dígitos verificadores corretos (módulo 11). A normalização guarda só os dígitos.

| Entrada | `isValid` | `from()` / valor normalizado |
|---|---|---|
| `'12345678000195'` | `true` | `12345678000195` |
| `'12.345.678/0001-95'` | `true` | `12345678000195` |
| `'12345678000196'` | `false` | lança `InvalidArgumentException` |
| `'11111111111111'` | `false` | lança `InvalidArgumentException` |
| `'1234567800019'` (13 dígitos) | `false` | |
| `''` | `false` | lança `InvalidArgumentException` |

## Principais pontos de entrada

- `Elavora\Api\DataTypes\Brazil\Cnpj`

## Dependencias de runtime

- `elavora/api-datatype-core` `^2.0`

## Validação do pacote

O passo a passo para rodar lint, PHPStan e testes em container está em [Desenvolvimento e testes](DESENVOLVIMENTO.md). Na aplicação consumidora, rode os testes dela depois de instalar o pacote.

## Observacoes

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensoes no bootstrap da aplicacao.
- Instale apenas os modulos que a aplicacao realmente usa.
