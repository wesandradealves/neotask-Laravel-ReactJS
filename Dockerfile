FROM php:8.3-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    libzip-dev \
    libpq-dev \
    netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www

# Copia o entrypoint
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permissões
RUN chown -R www-data:www-data /var/www

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000
