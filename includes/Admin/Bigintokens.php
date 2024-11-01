<?php
/**
 * Generate Refresh Token
 */

if ( ! function_exists( 'w3scbigin_refreshtoken' ) ) {
	function w3scbigin_refreshtoken() {
		 // $code = isset($_GET["code"]) ? $_GET["code"] : "";
		$code    = isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '';
		$dataSet = new AuthInfos();
		$dataSet->storeInfo( $_POST );
		$zcid       = $dataSet->getInfo( 'bigin_client_id' );
		$zcse       = $dataSet->getInfo( 'bigin_client_secret' );
		$dataCenter = $dataSet->getInfo( 'data_center' );

		if ( $code && $zcid && $zcse && $dataCenter ) {
			$args = array(
				'body'    => array(
					'code'          => $code,
					'redirect_uri'  =>
						get_site_url() .
						'/wp-admin/edit.php?post_type=w3sc_bigin&page=w3sc-cf7-bigin',
					'client_id'     => $zcid,
					'client_secret' => $zcse,
					'grant_type'    => 'authorization_code',
				),
				'headers' => array(
					'Content-Type : application/x-www-form-urlencoded',
				),
			);

			$refres   = wp_remote_post( "https://accounts.zoho{$dataCenter}/oauth/v2/token", $args );
			$response = json_decode( wp_remote_retrieve_body( $refres ), true );

			$refresh_token    = $response['refresh_token'];
			$new_access_token = $response['access_token'];
			$expires_in       = $response['expires_in'];
			$create_time      = time();

			// Condition for authentication success
			if ( $refresh_token && $new_access_token ) {
				$success = 1;
			} else {
				$success = 0;
			}

			// Save data to database
			$tokenarr           = array(
				'access_token' => $new_access_token,
				'expires_in'   => $expires_in,
				'create_time'  => $create_time,
			);
			$tokens_data        = update_option( 'store_all_token_data', json_encode( $tokenarr ) );
			$refresh_token_data = update_option( 'store_refresh_token_data', $refresh_token );

			echo "<script>window.location.href='" . get_site_url() . "/wp-admin/edit.php?post_type=w3sc_bigin&page=w3sc-cf7-bigin&w3sbiginsuccess=$success';</script>";
		}
	}
}

if ( ! function_exists( 'w3scbigin_accessToken' ) ) {
	function w3scbigin_accessToken() {
		$accessToken = '';

		$GetAccessToken = get_option( 'store_all_token_data' );
		if ( $GetAccessToken ) {
			$formatAccessToken = json_decode( $GetAccessToken, true );

			$expireTime = $formatAccessToken['expires_in'];
			$createTime = $formatAccessToken['create_time'];

			if ( $expireTime > time() - $createTime ) {
				$accessToken = $formatAccessToken['access_token'];
			} else {
				$accessToken = w3scbigin_refrtokencon();
			}
		}

		return $accessToken;
	}
}

/**
 * Access token generate function
 */
if ( ! function_exists( 'w3scbigin_refrtokencon' ) ) {
	function w3scbigin_refrtokencon() {
		$client_id        = '';
		$client_secret    = '';
		$refresh_token    = '';
		$new_access_token = '';

		$getRefreshToken = get_option( 'store_refresh_token_data' );

		$dataSet = new AuthInfos();
		$dataSet->storeInfo( $_POST );
		$zcid       = $dataSet->getInfo( 'bigin_client_id' );
		$zcse       = $dataSet->getInfo( 'bigin_client_secret' );
		$dataCenter = $dataSet->getInfo( 'data_center' );

		if ( $getRefreshToken && $zcid && $zcse && $dataCenter ) {
			$args = array(
				'body'    => array(
					'refresh_token' => $getRefreshToken,
					'client_id'     => $zcid,
					'client_secret' => $zcse,
					'grant_type'    => 'refresh_token',
				),
				'headers' => array(
					'Content-Type : application/x-www-form-urlencoded',
				),
			);

			$refrescon = wp_remote_post(
				"https://accounts.zoho{$dataCenter}/oauth/v2/token",
				$args
			);
			$response  = json_decode( wp_remote_retrieve_body( $refrescon ), true );

			$new_access_token = $response['access_token'];
			$expires_in       = $response['expires_in'];
			$create_time      = time();

			// Save data to database
			$tokenarr = array(
				'access_token' => $new_access_token,
				'expires_in'   => $expires_in,
				'create_time'  => $create_time,
			);

			update_option( 'store_all_token_data', json_encode( $tokenarr ) );
		}
		return $new_access_token;
	}
}
