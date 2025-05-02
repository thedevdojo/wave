#!/bin/sh

docker network inspect private || docker network create -d bridge private
