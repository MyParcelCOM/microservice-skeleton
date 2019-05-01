FROM node:11.12 as bundles

WORKDIR /build

RUN npm install -g json-refs \
    && git clone https://github.com/MyParcelCOM/carrier-specification.git \
    && mkdir -p carrier-specification/dist \
    && json-refs resolve carrier-specification/schema.json -f > carrier-specification/dist/swagger.json

FROM ubuntu:18.04

LABEL maintainer="MyParcel.com <info@myparcel.com>"

COPY . /opt/app
WORKDIR /opt/app

# Install locales for terminal and php.
RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8 \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y nginx curl zip unzip software-properties-common supervisor sqlite3 ssl-cert gettext-base iproute2 wget \
    && add-apt-repository -y ppa:ondrej/php \
    && echo 'deb http://apt.newrelic.com/debian/ newrelic non-free' | tee /etc/apt/sources.list.d/newrelic.list \
    && wget -O- https://download.newrelic.com/548C16BF.gpg | apt-key add - \
    && apt-get update \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd \
       php7.1-mbstring php7.1-xml php7.1-curl php7.1-xdebug \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && composer global require hirak/prestissimo \
    && DEBIAN_FRONTEND=noninteractive apt-get -y install newrelic-php5 \
    && mkdir /run/php \
    && apt-get remove -y --purge software-properties-common wget \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /etc/php/7.1/fpm/php-fpm.conf \
    && echo "daemon off;" >> /etc/nginx/nginx.conf \
    && sed -e 's/;clear_env = no/clear_env = no/' -i /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -e 's/error_reporting = E_ALL \& \~E_DEPRECATED \& \~E_STRICT/error_reporting = E_ALL/' -i /etc/php/7.1/cli/php.ini \
    && composer install --no-dev

# Copy swagger bundle
COPY --from=bundles /build/carrier-specification/dist/swagger.json /opt/api/vendor/myparcelcom/carrier-specification/dist/swagger.json

# Copy config files.
COPY ./docker/app/conf/php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf.template
COPY ./docker/app/conf/virtual_host /etc/nginx/sites-available/virtual_host.template
COPY ./docker/app/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf.template
COPY ./docker/app/conf/xdebug.ini /etc/php/7.1/mods-available/xdebug.ini.template

# Symlink the nginx conf.
RUN ln -s /etc/nginx/sites-available/virtual_host /etc/nginx/sites-enabled/virtual_host

# Copy entrypoint script.
COPY ./docker/app/entrypoint.sh /bin/entrypoint.sh

# Set the environment variables.
ENV APP_LOG_LEVEL notice
ENV DOCKER_ENV production
ENV XDEBUG_PORT 9000
ENV XDEBUG_IDE_KEY myparcelcom_microservice
ENV COMPOSER_ALLOW_SUPERUSER 1

EXPOSE 443

ENTRYPOINT ["/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord"]

RUN chown -R www-data: /opt/app
