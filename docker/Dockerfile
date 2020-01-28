FROM piotrbrzezina/php-binary:7.4.1-cli as php
RUN php-install php-fpm php curl;
WORKDIR /opt/app/

FROM php as builder
ARG APP_ENV=prod

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN set -eux; \
composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

FROM builder as build
COPY ./ ./
RUN set -eux; \
    composer install --no-dev  --prefer-dist --no-scripts --no-progress --no-suggest; \
    rm -f -R var/cache var/log; \
	mkdir -p var/cache var/log; \
	chmod -f +x bin/console || true; \
    composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	composer clear-cache; \
    composer dump-env prod; \
    mkdir -p /opt/app/prod; \
    cp -r /opt/app/bin /opt/app/prod; \
    cp -r /opt/app/config /opt/app/prod; \
    cp -r /opt/app/src /opt/app/prod; \
    cp -r /opt/app/var /opt/app/prod; \
    cp -r /opt/app/vendor /opt/app/prod; \
    cp /opt/app/.env.local.php /opt/app/prod/.env.local.php;

FROM php as app

COPY --from=build /opt/app/prod /opt/app/