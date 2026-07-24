# Push notifications

EpassCard sends **wallet push notifications** to members who have an active pass. You configure **what** the push says; **when** it fires depends on the integration (see below).

## Where to configure

On each integration page (**EpassCard → [module]**), scroll to **Push notification copy**.

| Column | Purpose |
|--------|---------|
| **Push enabled** | Turn a reminder type on or off |
| **Days before** *(cron modules)* | How many days before the event/expiry to send (1–90) |
| **Push title** | Short title (plain text) |
| **Push message** | Body text (plain text) |

Click **Save push notification settings**, then use **Send test notification** to verify.

Placeholders like `{membership_title}` or `{product_name}` are replaced with live data. HTML from email templates is **not** used — wallet pushes are plain text only.

## How timing works (by integration)

### MemberPress — native reminders

**When** pushes fire follows **MemberPress → Reminders** (same schedule as reminder emails).

EpassCard hooks `mepr-reminder-{event}` and sends a wallet push when MemberPress would send the matching reminder email, using your **Push title** and **Push message** for that type.

| Reminder type | MemberPress event |
|---------------|-------------------|
| Membership / subscription expiring | `sub-expires` |
| Subscription renewing | `sub-renews` |
| Trial ending | `sub-trial-ends` |
| Saved card expiring | `cc-expires` |

Use the **Reminder timing** link on the MemberPress integration page to open MemberPress reminder settings.

### WooCommerce Subscriptions — native customer notifications

**When** pushes fire follows **WooCommerce → Settings → Subscriptions → Customer notifications** (same cron hooks as customer notification emails).

| Reminder type | WCS scheduled action |
|---------------|----------------------|
| Next payment / renewal | `woocommerce_scheduled_subscription_customer_notification_renewal` |
| Trial ending | `woocommerce_scheduled_subscription_customer_notification_trial_expiration` |
| Subscription ending | `woocommerce_scheduled_subscription_customer_notification_expiration` |

A push is only sent if WCS would also send the customer email for that notification.

### Cron-based modules — EpassCard daily cron

These integrations have no native wallet reminder schedule. EpassCard runs a **daily cron** (`epc_pass_notifications_event`) that checks active passes and sends within the configured **Days before** window.

| Integration | Reminder type | Trigger date |
|-------------|---------------|--------------|
| Ultimate Membership Pro | Before membership level expires | Level expiry |
| Paid Memberships Pro | Before membership level expires | Level end date |
| Simple Membership | Before membership expires | Member expiry |
| Event Tickets | Before event starts | Event start |
| Events Manager | Before event starts | Event start |
| PW Gift Cards | Before gift card expires | Card expiration |
| YITH Gift Cards | Before gift card expires | Card expiration |

Each pass receives at most **one** push per reminder type and lead window (tracked in pass meta).

## What gets sent to the API

When a push is triggered, EpassCard calls:

```
POST /api/public/v1/send-pass-notification/{passUuid}
Content-Type: application/json
X-Api-Key: your key
X-Request-Origin: https://yoursite.com
```

Body:

```json
{
  "message": "Push title\n\nPush message body"
}
```

Title and message from your settings are combined into a single `message` field (blank line between them).

## Template placeholders

### MemberPress

`{membership_title}`, `{membership_expires}`, `{next_payment_date}`, `{trial_end_date}`, `{card_expiry_date}`, `{user_display_name}`, `{user_first_name}`, `{user_last_name}`, `{user_email}`

### WooCommerce Subscriptions

`{product_name}`, `{subscription_id}`, `{next_payment_date}`, `{trial_end_date}`, `{end_date}`, `{user_full_name}`, `{user_first_name}`, `{user_last_name}`, `{user_email}`

### Ultimate Membership Pro / Paid Memberships Pro

`{level_name}`, `{expire_date}`, `{user_display_name}`, `{user_email}`

### Simple Membership

`{level_name}`, `{expire_date}`, `{user_display_name}`, `{user_full_name}`, `{user_email}`

### Event Tickets

`{event_title}`, `{event_start}`, `{ticket_name}`, `{user_full_name}`, `{user_email}`

### Events Manager

`{event_name}`, `{event_start}`, `{booking_id}`, `{user_full_name}`, `{user_email}`

### PW Gift Cards / YITH Gift Cards

`{product_name}`, `{card_number}`, `{expire_date}`, `{balance_formatted}`, `{user_full_name}`, `{user_email}`

## Send test notification

1. Connect EpassCard on the **Connection** page.
2. Open an integration that supports pushes.
3. Configure title/message for a reminder type (or rely on saved values).
4. In **Send test notification**, enter a **Pass ID** (pass UUID from **Issued passes**, or source/record ID where supported).
5. Select **Reminder type** and click **Send test notification**.

Check **EpassCard → API Log** if the push fails. Common issues: API key not connected, domain mismatch on `X-Request-Origin`, or missing push title/message.

## Requirements

- An **active** pass with a valid `pass_uid` for the member.
- EpassCard **connected** with an API key whose `allowed_domains` matches your site URL (including `http` vs `https`).
- Reminder type **enabled** in push settings (except manual tests, which can use form values).

## Duplicate prevention

Scheduled cron notifications store a `notifications_sent` record in each pass row’s `meta` JSON so the same reminder is not sent twice.

MemberPress and WCS rely on their own reminder/notification scheduling; EpassCard sends one push per fired reminder event.
