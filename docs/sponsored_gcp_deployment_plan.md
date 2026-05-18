# Sponsored GCP Deployment Plan

Deployment-sizing plan for the new sponsor-funded Google Cloud production host.

This plan is based on the imported local WordPress staging stack, the live admin snapshot, and the current Docker deployment wiring in this repo. It is intentionally conservative: keep the architecture simple until measured workload proves it needs to grow.

---

## Table of Contents

1. [Current Workload Signals](#current-workload-signals)
2. [Deployment Constraints](#deployment-constraints)
3. [Recommended Production Shape](#recommended-production-shape)
4. [Estimated Cost](#estimated-cost)
5. [Storage and Backup Plan](#storage-and-backup-plan)
6. [Application and Plugin Requirements](#application-and-plugin-requirements)
7. [Cutover Sequence](#cutover-sequence)
8. [Open Measurements Before Provisioning](#open-measurements-before-provisioning)

---

## Current Workload Signals

- Live site export is small: `archive/humansofphnompenh.WordPress.2026-05-08.xml` is `1.4 MB`
- Imported content volume is modest:
  - 19 pages
  - 5 posts
  - 5 WooCommerce products
  - 107 attachments
  - 8 menu items
  - 4 Forminator forms in the legacy export
  - Divi layout/global-style artifacts from the old theme stack
- Live plugin count is 13, including WooCommerce, ABA PayWay, Checkout Field Editor, WP Menu Cart, Smart Slider 3, and several Divi-related plugins
- Theme code footprint is small; the custom `hopp` theme in repo is about `184 KB`
- Media weight matters more than content count. The project already found at least one oversized original image (`11.5 MB`) during staging review
- Local Docker measurements on `2026-05-15` show the imported workload is still modest:
  - MySQL data size: about `7.95 MB`
  - `wp-content/uploads`: about `83 MB`
  - WordPress named volume: about `260.7 MB`
  - MySQL named volume: about `267 MB`
- Observed container usage on the local imported stack:
  - idle `hopp_wordpress`: about `74 MB`
  - idle `hopp_mysql`: about `505 MB`
  - idle `nginx-proxy`: about `86 MB`
  - light-load `hopp_wordpress` peak during repeated page hits: about `226.5 MB`
  - light-load `hopp_mysql` peak during repeated page hits: about `526.6 MB`

Interpretation:

- This is not a large editorial or commerce workload
- It is still heavier than a brochure site because checkout, payment, forms, uploads, and WordPress admin all run on the same stack
- The right first production shape is one VM, not a split app/database topology

---

## Deployment Constraints

- The live payment gateway is `ABA PayWay Payment Gateway for WooCommerce`
- The local ABA runtime patch is not tracked in repo code because plugin files are outside the repo; the fix must be preserved explicitly during deployment
- PayWay local checkout cannot complete on `localhost`; the deployed host must match whatever public domain is configured in the ABA gateway success/pushback URLs, which is temporarily `hopp.delvedeepasia.org` for this deployment path until the final domain is available
- The theme now depends on Contact Form 7 for four public forms and resolves forms by exact title
- File uploads must keep these PHP limits:
  - `upload_max_filesize=10M`
  - `post_max_size=12M`
- The nginx template should also enforce a compatible upload ceiling; otherwise nginx can reject uploads before PHP sees them
- The GCP override has been hardened to production-safe defaults:
  - `WORDPRESS_ENVIRONMENT_TYPE=production`
  - `HOPP_ENABLE_DEMO_SEED=false`
  - nginx `client_max_body_size 12m`
- The final `.env.gcp` file must still be populated with the active deployment domain, TLS email, and public WordPress URL before cutover
- Domain switching is env-driven: use `hopp.delvedeepasia.org` for the temporary deployment if needed, then later switch back by editing `.env.gcp` values for `WORDPRESS_VIRTUAL_HOST`, `WORDPRESS_LOCAL_URL`, `DOMAIN_NAME`, and `WORDPRESS_PUBLIC_URL`
- The imported stack has now been measured locally, but production email delivery and plugin-patch persistence still need final verification before cutover

---

## Recommended Production Shape

Start with one Compute Engine VM.

Recommended baseline:

- Machine type: `e2-medium`
- vCPU / RAM: 2 vCPU, 4 GB RAM
- Boot/data disk: `pd-balanced`, `50 GB`
- Region: prefer a Southeast Asia region if sponsor policy allows, so latency stays reasonable for Cambodia-based users
- Topology:
  - `nginx`
  - `wordpress` (`wordpress:6.8-php8.3-apache`)
  - `mysql:8.4`
  - `certbot`
  all on the same VM through the existing Docker Compose wiring

Why this shape:

- `e2-micro` already exists in project history as a preview path, but it is too small to call reliable for live WooCommerce, MySQL, and checkout behavior on one box
- The imported workload is small enough that Cloud SQL would add cost and operational complexity without a measured need
- The measured local working set supports a conservative single-VM start. Even after repeated page hits, the active containers stayed well below `1 GB` combined memory use before OS headroom
- `50 GB` leaves room for OS, Docker images, uploads, database growth, logs, snapshots, and rollback safety. `30 GB` is possible but tighter than necessary

Do not split services yet.

Revisit split database hosting only if later measurements show one of these:

- sustained memory pressure
- slow WooCommerce/admin queries
- meaningful traffic spikes
- backup/restore windows becoming operationally painful

---

## Estimated Cost

Baseline monthly estimate for the recommended starting shape:

- `e2-medium`: about `$24.46/month`
  - based on `$0.03350571/hour * 730 hours`
- `50 GB pd-balanced`: about `$5.00/month`
  - based on `$0.000136986/GiB-hour * 50 GiB * 730 hours`

Estimated base infrastructure total:

- about **`$29.46/month`**

This is the steady-state starting point for one VM plus one balanced persistent disk. It does not include traffic, backups, DNS, domain registration, or mail delivery.

### Variable and Excluded Costs

Use these ranges to budget the likely extras around the base VM cost.

| Item | Estimated range | Notes |
|---|---:|---|
| Outbound internet traffic | `$0-$20+/month` for a small site, but can rise with traffic/media downloads | Google bills internet egress by destination and volume. Current Premium Tier rates start around `$0.12/GiB` to North America/Europe/most of Asia for the first `1,024 GiB`, `$0.19/GiB` to some destinations such as Australia/Indonesia/Korea/South America/Saudi Arabia, and `$0.15/GiB` to parts of the Middle East and Africa. A light-content site can stay low; media-heavy traffic can push this up quickly. |
| Persistent disk snapshots / backups | roughly `$1-$6/month` for a small site, higher with longer retention | Regional standard snapshot storage is `$0.000068493/GiB-hour`, about `$0.05/GiB-month`. At current scale, one full `50 GiB` equivalent snapshot is about `$2.50/month`, but snapshots are incremental, so real cost is often lower unless retention grows. |
| Extra static IPv4 charge | `$3.65/month` if attached to the VM; `$7.30/month` if reserved but unused | Google charges `$0.005/hour` for in-use static or ephemeral IPv4 on a standard VM, and `$0.01/hour` when a reserved static IPv4 is not attached to a resource. |
| DNS hosting | about `$0.20-$1/month` for a typical small setup | Cloud DNS charges `$0.20/month` per managed zone for the first 25 zones, plus `$0.40/million` regular queries. One public zone with modest traffic should stay near the low end. |
| Domain registration / renewal | `$0/month` if the domain is already owned and kept elsewhere; about `$12/year` for a `.com` in Cloud Domains | Domain cost is separate from Cloud DNS and separate from the VM budget. `humansofphnompenh.com` may already be paid elsewhere, in which case there is no new incremental domain-registration cost on GCP. |
| SMTP / transactional email | typically `$0-$15/month` at low volume | This is not a fixed Google Cloud VM charge. It depends on whether production mail is sent through an existing relay, workspace, cPanel mail path, or a transactional provider such as Postmark, Mailgun, Resend, or similar. Treat this as an operational estimate, not a Google price quote. |
| Regional price differences | likely small for this chosen shape, but verify before purchase | Google's published list pricing for `e2-medium` and `pd-balanced` appears the same on the pricing pages for both `us-central1` and `asia-southeast1`, but final billing should still be checked in the pricing calculator for the exact sponsor-approved region. |

### Practical Budget View

- Lower-bound realistic monthly operating cost: about **`$30-$40/month`**
- More cautious working budget with backups, DNS, and light traffic: about **`$40-$60/month`**
- Costs rise mainly with:
  - media-heavy outbound traffic
  - longer backup retention
  - adding separate services later such as Cloud SQL, object storage growth, or paid mail tooling

For this project's currently measured size, the sponsor can think of the server as a roughly `$30/month` base host with a modest buffer above that for operational extras.

---

## Storage and Backup Plan

Use layered backups, not only VM snapshots.

Minimum production backup shape:

- Daily persistent disk snapshot, retained for `7-14` days
- Weekly longer-retention snapshot, retained for `4-8` weeks
- Nightly MySQL dump to Cloud Storage
- Nightly `wp-content/uploads` backup to Cloud Storage
- Manual pre-change snapshot before theme/plugin updates, major content imports, or DNS cutover

Operational rule:

- Test one full restore before launch. A backup that has never been restored is not enough.

## Server Ownership Model

Use a shared deploy-group model on the VM instead of personal ownership.

- Application path: `/opt/hopp`
- Owner/group target: `root:hopp`
- Operator access model: one Linux user per real human operator, each added to group `hopp`
- Normal deploy edits should happen through group permissions, not by making the app directory belong to a single person
- System paths such as `/etc`, Docker service configuration, and TLS material under `/etc/letsencrypt` remain root-managed

Why this model:

- avoids coupling production access to one personal account
- allows future staff handoff without changing ownership of the whole app tree
- preserves a clear split between app-level collaboration and root-level system administration

---

## Application and Plugin Requirements

### ABA PayWay

- Install WooCommerce and the ABA PayWay gateway on the new host
- Recreate the live ABA settings and selected payment methods from the captured admin records
- The deployment artifact now carries a repo-owned startup patch path:
  - `docker/wordpress/start-wordpress.sh`
  - `docker/wordpress/apply-aba-payway-patch.php`
- On each WordPress container boot, that startup path patches `wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php` in place if the plugin exists and is still on the known unpatched vendor version
- Preserve the local backup/reference file `PayWayApiCheckout.php.bak.1778238414` until the new host is verified
- The production-safe behavior to preserve is:
  - normalize `payment_options` whether WooCommerce stored it as an array, a comma-delimited string, or a single string value
  - use the normalized value in both `get_icon()` and `getPaymentOption()`
  - keep checkout from assuming fixed array indexes such as `[0]` and `[1]`
- Keep ABA checkout URLs aligned with the currently deployed public host; if the temporary deployment stays on `hopp.delvedeepasia.org`, the gateway settings must use that host until the final domain becomes available
- Verify the four payment options render after deployment:
  - `ABA KHQR`
  - `Credit/Debit Card`
  - `WeChat`
  - `Alipay`

### Contact Form 7

- Install and activate Contact Form 7 on the GCP WordPress host
- Recreate these exact form titles because the theme resolves by title:
  - `HOPP Artist Submission`
  - `HOPP Career Application`
  - `HOPP Contact Message`
  - `HOPP Pitch Your Pal Nomination`
- Preserve PHP upload limits from `docker/php/uploads.ini`
- Add nginx upload allowance, for example `client_max_body_size 12m;`
- Configure a real mail path for production and verify delivery

### Environment and Compose

Before production cutover:

- confirm `WORDPRESS_PUBLIC_URL` is the active HTTPS deployment URL
- confirm `.env.gcp` keeps `WORDPRESS_ENVIRONMENT_TYPE=production`
- confirm `.env.gcp` keeps `HOPP_ENABLE_DEMO_SEED=false`
- confirm the proxy passes `X-Forwarded-Proto https`
- confirm any new operator is added as an individual Linux user in group `hopp`, not by reusing someone else's account

---

## Cutover Sequence

1. Prepare a production-safe GCP override and env file
2. Provision one `e2-medium` VM with a `50 GB pd-balanced` disk
   - Preferred repo path: `make gcp-provision`
   - Required env before running: `GCP_PROJECT_ID`
   - Optional env overrides: `GCP_REGION`, `GCP_ZONE`, `GCP_VM_NAME`, `GCP_ADDRESS_NAME`
3. Attach DNS and TLS
4. Deploy Docker Compose stack
5. Install/activate WooCommerce, ABA PayWay, Contact Form 7, and any still-required supporting plugins
6. Restore the ABA saved payment settings and verify the startup patch logs that the gateway file is already patched or was patched successfully
7. Restore content, uploads, and database
8. Verify:
   - homepage and key pages
   - add to cart
   - cart
   - checkout payment rows
   - Contact Form 7 submissions
   - real email delivery
9. Take a manual snapshot before DNS cutover
10. Cut over traffic

---

## Open Measurements Before Provisioning

These are still unresolved and must be verified before final production cutover:

- whether checkout or admin workflows create materially higher memory spikes than the light route-hit test showed
- whether nginx upload limit is lower than the required CF7 upload size on the final production proxy
- final SMTP provider and sender-domain DNS setup
- whether any Divi-era content skipped locally still matters to production

Based on the current measurements, `e2-medium` remains the smallest reliable starting point.

## Provisioning Command

Use the repo-owned provisioner instead of manually clicking through the console:

```bash
export GCP_PROJECT_ID=your-sponsor-project-id
export GCP_REGION=asia-southeast1
export GCP_ZONE=asia-southeast1-b
make gcp-provision
```

What it does:

- resolves the local `gcloud` binary, including the Homebrew SDK path on macOS
- requires an already-authenticated `gcloud` account
- reserves or reuses a static external IP
- creates HTTP and HTTPS firewall rules for the web host tag
- creates the recommended Compute Engine VM with `scripts/gcp-startup.sh` as the first-boot startup script

What it does not do:

- it does not populate real secrets into `.env.gcp`
- it does not clone the repo onto the VM
- it does not install WordPress plugins or import content
- it does not verify SMTP or Contact Form 7 delivery
