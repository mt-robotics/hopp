#!/bin/sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
REPO_ROOT=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

cd "$REPO_ROOT"

if [ ! -f .env.gcp ]; then
  echo "Missing .env.gcp in $REPO_ROOT" >&2
  exit 1
fi

if ! git diff --quiet || ! git diff --cached --quiet; then
  echo "Refusing deploy: server checkout has uncommitted changes." >&2
  exit 1
fi

git fetch origin --prune
git checkout main
git pull --ff-only origin main

make gcp-rebuild
make gcp-ps

printf 'Production deploy complete at commit %s\n' "$(git rev-parse --short HEAD)"
