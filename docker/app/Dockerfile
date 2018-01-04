FROM ubuntu:16.04

LABEL maintainer="MyParcel.com <info@myparcel.com>"

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
    && apt-get install -y nginx curl zip unzip software-properties-common supervisor sqlite3 ssl-cert gettext-base iproute wget \
    && add-apt-repository -y ppa:ondrej/php \
    && echo 'deb http://apt.newrelic.com/debian/ newrelic non-free' | tee /etc/apt/sources.list.d/newrelic.list \
    && wget -O- https://download.newrelic.com/548C16BF.gpg | apt-key add - \
    && apt-get update \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd \
       php7.1-mbstring php7.1-xml php7.1-curl php7.1-xdebug \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && DEBIAN_FRONTEND=noninteractive apt-get -y install newrelic-php5 \
    && mkdir /run/php \
    && apt-get remove -y --purge software-properties-common wget \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /etc/php/7.1/fpm/php-fpm.conf \
    && echo "daemon off;" >> /etc/nginx/nginx.conf \
    && sed -e 's/;clear_env = no/clear_env = no/' -i /etc/php/7.1/fpm/pool.d/www.conf \
    && sed -e 's/error_reporting = E_ALL \& \~E_DEPRECATED \& \~E_STRICT/error_reporting = E_ALL/' -i /etc/php/7.1/cli/php.ini

# Copy config files.
COPY conf/php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf.template
COPY conf/microservice.myparcel.com /etc/nginx/sites-available/microservice.myparcel.com.template
COPY conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf.template
COPY conf/xdebug.ini /etc/php/7.1/mods-available/xdebug.ini.template

# Symlink the nginx conf.
RUN ln -s /etc/nginx/sites-available/microservice.myparcel.com /etc/nginx/sites-enabled/microservice.myparcel.com

# Copy entrypoint script.
COPY entrypoint.sh /bin/entrypoint.sh

# Set the environment variables.
ENV APP_LOG_LEVEL notice
ENV APP_DOMAIN microservice.myparcel.com
ENV DOCKER_ENV production
ENV XDEBUG_PORT 9000
ENV XDEBUG_IDE_KEY myparcelcom_microservice

WORKDIR /opt/microservice

EXPOSE 443

ENTRYPOINT ["/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord"]

COPY dist /opt/microservice
RUN chown -R www-data: /opt/microservice
