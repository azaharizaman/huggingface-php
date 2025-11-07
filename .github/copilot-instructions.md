# Hugging Face PHP Client - GitHub Copilot Instructions

## Project Overview

This is a community-maintained PHP API client for interacting with the Hugging Face API. It provides a modern, type-safe interface for accessing Hugging Face's Inference API and Chat Completions API with support for multiple inference providers.

**Key Features:**
- OpenAI-compatible Chat Completions API
- Support for 17+ AI task types (text generation, sentiment analysis, summarization, image-to-text, etc.)
- Multiple inference provider support (Sambanova, Together AI, Hugging Face, etc.)
- Streaming responses for real-time applications
- Comprehensive type safety with PHP 8.2+ strict types
- 100% test coverage

## Tech Stack

- **PHP:** 8.2+ required by composer.json (strict types required)
- **HTTP Client:** PSR-18 compliant (Guzzle for dev/testing)
- **Standards:** PSR-12 coding style
- **Testing:** PHPUnit 11+
- **Static Analysis:** PHPStan (level max)
- **Code Style:** PHP_CodeSniffer with Slevomat coding standard
- **Dependencies:** Composer for package management

## Directory Structure

```
.
├── src/
│   ├── Client.php           # Main API client
│   ├── Factory.php          # Client factory with builder pattern
│   ├── Huggingface.php      # Entry point with static factory methods
│   ├── Contracts/           # Interfaces
│   ├── Enums/              # Type, Method, ContentType, Provider enums
│   ├── Exceptions/         # Custom exceptions
│   ├── Resources/          # API resource implementations (Inference, ChatCompletion, Hub)
│   ├── Responses/          # Response objects (immutable DTOs)
│   ├── Transporters/       # HTTP transport layer
│   └── ValueObjects/       # Immutable value objects (ApiKey, BaseUri, Headers, etc.)
├── tests/                  # PHPUnit tests mirroring src/ structure
├── examples/               # Usage examples and demos
├── phpcs.xml              # Code style configuration
├── phpstan.neon           # Static analysis configuration
└── phpunit.xml.dist       # Test suite configuration
```

## Coding Standards & Best Practices

### PHP Code Style

1. **Strict Types:** ALWAYS declare strict types at the top of every PHP file:
   ```php
   declare(strict_types=1);
   ```

2. **PSR-12 Compliance:** Follow PSR-12 coding standards strictly
   - Run `composer lint` to check code style
   - Run `composer format` to auto-fix style issues

3. **Type Hints:** Use comprehensive type hints for all parameters, return types, and properties:
   ```php
   public function create(array $parameters): ResponseContract
   {
       // ...
   }
   ```

4. **Immutability:** Prefer readonly properties and immutable objects:
   ```php
   public function __construct(
       private readonly ClientInterface $client,
       private readonly BaseUri $baseUri,
   ) {}
   ```

5. **Factory Pattern:** Use `with*()` methods to create modified instances:
   ```php
   public function withBaseUri(BaseUri $baseUri): self
   {
       return new self($this->client, $baseUri, $this->headers);
   }
   ```

### Documentation

