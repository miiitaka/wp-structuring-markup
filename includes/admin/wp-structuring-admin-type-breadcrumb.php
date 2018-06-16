<?php
/**
 * Schema.org Type Breadcrumb
 *
 * @author  Kazuya Takami
 * @version 4.3.0
 * @since   2.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/BreadcrumbList
 * @link    https://developers.google.com/search/docs/data-types/breadcrumbs
 */
class Structuring_Markup_Type_Breadcrumb {

	/**
	 * Constructor Define.
	 *
	 * @version 4.1.1
	 * @since   2.0.0
	 * @param array $option
	 */
	public function __construct ( array $option ) {
		/** Default Value Set */
		$option_array = $this->get_default_options();

		if ( !empty( $option ) ) {
			$option_array = array_merge( $option_array, $option );
		}

		$this->page_render( $option_array );
	}

	/**
	 * Form Layout Render
	 *
	 * @version 4.3.0
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
		$html .= '<small>Installed the HOME to breadcrumbs.</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="home_name">Home Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "home_name" . ']" id="home_name" class="regular-text" value="' . esc_attr( $option['home_name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")<br>* In the case of the pattern set for the static page on the front page its title is the default value.</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="current_on">Display Current Page :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "current_on" . ']" id="current_on" value="on"';
		if ( isset( $option['current_on'] ) &&  $option['current_on'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled';
		$html .= '<small>Installed the Current Page to breadcrumbs.</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="current_link">Current Page Link :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "current_link" . ']" id="current_link" value="on"';
		if ( isset( $option['current_link'] ) &&  $option['current_link'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled';
		$html .= '<small>Link setting of the current page of breadcrumbs.</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Short Code</caption>';
		$html .= '<tr><th><label for="home_on">Short Code Copy :</label></th><td>';
		$html .= '<input type="text" onfocus="this.select();" readonly="readonly" value="[wp-structuring-markup-breadcrumb]" class="large-text code">';
		$html .= '<small>Option : id="id_name" and class="class_name" attribute additional ol element.</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/search/docs/data-types/breadcrumbs" target="_blank">https://developers.google.com/search/docs/data-types/breadcrumbs</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @version 4.3.0
	 * @since   2.0.0
	 * @return  array $args
	 */
	private function get_default_options () {
		$args['home_on']      = '';
		$args['home_name']    = '';
		$args['current_on']   = '';
		$args['current_link'] = '';

		return (array) $args;
	}
}