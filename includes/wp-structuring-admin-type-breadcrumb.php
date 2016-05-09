<?php
/**
 * Schema.org Type Breadcrumb
 *
 * @author  Kazuya Takami
 * @version 2.5.1
 * @since   2.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/BreadcrumbList
 * @link    https://developers.google.com/structured-data/breadcrumbs
 */
class Structuring_Markup_Type_Breadcrumb {

	/**
	 * Constructor Define.
	 *
	 * @since 2.0.0
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
	 * @version 2.5.1
	 * @since   2.3.3
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th><label for="home_on">Display Home Page :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "home_on" . ']" id="home_on" value="on"';
		if ( isset( $option['home_on'] ) &&  $option['home_on'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled';
		$html .= '<small>( Installed the HOME to breadcrumbs )</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="home_name">Home Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "home_name" . ']" id="home_name" class="regular-text" value="' . esc_attr( $option['home_name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/slsb-overview" target="_blank">https://developers.google.com/structured-data/slsb-overview</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['home_on']   = '';
		$args['home_name'] = '';

		return (array) $args;
	}
}