<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Huggingface;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
if (!$yourApiKey) {
    echo "Note: Set HUGGINGFACE_API_KEY environment variable to test with real API\n";
    echo "This example shows the interface structure\n\n";
}

$client = Huggingface::client($yourApiKey ?: 'demo-key');

echo "=== Hugging Face PHP Client - Inference Examples ===\n\n";

// 1. Sentiment Analysis
echo "1. Sentiment Analysis:\n";
try {
    $result = $client->inference()->create([
        'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => 'I absolutely love this new product! It works perfectly.',
        'type' => Type::SENTIMENT_ANALYSIS,
    ]);
    
    echo "Text: 'I absolutely love this new product! It works perfectly.'\n";
    echo "Sentiment: " . $result['label'] . " (confidence: " . round((float)$result['score'], 3) . ")\n\n";
} catch (Exception $e) {
    echo "Sentiment analysis error: " . $e->getMessage() . "\n\n";
}

// 2. Emotion Classification
echo "2. Emotion Classification:\n";
try {
    $result = $client->inference()->create([
        'model' => 'SamLowe/roberta-base-go_emotions',
        'inputs' => 'I am so excited about this new opportunity! This is amazing!',
        'type' => Type::EMOTION_CLASSIFICATION,
    ]);
    
    echo "Text: 'I am so excited about this new opportunity! This is amazing!'\n";
    echo "Top emotions detected:\n";
    $resultArray = $result->toArray();
    foreach (array_slice($resultArray, 0, 3) as $emotion) {
        echo "- " . $emotion['label'] . ": " . round((float)$emotion['score'], 3) . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Emotion classification error: " . $e->getMessage() . "\n\n";
}

// 3. Text Generation
echo "3. Text Generation:\n";
try {
    $result = $client->inference()->create([
        'model' => 'gpt2',
        'inputs' => 'The future of artificial intelligence in healthcare will',
        'type' => Type::TEXT_GENERATION,
        'parameters' => [
            'max_length' => 100,
            'temperature' => 0.7,
        ]
    ]);
    
    echo "Prompt: 'The future of artificial intelligence in healthcare will'\n";
    echo "Generated: " . $result['generated_text'] . "\n\n";
} catch (Exception $e) {
    echo "Text generation error: " . $e->getMessage() . "\n\n";
}

// 4. Summarization
echo "4. Text Summarization:\n";
try {
    $longText = "Artificial intelligence (AI) is intelligence demonstrated by machines, " .
               "in contrast to the natural intelligence displayed by humans and animals. " .
               "Leading AI textbooks define the field as the study of 'intelligent agents': " .
               "any device that perceives its environment and takes actions that maximize " .
               "its chance of successfully achieving its goals. The term 'artificial intelligence' " .
               "is often used to describe machines that mimic cognitive functions that humans " .
               "associate with the human mind, such as learning and problem solving.";

    $result = $client->inference()->create([
        'model' => 'facebook/bart-large-cnn',
        'inputs' => $longText,
        'type' => Type::SUMMARIZATION,
        'parameters' => [
            'max_length' => 50,
            'min_length' => 10,
        ]
    ]);
    
    echo "Original text: " . substr($longText, 0, 100) . "...\n";
    echo "Summary: " . $result['summary_text'] . "\n\n";
} catch (Exception $e) {
    echo "Summarization error: " . $e->getMessage() . "\n\n";
}

// 5. Translation
echo "5. Language Translation:\n";
try {
    $result = $client->inference()->create([
        'model' => 'Helsinki-NLP/opus-mt-en-fr',
        'inputs' => 'Hello! How are you doing today? I hope you are well.',
        'type' => Type::TRANSLATION,
    ]);
    
    echo "English: 'Hello! How are you doing today? I hope you are well.'\n";
    echo "French: " . $result['translation_text'] . "\n\n";
} catch (Exception $e) {
    echo "Translation error: " . $e->getMessage() . "\n\n";
}

// 6. Fill Mask (BERT-style)
echo "6. Fill Mask:\n";
try {
    $result = $client->inference()->create([
        'model' => 'bert-base-uncased',
        'inputs' => 'The capital of France is [MASK].',
        'type' => Type::FILL_MASK,
    ]);
    
    echo "Text: 'The capital of France is [MASK].'\n";
    echo "Predictions:\n";
    $resultArray = $result->toArray();
    foreach (array_slice($resultArray, 0, 3) as $prediction) {
        echo "- " . $prediction['token_str'] . " (score: " . round((float)$prediction['score'], 3) . ")\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Fill mask error: " . $e->getMessage() . "\n\n";
}

echo "=== Auto-Detection Example ===\n";
echo "The library can automatically detect the task type based on the model:\n\n";

try {
    // No type specified - will be auto-detected
    $result = $client->inference()->create([
        'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => 'This automatic detection feature is really convenient!',
        // type is omitted - will be auto-detected as SENTIMENT_ANALYSIS
    ]);
    
    echo "Model: distilbert-base-uncased-finetuned-sst-2-english\n";
    echo "Auto-detected task: " . $result->type->value . "\n";
    echo "Result: " . $result['label'] . " (score: " . round((float)$result['score'], 3) . ")\n\n";
} catch (Exception $e) {
    echo "Auto-detection error: " . $e->getMessage() . "\n\n";
}

echo "🎉 Basic inference examples completed!\n";
echo "💡 Tip: Check out other example files for advanced features:\n";
echo "   - comprehensive_example.php: Full API showcase\n";
echo "   - streaming_example.php: Real-time streaming\n";
echo "   - advanced_inference_tasks.php: All AI task types\n";
