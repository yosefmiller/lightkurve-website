##### INITIALIZE #####
# Load ubuntu base image designed for docker
FROM phusion/baseimage:0.10.1
LABEL maintainer="yosefmiller613@gmail.com"
ENV REFRESHED_AT 2018-07-27
ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1
WORKDIR /var/www

# Reaps orphaned child processes correctly, and responds to SIGTERM correctly
RUN /etc/my_init.d/00_regen_ssh_host_keys.sh
CMD ["/sbin/my_init"]

##### INSTALL DEPENDENCIES #####
# Nginx and PHP installation
RUN apt-get update && \
    apt-get -y upgrade && \
    apt-get update --fix-missing && \
    apt-get -y install php7.0 php7.0-fpm php7.0-common php7.0-cli \
                       php7.0-mysqlnd php7.0-mcrypt php7.0-curl \
                       php7.0-bcmath php7.0-mbstring php7.0-soap \
                       php7.0-xml php7.0-zip php7.0-json php7.0-imap \
                       php-pgsql nginx-full python3-pip

# Composer PHP dependency manager
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer require klein/klein

##### CONFIGURE SERVICES #####
# Setup files
ADD config/nginx.conf /etc/nginx/sites-available/default
ADD config/nginx.sh /etc/service/nginx/run
ADD config/phpfpm.sh /etc/service/phpfpm/run

# Setup directories and permissions
RUN mkdir -p /var/run/php /var/www/html/outputs && \
    chown -R www-data:www-data /var/run/php && \
    chown -R www-data:www-data /var/www && \
    chmod +x /etc/service/nginx/run && \
    chmod +x /etc/service/phpfpm/run && \
    chmod 755 /var/www && \
    chmod 666 /var/www/html/outputs

# Disable services start
RUN update-rc.d -f apache2 remove && \
    update-rc.d -f nginx remove && \
    update-rc.d -f php7.0-fpm remove

# Expose port
EXPOSE 80

# Cleanup APT and lists
RUN apt-get clean && \
    apt-get autoclean