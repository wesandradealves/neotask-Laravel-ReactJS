#!/bin/bash
set -e

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
