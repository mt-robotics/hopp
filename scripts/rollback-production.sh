#!/bin/sh
set -eu

if [ "$#" -ne 1 ]; then
  echo "Usage: $0 <known-good-main-sha>" >&2
  exit 1
fi

TARGET_COMMIT=$1
SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
REPO_ROOT=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

cd "$REPO_ROOT"

if [ ! -f .env.gcp ]; then
  echo "Missing .env.gcp in $REPO_ROOT" >&2
  exit 1
fi

if ! git diff --quiet || ! git diff --cached --quiet; then
  echo "Refusing rollback: server checkout has uncommitted changes." >&2
  exit 1
fi

git fetch origin --prune

if ! git merge-base --is-ancestor "$TARGET_COMMIT" origin/main; then
  echo "Rollback target must be a commit that exists in origin/main history." >&2
  exit 1
fi

git checkout --detach "$TARGET_COMMIT"

make gcp-rebuild
make gcp-ps

printf 'Production rollback complete at commit %s\n' "$(git rev-parse --short HEAD)"
echo "The server is now detached at a known-good main commit."
echo "After fixing or reverting the issue in Git, run ./scripts/deploy-production.sh to return to tracked main."
