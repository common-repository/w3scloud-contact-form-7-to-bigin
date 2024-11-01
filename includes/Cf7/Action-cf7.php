<?php

/**
 * Shortcode handler class
 */
if ( ! class_exists( 'W3scbigin_cf7_submit' ) ) {
	class W3scbigin_cf7_submit {

		/**
		 * Shortcode handler class
		 *
		 * @param  array  $atts
		 * @param  string $content
		 *
		 * @return string
		 */
		public function run_on_cf7_submit( $contact ) {
			$dataSet    = new AuthInfos();
			$dataCenter = $dataSet->getInfo( 'data_center' );
			$args       = array(
				'post_type'      => 'w3sc_bigin',
				'posts_per_page' => -1,
			);
			$formData   = array();
			$the_query  = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					$integrationEnableDisable = get_post_meta( get_the_ID(), 'integration_enable_disable', true ); // ss00
					$cf7ID                    = get_post_meta( get_the_ID(), 'w3sc-cf7-bigin_cf7_form', true );
					$ContactForm              = WPCF7_ContactForm::get_instance( $cf7ID );
					$form_fields              = $ContactForm->scan_form_tags();
					$contact_form             = WPCF7_Submission::get_instance();
					$formData                 = $contact_form->get_posted_data();

					// check if the integration is for this contact form
					if ( $contact->id() == $cf7ID && $integrationEnableDisable ) {
						$contact_form = WPCF7_Submission::get_instance();
						$formData     = $contact_form->get_posted_data();
						$entries      = get_post_meta( get_the_ID(), 'w3sc_bigin_fields_repeat_group', true );
						$module       = get_post_meta( get_the_ID(), 'w3sc-cf7-bigin_module', true );
						$biginFields  = array();

						if ( is_array( $entries ) ) {
							foreach ( $entries as $entry ) {
								if (
									isset( $entry['bigin_select'] ) &&
									isset( $entry['cf7_select'] )
								) {
									$biginFields[ $entry['bigin_select'] ] =
										$formData[ $entry['cf7_select'] ];
								} else {
									continue;
								}
							}
						}

						// Create Bigin Record
						$access_token = w3scbigin_accessToken();

						$post_data = json_encode( array( 'data' => array( $biginFields ) ) );
						$args      = array(
							'body'    => $post_data,
							'headers' => array(
								'Authorization' => 'Bearer ' . $access_token,
							),
						);

						$test         = wp_remote_post(
							"https://www.zohoapis{$dataCenter}/bigin/v1/{$module}",
							$args
						);
						$responceData = json_decode(
							wp_remote_retrieve_body( $test ),
							true
						);

						( $myfile = fopen( __DIR__ . '/record.log', 'w' ) ) or
							die( 'Unable to open file!' );
						fwrite( $myfile, json_encode( $responceData ) );
						fclose( $myfile );

						if (
							isset( $responceData['data'] ) &&
							isset( $responceData['data'][0] ) &&
							isset( $responceData['data'][0]['code'] ) &&
							strtolower( $responceData['data'][0]['code'] ) ==
								'success'
						) {
							return true;
						}
					}
				}
			}
			/* Restore original Post Data */
			wp_reset_postdata();
		}
	}
}
