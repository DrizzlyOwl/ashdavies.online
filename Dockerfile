FROM wordpress:fpm
LABEL org.opencontainers.image.source https://github.com/DrizzlyOwl/ashdavies.online

# Install redis extension
RUN pecl install redis \
	&& docker-php-ext-enable redis
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

# Install plugins from composer sources
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./composer.lock .
COPY ./composer.json .
RUN composer install --verbose --prefer-dist --no-interaction

# Move vendor plugins to the right places
RUN mv -f ./app/plugins/* ./wp-content/plugins/

# Install ash-mods
COPY ./app/plugins/ash-mods.php ./wp-content/plugins/ash-mods.php

# Set final permissions for the image
USER root
RUN find . -type d -exec chmod 555 {} \;
RUN find . -type f -exec chmod 444 {} \;
RUN find ./wp-content/uploads/ -type f -exec chmod 644 {} \;
RUN find ./wp-content/uploads/ -type d -exec chmod 755 {} \;

# Cleanup
RUN rm -rf ./app/ ./vendor/
