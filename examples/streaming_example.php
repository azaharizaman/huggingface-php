<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Huggingface;
use AzahariZaman\Huggingface\Enums\Provider;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
if (!$yourApiKey) {
    echo "Note: Set HUGGINGFACE_API_KEY environment variable to test with real API\n";
    echo "This example shows the streaming interface structure\n\n";
}

$client = Huggingface::client($yourApiKey ?: 'demo-key');

echo "=== Streaming Chat Completion Example ===\n\n";

try {
    // Example 1: Basic streaming
    echo "1. Basic Streaming Chat:\n";
    $streamResponse = $client->chatCompletion()->createStream([
        'model' => 'meta-llama/Llama-3.1-8B-Instruct',
        'messages' => [
            ['role' => 'user', 'content' => 'Tell me a short story about a robot learning to paint.']
        ],
        'provider' => Provider::SAMBANOVA,
        'max_tokens' => 200,
        'temperature' => 0.7,
    ]);

    echo "Streaming response (real-time):\n";
    foreach ($streamResponse->getIterator() as $chunk) {
        if (isset($chunk['choices'][0]['delta']['content'])) {
            echo $chunk['choices'][0]['delta']['content'];
            flush(); // Force output immediately
        }
    }
    echo "\n\n";

    // Example 2: Collect streaming response
    echo "2. Collecting Streaming Response:\n";
    $streamResponse = $client->chatCompletion()->createStream([
        'model' => 'meta-llama/Llama-3.1-8B-Instruct',
        'messages' => [
            ['role' => 'user', 'content' => 'Explain quantum computing in one paragraph.']
        ],
        'provider' => Provider::TOGETHER,
        'max_tokens' => 150,
    ]);

    // Collect all chunks into a single response
    $completeResponse = $streamResponse->collect();
    echo "Complete response:\n";
    echo $completeResponse->choices[0]->message->content . "\n\n";

    // Example 3: Processing chunks manually
    echo "3. Manual Chunk Processing:\n";
    $streamResponse = $client->chatCompletion()->createStream([
        'model' => 'meta-llama/Llama-3.1-8B-Instruct',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful coding assistant.'],
            ['role' => 'user', 'content' => 'Write a Python function to check if a number is prime.']
        ],
        'provider' => Provider::CEREBRAS,
        'max_tokens' => 300,
    ]);

    $fullContent = '';
    $chunkCount = 0;
    
    foreach ($streamResponse->getIterator() as $chunk) {
        $chunkCount++;
        
        if (isset($chunk['choices'][0]['delta']['content'])) {
            $content = $chunk['choices'][0]['delta']['content'];
            $fullContent .= $content;
            
            // Show progress
            echo "Chunk $chunkCount: " . json_encode($content) . "\n";
        }
        
        if (isset($chunk['choices'][0]['finish_reason']) && $chunk['choices'][0]['finish_reason'] !== null) {
            echo "Stream finished with reason: " . $chunk['choices'][0]['finish_reason'] . "\n";
            break;
        }
    }
    
    echo "\nFull response:\n$fullContent\n\n";

} catch (Exception $e) {
    echo "Streaming error: " . $e->getMessage() . "\n";
    echo "This is expected when using demo-key instead of real API key.\n\n";
}

echo "=== Non-Streaming Comparison ===\n";

try {
    // Show the difference with non-streaming
    $regularResponse = $client->chatCompletion()->create([
        'model' => 'meta-llama/Llama-3.1-8B-Instruct',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello!']
        ],
        'provider' => Provider::AUTO,
        'max_tokens' => 50,
    ]);

    echo "Regular (non-streaming) response:\n";
    echo $regularResponse->choices[0]->message->content . "\n\n";

} catch (Exception $e) {
    echo "Regular response error: " . $e->getMessage() . "\n";
    echo "This is expected when using demo-key instead of real API key.\n\n";
}

echo "=== Streaming Benefits ===\n";
echo "✅ Real-time response generation\n";
echo "✅ Lower perceived latency\n";
echo "✅ Better user experience for long responses\n";
echo "✅ Ability to process responses as they arrive\n";
echo "✅ Compatible with OpenAI streaming format\n";
echo "✅ Support for all major inference providers\n\n";

echo "=== Usage Patterns ===\n";
echo "1. Real-time chat interfaces\n";
echo "2. Live code generation\n";
echo "3. Progressive content creation\n";
echo "4. Interactive storytelling\n";
echo "5. Live translation services\n\n";

echo "Streaming implementation completed! 🚀\n";