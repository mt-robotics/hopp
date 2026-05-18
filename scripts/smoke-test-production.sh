#!/bin/sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
REPO_ROOT=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

cd "$REPO_ROOT"

BASE_URL=${1:-}

if [ -z "$BASE_URL" ]; then
	if [ ! -f .env.gcp ]; then
		echo "Missing .env.gcp in $REPO_ROOT and no base URL argument supplied." >&2
		exit 1
	fi

	set -a
	. ./.env.gcp
	set +a
	BASE_URL=${WORDPRESS_PUBLIC_URL:-}
fi

if [ -z "$BASE_URL" ]; then
	echo "Set WORDPRESS_PUBLIC_URL in .env.gcp or pass a base URL explicitly." >&2
	exit 1
fi

trimmed_base=${BASE_URL%/}
routes="/ /about-us/ /products/ /stories/ /artist/ /career/ /contact-us/ /cart/ /checkout/ /wp-login.php"

for route in $routes; do
	url="$trimmed_base$route"
	status=$(curl -fsS -o /dev/null -w '%{http_code}' "$url")
	case "$status" in
		200|301|302)
			printf '[OK] %s -> %s\n' "$url" "$status"
			;;
		*)
			printf '[FAIL] %s -> %s\n' "$url" "$status" >&2
			exit 1
			;;
	esac
done

printf 'Production smoke GET checks passed for %s\n' "$trimmed_base"
