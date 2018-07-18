FROM php:7.0-fpm
LABEL maintainer="yosefmiller613@gmail.com"
ENV REFRESHED_AT 2018-07-17

# Add Extensions
WORKDIR /var/www/html
COPY . .
RUN apt-get update && \
    # Curl, zip, custom
    apt-get install -y libcurl3-dev zlib1g-dev < apt-requirements.txt && \
    # ZIP
    docker-php-ext-configure zip --with-zlib-dir=/usr && \
    # Mysqli, json, curl, zip
    docker-php-ext-install curl mysqli pdo pdo_mysql json zip && \
    # Remove APT lists
    rm -rf /var/lib/apt/lists/*

#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install

# Expose NGINX
EXPOSE 9000