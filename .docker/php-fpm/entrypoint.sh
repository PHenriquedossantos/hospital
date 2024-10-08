#!/bin/bash

echo "Instalando dependências do Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
php artisan migrate

# Definir permissões corretas
echo "Definindo permissões para as pastas de cache e armazenamento do Laravel..."

# Permitir ao servidor web (www-data ou nginx) acessar essas pastas
HTTP_USER=www-data

# Definir permissões para as pastas de cache e storage
chown -R $USER:$HTTP_USER bootstrap/cache
chown -R $USER:$HTTP_USER storage

chmod -R 775 bootstrap/cache
chmod -R 775 storage

php-fpm
