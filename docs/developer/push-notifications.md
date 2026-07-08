# Push notifications (developer)

## Classes

| Class | Role |
|-------|------|
| `EPC_Pass_Notifications` | Send helpers, cron runner, dedupe meta |
| `EPC_Api_Client::send_pass_push()` | HTTP call to EpassCard API |
| `EPC_Module` | Admin UI, rules storage, test AJAX |

## API request

```http
POST {api_base}/send-pass-notification/{passUuid}
Content-Type: application/json
X-Api-Key: {key}
X-Request-Origin: {site origin}
Origin: {site origin}
```

Body after `EPC_Api_Client::build_push_notification_message()`:

```php
array( 'message' => "Title\n\nBody" );
```

### Client methods

```php
EPC_Api_Client::send_pass_push( $pass_uid, $title, $message );
EPC_Api_Client::build_push_notification_message( $title, $message );
EPC_Api_Client::send_push_url( $pass_uid );
```

### Send helpers

```php
EPC_Pass_Notifications::send_for_pass( $pass_row, $title, $message );
EPC_Pass_Notifications::send_for_module_source( $slug, $source_id, $title, $message );
EPC_Pass_Notifications::maybe_send_for_event( $pass_row, $type, $rule, $event_ts, $replacements );
```

## Module integration patterns

### Native reminder hooks (MemberPress, WCS)

Modules override `get_notification_types()`, `get_default_notification_rules()`, and hook the parent plugin’s reminder/notification events. On fire:

1. Map event → notification type slug
2. `build_push_notification_copy( $type, $replacements )`
3. Find pass for member/subscription
4. `EPC_Pass_Notifications::send_for_pass()`

MemberPress: `mepr-reminder-*` via `maybe_send_reminder_push()`.

WCS: `woocommerce_scheduled_subscription_customer_notification_*` via `on_wcs_customer_notification()`.

### Scheduled cron (UMP)

Implement `process_scheduled_notifications()` on the module. Registered via `EPC_Pass_Notifications::run()` daily cron.

Uses `maybe_send_for_event()` with a lead-days window and `notifications_sent` meta dedupe.

## Stored options

Per module: `epc_{slug_with_underscores}_notification_rules`

```php
array(
    'sub_expires' => array(
        'enabled' => true,
        'title'   => '...',
        'message' => '...',
        // 'days' => 7  (UMP / cron modules only)
    ),
)
```

Pass meta (`epc_passes.meta` JSON):

```php
array(
    'notifications_sent' => array(
        'before_level_expire_7' => '2026-07-07 12:00:00',
    ),
)
```

## Hooks

| Hook | Type | Description |
|------|------|-------------|
| `epc_pass_push_title` | filter | `( $title, $pass_row )` before API call |
| `epc_pass_push_message` | filter | `( $message, $pass_row )` before API call |
| `epc_send_push_notification_url` | filter | `( $url, $pass_uid )` |
| `epc_send_push_notification_body` | filter | `( $body, $pass_uid, $title, $message )` |

## Extending a custom module

```php
public function get_notification_types() {
    return array( 'renewal' => __( 'Renewal', 'my-plugin' ) );
}

public function get_default_notification_rules() {
    return array(
        'renewal' => array(
            'enabled' => false,
            'title'   => 'Renewal soon',
            'message' => 'Hi {user_first_name}, renew {membership_title}.',
        ),
    );
}

protected function register_event_hooks() {
    add_action( 'my_plugin_renewal_reminder', array( $this, 'on_renewal' ), 10, 1 );
}

public function on_renewal( $source_id ) {
    $copy = $this->build_push_notification_copy(
        'renewal',
        $this->build_push_replacements_for_pass( /* pass row */ )
    );
    if ( $copy ) {
        EPC_Pass_Notifications::send_for_module_source(
            $this->get_slug(),
            $source_id,
            $copy['title'],
            $copy['message']
        );
    }
}
```

Call `$this->render_push_notification_settings()` from `render_module_settings()` to show the admin UI.

## AJAX

`epc_send_test_push_{module}` — POST `pass_id`, `notification_type`, optional `title`, `message`. Nonce: `epc_admin`.
