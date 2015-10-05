<?php
/**
 * Schema.org Admin Post
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Post {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Input Mode
		 *
		 * ""       : Input Start
		 * "edit"   : Edit Mode
		 */
		$mode = isset( $_GET['mode'] ) ? esc_html( $_GET['mode'] ) : "";

		/**
		 * Update Status
		 *
		 * "ok"     : Successful update
		 * "output" : Output No Check
		 */
		$status = "";

		/** DB Connect */
		$db = new Structuring_Markup_Admin_Db();

		/** Set Default Parameter for Array */
		$options = array(
			"id"     => "",
			"type"   => "website",
			"output" => array(),
			"option" => array()
		);

		/** Key Set */
		if ( isset( $_GET['schema_post_id'] ) && is_numeric( $_GET['schema_post_id'] ) ) {
			$options['id'] = $_GET['schema_post_id'];
		}

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) {
			if ( isset( $_POST['output'] ) ) {
				$db->update_options( $_POST );
				$options['id'] = $_POST['id'];
				$status = "ok";
			} else {
				$status = "output";
			}
		} else {
			if ( isset( $_POST['id'] ) && $_POST['id'] === '' ) {
				if ( isset( $_POST['output'] ) ) {
					$options['id'] = $db->insert_options( $_POST );
					$status = "ok";
					$mode = "edit";
				} else {
					$status = "output";
				}
			}
		}

		/** Mode Judgment */
		if ( isset( $options['id'] ) && is_numeric( $options['id'] ) ) {
			$options = $db->get_options( $options['id'] );
		}

		$this->page_render( $options, $mode, $status );
	}

	/**
	 * Setting Page of the Admin Screen.
	 *
	 * @since 1.0.0
	 * @param array  $options
	 * @param string $mode
	 * @param string $status
	 */
	private function page_render( array $options, $mode, $status ) {
		/** Schema.org Type defined. */
		$type_array = array(
			array("type"=>"website",      "display"=>"Web Site"),
			array("type"=>"organization", "display"=>"Organization")
		);

		$html  = '';
		$html .= '<link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . 'css/style.css">';
		$html .= '<div class="wrap">';
		$html .= '<h1>Schema.org Post</h1>';
		echo $html;

		switch ( $status ) {
			case "ok":
				$this->information_render();
				break;
			case "output":
				$this->output_error_render();
				break;
			default:
				break;
		}

		$html  = '<hr>';
		$html .= '<form method="post" action="">';
		$html .= '<input type="hidden" name="id" value="' . esc_attr( $options['id'] ) . '">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th><label for="type">Type :</label></th><td>';

		$html .= '<select id="type" name="type">';

		foreach ( $type_array as $value ) {
			if ( $value['type'] === $options['type'] ) {
				$html .= '<option value="' . $value['type'] . '" selected>' . $value['display'] . '</option>';
			} else {
				if ( $mode === "" ) {
					$html .= '<option value="' . $value['type'] . '">' . $value['display'] . '</option>';
				} else {
					$html .= '<option value="' . $value['type'] . '" disabled>' . $value['display'] . '</option>';
				}
			}
		}

		$html .= '</select>';
		$html .= '</td></tr>';
		echo $html;

		$html  = '<tr><th>Output :</th><td>';
		echo $html;

		$this->output_checkbox_render( $options['output'], "home", "Top", "Top Page" );
		$this->output_checkbox_render( $options['output'], "post", "Post", "Post Page" );
		$this->output_checkbox_render( $options['output'], "page", "Fixed", "Fixed Page" );

		$html  = '</td></tr></table><hr>';
		echo $html;

		require_once( 'wp-structuring-admin-type-website.php' );
		new Structuring_Markup_Type_Website( $options['option'] );

		$html  = '</form>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * CheckBox Build Render
	 *
	 * @since  1.0.0
	 * @param  array  $option['output']
	 * @param  string $output
	 * @param  string $value
	 * @param  string $display
	 * @return string $html
	 */
	private function output_checkbox_render( array $option, $output, $value, $display ) {
		$html  = '<label>';
		$html .= '<input type="checkbox" name="output[' . $output . ']" value="' . $value . '""';

		if ( isset( $option[$output] ) ) {
			$html .=  ' checked>';
		} else {
			$html .=  '>';
		}
		$html .= $display . '</label>';

		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	private function information_render() {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Schema.org Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Error Message Render
	 *
	 * @since 1.0.0
	 */
	private function output_error_render() {
		$html  = '<div id="notice" class="notice notice-error is-dismissible below-h2">';
		$html .= '<p>Output No Select.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}