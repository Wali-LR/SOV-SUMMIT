# syntax=docker/dockerfile:1

# ==============================================================================
# Stage 1: Build Frontend Assets (Vite, TailwindCSS)
# ==============================================================================
FROM node:22-alpine AS frontend

WORKDIR /app

# Copy dependency manifests
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm ci --prefer-offline --no-audit

# Copy asset source files
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public

# Build production assets into public/build
RUN npm run build

# ==============================================================================
# Stage 2: Production PHP-FPM + Nginx Application
# ==============================================================================
FROM php:8.3-fpm-alpine AS production

LABEL maintainer="SOV Summit Team"
LABEL description="Production image for SOV-SUMMIT Laravel application on Dokploy"

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & fonts for Dompdf
RUN apk add --no-cache \
    bash \
    curl \
    nginx \
    supervisor \
    fontconfig \
    ttf-dejavu \
    shadow

# Install PHP extensions using official extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    bcmath \
    ctype \
    curl \
    dom \
    exif \
    fileinfo \
    filter \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    redis \
    session \
    tokenizer \
    xml \
    zip

# Copy Composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Composer dependencies (layer-cached)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-autoloader \
    --no-scripts

# Copy application source code
COPY . .

# Copy compiled frontend assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Generate optimized Composer autoloader
RUN composer dump-autoload --optimize --no-dev

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Configure directory permissions and make entrypoint executable
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /var/log/supervisor /var/run /run/nginx \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/log/nginx /var/lib/nginx /run/nginx /var/log/supervisor

# Expose web server port
EXPOSE 80

# Health check using Laravel 11/12/13 /up endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl -f http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
