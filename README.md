# SortedLinkedList

Library providing a type-safe, immutable Sorted Linked List data structure that maintains values in sorted order. Supports both `int` and `string` types, but prevents mixing types within a single list.

## Features

**Type-Safe**: Prevents mixing string and integer values within a single list

## Installation

### Via Composer

```bash
composer require seriyyah/sorted-linked-list
```

### From Source

```bash
git clone https://github.com/seriyyah/sorted-linked-list

cd sorted-linked-list

# Build and start Docker environment
make dev-setup  

# Or manually:
docker-compose build
docker-compose up -d

# Enter container  
make shell 

# Install Dependencies
composer install

# Run all quality checks (lint + analyse + test)
make qa
```

### Using Make Commands (Recommended)

```bash
make help          # Show all available commands
make dev-setup     # Build Docker image and start container
make shell         # Enter container shell
make test          # Run all tests
make test-coverage # Generate coverage report
make lint          # Check PSR-12 compliance
make lint-fix      # Auto-fix linting issues
make analyse       # Run PhpStan static analysis
make qa            # Run all quality checks (lint + analyse + test)
make up            # Start Docker container
make down          # Stop Docker container
make logs          # View Docker logs
make clean         # Clean build artifacts
```

### Using Docker Compose Directly

```bash
# Install dependencies
docker-compose exec app composer install

# Run tests
docker-compose exec app composer test

# Generate coverage
docker-compose exec app composer test:coverage

# Check code style
docker-compose exec app composer lint

# Auto-fix code style
docker-compose exec app composer lint:fix

# Run static analysis
docker-compose exec app composer analyse
```
