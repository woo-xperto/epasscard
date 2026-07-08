# Connection

The Connection page (**EpassCard → Connection**) is the central place to link your WordPress site to EpassCard.

## Connect with an API key

1. Log in to [app.epasscard.com](https://app.epasscard.com).
2. Create or copy an API key.
3. Paste it into **API key** on the Connection page.
4. Click **Connect**.

When connected, you will see the account email and key expiry (if provided by the API).

## Connect with email & password

Alternatively, enter your EpassCard email and password and click **Generate & connect**. The plugin will generate an API key and store it encrypted in the database.

## Disconnect

Click **Disconnect** to remove stored credentials. Issued pass records remain in the database but no new API calls will succeed until you reconnect.

## Enable integrations

The **Integrations** table lists available modules. Only **installed** plugins can be enabled.

1. Check the integrations you need.
2. Click **Save integrations**.

Enabled integrations appear as submenu items under **EpassCard**.

## Pass link email settings

When connected, the **Pass link email** section lets you configure:

| Setting | Description |
|---------|-------------|
| Auto email on create | Sends the pass link when a **new** pass is created |
| WooCommerce order emails | Appends pass links to customer order emails |
| Subject / body | Plain-text templates with placeholders |

### Email placeholders

- `{pass_link}` — Wallet add/view URL
- `{user_first_name}`, `{user_last_name}`, `{user_display_name}`, `{user_email}`
- `{membership_title}` — Product or level name
- `{module_label}` — Integration name (e.g. MemberPress)
- `{site_name}` — WordPress site title

## Developer API key access

Expand **Developer: X-Api-Key for custom endpoints** to see the WordPress option name and a PHP snippet using `epc_get_api_key()`.

Keys are stored encrypted; always use the helper function rather than reading the option directly.

## Domain verification (API requests)

When generating an API key, the plugin sends `allowed_domains` as your site origin (e.g. `https://example.com` or `http://giftsrocket.test`).

Every subsequent API request (pass create, push, templates, etc.) includes:

| Header | Value |
|--------|--------|
| `X-Request-Origin` | Site origin from `home_url()` |
| `Origin` | Same site origin |
| `Referer` | Site home URL |

The EpassCard API matches `X-Request-Origin` against the key’s allowed domains. **Protocol must match** — if the key was registered with `http://`, requests must use `http://` in the header (set **WordPress Address** in **Settings → General** accordingly).

Filter overrides:

```php
add_filter( 'epc_api_allowed_domains', fn() => 'https://yoursite.com' );
add_filter( 'epc_api_request_headers', function ( $headers, $origin ) {
    $headers['X-Request-Origin'] = 'https://yoursite.com';
    return $headers;
}, 10, 2 );
```

If pushes or other API calls fail with a domain error, reconnect or regenerate the API key after fixing the site URL.
