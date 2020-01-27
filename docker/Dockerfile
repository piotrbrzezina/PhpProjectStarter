FROM piotrbrzezina/php-binary:7.4.1-cli as php
RUN php-install php-fpm php curl;
WORKDIR /opt/app/

FROM php as php_builder

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

ARG APP_ENV=prod

COPY ./ ./build

RUN set -eux; \
    cd build; \
    rm -f -R var/cache var/log; \
	mkdir -p var/cache var/log; \
	chmod -f +x bin/console || true; \
    composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest; \
	composer clear-cache; \
    composer dump-env prod; \
    mkdir -p /opt/app/prod; \
    cp -r /opt/app/build/bin /opt/app/prod; \
    cp -r /opt/app/build/config /opt/app/prod; \
    cp -r /opt/app/build/src /opt/app/prod; \
    cp -r /opt/app/build/var /opt/app/prod; \
    cp -r /opt/app/build/vendor /opt/app/prod; \
    cp /opt/app/build/.env.local.php /opt/app/prod/.env.local.php;

FROM php as app_prod

COPY --from=php_builder /opt/app/prod /opt/app/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

USER root
