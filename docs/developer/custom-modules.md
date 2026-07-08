# Custom modules

Add integrations for other membership or CRM plugins by extending `EPC_Module`.

## Quick registration

From your plugin (early `plugins_loaded`):

```php
add_action( 'plugins_loaded', function () {
    if ( ! function_exists( 'epc_register_module' ) ) {
        return;
    }

    epc_register_module(
        'my-crm',
        dirname( __FILE__ ) . '/includes/class-epc-module-my-crm.php',
        'EPC_Module_My_CRM'
    );
}, 10 );
```

For files inside `epasscard/modules/`, pass only the filename as the second argument.

## Module class skeleton

```php
class EPC_Module_My_CRM extends EPC_Module {

    public function get_slug() {
        return 'my-crm';
    }

    public function get_label() {
        return __( 'My CRM', 'my-plugin' );
    }

    public function is_available() {
        return class_exists( 'My_CRM_Plugin' );
    }

    public function get_source_fields() {
        return array(
            'email'      => __( 'Email', 'my-plugin' ),
            'full_name'  => __( 'Full name', 'my-plugin' ),
        );
    }

    public function get_mappable_entities() {
        return array(
            array( 'id' => 1, 'label' => __( 'Gold plan', 'my-plugin' ) ),
        );
    }

    public function get_entity_label( $entity_id ) {
        return 'Plan #' . (int) $entity_id;
    }

    public function sync_by_source_id( $source_id, $mode = 'sync' ) {
        // Load record, mapping, source values, then:
        return EPC_Pass_Service::sync_pass(
            $this->get_slug(),
            $source_id,
            $entity_id,
            $user_id,
            $mapping,
            $source_values,
            $mode
        );
    }

    protected function register_event_hooks() {
        add_action( 'my_crm_subscription_active', array( $this, 'on_active' ), 10, 1 );
    }

    public function on_active( $source_id ) {
        $this->sync_by_source_id( (int) $source_id, 'sync' );
    }
}
```

## Required methods

| Method | Purpose |
|--------|---------|
| `get_slug()` | Unique key, used in DB and options |
| `get_label()` | Admin menu title |
| `is_available()` | Dependency check |
| `get_source_fields()` | Mapping dropdown values |
| `get_mappable_entities()` | Rows in mapping table |
| `get_entity_label()` | Display name for entity id |
| `sync_by_source_id()` | Manual/AJAX pass actions |
| `register_event_hooks()` | Subscribe to plugin events |

## Optional overrides

- `get_entity_column_label()`, `get_empty_entities_message()`
- `get_create_entity_url()`, `get_create_entity_label()`
- `render_module_settings()` — Extra settings UI
- `get_pass_action_source_ids_for_user()` — Pass buttons on external user lists
- `should_enqueue_pass_action_assets()` — Where to load admin JS

## Enable in admin

Users must enable your module on **EpassCard → Connection** (slug appears after registration). The slug must exist in `epc_module_files` when the registry is built.

## Testing checklist

- [ ] Module appears in Connection integrations table when dependency active
- [ ] Mapping saves and reloads
- [ ] Create pass / Update pass AJAX works
- [ ] Pass row stored in `epc_passes`
- [ ] API log shows create/update requests

See `modules/module-memberpress.php` for a full reference implementation.
