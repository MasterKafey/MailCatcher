FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    git \
    libsodium-dev \
    libicu-dev \
    librabbitmq-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    supervisor \
    npm

RUN pecl install mailparse amqp \
    && docker-php-ext-enable mailparse amqp

RUN docker-php-ext-install pdo pdo_mysql sodium intl curl fileinfo gd pdo_pgsql pgsql zip

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY .docker/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN git clone https://github.com/MasterKafey/MailCatcher.git /var/www/html

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

RUN groupadd -r mail-catcher && useradd -r -g mail-catcher mail-catcher \
    && mkdir -p /var/www/html \
    && chown -R mail-catcher:mail-catcher /var/www/html

USER mail-catcher
RUN composer install --no-dev --optimize-autoloader
USER root

RUN npm install
RUN npm run build


EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]