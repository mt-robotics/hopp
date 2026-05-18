#!/bin/sh
set -eu

usage() {
	echo "Usage: $0 --yes <backup_dir>" >&2
	exit 1
}

if [ "$#" -ne 2 ]; then
	usage
fi

if [ "$1" != "--yes" ]; then
	usage
fi

BACKUP_DIR=$2

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
REPO_ROOT=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

cd "$REPO_ROOT"

if [ ! -f .env.gcp ]; then
	echo "Missing .env.gcp in $REPO_ROOT" >&2
	exit 1
fi

if [ ! -d "$BACKUP_DIR" ]; then
	echo "Backup directory does not exist: $BACKUP_DIR" >&2
	exit 1
fi

if [ ! -f "$BACKUP_DIR/db.sql.gz" ] || [ ! -f "$BACKUP_DIR/uploads.tar.gz" ]; then
	echo "Backup directory must contain db.sql.gz and uploads.tar.gz." >&2
	exit 1
fi

set -a
. ./.env.gcp
set +a

docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml stop nginx
docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml up -d db wordpress

gunzip -c "$BACKUP_DIR/db.sql.gz" \
	| docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml exec -T db \
		sh -lc 'exec mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"'

docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml exec -T wordpress \
	sh -lc 'rm -rf /var/www/html/wp-content/uploads && mkdir -p /var/www/html/wp-content/uploads && tar -C /var/www/html/wp-content -xzf -' \
	< "$BACKUP_DIR/uploads.tar.gz"

make gcp-rebuild

printf 'Production restore complete from %s\n' "$BACKUP_DIR"
echo "Next: ./scripts/smoke-test-production.sh"
