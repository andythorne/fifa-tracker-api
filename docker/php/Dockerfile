FROM php:7.2-fpm-alpine

WORKDIR /var/www/html

# See https://github.com/docker-library/php/issues/221
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo_pgsql

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer -O - -q | php -- --install-dir=/usr/local/bin --filename=composer
RUN ls -ls

COPY composer.json /var/www/html
COPY composer.lock /var/www/html
RUN composer install --no-scripts

COPY docker/php/xdebug-overrides.ini /usr/local/etc/php-fpm.d/zzz-xdebug-overrides.ini
COPY docker/php/xdebug-overrides.ini /usr/local/etc/php/conf.d/zzz-xdebug-overrides.ini

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug
