<?php
/**
 * Schema.org Admin Post
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Post {

	/** Schema.org Type defined. */
	private $type_array = array(
		array("type" => "website",      "display" => "Web Site"),
		array("type" => "organization", "display" => "Organization")
	);

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Input Mode
		 *
		 * ""     : Input Start
		 * "edit" : Edit Mode
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
			$options['id'] = esc_html( $_GET['schema_post_id'] );
		}

		/** Type Set */
		if ( isset( $_GET['type'] ) ) {
			foreach ( $this->type_array as $value ) {
				if ( $_GET['type'] === $value['type'] ) {
					$options['type'] = esc_html( $_GET['type'] );
					break;
				}
			}
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
		$html .= '<form method="get" action="">';
		$html .= '<input type="hidden" name="page" value="wp-structuring-admin-post.php">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th><label for="type">Schema Type :</label></th><td>';
		$html .= '<select id="type" name="type" onchange="this.form.submit();">';

		foreach ( $this->type_array as $value ) {
			$html .= '<option value="' . $value['type'] . '"';
			if ( $value['type'] === $options['type'] ) {
				$html .= ' selected';
			} else {
				if ( $mode === "edit" ) {
					$html .= ' disabled';
				}
			}
			$html .= '>' . $value['display'] . '</option>';
		}

		$html .= '</select>';
		$html .= '</td></tr></table>';
		$html .= '</form>';
		echo $html;

		/** Output Page Select */
		$html  = '<form method="post" action="">';
		$html .= '<input type="hidden" name="id" value="' . esc_attr( $options['id'] ) . '">';
		$html .= '<input type="hidden" name="type" value="' . esc_attr( $options['type'] ) . '">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th>Output :</th><td>';
		echo $html;

		$this->output_checkbox_render( $options['output'], "all", "All", "All Page" );
		$this->output_checkbox_render( $options['output'], "home", "Top", "Top Page" );
		$this->output_checkbox_render( $options['output'], "post", "Post", "Post Page" );
		$this->output_checkbox_render( $options['output'], "page", "Fixed", "Fixed Page" );

		$html  = '</td></tr></table><hr>';
		echo $html;

		switch ( $options['type'] ) {
			case 'website':
				require_once( 'wp-structuring-admin-type-website.php' );
				new Structuring_Markup_Type_Website( $options['option'] );
				break;
			case 'organization':
				require_once ( 'wp-structuring-admin-type-organization.php' );
				new Structuring_Markup_Type_Organization( $options['option'] );
				break;
		}

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
			$html .=  ' checked';
		}
		$html .= '>' . $display . '</label>';

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