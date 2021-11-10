#!/bin/sh
set -e

if [ ! -d "/root/.composer/" ]; then
    mkdir -p /root/.composer/
fi

if [ ! -e "/.composer/" ]; then
    mkdir /.composer/
fi
chmod -R 777 /.composer/

cd /var/www

composer install -n --prefer-source --classmap-authoritative

chown -R www-data:www-data /var/www/var/cache

bin/console doctrine:database:create -n --if-not-exists
bin/console d:m:m -n --allow-no-migration

if [ "${APP_ENV}"  =  "test" ]; then
  exec bin/phpunit
  exit 0
else
  bin/console d:m:m -n --allow-no-migration

  exec "php-fpm"
fi
