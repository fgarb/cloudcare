FROM composer:2.4 as build
COPY . /app/
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8.3.3-apache as dev

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
    php artisan route:cache

#RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg

#RUN apt-get update \
#        && mkdir -p /etc/apt/keyrings \
#RUN apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch
#&& curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
#        && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
#        && apt-get update

#echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
RUN apt-get update \
    && apt-get install -y nodejs \
    && apt-get install -y npm \
    && npm install -g npm \
    && npm install -g pnpm \
    && npm install -g bun

RUN chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite

RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
