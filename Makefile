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
#   make gcp-provision   - Provision the sponsor-funded GCP VM
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

# Load .env.gcp as Make variables when the file exists.
#
# WHY include IS needed:
#   gcp-cert uses $(DOMAIN_NAME) and $(LETSENCRYPT_EMAIL) as Make variables —
#   Make expands them into the command string before bash ever runs. That
#   expansion requires Make to know the values, which only happens via include.
#   Passing --env-file to docker compose is NOT enough: --env-file feeds Docker
#   Compose's own substitution engine, not Make's $(VAR) expander.
#
# WHY blanket `export` is NOT needed (and harmful):
#   export pushes every Make variable into the shell environment. Child
#   processes like Docker Compose inherit that environment and treat it with
#   higher priority than --env-file. So exporting .env.gcp variables (e.g.
#   WORDPRESS_LOCAL_URL=http://localhost:8080) silently overrides the value in
#   --env-file .env.local (e.g. http://192.168.11.155:8080), causing WordPress
#   to redirect LAN devices to localhost instead of the host machine's IP.
#
#   Nothing in this Makefile needs blanket export because:
#     - docker compose commands all use --env-file to load their own variables
#     - $(DOMAIN_NAME) and $(LETSENCRYPT_EMAIL) are expanded by Make (include
#       is sufficient for that — export is not involved in Make expansion)
#     - no external shell scripts are called that would need $VAR from the env
#
# SAFE ALTERNATIVE if export is ever needed in the future:
#   Export only the specific variables that a shell script or child process
#   genuinely needs, rather than everything from the file:
#     export DOMAIN_NAME
#     export LETSENCRYPT_EMAIL
ifneq (,$(wildcard .env.gcp))
include .env.gcp
# export  # removed: blanket export leaks .env.gcp vars into the shell,
           # overriding --env-file .env.local values in docker compose commands
endif

.PHONY: init gcp-init gcp-provision up down restart rebuild ps gcp-up gcp-down gcp-rebuild gcp-ps gcp-cert logs logs-db shell-wordpress shell-db clean help

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

gcp-provision:
	@echo "Provisioning the sponsor-funded GCP VM..."
	./scripts/gcp-provision-vm.sh

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

gcp-cert:
	@test -n "$(DOMAIN_NAME)" || (echo "Set DOMAIN_NAME in .env.gcp"; exit 1)
	@test -n "$(LETSENCRYPT_EMAIL)" || (echo "Set LETSENCRYPT_EMAIL in .env.gcp"; exit 1)
	@echo "Requesting Let's Encrypt certificate for $(DOMAIN_NAME)..."
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) run --rm certbot certonly --webroot -w /var/www/certbot -d $(DOMAIN_NAME) --email $(LETSENCRYPT_EMAIL) --agree-tos --no-eff-email
	@echo "Recreating nginx so it switches from bootstrap HTTP to HTTPS..."
	docker compose --env-file $(GCP_ENV_FILE) -f $(COMPOSE_FILE) -f $(GCP_OVERRIDE) up -d --force-recreate nginx

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
	@echo "  make gcp-provision - Provision the sponsor-funded GCP VM"
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
