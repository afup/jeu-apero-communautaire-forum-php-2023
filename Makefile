deploy:
	php bin/console doctrine:migrations:migrate --env=prod --no-interaction

DOCKER_EXEC = docker compose exec -ti -u $(shell id -u) php

.SILENT:

start:
	docker compose up -d

console:
	${DOCKER_EXEC} bash

test:
	${DOCKER_EXEC} php bin/console d:d:c --env=test --if-not-exists
	${DOCKER_EXEC} php bin/console d:mi:mi --env=test --no-interaction
	${DOCKER_EXEC} php vendor/bin/codecept run