# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

#### 🤖 Chat Completions API
- **NEW**: Complete OpenAI-compatible Chat Completions API implementation
- **NEW**: `ChatCompletion` resource with `create()` and `createStream()` methods
- **NEW**: Support for streaming responses with Server-Sent Events (SSE)
- **NEW**: `CreateStreamResponse` class for handling real-time chat streams
- **NEW**: Router endpoint support (`router.huggingface.co`) for chat completions
- **NEW**: Provider selection system with 16 available providers (SambaNova, Together, Replicate, etc.)
- **NEW**: Comprehensive chat completion parameters: temperature, max_tokens, top_p, frequency_penalty, etc.

#### 🌐 Hub API Integration
- **NEW**: Complete Hugging Face Hub API implementation
- **NEW**: `Hub` resource for accessing model metadata and user information
- **NEW**: `getModel()` method for retrieving detailed model information
- **NEW**: `listModels()` method for browsing available models with filtering
- **NEW**: `whoami()` method for authenticated user information
- **NEW**: Hub endpoint support (`huggingface.co`) for model discovery

#### 🔧 Enhanced Inference Capabilities
- **EXPANDED**: Task types from 5 to 17 comprehensive categories:
  - Audio: `AUDIO_TO_AUDIO`, `AUTOMATIC_SPEECH_RECOGNITION`, `TEXT_TO_SPEECH`, `AUDIO_CLASSIFICATION`
  - Vision: `IMAGE_TO_TEXT`, `TEXT_TO_IMAGE`, `IMAGE_TO_IMAGE`
  - Multimodal: `IMAGE_TEXT_TO_TEXT`
  - Core NLP: Enhanced existing `TEXT_GENERATION`, `SUMMARIZATION`, `FILL_MASK`, `SENTIMENT_ANALYSIS`, `EMOTION_CLASSIFICATION`, `TRANSLATION`, `SENTENCE_SIMILARITY`
  - Conversational: `CONVERSATIONAL`, `CHAT_COMPLETION`
- **NEW**: Provider-aware inference with automatic routing
- **NEW**: Enhanced response handling for new task types

#### 🏗️ Architecture Improvements
- **NEW**: Multi-endpoint support with automatic transporter creation
- **NEW**: Reflection-based transporter cloning for different API surfaces
- **NEW**: `Provider` enum for standardized provider selection
- **NEW**: Enhanced response classes with proper type safety
- **IMPROVED**: Client architecture to support multiple base URIs
- **IMPROVED**: Error handling and response parsing

#### 📚 Documentation & Examples
- **NEW**: Comprehensive usage examples for all new features
- **NEW**: `examples/chat_completions.php` - Chat API demonstration
- **NEW**: `examples/streaming_chat.php` - Real-time streaming example
- **NEW**: `examples/hub_integration.php` - Hub API usage
- **NEW**: `examples/enhanced_inference.php` - New task types showcase
- **NEW**: `examples/complete_integration_test.php` - Full feature validation
- **UPDATED**: README.md with new features and usage patterns
- **IMPROVED**: API documentation with comprehensive parameter descriptions

### Changed

#### 🔄 Backward Compatibility
- **MAINTAINED**: All existing inference functionality remains unchanged
- **MAINTAINED**: Original API contracts and method signatures preserved
- **ENHANCED**: Existing task types now support provider selection
- **IMPROVED**: Response handling with fallback to raw responses for compatibility

#### ⚡ Performance & Reliability
- **OPTIMIZED**: HTTP transport layer for multiple endpoint handling
- **IMPROVED**: Error handling with more descriptive exceptions
- **ENHANCED**: Type safety across all new response classes
- **STREAMLINED**: Resource creation and dependency injection

### Technical Details

#### 🔧 Infrastructure Changes
- **Multi-Endpoint Architecture**: Support for three distinct API surfaces:
  - `api-inference.huggingface.co` - Traditional inference API
  - `router.huggingface.co` - Chat completions and routing
  - `huggingface.co` - Hub API and model metadata
- **Streaming Support**: Server-Sent Events implementation for real-time responses
- **Provider System**: Intelligent routing to optimal inference providers
- **Type System**: Comprehensive enum system for tasks and providers

#### 📊 Testing & Quality
- **MAINTAINED**: 100% backward compatibility with existing 124 tests
- **VERIFIED**: All new features covered by integration testing
- **VALIDATED**: Zero lint errors and PHPStan compliance
- **TESTED**: Streaming functionality and error handling

#### 🎯 Developer Experience
- **SIMPLIFIED**: Intuitive API design following established patterns
- **DOCUMENTED**: Comprehensive examples for all use cases
- **TYPED**: Full PHP type hints and return type declarations
- **EXTENSIBLE**: Modular architecture for future enhancements

---

### Migration Guide

#### For Existing Users
No breaking changes! All existing code continues to work exactly as before:

```php
// Existing inference code works unchanged
$client = Huggingface::client('your-api-key');
$result = $client->inference()->create([
    'model' => 'gpt2',
    'inputs' => 'Hello world',
]);
```

#### For New Features

**Chat Completions:**
```php
$response = $client->chatCompletion()->create([
    'model' => 'microsoft/DialoGPT-medium',
    'messages' => [['role' => 'user', 'content' => 'Hello!']],
]);
```

**Hub Integration:**
```php
$model = $client->hub()->getModel('microsoft/DialoGPT-medium');
$models = $client->hub()->listModels(['task' => 'text-generation']);
```

**Enhanced Inference:**
```php
$result = $client->inference()->create([
    'model' => 'openai-community/gpt2',
    'inputs' => 'Generate text...',
    'provider' => Provider::TOGETHER,
]);
```

---

### Contributors

This major update represents a comprehensive modernization of the Hugging Face PHP client library, bringing it in line with the latest Hugging Face API capabilities while maintaining full backward compatibility.

### Acknowledgments

Special thanks to the Hugging Face team for their excellent API documentation and the Context7 service for providing up-to-date API references that made this comprehensive update possible.