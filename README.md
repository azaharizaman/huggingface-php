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
  - [Text Generation](#text-generation)
  - [Fill Mask](#fill-mask)
  - [Summarization](#summarization)
  - [Sentiment Analysis](#sentiment-analysis)
  - [Emotion Classification](#emotion-classification)
- [Advanced Usage](#advanced-usage)
  - [Custom Configuration](#custom-configuration)
  - [Error Handling](#error-handling)
- [Popular Models](#popular-models)
- [Testing](#testing)


## Get Started

> **Requires [PHP 8.2+](https://php.net/releases/)**

First, install Huggingface PHP via the [Composer](https://getcomposer.org/) package manager:

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

### Text Generation

Generate creative text continuations using various language models:

```php
use AzahariZaman\Huggingface\Huggingface;
use AzahariZaman\Huggingface\Enums\Type;

$client = Huggingface::client(getenv('HUGGINGFACE_API_KEY'));

// Using GPT-2
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The future of artificial intelligence is',
    'type' => Type::TEXT_GENERATION,
]);

echo $result['generated_text'] . "\n";
// Output: "The future of artificial intelligence is bright and full of possibilities..."

// Using Microsoft DialoGPT for conversational AI
$result = $client->inference()->create([
    'model' => 'microsoft/DialoGPT-medium',
    'inputs' => 'Hello, how are you today?',
    'type' => Type::TEXT_GENERATION,
]);

echo $result['generated_text'] . "\n";

// Using CodeT5 for code generation
$result = $client->inference()->create([
    'model' => 'Salesforce/codet5-base',
    'inputs' => 'def fibonacci(n):',
    'type' => Type::TEXT_GENERATION,
]);

echo $result['generated_text'] . "\n";
```

### Fill Mask

Predict missing words in sentences using masked language models:

```php
// Using BERT for general text
$result = $client->inference()->create([
    'model' => 'bert-base-uncased',
    'inputs' => 'The capital of France is [MASK].',
    'type' => Type::FILL_MASK,
]);

foreach ($result['filled_masks'] as $prediction) {
    echo "Token: {$prediction['token_str']}, Score: {$prediction['score']}\n";
    echo "Full sequence: {$prediction['sequence']}\n\n";
}

// Using RoBERTa for more accurate predictions
$result = $client->inference()->create([
    'model' => 'roberta-base',
    'inputs' => 'The best programming language for web development is <mask>.',
    'type' => Type::FILL_MASK,
]);

// Using domain-specific models like BioBERT for medical text
$result = $client->inference()->create([
    'model' => 'dmis-lab/biobert-base-cased-v1.1',
    'inputs' => 'The patient was diagnosed with [MASK] diabetes.',
    'type' => Type::FILL_MASK,
]);
```

### Summarization

Create concise summaries of longer texts:

```php
// Using BART for general summarization
$longText = "Artificial intelligence (AI) is intelligence demonstrated by machines, " .
           "in contrast to the natural intelligence displayed by humans and animals. " .
           "Leading AI textbooks define the field as the study of 'intelligent agents': " .
           "any device that perceives its environment and takes actions that maximize " .
           "its chance of successfully achieving its goals.";

$result = $client->inference()->create([
    'model' => 'facebook/bart-large-cnn',
    'inputs' => $longText,
    'type' => Type::SUMMARIZATION,
]);

echo "Summary: " . $result['summary_text'] . "\n";

// Using T5 for more flexible summarization
$result = $client->inference()->create([
    'model' => 't5-base',
    'inputs' => 'summarize: ' . $longText,
    'type' => Type::SUMMARIZATION,
]);

// Using Pegasus for news article summarization
$newsArticle = "The latest research in quantum computing shows promising results...";
$result = $client->inference()->create([
    'model' => 'google/pegasus-xsum',
    'inputs' => $newsArticle,
    'type' => Type::SUMMARIZATION,
]);
```

### Sentiment Analysis

Analyze the emotional tone and sentiment of text:

```php
// Basic sentiment analysis
$result = $client->inference()->create([
    'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
    'inputs' => 'I absolutely love this new product! It works perfectly.',
    'type' => Type::SENTIMENT_ANALYSIS,
]);

foreach ($result['sentiment_analysis'] as $sentiment) {
    echo "Label: {$sentiment['label']}, Score: {$sentiment['score']}\n";
}

// Multi-language sentiment analysis
$result = $client->inference()->create([
    'model' => 'nlptown/bert-base-multilingual-uncased-sentiment',
    'inputs' => 'Este producto es increíble, me encanta!',
    'type' => Type::SENTIMENT_ANALYSIS,
]);

// Financial sentiment analysis
$result = $client->inference()->create([
    'model' => 'ProsusAI/finbert',
    'inputs' => 'The company reported strong quarterly earnings with revenue growth of 15%.',
    'type' => Type::SENTIMENT_ANALYSIS,
]);

// Customer review sentiment
$result = $client->inference()->create([
    'model' => 'cardiffnlp/twitter-roberta-base-sentiment-latest',
    'inputs' => 'Just tried the new restaurant downtown. Food was amazing but service was slow.',
    'type' => Type::SENTIMENT_ANALYSIS,
]);
```

### Emotion Classification

Detect specific emotions in text with fine-grained analysis:

```php
// Detect multiple emotions
$result = $client->inference()->create([
    'model' => 'SamLowe/roberta-base-go_emotions',
    'inputs' => 'I am so excited about the upcoming vacation! Can\'t wait to relax on the beach.',
    'type' => Type::EMOTION_CLASSIFICATION,
]);

foreach ($result['emotion_classification'] as $emotions) {
    foreach ($emotions as $emotion => $score) {
        echo "Emotion: {$emotion}, Intensity: " . number_format($score, 3) . "\n";
    }
}

// Emotional analysis for customer support
$result = $client->inference()->create([
    'model' => 'j-hartmann/emotion-english-distilroberta-base',
    'inputs' => 'I am frustrated with this service. Nothing works as expected and support is unhelpful.',
    'type' => Type::EMOTION_CLASSIFICATION,
]);

// Detect emotions in social media posts
$result = $client->inference()->create([
    'model' => 'cardiffnlp/twitter-roberta-base-emotion',
    'inputs' => 'Watching the sunset with my loved ones. Feeling grateful for these precious moments.',
    'type' => Type::EMOTION_CLASSIFICATION,
]);
```

## Advanced Usage

### Custom Configuration

Customize the client with advanced options:

```php
use AzahariZaman\Huggingface\Huggingface;

// Using the factory for advanced configuration
$client = Huggingface::factory()
    ->withApiKey(getenv('HUGGINGFACE_API_KEY'))
    ->withBaseUri('https://api-inference.huggingface.co')
    ->withHttpHeader('User-Agent', 'MyApp/1.0')
    ->withQueryParam('wait_for_model', 'true')
    ->make();

// Custom HTTP client configuration
$httpClient = new \GuzzleHttp\Client([
    'timeout' => 30,
    'connect_timeout' => 10,
]);

$client = Huggingface::factory()
    ->withApiKey(getenv('HUGGINGFACE_API_KEY'))
    ->withHttpClient($httpClient)
    ->make();

// Stream handling for real-time responses
$streamHandler = function ($request) use ($httpClient) {
    return $httpClient->send($request, ['stream' => true]);
};

$client = Huggingface::factory()
    ->withApiKey(getenv('HUGGINGFACE_API_KEY'))
    ->withStreamHandler($streamHandler)
    ->make();
```

### Error Handling

Handle various types of errors gracefully:

```php
use AzahariZaman\Huggingface\Exceptions\ErrorException;
use AzahariZaman\Huggingface\Exceptions\TransporterException;
use AzahariZaman\Huggingface\Exceptions\UnserializableResponse;

try {
    $result = $client->inference()->create([
        'model' => 'invalid-model-name',
        'inputs' => 'Test input',
        'type' => Type::TEXT_GENERATION,
    ]);
} catch (ErrorException $e) {
    // Handle API errors (model not found, invalid input, etc.)
    echo "API Error: " . $e->getMessage() . "\n";
    echo "Error Type: " . $e->getErrorType() . "\n";
    echo "Error Code: " . $e->getErrorCode() . "\n";
} catch (TransporterException $e) {
    // Handle network/HTTP errors
    echo "Network Error: " . $e->getMessage() . "\n";
} catch (UnserializableResponse $e) {
    // Handle response parsing errors
    echo "Response Error: " . $e->getMessage() . "\n";
}
```

## Popular Models

Here are some popular models for different tasks:

### Text Generation
- **GPT-2**: `gpt2`, `gpt2-medium`, `gpt2-large`, `gpt2-xl`
- **GPT-Neo**: `EleutherAI/gpt-neo-1.3B`, `EleutherAI/gpt-neo-2.7B`
- **DialoGPT**: `microsoft/DialoGPT-medium`, `microsoft/DialoGPT-large`
- **CodeT5**: `Salesforce/codet5-base`, `Salesforce/codet5-large`

### Fill Mask
- **BERT**: `bert-base-uncased`, `bert-large-uncased`
- **RoBERTa**: `roberta-base`, `roberta-large`
- **DeBERTa**: `microsoft/deberta-base`, `microsoft/deberta-large`
- **ALBERT**: `albert-base-v2`, `albert-large-v2`

### Summarization
- **BART**: `facebook/bart-large-cnn`, `facebook/bart-large-xsum`
- **T5**: `t5-small`, `t5-base`, `t5-large`
- **Pegasus**: `google/pegasus-xsum`, `google/pegasus-cnn_dailymail`
- **LED**: `allenai/led-base-16384`

### Sentiment Analysis
- **DistilBERT**: `distilbert-base-uncased-finetuned-sst-2-english`
- **RoBERTa**: `cardiffnlp/twitter-roberta-base-sentiment-latest`
- **FinBERT**: `ProsusAI/finbert` (Financial sentiment)
- **Multilingual**: `nlptown/bert-base-multilingual-uncased-sentiment`

### Emotion Classification
- **GoEmotions**: `SamLowe/roberta-base-go_emotions`
- **Emotion**: `j-hartmann/emotion-english-distilroberta-base`
- **Twitter Emotion**: `cardiffnlp/twitter-roberta-base-emotion`

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
- **Lines: 100%** (249/249)
- **Methods: 100%** (80/80) 
- **Classes: 100%** (21/21)

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

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

This library was inspired at the source level by the PHP OpenAI client and Kambo-1st/Huggingface-php. Portions of the code have been directly copied from these outstanding libraries.

---

Huggingface PHP is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
