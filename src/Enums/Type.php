<?php

namespace AzahariZaman\Huggingface\Enums;

enum Type: string
{
    case TEXT_GENERATION = 'text-generation';
    case FILL_MASK = 'fill-mask';
    case SUMMARIZATION = 'summarization';
    case SENTIMENT_ANALYSIS = 'sentiment-analysis';
}
