ARG REGISTRY=docker.io
# Stage 1: Build swagger bundles for carrier specification
FROM ${REGISTRY}/myparcelcom/specs-builder:latest AS bundles

ARG CARRIER_SPEC_BRANCH="master"

RUN build_carrier_spec

# Stage 2: Build a php based image with an apache web server
FROM ${REGISTRY}/myparcelcom/php:8.2-apache

COPY . /opt/app
WORKDIR /opt/app

RUN composer install --no-dev \
    && new_cert \
    && a2ensite default-ssl \
    && fix_laravel_permissions \
    && rm -f .env

# Copy swagger bundle
COPY --from=bundles /build/carrier-specification/dist/swagger.json /opt/app/vendor/myparcelcom/carrier-specification/dist/swagger.json
