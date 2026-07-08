# My Account & shortcode

Members can access wallet passes without opening email links.

## WooCommerce My Account

When WooCommerce is active, logged-in customers see **Wallet passes** in the My Account menu (after **Orders** when possible).

URL pattern: `/my-account/wallet-passes/`

Requires at least one **active** pass with a link for the current user.

**Note:** After installing or updating EpassCard, visit **Settings → Permalinks** and save once if the endpoint 404s (activation also flushes rewrite rules).

## MemberPress account

On the MemberPress account page, members see a **Wallet Passes** nav item linking to `?action=wallet_passes`.

## Shortcode

Add to any page or post:

```
[epc_my_passes]
```

- Logged-out visitors see a “Please log in” message.
- Logged-in users see a list of buttons linking to their active passes.

## Pass list behavior

- Only **active** passes with a non-empty `pass_link` are shown.
- Labels use the membership/product name when available (“View pass: Gold Membership”).
- Links open in a new tab.

## Customization (developers)

- `epc_frontend_user_passes` — Filter which passes appear
- `epc_frontend_pass_label` — Change button text
- `epc_enqueue_frontend_assets` — Force-load pass list styles

See [developer/hooks.md](../developer/hooks.md).
