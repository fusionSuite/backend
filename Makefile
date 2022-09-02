.DEFAULT_GOAL := help

USER = $(shell id -u):$(shell id -g)

ENV_NAME ?= development

ifdef DOCKER
	CLI = ./docker/bin/cli
	COMPOSER = ./docker/bin/composer
	PHP = ./docker/bin/php

	DB_TYPE = MariaDB
	DB_HOST = database
	DB_NAME = fusionsuite_development
	DB_USER = fusionsuite
	DB_PASS = fusionsuite
	DB_PORT = 3306
else
	CLI = ./bin/cli
	COMPOSER = composer
	PHP = php

	DB_TYPE ?= MariaDB
	DB_HOST ?= localhost
	DB_NAME ?= fusionsuite_development
	DB_USER ?= fusionsuite
	DB_PASS ?= fusionsuite
	DB_PORT ?= 3306
endif

ACTIONSCRIPTS_DIRS := $(wildcard ActionScripts/*)
ACTIONSCRIPTS_DIRS := $(filter-out ActionScripts/autoload.php, $(ACTIONSCRIPTS_DIRS))

.PHONY: docker-start
docker-start: ## Start a development server with Docker
	@echo "Running webserver on http://localhost:8000"
	docker-compose -p fusionsuite-backend -f docker/docker-compose.yml up

.PHONY: docker-clean
docker-clean: ## Clean the Docker stuff
	docker-compose -p fusionsuite-backend -f docker/docker-compose.yml down

.PHONY: lint
lint: ## Run the linter
	$(PHP) ./vendor/bin/phpcs ./src ./public

.PHONY: lint-fix
lint-fix: ## Fix the errors detected by the linter
	$(PHP) ./vendor/bin/phpcbf ./src ./public

.PHONY: install
install: ## Install the dependencies
	$(COMPOSER) install
	for dir in $(ACTIONSCRIPTS_DIRS); do \
		$(COMPOSER) install --working-dir=$$dir; \
	done

.PHONY: setup
setup: ## Setup the database
	$(CLI) env:create -c \
		-n $(ENV_NAME) \
		-t $(DB_TYPE) \
		-H $(DB_HOST) \
		-d $(DB_NAME) \
		-u $(DB_USER) \
		-p $(DB_PASS) \
		-P $(DB_PORT)
	$(CLI) install

.PHONY: db-reset
db-reset: ## Reset the database
	$(CLI) reset

.PHONY: help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
