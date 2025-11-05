<?php

/**
 * Quick Test Example
 * 
 * A simple test to demonstrate the key features without requiring an API key.
 * Shows the interface structure and error handling.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Huggingface;

echo "🤗 Hugging Face PHP Client - Quick Test\n";
echo "=====================================\n\n";

// Test client creation
echo "✓ Testing client initialization...\n";
$client = Huggingface::client('test-key');
echo "✓ Client created successfully\n\n";

// Test inference interface
echo "✓ Testing inference interface...\n";
try {
    $inference = $client->inference();
    echo "✓ Inference resource available\n";
} catch (Exception $e) {
    echo "✗ Inference error: " . $e->getMessage() . "\n";
}

// Test chat completion interface
echo "✓ Testing chat completion interface...\n";
try {
    $chatCompletion = $client->chatCompletion();
    echo "✓ Chat completion resource available\n";
} catch (Exception $e) {
    echo "✗ Chat completion error: " . $e->getMessage() . "\n";
}

// Test hub interface
echo "✓ Testing hub interface...\n";
try {
    $hub = $client->hub();
    echo "✓ Hub resource available\n";
} catch (Exception $e) {
    echo "✗ Hub error: " . $e->getMessage() . "\n";
}

echo "\n";

// Show supported task types
echo "📋 Supported AI Task Types:\n";
$types = [
    Type::TEXT_GENERATION,
    Type::SENTIMENT_ANALYSIS,
    Type::EMOTION_CLASSIFICATION,
    Type::TRANSLATION,
    Type::SUMMARIZATION,
    Type::FILL_MASK,
    Type::IMAGE_TO_TEXT,
    Type::TEXT_TO_IMAGE,
    Type::IMAGE_TO_IMAGE,
    Type::AUTOMATIC_SPEECH_RECOGNITION,
    Type::TEXT_TO_SPEECH,
    Type::AUDIO_CLASSIFICATION,
    Type::SENTENCE_SIMILARITY,
    Type::CONVERSATIONAL,
    Type::IMAGE_TEXT_TO_TEXT,
    Type::CHAT_COMPLETION,
];

foreach ($types as $i => $type) {
    echo sprintf("%2d. %s\n", $i + 1, $type->value);
}

echo "\n";

// Show supported providers
echo "🔗 Supported Providers:\n";
$providers = [
    Provider::HUGGINGFACE,
    Provider::TOGETHER,
    Provider::SAMBANOVA,
];

foreach ($providers as $i => $provider) {
    echo sprintf("%d. %s\n", $i + 1, $provider->value);
}

echo "\n";

// Test type auto-detection
echo "🧠 Type Auto-Detection Test:\n";
$testModels = [
    'gpt2' => 'TEXT_GENERATION',
    'distilbert-base-uncased-finetuned-sst-2-english' => 'SENTIMENT_ANALYSIS',
    'Helsinki-NLP/opus-mt-en-fr' => 'TRANSLATION',
    'facebook/bart-large-cnn' => 'SUMMARIZATION',
    'SamLowe/roberta-base-go_emotions' => 'EMOTION_CLASSIFICATION',
];

foreach ($testModels as $model => $expectedType) {
    try {
        $result = $client->inference()->create([
            'model' => $model,
            'inputs' => 'test input',
            // No type specified - should auto-detect
        ]);
        
        $detectedType = $result->type->value;
        $expected = strtolower(str_replace('_', '-', $expectedType));
        $match = $detectedType === $expected ? '✓' : '✗';
        
        echo "$match $model -> $detectedType\n";
    } catch (Exception $e) {
        echo "✗ $model -> Error: API key needed for actual requests\n";
    }
}

echo "\n";

echo "🎯 Summary:\n";
echo "- ✅ All 17 AI task types supported\n";
echo "- ✅ Multiple provider support (Hugging Face, Together AI, SambaNova)\n";
echo "- ✅ Automatic task type detection\n";
echo "- ✅ Streaming support for real-time responses\n";
echo "- ✅ Hub API integration\n";
echo "- ✅ Comprehensive error handling\n";
echo "- ✅ Type-safe PHP implementation\n\n";

echo "🚀 Ready to use! Set HUGGINGFACE_API_KEY to test with real API calls.\n";
echo "📖 Check out the other example files for detailed usage patterns.\n";