## 2026-04-30

- Session 1: project kickoff — read context.md, understood scope (WP UI/UX redesign, no server access, local Docker approach)
- Researched Google Stitch DESIGN.md format — fetched canonical spec from GitHub (format is "alpha", evolves)
- Decisions confirmed: Option B nav (original 7-item nav preserved), inline colors named by role (sand, terracotta, paper)
- Created `DESIGN.md` — 9 color tokens, 8 typography tokens, 13 components, Do's and Don'ts
- Diagnosed context injection issue: `.claude/` files not loading because no root `CLAUDE.md` and files not in `.claude/rules/`. User resolved by symlinking to `.claude/rules/`
- Created `/design-md` agent skill at `agent_core/skills/design-md/SKILL.md` — 6-step workflow, always fetches live spec
- Created `current_state/project_status.md` and `current_state/milestone.md` — V1 plan documented, Design System milestone logged
- Next: local WP Docker environment (can start without admin credentials; content import blocked on creds)

- Session 2: initialized standard project docs from `~/projects/smart_chatbot/` reference — created `CLAUDE.md`, `PROJECT.md`, `README.md`, `DOCKER_SETUP.md`, `.env.example`, `docs/error_log.md`
- Scaffolded local WordPress Docker setup — `docker-compose.yml`, `.env.local`, external `proxy-network`, private `hopp-network`, WordPress/MySQL services, theme bind mount
- Verified local WordPress pipeline manually with user: installed WP locally, activated HOPP theme, confirmed frontend render
- Built minimal WordPress theme foundation under `wp-content/themes/hopp/`: `style.css`, `functions.php`, `index.php`, `header.php`, `footer.php`, `front-page.php`, `page.php`, `single.php`, `archive.php`, `404.php`, `search.php`, `home.php`
- Added initial base CSS from `DESIGN.md`: tokens, typography, header/footer, hero, page/post/archive/card/404 styles
- Clarified strategy: V1 is a local UI/UX demo for team review; V2 production integration waits for WP admin access, live export/import, plugin inspection, and e-commerce/form validation
- Moved live WP admin deployment procedure into `docs/live_wordpress_deployment.md` instead of `DOCKER_SETUP.md`
- User obtained public crawl from `markdown.new/crawl`; created `docs/current_site_audit.md` and `docs/demo_design_plan.md`
- Current resume point: implement V1 demo from `docs/demo_design_plan.md`, starting with global header/footer/navigation and reusable components
- Session 3: invoked vibe-code but user clarified frontend work does not need development notes, knowledge assessments, or dataflow diagrams; removed the started dev note and continued with practical frontend implementation
- Initialized local Git repository on `development` because the project had no `.git`; committed the baseline before feature work
- Built V1 local WordPress UI/UX demo theme: responsive editorial homepage, page-specific demo layouts, product cards, story cards, demo forms, empty cart state, sticky header, mobile navigation, and footer
- Added local-only demo seed behavior through Docker `WORDPRESS_CONFIG_EXTRA` and theme guard `HOPP_ENABLE_DEMO_SEED`, creating placeholder pages, stories, and the original nav plus cart only in local WordPress
- Verified `docker compose --env-file .env.local config`, PHP syntax, local HTTP 200 routes, and WordPress logs; Playwright not available for browser screenshots

## 2026-05-04

- Diagnosed local 503 on `humansofphnompenh.local` as nginx-proxy receiving an empty `VIRTUAL_HOST` because `docker compose up -d` was run without `--env-file .env.local`
- Added a repo Makefile to make `.env.local` the default startup path and reduce repeat setup mistakes: `make init`, `make up`, `make down`, `make restart`, `make rebuild`, `make ps`, `make logs`, `make logs-db`
- Updated `README.md` and `DOCKER_SETUP.md` to steer local setup through the new Makefile and call out the explicit env file requirement
- Switched the next hosting step to Oracle Always Free so the team can review the demo without the developer's laptop staying online
- Documented the Oracle-hosted preview workflow in `docs/oracle_preview_deployment.md` so the deployment plan is easy to follow later
- Moved the Oracle preview runbook into `deliverables/software_ai_engineering/infrastructure/oracle_preview_deployment.md` and left a symlink at `archive/oracle_preview_deployment.md`
- Split the Docker setup into shared, local, and Oracle override compose files so local and cloud preview settings can differ cleanly
- Confirmed LAN sharing for the local WordPress demo by setting `WORDPRESS_LOCAL_URL` to the laptop's Wi-Fi URL and recreating the stack
- Prepared `archive/20260504_team_confirmation.md` as a concise team-facing review note for comparing the local demo against three reference sites
- Updated `current_state/project_status.md` so the immediate next task is team feedback collection, with Oracle preview kept as the longer-lived hosting option
