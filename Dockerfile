FROM php:7.2.10-cli-alpine3.8
ARG PECL_XDEBUG_VERSION=2.6.1

# $PHPIZE_DEPS are needed to run pecl
RUN  apk add --no-cache --virtual .build-deps ${PHPIZE_DEPS} && \
    pecl install xdebug-${PECL_XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
