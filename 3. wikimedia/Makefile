# Executables (local)
DOCKER_COMP = XDEBUG_MODE=debug docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) -f compose.prod.yaml -f compose.yaml build  --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) -f compose.prod.yaml -f compose.yaml up -d

restart: ## Restart the docker hub in detached mode (no logs)
	make down && make up

import-db-cars:
	 mysql -u root -p'!ChangeMe!' app < /database/car2db_esp_20251101.sql

redis-clear:
	@echo "🧹 Limpiando Redis..."
	docker compose exec redis redis-cli FLUSHALL
	@echo "✅ Redis vaciado correctamente."

tailwind-watch: ## Ejecuta el watcher de Tailwind CSS en segundo plano
	@echo "👀 Iniciando Tailwind watcher..."
	@$(PHP_CONT) bash -c "\
		php bin/console tailwind:build --watch & \
		wait"

workers: ## Ejecuta los dos workers de Messenger (async_high y async_low)
	@$(PHP_CONT) bash -c "\
		php bin/console messenger:consume async_high --time-limit=3600 --memory-limit=256M  & \
		php bin/console messenger:consume async_low --time-limit=3600 --memory-limit=512M & \
		wait"

stop-workers: ## Ejecuta los dos workers de Messenger (async_high y async_low)
	@$(PHP_CONT) bash -c "\
		php bin/console messenger:stop-workers & \
		wait"

start: build up ## Build and start the containers
	@$(SYMFONY) tailwind:build
	@echo "✅ Tailwind CSS built successfully."
fix-all-code: ## Fix all code with PHP CS Fixer
	@$(DOCKER_COMP) exec -e PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer fix src

reset: ## Complete reset: stops containers, removes volumes, images and rebuilds everything
	@echo "💣 Starting complete reset..."
	@echo "🛑 Stopping and removing containers..."
	@$(DOCKER_COMP) down --remove-orphans --volumes
	@echo "🗑️  Removing Docker volumes..."
	@docker volume prune -f
	@echo "🗑️  Removing unused Docker images..."
	@docker image prune -f
	@echo "🔨 Building containers..."
	@$(DOCKER_COMP) build --no-cache --pull
	@echo "🚀 Starting containers..."
	make up
	@echo "⏳ Waiting for services to be ready..."
	@sleep 10
	@echo "📦 Installing dependencies..."
	@$(COMPOSER) install --no-interaction
	@echo "🗃️  Running database migrations..."
	@$(SYMFONY) doctrine:migrations:migrate --no-interaction || echo "⚠️  No migrations to run"
	@echo "🧹 Clearing cache..."
	@$(SYMFONY) cache:clear --no-interaction
	@echo "✅ Complete reset finished!"

reset-db: ## Reset database: removes volumes, rebuilds containers and runs migrations
	@echo "💣 Resetting the database..."
	@$(DOCKER_COMP) down -v --remove-orphans
	@$(DOCKER_COMP) up --build -d
	@sleep 5 # wait for DB to be ready
	@$(SYMFONY) doctrine:migrations:migrate --no-interaction
	@echo "✅ Database reset complete!"

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

ssh: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)


## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: ## Clears the cache and rebuilds Tailwind CSS
	@echo "🧹 Clearing Symfony cache..."
	@$(SYMFONY) cache:clear --no-interaction
	@echo "🎨 Rebuilding Tailwind CSS..."
	@$(SYMFONY) tailwind:build
	@echo "✅ Cache cleared and CSS rebuilt successfully."

## Conectarse a Manticore usando el cliente MySQL local
manticore-sql:
	@echo "🔌 Conectando a Manticore Search (127.0.0.1:9306)..."
	mysql -h 127.0.0.1 -P 9306 --protocol=tcp -u root --prompt='manticore> '
