#!/usr/bin/env bash
export IMAGE_NAME='haakco/stage3-ubuntu-20.04-php7.4-lv-wave'
export DOCKER_FILE="${DOCKER_FILE:-"Dockerfile"}"

echo "Tagged as : ${IMAGE_NAME}"
echo ""
echo ""

CMD='docker build --rm --file '"${DOCKER_FILE}"' -t '"${IMAGE_NAME}"' .'

echo "Build commmand: ${CMD}"
echo ""
${CMD}
