<?php

/**
 * The Menu handler class
 */
if ( ! class_exists( 'W3scbigin_menu' ) ) {
	class W3scbigin_menu {

		public $w3scbigin_setting;

		/**
		 * Initialize the class
		 */
		function __construct( $bigin_setting ) {
			$this->w3scbigin_setting = $bigin_setting;

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		/**
		 * Register admin menu
		 *
		 * @return void
		 */
		public function admin_menu() {
			$parent_slug = 'w3sc-cf7-bigin';
			$capability  = 'manage_options';

			$hook = add_submenu_page(
				'edit.php?post_type=w3sc_bigin',
				__( 'Bigin Auth Settings', 'w3sc-cf7-bigin' ),
				__( 'Bigin Auth Settings', 'w3sc-cf7-bigin' ),
				$capability,
				'w3sc-cf7-bigin',
				array( $this->w3scbigin_setting, 'biginSettingspage' )
			);

			add_action( 'admin_head-' . $hook, array( $this, 'enqueue_assets' ) );
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		public function enqueue_assets() {
			wp_enqueue_style( 'w3scbigin-admin-style' );
			wp_enqueue_script( 'w3scbigin-mainjs' );
		}
	}
}
