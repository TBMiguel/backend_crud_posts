FROM php:8.3.11-alpine

ENV TIMEZONE America/Sao_Paulo

COPY . /usr/src/postscrud/php.ini

RUN  apk update; \
     apk add bash; \
     apk add curl; \
     curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
     alias composer='/composer.phar'; \
     apk add --no-cache zip libzip-dev; \
     docker-php-ext-configure zip; \
     docker-php-ext-install zip; \
     docker-php-ext-install pdo; pdo_mysql; \
     docker-php-ext-install pdo_mysql;  \
     docker-php-ext-install intl; \
     apk add git; \
