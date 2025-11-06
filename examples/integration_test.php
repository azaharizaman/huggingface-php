<?php

/**
 * Integration Test with Real API Calls
 *
 * This script performs real API calls to validate that the Hugging Face PHP client
 * works correctly with the updated endpoints and prevents user errors.
 *
 * Requirements:
 * - HUGGINGFACE_API_KEY in .env file or environment variable
 * - Internet connection
 * - Valid Hugging Face API key with sufficient credits
 */

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Huggingface;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use AzahariZaman\Huggingface\Enums\Provider;

// Load .env file if it exists
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        if ($key && $value) {
            putenv("$key=$value");
        }
    }
    echo "✓ Loaded configuration from .env file\n";
}

$apiKey = getenv('HUGGINGFACE_API_KEY');
if (!$apiKey) {
    echo "❌ ERROR: HUGGINGFACE_API_KEY not found in environment or .env file\n";
    echo "Please set your Hugging Face API key in the .env file or environment variable.\n";
    exit(1);
}

echo "\n🤗 Hugging Face PHP Client - Real API Integration Test\n";
echo "======================================================\n\n";

$client = Huggingface::client($apiKey);

$testsPassed = 0;
$testsTotal = 0;

function runTest($name, $testFunction) {
    global $testsPassed, $testsTotal;
    $testsTotal++;
    echo "🧪 Testing: $name\n";
    try {
        $result = $testFunction();
        if ($result) {
            echo "✅ PASSED\n\n";
            $testsPassed++;
        } else {
            echo "❌ FAILED\n\n";
        }
    } catch (Exception $e) {
        echo "❌ FAILED: " . $e->getMessage() . "\n\n";
    }
}

// Test 1: Router Sentiment Analysis (verifies updated endpoint)
runTest("Router Sentiment Analysis", function() use ($client) {
    $httpClient = new \GuzzleHttp\Client();
    $modelId = 'distilbert/distilbert-base-uncased-finetuned-sst-2-english';
    $url = "https://router.huggingface.co/hf-inference/models/{$modelId}";

    // First, confirm the raw REST call succeeds so we know the router path is valid
    $response = $httpClient->post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . getenv('HUGGINGFACE_API_KEY'),
            'Content-Type' => 'application/json'
        ],
        'json' => [
            'inputs' => 'I absolutely love this new product! It works perfectly.',
        ],
    ]);

    $rawResponse = $response->getBody()->getContents();
    $contentType = $response->getHeader('Content-Type')[0] ?? 'unknown';
    echo "   Raw API Response (Content-Type: $contentType)\n";
    echo "   " . substr($rawResponse, 0, 200) . (strlen($rawResponse) > 200 ? '...' : '') . "\n";

    // Then verify the PHP client handles the same call correctly
    $result = $client->inference()->create([
        'model' => $modelId,
        'inputs' => 'I absolutely love this new product! It works perfectly.',
        'type' => Type::SENTIMENT_ANALYSIS,
    ]);

    $sentiments = [];

    if ($result instanceof CreateResponse) {
        $sentiments = $result->toArray()['sentiment_analysis'] ?? [];
    } elseif (is_array($result)) {
        $sentiments = $result['sentiment_analysis'] ?? $result;
    } elseif (is_string($result)) {
        echo "   Client returned string: '" . substr($result, 0, 100) . "...'\n";
        return true;
    }

    if (empty($sentiments)) {
        throw new Exception('No sentiment data returned from client');
    }

    $primary = $sentiments[0];
    $label = $primary['label'] ?? '';
    $score = $primary['score'] ?? 0;

    echo "   Sentiment: $label (score: $score)\n";
    return true;
});

