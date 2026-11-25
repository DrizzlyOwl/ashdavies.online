# syntax=docker/dockerfile:1

# Install plugins from composer sources
FROM composer:2.11.3@sha256:4e5f671c4a5784b422c20fcb993a38da96529438889dbce40be27c452d18dd6d AS php-build
WORKDIR /build
COPY --link composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install \
      --no-dev \
      --no-interaction \
      --no-progress \
      --prefer-dist \
      --no-cache

# Build theme stylesheet from SCSS sources
FROM node:24-alpine@sha256:116b800c3754b440ee0aa3ed2f20f5bf3d99a554bfd9adeea09ea8017f6e85f5 AS theme-build
WORKDIR /build
COPY --link app/themes/ashdavies/package.json app/themes/ashdavies/npm-shrinkwrap.json ./
RUN npm ci --ignore-scripts --no-audit --no-fund
COPY --link app/themes/ashdavies/ ./
RUN npm run build \
 && mkdir -p /theme-out \
 && cp -a ./*.php ./*.css ./template-parts ./theme.json ./readme.txt ./screenshot.png ./LICENSE.md ./dist /theme-out/

# Production runtime image
FROM wordpress:7.1.5-apache@sha256:9b46af6a41fd0dcb2e5cf85033a6350d05010356847ede1cfcac5cf94292eb26 AS final
LABEL org.opencontainers.image.source="https://github.com/DrizzlyOwl/ashdavies.online"

# Cache-busting build arg so CI builds always fetch fresh upstream OS patches
ARG CACHE_BUST=1

# Apply upstream Debian upgrades and install pinned phpredis extension
RUN set -eux; \
    : "${CACHE_BUST}"; \
    export DEBIAN_FRONTEND=noninteractive; \
    apt-get update; \
    apt-get upgrade -y --no-install-recommends; \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*; \
    pecl install redis-6.3.0; \
    docker-php-ext-enable redis; \
    rm -rf /tmp/pear

# Harden PHP configuration and Apache settings
COPY --link php.ini $PHP_INI_DIR/conf.d/zz-ashdavies.ini
RUN set -eux; \
    sed -ri \
      -e 's/^ServerTokens .*/ServerTokens Prod/' \
      -e 's/^ServerSignature .*/ServerSignature Off/' \
      /etc/apache2/conf-available/security.conf; \
    find /etc/apache2 -name '*.conf' -type f -exec sed -ri \
      -e 's!/var/www/html!/usr/src/wordpress!g' \
      -e 's!Directory /var/www/!Directory /usr/src/wordpress!g' '{}' +

WORKDIR /usr/src/wordpress

# Install compiled theme, translations, Composer plugins, and mu-plugins
COPY --from=theme-build --chown=root:www-data /theme-out/ ./wp-content/themes/ashdavies/
COPY --chown=root:www-data app/languages/ ./wp-content/languages/
COPY --from=php-build --chown=root:www-data /build/app/plugins/ ./wp-content/plugins/
COPY --chown=root:www-data app/mu-plugins/ ./wp-content/mu-plugins/

# Remove default themes/plugins, configure drop-ins, health endpoint, and immutable permissions
RUN set -eux; \
    rm -rf \
      ./wp-content/themes/twenty* \
      ./wp-content/plugins/akismet \
      ./wp-content/plugins/hello.php; \
    mkdir -p ./wp-content/uploads ./wp-content/upgrade; \
    ln -sfn ./plugins/redis-cache/includes/object-cache.php ./wp-content/object-cache.php; \
    printf 'Healthy\n' > health.txt; \
    cp -sf wp-config-docker.php wp-config.php; \
    chown -R root:www-data .; \
    find . -type d -exec chmod 0555 {} +; \
    find . -type f -exec chmod 0444 {} +; \
    chmod 0644 ./wp-content/plugins/redis-cache/includes/object-cache.php; \
    chown -R www-data:www-data ./wp-content/uploads ./wp-content/upgrade; \
    chmod 0755 ./wp-content/uploads ./wp-content/upgrade

HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
  CMD php -r 'exit(@file_get_contents("http://127.0.0.1/health.txt") === "Healthy\n" ? 0 : 1);'

USER www-data
