<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Huggingface;

$yourApiKey = getenv('HUGGINGFACE_API_KEY');
if (!$yourApiKey) {
    throw new \RuntimeException('HUGGINGFACE_API_KEY environment variable is required');
}
$client = Huggingface::client($yourApiKey);

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
