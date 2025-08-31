FROM php:8.2-cli

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Cachés opcionales (no críticos si fallan en build)
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

EXPOSE 8080

# Arrancar servidor
CMD php artisan serve --host 0.0.0.0 --port 8080