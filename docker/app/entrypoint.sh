#!/usr/bin/env bash

substitutions="\${APP_LOG_LEVEL} \${APP_DOMAIN} \${XDEBUG_PORT} \${XDEBUG_IDE_KEY}"

if [ ! -f /etc/php/7.1/fpm/php-fpm.conf ]; then
    envsubst "${substitutions}" < /etc/php/7.1/fpm/php-fpm.conf.template > /etc/php/7.1/fpm/php-fpm.conf
fi

if [ ! -f /etc/nginx/sites-available/virtual_host ]; then
    envsubst "${substitutions}" < /etc/nginx/sites-available/virtual_host.template > /etc/nginx/sites-available/virtual_host
fi

if [ ! -f /etc/supervisor/conf.d/supervisord.conf ]; then
    envsubst "${substitutions}" < /etc/supervisor/conf.d/supervisord.conf.template > /etc/supervisor/conf.d/supervisord.conf
fi

if [ -f /etc/php/7.1/mods-available/xdebug.ini.template ]; then
    envsubst "${substitutions}" < /etc/php/7.1/mods-available/xdebug.ini.template > /etc/php/7.1/mods-available/xdebug.ini
    rm -f /etc/php/7.1/mods-available/xdebug.ini.template

    host_ip=$(/sbin/ip route|awk '/default/ { print $3 }')
    sed -i "s/xdebug\.remote_host\=.*/xdebug\.remote_host\=${host_ip}/g" /etc/php/7.1/mods-available/xdebug.ini
fi

if [ ! "${DOCKER_ENV}" == "production" ] && [ ! "${DOCKER_ENV}" == "prod" ]; then
    # Enable xdebug
    ln -sf /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/fpm/conf.d/20-xdebug.ini
else
    # Disable xdebug
    if [ -e /etc/php/7.1/fpm/conf.d/20-xdebug.ini ]; then
        rm -f /etc/php/7.1/fpm/conf.d/20-xdebug.ini
    fi
fi

# If a newrelic license key is set, we install and start the newrelic daemon.
if [ "${NEWRELIC_LICENSE}" ]; then
    NR_INSTALL_SILENT=1 NR_INSTALL_KEY=${NEWRELIC_LICENSE} newrelic-install install
fi

"$@"
