#!/bin/sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
REPO_ROOT=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

cd "$REPO_ROOT"

if [ ! -f .env.gcp ]; then
	echo "Missing .env.gcp in $REPO_ROOT" >&2
	exit 1
fi

set -a
. ./.env.gcp
set +a

BACKUP_ROOT=${HOPP_BACKUP_DIR:-"$REPO_ROOT/backups"}
RETENTION_DAYS=${HOPP_BACKUP_RETENTION_DAYS:-14}
TIMESTAMP=$(date -u +"%Y%m%dT%H%M%SZ")
BACKUP_DIR="$BACKUP_ROOT/$TIMESTAMP"
LATEST_LINK="$BACKUP_ROOT/latest"

mkdir -p "$BACKUP_DIR"

docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml ps db wordpress >/dev/null

docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml exec -T db \
	sh -lc 'exec mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" --single-transaction --quick --lock-tables=false "$MYSQL_DATABASE"' \
	| gzip -c > "$BACKUP_DIR/db.sql.gz"

docker compose --env-file .env.gcp -f docker-compose.yml -f docker-compose.gcp.yml exec -T wordpress \
	sh -lc 'tar -C /var/www/html/wp-content -czf - uploads' \
	> "$BACKUP_DIR/uploads.tar.gz"

{
	echo "timestamp=$TIMESTAMP"
	echo "git_commit=$(git rev-parse HEAD)"
	echo "git_commit_short=$(git rev-parse --short HEAD)"
	echo "public_url=${WORDPRESS_PUBLIC_URL:-}"
	echo "backup_dir=$BACKUP_DIR"
	echo "files=db.sql.gz,uploads.tar.gz"
} > "$BACKUP_DIR/manifest.txt"

ln -sfn "$BACKUP_DIR" "$LATEST_LINK"

case "$RETENTION_DAYS" in
	''|*[!0-9]*)
		echo "HOPP_BACKUP_RETENTION_DAYS must be a non-negative integer." >&2
		exit 1
		;;
esac

if [ "$RETENTION_DAYS" -gt 0 ]; then
	find "$BACKUP_ROOT" -mindepth 1 -maxdepth 1 -type d -name '20*' -mtime +"$RETENTION_DAYS" -exec rm -rf {} +
fi

printf 'Production backup created at %s\n' "$BACKUP_DIR"
