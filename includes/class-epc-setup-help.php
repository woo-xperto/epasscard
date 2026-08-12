<?php
/**
 * Free setup help helpers (days window, WhatsApp CTA, avatar markup).
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared free-setup offer utilities.
 */
class EPC_Setup_Help {

	/**
	 * Free setup offer window in days.
	 */
	public const TOTAL_FREE_DAYS = 30;

	/**
	 * Option key for first activation timestamp.
	 */
	public const OPTION_FIRST_ACTIVATED = 'epc_first_activated_time';

	/**
	 * Ensure first-activated timestamp exists and return it.
	 *
	 * @return int Unix timestamp.
	 */
	public static function get_first_activated_time() {
		$first_activated = get_option( self::OPTION_FIRST_ACTIVATED );

		if ( ! $first_activated ) {
			$first_activated = time();
			add_option( self::OPTION_FIRST_ACTIVATED, $first_activated );
		}

		return (int) $first_activated;
	}

	/**
	 * Record activation time once (call from activator).
	 *
	 * @return void
	 */
	public static function maybe_set_first_activated_time() {
		if ( get_option( self::OPTION_FIRST_ACTIVATED ) ) {
			return;
		}

		add_option( self::OPTION_FIRST_ACTIVATED, time() );
	}

	/**
	 * Days remaining in the free setup window.
	 *
	 * @return int
	 */
	public static function get_days_left() {
		$days_passed = (int) floor( ( time() - self::get_first_activated_time() ) / DAY_IN_SECONDS );

		return max( 0, self::TOTAL_FREE_DAYS - $days_passed );
	}

	/**
	 * Whether the free setup offer is still active.
	 *
	 * @return bool
	 */
	public static function is_offer_active() {
		return self::get_days_left() > 0;
	}

	/**
	 * Localized “N days left” label.
	 *
	 * @return string
	 */
	public static function get_days_left_label() {
		$days_left = self::get_days_left();

		if ( $days_left > 0 ) {
			/* translators: %d: number of days left for free setup help. */
			return sprintf( __( '%d days left', 'epasscard' ), $days_left );
		}

		return __( '0 days left', 'epasscard' );
	}

	/**
	 * WhatsApp CTA URL for free setup.
	 *
	 * @return string
	 */
	public static function get_whatsapp_url() {
		return 'https://wa.me/8801926167151?text=' . rawurlencode( 'Hi, I need free setup help for EpassCard' );
	}

	/**
	 * Support avatar SVG markup.
	 *
	 * @param string $class CSS class for the svg element.
	 * @param int    $size  Width/height in px.
	 * @return string
	 */
	public static function get_avatar_svg( $class = 'epc-setup-avatar', $size = 64 ) {
		$size = absint( $size );

		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="%2$s" aria-hidden="true">
				<circle cx="40" cy="40" r="40" fill="#E2E8F0"/>
				<path d="M16 68C16 56 26 48 40 48C54 48 64 56 64 68" fill="#475569"/>
				<path d="M32 48L40 58L48 48" fill="#FFFFFF"/>
				<path d="M34 40V46C34 49.3 36.7 52 40 52C43.3 52 46 49.3 46 46V40" fill="#FDBA74"/>
				<circle cx="40" cy="32" r="14" fill="#FDBA74"/>
				<path d="M26 28C26 20 32 16 40 16C48 16 54 20 54 28C54 28 50 24 40 24C30 24 26 28 26 28Z" fill="#334155"/>
				<rect x="29" y="29" width="9" height="7" rx="2" fill="none" stroke="#1E293B" stroke-width="2"/>
				<rect x="42" y="29" width="9" height="7" rx="2" fill="none" stroke="#1E293B" stroke-width="2"/>
				<line x1="38" y1="32" x2="42" y2="32" stroke="#1E293B" stroke-width="2"/>
				<path d="M36 38C36 40 38 41 40 41C42 41 44 40 44 38" stroke="#C2410C" stroke-width="1.5" stroke-linecap="round"/>
				<path d="M24 32C24 23 31 16 40 16C49 16 56 23 56 32" stroke="#0F172A" stroke-width="3" stroke-linecap="round" fill="none"/>
				<rect x="22" y="28" width="4" height="8" rx="2" fill="#0F172A"/>
				<rect x="54" y="28" width="4" height="8" rx="2" fill="#0F172A"/>
				<path d="M24 34C24 40 30 42 34 42" stroke="#0F172A" stroke-width="2" stroke-linecap="round" fill="none"/>
				<circle cx="35" cy="42" r="2.5" fill="#22C55E"/>
			</svg>',
			$size,
			esc_attr( $class )
		);
	}

	/**
	 * Render the sidebar sticky free-setup card (admin shell).
	 *
	 * @return void
	 */
	public static function render_sidebar_card() {
		if ( ! self::is_offer_active() ) {
			return;
		}

		$days_text = self::get_days_left_label();
		?>
		<a href="<?php echo esc_url( self::get_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" class="epc-sidebar-setup-card">
			<div class="epc-sidebar-setup-top">
				<div class="epc-sidebar-avatar-wrap">
					<?php echo self::get_avatar_svg( 'epc-sidebar-avatar-img', 38 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted SVG from helper. ?>
					<span class="epc-sidebar-status-dot" aria-hidden="true"></span>
				</div>
				<div class="epc-sidebar-badge">
					<span><?php echo esc_html( $days_text ); ?></span>
				</div>
			</div>
			<div class="epc-sidebar-setup-content">
				<h4 class="epc-sidebar-setup-title"><?php esc_html_e( 'Get free setup help', 'epasscard' ); ?></h4>
				<p class="epc-sidebar-setup-desc"><?php esc_html_e( 'Get help from our experts at no cost', 'epasscard' ); ?></p>
			</div>
		</a>
		<?php
	}

	/**
	 * Render the welcome-page free-setup card.
	 *
	 * @return void
	 */
	public static function render_welcome_card() {
		$days_text = self::get_days_left_label();
		?>
		<div class="epc-setup-help-card">
			<div class="epc-setup-left">
				<div class="epc-setup-avatar-wrapper">
					<?php echo self::get_avatar_svg( 'epc-setup-avatar-svg', 64 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted SVG from helper. ?>
					<span class="epc-setup-status-dot" aria-hidden="true"></span>
				</div>
				<div class="epc-setup-info">
					<div class="epc-setup-time-badge">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
						<span><?php echo esc_html( $days_text ); ?></span>
					</div>
					<h3><?php esc_html_e( 'Get free setup help', 'epasscard' ); ?></h3>
					<p><?php esc_html_e( 'Get help from our experts at no cost.', 'epasscard' ); ?></p>
				</div>
			</div>
			<div class="epc-setup-right">
				<a href="<?php echo esc_url( self::get_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" class="epc-setup-claim-btn">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
					</svg>
					<span><?php esc_html_e( 'Claim Free Setup', 'epasscard' ); ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}
