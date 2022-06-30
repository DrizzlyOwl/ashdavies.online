FROM wordpress:apache
WORKDIR /usr/src/wordpress
RUN set -eux; \
	find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
	cp -s wp-config-docker.php wp-config.php
USER www-data
RUN rm -rf ./wp-content/themes/ ./wp-content/plugins/
RUN mkdir -p ./wp-content/uploads/ ./wp-content/languages/ ./wp-content/themes/ ./wp-content/plugins/
COPY ./app/themes/ ./wp-content/themes/
COPY ./app/languages/ ./wp-content/languages/
COPY build.ini $PHP_INI_DIR/conf.d/
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./composer.json .
COPY ./composer.lock .
RUN composer install
RUN mv ./app/plugins/* ./wp-content/plugins/
RUN rm -rf ./app/
USER root
RUN find . -type d -exec chmod 755 {} \; 
RUN find . -type f -exec chmod 644 {} \;
RUN find ./wp-content/themes/ -type f -exec chmod 644 {} \; 
RUN find ./wp-content/plugins/ -type f -exec chmod 644 {} \; 
