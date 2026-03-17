# Contributing to Wave

Thank you for considering contributing to Wave! We appreciate your interest in making this SaaS framework even better.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Submitting Changes](#submitting-changes)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)
- [Testing](#testing)

## Code of Conduct

This project and everyone participating in it is governed by our commitment to fostering an open and welcoming environment. We expect all contributors to:

- Use welcoming and inclusive language
- Be respectful of differing viewpoints and experiences
- Gracefully accept constructive criticism
- Focus on what is best for the community
- Show empathy towards other community members

## How Can I Contribute?

### Reporting Bugs

If you discover a bug, please:

1. Check the [documentation](https://devdojo.com/wave/docs) to ensure it's not expected behavior
2. Search [existing pull requests](https://github.com/thedevdojo/wave/pulls) to see if it's already being addressed
3. Join the [DevDojo community](https://devdojo.com) to discuss the issue
4. If confirmed, submit a pull request with a fix

When reporting or fixing a bug, please include:

- **Clear descriptive title** - Use a clear and descriptive title
- **Detailed description** - Provide a detailed description of the issue
- **Steps to reproduce** - List the exact steps to reproduce the problem
- **Expected behavior** - Describe what you expected to happen
- **Actual behavior** - Describe what actually happened
- **Environment details**:
  - Wave version
  - Laravel version
  - PHP version
  - Operating system
  - Database (MySQL, PostgreSQL, SQLite)
  - Billing provider (Stripe/Paddle)
- **Screenshots** - If applicable, add screenshots to help explain the problem
- **Error messages** - Include any relevant error messages or logs

### Suggesting Features

Feature suggestions are welcome! Before suggesting a feature:

- Check the [documentation](https://devdojo.com/wave/docs) to ensure the feature doesn't already exist
- Search [existing pull requests](https://github.com/thedevdojo/wave/pulls) to see if it's already in progress
- Join the [DevDojo community](https://devdojo.com) to discuss your idea with other users and maintainers

When suggesting a feature via pull request, please include:

- **Clear description** - Explain the feature in detail
- **Use case** - Describe why this feature would be useful
- **Examples** - Provide examples of how the feature would work
- **Alternatives** - Describe any alternative solutions you've considered
- **Documentation** - Update relevant documentation to explain the new feature

### Pull Requests

We actively welcome your pull requests! Here's how to submit one:

1. Fork the repository
2. Create a new branch from `main` (`git checkout -b feature/my-feature`)
3. Make your changes
4. Write or update tests as needed
5. Ensure tests pass (`./vendor/bin/pest`)
6. Ensure code follows style guidelines (`./vendor/bin/pint --test`)
7. Commit your changes with clear, descriptive messages
8. Push to your fork
9. Submit a pull request to the `main` branch

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite, MySQL, or PostgreSQL

### Installation

1. **Clone your fork**:
   ```bash
   git clone https://github.com/YOUR-USERNAME/wave.git
   cd wave
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node dependencies**:
   ```bash
   npm install
   ```

4. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Create database**:
   ```bash
   touch database/database.sqlite  # For SQLite
   ```

6. **Run migrations and seeders**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**:
   ```bash
   npm run build
   ```

8. **Start development server**:
   ```bash
   composer run dev  # Starts server, queue, logs, and Vite
   ```

   Or run components separately:
   ```bash
   php artisan serve
   npm run dev
   php artisan queue:listen
   ```

## Coding Standards

### PHP Code Style

Wave follows the Laravel coding style using [Laravel Pint](https://laravel.com/docs/pint).

- **Check code style**: `./vendor/bin/pint --test`
- **Fix code style**: `./vendor/bin/pint`

Key conventions:
- PSR-12 compliant
- Use type hints where possible
- Write descriptive variable and method names
- Keep methods focused and concise
- Add PHPDoc blocks for complex methods

### JavaScript/CSS Style

- Follow standard JavaScript ES6+ conventions
- Use Tailwind CSS utility classes
- Avoid inline styles when possible
- Keep JavaScript minimal and maintainable

### Blade Templates

- Use Blade components where appropriate
- Keep logic out of views (use Livewire or view composers)
- Follow consistent indentation (4 spaces)
- Use Blade directives (`@if`, `@foreach`) over PHP tags

## Submitting Changes

### Commit Messages

Write clear, concise commit messages following these guidelines:

- Use the imperative mood ("Add feature" not "Added feature")
- First line should be 50 characters or less
- Optionally add a blank line and detailed description
- Reference pull requests when relevant

**Good examples**:
```
fix: resolve subscription webhook timeout issue

feat: add user data export functionality

docs: improve billing configuration examples

test: add coverage for profile update validation
```

**Commit types**:
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style/formatting (no functional changes)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

### Pull Request Guidelines

- **One feature per PR** - Keep pull requests focused on a single feature or fix
- **Update tests** - Add or update tests to cover your changes
- **Update documentation** - Update relevant documentation if needed
- **Clean commit history** - Squash commits if needed to keep history clean
- **Descriptive title** - Use a clear title that describes the change
- **Detailed description** - Explain what changes were made and why
- **Screenshots** - Add screenshots for UI changes
- **Breaking changes** - Clearly mark any breaking changes

## Testing

Wave uses [Pest PHP](https://pestphp.com/) for testing.

### Running Tests

```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/AccountDeletionTest.php

# Run tests with coverage
./vendor/bin/pest --coverage

# Run tests with detailed output
./vendor/bin/pest --verbose
```

### Writing Tests

- Place feature tests in `tests/Feature/`
- Place unit tests in `tests/Unit/`
- Follow existing test patterns in the codebase
- Test both success and failure scenarios
- Use descriptive test names that explain what is being tested

**Example test structure**:

```php
it('allows users to update their profile', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->put('/settings/profile', [
        'name' => 'Updated Name',
        'email' => 'newemail@example.com',
    ]);
    
    $response->assertRedirect();
    expect($user->fresh()->name)->toBe('Updated Name');
});
```

### Test Database

Tests use SQLite in-memory database by default. You can configure this in `phpunit.xml`.

## Additional Resources

- [Wave Documentation](https://devdojo.com/wave/docs)
- [Laravel Documentation](https://laravel.com/docs)
- [Pest PHP Documentation](https://pestphp.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)

## Questions?

If you have questions about contributing, feel free to:

- Open a [discussion](https://github.com/thedevdojo/wave/discussions)
- Join the [DevDojo community](https://devdojo.com)
- Check the [documentation](https://devdojo.com/wave/docs)

## License

By contributing to Wave, you agree that your contributions will be licensed under the [MIT License](LICENSE.md).

---

Thank you for contributing to Wave! Your efforts help make this framework better for everyone. 🌊
