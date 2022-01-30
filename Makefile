#!make
OS=$(shell uname)

ifeq ($(OS),Darwin)
	export UID = 1000
	export GID = 1000
else
	export UID = $(shell id -u)
	export GID = $(shell id -g)
endif


APP_IMAGE_NAME?=document
APP_IMAGE_TAG?=latest
APP_IMAGE_NAMESPACE?=${DOCKER_HUB_USER}
APP_IMAGE=${APP_IMAGE_NAMESPACE}/${APP_IMAGE_NAME}

DOCKER_COMPOSE_FILE?=./docker/docker-compose.yml
DOCKER_COMPOSE=docker-compose --file ${DOCKER_COMPOSE_FILE} --project-name=operation
COMPOSER_AUTH?=$(shell test -f ~/.composer/auth.json && cat ~/.composer/auth.json)
DOCKER_FILE?=./docker/fpm/Dockerfile

# Eecute command in container
RUN_IN_CONTAINER := docker exec -it ${APP_IMAGE_NAME}
RUN_IN_NODE := docker exec -it ${APP_IMAGE_NAME}_node
SUBCOMMAND = $(subst +,-, $(filter-out $@,$(MAKECMDGOALS)))


.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | cut -d: -f2- | sort -t: -k 2,2 | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: shell
shell: ## (Docker) Enter in app container
	$(RUN_IN_CONTAINER) bash

.PHONY: logs
 logs:
	- docker logs ${APP_IMAGE_NAME}
