FROM php:7.0-apache

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev libpq-dev git \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pdo_pgsql \
    && pecl install apcu-5.1.5 \ && docker-php-ext-enable apcu

RUN a2enmod rewrite
RUN a2enmod headers

ADD ./vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

EXPOSE 80

CMD /usr/sbin/apache2ctl -D FOREGROUND
