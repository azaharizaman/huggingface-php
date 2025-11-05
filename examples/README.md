# 🤗 Advanced AI Tasks Examples

This directory contains comprehensive examples showcasing the full range of AI inference tasks supported by the Hugging Face PHP client in this new release.

## 📁 Example Files

### `inference.php`
Basic examples demonstrating:
- Sentiment Analysis
- Emotion Classification
- Simple inference patterns

### `streaming_example.php` 
Real-time streaming examples for:
- Chat completion streaming
- Real-time response processing
- Streaming data collection

### `comprehensive_example.php`
Complete showcase including:
- Chat completion with multiple providers
- Traditional inference tasks
- Hub API integration
- Auto-detection features

### `advanced_inference_tasks.php` ⭐ **NEW**
Demonstrates all advanced AI tasks:
- **🖼️ Image Processing**: Image-to-text, text-to-image, image-to-image
- **🔊 Audio Processing**: Speech recognition, text-to-speech, audio classification  
- **🌐 Language Tasks**: Translation, sentence similarity
- **💬 Conversational AI**: Dialog systems, visual Q&A
- **🔍 Auto-detection**: Automatic task type detection
- **⚡ Practical Pipelines**: Content moderation, multi-language processing

## 🚀 New AI Tasks Covered

### Vision Tasks
```php
// Image Captioning
$result = $client->inference()->create([
    'model' => 'Salesforce/blip-image-captioning-base',
    'inputs' => 'https://example.com/image.jpg',
    'type' => Type::IMAGE_TO_TEXT,
]);

// Text-to-Image Generation
$result = $client->inference()->create([
    'model' => 'runwayml/stable-diffusion-v1-5',
    'inputs' => 'A beautiful sunset over mountains',
    'type' => Type::TEXT_TO_IMAGE,
]);

// Visual Question Answering
$result = $client->inference()->create([
    'model' => 'dandelin/vilt-b32-finetuned-vqa',
    'inputs' => [
        'image' => 'https://example.com/image.jpg',
        'question' => 'What is in this image?'
    ],
    'type' => Type::IMAGE_TEXT_TO_TEXT,
]);
```

### Audio Tasks
```php
// Speech Recognition
$result = $client->inference()->create([
    'model' => 'openai/whisper-small',
    'inputs' => '/path/to/audio.wav',
    'type' => Type::AUTOMATIC_SPEECH_RECOGNITION,
]);

// Text-to-Speech
$result = $client->inference()->create([
    'model' => 'microsoft/speecht5_tts',
    'inputs' => 'Hello, this is synthesized speech!',
    'type' => Type::TEXT_TO_SPEECH,
]);

// Audio Classification
$result = $client->inference()->create([
    'model' => 'facebook/wav2vec2-base-960h',
    'inputs' => '/path/to/audio.wav',
    'type' => Type::AUDIO_CLASSIFICATION,
]);
```

### Language Tasks
```php
// Translation
$result = $client->inference()->create([
    'model' => 'Helsinki-NLP/opus-mt-en-fr',
    'inputs' => 'Hello, how are you?',
    'type' => Type::TRANSLATION,
]);

// Sentence Similarity
$result = $client->inference()->create([
    'model' => 'sentence-transformers/all-MiniLM-L6-v2',
    'inputs' => [
        'source_sentence' => 'The cat is sleeping.',
        'sentences' => ['A feline is resting.', 'The dog is running.']
    ],
    'type' => Type::SENTENCE_SIMILARITY,
]);
```

### Conversational AI
```php
// Dialog Systems
$result = $client->inference()->create([
    'model' => 'microsoft/DialoGPT-medium',
    'inputs' => [
        'past_user_inputs' => ['Hello'],
        'generated_responses' => ['Hi there!'],
        'text' => 'What can you help me with?'
    ],
    'type' => Type::CONVERSATIONAL,
]);
```

## 🔧 Smart Auto-Detection

The library now automatically detects the appropriate task type based on the model:

```php
// No need to specify type - auto-detected as TEXT_GENERATION
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'The future of AI is',
]);

// Auto-detected as TRANSLATION
$result = $client->inference()->create([
    'model' => 'Helsinki-NLP/opus-mt-en-fr',
    'inputs' => 'Hello world',
]);
```

## 📊 Practical Use Cases

### Content Moderation Pipeline
```php
$content = "User-generated content to analyze";

// Analyze sentiment
$sentiment = $client->inference()->create([
    'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
    'inputs' => $content,
    'type' => Type::SENTIMENT_ANALYSIS,
]);

// Detect emotions
$emotions = $client->inference()->create([
    'model' => 'SamLowe/roberta-base-go_emotions',
    'inputs' => $content,
    'type' => Type::EMOTION_CLASSIFICATION,
]);
```

### Multi-language Processing
```php
// Translate content
$translated = $client->inference()->create([
    'model' => 'Helsinki-NLP/opus-mt-en-fr',
    'inputs' => 'Original English text',
    'type' => Type::TRANSLATION,
]);

// Analyze translated content
$sentiment = $client->inference()->create([
    'model' => 'distilbert-base-uncased-finetuned-sst-2-english',
    'inputs' => $translated['translation_text'],
    'type' => Type::SENTIMENT_ANALYSIS,
]);
```

## 🏁 Getting Started

1. **Set up your API key:**
   ```bash
   export HUGGINGFACE_API_KEY="your_api_key_here"
   ```

2. **Run any example:**
   ```bash
   php examples/advanced_inference_tasks.php
   ```

3. **Explore the code** to understand different task patterns and adapt them to your use case.

## 📋 Complete Task Coverage

This release supports all major Hugging Face Inference API tasks:

- ✅ Text Generation
- ✅ Fill Mask
- ✅ Summarization  
- ✅ Sentiment Analysis
- ✅ Emotion Classification
- ✅ Audio-to-Audio
- ✅ Automatic Speech Recognition
- ✅ Audio Classification
- ✅ Image-to-Text
- ✅ Image-Text-to-Text
- ✅ Text-to-Speech
- ✅ Text-to-Image
- ✅ Image-to-Image
- ✅ Translation
- ✅ Sentence Similarity
- ✅ Conversational
- ✅ Chat Completion

Happy coding! 🚀