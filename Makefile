deploy:
	php bin/console doctrine:migrations:migrate --env=prod --no-interaction
