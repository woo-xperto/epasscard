# Passes & email

## Issued passes list

Each integration page includes an **Issued passes** table showing:

- Record ID, member, plan/product, pass ID, link, status, updated date
- **Actions:** Create pass, Update pass, **Email pass link**

Use filters for status and plan/product.

## Automatic pass creation

When mapping exists and the integration event fires (e.g. new active subscription), EpassCard:

1. Collects source field values
2. Calls the EpassCard API to create or update the pass
3. Stores `pass_uid` and `pass_link` in the local database

## Email pass links

### Automatic email

Enable **Automatically email pass link when a new pass is created** on **EpassCard → Connection**.

Sent when a pass is **newly created** (including first-time sync that creates a pass).

### Manual email (admin)

On **Issued passes** or member/subscription list actions, click **Email pass link**. Uses the member’s WordPress email unless overridden in code.

### WooCommerce order emails

Enable **Include pass links in WooCommerce order emails** on the Connection page. Related subscription passes are appended to customer order emails (not admin copies).

### Member self-service

Members can open passes without email:

- WooCommerce **My Account → Wallet passes**
- MemberPress account **Wallet Passes** tab
- Shortcode `[epc_my_passes]` on any page (logged-in users only)

## Email template

Customize subject and body on the Connection page. Plain text only by default; use the `epc_pass_email_body` filter for HTML (developers).

## Troubleshooting email

- Confirm WordPress can send mail (`wp_mail`).
- Check the member has a valid email on their user account.
- Ensure the pass has a `pass_link` (create the pass first).
- Review server mail logs or use an SMTP plugin.

## Revoked passes

When a membership ends, integrations may mark passes **revoked** locally. Revoked passes are hidden from the frontend pass list.
