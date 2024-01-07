FROM composer:latest as composer
FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream

FROM php:8.3-fpm-alpine as php

RUN apk update

COPY --from=php_extension_installer_upstream --link /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    install-php-extensions \
		apcu \
		intl \
		opcache \
		zip \
        http \
    ;

WORKDIR /srv/app

COPY rootfs /

FROM php as production

FROM php as development

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN set -eux; \
	install-php-extensions \
    	xdebug \
        pdo pdo_pgsql \
    ;