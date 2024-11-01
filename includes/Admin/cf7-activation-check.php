<?php

if ( ! class_exists( 'Cf7_Activationcheck' ) ) {
	class Cf7_Activationcheck {

		function __construct() {
			$this->cf7_dependency_check();
		}

		private function cf7_dependency_check() {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
				return true;
			} else {
				$this->admin_notice();
				// add_action( 'admin_notices', array($this, 'admin_notice' ));
				return false;
			}
		}

		public function admin_notice() {            ?>
			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Whoops! Bigin with Contact Form 7 Plugin needs  <strong><em>Contact Form 7</em></strong> to work.', 'w3sc-cf7-bigin' ); ?></p>
			</div>
			<?php
		}
	}
}
