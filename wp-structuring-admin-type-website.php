<?php
new Structuring_Markup_Type_Website();

/**
 * Schema.org Type WebSite
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/WebSite
 * @link    https://developers.google.com/structured-data/slsb-overview
 * @link    https://developers.google.com/structured-data/site-name
 */
class Structuring_Markup_Type_Website {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		/** DB Connect */
		$db = new Structuring_Markup_Admin_Db();

		/** Set Default Parameter for Array */
		$options = array();

		/** Mode Set */
		if ( isset( $_GET['schema_post_id'] ) && is_numeric( $_GET['schema_post_id'] ) ) {
			$options['id'] = $_GET['schema_post_id'];
		}

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) {
			if ( isset( $_POST['output'] ) ) {
				$db->update_options( $_POST, $this->post_serialize() );
				$db->information_render();
				$options['id'] = $_POST['id'];
			} else {
				$db->error_render();
			}
		} else {
			if ( isset( $_POST['id'] ) && $_POST['id'] === '' ) {
				if ( isset( $_POST['output'] ) ) {
					$options['id'] = $db->insert_options( $_POST, $this->post_serialize() );
					$db->information_render();
				} else {
					$db->error_render();
				}
			}
		}

		/** Mode Judgment */
		if ( isset( $options['id'] ) && is_numeric( $options['id'] ) ) {
			/** Editing mode */
			$options = $db->get_options( $options['id'] );
		} else {
			/** Registration mode */
			$options = $this->get_default_options( $options );
		}
		$this->page_render( $options );
	}

	/**
	 * Form Layout Render
	 *
	 * @since 1.0.0
	 * @param array $options
	 */
	private function page_render( $options )
	{
		$html  = '<input type="hidden" name="id" value="' . esc_attr( $options['id'] ) . '">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th><label for="name">name :</label></th><td>';
		$html .= '<input type="text" name="name" id="name" class="regular-text" required value="' . esc_attr( $options['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '<tr><th><label for="alternateName">alternateName :</label></th><td>';
		$html .= '<input type="text" name="alternateName" id="alternateName" class="regular-text" value="' . esc_attr( $options['alternateName'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '<tr><th><label for="url">url :</label></th><td>';
		$html .= '<input type="text" name="url" id="url" class="regular-text" required value="' . esc_attr( $options['url'] ) . '">';
		$html .= '<small>Default : bloginfo("url")</small>';
		$html .= '</td></tr>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="potential_action">potentialAction Active :</label></th><td>';

		if ( $options['potential_action'] === 'on' ) {
			$html .= '<input type="checkbox" name="potential_action" id="potential_action" value="on" checked="checked">';
		} else {
			$html .= '<input type="checkbox" name="potential_action" id="potential_action" value="on">';
		}

		$html .= '</td></tr>';
		$html .= '<tr><th><label for="target">target :</label></th><td>';
		$html .= '<input type="text" name="target" id="target" class="regular-text" value="' . esc_attr( $options['target'] ) . '">';
		$html .= '<small>Default : bloginfo("url") + /?s=</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since  1.0.0
	 * @param  array $args
	 * @return array $args
	 */
	private function get_default_options( $args ) {
		$args['id']               = '';
		$args['name']             = get_bloginfo('name');
		$args['alternateName']    = $args['name'];
		$args['url']              = get_bloginfo('url');
		$args['potential_action'] = '';
		$args['target']           = $args['url'] . '/?s=';

		return $args;
	}

	/**
	 * Post Data Serialize
	 *
	 * @since  1.0.0
	 * @return array $args
	 */
	private function post_serialize() {
		$args = array();
		$args['name']             = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$args['alternateName']    = isset( $_POST['alternateName'] ) ? $_POST['alternateName'] : '';
		$args['url']              = isset( $_POST['url'] ) ? $_POST['url'] : '';
		$args['potential_action'] = isset( $_POST['potential_action'] ) ? $_POST['potential_action'] : '';
		$args['target']           = isset( $_POST['target'] ) ? $_POST['target'] : '';

		return $args;
	}
}