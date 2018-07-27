#!/usr/bin/env bash

# set timezone machine to America/New_York
cp /usr/share/zoneinfo/America/New_York /etc/localtime

# set PHP7 timezone to America/New_York
sed -i "s/;date.timezone =*/date.timezone = America\/New_York/" /etc/php/7.0/fpm/php.ini
sed -i "s/;date.timezone =*/date.timezone = America\/New_York/" /etc/php/7.0/cli/php.ini

# setup php7.0-fpm to not run as daemon (allow my_init to control)
sed -i "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/7.0/fpm/php-fpm.conf
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.0/fpm/php.ini

# run PHP-FPM
php-fpm7.0 -c /etc/php/7.0/fpm