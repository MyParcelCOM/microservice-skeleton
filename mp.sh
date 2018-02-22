#!/usr/bin/env bash
set -eo pipefail

function fixPermissions {
  ${COMPOSE} exec microservice chown -R www-data:www-data ./storage/ ./bootstrap/cache/
}

function ownAllTheThings {
  ${COMPOSE} run --rm microservice chown -R $(id -u):$(id -g) .
}

ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Check if the file with environment variables exists, otherwise copy the default file.
if [ ! -f ${ROOT_DIR}/.env ]; then
  if [ ! -f ${ROOT_DIR}/.env.dist ]; then
    >&2 echo 'Unable to locate .env or .env.dist file'
    exit 1
  fi

  cp ${ROOT_DIR}/.env.dist ${ROOT_DIR}/.env

  # Add current user and group to .env file, with root as fallback.
  echo "" >> ${ROOT_DIR}/.env
  echo "USER_ID=${UID-0}" >> ${ROOT_DIR}/.env
  echo "GROUP_ID=${GROUPS-0}" >> ${ROOT_DIR}/.env
fi
export $(cat ${ROOT_DIR}/.env | xargs)

COMPOSE="docker-compose"

if [ $# -gt 0 ]; then
  # Check if services are running.
  RUNNING=$(${COMPOSE} ps -q)

  # Either run or exec based on RUNNING var.
  DO="run --rm"
  if [ "${RUNNING}" != "" ]; then
    DO="exec"
  fi

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
    if [ "$1" == "skeleton" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --exclude-group Implementation
    elif [ "$1" == "pudo" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:PickUpDropOff
    elif [ "$1" == "shipment" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:Shipment
    elif [ "$1" == "status" ]; then
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit --group Endpoints:Status
    else
      ${COMPOSE} run --rm microservice ./vendor/bin/phpunit "$@"
    fi

  # Execute a command on a service.
  elif [ "$1" == "microservice" ]; then
    ${COMPOSE} ${DO} "$@"

  # Run commands for the api specification
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
