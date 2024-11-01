<?php

/**
 * Setting Handler class
 */

if ( ! class_exists( 'W3scbigin_setting' ) ) {
	class W3scbigin_setting {

		/**
		 * Handles the settings page
		 *
		 * @return void
		 */
		public function biginSettingspage() {
			$redirectURLEncoded = urlencode_deep( admin_url( 'edit.php?post_type=w3sc_bigin&page=w3sc-cf7-bigin' ) );
			$redirectURL        = admin_url( 'edit.php?post_type=w3sc_bigin&page=w3sc-cf7-bigin' );
			$siteURL            = parse_url( site_url() )['host'];

			$dataSet = new AuthInfos();
			$dataSet->storeInfo( $_POST );
			$zcid       = $dataSet->getInfo( 'bigin_client_id' );
			$dataCenter = $dataSet->getInfo( 'data_center' );

			// Get Authenticate Success/Failure notification
			$w3ssucc_noti = isset( $_GET['w3sbiginsuccess'] ) ? sanitize_text_field( $_GET['w3sbiginsuccess'] ) : '';
			// $w3ssucc_noti = sanitize_text_field( $w3ssucc_noti );

			if ( $w3ssucc_noti ) {
				if ( $w3ssucc_noti == 1 ) {
					printf( '<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Authenticated Successfully' );
				} else {
					printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Something went Wrong!Please Try again' );
				}
			}

			// CF7 Activation check
			new Cf7_Activationcheck();

			echo '<div>';
			?>

			<?php do_action( '_message_' ); ?>
			<h2>Bigin Auth Settings</h2>
			<hr/>
			<form method="post">
				<table class="zoho-auth-info">
					<tr>
						<td colspan="2"><h3 >Information to create Zoho Client :: </h3></td>
					</tr>
					<tr>
						<td><h4>No Zoho Bigin Account?</h4></td>
						<td>
							<a target="_blank" href="https://payments.zoho.com/ResellerCustomerSignUp.do?id=4c1e927246825d26d1b5d89b9b8472de"><b>Create FREE Account!</b></a>
						</td>
					</tr> 
					<tr>
						<td><h4>Client Name</h4></td>
						<td><code>W3S CF7 to Bigin</code></td>
					</tr>        
					<tr>
						<td><h4>Client Domain</h4></td>
						<td><code><?php echo esc_html( $siteURL ); ?></code></td>
					</tr>
					<tr>
						<td><h4>Authorized redirect URIs</h4></td>
						<td><code><?php echo esc_html( $redirectURL ); ?></code></td>
					</tr>
					<tr>
						<td><h4>Client Type</h4></td>
						<td><code>Web Based</code></td>
					</tr>
					<tr>
						<td colspan="2"><h3>Zoho Credentials :: </h3></td>
					</tr>
					<tr>
						<td><h4>Data Center</h4></td>
						<td>
						<?php
						foreach ( array(
							'zoho.com'    => '.com',
							'zoho.eu'     => '.eu',
							'zoho.com.au' => '.com.au',
							'zoho.in'     => '.in',
						) as $k => $v ) {
							$selected = $dataCenter == $v ? "checked='checked'" : '';
							?>
							<label><input type='radio' name='data_center' value='<?php echo esc_html( $v ); ?>' <?php echo esc_html( $selected ); ?>><span style='margin-right:15px'><?php echo esc_html( $k ); ?></span></label>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td valign="top"><h4 class="zci">Zoho Client ID</h4></td>
						<td>
							<input type="text" name="bigin_client_id" id="bigin_client_id" value="<?php	echo esc_html( $dataSet->getInfo( 'bigin_client_id' ) ); ?>">
							<p class="guid">
								Your Zoho App Client ID. To Generate, Please follow <a href="https://www.zoho.com/crm/help/developer/api/register-client.html" target="_blank">this instructions.</a>
							</p>
						</td>
					</tr>
					<tr>
						<td valign="top"><h4 class="zcs">Zoho Client Secret</h4></td>
						<td>
							<input type="password" name="bigin_client_secret" id="bigin_client_secret" value="<?php echo esc_html( $dataSet->getInfo( 'bigin_client_secret' ) ); ?>">
							<p class="guid">
							Your Zoho App Client Secret. To Generate, Please follow <a href="https://www.zoho.com/crm/help/developer/api/register-client.html" target="_blank">this instructions.</a>
							</p>
						</td>
					</tr>
					<tr>
						<td><h4>Zoho User Email</h4></td>
						<td>
							<input type="email" name="bigin_user_email" id="bigin_user_email" value="<?php echo esc_html( $dataSet->getInfo( 'bigin_user_email' ) ); ?>">
						</td>
					</tr>
					<?php if ( $dataSet->getInfo( 'bigin_client_id' ) && $dataSet->getInfo( 'data_center' ) ) : ?>
					<tr>
						<td><h4>Authorize Zoho Account</h4></td>
						<td>
							<a href="https://accounts.zoho<?php echo esc_html( $dataCenter ); ?>/oauth/v2/auth?scope=ZohoBigin.modules.ALL,ZohoBigin.settings.ALL&client_id=<?php echo esc_html( $zcid ); ?>&response_type=code&access_type=offline&prompt=consent&redirect_uri=<?php echo esc_html( $redirectURLEncoded ); ?>"><b>Grant Access</b></a>
								<?php w3scbigin_refreshtoken(); ?>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td colspan="2">
							<div style="margin-top: 20px">
								<button name="store_zoho_info" value="save" class="button button-primary">Save & Bring Grant Access</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
			<?php
			echo '</div>';
		}
	}
}
