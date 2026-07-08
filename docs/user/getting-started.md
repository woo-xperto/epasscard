# Getting started

## Requirements

- WordPress 6.5+
- PHP 8.1+
- An [EpassCard](https://app.epasscard.com) account with at least one pass template
- One supported membership/subscription plugin:
  - MemberPress
  - WooCommerce Subscriptions
  - Ultimate Membership Pro (Indeed)

## Installation

1. Upload the `epasscard` folder to `wp-content/plugins/` or install from your distribution package.
2. Activate **EpassCard** in **Plugins**.
3. Go to **EpassCard → Connection**.

## Basic setup (5 steps)

1. **Connect** — Paste your API key or sign in with EpassCard credentials to generate one.
2. **Enable integrations** — On the Connection page, check the plugins you use and click **Save integrations**.
3. **Open an integration** — e.g. **EpassCard → MemberPress**.
4. **Map templates** — For each membership level or subscription product, choose a pass template and map fields.
5. **Issue passes** — Passes are created automatically when members subscribe (per module rules), or manually via **Create pass** on member/subscription lists.

## What happens after setup

- When a member qualifies, EpassCard creates a wallet pass via the API and stores the link locally.
- Profile changes can update existing passes (see integration module settings).
- Optional reminder pushes use your membership plugin’s native reminder timing.
- Pass links can be emailed automatically, included in WooCommerce order emails, or viewed in My Account.

## Next steps

- [Connection & email settings](connection.md)
- [Template mapping](mapping.md)
- [Passes & email delivery](passes-and-email.md)
