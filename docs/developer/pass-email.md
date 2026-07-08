# Pass email API

Programmatically send wallet pass links using `EPC_Pass_Email`.

## Settings

Option: `epc_pass_email_settings`

```php
$settings = EPC_Pass_Email::get_settings();
// auto_on_create, include_on_wc_order, subject, body
```

## Send for a pass row

```php
$pass = EPC_DB::get_pass( 'memberpress', $subscription_id );
$result = EPC_Pass_Email::send_for_pass_row( $pass, array(
    'to'           => 'member@example.com', // optional
    'subject'      => 'Your pass',          // optional override
    'body'         => 'Link: {pass_link}',  // optional override
    'module_label' => 'MemberPress',
) );

if ( is_wp_error( $result ) ) {
    // epc_no_pass_link, epc_no_recipient, epc_mail_failed
}
```

## Send by module + source id

```php
EPC_Pass_Email::send_for_source( 'woocommerce-subscriptions', $subscription_id );
```

## Auto send on create

Handled by `EPC_Pass_Email::maybe_send_after_sync()` from `EPC_Pass_Service` after successful create.

Disable globally via Connection settings or:

```php
add_filter( 'epc_pass_email_auto_send', '__return_false' );
```

Conditionally:

```php
add_filter( 'epc_pass_email_auto_send', function ( $send, $mode, $pass_row ) {
    if ( 'memberpress' === $pass_row->module ) {
        return false;
    }
    return $send;
}, 10, 3 );
```

## Placeholders

Built in `EPC_Pass_Email::build_replacements()`:

`user_first_name`, `user_last_name`, `user_display_name`, `user_email`, `pass_link`, `pass_uid`, `site_name`, `membership_title`, `module_label`

Use `EPC_Pass_Email::replace_tags( $text, $replacements )` for custom templates.

## HTML email

Default headers are plain text. Example HTML body:

```php
add_filter( 'epc_pass_email_headers', function ( $headers ) {
    return array( 'Content-Type: text/html; charset=UTF-8' );
} );

add_filter( 'epc_pass_email_body', function ( $body, $pass_row, $replacements ) {
    $link = esc_url( $replacements['pass_link'] );
    return '<p>Hi ' . esc_html( $replacements['user_first_name'] ) . ',</p>'
        . '<p><a href="' . $link . '">Add to wallet</a></p>';
}, 10, 3 );
```

## WooCommerce order emails

Hook: `woocommerce_email_after_order_table` (priority 15).

Passes resolved via `EPC_Pass_Email::get_passes_for_order()` — subscriptions on the order plus active passes for the customer.

## Admin AJAX

`epc_send_pass_email_{module}` — POST `source_id`, `email_nonce`, nonce `epc_admin`.

## Actions for integrations

```php
add_action( 'epc_pass_created', function ( $slug, $source_id, $pass_row ) {
    // Custom delivery: SMS, CRM, etc.
}, 10, 3 );
```
