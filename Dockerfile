FROM php:8.4-cli-alpine

LABEL maintainer="SortedLinkedList"

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    make \
    unzip \
    bash \
    openssh-client

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./

# Install PHP dependencies (including dev for testing/analysis)
RUN composer install --optimize-autoloader

# Copy project files
COPY . .

# Default command: bash
CMD ["/bin/bash"]
