.PHONY: help build up down logs shell test lint analyse coverage clean

help: ## Show this help message
	@echo 'SortedLinkedList - Make Commands'
	@echo ''
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker image
	docker-compose build

up: ## Start Docker container
	docker-compose up -d

down: ## Stop Docker container
	docker-compose down

logs: ## View Docker logs
	docker-compose logs -f

shell: ## Enter Docker shell
	docker-compose exec app /bin/bash

test: ## Run all tests
	docker-compose exec app composer test

test-coverage: ## Generate coverage report
	docker-compose exec app composer test:coverage

lint: ## Run PSR-12 linting
	docker-compose exec app composer lint

lint-fix: ## Fix PSR-12 linting issues
	docker-compose exec app composer lint:fix

analyse: ## Run PhpStan static analysis (Level 8)
	docker-compose exec app composer analyse

qa: lint analyse test ## Run all quality checks

clean: ## Clean build artifacts
	docker-compose down -v
	rm -rf coverage vendor composer.lock

dev-setup: build up ## Setup development environment

install: ## Install dependencies
	docker-compose exec app composer install

.DEFAULT_GOAL := help
