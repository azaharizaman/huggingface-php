<p align="center">
    <p align="center">
        <a href="https://github.com/azaharizaman/huggingface-php/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/azaharizaman/huggingface-php/tests.yml?branch=main&label=tests&style=round-square"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/azaharizaman/huggingface-php"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="Latest Version" src="https://img.shields.io/packagist/v/azaharizaman/huggingface-php"></a>
        <a href="https://packagist.org/packages/azaharizaman/huggingface-php"><img alt="License" src="https://img.shields.io/github/license/azaharizaman/huggingface-php"></a>
    </p>
</p>

------
**Huggingface PHP** is a community-maintained PHP API client that allows you to interact with the [Hugging Face API](https://huggingface.co/inference-api) and the latest [Chat Completions API](https://huggingface.co/docs/inference-providers).

**✨ NEW**: Now supports OpenAI-compatible Chat Completions API with multiple inference providers!



## Table of Contents
- [Get Started](#get-started)
- [Usage](#usage)
  - [Chat Completions](#chat-completions)
  - [Text Generation](#text-generation)
  - [Fill Mask](#fill-mask)
  - [Summarization](#summarization)
  - [Sentiment Analysis](#sentiment-analysis)
  - [Emotion Classification](#emotion-classification)
  - [Hub API](#hub-api)
- [Advanced Usage](#advanced-usage)
  - [Custom Configuration](#custom-configuration)
  - [Provider Selection](#provider-selection)
  - [Auto Task Type Detection](#auto-task-type-detection)
  - [Error Handling](#error-handling)
- [Supported Task Types](#supported-task-types)
- [Inference Providers](#inference-providers)
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
use AzahariZaman\Huggingface\Enums\Provider;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
$client = Huggingface::client($yourApiKey);

// NEW: Chat Completions API with provider support
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [
        ['role' => 'user', 'content' => 'What is the meaning of life?']
    ],
    'provider' => Provider::SAMBANOVA,
    'max_tokens' => 100,
    'temperature' => 0.7,
]);

echo $result->choices[0]->message->content . "\n";

// Original Inference API (still supported)
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The goal of life is?',
    'type' => Type::TEXT_GENERATION,
]);

echo $result['generated_text']."\n";
```

## Usage

### Chat Completions

The new Chat Completions API provides OpenAI-compatible endpoints with support for multiple inference providers:

```php
use AzahariZaman\Huggingface\Huggingface;
use AzahariZaman\Huggingface\Enums\Provider;

$client = Huggingface::client(getenv('HUGGINGFACE_API_KEY'));

// Basic chat completion
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user', 'content' => 'Explain quantum computing in simple terms.']
    ],
    'max_tokens' => 200,
    'temperature' => 0.7,
]);

echo $result->choices[0]->message->content . "\n";

// With specific provider
$result = $client->chatCompletion()->create([
    'model' => 'deepseek-ai/DeepSeek-V3-0324',
    'messages' => [
        ['role' => 'user', 'content' => 'Write a Python function to calculate fibonacci numbers.']
    ],
    'provider' => Provider::SAMBANOVA,
    'max_tokens' => 300,
]);

// Multi-turn conversation
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [
        ['role' => 'user', 'content' => 'What is machine learning?'],
        ['role' => 'assistant', 'content' => 'Machine learning is a subset of AI...'],
        ['role' => 'user', 'content' => 'Can you give me a practical example?']
    ],
    'provider' => Provider::TOGETHER,
    'temperature' => 0.5,
]);

// Advanced options
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [
        ['role' => 'user', 'content' => 'Generate a JSON object with user information.']
    ],
    'response_format' => [
        'type' => 'json_schema',
        'json_schema' => [
            'name' => 'user',
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string'],
                    'age' => ['type' => 'integer'],
                    'email' => ['type' => 'string']
                ],
                'required' => ['name', 'age']
            ]
        ]
    ],
    'max_tokens' => 150,
]);
```

### Streaming Chat Completions

For real-time applications, use streaming responses:

```php
// Stream responses in real-time
$streamResponse = $client->chatCompletion()->createStream([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [
        ['role' => 'user', 'content' => 'Tell me a story about space exploration.']
    ],
    'provider' => Provider::SAMBANOVA,
    'max_tokens' => 200,
    'temperature' => 0.7,
]);

// Process chunks as they arrive
echo "Streaming response: ";
foreach ($streamResponse->getIterator() as $chunk) {
    if (isset($chunk['choices'][0]['delta']['content'])) {
        echo $chunk['choices'][0]['delta']['content'];
        flush(); // Output immediately for real-time effect
    }
}

// Or collect the complete response
$completeResponse = $streamResponse->collect();
echo $completeResponse->choices[0]->message->content;

// Manual chunk processing with progress tracking
$fullContent = '';
$chunkCount = 0;

foreach ($streamResponse->getIterator() as $chunk) {
    $chunkCount++;
    
    if (isset($chunk['choices'][0]['delta']['content'])) {
        $content = $chunk['choices'][0]['delta']['content'];
        $fullContent .= $content;
        
        // Custom processing per chunk
        echo "Chunk $chunkCount: " . json_encode($content) . "\n";
    }
    
    // Check for completion
    if (isset($chunk['choices'][0]['finish_reason'])) {
        echo "Finished: " . $chunk['choices'][0]['finish_reason'] . "\n";
        break;
    }
}
```

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

### Hub API

Access model metadata, inference status, and provider information:

```php
// Get model information
$modelInfo = $client->hub()->getModel([
    'model_id' => 'meta-llama/Llama-3.1-8B-Instruct',
    'expand' => ['inference', 'inferenceProviderMapping']
]);

echo "Model: " . $modelInfo->id . "\n";
echo "Inference status: " . ($modelInfo->inference ?? 'not available') . "\n";

if ($modelInfo->inferenceProviderMapping) {
    echo "Available providers:\n";
    foreach ($modelInfo->inferenceProviderMapping as $provider => $info) {
        echo "- $provider: " . $info['status'] . " (" . $info['task'] . ")\n";
    }
}

// List models by provider
$models = $client->hub()->listModels([
    'inference_provider' => 'sambanova',
    'pipeline_tag' => 'text-generation',
    'limit' => 10
]);

foreach ($models->models as $model) {
    echo "Model: " . $model->id . "\n";
}

// Get user information
$userInfo = $client->hub()->whoami();
echo "Username: " . $userInfo['name'] . "\n";
```

## Advanced Usage

### Provider Selection

Choose specific inference providers for your requests:

```php
use AzahariZaman\Huggingface\Enums\Provider;

// Using Sambanova for fast inference
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
    'provider' => Provider::SAMBANOVA,
]);

// Using Together AI
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
    'provider' => Provider::TOGETHER,
]);