- **PHPDoc Blocks:** Every public class, method, and property MUST have PHPDoc
- **Inline Comments:** Only for complex logic that isn't self-explanatory
- **Keep CHANGELOG.md Updated:** Follow [Keep a Changelog](https://keepachangelog.com/) format

### Critical Best Practices from CONTRIBUTING.md

1. **Markdown Formatting:** Never combine heading markers (use `#` not `## #`)

2. **Documentation Accuracy:** Always verify documented features exist in the codebase before documenting them

3. **Pattern Matching Precision:** Use word boundaries and specific delimiters in regex:
   ```php
   // GOOD: Uses word boundary
   if (preg_match('/\bt5[-_]/i', $model)) {
       return Type::TRANSLATION;
   }
   
   // BAD: Too broad, matches 'gpt5', 'bert5', 'mt5'
   if (str_contains($model, 't5')) {
       return Type::TRANSLATION;
   }
   ```

4. **Never Use Reflection:** Don't access private properties via reflection. Add proper public methods instead:
   ```php
   // GOOD: Add a proper method
   public function withBaseUri(BaseUri $baseUri): self
   {
       return new self($this->client, $baseUri, $this->headers);
   }
   
   // BAD: Using reflection
   $reflection = new \ReflectionClass($this->transporter);
   $property = $reflection->getProperty('client');
   $property->setAccessible(true);
   ```

5. **Token Count Accuracy:** Use actual API values when available, otherwise use `0` for unknown values. Never approximate with `str_word_count()`:
   ```php
   // GOOD: Use 0 for unknown
   'completion_tokens' => 0,
   
   // BAD: Inaccurate approximation
   'completion_tokens' => str_word_count($content),
   ```

6. **Error Handling:** Wrap third-party exceptions in domain-specific exceptions:
   ```php
   try {
       $response = $this->client->sendRequest($request);
   } catch (ClientExceptionInterface $clientException) {
       throw new TransporterException($clientException);
   }
   ```

## Testing Guidelines

### Running Tests

```bash
composer test              # Run test suite
composer test-coverage     # Run with coverage report
composer analyse          # Run PHPStan static analysis
composer lint             # Check code style
composer check            # Run all checks (lint + analyse + test)
```

### Writing Tests

- **Structure:** Follow Arrange-Act-Assert pattern
- **Naming:** Use `test{MethodName}{Scenario}{ExpectedResult}` format
- **Coverage:** Aim for comprehensive coverage (current: 100%)
- **Location:** Tests mirror `src/` structure in `tests/` directory
- **Edge Cases:** Include tests for error conditions and edge cases

Example test structure:
```php
public function testCreateWithValidInput(): void
{
    // Arrange
    $input = ['model' => 'gpt2', 'inputs' => 'test'];
    
    // Act
    $result = $this->client->inference()->create($input);
    
    // Assert
    $this->assertInstanceOf(Response::class, $result);
}
```

## Common Pitfalls to Avoid

1. ❌ **Don't modify working code unnecessarily** - Only change what's needed
2. ❌ **Don't remove or modify tests unless incorrect** - Tests document expected behavior
3. ❌ **Don't ignore linting errors** - Code quality is essential
4. ❌ **Don't skip documentation** - Update PHPDoc and markdown files
5. ❌ **Don't use overly broad patterns** - Be specific in string matching
6. ❌ **Don't break encapsulation** - Use proper methods instead of reflection
7. ❌ **Don't approximate when accuracy matters** - Use actual values or indicate unknown

## Key Design Patterns

### Value Objects
Immutable objects representing values (ApiKey, BaseUri, Headers, etc.):
```php
final readonly class BaseUri implements Stringable
{
    private function __construct(public string $baseUri) {}
    
    public static function from(string $uri): self
    {
        return new self($uri);
    }
}
```

### Builder Pattern
Factory class uses builder pattern for client configuration:
```php
$client = Huggingface::factory()
    ->withApiKey($apiKey)
    ->withBaseUri('https://api-inference.huggingface.co')
    ->withHttpHeader('User-Agent', 'MyApp/1.0')
    ->make();
```

### Resource Pattern
API operations grouped into resource classes (Inference, ChatCompletion, Hub)

## Development Workflow

### Before Creating PR

1. Run all checks: `composer check`
2. Update CHANGELOG.md with changes
3. Add/update PHPDoc blocks
4. Update README.md if adding new features
5. Ensure all tests pass

### Commit Message Format

Use clear, descriptive commit messages:
- `feat: add support for audio classification`
- `fix: correct token count approximation`
- `docs: update README with new examples`
- `test: add tests for error handling`

## API Design Philosophy

- **Type Safety:** Leverage PHP 8.2+ type system fully
- **Immutability:** Prefer immutable objects and value objects
- **Explicit over Implicit:** Clear, explicit method names and parameters
- **PSR Compliance:** Follow PSR standards for HTTP, logging, etc.
- **Encapsulation:** Strong encapsulation with clear public APIs

## Related Documentation

- [README.md](../README.md) - Full API documentation and examples
- [CONTRIBUTING.md](../CONTRIBUTING.md) - Detailed contribution guidelines
- [CHANGELOG.md](../CHANGELOG.md) - Version history and changes
- [examples/](../examples/) - Comprehensive usage examples
- [Hugging Face API Docs](https://huggingface.co/docs/inference-providers)

## When Working on Issues

1. **Understand First:** Read the issue and all comments thoroughly
2. **Minimal Changes:** Make the smallest possible changes to achieve the goal
3. **Test Early:** Run linters and tests frequently during development
4. **Document Changes:** Update relevant documentation
5. **Follow Patterns:** Match existing code style and patterns in the repository

## Getting Help

- **GitHub Issues:** Report bugs or request features
- **Code Review:** Tag maintainers for complex PRs
- **Documentation:** Check README.md and CONTRIBUTING.md first
