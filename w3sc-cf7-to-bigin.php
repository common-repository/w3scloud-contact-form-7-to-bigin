<?php
/**
 * @wordpress-plugin
 * Plugin Name:       W3SCloud Contact Form 7 to Bigin
 * Plugin URI:        https://wordpress.org/plugins/w3sc-cf7-to-bigin/
 * Description:       Zoho Bigin Integration with Contact Form 7. Add Contacts from Contact form 7 form entry.
 * Version:           2.3.0
 * Author:            W3SCloud Technology
 * Author URI:        https://w3scloud.com/
 * License:           GPL-2.0+
 * License URI:       license.txt
 * Text Domain:       w3sc-cf7-bigin
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 *  Get all php file.
 */

require_once __DIR__ . '/includes/Admin/Menu.php';
require_once __DIR__ . '/includes/Admin/Settings.php';
require_once __DIR__ . '/includes/Cf7/Action-cf7.php';
require_once __DIR__ . '/includes/Admin/Auth-data.php';
require_once __DIR__ . '/includes/Admin/cf7-activation-check.php';
require_once __DIR__ . '/includes/Admin/Bigintokens.php';
require_once __DIR__ . '/includes/CMB2-w3sc/init.php';
require_once __DIR__ . '/includes/Admin.php';
require_once __DIR__ . '/includes/Assets.php';
require_once __DIR__ . '/includes/Bigincpt.php';
require_once __DIR__ . '/includes/Installer.php';

/**
 * The main plugin class
 */
final class W3sc_Bigin {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '2.3.0';

	/**
	 * Class constructor
	 */
	private function __construct() {
		$this->define_w3scbigin_constants();

		// Generate cpt
		new W3scbigin_Cpt();

		$oncf7submit = new W3scbigin_cf7_submit();

		register_activation_hook( __FILE__, array( $this, 'w3scbigin_activate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_w3scbigin_plugin' ) );

		// Insert in Bigin on CF7 submit
		add_action( 'wpcf7_before_send_mail', array( $oncf7submit, 'run_on_cf7_submit' ), 10, 1 );
	}

	/**
	 * Initializes a singleton instance
	 *
	 * @return \W3sc_Bigin
	 */
	public static function w3scbigin_init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_w3scbigin_constants() {
		define( 'W3SC_BIGIN_VERSION', self::version );
		define( 'W3SC_BIGIN_FILE', __FILE__ );
		define( 'W3SC_BIGIN_PATH', __DIR__ );
		define( 'W3SC_BIGIN_URL', plugins_url( '', W3SC_BIGIN_FILE ) );
		define( 'W3SC_BIGIN_ASSETS', W3SC_BIGIN_URL . '/assets' );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_w3scbigin_plugin() {
		new W3scbigin_assets();

		if ( is_admin() ) {
			new W3scbigin_admin();
		}
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function w3scbigin_activate() {
		$installer = new W3scbigin_installer();
		$installer->w3scbigin_run();
	}
}

/**
 * Initializes the main plugin
 *
 * @return \W3sc_Bigin
 */
function w3sc_bigin() {
	 return W3sc_Bigin::w3scbigin_init();
}

// kick-off the plugin
w3sc_bigin();
