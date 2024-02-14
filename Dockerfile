FROM composer:2.4 as build
COPY . /app/
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8-apache-buster as dev

ENV APP_ENV=dev
ENV APP_DEBUG=false

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql
#COPY ./php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY ./docker/start-container /usr/local/bin/start-container
COPY --from=build /app /var/www/html
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./.env.prod /var/www/html/.env

RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite

RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
