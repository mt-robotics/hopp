# Production Mail And Form Verification

Canonical production mail path and verification checklist for the live sponsor-funded VM.

---

## Table of Contents

1. [Current Verified State](#current-verified-state)
2. [Canonical Mail Path](#canonical-mail-path)
3. [Required Host Env Vars](#required-host-env-vars)
4. [Verified Public Forms](#verified-public-forms)
5. [WooCommerce Email Behavior To Verify](#woocommerce-email-behavior-to-verify)
6. [Verification Checklist](#verification-checklist)

---

## Current Verified State

Verified on `hopp-prod` on 2026-05-18:

- the live WordPress container has `contact-form-7`, `woocommerce`, `forminator`, and the ABA PayWay gateway plugin installed
- the active plugin list includes Contact Form 7 and WooCommerce, so the public form and store flows are live code paths
- the four expected CF7 forms exist in the database:
  - `HOPP Artist Submission`
  - `HOPP Career Application`
  - `HOPP Contact Message`
  - `HOPP Pitch Your Pal Nomination`
- the HOPP theme renders those forms by exact title through `hopp_render_contact_form()` in `wp-content/themes/hopp/functions.php`
- PHP inside the live container still reports core mail defaults (`SMTP => localhost`, `smtp_port => 25`, `sendmail_path => /usr/sbin/sendmail -t -i`)
- `/usr/sbin/sendmail` does not exist in the live container, so the current generic PHP mail path is not a valid production mail transport
- the live database still contains placeholder mail recipients and sender values:
  - `admin_email=admin@example.com`
  - `woocommerce_email_from_address=admin@example.com`
  - each CF7 `_mail` record points its recipient to `admin@example.com`

Conclusion:

- production mail was not actually configured on the VM before this task
- public form and order-notification mail cannot be treated as production-ready until a real SMTP mailbox is configured and verified end to end

---

## Canonical Mail Path

The canonical production mail path is now:

```text
/opt/hopp/.env.gcp -> repo-owned MU plugin -> PHPMailer SMTP -> provider mailbox
```

Repo-owned policy:

- do not depend on a WP-admin SMTP plugin as the production source of truth
- keep mail transport and critical recipient routing in Git plus host-managed `.env.gcp`
- use the repo-owned MU plugin `docker/wordpress/mu-plugins/hopp-production-mail.php` to:
  - configure PHPMailer for SMTP
  - set the shared sender identity
  - override the admin notification recipient used by CF7 and WooCommerce admin mail

Provider choice:

- `humansofphnompenh.com` currently publishes MX records for Hostinger (`mx1.hostinger.com`, `mx2.hostinger.com`)
- `humansofphnompenh.com` currently publishes SPF through `include:_spf.mail.hostinger.com`
- based on those live DNS facts, the canonical provider path is Hostinger SMTP unless the business deliberately migrates mail elsewhere

Recommended Hostinger SMTP settings, per Hostinger's current support docs:

- host: `smtp.hostinger.com`
- port: `465` with `ssl`
- fallback: `587` with `tls` or `starttls` if `465` is blocked

---

## Required Host Env Vars

Populate these on the host copy of `/opt/hopp/.env.gcp`:

```bash
HOPP_ADMIN_NOTIFICATION_EMAIL=info@humansofphnompenh.com
HOPP_MAIL_FROM_ADDRESS=info@humansofphnompenh.com
HOPP_MAIL_FROM_NAME="Humans of Phnom Penh"
HOPP_MAIL_TRANSPORT=smtp
HOPP_SMTP_HOST=smtp.hostinger.com
HOPP_SMTP_PORT=465
HOPP_SMTP_SECURE=ssl
HOPP_SMTP_USER=info@humansofphnompenh.com
HOPP_SMTP_PASSWORD=fill_real_mailbox_password
HOPP_SMTP_AUTO_TLS=false
```

Operational rules:

- keep the real mailbox password only in the host copy of `.env.gcp`
- after changing these values, redeploy with `./scripts/deploy-production.sh` so the WordPress container resyncs the MU plugin path and picks up the new env vars
- if the final business inbox is not `info@humansofphnompenh.com`, replace both `HOPP_ADMIN_NOTIFICATION_EMAIL` and `HOPP_MAIL_FROM_ADDRESS` with the real mailbox before verification

---

## Verified Public Forms

These are the public CF7 flows that must be verified on production:

| Page | Theme form title | Expected recipient |
|---|---|---|
| `/artist/` | `HOPP Artist Submission` | `HOPP_ADMIN_NOTIFICATION_EMAIL` |
| `/career/` | `HOPP Career Application` | `HOPP_ADMIN_NOTIFICATION_EMAIL` |
| `/contact-us/` | `HOPP Contact Message` | `HOPP_ADMIN_NOTIFICATION_EMAIL` |
| `/pitch-your-pal-phnom-penh/` | `HOPP Pitch Your Pal Nomination` | `HOPP_ADMIN_NOTIFICATION_EMAIL` |

Current verified runtime fact:

- the forms exist in the live DB already, so no new form creation is required before verification

---

## WooCommerce Email Behavior To Verify

Verified from the live plugin code on 2026-05-18:

- the ABA gateway sets the order to pending while payment is in flight
- on a successful PayWay pushback, the ABA gateway updates the order status to `completed`

That means the expected default WooCommerce mail behavior for the current production stack is:

- admin `New order` email:
  - triggered on `pending -> completed`
- customer `Completed order` email:
  - triggered when the order reaches `completed`

Important operational implication:

- because ABA marks successful payment directly as `completed`, the default customer "completed order" email may read like a shipment/fulfillment message rather than a pure payment confirmation
- this behavior must be reviewed with the business after the first verified test order

Failure-path mail to keep in mind:

- WooCommerce admin `Failed order` only triggers on `pending -> failed` or `on-hold -> failed`
- the current ABA code path shown on the live VM does not mark failed payments as `failed`; it marks cancellations as `cancelled`
- so failed-payment alerting may need a later business-specific enhancement if cancelled orders are not enough

---

## Verification Checklist

1. Confirm the real mailbox exists in Hostinger and can sign in outside WordPress.
2. Populate the mail env vars in `/opt/hopp/.env.gcp`.
3. Redeploy the VM with:

```bash
ssh hopp-prod
cd /opt/hopp
./scripts/deploy-production.sh
```

4. Submit each public form once with a unique subject/body marker and verify the message arrives in `HOPP_ADMIN_NOTIFICATION_EMAIL`.
5. Confirm the message headers show the expected sender identity and do not fail SPF.
6. Place one real or tightly controlled test order through ABA PayWay and verify:
   - the order reaches `completed`
   - admin receives the WooCommerce `New order` email
   - the customer billing email receives the WooCommerce `Completed order` email
7. Decide whether the default `completed` customer email copy is acceptable for this business flow.
8. Record the final operational inboxes and alert owners in `current_state/project_status.md` once verified.

Do not mark the production-mail subtask complete until steps 4 through 7 are finished on the live VM.
