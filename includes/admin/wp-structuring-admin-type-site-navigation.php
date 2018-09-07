<?php
/**
 * Schema.org Site Navigation Element
 *
 * @author  Kazuya Takami
 * @version 4.5.3
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
	 * @version 4.5.3
	 * @since   3.1.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require"><label for="menu_name">Menu Name :</label></th><td>';

		$nav_menus = wp_get_nav_menus();

		if ( count( $nav_menus ) > 0 ) {
			$html .= '<select name="option[' . "menu_name" . ']" id="menu_name">';

			foreach ( (array) $nav_menus as $menu ) {
				if ( $option['menu_name'] === $menu->name ) {
					$html .= '<option value="' . esc_attr( $menu->name ) . '" selected>';
				} else {
					$html .= '<option value="' . esc_attr( $menu->name ) . '">';
				}
				$html .= esc_html( $menu->name );
				$html .= '</option>';
			}

			$html .= '</select>';
		}

		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>schema.org SiteNavigationElement :</th>';
		$html .= '<td><a href="https://schema.org/SiteNavigationElement" target="_blank">https://schema.org/SiteNavigationElement</a></td></tr>';
		$html .= '</table>';
		echo $html;

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