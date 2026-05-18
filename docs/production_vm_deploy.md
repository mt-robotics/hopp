# Production VM Deploy Runbook

Canonical Git-to-VM deploy path for the sponsor-funded production host.

This is the default production workflow for the current `https://hopp.delvedeepasia.org` stack. It replaces ad hoc server edits and replaces the older WP-admin-only theme-upload path as the normal production deployment model.

---

## Table of Contents

1. [Source of Truth](#source-of-truth)
2. [One-Time Host Expectations](#one-time-host-expectations)
3. [Canonical Deploy Sequence](#canonical-deploy-sequence)
4. [Canonical Rollback Sequence](#canonical-rollback-sequence)
5. [Operational Rules](#operational-rules)
6. [Related Docs](#related-docs)

---

## Source of Truth

- `main` is the only production branch
- The production checkout lives at `/opt/hopp`
- Production deploys happen by SSHing to the VM and updating that checkout from `origin/main`
- Host-only secrets stay in `/opt/hopp/.env.gcp` on the VM and are never overwritten by Git
- Container recreation must always use the repo-owned GCP Compose path so nginx, WordPress bootstrap, and the ABA runtime patch are reapplied consistently

---

## One-Time Host Expectations

Before using the deploy workflow, the host should already meet these conditions:

- the VM exists and is reachable through `ssh hopp-prod`
- the repo is cloned into `/opt/hopp`
- the working tree is owned through the `root:hopp` group-based model
- `.env.gcp` exists on the host with the real production values
- Docker Engine and the Docker Compose plugin are installed

The bootstrap script `scripts/gcp-startup.sh` prepares the VM for this model. It does not populate secrets or perform the first deploy by itself.

Host toolchain expectation:

- `git` must be installed
- Docker Engine and the Docker Compose plugin must be installed
- `make` must be installed because the deploy and rollback helpers call `make gcp-rebuild` and `make gcp-ps`

---

## Canonical Deploy Sequence

Run this from a workstation after the intended revision has already been merged into `main`.

```bash
ssh hopp-prod
cd /opt/hopp
./scripts/deploy-production.sh
```

What `./scripts/deploy-production.sh` does:

1. refuses to run if the server checkout is dirty
2. fetches `origin`
3. checks out `main`
4. fast-forwards `main` to `origin/main`
5. recreates the production stack with `make gcp-rebuild`
6. verifies container status with `make gcp-ps`

This is the canonical production deploy path for the current VM-backed stack.

---

## Canonical Rollback Sequence

Use rollback only for a production incident where the latest `main` commit must be removed from the running stack immediately.

First identify the last known-good `main` commit:

```bash
ssh hopp-prod
cd /opt/hopp
git log --oneline origin/main -n 10
```

Then deploy that known-good commit:

```bash
./scripts/rollback-production.sh <known-good-main-sha>
```

What `./scripts/rollback-production.sh` does:

1. refuses to run if the server checkout is dirty
2. fetches `origin`
3. verifies the target commit is reachable from `origin/main`
4. checks out the target commit in detached-HEAD mode
5. recreates the stack with `make gcp-rebuild`
6. verifies container status with `make gcp-ps`

Important follow-up:

- detached-HEAD rollback is an emergency runtime state, not the long-term Git state
- after service is stabilized, fix the bad change in Git properly on a feature branch, merge that fix through `development` to `main`, then return the server to tracked `main` with:

```bash
ssh hopp-prod
cd /opt/hopp
./scripts/deploy-production.sh
```

---

## Operational Rules

- Never deploy from `development`, a personal branch, or an untracked server edit
- Never edit repo-owned code directly on the VM and leave it there; if an emergency server-side change happens, write it back into Git immediately
- Never commit `.env.gcp` or other real production secrets
- Use the same deploy path for normal releases and for post-fix redeploys so the runtime stays reproducible
- Use `docs/live_wordpress_deployment.md` only for the rare case where a theme-only WP Admin upload is explicitly required

## Related Docs

- open `docs/production_operations_index.md` first if you just need the operator commands and boundaries
- use `docs/production_mail_and_form_verification.md` for the remaining mail and WooCommerce verification gate
