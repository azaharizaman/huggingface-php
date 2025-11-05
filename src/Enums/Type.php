<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Enums;

enum Type: string
{
    case TEXT_GENERATION = 'text-generation';
    case FILL_MASK = 'fill-mask';
    case SUMMARIZATION = 'summarization';
    case SENTIMENT_ANALYSIS = 'sentiment-analysis';
    case EMOTION_CLASSIFICATION = 'emotion-classification';
    case AUDIO_TO_AUDIO = 'audio-to-audio';
    case AUTOMATIC_SPEECH_RECOGNITION = 'automatic-speech-recognition';
    case AUDIO_CLASSIFICATION = 'audio-classification';
    case IMAGE_TO_TEXT = 'image-to-text';
    case IMAGE_TEXT_TO_TEXT = 'image-text-to-text';
    case TEXT_TO_SPEECH = 'text-to-speech';
    case TEXT_TO_IMAGE = 'text-to-image';
    case IMAGE_TO_IMAGE = 'image-to-image';
    case TRANSLATION = 'translation';
    case SENTENCE_SIMILARITY = 'sentence-similarity';
    case CONVERSATIONAL = 'conversational';
    case CHAT_COMPLETION = 'chat-completion';
}
