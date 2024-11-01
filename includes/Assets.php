<?php

/**
 * Assets handlers class
 */
if ( ! class_exists( 'W3scbigin_assets' ) ) {
	class W3scbigin_assets {

		/**
		 * Class constructor
		 */
		function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'w3scbigin_register_assets' ) );

		}


		/**
		 * All available scripts
		 *
		 * @return array
		 */
		public function w3scbigin_get_scripts() {
			return array(
				'w3scbigin-mainjs' => array(
					'src'     => W3SC_BIGIN_ASSETS . '/js/main-js.js',
					'version' => filemtime( W3SC_BIGIN_PATH . '/assets/js/main-js.js' ),
					'deps'    => array( 'jquery' ),
				),

			);
		}



		/**
		 * All available styles
		 *
		 * @return array
		 */
		public function w3scbigin_get_styles() {
			return array(
				'w3scbigin-admin-style' => array(
					'src'     => W3SC_BIGIN_ASSETS . '/css/admin.css',
					'version' => filemtime(
						W3SC_BIGIN_PATH . '/assets/css/admin.css'
					),
				),
			);
		}

		/**
		 * Register scripts and styles
		 *
		 * @return void
		 */
		public function w3scbigin_register_assets() {
			$scripts = $this->w3scbigin_get_scripts();
			$styles  = $this->w3scbigin_get_styles();

			foreach ( $scripts as $handle => $script ) {

				$deps = isset( $script['deps'] ) ? $script['deps'] : false;
				wp_register_script( $handle, $script['src'], $deps, $script['version'], true );

			}

			foreach ( $styles as $handle => $style ) {

				$deps = isset( $style['deps'] ) ? $style['deps'] : false;
				wp_register_style( $handle, $style['src'], $deps, $style['version'] );

			}
		}
	}
}
