<?php
/**
 * Schema.org Type WebSite
 *
 * @author  Kazuya Takami
 * @version 2.3.3
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
	 * @param array $option
	 */
	public function __construct ( array $option ) {
		/** Default Value Set */
		if ( empty( $option ) ) {
			$option = $this->get_default_options( $option );
		}
		$this->page_render( $option );
	}

	/**
	 * Form Layout Render
	 *
	 * @since 2.3.3
	 * @param array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th><label for="name">name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="alternateName">alternateName :</label></th><td>';
		$html .= '<input type="text" name="option[' . "alternateName" . ']" id="alternateName" class="regular-text" value="' . esc_attr( $option['alternateName'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="url">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $option['url'] ) . '">';
		$html .= '<small>Default : bloginfo("url")</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Sitelink Search Box</caption>';
		$html .= '<tr><th><label for="potential_action">potentialAction Active :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "potential_action" . ']" id="potential_action" value="on"';
		if ( isset( $option['potential_action'] ) &&  $option['potential_action'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="target">target :</label></th><td>';
		$html .= '<input type="text" name="option[' . "target" . ']" id="target" class="regular-text" value="' . esc_attr( $option['target'] ) . '">';
		$html .= '<small>Default : bloginfo("url") + /?s=</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/slsb-overview" target="_blank">https://developers.google.com/structured-data/slsb-overview</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['name']             = get_bloginfo('name');
		$args['alternateName']    = $args['name'];
		$args['url']              = get_bloginfo('url');
		$args['potential_action'] = '';
		$args['target']           = $args['url'] . '/?s=';

		return (array) $args;
	}
}