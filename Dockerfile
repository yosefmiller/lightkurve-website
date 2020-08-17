##### INITIALIZE #####
# Load ubuntu base image designed for docker
FROM phusion/baseimage:0.11
LABEL maintainer="yosefmiller613@gmail.com"
ENV REFRESHED_AT 2020-08-16
ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1
WORKDIR /var/www

##### INSTALL DEPENDENCIES #####
# Nginx and PHP installation
RUN rm -rf /var/lib/apt/lists/* && \
    apt-get update --fix-missing && \
    apt-get -y upgrade -o Dpkg::Options::="--force-confdef" && \
    apt-get -y install php-fpm php-cli php-json php-zip unzip nginx-core python3-pip git

# Composer PHP dependency manager
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer config -g repo.packagist composer https://packagist.org && \
    composer require klein/klein

# PIP Python dependency manager
RUN pip3 install lightkurve==1.11.1 cython pytest jupyter
RUN pip3 install git+https://github.com/mirca/transit-periodogram.git

##### CONFIGURE SERVICES #####
# Setup files
ADD config/nginx.conf /etc/nginx/sites-available/default
ADD config/nginx.sh /etc/service/nginx/run
ADD config/phpfpm.sh /etc/service/phpfpm/run
ADD config/jupyter.sh /etc/service/jupyter/run
ADD config/crontab /etc/cron.d/cleanup
COPY . /var/www/html

# Setup directories and permissions
RUN mkdir -p /var/run/php /var/log/cleanup /var/www/html/outputs && \
    chown -R www-data:www-data /var/run/php /var/www && \
    chmod +x /etc/service/nginx/run && \
    chmod +x /etc/service/phpfpm/run && \
    chmod +x /etc/service/jupyter/run && \
    chmod 755 /var/www && \
    chmod 766 /var/www/html/outputs

# Disable services start
RUN update-rc.d -f apache2 remove && \
    update-rc.d -f nginx remove && \
    update-rc.d -f php7.2-fpm remove

# Expose port
EXPOSE 80 8888

# Cleanup APT and lists
RUN apt-get clean && \
    apt-get autoclean

# Reaps orphaned child processes correctly, and responds to SIGTERM correctly
RUN echo /root > /etc/container_environment/HOME
CMD ["/sbin/my_init"]