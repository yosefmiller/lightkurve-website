FROM balr/php7-fpm-nginx
LABEL maintainer="yosefmiller613@gmail.com"
ENV REFRESHED_AT 2018-07-16

# Modify the nginx configuration
COPY nginx-site.conf /etc/nginx/sites-enabled/default

# Install dependencies
WORKDIR /var/www/html
COPY . .
RUN apt-get -y update && \
    xargs apt-get install -y < apt-requirements.txt