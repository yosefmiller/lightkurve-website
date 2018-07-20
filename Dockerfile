FROM composer:1.5.1 AS composer_dependencies
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer require klein/klein

FROM php:7.1.8-fpm
LABEL maintainer="yosefmiller613@gmail.com"
ENV REFRESHED_AT 2018-07-17

# Add Extensions
WORKDIR /var/www/html
COPY . .
COPY --from=composer_dependencies /app/vendor /var/www/vendor
RUN apt-get update && \
    # Curl, zip, custom
    apt-get install -y libcurl3-dev zlib1g-dev python3 && \
    # Custom requirements
    #xargs apt-get install -y < config/apt-requirements.txt && \
    # ZIP
    docker-php-ext-configure zip --with-zlib-dir=/usr && \
    # Mysqli, json, curl, zip
    docker-php-ext-install curl mysqli pdo pdo_mysql json zip && \
    # Remove APT lists
    rm -rf /var/lib/apt/lists/*

# Expose NGINX
EXPOSE 9000