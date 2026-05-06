# ─── PHP Runtime Base ──────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS base

# install-php-extensions: precompiled extension yükleyici (pecl'den ~10x hızlı)
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
    bash \
    curl \
    mysql-client \
    supervisor \
    su-exec \
    && install-php-extensions \
        bcmath \
        exif \
        gd \
        mbstring \
        pdo_mysql \
        pcntl \
        redis \
        zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Non-root kullanıcı + supervisor log dizini
RUN addgroup -g 1000 www \
    && adduser -u 1000 -G www -s /bin/sh -D www \
    && mkdir -p /var/log/supervisor \
    && chown -R www:www /var/log/supervisor

WORKDIR /var/www

# ─── Development ───────────────────────────────────────────────────────────────
FROM base AS development

COPY docker/php/php-dev.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Entrypoint root olarak çalışır (vendor volume chown + su-exec ile www'ye geçer)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# ─── Builder (frontend assets) ─────────────────────────────────────────────────
FROM node:20-alpine AS builder

WORKDIR /build

COPY backend/package.json backend/package-lock.json ./
RUN npm ci --ignore-scripts

COPY backend/ .
RUN npm run build

# ─── Production ────────────────────────────────────────────────────────────────
FROM base AS production

COPY docker/php/php-prod.ini /usr/local/etc/php/conf.d/app.ini

COPY --chown=www:www backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

COPY --chown=www:www backend/ .

COPY --from=builder --chown=www:www /build/public/build ./public/build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link

USER www
CMD ["php-fpm"]
