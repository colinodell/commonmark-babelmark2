FROM php:8.0-apache
MAINTAINER Colin O'Dell <colinodell@gmail.com>

WORKDIR /var/www

RUN set -ex \
	&& docker-php-ext-install opcache \
	&& rm -rf /var/www/html \
        && apt-get update \
        && apt-get install -y git unzip \
        && rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . .

RUN set -ex \
	&& mv web html \
	&& curl -sS https://getcomposer.org/installer | php \
	&& ./composer.phar install -a --no-progress --no-dev \
	&& rm composer.phar \
	&& chown -R www-data:www-data /var/www/html \
	&& chmod -R a-w /var/www/html

USER www-data
