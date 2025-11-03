<p align="center">
    <p align="center">
        <a href="https://github.com/azaharizaman/huggingface-php/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/azaharizaman/huggingface-php/tests.yml?branch=main&label=tests&style=round-square"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/azaharizaman/huggingface-php"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="Latest Version" src="https://img.shields.io/packagist/v/azaharizaman/huggingface-php"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="License" src="https://img.shields.io/github/license/azaharizaman/huggingface-php"></a>
    </p>
</p>

------
**Huggingface PHP** is a community-maintained PHP API client that allows you to interact with the [Hugging Face API](https://huggingface.co/inference-api).



## Table of Contents
- [Get Started](#get-started)
- [Usage](#usage)
    - [Inferring](#Inferring)
- [Testing](#testing)


## Get Started

> **Requires [PHP 8.2+](https://php.net/releases/)**

First, install OpenAI via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require azaharizaman/huggingface-php
```

Ensure that the `php-http/discovery` composer plugin is allowed to run or install a client manually if your project does not already have a PSR-18 client integrated.
```bash
composer require guzzlehttp/guzzle
```

Then, interact with Hugging Face's API:

```php
use AzahariZaman\Huggingface\Huggingface;
use AzahariZaman\Huggingface\Enums\Type;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
$client = Huggingface::client($yourApiKey);

$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The goal of life is?',
    'type' => Type::TEXT_GENERATION,
]);

echo $result['generated_text']."\n";
```

## Usage

### `Inference` Resource

#### `create`

Execute inference using the chosen model.

```php
$response = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The goal of life is?',
    'type' => Type::TEXT_GENERATION,
]);


$response->toArray(); // ['type' => .., 'generated_text' => ...]
```

## Testing

Huggingface PHP uses PHPUnit for testing. The test suite provides comprehensive coverage of all classes, methods, and lines.

### Running Tests

To run the test suite:

```bash
composer test
```

### Running Tests with Coverage

To generate a code coverage report:

```bash
composer test-coverage
```

This will display coverage statistics in the terminal. The current test suite achieves:
- **Lines: 99.20%** (247/249)
- **Methods: 98.75%** (79/80)
- **Classes: 95.24%** (20/21)

### Running Specific Tests

To run a specific test file:

```bash
vendor/bin/phpunit tests/HuggingfaceTest.php
```

To run tests for a specific class or method:

```bash
vendor/bin/phpunit --filter=testMethodName
```

### Test Structure

The test suite is organized to mirror the source code structure:

```
tests/
├── Core: Huggingface, Factory, Client
├── Resources: Inference
├── Transporters: HttpTransporter  
├── ValueObjects: ApiKey, ResourceUri, BaseUri, Headers, QueryParams, Payload
├── Enums: Type, Method, ContentType
├── Responses: CreateResponse + specialized response types
├── Exceptions: ErrorException, TransporterException, UnserializableResponse
└── Traits: ArrayAccessible
```

## Acknowledge

This library was inspired at the source level by the PHP OpenAI client and Kambo-1st/Huggingface-php. Portions of the code have been directly copied from these outstanding libraries.

---

Huggingface PHP is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
