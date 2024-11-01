<?php

/**
 * Installer class
 */
if ( ! class_exists( 'W3scbigin_installer' ) ) {
	class W3scbigin_installer {

		/**
		 * Run the installer
		 *
		 * @return void
		 */
		public function w3scbigin_run() {
			$this->w3scbigin_add_version();
		}

		/**
		 * Add time and version on DB
		 */
		public function w3scbigin_add_version() {
			$installed = get_option( 'w3sc_bigin_installed' );

			if ( ! $installed ) {
				update_option( 'w3sc_bigin_installed', time() );
			}

			update_option( 'w3sc_bigin_version', W3SC_BIGIN_VERSION );
		}
	}
}
