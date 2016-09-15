<?php
/**
 * Schema.org Site Navigation Element
 *
 * @author  Kazuya Takami
 * @version 3.1.0
 * @since   3.1.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/SiteNavigationElement
 */
class Structuring_Markup_Type_Site_Navigation {

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.0
	 * @since   3.1.0
	 * @param   array $option
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
	 * @version 3.1.0
	 * @since   3.1.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require"><label for="menu_name">Menu Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "menu_name" . ']" id="menu_name" class="regular-text" required value="' . esc_attr( $option['menu_name'] ) . '">';
		$html .= '<small>Menu ID, name, or slug</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://schema.org/SiteNavigationElement" target="_blank">https://schema.org/SiteNavigationElement</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @version 3.1.0
	 * @since   3.1.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['menu_name'] = '';

		return (array) $args;
	}
}