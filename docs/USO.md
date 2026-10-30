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

$valor = new Cnpj('12345678000195');
$normalizado = $valor->value();
```

## Versão e compatibilidade

| Item | Valor |
|---|---|
| Última versão publicada | `0.2.0` (tag legada `v0.2.0`) |
| PHP | `>=8.1` |
| `elavora/api-datatype-core` | `^0.1` |

As versões seguintes saem do fluxo central de release: veja [Versões e release](AUTOMACAO.md#versões-e-release).
Estas restrições não mudam na adoção do pipeline central. O suporte ao framework 2 e ao PHP 8.3 vem em outro PR.

### `composer.json`

O `composer.json` do pacote deve ter:

- `require.php` = `">=8.1"` e `require.elavora/api-datatype-core` = `"^0.1"` (sem mudança)
- `autoload.psr-4` = `"Elavora\\Api\\DataTypes\\Brazil\\": "src/"` (sem mudança)
- `require-dev.phpunit/phpunit` = `"^10.5 || ^11.0 || ^12.0"` (sem mudança)
- `scripts.test` = `"phpunit -c phpunit.xml"` (sem mudança)
- `require-dev`, `scripts` e `config` de qualidade (`elavora/code-standards`, PHPStan, `lint`, `analyse`, `check`):
  veja [`composer.json` em Automação](AUTOMACAO.md#composerjson)
- nenhum `repositories` (nem `path`, nem `vcs`)
- `composer validate --strict --no-check-publish` sem erros.

## Validação do CNPJ

`Elavora\Api\DataTypes\Brazil\Cnpj::isValid(mixed $value): bool` não lança exceção. O valor é convertido em texto e
tudo o que não é dígito é removido. Ele é válido quando sobram exatamente 14 dígitos, não são todos iguais e os dois
últimos são os dígitos verificadores corretos (módulo 11). A normalização guarda só os dígitos.

| Entrada | `isValid` | Valor normalizado |
|---|---|---|
| `'12345678000195'` | `true` | `12345678000195` |
| `'12.345.678/0001-95'` | `true` | `12345678000195` |
| `'12345678000196'` | `false` | |
| `'11111111111111'` | `false` | |
| `'1234567800019'` (13 dígitos) | `false` | |
| `''` | `false` | |
## Principais pontos de entrada

- `Elavora\Api\DataTypes\Brazil\Cnpj`

## Dependencias de runtime

- `elavora/api-datatype-core` `^0.1`

## Validação do pacote

O passo a passo para rodar lint, PHPStan e testes em container está em [Desenvolvimento e testes](DESENVOLVIMENTO.md). Na aplicação consumidora, rode os testes dela depois de instalar o pacote.

## Observacoes

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensoes no bootstrap da aplicacao.
- Instale apenas os modulos que a aplicacao realmente usa.