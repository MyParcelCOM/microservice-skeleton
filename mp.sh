#!/usr/bin/env bash
set -eo pipefail

function fixPermissions {
  ${COMPOSE} run --rm microservice chown -R www-data:www-data ./storage/ ./bootstrap/cache/
}

function ownAllTheThings {
  ${COMPOSE} run --rm microservice chown -R $(id -u):$(id -g) .
}

function createMicronet {
  if [ "$(docker network ls -q -f name=micronet)" = "" ]; then
    echo ""
    echo "Creating micronet network"
    docker network create micronet
  fi
}

ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Check if the file with environment variables for Xdebug exists, otherwise copy the default file.
if [ ! -f ${ROOT_DIR}/.env.xdebug ]; then
  if [ ! -f ${ROOT_DIR}/.env.xdebug.dist ]; then
    echo -e "\033[0;97;101m Unable to locate .env.xdebug.dist file \033[0m" >&2
    exit 1
  fi

  cp -a ${ROOT_DIR}/.env.xdebug.dist ${ROOT_DIR}/.env.xdebug

  echo -e "\033[0;30;47m .env.xdebug file has been created \033[0m"
fi

# Check if the file with environment variables exists, otherwise copy the default file.
if [ ! -f ${ROOT_DIR}/.env ]; then
  if [ ! -f ${ROOT_DIR}/.env.dist ]; then
    echo -e "\033[0;97;101m Unable to locate .env.dist file \033[0m" >&2
    exit 1
  fi

  cp -a ${ROOT_DIR}/.env.dist ${ROOT_DIR}/.env

  echo -e "\033[0;30;47m .env file has been created \033[0m"
fi
IFS=$'\n' && export $(grep -v '^#' ${ROOT_DIR}/.env | xargs -0) && unset IFS

COMPOSE="docker-compose"

if [ $# -gt 0 ]; then
  createMicronet

  # Check if services are running.
  RUNNING=$(${COMPOSE} ps --services --filter status=running)

  # Start services.
  if [ "$1" == "up" ]; then
    ${COMPOSE} up -d

    echo ""
    echo "microservice server running on https://localhost:${APP_PORT}"

  # Run a composer command on the microservice service.
  elif [ "$1" == "composer" ]; then
    shift 1
    ${COMPOSE} run --rm microservice composer "$@"
    ownAllTheThings
    fixPermissions

  # Run an artisan command on the microservice service.
  elif [ "$1" == "artisan" ]; then
    shift 1
    ${COMPOSE} run --rm microservice php artisan "$@"
    ownAllTheThings
    fixPermissions

  # Run phpunit tests.
  elif [ "$1" == "test" ]; then
    shift 1
    if [ "$IGNORE_TESTS" == "true" ]; then
      exit 0
    elif [ "$1" == "skeleton" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --exclude-group Implementation
    elif [ "$1" == "pudo" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:PickUpDropOff
    elif [ "$1" == "shipment" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:Shipment
    elif [ "$1" == "status" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:Status
    elif [ "$1" == "credentials" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:ValidateCredentials
    else
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit "$@"
    fi

  # Execute a command on a service.
  elif [ "$1" == "microservice" ]; then
    ${COMPOSE} run --rm "$@"

  # Run commands for the carrier specification.
  elif [ "$1" == "schema" ]; then
    shift 1
    echo ""
    (cd ${SCHEMA_DIR}; ./mp.sh "$@")

  # Setup the application.
  elif [ "$1" == "setup" ]; then
    # Start services if not running.
    if [ "${RUNNING}" == "" ]; then
      echo "Starting servers..."
      ${COMPOSE} up -d
    fi

    # Install Composer dependencies.
    echo ""
    echo "Installing Composer dependencies..."
    ownAllTheThings
    # first make sure the cache is writable
    ./mp.sh microservice chmod 777 /.composer/cache
    ./mp.sh composer install

    # Making directories writable for www-data.
    echo ""
    echo "Making directories writable for www-data..."
    fixPermissions

    ./mp.sh schema bundle

    # Stop services or restart if they were already running.
    if [ "${RUNNING}" == "" ]; then
      echo ""
      echo "Stopping servers..."
      ${COMPOSE} down
    else
      echo ""
      echo "Restarting servers..."
      ${COMPOSE} restart
    fi

  # Make the application up to date.
  elif [ "$1" == "update" ]; then
    ./mp.sh composer install
    ./mp.sh schema bundle

  # Upgrade dependencies.
  elif [ "$1" == "upgrade" ]; then
    ./mp.sh composer update
    ./mp.sh schema bundle

  else
    ${COMPOSE} "$@"
  fi
else
  ${COMPOSE} ps
fi
