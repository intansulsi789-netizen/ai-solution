FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY --from=vendor /app/vendor ./vendor
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm run build

FROM dunglas/frankenphp:1-php8.3-bookworm AS runtime

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf '%s\n' \
    'upload_max_filesize=8M' \
    'post_max_size=10M' \
    'max_execution_time=60' \
    > "$PHP_INI_DIR/conf.d/zz-ai-solution.ini"

WORKDIR /app

COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --chown=www-data:www-data . .
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build
COPY --chown=www-data:www-data docker/Caddyfile /etc/frankenphp/Caddyfile
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/ai-solution-entrypoint

RUN mkdir -p \
    /app/bootstrap/cache \
    /app/storage/app/private \
    /app/storage/app/public \
    /app/storage/framework/cache/data \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /config/caddy \
    /data/caddy \
    /var/lib/ai-solution \
    && chown -R www-data:www-data \
    /app/bootstrap/cache \
    /app/storage \
    /config/caddy \
    /data/caddy \
    /var/lib/ai-solution

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PORT=8080

USER www-data

ENTRYPOINT ["/usr/local/bin/ai-solution-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
