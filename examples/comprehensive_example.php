<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Huggingface;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
if (!$yourApiKey) {
    throw new \RuntimeException('HUGGINGFACE_API_KEY environment variable is required');
}

$client = Huggingface::client($yourApiKey);

echo "=== Chat Completion Example ===\n";
try {
    $chatResponse = $client->chatCompletion()->create([
        'model' => 'meta-llama/Llama-3.1-8B-Instruct',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello! How are you today?']
        ],
        'provider' => Provider::SAMBANOVA,
        'max_tokens' => 100,
        'temperature' => 0.7,
    ]);
    
    echo "Chat Response:\n";
    var_export($chatResponse->toArray());
    echo "\n\n";
} catch (Exception $e) {
    echo "Chat completion error: " . $e->getMessage() . "\n\n";
}

echo "=== Traditional Inference Examples ===\n";

// Text Generation
echo "1. Text Generation:\n";
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The future of artificial intelligence is',
    'type' => Type::TEXT_GENERATION,
]);
echo "Generated text: " . $result['generated_text'] . "\n\n";

// Sentiment Analysis
echo "2. Sentiment Analysis:\n";
$result = $client->inference()->create([
    'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
    'inputs' => 'I absolutely love this new product!',
    'type' => Type::SENTIMENT_ANALYSIS,
]);
echo "Sentiment: ";
var_export($result['sentiment_analysis']);
echo "\n\n";

// Summarization
echo "3. Summarization:\n";
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
echo "Summary: " . $result['summary_text'] . "\n\n";

// Fill Mask
echo "4. Fill Mask:\n";
$result = $client->inference()->create([
    'model' => 'bert-base-uncased',
    'inputs' => 'The capital of France is [MASK].',
    'type' => Type::FILL_MASK,
]);
echo "Fill mask predictions:\n";
foreach ($result['filled_masks'] as $prediction) {
    echo "- " . $prediction['token_str'] . " (score: " . $prediction['score'] . ")\n";
}
echo "\n";

// Emotion Classification
echo "5. Emotion Classification:\n";
$result = $client->inference()->create([
    'model' => 'SamLowe/roberta-base-go_emotions',
    'inputs' => 'I am so excited about this new opportunity!',
    'type' => Type::EMOTION_CLASSIFICATION,
]);
echo "Emotions detected:\n";
var_export($result['emotion_classification']);
echo "\n\n";

echo "=== Hub API Examples ===\n";

// Get model information
echo "1. Model Information:\n";
try {
    $modelInfo = $client->hub()->getModel([
        'model_id' => 'gpt2',
        'expand' => ['inference']
    ]);
    echo "Model: " . $modelInfo->id . "\n";
    echo "Inference status: " . ($modelInfo->inference ?? 'not available') . "\n";
} catch (Exception $e) {
    echo "Model info error: " . $e->getMessage() . "\n";
}
echo "\n";

// Get user info
echo "2. User Information:\n";
try {
    $userInfo = $client->hub()->whoami();
    echo "User info:\n";
    var_export($userInfo);
    echo "\n";
} catch (Exception $e) {
    echo "User info error: " . $e->getMessage() . "\n";
}

echo "\n=== New Task Type Examples (Auto-detection) ===\n";

// Example with auto-detection for different model types
echo "1. Auto-detected Text Generation:\n";
try {
    $result = $client->inference()->create([
        'model' => 'gpt2',
        'inputs' => 'Once upon a time',
        // No type specified - will auto-detect
    ]);
    echo "Type detected: " . $result->type->value . "\n";
    echo "Result: " . $result['generated_text'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "2. Auto-detected Translation:\n";
try {
    $result = $client->inference()->create([
        'model' => 't5-base',
        'inputs' => 'translate English to German: Hello, how are you?',
        // No type specified - will auto-detect as TRANSLATION
    ]);
    echo "Type detected: " . $result->type->value . "\n";
    var_export($result->toArray());
    echo "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Examples completed!\n";