FROM php:8.1-apache

RUN apt-get update && apt-get install -y git libsodium-dev

RUN pecl install mailparse \
    && docker-php-ext-enable mailparse \
    &&  docker-php-ext-install pdo pdo_mysql sodium

RUN a2enmod rewrite

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN git clone https://github.com/MasterKafey/MailCatcher.git /var/www/html

RUN chown -R www-data:www-data /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80