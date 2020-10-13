FROM composer AS builder

WORKDIR /usr/src/app

COPY . .

RUN composer install --no-dev

FROM php:7.3-fpm-alpine3.8

WORKDIR /usr/src/app

COPY --from=builder /usr/src/app /usr/src/app

RUN chown -R www-data:www-data ./var \
 && chmod +x ./docker/entrypoint.sh
 # && docker-php-ext-install \

USER www-data