// Auto-select best provider
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
    'provider' => Provider::AUTO, // or omit provider parameter
]);
```

### Auto Task Type Detection

The client can automatically detect the appropriate task type based on the model:

```php
// Automatic detection - no need to specify type
$result = $client->inference()->create([
    'model' => 'openai/whisper-large-v3',
    'inputs' => $audioData, // Will auto-detect as AUTOMATIC_SPEECH_RECOGNITION
]);

$result = $client->inference()->create([
    'model' => 'stabilityai/stable-diffusion-2',
    'inputs' => 'A beautiful sunset', // Will auto-detect as TEXT_TO_IMAGE
]);

$result = $client->inference()->create([
    'model' => 'sentence-transformers/all-MiniLM-L6-v2',
    'inputs' => ['source' => 'Hello', 'targets' => ['Hi', 'Goodbye']], // Will auto-detect as SENTENCE_SIMILARITY
]);

// You can still override with explicit type
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'Translate this: Hello',
    'type' => Type::TRANSLATION, // Override auto-detection
]);
```

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

## Supported Task Types

The library supports all major Hugging Face inference tasks:

### Traditional NLP Tasks
- **TEXT_GENERATION**: Generate text continuations
- **FILL_MASK**: Fill missing words in sentences  
- **SUMMARIZATION**: Create text summaries
- **SENTIMENT_ANALYSIS**: Analyze emotional tone
- **EMOTION_CLASSIFICATION**: Detect specific emotions
- **TRANSLATION**: Translate between languages

### Audio Tasks
- **AUTOMATIC_SPEECH_RECOGNITION**: Convert speech to text
- **AUDIO_CLASSIFICATION**: Classify audio content
- **AUDIO_TO_AUDIO**: Audio enhancement and transformation
- **TEXT_TO_SPEECH**: Generate speech from text

### Vision Tasks
- **IMAGE_TO_TEXT**: Generate captions for images
- **TEXT_TO_IMAGE**: Generate images from text descriptions
- **IMAGE_TO_IMAGE**: Transform images based on prompts

### Multimodal Tasks
- **IMAGE_TEXT_TO_TEXT**: Process images and text together
- **CONVERSATIONAL**: Multi-turn chat conversations

### Specialized Tasks
- **SENTENCE_SIMILARITY**: Compare text similarity
- **CHAT_COMPLETION**: OpenAI-compatible chat interface

## Inference Providers

Choose from multiple inference providers for optimal performance:

### Supported Providers
- **SAMBANOVA**: Fast inference with competitive pricing
- **TOGETHER**: High-quality models with good performance
- **REPLICATE**: Specialized in image and video generation
- **FAL_AI**: Fast inference for creative tasks
- **FIREWORKS_AI**: Enterprise-grade inference
- **CEREBRAS**: Optimized for large language models
- **COHERE**: Advanced language understanding
- **GROQ**: Ultra-fast inference speeds
- **MISTRAL**: European AI provider
- **OPENAI**: Direct OpenAI API integration
- **ANTHROPIC**: Claude models access
- **HUGGINGFACE**: Default Hugging Face inference
- **AUTO**: Automatic provider selection

### Provider Usage
```php
use AzahariZaman\Huggingface\Enums\Provider;

// Specify provider explicitly
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
    'provider' => Provider::SAMBANOVA,
]);

// Let the system choose the best provider
$result = $client->chatCompletion()->create([
    'model' => 'meta-llama/Llama-3.1-8B-Instruct',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
    'provider' => Provider::AUTO,
]);
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
