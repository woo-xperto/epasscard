<?php
/**
 * Frontend pass access (My Account, shortcodes).
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Member-facing wallet pass UI.
 */
class EPC_Frontend {

	public const WC_ENDPOINT = 'wallet-passes';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_shortcode( 'epc_my_passes', array( __CLASS__, 'shortcode_my_passes' ) );

		add_action( 'init', array( __CLASS__, 'register_wc_endpoint' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'wc_account_menu_item' ) );
		add_action( 'woocommerce_account_' . self::WC_ENDPOINT . '_endpoint', array( __CLASS__, 'render_wc_account_passes' ) );

		add_action( 'mepr_account_nav', array( __CLASS__, 'mepr_account_nav_link' ) );
		add_action( 'mepr_account_nav_content', array( __CLASS__, 'mepr_account_content' ), 10, 2 );
	}

	/**
	 * Register WooCommerce account endpoint.
	 *
	 * @return void
	 */
	public static function register_wc_endpoint() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_rewrite_endpoint( self::WC_ENDPOINT, EP_ROOT | EP_PAGES );
	}

	/**
	 * Enqueue frontend styles for pass lists.
	 *
	 * @return void
	 */
	public static function enqueue_frontend_assets() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$should_load = false;

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
            $should_load = true;
        }

		if ( is_singular() ) {
			$post = get_post();
			if ( $post instanceof WP_Post && has_shortcode( (string) $post->post_content, 'epc_my_passes' ) ) {
				$should_load = true;
			}
		}

		/**
		 * Filter whether frontend pass list styles should load.
		 *
		 * @param bool $should_load Load flag.
		 */
		if ( ! apply_filters( 'epc_enqueue_frontend_assets', $should_load ) ) {
			return;
		}

		wp_enqueue_style(
			'epc-frontend',
			EPC_PLUGIN_URL . 'admin/css/admin.css',
			array(),
			EPC_VERSION
		);
	}

	/**
	 * Add WooCommerce My Account menu item.
	 *
	 * @param array<string, string> $items Menu items.
	 * @return array<string, string>
	 */
	public static function wc_account_menu_item( $items ) {
		if ( ! is_user_logged_in() ) {
			return $items;
		}

		$new = array();
		foreach ( $items as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'orders' === $key ) {
				$new[ self::WC_ENDPOINT ] = __( 'Wallet passes', 'epasscard' );
			}
		}

		if ( ! isset( $new[ self::WC_ENDPOINT ] ) ) {
			$new[ self::WC_ENDPOINT ] = __( 'Wallet passes', 'epasscard' );
		}

		return $new;
	}

	/**
	 * Render WooCommerce account endpoint content.
	 *
	 * @return void
	 */
	public static function render_wc_account_passes() {
		self::render_passes_list( get_current_user_id() );
	}

	/**
	 * MemberPress account nav link.
	 *
	 * @param MeprUser $user MemberPress user.
	 * @return void
	 */
	public static function mepr_account_nav_link( $user ) {
		if ( ! class_exists( 'MeprUser' ) || ! class_exists( 'MeprOptions' ) || ! $user instanceof MeprUser ) {
			return;
		}

		$mepr_options = MeprOptions::fetch();
		$account_url  = $mepr_options->account_page_url();
		$delim        = MeprAppCtrl::get_param_delimiter_char( $account_url );
		$url          = $account_url . $delim . 'action=wallet_passes';
		?>
		<span class="mepr-nav-item">
			<a href="<?php echo esc_url( $url ); ?>" id="mepr-account-wallet-passes"><?php esc_html_e( 'Wallet Passes', 'epasscard' ); ?></a>
		</span>
		<?php
	}

	/**
	 * MemberPress account tab content.
	 *
	 * @param string $action Current action.
	 * @param array  $atts   Shortcode atts.
	 * @return void
	 */
	public static function mepr_account_content( $action, $atts ) {
		unset( $atts );
		if ( 'wallet_passes' !== $action || ! class_exists( 'MeprUtils' ) ) {
			return;
		}

		$user = MeprUtils::get_currentuserinfo();
		if ( ! $user || empty( $user->ID ) ) {
			return;
		}

		echo '<div class="mepr-wallet-passes">';
		self::render_passes_list( (int) $user->ID );
		echo '</div>';
	}

	/**
	 * Shortcode [epc_my_passes].
	 *
	 * @param array<string, string> $atts Shortcode attributes.
	 * @return string
	 */
	public static function shortcode_my_passes( $atts ) {
		if ( ! is_user_logged_in() ) {
			return '<p class="epc-my-passes epc-my-passes--guest">' . esc_html__( 'Please log in to view your wallet passes.', 'epasscard' ) . '</p>';
		}

		ob_start();
		echo '<div class="epc-my-passes">';
		self::render_passes_list( get_current_user_id() );
		echo '</div>';
		return (string) ob_get_clean();
	}

	/**
	 * Output HTML list of passes for a user.
	 *
	 * @param int $user_id User id.
	 * @return void
	 */
	public static function render_passes_list( $user_id ) {
		$user_id = absint( $user_id );
		if ( $user_id <= 0 ) {
			echo '<p>' . esc_html__( 'No wallet passes found.', 'epasscard' ) . '</p>';
			return;
		}

		$passes = EPC_DB::get_active_passes_for_user( $user_id );
		$passes = array_values(
			array_filter(
				$passes,
				static function ( $pass ) {
					return is_object( $pass ) && ! empty( $pass->pass_link );
				}
			)
		);

		/**
		 * Filter passes shown on the frontend for a user.
		 *
		 * @param array<int, object> $passes  Pass rows.
		 * @param int                $user_id User id.
		 */
		$passes = (array) apply_filters( 'epc_frontend_user_passes', $passes, $user_id );

		if ( empty( $passes ) ) {
			echo '<p>' . esc_html__( 'You do not have any wallet passes yet.', 'epasscard' ) . '</p>';
			return;
		}

		echo '<ul class="epc-pass-list">';
		foreach ( $passes as $pass ) {
			$label = self::get_pass_label( $pass );
			echo '<li class="epc-pass-list__item">';
			echo '<a class="epc-pass-list__link button" href="' . esc_url( (string) $pass->pass_link ) . '" target="_blank" rel="noopener noreferrer">';
			echo esc_html( $label );
			echo '</a>';
			echo '</li>';
		}
		echo '</ul>';
	}

	/**
	 * Human label for a pass row on the frontend.
	 *
	 * @param object $pass Pass row.
	 * @return string
	 */
	private static function get_pass_label( $pass ) {
		$label = __( 'View wallet pass', 'epasscard' );

		if ( ! empty( $pass->module ) && ! empty( $pass->entity_id ) && function_exists( 'epc_plugin' ) ) {
			$mod = epc_plugin()->get_module( (string) $pass->module );
			if ( $mod ) {
				$label = sprintf(
					/* translators: %s: membership or product name */
					__( 'View pass: %s', 'epasscard' ),
					$mod->get_entity_label( (int) $pass->entity_id )
				);
			}
		}

		/**
		 * Filter frontend pass link label.
		 *
		 * @param string $label Label.
		 * @param object $pass  Pass row.
		 */
		return (string) apply_filters( 'epc_frontend_pass_label', $label, $pass );
	}
}
