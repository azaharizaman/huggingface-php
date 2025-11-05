<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Huggingface;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
if (!$yourApiKey) {
    echo "Note: Set HUGGINGFACE_API_KEY environment variable to test with real API\n";
    echo "This example shows the interface structure for advanced inference tasks\n\n";
}

$client = Huggingface::client($yourApiKey ?: 'demo-key');

echo "=== Advanced Inference Tasks Examples ===\n\n";

// Image-to-Text (OCR/Image Captioning)
echo "1. Image-to-Text (Image Captioning):\n";
try {
    // Using a base64-encoded image or image URL
    $imageData = 'https://huggingface.co/datasets/mishig/sample_images/resolve/main/tiger.jpg';
    
    $result = $client->inference()->create([
        'model' => 'Salesforce/blip-image-captioning-base',
        'inputs' => $imageData,
        'type' => Type::IMAGE_TO_TEXT,
    ]);
    
    echo "Image Caption: " . $result['generated_text'] . "\n\n";
} catch (Exception $e) {
    echo "Image-to-text error: " . $e->getMessage() . "\n\n";
}

// Text-to-Image Generation
echo "2. Text-to-Image Generation:\n";
try {
    $result = $client->inference()->create([
        'model' => 'runwayml/stable-diffusion-v1-5',
        'inputs' => 'A beautiful sunset over a mountain lake with reflection',
        'type' => Type::TEXT_TO_IMAGE,
        'parameters' => [
            'guidance_scale' => 7.5,
            'num_inference_steps' => 50,
        ]
    ]);
    
    echo "Generated image data available (base64 encoded)\n";
    $imageData = $result['image'];
    if (is_string($imageData)) {
        echo "Image size: " . strlen($imageData) . " bytes\n\n";
    } else {
        echo "Image data structure: " . gettype($imageData) . "\n\n";
    }
} catch (Exception $e) {
    echo "Text-to-image error: " . $e->getMessage() . "\n\n";
}

// Translation
echo "3. Language Translation:\n";
try {
    $result = $client->inference()->create([
        'model' => 'Helsinki-NLP/opus-mt-en-fr',
        'inputs' => 'Hello, how are you today? I hope you are doing well.',
        'type' => Type::TRANSLATION,
    ]);
    
    echo "English to French: " . $result['translation_text'] . "\n\n";
} catch (Exception $e) {
    echo "Translation error: " . $e->getMessage() . "\n\n";
}

// Automatic Speech Recognition
echo "4. Automatic Speech Recognition:\n";
try {
    // Audio file path or base64 encoded audio
    $audioData = 'path/to/audio.wav'; // In practice, use actual audio data
    
    $result = $client->inference()->create([
        'model' => 'openai/whisper-small',
        'inputs' => $audioData,
        'type' => Type::AUTOMATIC_SPEECH_RECOGNITION,
    ]);
    
    echo "Transcribed text: " . $result['text'] . "\n\n";
} catch (Exception $e) {
    echo "Speech recognition error: " . $e->getMessage() . "\n\n";
}

// Text-to-Speech
echo "5. Text-to-Speech:\n";
try {
    $result = $client->inference()->create([
        'model' => 'microsoft/speecht5_tts',
        'inputs' => 'Hello! This is a demonstration of text to speech conversion.',
        'type' => Type::TEXT_TO_SPEECH,
    ]);
    
    echo "Generated audio data available\n";
    $audioData = $result['audio'];
    if (is_string($audioData)) {
        echo "Audio size: " . strlen($audioData) . " bytes\n\n";
    } else {
        echo "Audio data structure: " . gettype($audioData) . "\n\n";
    }
} catch (Exception $e) {
    echo "Text-to-speech error: " . $e->getMessage() . "\n\n";
}

