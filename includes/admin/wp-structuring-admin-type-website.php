<?php
/**
 * Schema.org Type WebSite
 *
 * @author  Kazuya Takami
 * @version 4.5.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/WebSite
 * @link    https://developers.google.com/search/docs/guides/enhance-site#add-a-sitelinks-searchbox-for-your-site
 * @link    https://developers.google.com/search/docs/data-types/sitename
 * @link    https://developers.google.com/search/docs/data-types/speakable
 */
class Structuring_Markup_Type_Website {

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.0
	 * @since   1.0.0
	 * @param   array $option
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
	 * @version 4.5.0
	 * @since   2.3.3
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require"><label for="name">name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="alternateName">alternateName :</label></th><td>';
		$html .= '<input type="text" name="option[' . "alternateName" . ']" id="alternateName" class="regular-text" value="' . esc_attr( $option['alternateName'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th class="require"><label for="url">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $option['url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Sitelink Search Box [ Site ]</caption>';
		$html .= '<tr><th><label for="potential_action">potentialAction Active :</label></th><td>';
		$html .= '<label><input type="checkbox" name="option[' . "potential_action" . ']" id="potential_action" value="on"';
		if ( isset( $option['potential_action'] ) &&  $option['potential_action'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled</label>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="target">target :</label></th><td>';
		$html .= '<input type="text" name="option[' . "target" . ']" id="target" class="regular-text" value="' . esc_attr( $option['target'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Sitelink Search Box [ App ] *required Sitelink Search Box [ Site ]</caption>';
		$html .= '<tr><th><label for="potential_action_app">potentialAction Active :</label></th><td>';
		$html .= '<label><input type="checkbox" name="option[' . "potential_action_app" . ']" id="potential_action_app" value="on"';
		if ( isset( $option['potential_action_app'] ) &&  $option['potential_action_app'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled</label>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="target_app">target :</label></th><td>';
		$html .= '<input type="text" name="option[' . "target_app" . ']" id="target_app" class="regular-text" value="' . esc_attr( $option['target_app'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Speakable</caption>';
		$html .= '<tr><th><label for="potential_action_speakable">speakable Active :</label></th><td>';
		$html .= '<label><input type="checkbox" name="option[' . "potential_action_speakable" . ']" id="potential_action_speakable" value="on"';
		if ( isset( $option['potential_action_speakable'] ) &&  $option['potential_action_speakable'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled</label>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="speakable_type_css">cssSelector OR xpath :</label></th><td>';

		if( $option['speakable_type'] !== 'xpath' ) {
			$checked['css']   = ' checked';
			$checked['xpath'] = '';
		} else {
			$checked['css']   = '';
			$checked['xpath'] = ' checked';
		}

		$html .= '<label><input type="radio" name="option[' . "speakable_type" . ']" id="speakable_type_css" value="css"' . $checked['css'] . '>CSS selectors&nbsp;&nbsp;</label>';
		$html .= '<label><input type="radio" name="option[' . "speakable_type" . ']" id="speakable_type_xpath" value="xpath"' . $checked['xpath'] . '>xPaths</label>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="speakable_headline">headline :</label></th><td>';
		$html .= '<input type="text" name="option[' . "speakable_headline" . ']" id="speakable_headline" class="regular-text" value="' . esc_attr( $option['speakable_headline'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="speakable_summary">summary :</label></th><td>';
		$html .= '<input type="text" name="option[' . "speakable_summary" . ']" id="speakable_summary" class="regular-text" value="' . esc_attr( $option['speakable_summary'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/search/docs/data-types/sitename" target="_blank">https://developers.google.com/search/docs/data-types/sitename</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 * @return  array $args
	 */
	private function get_default_options () {
		$args = array();

		$args['name']                 = '';
		$args['alternateName']        = '';
		$args['url']                  = '';
		$args['potential_action']     = '';
		$args['target']               = '';
		$args['potential_action_app'] = '';
		$args['target_app']           = '';
		$args['speakable_type']       = '';
		$args['speakable_headline']   = '';
		$args['speakable_summary']    = '';

		return (array) $args;
	}
}