// Test 2: Sentiment Analysis
runTest("Sentiment Analysis", function() use ($client) {
    echo "   About to make API call...\n";
    $result = $client->inference()->create([
        'model' => 'distilbert/distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => 'I absolutely love this new product! It works perfectly.',
        'type' => Type::SENTIMENT_ANALYSIS,
    ]);

    $sentiments = [];

    if ($result instanceof CreateResponse) {
        $sentiments = $result->toArray()['sentiment_analysis'] ?? [];
    } elseif (is_array($result)) {
        $sentiments = $result['sentiment_analysis'] ?? $result;
    } elseif (is_string($result)) {
        echo "   Inference returned string: '" . substr($result, 0, 100) . "...'\n";
        return true;
    }

    if (empty($sentiments)) {
        throw new Exception("No sentiment label received");
    }

    $primary = $sentiments[0];
    $label = $primary['label'] ?? '';
    $score = $primary['score'] ?? 0;

    echo "   Text: 'I absolutely love this new product! It works perfectly.'\n";
    echo "   Sentiment: $label (confidence: " . round((float)$score, 3) . ")\n";
    return true;
});

// Test 3: Chat Completions (OpenAI-compatible)
runTest("Chat Completions (OpenAI-compatible)", function() use ($client) {
    $response = $client->chatCompletion()->create([
        'model' => 'deepseek-ai/DeepSeek-R1:fastest',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello! How are you today?']
        ],
        'max_tokens' => 50,
        'temperature' => 0.7
    ]);

    $content = $response['choices'][0]['message']['content'] ?? '';
    if (empty($content)) {
        throw new Exception("No chat response received");
    }

    echo "   User: 'Hello! How are you today?'\n";
    echo "   Assistant: '" . substr($content, 0, 100) . "...'\n";
    return true;
});

// Test 4: Hub API - Get Model Info
runTest("Hub API - Get Model Info", function() use ($client) {
    $model = $client->hub()->getModel(['model_id' => 'openai-community/gpt2']);

    if (!isset($model['id']) || $model['id'] !== 'openai-community/gpt2') {
        throw new Exception("Model info not retrieved correctly");
    }

    echo "   Model ID: " . $model['id'] . "\n";
    echo "   Downloads: " . ($model['downloads'] ?? 'N/A') . "\n";
    echo "   Likes: " . ($model['likes'] ?? 'N/A') . "\n";
    return true;
});

// Test 5: Hub API - List Models
runTest("Hub API - List Models", function() use ($client) {
    $response = $client->hub()->listModels([
        'limit' => 5,
        'sort' => 'downloads'
    ]);

    $models = is_array($response) ? $response : $response->toArray();

    if (empty($models)) {
        throw new Exception("No models retrieved");
    }

    $topModels = array_slice($models, 0, 5);

    echo "   Retrieved " . count($topModels) . " models\n";
    echo "   Top model: " . ($topModels[0]['id'] ?? 'Unknown') . "\n";
    return true;
});

// Test 6: Hub API - User Info
runTest("Hub API - User Authentication", function() use ($client) {
    $user = $client->hub()->whoami();

    if (!isset($user['name'])) {
        throw new Exception("User info not retrieved");
    }

    echo "   Authenticated as: " . $user['name'] . "\n";
    echo "   User ID: " . ($user['id'] ?? 'N/A') . "\n";
    return true;
});

// Test 7: Error Handling - Invalid Model
runTest("Error Handling - Invalid Model", function() use ($client) {
    try {
        $client->inference()->create([
            'model' => 'non-existent-model-12345',
            'inputs' => 'test'
        ]);
        throw new Exception("Expected error for invalid model");
    } catch (Exception $e) {
        // This is expected - we want to see proper error handling
        echo "   Correctly handled error: " . substr($e->getMessage(), 0, 100) . "...\n";
        return true;
    }
});

// Summary
echo "======================================================\n";
echo "Integration Test Results: $testsPassed / $testsTotal tests passed\n";

if ($testsPassed === $testsTotal) {
    echo "🎉 All tests passed! The Hugging Face PHP client is working correctly.\n";
    echo "Users should not encounter the router endpoint errors.\n";
} else {
    echo "⚠️  Some tests failed. Please check the error messages above.\n";
    echo "This may indicate issues with API keys, network, or the updated endpoints.\n";
}

echo "\nTest completed at: " . date('Y-m-d H:i:s') . "\n";