// Audio Classification
echo "6. Audio Classification:\n";
try {
    $audioData = 'path/to/audio.wav'; // In practice, use actual audio data
    
    $result = $client->inference()->create([
        'model' => 'facebook/wav2vec2-base-960h',
        'inputs' => $audioData,
        'type' => Type::AUDIO_CLASSIFICATION,
    ]);
    
    echo "Audio classification results:\n";
    foreach ($result['classifications'] as $classification) {
        echo "- " . $classification['label'] . " (score: " . $classification['score'] . ")\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Audio classification error: " . $e->getMessage() . "\n\n";
}

// Image-to-Image (Style Transfer/Enhancement)
echo "7. Image-to-Image Processing:\n";
try {
    $imageData = 'https://huggingface.co/datasets/mishig/sample_images/resolve/main/tiger.jpg';
    
    $result = $client->inference()->create([
        'model' => 'timbrooks/instruct-pix2pix',
        'inputs' => [
            'image' => $imageData,
            'prompt' => 'Make it a painting in the style of Van Gogh'
        ],
        'type' => Type::IMAGE_TO_IMAGE,
    ]);
    
    echo "Processed image data available\n";
    $imageData = $result['image'];
    if (is_string($imageData)) {
        echo "Image size: " . strlen($imageData) . " bytes\n\n";
    } else {
        echo "Image data structure: " . gettype($imageData) . "\n\n";
    }
} catch (Exception $e) {
    echo "Image-to-image error: " . $e->getMessage() . "\n\n";
}

// Sentence Similarity
echo "8. Sentence Similarity:\n";
try {
    $result = $client->inference()->create([
        'model' => 'sentence-transformers/all-MiniLM-L6-v2',
        'inputs' => [
            'source_sentence' => 'The cat is sleeping on the couch.',
            'sentences' => [
                'A feline is resting on the sofa.',
                'The dog is running in the park.',
                'Cats love to sleep during the day.',
            ]
        ],
        'type' => Type::SENTENCE_SIMILARITY,
    ]);
    
    echo "Similarity scores:\n";
    foreach ($result['similarities'] as $index => $score) {
        echo "- Sentence " . ($index + 1) . ": " . round($score, 4) . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Sentence similarity error: " . $e->getMessage() . "\n\n";
}

// Conversational AI
echo "9. Conversational AI:\n";
try {
    $result = $client->inference()->create([
        'model' => 'microsoft/DialoGPT-medium',
        'inputs' => [
            'past_user_inputs' => ['Hello', 'How are you?'],
            'generated_responses' => ['Hi there!'],
            'text' => 'What can you help me with?'
        ],
        'type' => Type::CONVERSATIONAL,
    ]);
    
    echo "AI Response: " . $result['generated_text'] . "\n\n";
} catch (Exception $e) {
    echo "Conversational AI error: " . $e->getMessage() . "\n\n";
}

// Image-Text-to-Text (Visual Question Answering)
echo "10. Visual Question Answering (Image+Text to Text):\n";
try {
    $imageData = 'https://huggingface.co/datasets/mishig/sample_images/resolve/main/tiger.jpg';
    
    $result = $client->inference()->create([
        'model' => 'dandelin/vilt-b32-finetuned-vqa',
        'inputs' => [
            'image' => $imageData,
            'question' => 'What animal is in the image?'
        ],
        'type' => Type::IMAGE_TEXT_TO_TEXT,
    ]);
    
    echo "Answer: " . $result['answer'] . "\n\n";
} catch (Exception $e) {
    echo "Visual QA error: " . $e->getMessage() . "\n\n";
}

echo "=== Auto-Detection Examples ===\n\n";

// Show how the library can auto-detect task types
echo "Auto-Detection Demo:\n";
$testCases = [
    [
        'model' => 'gpt2',
        'inputs' => 'The future of AI is',
        'expected_type' => 'TEXT_GENERATION'
    ],
    [
        'model' => 'Helsinki-NLP/opus-mt-en-fr',
        'inputs' => 'Hello world',
        'expected_type' => 'TRANSLATION'
    ],
    [
        'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => 'I love this product!',
        'expected_type' => 'SENTIMENT_ANALYSIS'
    ]
];

foreach ($testCases as $i => $testCase) {
    echo ($i + 1) . ". Testing model: {$testCase['model']}\n";
    try {
        $result = $client->inference()->create([
            'model' => $testCase['model'],
            'inputs' => $testCase['inputs'],
            // No type specified - will be auto-detected
        ]);
        
        echo "   Auto-detected type: " . $result->type->value . "\n";
        echo "   Expected type: " . strtolower($testCase['expected_type']) . "\n";
        echo "   Match: " . ($result->type->value === strtolower(str_replace('_', '-', $testCase['expected_type'])) ? '✓' : '✗') . "\n\n";
    } catch (Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }
}

echo "=== Practical Use Cases ===\n\n";

echo "Content Moderation Pipeline:\n";
try {
    $content = "This product is absolutely terrible and I hate it!";
    
    // Step 1: Sentiment Analysis
    $sentiment = $client->inference()->create([
        'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => $content,
        'type' => Type::SENTIMENT_ANALYSIS,
    ]);
    
    // Step 2: Emotion Classification
    $emotions = $client->inference()->create([
        'model' => 'SamLowe/roberta-base-go_emotions',
        'inputs' => $content,
        'type' => Type::EMOTION_CLASSIFICATION,
    ]);
    
    echo "Original text: $content\n";
    echo "Sentiment: " . $sentiment['label'] . " (confidence: " . round((float)$sentiment['score'], 3) . ")\n";
    echo "Top emotion: " . $emotions[0]['label'] . " (confidence: " . round((float)$emotions[0]['score'], 3) . ")\n\n";
    
} catch (Exception $e) {
    echo "Content moderation error: " . $e->getMessage() . "\n\n";
}

echo "Multi-language Content Processing:\n";
try {
    $originalText = "Artificial intelligence is transforming the world.";
    
    // Translate to French
    $frenchText = $client->inference()->create([
        'model' => 'Helsinki-NLP/opus-mt-en-fr',
        'inputs' => $originalText,
        'type' => Type::TRANSLATION,
    ]);
    
    // Analyze sentiment of French text
    $frenchSentiment = $client->inference()->create([
        'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
        'inputs' => $frenchText['translation_text'],
        'type' => Type::SENTIMENT_ANALYSIS,
    ]);
    
    echo "Original (EN): $originalText\n";
    echo "Translated (FR): " . $frenchText['translation_text'] . "\n";
    echo "French sentiment: " . $frenchSentiment['label'] . "\n\n";
    
} catch (Exception $e) {
    echo "Multi-language processing error: " . $e->getMessage() . "\n\n";
}

echo "Advanced inference tasks examples completed!\n";