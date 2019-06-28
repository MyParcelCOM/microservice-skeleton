FROM myparcelcom/php:7.1-apache

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-dev \
    && new_cert \
    && a2ensite default-ssl \
    && fix_laravel_permissions \
    && rm -f .env

ENV XDEBUG_IDE_KEY myparcelcom_microservice
