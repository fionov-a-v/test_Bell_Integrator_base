FROM php:7.4.25-fpm-alpine as foundation

RUN apk add --update composer \
    git \
    wget \
    openssh \
    php7-pdo_pgsql \
    php7-json \
    php7-curl \
    php7-opcache

RUN apk --no-cache add postgresql-dev && \
        docker-php-ext-install pdo_pgsql opcache

FROM foundation as builder
ARG WD="docker/php"
COPY $WD/entrypoint.sh /entrypoint.sh
COPY $WD/xdebug.ini /tmp/xdebug.ini

ARG APP_DEBUG
RUN if [ -n $APP_DEBUG ] && [ $APP_DEBUG = 1 ]; then \
    apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
      && pecl install -o -f xdebug \
      && docker-php-ext-enable xdebug \
      && apk del pcre-dev ${PHPIZE_DEPS} \
      && cp /tmp/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
      && echo "Xdebug on"; \
else \
    echo "Xdebug off"; \
fi

FROM builder as deployer
WORKDIR /var/www
COPY app/ ./
