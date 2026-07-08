# Integrations

EpassCard ships with three integration modules. Each extends the same workflow: **map template → issue pass → sync on changes**.

## MemberPress

**Menu:** EpassCard → MemberPress

- Maps **membership products** to pass templates.
- Issues passes for active memberships/subscriptions.
- **Members list:** Create/Update pass column actions.
- **MemberPress account:** **Wallet Passes** tab for members.
- **Push reminders:** Subscription expire, renewal, trial, card expire (uses MemberPress reminder timing).

## WooCommerce Subscriptions

**Menu:** EpassCard → WooCommerce Subscriptions

- Maps **subscription products** to pass templates.
- Issues passes per subscription record.
- **Subscriptions list:** Row actions and pass column.
- **WooCommerce My Account:** **Wallet passes** endpoint.
- **Order emails:** Optional pass link block (Connection settings).
- **Push reminders:** Renewal, trial end, subscription end (uses WCS reminder timing).

## Ultimate Membership Pro

**Menu:** EpassCard → Ultimate Membership Pro

- Maps **membership levels** to pass templates.
- Issues passes per user-level assignment (`ihc_user_levels` record).
- **Users list** (`ihc_manage` → Users): Pass actions column.
- **Push reminders:** Before level expires (EpassCard daily cron + **Days before** setting).

## Enabling / disabling

Integrations are toggled on the **Connection** page. Disabled modules do not load hooks or appear in the admin menu.

## Per-module settings

Each integration page may include:

- **Template mapping** — Required before passes can be issued.
- **Issued passes** — Searchable list with manual actions.
- **Push notification rules** — Custom title/message per reminder type.
- **Test notification** — Send a test push to a specific pass.

See [mapping.md](mapping.md) and [notifications.md](notifications.md) for details.
