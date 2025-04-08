#!/bin/bash
set -e

# Corrige permissões da storage e bootstrap/cache
echo "✔️ Corrigindo permissões..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

cd /var/www

# Instala as dependências
if [ ! -d "vendor" ]; then
    composer install
fi

# Copia o .env se não existir
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Gera a chave da aplicação
php artisan key:generate

# Espera o banco subir
until nc -z -v -w30 mysql 3306
do
  echo "Aguardando o MySQL iniciar..."
  sleep 5
done

# Roda as migrations e seeders
php artisan migrate --seed

# Inicia o PHP-FPM
exec php-fpm
