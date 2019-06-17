FROM php:7.1.30-cli-alpine3.9

# Install extensions
RUN docker-php-ext-install -j$(nproc) pdo \
 && docker-php-ext-install -j$(nproc) pdo_mysql \
 && docker-php-ext-install -j$(nproc) mbstring \
 && docker-php-ext-install -j$(nproc) tokenizer

ENTRYPOINT ["tail", "-f", "/dev/null"]
