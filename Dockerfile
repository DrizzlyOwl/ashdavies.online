FROM wordpress:apache
RUN pecl install redis \
	&& docker-php-ext-enable redis
WORKDIR /usr/src/wordpress
RUN set -eux; \
	find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
	cp -s wp-config-docker.php wp-config.php
USER www-data
RUN rm -rf ./wp-content/themes/ ./wp-content/plugins/
RUN mkdir -p ./wp-content/uploads/ ./wp-content/languages/ ./wp-content/themes/ ./wp-content/plugins/
COPY ./app/themes/ashdavies/ ./wp-content/themes/ashdavies/
COPY ./app/languages/ ./wp-content/languages/
COPY ./app/object-cache.php ./wp-content/object-cache.php
COPY ./app/plugins/ ./wp-content/plugins/
COPY php.ini $PHP_INI_DIR/conf.d/
USER root
RUN find . -type d -exec chmod 555 {} \;
RUN find . -type f -exec chmod 444 {} \;
RUN find ./wp-content/uploads/ -type f -exec chmod 644 {} \;
RUN find ./wp-content/uploads/ -type d -exec chmod 755 {} \;
