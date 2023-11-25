<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Huggingface;

// $yourApiKey = getenv('HUGGINGFACE_API_KEY');
$yourApiKey='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';
$client = Huggingface::client($yourApiKey);

// $result = $client->inference()->create([
//     'model' => 'gpt2',
//     'inputs' => 'The goal of life is?',
//     'type' => Type::TEXT_GENERATION,
// ]);

// echo $result['generated_text']."\n";
// var_export($result->toArray());


// $result = $client->inference()->create([
//     'model' => 'distilbert-base-uncased',
//     'inputs' => 'The answer to the universe is [MASK].',
//     'type' => Type::FILL_MASK,
// ]);

// echo $result['filled_masks'][0]['sequence']."\n";
// var_export($result->toArray());

// $result = $client->inference()->create([
//     'model' => 'facebook/bart-large-cnn',
//     'inputs' => 'My friends are cool but they eat too many carbs.',
//     'type' => Type::SUMMARIZATION,
// ]);

// echo $result['summary_text']."\n";
// var_export($result->toArray());

$result = $client->inference()->create([
    'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
    'inputs' => 'I like this product',
    'type' => Type::SENTIMENT_ANALYSIS,
]);
var_export($result->toArray());

$result = $client->inference()->create([
    'model' => 'SamLowe/roberta-base-go_emotions',
    'inputs' => 'Im sorry to hear about your situation.',
    'type' => Type::EMOTION_CLASSIFICATION,
]);
$results = $result->toArray();

foreach ($results as $result) {
    var_export($result);
}
