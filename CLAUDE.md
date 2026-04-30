# CLAUDE.md - Humans of Phnom Penh Website

## Project Type
`coding`

---

## Project Goal

Build a pixel-accurate custom WordPress theme for Humans of Phnom Penh, based on `DESIGN.md`, and verify it locally before touching the live site.

The live site is WordPress. The project must preserve the existing content, navigation, products/cart behavior, and editorial storytelling identity.

---

## Session Initialization

At session start (`initialize`), read in order:
1. `PROJECT.md`
2. `current_state/project_status.md`

Also read as work demands:
- `DESIGN.md` - visual system and UI implementation source of truth
- `resources/context.md` - legacy site context and constraints
- `current_state/milestone.md` - full details on completed tasks
- `DOCKER_SETUP.md` - local WordPress Docker setup and env vars
- `docs/live_wordpress_deployment.md` - live WP admin-only deployment runbook

Rule: verify in `project_status.md` before assuming something is built.

---

## Development Rules

- Work locally first. The live WordPress site must not be modified until the custom theme is verified end-to-end in the local Docker environment.
- No server access is currently available. Assume rollback options are limited until the live theme name and admin access are known.
- Preserve the original 7-item navigation: Home, About Us, Products, Stories, Artist, Career, Contact Us, plus cart behavior.
- Do not replace WordPress with another stack. The safe path is a custom WordPress theme.
- Do not assume WooCommerce or any plugin is installed until admin access confirms it.

---

## End Session

Follow `logging-workflow.md §2.5`. Additionally:
- Update `current_state/project_status.md`
- Update `current_state/milestone.md` when a task is completed
- Update `PROJECT.md` only if structure, stack, or architecture changed
- Update `DOCKER_SETUP.md` when Docker services, ports, env vars, or setup steps change

## Doc Sync Rule

| Changed in `project_status.md` | Sync target |
|---|---|
| Task completed | `current_state/milestone.md` |
| Features, theme scope, site structure, roadmap | `README.md` |
| Project structure, stack, architecture | `PROJECT.md` |
| Docker, ports, env vars, local setup | `DOCKER_SETUP.md` |
| Debugging investigation with root cause | `docs/error_log.md` |
