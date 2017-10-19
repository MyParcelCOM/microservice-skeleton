#!/usr/bin/env bash
set -eo pipefail

function fixPermissions {
  ${COMPOSE} exec microservice chown -R www-data:www-data ./storage/ ./bootstrap/cache/
}

function ownAllTheThings {
  ${COMPOSE} ${DO} microservice chown -R ${USER_ID}:${GROUP_ID} .
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

COMPOSE="docker-compose --project-name ${PROJECT_NAME}"
TTY="" # tty is enabled by default

# CI config
if [ ! -z "${JENKINS_VERSION}" ]; then
  # Disable tty so Jenkins doesn't throw errors.
  TTY="-T"
fi

if [ $# -gt 0 ]; then
  # Check if services are running.
  RUNNING=$(${COMPOSE} ps -q)

  # Either run or exec based on RUNNING var.
  DO="run --rm ${TTY}"
  if [ "${RUNNING}" != "" ]; then
    DO="exec ${TTY}"
  fi

  # Start services.
  if [ "$1" == "up" ]; then
    ${COMPOSE} up -d

    echo ""
    echo "microservice server running on https://localhost:${APP_PORT}"

  # Run a composer command on the microservice service.
  elif [ "$1" == "composer" ]; then
    shift 1
    ownAllTheThings
    ${COMPOSE} ${DO} -u ${USER_ID}:${GROUP_ID} microservice composer "$@"
    fixPermissions

  # Run an artisan command on the microservice service.
  elif [ "$1" == "artisan" ]; then
    shift 1
    ownAllTheThings
    ${COMPOSE} ${DO} -u ${USER_ID}:${GROUP_ID} microservice php artisan "$@"
    fixPermissions

  # Run phpunit tests.
  elif [ "$1" == "test" ]; then
    shift 1
    ${COMPOSE} ${DO} microservice ./vendor/bin/phpunit "$@"

  # Execute a command on a service.
  elif [ "$1" == "microservice" ]; then
    ${COMPOSE} ${DO} "$@"

  # Delete project volumes.
  elif [ "$1" == "prune" ]; then
    docker volume rm $(docker volume ls -f name=${PROJECT_NAME} -q)

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
    ${COMPOSE} exec ${TTY} -u ${USER_ID}:${GROUP_ID} microservice composer install

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
  else
    ${COMPOSE} "$@"
  fi
else
  ${COMPOSE} ps
fi
