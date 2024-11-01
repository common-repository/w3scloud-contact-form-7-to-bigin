<?php

if ( ! class_exists( 'W3scbigin_admin' ) ) {
	class W3scbigin_admin {

		/**
		 * Initialize the class
		 */
		function __construct() {
			$bigin_setting = new W3scbigin_setting();

			new W3scbigin_menu( $bigin_setting );
		}
	}
}
