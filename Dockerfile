FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    nodejs \
    npm \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

RUN mkdir -p /tmp && chmod 777 /tmp

RUN echo "sys_temp_dir=/tmp" > /usr/local/etc/php/conf.d/tempdir.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN composer install

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
