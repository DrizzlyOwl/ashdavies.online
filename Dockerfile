# Install plugins from composer sources
FROM composer:latest AS php-build
WORKDIR /build
COPY ./composer.lock .
COPY ./composer.json .
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --verbose --prefer-dist --no-interaction

# Install assets from npm sources
FROM node:latest AS theme-build
COPY ./app/themes/ashdavies /build/
WORKDIR /build/
RUN npm install --prefer-dist
RUN npm run build

FROM wordpress:6-apache AS final
LABEL org.opencontainers.image.source="https://github.com/DrizzlyOwl/ashdavies.online"

# Install wp-cli
COPY --from=wordpress:cli /usr/local/bin/wp /usr/local/bin/wp

# Install redis extension
RUN pecl install redis \
  && docker-php-ext-enable redis

RUN apt-get update
RUN apt-get -y install python3-pip less sendmail python3-botocore

# Install any apt package upgrades that are tagged 'securi'
RUN apt-get -s dist-upgrade | grep "^Inst" | grep -i securi | awk -F " " {'print $2'} | xargs apt-get install

WORKDIR /usr/src/wordpress

# Remove default themes and plugins
RUN rm -rf ./wp-content/themes/ ./wp-content/plugins/

# Install custom themes and sources
RUN mkdir -p ./wp-content/uploads/ ./wp-content/languages/ ./wp-content/themes/ ./wp-content/plugins/
COPY --from=theme-build /build/ ./wp-content/themes/ashdavies/
COPY ./app/languages/ ./wp-content/languages/
COPY ./app/object-cache.php ./wp-content/object-cache.php

# Custom PHP ini for upload sizes
COPY php.ini $PHP_INI_DIR/conf.d/

# Move vendor plugins to the right places
COPY --from=php-build /build/app/plugins/* ./wp-content/plugins/

# Install ash-mods
COPY ./app/plugins/ash-mods.php ./wp-content/plugins/ash-mods.php

USER root

# Cleanup
RUN rm -rf ./app/ ./vendor/

# Set final permissions for the image
RUN find . -type d -exec chmod 555 {} \;
RUN find . -type f -exec chmod 444 {} \;
RUN find ./wp-content/uploads/ -type f -exec chmod 644 {} \;
RUN find ./wp-content/uploads/ -type d -exec chmod 755 {} \;

# Health endpoint
RUN echo "Healthy" >> health.txt

RUN set -eux; \
  find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
  cp -s wp-config-docker.php wp-config.php
