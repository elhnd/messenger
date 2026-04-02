.PHONY: help build up down restart logs shell db-create db-migrate clean install status

# Exporter UID/GID automatiquement pour docker compose
export USER_UID := $(shell id -u)
export USER_GID := $(shell id -g)

help: ## Afficher cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Construire les images Docker
	docker compose build

up: ## Démarrer tous les services
	docker compose up -d
	@echo "✅ App: http://localhost:8080"
	@echo "✅ RabbitMQ: http://localhost:15672 (guest/guest)"

down: ## Arrêter tous les services
	docker compose down

restart: down up ## Redémarrer tous les services

logs: ## Afficher les logs
	docker compose logs -f

logs-worker: ## Logs du worker Messenger
	docker compose logs -f messenger_worker

shell: ## Accéder au conteneur PHP
	docker compose exec php bash

db-create: ## Créer la base de données
	docker compose exec php php bin/console doctrine:database:create --if-not-exists

db-migrate: ## Exécuter les migrations
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

cache-clear: ## Vider le cache
	docker compose exec php php bin/console cache:clear

clean: ## Tout supprimer (conteneurs + volumes)
	docker compose down -v --remove-orphans

install: build up ## Installation complète
	@echo "⏳ Attente de MySQL..."
	@until docker compose exec -T mysql mysqladmin ping -h localhost -usymfony -psymfony --silent 2>/dev/null; do sleep 2; done
	$(MAKE) db-create db-migrate
	@echo "✅ Installation terminée!"
	@echo "✅ App: http://localhost:8080"
	@echo "✅ RabbitMQ: http://localhost:15672 (guest/guest)"

status: ## Statut des conteneurs
	docker compose ps
