#!/usr/bin/env bash
export PROXY="${PROXY:-""}"
export IMAGE_NAME="${IMAGE_NAME}"
export DOCKER_FILE="${DOCKER_FILE:-"Dockerfile"}"

echo "Proxy Set to: ${PROXY}"
echo "Tagged as : ${IMAGE_NAME}"
echo ""
echo ""

CMD='docker build --rm --build-arg PROXY='"${PROXY}"' --file '"${DOCKER_FILE}"' -t '"${IMAGE_NAME}"' .'

echo "Build commmand: ${CMD}"
echo ""
${CMD}
