# api-datatype-cnpj

[![Packagist Version](https://img.shields.io/packagist/v/elavora/api-datatype-cnpj.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cnpj)
[![PHP Version](https://img.shields.io/packagist/php-v/elavora/api-datatype-cnpj.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cnpj)
[![Composer Quality](https://github.com/Elavora/api-datatype-cnpj/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-cnpj/actions/workflows/quality.yml)
[![CodeQL](https://github.com/Elavora/api-datatype-cnpj/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-cnpj/actions/workflows/codeql.yml)
[![License](https://img.shields.io/packagist/l/elavora/api-datatype-cnpj.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-cnpj)

DataType imutavel para validar e normalizar CNPJ numerico ou alfanumerico.

## Requisitos

- PHP 8.3 ou superior.
- Demais requisitos declarados em [`composer.json`](composer.json).

## Instalacao

```bash
composer require elavora/api-datatype-cnpj
```

## Inicio rapido

```php
use Elavora\Api\DataTypes\Brazil\Cnpj;

$valor = Cnpj::from('11.222.333/0001-81');
$normalizado = $valor->value();
```

`$normalizado` contem `11222333000181`. CNPJs alfanumericos sao normalizados para letras maiusculas e sem mascara.

## Documentacao

Consulte o [guia de uso](docs/USO.md) para os formatos aceitos e a validacao local.
