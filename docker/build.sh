#!/usr/bin/env bash

if [ $# -lt 2 ]; then
  echo "Please supply the image and tag name of the container you want to build"
  exit 1
fi

ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DOCKER_ROOT=${ROOT_DIR}/${1}
DIST_ROOT=${DOCKER_ROOT}/dist

HASH=$(git rev-parse HEAD)
IMAGE_NAME=myparcelcom/${1}:${2}

git archive --format=tar --worktree-attributes ${HASH} | tar -xf - -C ${DIST_ROOT}

# Install composer dependencies for distribution
./mp.sh microservice bash -c "(cd docker/${1}/dist; composer install --no-dev; chown -R ${UID}:${GROUPS} vendor)"

# Bundle the json schema
(cd ${DIST_ROOT}/vendor/myparcelcom/carrier-specification; ./mp.sh bundle)

(cd ${DOCKER_ROOT}; docker build --rm --pull -t ${IMAGE_NAME} .)
docker push ${IMAGE_NAME}
