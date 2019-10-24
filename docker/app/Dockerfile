# Stage 1: Build swagger bundles for api and carrier specifications
FROM myparcelcom/specs-builder:latest as bundles

ARG CARRIER_SPEC_BRANCH="master"

RUN build_carrier_spec

# Stage 2: Build a php based image with an apache web server
FROM myparcelcom/php:7.2-apache

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-dev \
    && new_cert \
    && a2ensite default-ssl \
    && fix_laravel_permissions \
    && rm -f .env

# Copy swagger bundle
COPY --from=bundles /build/carrier-specification/dist/swagger.json /opt/app/vendor/myparcelcom/carrier-specification/dist/swagger.json

ENV XDEBUG_IDE_KEY myparcelcom_microservice
