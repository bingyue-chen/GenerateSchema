FROM php:7.3.6-cli-alpine3.9

# Install php extensions and php composer
RUN apk add --no-cache libzip-dev \
 && docker-php-ext-configure zip --with-libzip=/usr/include \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql mbstring tokenizer zip \
 && EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" \
 && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php -r "if (hash_file('sha384', 'composer-setup.php') === '$EXPECTED_SIGNATURE') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
 && php composer-setup.php --install-dir=/usr/bin --filename=composer \
 && php -r "unlink('composer-setup.php');"


ENTRYPOINT ["tail", "-f", "/dev/null"]
