<?php
/**
 * Schema.org Admin Config
 *
 * @author  Kazuya Takami
 * @version 4.5.3
 * @since   4.5.0
 */
class Structuring_Markup_Admin_Config {

	/**
	 * Variable definition.
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 */
	private $text_domain;

	/**
	 * Defined nonce.
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 */
	private $nonce_name;
	private $nonce_action;

	/**
	 * Constructor Define.
	 *
	 * @version 4.5.3
	 * @since   4.5.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain  = $text_domain;
		$this->nonce_name   = "_wpnonce_" . $text_domain;
		$this->nonce_action = "config-"   . $text_domain;

		/**
		 * Update Status
		 *
		 * "ok"     : Successful update
		 * "output" : Output No Check
		 */
		$status = "";

		/** DataBase Update & Insert Mode */
		if ( ! empty( $_POST ) && check_admin_referer( $this->nonce_action, $this->nonce_name ) ) {
			$db = new Structuring_Markup_Admin_Db();
			if ( $db->update_config( $_POST, $this->text_domain ) ) {
				$status = "ok";
			} else {
				$status = "error";
			}
		}
		$options = get_option( $text_domain );
		if ( !$options ) {
			$options = array();
		}

		$this->page_render( $options, $status );
	}

	/**
	 * Setting Page of the Admin Screen.
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 * @param   array  $options
	 * @param   string $status
	 */
	private function page_render ( array $options, $status ) {
		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Schema.org Config', $this->text_domain ) . '</h1>';
		switch ( $status ) {
			case "ok":
				$html .= $this->information_render();
				break;
			case "error":
				$html .= $this->error_render();
				break;
			default:
				break;
		}
		echo $html;

		/** Output Page Select */
		$html  = '<hr>';
		$html .= '<form method="post" action="">';
		echo $html;

		wp_nonce_field( $this->nonce_action, $this->nonce_name );

		$html  = '<table class="schema-admin-table">';

		$html .= '<tr><th>' . esc_html__( 'Plug-in version' ) . ' : </th>';
		$html .= '<td>';
		$html .= isset( $options['version'] ) ? esc_html( $options['version'] ) : '';
		$html .= '</td></tr>';

		$html .= '<tr><th><label for="compress">Enabled : </label></th>';
		$html .= '<td><label><input type="checkbox" name="compress" id="compress" value="on"';
		$html .= ( isset( $options['compress'] ) && $options['compress'] === "on" ) ? ' checked' : '';
		$html .= '><label for="compress">' . esc_html__( 'Compress output data', $this->text_domain ) . '</label></td></tr>';

		$html .= '</table>';

		echo $html;

		submit_button();

		$html  = '</form>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 * @return  string $html
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Schema.org Config Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		return (string) $html;
	}

	/**
	 * Error Message Render
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 * @return  string $html
	 */
	private function error_render () {
		$html  = '<div id="notice" class="notice notice-error is-dismissible below-h2">';
		$html .= '<p>Update Error.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		return (string) $html;
	}
}