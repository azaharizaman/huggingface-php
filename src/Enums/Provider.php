<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Enums;

enum Provider: string
{
    case AUTO = 'auto';
    case HUGGINGFACE = 'hf-inference';
    case SAMBANOVA = 'sambanova';
    case TOGETHER = 'together';
    case REPLICATE = 'replicate';
    case FAL_AI = 'fal-ai';
    case FIREWORKS_AI = 'fireworks-ai';
    case CEREBRAS = 'cerebras';
    case COHERE = 'cohere';
    case NOVITA = 'novita';
    case GROQ = 'groq';
    case MISTRAL = 'mistral';
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
    case DEEPSEEK = 'deepseek';
    case NEBIUS = 'nebius';
    case NVIDIA = 'nvidia';
}
