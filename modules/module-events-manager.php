<?php
/**
 * Events Manager integration module.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Issues EpassCard passes for Events Manager bookings.
 */
class EPC_Module_Events_Manager extends EPC_Module {

	/**
	 * @inheritDoc
	 */
	public function get_slug() {
		return 'events-manager';
	}

	/**
	 * @inheritDoc
	 */
	public function get_label() {
		return __( 'Events Manager', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_dependency_label() {
		return __( 'Events Manager', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function is_available() {
		return epc_is_events_manager_active();
	}

	/**
	 * @inheritDoc
	 */
	public function get_unavailable_message() {
		return __(
			'Events Manager is not installed or activated. Install Events Manager, activate it, then return here to map pass templates.',
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
			'user_full_name'    => __( 'Full name', 'epasscard' ),
			'booking_id'        => __( 'Booking ID', 'epasscard' ),
			'booking_uuid'      => __( 'Booking UUID', 'epasscard' ),
			'booking_status'    => __( 'Booking status', 'epasscard' ),
			'booking_spaces'    => __( 'Spaces', 'epasscard' ),
			'booking_price'     => __( 'Price', 'epasscard' ),
			'event_id'          => __( 'Event ID', 'epasscard' ),
			'event_name'        => __( 'Event name', 'epasscard' ),
			'event_start'       => __( 'Event start', 'epasscard' ),
			'event_end'         => __( 'Event end', 'epasscard' ),
			'expire_date'       => __( 'Pass expiry (event end)', 'epasscard' ),
			'location_name'     => __( 'Location name', 'epasscard' ),
			'booking_comment'   => __( 'Booking comment', 'epasscard' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_status_rules_option_key() {
		return 'epc_events_manager_status_rules';
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
		return $this->get_configurable_booking_statuses();
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
	 * Default pass behavior by EM booking status id.
	 *
	 * @return array<string, string>
	 */
	public function get_default_status_rules() {
		return array(
			'0' => 'none',    // Pending.
			'1' => 'sync',    // Approved.
			'2' => 'revoke',  // Rejected.
			'3' => 'revoke',  // Cancelled.
			'4' => 'none',    // Awaiting online payment.
			'5' => 'none',    // Awaiting payment.
			'6' => 'none',    // Waitlist.
			'7' => 'sync',    // Waitlist approved.
			'8' => 'revoke',  // Waitlist expired.
		);
	}

	/**
	 * Booking statuses for UI.
	 *
	 * @return array<string, string>
	 */
	public function get_configurable_booking_statuses() {
		$booking = class_exists( 'EM_Booking' ) ? new EM_Booking() : null;
		if ( $booking && ! empty( $booking->status_array ) && is_array( $booking->status_array ) ) {
			$out = array();
			foreach ( $booking->status_array as $id => $label ) {
				$out[ (string) (int) $id ] = (string) $label;
			}
			return $out;
		}

		return array(
			'0' => __( 'Pending', 'epasscard' ),
			'1' => __( 'Approved', 'epasscard' ),
			'2' => __( 'Rejected', 'epasscard' ),
			'3' => __( 'Cancelled', 'epasscard' ),
			'4' => __( 'Awaiting Online Payment', 'epasscard' ),
			'5' => __( 'Awaiting Payment', 'epasscard' ),
			'6' => __( 'Waitlist', 'epasscard' ),
			'7' => __( 'Waitlist Approved', 'epasscard' ),
			'8' => __( 'Waitlist Expired', 'epasscard' ),
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
	 * @param string|int $status Status id.
	 * @return string
	 */
	public function get_status_rule( $status ) {
		$key   = (string) absint( $status );
		$rules = $this->get_status_rules();

		return $rules[ $key ] ?? 'none';
	}

	/**
	 * @inheritDoc
	 */
	public function render_module_settings() {
		if ( ! $this->is_available() ) {
			return;
		}

		$statuses = $this->get_configurable_booking_statuses();
		$actions  = $this->get_status_action_options();
		$rules    = $this->get_status_rules();
		?>
		<div id="epc-section-pass-behavior" class="epc-section epc-section--status-rules">
			<h2><?php esc_html_e( 'Pass behavior by booking status', 'epasscard' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Choose what happens to the wallet pass when a booking reaches each status.', 'epasscard' ); ?>
			</p>
			<form class="epc-ajax-form" data-epc-action="<?php echo esc_attr( 'epc_save_status_rules_' . $this->get_slug() ); ?>" method="post" action="">
				<input type="hidden" name="epc_module_slug" value="<?php echo esc_attr( $this->get_slug() ); ?>" />
				<table class="widefat striped epc-status-rules-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Booking status', 'epasscard' ); ?></th>
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
				'message' => __( '{event_name} starts on {event_start}. Your booking pass is on your phone.', 'epasscard' ),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_notification_template_tags() {
		return array(
			'event_name',
			'event_start',
			'user_full_name',
			'user_email',
			'booking_id',
		);
	}

	/**
	 * @inheritDoc
	 */
	public function process_scheduled_notifications() {
		$rules  = $this->get_notification_rules();
		$passes = EPC_DB::get_active_passes_for_module( $this->get_slug() );

		foreach ( $passes as $pass_row ) {
			$booking = $this->get_booking( (int) $pass_row->source_id );
			if ( ! $booking ) {
				continue;
			}

			$event = $booking->get_event();
			if ( ! $event || empty( $event->event_start ) ) {
				continue;
			}

			$start_ts = strtotime( (string) $event->event_start . ( ! empty( $event->event_start_time ) ? ' ' . $event->event_start_time : '' ) );
			if ( ! $start_ts ) {
				continue;
			}

			EPC_Pass_Notifications::maybe_send_for_event(
				$pass_row,
				'before_event_start',
				$rules['before_event_start'] ?? array(),
				(int) $start_ts,
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

		$booking = $this->get_booking( (int) $pass_row->source_id );
		if ( ! $booking ) {
			return array();
		}

		$person = $booking->get_person();
		$event  = $booking->get_event();
		$first  = $person ? (string) $person->first_name : '';
		$last   = $person ? (string) $person->last_name : '';
		$email  = $person ? (string) $person->user_email : '';

		return array(
			'event_name'     => $event ? (string) $event->event_name : '',
			'event_start'    => $this->format_em_event_datetime( $event, 'start' ),
			'user_full_name' => epc_format_user_full_name( $first, $last, $person ? $person->get_name() : $email ),
			'user_email'     => $email,
			'booking_id'     => (string) $booking->booking_id,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_mappable_entities() {
		if ( ! class_exists( 'EM_Events' ) ) {
			return array();
		}

		$events = EM_Events::get(
			array(
				'scope'   => 'all',
				'limit'   => 200,
				'orderby' => 'event_start_date',
				'order'   => 'DESC',
			)
		);

		if ( ! is_array( $events ) ) {
			return array();
		}

		$out = array();
		foreach ( $events as $event ) {
			if ( ! is_object( $event ) || empty( $event->event_id ) ) {
				continue;
			}
			$label = ! empty( $event->event_name ) ? (string) $event->event_name : ( '#' . (int) $event->event_id );
			if ( ! empty( $event->event_start_date ) ) {
				$label .= ' (' . (string) $event->event_start_date . ')';
			}
			$out[] = array(
				'id'    => (int) $event->event_id,
				'label' => $label,
			);
		}

		return $out;
	}

	/**
	 * @inheritDoc
	 */
	public function get_entity_column_label() {
		return __( 'Event', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_empty_entities_message() {
		return __(
			'Create an event in Events Manager, then return here to map a pass template.',
			'epasscard'
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_entity_url() {
		return admin_url( 'edit.php?post_type=' . ( defined( 'EM_POST_TYPE_EVENT' ) ? EM_POST_TYPE_EVENT : 'event' ) );
	}

	/**
	 * @inheritDoc
	 */
	public function get_create_entity_label() {
		return __( 'Create event', 'epasscard' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_entity_label( $entity_id ) {
		$entity_id = absint( $entity_id );
		if ( $entity_id <= 0 ) {
			return '';
		}

		if ( function_exists( 'em_get_event' ) ) {
			$event = em_get_event( $entity_id );
			if ( $event && ! empty( $event->event_name ) ) {
				return (string) $event->event_name;
			}
		}

		return '#' . $entity_id;
	}

	/**
	 * @inheritDoc
	 */
	protected function register_event_hooks() {
		add_action( 'em_booking_added', array( $this, 'on_booking' ), 20, 1 );
		add_action( 'em_bookings_added', array( $this, 'on_booking' ), 20, 1 );
		add_action( 'em_booking_status_changed', array( $this, 'on_booking_status_changed' ), 20, 1 );
		add_action( 'em_booking_deleted', array( $this, 'on_booking_deleted' ), 20, 1 );
	}

	/**
	 * Handle booking added.
	 *
	 * @param mixed $booking Booking object.
	 * @return void
	 */
	public function on_booking( $booking ) {
		if ( ! $booking instanceof EM_Booking ) {
			return;
		}
		$this->handle_booking( $booking );
	}

	/**
	 * Handle status change.
	 *
	 * @param mixed $booking Booking object.
	 * @return void
	 */
	public function on_booking_status_changed( $booking ) {
		if ( ! $booking instanceof EM_Booking ) {
			return;
		}
		$this->handle_booking( $booking );
	}

	/**
	 * Revoke on delete.
	 *
	 * @param mixed $booking Booking.
	 * @return void
	 */
	public function on_booking_deleted( $booking ) {
		$booking_id = 0;
		if ( $booking instanceof EM_Booking ) {
			$booking_id = absint( $booking->booking_id );
		} elseif ( is_numeric( $booking ) ) {
			$booking_id = absint( $booking );
		}

		if ( $booking_id > 0 ) {
			EPC_Pass_Service::revoke_pass( $this->get_slug(), $booking_id );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function sync_by_source_id( $source_id, $mode = 'sync' ) {
		$booking = $this->get_booking( absint( $source_id ) );
		if ( ! $booking ) {
			return new WP_Error( 'epc_invalid_source', __( 'Booking not found.', 'epasscard' ) );
		}

		return $this->sync_booking( $booking, $mode );
	}

	/**
	 * Apply status rule for a booking.
	 *
	 * @param EM_Booking $booking Booking.
	 * @return void
	 */
	private function handle_booking( $booking ) {
		if ( ! EPC_Api_Client::is_configured() || ! $booking instanceof EM_Booking ) {
			return;
		}

		$status = (string) absint( $booking->booking_status );
		$action = $this->get_status_rule( $status );

		switch ( $action ) {
			case 'sync':
				$this->sync_booking( $booking, 'sync' );
				break;
			case 'update':
				$this->sync_booking( $booking, 'update' );
				break;
			case 'revoke':
				EPC_Pass_Service::revoke_pass( $this->get_slug(), absint( $booking->booking_id ) );
				break;
			default:
				break;
		}
	}

	/**
	 * Sync pass from booking.
	 *
	 * @param EM_Booking $booking Booking.
	 * @param string     $mode    Mode.
	 * @return true|\WP_Error
	 */
	private function sync_booking( $booking, $mode = 'sync' ) {
		$booking_id = absint( $booking->booking_id );
		$event      = $booking->get_event();
		$event_id   = $event && ! empty( $event->event_id ) ? absint( $event->event_id ) : absint( $booking->event_id );

		if ( $booking_id <= 0 || $event_id <= 0 ) {
			return new WP_Error( 'epc_invalid_source', __( 'Booking not found.', 'epasscard' ) );
		}

		$mapping = $this->get_mapping( $event_id );
		if ( empty( $mapping['template_uid'] ) ) {
			return new WP_Error( 'epc_no_mapping', __( 'No pass template is mapped for this event.', 'epasscard' ) );
		}

		$person  = $booking->get_person();
		$user_id = $person && ! empty( $person->ID ) ? absint( $person->ID ) : absint( $booking->person_id );
		$first   = $person ? (string) $person->first_name : '';
		$last    = $person ? (string) $person->last_name : '';
		$email   = $person ? (string) $person->user_email : '';
		$display = $person && method_exists( $person, 'get_name' ) ? (string) $person->get_name() : epc_format_user_full_name( $first, $last, $email );

		$status_label = '';
		if ( ! empty( $booking->status_array[ $booking->booking_status ] ) ) {
			$status_label = (string) $booking->status_array[ $booking->booking_status ];
		}

		$location_name = '';
		if ( $event && method_exists( $event, 'get_location' ) ) {
			$location = $event->get_location();
			if ( $location && ! empty( $location->location_name ) ) {
				$location_name = (string) $location->location_name;
			}
		}

		$event_end_raw = '';
		if ( $event ) {
			$event_end_raw = trim( (string) ( $event->event_end_date ?? $event->event_end ?? '' ) );
			if ( $event_end_raw && ! empty( $event->event_end_time ) ) {
				$event_end_raw .= ' ' . $event->event_end_time;
			}
		}

		$values = array(
			'user_display_name' => $display,
			'user_email'        => $email,
			'user_first_name'   => $first,
			'user_last_name'    => $last,
			'user_full_name'    => epc_format_user_full_name( $first, $last, $display ),
			'booking_id'        => (string) $booking_id,
			'booking_uuid'      => isset( $booking->booking_uuid ) ? (string) $booking->booking_uuid : '',
			'booking_status'    => $status_label ? $status_label : (string) $booking->booking_status,
			'booking_spaces'    => isset( $booking->booking_spaces ) ? (string) $booking->booking_spaces : '',
			'booking_price'     => isset( $booking->booking_price ) ? (string) $booking->booking_price : '',
			'event_id'          => (string) $event_id,
			'event_name'        => $event ? (string) $event->event_name : $this->get_entity_label( $event_id ),
			'event_start'       => $this->format_em_event_datetime( $event, 'start' ),
			'event_end'         => $this->format_em_event_datetime( $event, 'end' ),
			'expire_date'       => epc_format_pass_expiry_datetime( $event_end_raw ),
			'location_name'     => $location_name,
			'booking_comment'   => isset( $booking->booking_comment ) ? (string) $booking->booking_comment : '',
		);

		return EPC_Pass_Service::sync_pass(
			$this->get_slug(),
			$booking_id,
			$event_id,
			$user_id,
			$mapping,
			$values,
			$mode
		);
	}

	/**
	 * Load booking by id.
	 *
	 * @param int $booking_id Booking ID.
	 * @return EM_Booking|null
	 */
	private function get_booking( $booking_id ) {
		$booking_id = absint( $booking_id );
		if ( $booking_id <= 0 || ! function_exists( 'em_get_booking' ) ) {
			return null;
		}

		$booking = em_get_booking( $booking_id );
		return $booking instanceof EM_Booking && ! empty( $booking->booking_id ) ? $booking : null;
	}

	/**
	 * Format EM event datetime.
	 *
	 * @param mixed  $event Event object.
	 * @param string $which start|end.
	 * @return string
	 */
	private function format_em_event_datetime( $event, $which = 'start' ) {
		if ( ! is_object( $event ) ) {
			return '';
		}

		if ( 'end' === $which ) {
			$date = isset( $event->event_end_date ) ? (string) $event->event_end_date : ( isset( $event->event_end ) ? (string) $event->event_end : '' );
			$time = isset( $event->event_end_time ) ? (string) $event->event_end_time : '';
		} else {
			$date = isset( $event->event_start_date ) ? (string) $event->event_start_date : ( isset( $event->event_start ) ? (string) $event->event_start : '' );
			$time = isset( $event->event_start_time ) ? (string) $event->event_start_time : '';
		}

		$date = trim( $date );
		if ( '' === $date ) {
			return '';
		}

		$raw = trim( $date . ( $time ? ' ' . $time : '' ) );
		$ts  = strtotime( $raw );
		return $ts ? wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ts ) : $raw;
	}
}
