.PHONY: build up down bash install update psalm psalm-version php-version validate clean cs-fix cs-check tests phpunit-version commit-check
UID := $(shell id -u)
GID := $(shell id -g)

DOCKER_COMPOSE = UID=$(UID) GID=$(GID) docker compose

build:
	$(DOCKER_COMPOSE) build --pull

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

bash:
	$(DOCKER_COMPOSE) run --rm php bash

install:
	$(DOCKER_COMPOSE) run --rm php composer install

update:
	$(DOCKER_COMPOSE) run --rm php composer update

validate:
	$(DOCKER_COMPOSE) run --rm php composer validate

php-version:
	$(DOCKER_COMPOSE) run --rm php php -v

psalm:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/psalm

psalm-version:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/psalm --version

cs-fix:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/php-cs-fixer fix

cs-check:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/php-cs-fixer fix --dry-run --diff

clean:
	$(DOCKER_COMPOSE) down --remove-orphans

tests:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/phpunit

phpunit-version:
	$(DOCKER_COMPOSE) run --rm php vendor/bin/phpunit --version

commit-check: tests cs-check psalm