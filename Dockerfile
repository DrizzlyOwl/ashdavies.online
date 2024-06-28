FROM wordpress:6-apache
LABEL org.opencontainers.image.source="https://github.com/DrizzlyOwl/ashdavies.online"

# Install redis extension
RUN pecl install redis \
	&& docker-php-ext-enable redis

RUN apt-get update
RUN apt-get -y install python3-pip less sendmail python3-botocore

WORKDIR /usr/src/wordpress

# Remove default themes and plugins
RUN rm -rf ./wp-content/themes/ ./wp-content/plugins/

# Install custom themes and sources
RUN mkdir -p ./wp-content/uploads/ ./wp-content/languages/ ./wp-content/themes/ ./wp-content/plugins/
COPY ./app/themes/ashdavies/ ./wp-content/themes/ashdavies/
COPY ./app/languages/ ./wp-content/languages/
COPY ./app/object-cache.php ./wp-content/object-cache.php

# Custom PHP ini for upload sizes
COPY php.ini $PHP_INI_DIR/conf.d/

# Install wp-cli
COPY --from=wordpress:cli /usr/local/bin/wp /usr/local/bin/wp

# Install plugins from composer sources
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./composer.lock .
COPY ./composer.json .
RUN composer install --verbose --prefer-dist --no-interaction

# Move vendor plugins to the right places
RUN mv -f ./app/plugins/* ./wp-content/plugins/

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
