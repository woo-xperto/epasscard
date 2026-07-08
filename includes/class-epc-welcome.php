<?php
/**
 * Welcome screen and first-activation redirect.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Onboarding welcome page (page=epc-welcome).
 */
class EPC_Welcome {

	public const PAGE_SLUG = 'epc-welcome';

	public const OPTION_ACTIVATION_REDIRECT = 'epc_activation_redirect';

	public const TRANSIENT_ACTIVATION_REDIRECT = 'epc_activation_redirect';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_redirect_after_activation' ) );
		add_action( 'wp_ajax_epc_welcome_api_call', array( __CLASS__, 'ajax_welcome_api_call' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 4 );
	}

	/**
	 * Register hidden welcome submenu (admin.php?page=epc-welcome).
	 *
	 * @return void
	 */
	public static function register_page() {
		add_submenu_page(
			'admin.php',
			__( 'Welcome to EpassCard', 'epasscard' ),
			__( 'Welcome', 'epasscard' ),
			'manage_options',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Whether current screen is the welcome page.
	 *
	 * @return bool
	 */
	public static function is_welcome_screen() {
		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen detection.
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( (string) $_GET['page'] ) ) : '';

		return self::PAGE_SLUG === $page;
	}

	/**
	 * Enqueue welcome assets.
	 *
	 * @param string $hook Hook suffix.
	 * @return void
	 */
	public static function enqueue_assets( $hook ) {
		if ( 'admin_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		$style_path = EPC_PLUGIN_DIR . 'admin/css/welcome.css';
		wp_enqueue_style(
			'epc-welcome',
			EPC_PLUGIN_URL . 'admin/css/welcome.css',
			array(),
			file_exists( $style_path ) ? (string) filemtime( $style_path ) : EPC_VERSION
		);

		$script_path = EPC_PLUGIN_DIR . 'admin/js/welcome.js';
		wp_enqueue_script(
			'epc-welcome',
			EPC_PLUGIN_URL . 'admin/js/welcome.js',
			array( 'jquery' ),
			file_exists( $script_path ) ? (string) filemtime( $script_path ) : EPC_VERSION,
			true
		);

		wp_localize_script(
			'epc-welcome',
			'epcWelcomePage',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'epc_welcome_page_nonce' ),
				'i18n'    => array(
					'invalidEmail' => __( 'Please enter a valid email address to subscribe.', 'epasscard' ),
					'processing'   => __( 'Processing…', 'epasscard' ),
					'error'        => __( 'There was an error. Please try again.', 'epasscard' ),
				),
			)
		);
	}

