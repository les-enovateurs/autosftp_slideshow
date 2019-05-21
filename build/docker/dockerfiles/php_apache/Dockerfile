FROM php:7.2-apache-stretch
MAINTAINER Jérémy PASTOURET <pastouret.jeremy@gmail.com>

RUN apt-get update

# PDO
RUN docker-php-ext-install pdo_mysql

# Réécriture d'URL sur Apache, depuis le .htaccess
RUN a2enmod rewrite

RUN set -eux; \
	apt-get install -y --no-install-recommends libssh2-1-dev; \
	pecl install ssh2-1.1.2; \
	docker-php-ext-enable ssh2

RUN apt install ssh

# Php errors
RUN echo "log_errors = Off" > /usr/local/etc/php/conf.d/errors.ini \
&&  echo "error_reporting = E_ERROR | E_WARNING | E_PARSE" >> /usr/local/etc/php/conf.d/errors.ini

# TimeZone
RUN echo "date.timezone = Europe/Paris" > /usr/local/etc/php/conf.d/date.ini

# Apache custom configurations
RUN sed 's/^LogLevel.*/LogLevel info/' /etc/apache2/apache2.conf \
&&  echo "ServerName localhost" >> /etc/apache2/apache2.conf

# GoAccess
RUN unlink /var/log/apache2/access.log \
&&  unlink /var/log/apache2/error.log \
&&  touch /var/log/apache2/access.log /var/log/apache2/error.log \
&&  chmod 777 /var/log/apache2/access.log /var/log/apache2/error.log

# Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.default_enable = 1" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.profiler_enable = 0" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.remote_enable = 1" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.remote_autostart = 0" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.remote_connect_back = 0" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.remote_log='/tmp/xdebug'" >> /usr/local/etc/php/conf.d/xdebug.ini \
&&  echo "xdebug.remote_port= 9000" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN touch /tmp/xdebug \
&&  chown www-data:www-data /tmp/xdebug \
&&  chmod 644 /tmp/xdebug

# APCU Cache
RUN pecl install apcu \
&&  echo "extension=apcu.so" >> /usr/local/etc/php/conf.d/apcu.ini
#&&  echo "apc.preload_path=/tmp/apcu/" >> /usr/local/etc/php/conf.d/apcu.ini

# Memcached
#RUN apt-get install memcached zlib1g-dev \
#&&  pecl install memcached
#&&  echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini

# OPcache
RUN echo "zend_extension=opcache.so" >> /usr/local/etc/php/conf.d/opcache.ini

# ZIP (required for composer)
RUN apt-get install -y zlib1g-dev \
&& docker-php-ext-install zip

# Copy sources
COPY ./ /var/www/html
RUN chmod -R 777 /var/www/html


WORKDIR /var/www/html

