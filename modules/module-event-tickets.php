<?php
/**
 * Event Tickets integration module.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Issues EpassCard passes for Event Tickets attendees (RSVP, Tickets Commerce, Woo/PayPal when present).
 */
class EPC_Module_Event_Tickets extends EPC_Module {

	/**
	 * @inheritDoc
	 */
	public function get_slug() {
		return 'event-tickets';
	}

	/**
	 * @inheritDoc
	 */
	public function get_label() {
		return __( 'Event Tickets', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_dependency_label() {
		return __( 'Event Tickets', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function is_available() {
		return epc_is_event_tickets_active();
	}

	/**
	 * @inheritDoc
	 */
	public function get_unavailable_message() {
		return __(
			'Event Tickets is not installed or activated. Install Event Tickets, activate it, then return here to map pass templates.',
			'epasscard'
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_source_fields() {
		return array(
			'user_display_name' => __( 'Display name', 'epasscard' ),
			'user_email'        => __( 'Email', 'epasscard' ),
			'user_first_name'   => __( 'First name', 'epasscard' ),
			'user_last_name'    => __( 'Last name', 'epasscard' ),
			'user_full_name'    => __( 'Full name (attendee)', 'epasscard' ),
			'attendee_id'       => __( 'Attendee ID', 'epasscard' ),
			'ticket_id'         => __( 'Ticket ID', 'epasscard' ),
			'ticket_name'       => __( 'Ticket name', 'epasscard' ),
			'event_id'          => __( 'Event / post ID', 'epasscard' ),
			'event_title'       => __( 'Event title', 'epasscard' ),
			'event_start'       => __( 'Event start', 'epasscard' ),
			'event_end'         => __( 'Event end', 'epasscard' ),
			'expire_date'       => __( 'Pass expiry (event end)', 'epasscard' ),
			'order_id'          => __( 'Order ID', 'epasscard' ),
			'security_code'     => __( 'Security / QR code', 'epasscard' ),
			'attendee_status'   => __( 'Attendee status', 'epasscard' ),
			'check_in'          => __( 'Checked in', 'epasscard' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_status_rules_option_key() {
		return 'epc_event_tickets_status_rules';
	}

	/**
	 * @inheritDoc
	 */
	public function has_pass_behavior_settings() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_pass_behavior_statuses() {
		return $this->get_configurable_attendee_statuses();
	}

	/**
	 * @inheritDoc
	 */
	public function get_status_action_options() {
		return array(
			'none'   => __( 'Do nothing', 'epasscard' ),
			'sync'   => __( 'Create or update pass', 'epasscard' ),
			'update' => __( 'Update pass only', 'epasscard' ),
			'revoke' => __( 'Revoke pass', 'epasscard' ),
		);
	}

	/**
	 * Default pass behavior.
	 *
	 * @return array<string, string>
	 */
	public function get_default_status_rules() {
		return array(
			'yes'     => 'sync',
			'no'      => 'revoke',
			'active'  => 'sync',
			'deleted' => 'revoke',
		);
	}

	/**
	 * Attendee statuses for behavior UI.
	 *
	 * @return array<string, string>
	 */
	public function get_configurable_attendee_statuses() {
		return array(
			'yes'     => __( 'RSVP going / yes', 'epasscard' ),
			'no'      => __( 'RSVP not going / no', 'epasscard' ),
			'active'  => __( 'Paid / active ticket', 'epasscard' ),
			'deleted' => __( 'Deleted / refunded', 'epasscard' ),
		);
	}

	/**
	 * @return array<string, string>
	 */
	public function get_status_rules() {
		$saved    = get_option( $this->get_status_rules_option_key(), array() );
		$defaults = $this->get_default_status_rules();
		$merged   = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
		$allowed  = array_keys( $this->get_status_action_options() );

		foreach ( $merged as $status => $action ) {
			if ( ! in_array( $action, $allowed, true ) ) {
				$merged[ $status ] = $defaults[ $status ] ?? 'none';
			}
		}

		return $merged;
	}

	/**
	 * @param string $status Status.
	 * @return string
	 */
	public function get_status_rule( $status ) {
		$status = sanitize_key( (string) $status );
		$rules  = $this->get_status_rules();

		return $rules[ $status ] ?? 'sync';
	}

	/**
	 * @inheritDoc
	 */
	public function render_module_settings() {
		if ( ! $this->is_available() ) {
			return;
		}

		$statuses = $this->get_configurable_attendee_statuses();
		$actions  = $this->get_status_action_options();
		$rules    = $this->get_status_rules();
		?>
		<div id="epc-section-pass-behavior" class="epc-section epc-section--status-rules">
			<h2><?php esc_html_e( 'Pass behavior by attendee status', 'epasscard' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Choose what happens when an attendee is created or their RSVP/ticket status changes.', 'epasscard' ); ?>
			</p>
			<form class="epc-ajax-form" data-epc-action="<?php echo esc_attr( 'epc_save_status_rules_' . $this->get_slug() ); ?>" method="post" action="">
				<input type="hidden" name="epc_module_slug" value="<?php echo esc_attr( $this->get_slug() ); ?>" />
				<table class="widefat striped epc-status-rules-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Attendee status', 'epasscard' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Pass action', 'epasscard' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $statuses as $status => $label ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $label ); ?></strong></td>
								<td>
									<select name="epc_status_rule_<?php echo esc_attr( $status ); ?>">
										<?php foreach ( $actions as $action_key => $action_label ) : ?>
											<option value="<?php echo esc_attr( $action_key ); ?>" <?php selected( $rules[ $status ] ?? 'none', $action_key ); ?>>
												<?php echo esc_html( $action_label ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p><?php submit_button( __( 'Save pass behavior', 'epasscard' ), 'secondary', 'submit', false ); ?></p>
			</form>
		</div>
		<?php
		$this->render_push_notification_settings();
	}

	/**
	 * @inheritDoc
	 */
	public function get_notification_types() {
		return array(
			'before_event_start' => __( 'Before event starts', 'epasscard' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_default_notification_rules() {
		return array(
			'before_event_start' => array(
				'enabled' => false,
				'days'    => 1,
				'title'   => __( 'Event coming up', 'epasscard' ),
				'message' => __( '{event_title} starts on {event_start}. Your ticket is ready on your phone.', 'epasscard' ),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_notification_template_tags() {
		return array(
			'event_title',
			'event_start',
			'ticket_name',
			'user_full_name',
			'user_email',
		);
	}

	/**
	 * @inheritDoc
	 */
	public function process_scheduled_notifications() {
		$rules  = $this->get_notification_rules();
		$passes = EPC_DB::get_active_passes_for_module( $this->get_slug() );

		foreach ( $passes as $pass_row ) {
			$data = $this->get_attendee_data( (int) $pass_row->source_id );
			if ( ! $data ) {
				continue;
			}

			$event_id  = isset( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;
			$start_ts  = $this->get_event_start_timestamp( $event_id );
			if ( $start_ts <= 0 ) {
				continue;
			}

			EPC_Pass_Notifications::maybe_send_for_event(
				$pass_row,
				'before_event_start',
				$rules['before_event_start'] ?? array(),
				$start_ts,
				$this->build_push_replacements_for_pass( $pass_row )
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function build_push_replacements_for_pass( $pass_row ) {
		if ( ! is_object( $pass_row ) ) {
			return array();
		}

		$data = $this->get_attendee_data( (int) $pass_row->source_id );
		if ( ! $data ) {
			return array();
		}

		$event_id = isset( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;
		$name     = isset( $data['holder_name'] ) ? (string) $data['holder_name'] : '';

		return array(
			'event_title'    => $event_id ? get_the_title( $event_id ) : '',
			'event_start'    => $this->format_event_datetime( $event_id, 'start' ),
			'ticket_name'    => isset( $data['ticket'] ) ? (string) $data['ticket'] : ( isset( $data['ticket_name'] ) ? (string) $data['ticket_name'] : '' ),
			'user_full_name' => $name,
			'user_email'     => isset( $data['holder_email'] ) ? (string) $data['holder_email'] : '',
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_mappable_entities() {
		$ticket_ids = get_posts(
			array(
				'post_type'      => array( 'tribe_rsvp_tickets', 'tribe_tpp_tickets', 'tec_tc_ticket', 'product' ),
				'post_status'    => array( 'publish', 'private' ),
				'posts_per_page' => 200,
				'fields'         => 'ids',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( ! is_array( $ticket_ids ) ) {
			return array();
		}

		$out = array();
		foreach ( $ticket_ids as $ticket_id ) {
			$ticket_id = absint( $ticket_id );
			if ( $ticket_id <= 0 ) {
				continue;
			}

			// Only Woo products that are Event Tickets tickets.
			if ( 'product' === get_post_type( $ticket_id ) ) {
				if ( ! absint( get_post_meta( $ticket_id, '_tribe_wooticket_for_event', true ) ) ) {
					continue;
				}
			}

			$label = get_the_title( $ticket_id );
			$event_id = $this->get_ticket_event_id( $ticket_id );
			if ( $event_id ) {
				$label .= ' — ' . get_the_title( $event_id );
			}

			$out[] = array(
				'id'    => $ticket_id,
				'label' => $label ? $label : ( '#' . $ticket_id ),
			);
		}

		return $out;
	}

	/**
	 * @inheritDoc
	 */
	public function get_entity_column_label() {
		return __( 'Ticket', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_empty_entities_message() {
		return __(
			'Create a ticket or RSVP on an event in Event Tickets, then return here to map a pass template.',
			'epasscard'
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_entity_url() {
		return admin_url( 'edit.php?post_type=tribe_events' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_entity_label() {
		return __( 'Open events', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_entity_label( $entity_id ) {
		$entity_id = absint( $entity_id );
		$title     = $entity_id ? get_the_title( $entity_id ) : '';

		return $title ? $title : ( '#' . $entity_id );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_event_hooks() {
		add_action( 'event_tickets_rsvp_attendee_created', array( $this, 'on_rsvp_attendee_created' ), 20, 4 );
		add_action( 'event_tickets_tpp_attendee_created', array( $this, 'on_tpp_attendee_created' ), 20, 5 );
		add_action( 'tec_tickets_commerce_attendee_after_create', array( $this, 'on_commerce_attendee_created' ), 20, 4 );
		add_action( 'tec_tickets_commerce_flag_action_generated_attendee', array( $this, 'on_commerce_flag_attendee' ), 20, 1 );
		add_action( 'event_ticket_woo_attendee_created', array( $this, 'on_woo_attendee_created' ), 20, 4 );
		add_action( 'event_tickets_attendee_update', array( $this, 'on_attendee_update' ), 20, 3 );
		add_action( 'event_tickets_attendee_ticket_deleted', array( $this, 'on_attendee_deleted' ), 20, 2 );
		add_action( 'tec_tickets_commerce_attendee_after_delete', array( $this, 'on_commerce_attendee_deleted' ), 20, 1 );
	}

	/**
	 * RSVP attendee created.
	 *
	 * @param int    $attendee_id Attendee ID.
	 * @param int    $post_id     Event ID.
	 * @param string $order_id    Order hash.
	 * @param int    $product_id  Ticket ID.
	 * @return void
	 */
	public function on_rsvp_attendee_created( $attendee_id, $post_id, $order_id, $product_id = 0 ) {
		unset( $post_id, $order_id, $product_id );
		$this->handle_attendee( absint( $attendee_id ) );
	}

	/**
	 * Legacy Tribe Commerce / PayPal attendee.
	 *
	 * @param int $attendee_id Attendee ID.
	 * @return void
	 */
	public function on_tpp_attendee_created( $attendee_id ) {
		$this->handle_attendee( absint( $attendee_id ) );
	}

	/**
	 * Tickets Commerce attendee created.
	 *
	 * @param mixed $attendee Attendee post.
	 * @return void
	 */
	public function on_commerce_attendee_created( $attendee ) {
		$attendee_id = is_object( $attendee ) && isset( $attendee->ID ) ? (int) $attendee->ID : absint( $attendee );
		$this->handle_attendee( $attendee_id );
	}

	/**
	 * Tickets Commerce flag-generated attendee.
	 *
	 * @param mixed $attendee Attendee.
	 * @return void
	 */
	public function on_commerce_flag_attendee( $attendee ) {
		$this->on_commerce_attendee_created( $attendee );
	}

	/**
	 * WooCommerce (Event Tickets Plus) attendee.
	 *
	 * @param int $attendee_id Attendee ID.
	 * @return void
	 */
	public function on_woo_attendee_created( $attendee_id ) {
		$this->handle_attendee( absint( $attendee_id ) );
	}

	/**
	 * Attendee profile update.
	 *
	 * @param mixed $attendee_data Data.
	 * @param int   $attendee_id   Attendee ID.
	 * @return void
	 */
	public function on_attendee_update( $attendee_data, $attendee_id ) {
		unset( $attendee_data );
		$this->handle_attendee( absint( $attendee_id ) );
	}

	/**
	 * Attendee ticket deleted.
	 *
	 * @param int $post_id   Event ID.
	 * @param int $ticket_id Attendee/ticket ID (provider-specific).
	 * @return void
	 */
	public function on_attendee_deleted( $post_id, $ticket_id ) {
		unset( $post_id );
		$attendee_id = absint( $ticket_id );
		if ( $attendee_id > 0 ) {
			EPC_Pass_Service::revoke_pass( $this->get_slug(), $attendee_id );
		}
	}

	/**
	 * Commerce attendee deleted.
	 *
	 * @param int $attendee_id Attendee ID.
	 * @return void
	 */
	public function on_commerce_attendee_deleted( $attendee_id ) {
		$attendee_id = absint( $attendee_id );
		if ( $attendee_id > 0 ) {
			EPC_Pass_Service::revoke_pass( $this->get_slug(), $attendee_id );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function sync_by_source_id( $source_id, $mode = 'sync' ) {
		$data = $this->get_attendee_data( absint( $source_id ) );
		if ( ! $data ) {
			return new WP_Error( 'epc_invalid_source', __( 'Attendee not found.', 'epasscard' ) );
		}

		return $this->sync_attendee_data( $data, $mode );
	}

	/**
	 * Apply status rule then sync.
	 *
	 * @param int $attendee_id Attendee ID.
	 * @return void
	 */
	private function handle_attendee( $attendee_id ) {
		if ( ! EPC_Api_Client::is_configured() || $attendee_id <= 0 ) {
			return;
		}

		$data = $this->get_attendee_data( $attendee_id );
		if ( ! $data ) {
			return;
		}

		$status = $this->normalize_attendee_status( $data );
		$action = $this->get_status_rule( $status );

		switch ( $action ) {
			case 'sync':
				$this->sync_attendee_data( $data, 'sync' );
				break;
			case 'update':
				$this->sync_attendee_data( $data, 'update' );
				break;
			case 'revoke':
				EPC_Pass_Service::revoke_pass( $this->get_slug(), $attendee_id );
				break;
			default:
				break;
		}
	}

	/**
	 * Sync from attendee data array.
	 *
	 * @param array<string, mixed> $data Attendee data.
	 * @param string               $mode Mode.
	 * @return true|\WP_Error
	 */
	private function sync_attendee_data( array $data, $mode = 'sync' ) {
		$attendee_id = isset( $data['attendee_id'] ) ? absint( $data['attendee_id'] ) : 0;
		$ticket_id   = isset( $data['product_id'] ) ? absint( $data['product_id'] ) : 0;
		if ( $ticket_id <= 0 && isset( $data['ticket_id'] ) ) {
			$ticket_id = absint( $data['ticket_id'] );
		}

		if ( $attendee_id <= 0 || $ticket_id <= 0 ) {
			return new WP_Error( 'epc_invalid_source', __( 'Attendee not found.', 'epasscard' ) );
		}

		$mapping = $this->get_mapping( $ticket_id );
		if ( empty( $mapping['template_uid'] ) ) {
			return new WP_Error( 'epc_no_mapping', __( 'No pass template is mapped for this ticket.', 'epasscard' ) );
		}

		$event_id = isset( $data['event_id'] ) ? absint( $data['event_id'] ) : 0;
		$user_id  = isset( $data['user_id'] ) ? absint( $data['user_id'] ) : 0;
		$email    = isset( $data['holder_email'] ) ? (string) $data['holder_email'] : '';
		$full     = isset( $data['holder_name'] ) ? (string) $data['holder_name'] : '';

		if ( $user_id <= 0 && $email ) {
			$user = get_user_by( 'email', $email );
			if ( $user ) {
				$user_id = (int) $user->ID;
			}
		}

		$user  = $user_id > 0 ? get_userdata( $user_id ) : false;
		$first = $user ? (string) get_user_meta( $user_id, 'first_name', true ) : '';
		$last  = $user ? (string) get_user_meta( $user_id, 'last_name', true ) : '';
		if ( '' === $full ) {
			$full = epc_format_user_full_name( $first, $last, $user ? $user->display_name : $email );
		}

		$ticket_name = isset( $data['ticket'] ) ? (string) $data['ticket'] : get_the_title( $ticket_id );
		$event_end   = $this->get_event_end_raw( $event_id );

		$values = array(
			'user_display_name' => $user ? $user->display_name : $full,
			'user_email'        => $email ? $email : ( $user ? $user->user_email : '' ),
			'user_first_name'   => $first,
			'user_last_name'    => $last,
			'user_full_name'    => $full,
			'attendee_id'       => (string) $attendee_id,
			'ticket_id'         => (string) $ticket_id,
			'ticket_name'       => $ticket_name,
			'event_id'          => (string) $event_id,
			'event_title'       => $event_id ? get_the_title( $event_id ) : '',
			'event_start'       => $this->format_event_datetime( $event_id, 'start' ),
			'event_end'         => $this->format_event_datetime( $event_id, 'end' ),
			'expire_date'       => epc_format_pass_expiry_datetime( $event_end ),
			'order_id'          => isset( $data['order_id'] ) ? (string) $data['order_id'] : '',
			'security_code'     => isset( $data['security_code'] ) ? (string) $data['security_code'] : ( isset( $data['security'] ) ? (string) $data['security'] : '' ),
			'attendee_status'   => $this->normalize_attendee_status( $data ),
			'check_in'          => ! empty( $data['check_in'] ) ? '1' : '0',
		);

		return EPC_Pass_Service::sync_pass(
			$this->get_slug(),
			$attendee_id,
			$ticket_id,
			$user_id,
			$mapping,
			$values,
			$mode
		);
	}

	/**
	 * Load attendee data via ticket provider.
	 *
	 * @param int $attendee_id Attendee ID.
	 * @return array<string, mixed>|null
	 */
	private function get_attendee_data( $attendee_id ) {
		$attendee_id = absint( $attendee_id );
		if ( $attendee_id <= 0 ) {
			return null;
		}

		if ( function_exists( 'tribe_tickets_get_ticket_provider' ) ) {
			$provider = tribe_tickets_get_ticket_provider( $attendee_id );
			if ( $provider && method_exists( $provider, 'get_attendee' ) ) {
				$data = $provider->get_attendee( $attendee_id );
				if ( is_array( $data ) && ! empty( $data ) ) {
					if ( empty( $data['attendee_id'] ) ) {
						$data['attendee_id'] = $attendee_id;
					}
					return $data;
				}
			}
		}

		return null;
	}

	/**
	 * Normalize status for rules.
	 *
	 * @param array<string, mixed> $data Attendee data.
	 * @return string
	 */
	private function normalize_attendee_status( array $data ) {
		$raw = '';
		if ( isset( $data['order_status'] ) ) {
			$raw = (string) $data['order_status'];
		} elseif ( isset( $data['optout'] ) ) {
			$raw = '';
		}

		$raw = strtolower( trim( $raw ) );
		if ( in_array( $raw, array( 'yes', 'going' ), true ) ) {
			return 'yes';
		}
		if ( in_array( $raw, array( 'no', 'not-going', 'not_going' ), true ) ) {
			return 'no';
		}

		return 'active';
	}

	/**
	 * Event ID for a ticket product.
	 *
	 * @param int $ticket_id Ticket ID.
	 * @return int
	 */
	private function get_ticket_event_id( $ticket_id ) {
		$ticket_id = absint( $ticket_id );
		$keys      = array(
			'_tribe_rsvp_for_event',
			'_tec_tickets_commerce_event',
			'_tribe_wooticket_for_event',
			'_tribe_tpp_for_event',
		);

		foreach ( $keys as $key ) {
			$event_id = absint( get_post_meta( $ticket_id, $key, true ) );
			if ( $event_id > 0 ) {
				return $event_id;
			}
		}

		return 0;
	}

	/**
	 * Raw event end datetime string.
	 *
	 * @param int $event_id Event ID.
	 * @return string
	 */
	private function get_event_end_raw( $event_id ) {
		$event_id = absint( $event_id );
		if ( $event_id <= 0 ) {
			return '';
		}

		$end = get_post_meta( $event_id, '_EventEndDate', true );
		if ( ! $end ) {
			$end = get_post_meta( $event_id, '_EventEndDateUTC', true );
		}

		return is_string( $end ) ? $end : '';
	}

	/**
	 * Format event start/end for mapping.
	 *
	 * @param int    $event_id Event ID.
	 * @param string $which    start|end.
	 * @return string
	 */
	private function format_event_datetime( $event_id, $which = 'start' ) {
		$event_id = absint( $event_id );
		if ( $event_id <= 0 ) {
			return '';
		}

		$key = 'end' === $which ? '_EventEndDate' : '_EventStartDate';
		$raw = get_post_meta( $event_id, $key, true );
		if ( ! is_string( $raw ) || '' === $raw ) {
			return '';
		}

		$ts = strtotime( $raw );
		return $ts ? wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ts ) : $raw;
	}

	/**
	 * Event start timestamp for push scheduling.
	 *
	 * @param int $event_id Event ID.
	 * @return int
	 */
	private function get_event_start_timestamp( $event_id ) {
		$raw = get_post_meta( absint( $event_id ), '_EventStartDate', true );
		if ( ! is_string( $raw ) || '' === $raw ) {
			return 0;
		}
		$ts = strtotime( $raw );
		return $ts ? (int) $ts : 0;
	}
}
