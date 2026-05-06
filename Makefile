# ============================================================================
# MAKEFILE - Local WordPress Shortcuts
# ============================================================================
# Usage:
#   make init            - Create .env.local from .env.example if missing
#   make gcp-init        - Create .env.gcp from .env.example if missing
#   make up              - Start the local WordPress environment
#   make down            - Stop all local services
#   make restart         - Restart all local services
#   make rebuild         - Recreate containers with the current env file
#   make ps              - Show service status
#   make gcp-up          - Start the GCP preview stack
#   make gcp-down        - Stop the GCP preview stack
#   make gcp-rebuild     - Recreate GCP preview containers
#   make gcp-ps          - Show GCP preview status
#   make logs            - Follow WordPress logs
#   make logs-db         - Show MySQL logs
#   make shell-wordpress  - Open a shell in the WordPress container
#   make shell-db        - Open a MySQL shell
#   make clean           - Stop services and remove volumes
#   make help            - Show available commands
# ============================================================================

ENV_FILE := .env.local
GCP_ENV_FILE := .env.gcp
COMPOSE_FILE := docker-compose.yml
LOCAL_OVERRIDE := docker-compose.local.yml
GCP_OVERRIDE := docker-compose.gcp.yml
WORDPRESS_SERVICE := wordpress
DB_SERVICE := db
WORDPRESS_CONTAINER := hopp_wordpress
DB_CONTAINER := hopp_mysql

.PHONY: init gcp-init up down restart rebuild ps gcp-up gcp-down gcp-rebuild gcp-ps logs logs-db shell-wordpress shell-db clean help

init:
	@if [ -f "$(ENV_FILE)" ]; then \
		echo "$(ENV_FILE) already exists"; \
	else \
		cp .env.example $(ENV_FILE); \
		echo "Created $(ENV_FILE) from .env.example"; \
	fi

gcp-init:
	@if [ -f "$(GCP_ENV_FILE)" ]; then \
		echo "$(GCP_ENV_FILE) already exists"; \
	else \
		cp .env.example $(GCP_ENV_FILE); \
		echo "Created $(GCP_ENV_FILE) from .env.example"; \
	fi

up:
	@echo "Starting local WordPress environment with $(ENV_FILE)..."
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) up -d

down:
	@echo "Stopping local services..."
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) down

restart:
	@echo "Restarting local services..."
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) restart

rebuild:
	@echo "Recreating local services with $(ENV_FILE)..."
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) up -d --force-recreate

ps:
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) ps

gcp-up:
	@echo "Starting GCP preview environment with $(GCP_ENV_FILE)..."
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) up -d

gcp-down:
	@echo "Stopping GCP preview services..."
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) down

gcp-rebuild:
	@echo "Recreating GCP preview services with $(GCP_ENV_FILE)..."
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) up -d --force-recreate

gcp-ps:
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) ps

logs:
	@echo "Following WordPress logs (Ctrl+C to stop)..."
	docker logs -f $(WORDPRESS_CONTAINER)

logs-db:
	@echo "Showing MySQL logs..."
	docker logs --tail 100 $(DB_CONTAINER)

shell-wordpress:
	@echo "Opening a shell in the WordPress container..."
	docker exec -it $(WORDPRESS_CONTAINER) bash

shell-db:
	@echo "Opening a MySQL shell..."
	docker exec -it $(DB_CONTAINER) mysql -u root -p

clean:
	@echo "Stopping services and removing volumes..."
	docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE) -f $(LOCAL_OVERRIDE) down -v

help:
	@echo "Available commands:"
	@echo ""
	@echo "Setup:"
	@echo "  make init          - Create .env.local from .env.example if missing"
	@echo "  make gcp-init      - Create .env.gcp from .env.example if missing"
	@echo ""
	@echo "Local WordPress:"
	@echo "  make up            - Start the local WordPress environment"
	@echo "  make down          - Stop all local services"
	@echo "  make restart       - Restart all local services"
	@echo "  make rebuild       - Recreate containers with the current env file"
	@echo "  make ps            - Show service status"
	@echo "  make gcp-up        - Start the GCP preview stack"
	@echo "  make gcp-down      - Stop the GCP preview stack"
	@echo "  make gcp-rebuild   - Recreate GCP preview containers"
	@echo "  make gcp-ps        - Show GCP preview status"
	@echo ""
	@echo "Logs:"
	@echo "  make logs          - Follow WordPress logs"
	@echo "  make logs-db       - Show MySQL logs"
	@echo ""
	@echo "Shell:"
	@echo "  make shell-wordpress - Open a shell in the WordPress container"
	@echo "  make shell-db        - Open a MySQL shell"
	@echo ""
	@echo "Cleanup:"
	@echo "  make clean         - Stop services and remove volumes"
