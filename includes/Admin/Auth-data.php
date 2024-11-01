<?php
class AuthInfos {

	private $infos = array(
		'data_center'         => '',
		'bigin_client_id'     => '',
		'bigin_client_secret' => '',
		'bigin_user_email'    => '',
		'bigin_redirect_url'  => '',
		'bigin_api_base_url'  => '',
		'bigin_account_url'   => '',
		'bigin_authorized'    => '',
		'time'                => '',
	);

	function __construct() {
		$this->setAll();
		$this->infos['time'] = time(); // this field no need. only for make unique
	}

	public function setInfo( $key, $val ) {
		if ( array_key_exists( $key, $this->infos ) ) {
			$this->infos[ $key ] = $val;
		}
		return $this;
	}

	public function getInfo( $key ) {
		return $this->infos[ $key ] ?? '';
	}

	public function storeInfo( $data = null ) {
		if ( isset( $data['store_zoho_info'] ) ) {
			$this->setInfo( 'data_center', sanitize_text_field( $data['data_center'] ) );
			$this->setInfo( 'bigin_client_id', sanitize_text_field( $data['bigin_client_id'] ) );
			$this->setInfo( 'bigin_client_secret', sanitize_text_field( $data['bigin_client_secret'] ) );
			$this->setInfo( 'bigin_user_email', sanitize_text_field( $data['bigin_user_email'] ) );
			$store = update_option( '_bigin_auth_infos', $this->infos );
			$this->message( $store );
		} else {
			$this->setAll();
			update_option( '_bigin_auth_infos', $this->infos );
		}
	}

	private function setAll() {
		 $infos = get_option( '_bigin_auth_infos' );

		$infos = is_array( $infos ) ? $infos : array();

		$this->infos = array_merge( $this->infos, $infos );
		if ( $infos ) {
			foreach ( $infos as $k => $v ) {
				if ( ! $this->infos[ $k ] ) {
					$this->infos[ $k ] = $v;
				}
			}
		}
	}

	public function message( $true ) {
		$message = '';
		$class   = '';

		if ( $true ) {
			$message = 'Settings saved.';
			$class   = 'notice-success';
		} else {
			$message = 'Something Wrong';
			$class   = 'notice-error';
		}
		$notice  = '';
		$notice .= "<div class='notice is-dismissible $class'>";
		$notice .= "<p><strong>$message</strong></p>";
		$notice .=
			"<button type='button' class='notice-dismiss' onClick=\"this.closest('.notice').outerHTML='' \"></button>";
		$notice .= '</div>';

		add_action(
			'_message_',
			function () use ( $notice ) {
				echo _e( $notice );
			}
		);
	}
}
