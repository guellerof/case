FROM php:8.2-fpm

# Instala dependências
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev default-mysql-client libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip xml

WORKDIR /var/www/html

# Copia apenas o composer.json primeiro (se composer.lock não existir)
COPY composer.json /var/www/html/

# Se você tiver o composer.lock, adicione também:
# COPY composer.lock /var/www/html/

# Depois instale as dependências PHP com Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install