	/**
	 * Redirect to welcome screen once after activation.
	 *
	 * @return void
	 */
	public static function maybe_redirect_after_activation() {
		if ( ! is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return;
		}

		if ( get_option( self::OPTION_ACTIVATION_REDIRECT ) ) {
			return;
		}

		if ( ! get_transient( self::TRANSIENT_ACTIVATION_REDIRECT ) ) {
			return;
		}

		delete_transient( self::TRANSIENT_ACTIVATION_REDIRECT );
		update_option( self::OPTION_ACTIVATION_REDIRECT, true, false );

		wp_safe_redirect( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
		exit;
	}

	/**
	 * Set activation redirect transient (call from activator).
	 *
	 * @return void
	 */
	public static function flag_activation_redirect() {
		if ( get_option( self::OPTION_ACTIVATION_REDIRECT ) ) {
			return;
		}

		set_transient( self::TRANSIENT_ACTIVATION_REDIRECT, true, 30 );
	}

	/**
	 * Render welcome page.
	 *
	 * @return void
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'epasscard' ) );
		}

		?>
		<div class="epc-welcome">
			<div class="epc-welcome-header">
				<h1><?php esc_html_e( 'Welcome to EpassCard!', 'epasscard' ); ?></h1>
				<p><?php esc_html_e( 'Your plugin has been successfully activated!', 'epasscard' ); ?></p>
			</div>

			<div class="epc-welcome-content">
				<h2 class="epc-welcome-section-title"><?php esc_html_e( 'Stay updated with the latest features, security updates, tips and tricks.', 'epasscard' ); ?></h2>

				<form method="post">
					<div class="epc-welcome-form-group">
						<label for="epc_admin_email"><?php esc_html_e( 'Email Address', 'epasscard' ); ?></label>
						<input
							type="email"
							id="epc_admin_email"
							name="epc_admin_email"
							value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"
							class="epc-welcome-input"
							required
							placeholder="<?php esc_attr_e( 'Enter your email address', 'epasscard' ); ?>"
						/>
						<p style="font-size:12px;color:#6c757d;margin-top:5px;">
							<?php esc_html_e( 'This email will be used for notifying you.', 'epasscard' ); ?>
						</p>
					</div>

					<div class="epc-welcome-buttons">
						<button
							type="button"
							name="epc_welcome_subscribe"
							id="epc_welcome_subscribe"
							value="subscribe"
							class="epc-welcome-btn epc-welcome-btn-primary"
						>
							<?php esc_html_e( 'Subscribe & Continue Setup', 'epasscard' ); ?>
						</button>
						<button
							type="button"
							name="epc_welcome_no_thanks"
							id="epc_welcome_no_thanks"
							value="no_thanks"
							class="epc-welcome-btn epc-welcome-btn-secondary"
						>
							<?php esc_html_e( 'Skip & Continue Setup', 'epasscard' ); ?>
						</button>
						<button type="button" class="epc-welcome-btn epc-welcome-btn-dashboard">
							<a href="https://epasscard.com/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit EpassCard', 'epasscard' ); ?></a>
						</button>
					</div>
				</form>

				<div class="epc-welcome-other-products">
					<h2 class="epc-welcome-section-title"><?php esc_html_e( 'Our Other Products', 'epasscard' ); ?></h2>
					<div class="epc-welcome-features">
						<div class="epc-welcome-feature">
							<h3><?php esc_html_e( 'Giveaway Lottery for WooCommerce', 'epasscard' ); ?></h3>
							<p><?php esc_html_e( 'Run giveaways and raffles on your WooCommerce store with ticket sales and winner selection.', 'epasscard' ); ?></p>
						</div>
						<div class="epc-welcome-feature">
							<h3><?php esc_html_e( 'GiftCard for WooCommerce', 'epasscard' ); ?></h3>
							<p><?php esc_html_e( 'Create and sell customizable gift cards for your WooCommerce store with ease.', 'epasscard' ); ?></p>
						</div>
						<div class="epc-welcome-feature">
							<h3><?php esc_html_e( 'Variation Monster for WooCommerce', 'epasscard' ); ?></h3>
							<p><?php esc_html_e( 'Manage variation products including swatches, quick view, galleries, and variation tables.', 'epasscard' ); ?></p>
						</div>
						<div class="epc-welcome-feature">
							<h3><?php esc_html_e( 'Teachable Enrollment for WooCommerce', 'epasscard' ); ?></h3>
							<p><?php esc_html_e( 'Integrate Teachable courses with WooCommerce for automated enrollments.', 'epasscard' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX: subscribe or skip welcome notice.
	 *
	 * @return void
	 */
	public static function ajax_welcome_api_call() {
		check_ajax_referer( 'epc_welcome_page_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'epasscard' ) ), 403 );
		}

		$admin_email = isset( $_POST['admin_email'] ) ? sanitize_email( wp_unslash( (string) $_POST['admin_email'] ) ) : get_option( 'admin_email' );
		$type        = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( (string) $_POST['type'] ) ) : '';
		$api_url     = 'https://webcartisan.com/wp-json/epasscard/v1/welcome-notice';

		wp_remote_post(
			$api_url,
			array(
				'method'   => 'POST',
				'timeout'  => 5,
				'blocking' => false,
				'headers'  => array(
					'Content-Type' => 'application/json',
				),
				'body'     => wp_json_encode(
					array(
						'site_url'    => get_site_url(),
						'admin_email' => $admin_email,
						'type'        => $type,
					)
				),
			)
		);

		wp_send_json_success(
			array(
				'url' => admin_url( 'admin.php?page=epasscard' ),
			)
		);
	}

	/**
	 * Add welcome page link to plugin row meta.
	 *
	 * @param array<int, string> $links_array Plugin row meta links.
	 * @param string             $plugin_file_name Plugin file.
	 * @param array<string, mixed> $plugin_data Plugin data.
	 * @param string             $status Status.
	 * @return array<int, string>
	 */
	public static function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
		unset( $plugin_data, $status );

		if ( false !== strpos( $plugin_file_name, plugin_basename( EPC_PLUGIN_FILE ) ) ) {
			$links_array[] = '<a href="' . esc_url( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) ) . '">' . esc_html__( 'Welcome Page', 'epasscard' ) . '</a>';
		}

		return $links_array;
	}
}
