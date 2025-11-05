# Contributing to Hugging Face PHP Client

Thank you for your interest in contributing to the Hugging Face PHP Client! This document provides guidelines and best practices to help ensure high-quality contributions.

## Table of Contents

- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Best Practices](#best-practices)
- [Testing Guidelines](#testing-guidelines)
- [Pull Request Process](#pull-request-process)

## Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- Git

### Installation

1. Clone the repository:
```bash
git clone https://github.com/azaharizaman/huggingface-php.git
cd huggingface-php
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file and configure your API key:
```bash
cp .env.example .env
```

### Running Tests

```bash
composer test
```

### Code Style

Run PHP Code Sniffer to check code style:
```bash
composer lint
```

### Static Analysis

Run PHPStan for static analysis:
```bash
composer analyse
```

## Coding Standards

### PHP Standards

- Follow PSR-12 coding standards
- Use strict types: `declare(strict_types=1);`
- Maintain comprehensive type hints for parameters and return types
- Keep methods focused and concise

### Documentation

- Use PHPDoc blocks for all classes, methods, and properties
- Document complex logic with inline comments
- Keep CHANGELOG.md updated with all notable changes
- Follow [Keep a Changelog](https://keepachangelog.com/) format

## Best Practices

### 1. Markdown Formatting

**Problem:** Invalid markdown heading syntax can break document rendering.

**Example of incorrect usage:**
```markdown
## # Changelog
```

**Correct usage:**
```markdown
# Changelog
```

**Rule:** Never combine multiple heading markers. Use a single `#` for top-level headings, `##` for second-level, etc.

---

### 2. Documentation Accuracy

**Problem:** Documenting features or enum cases that don't exist in the codebase leads to confusion and maintenance issues.

**Example:** Listing task types in CHANGELOG.md that aren't defined in `src/Enums/Type.php`:
```markdown
- Vision: `IMAGE_CLASSIFICATION`, `VISUAL_QUESTION_ANSWERING`
```

**Rule:** Always verify that documented features, enum cases, and methods actually exist in the code. Cross-reference documentation with implementation.

**Best Practice:**
- Before documenting a feature, check the actual implementation
- Keep documentation synchronized with code changes
- Review enum definitions before listing available values

---

### 3. Pattern Matching Precision

**Problem:** Overly broad pattern matching can cause false positives and incorrect behavior.

**Example of incorrect usage:**
```php
// This will incorrectly match 'gpt5', 'bert5', 'mt5', etc.
if (str_contains($model, 't5')) {
    return Type::TRANSLATION;
}
```

**Correct usage:**
```php
// Use word boundaries to match specific patterns
if (str_contains($model, 'translation') || preg_match('/\bt5[-_]/i', $model)) {
    return Type::TRANSLATION;
}
```

**Rule:** When matching patterns in strings, especially model names:
- Use word boundaries (`\b`) in regex to avoid false matches
- Look for specific delimiters (hyphens, underscores) after identifiers
- Test edge cases: `gpt5`, `bert5`, `t5-base`, `mt5-large`
- Consider case-insensitive matching with the `i` flag when appropriate

**Pattern Matching Guidelines:**
- For exact matches: Use `===` comparison
- For substring matches: Use `str_contains()` with specific enough strings
- For pattern-based matches: Use `preg_match()` with precise regex patterns
- Always test with examples that could produce false positives

---

### 4. Avoid Reflection for Internal Access

**Problem:** Using reflection to access private properties creates fragile, tightly-coupled code that breaks encapsulation.

**Example of incorrect usage:**
```php
$reflection = new \ReflectionClass($this->transporter);
$property = $reflection->getProperty('client');
$property->setAccessible(true);
$client = $property->getValue($this->transporter);
```

**Correct usage:**
```php
// Add a proper method to the class
public function withBaseUri(BaseUri $baseUri): self
{
    return new self(
        $this->client,
        $baseUri,
        $this->headers,
        $this->queryParams,
        $this->streamHandler
    );
}

// Use the method instead of reflection
$newTransporter = $this->transporter->withBaseUri($chatBaseUri);
```

**Rule:** Never use reflection to access private properties. Instead:
1. Add public getter methods for properties that need external access
2. Add factory or cloning methods (e.g., `withBaseUri()`) for creating variants
3. Consider if the design needs refactoring if reflection seems necessary

**Benefits of proper encapsulation:**
- Changes to internal structure won't break dependent code
- Clear API contract through public methods
- Better IDE support and type safety
- Easier to maintain and refactor

---

### 5. Token Estimation Accuracy

**Problem:** Using inaccurate approximations for token counts can mislead users about actual API usage and costs.

**Example of incorrect usage:**
```php
'completion_tokens' => str_word_count($content),  // Inaccurate approximation
'total_tokens' => str_word_count($content)
```

**Correct usage:**
```php
// Use actual values from API when available, otherwise use 0
'completion_tokens' => 0,  // Unknown value
'total_tokens' => 0
```

**Rule:** For token counts and similar metrics:
- Use actual values from the API response when available
- Use `0` to indicate unknown/unavailable values rather than approximations
- Document in comments if a value is approximated and why
- Consider adding a note in API documentation about token counting limitations

**Why word count is inaccurate:**
- Tokenizers split words differently (subwords, punctuation, etc.)
- Different models use different tokenization strategies
- Special tokens aren't accounted for in word counts
- Multi-byte characters are handled differently

---

### 6. Class Design Patterns

**Immutability:** Prefer readonly properties and return new instances rather than mutating state:
```php
public function __construct(
    private readonly ClientInterface $client,
    private readonly BaseUri $baseUri,
) {}

public function withBaseUri(BaseUri $baseUri): self
{
    return new self($this->client, $baseUri);
}
```

**Factory Methods:** Use static factory methods for complex object creation:
```php
public static function from(string $uri): self
{
    return new self($uri);
}
```

---

### 7. Error Handling

**Always handle exceptions appropriately:**
```php
try {
    $response = $this->client->sendRequest($request);
} catch (ClientExceptionInterface $clientException) {
    throw new TransporterException($clientException);
}
```

**Wrap third-party exceptions in domain-specific exceptions:**
- Makes it easier to handle errors at higher levels
- Provides better context for debugging
- Allows for future implementation changes

---

## Testing Guidelines

### Writing Tests

1. **Test Coverage:** Aim for comprehensive test coverage of all public APIs
2. **Unit Tests:** Test individual components in isolation
3. **Integration Tests:** Test components working together
4. **Edge Cases:** Include tests for error conditions and edge cases

### Test Structure

```php
public function testMethodName(): void
{
    // Arrange: Set up test data and conditions
    $input = 'test data';
    
    // Act: Execute the code being tested
    $result = $this->subject->method($input);
    
    // Assert: Verify the results
    $this->assertEquals('expected', $result);
}
```

### Test Naming

- Use descriptive test names that explain what is being tested
- Follow the pattern: `test{MethodName}{Scenario}{ExpectedResult}`
- Example: `testCreateWithInvalidModelThrowsException`

---

## Pull Request Process

### Before Submitting

1. **Run all checks:**
   ```bash
   composer lint
   composer analyse
   composer test
   ```

2. **Update documentation:**
   - Update CHANGELOG.md with your changes
   - Add/update PHPDoc blocks
   - Update README.md if adding new features

3. **Commit message format:**
   - Use clear, descriptive commit messages
   - Follow conventional commits format when possible
   - Example: `feat: add support for audio classification`

### PR Description Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] Tests added/updated
- [ ] All tests pass
- [ ] CHANGELOG.md updated
```

### Code Review

- Be open to feedback and suggestions
- Respond to review comments promptly
- Make requested changes in new commits (don't force push)
- Keep discussions professional and constructive

---

## Common Pitfalls to Avoid

1. **Don't modify working code unnecessarily** - Only change what's needed to fix a bug or add a feature
2. **Don't remove or modify tests unless they're incorrect** - Tests document expected behavior
3. **Don't ignore linting errors** - They exist to maintain code quality
4. **Don't skip documentation** - Future maintainers (including yourself) will thank you
5. **Don't use overly broad patterns** - Be specific to avoid false positives
6. **Don't break encapsulation** - Use proper methods instead of reflection
7. **Don't approximate when accuracy matters** - Use actual values or indicate unknown

---

## Getting Help

- **Issues:** Report bugs or request features via GitHub Issues
- **Discussions:** Ask questions in GitHub Discussions
- **Code Review:** Tag maintainers for review of complex PRs

---

## Code of Conduct

- Be respectful and inclusive
- Focus on constructive feedback
- Welcome newcomers and help them learn
- Maintain a positive and collaborative environment

---

## License

By contributing to this project, you agree that your contributions will be licensed under the same license as the project (MIT License).

---

Thank you for contributing to make the Hugging Face PHP Client better! 🚀
