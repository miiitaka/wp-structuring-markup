<?php
/**
 * Schema.org Type BlogPosting
 *
 * @author  Kazuya Takami
 * @version 4.5.3
 * @since   1.2.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/BlogPosting
 * @link    https://pending.schema.org/speakable
 * @link    https://developers.google.com/search/docs/data-types/articles
 * @link    https://developers.google.com/search/docs/data-types/speakable
 */
class Structuring_Markup_Type_Blog_Posting {

	/**
	 * Constructor Define.
	 *
	 * @version 3.2.2
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
	 * @version 4.5.3
	 * @since   1.2.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th>headline :</th><td><small>Default : post_title</small></td></tr>';
		$html .= '<tr><th>datePublished :</th><td><small>Default : get_the_time( DATE_ISO8601, ID )</small></td></tr>';
		$html .= '<tr><th>dateModified :</th><td><small>Default : get_the_modified_time( DATE_ISO8601, false, ID )</small></td></tr>';
		$html .= '<tr><th>description :</th><td><small>Default : post_excerpt</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>mainEntityOfPage</caption>';
		$html .= '<tr><th>@type :</th><td><small>"WebPage"</small></td></tr>';
		$html .= '<tr><th>@id :</th><td><small>Default : get_permalink( ID )</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>image</caption>';
		$html .= '<tr><th>@type :</th><td><small>"ImageObject"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Default : Featured Image</small></td></tr>';
		$html .= '<tr><th>height :</th><td><small>Auto : The height of the image, in pixels.</small></td></tr>';
		$html .= '<tr><th>width :</th><td><small>Auto : The width of the image, in pixels. Images should be at least 696 pixels wide.</small></td></tr>';
		$html .= '<tr><th><label for="content_image">Setting image url :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "content_image" . ']" id="content_image" value="on"';
		if ( isset( $option['content_image'] ) &&  $option['content_image'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Set the first image in the content.<br><small>Pattern without feature image set (feature image takes precedence)</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="default_image">Default image url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "default_image" . ']" id="default_image" class="regular-text" value="' . esc_attr( $option['default_image'] ) . '">';
		$html .= '<button id="media-upload-default" class="dashicons-before dashicons-admin-media schema-admin-media-button"></button><br>';
		$html .= '<small>Image output when feature image or content image check is not set.</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>author</caption>';
		$html .= '<tr><th>@type :</th><td><small>"Person"</small></td></tr>';
		$html .= '<tr><th>name :</th><td><small>Default : get_the_author_meta( "display_name", author )</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>publisher</caption>';
		$html .= '<tr><th>@type :</th><td><small>"Organization"</small></td></tr>';
		$html .= '<tr><th><label for="name">Organization Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" value="' . esc_attr( $option['name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>publisher.logo</caption>';
		$html .= '<tr><th>@type :</th><td><small>"ImageObject"</small></td></tr>';
		$html .= '<tr><th><label for="logo">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "logo" . ']" id="logo" class="regular-text" value="' . esc_attr( $option['logo'] ) . '">';
		$html .= '<button id="media-upload" class="dashicons-before dashicons-admin-media schema-admin-media-button"></button>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="logo-width">width :</label></th><td>';
		$html .= '<input type="number" name="option[' . "logo-width" . ']" id="logo-width" min="0" value="' . esc_attr( $option['logo-width'] ) . '" placeholder="width <= 600px.">px';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="logo-height">height :</label></th><td>';
		$html .= '<input type="number" name="option[' . "logo-height" . ']" id="logo-height" min="0" value="' . esc_attr( $option['logo-height'] ) . '" placeholder="height <= 60px.">px';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Speakable</caption>';
		$html .= '<tr><th><label for="speakable_action">speakable Active :</label></th><td>';
		$html .= '<label><input type="checkbox" name="option[' . "speakable_action" . ']" id="speakable_action" value="on"';

		if ( isset( $option['speakable_action'] ) &&  $option['speakable_action'] === 'on' ) {
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

		$html .= '<label><input type="radio" name="option[' . "speakable_type" . ']" id="speakable_type_css" value="cssSelector"' . $checked['css'] . '>CSS selectors&nbsp;&nbsp;</label>';
		$html .= '<label><input type="radio" name="option[' . "speakable_type" . ']" id="speakable_type_xpath" value="xpath"' . $checked['xpath'] . '>xPaths</label>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="speakable_headline">headline :</label></th><td>';
		$html .= '<input type="text" name="option[' . "speakable_headline" . ']" id="speakable_headline" class="regular-text" value="' . esc_attr( stripslashes( $option['speakable_headline'] ) ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="speakable_summary">summary :</label></th><td>';
		$html .= '<input type="text" name="option[' . "speakable_summary" . ']" id="speakable_summary" class="regular-text" value="' . esc_attr( stripslashes( $option['speakable_summary'] ) ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>schema.org BlogPosting :</th>';
		$html .= '<td><a href="http://schema.org/BlogPosting" target="_blank">http://schema.org/BlogPosting</a></td></tr>';
		$html .= '<tr><th>pending.schema.org Speakable :</th>';
		$html .= '<td><a href="https://pending.schema.org/speakable" target="_blank">https://pending.schema.org/speakable</a></td></tr>';
		$html .= '<tr><th>Google Search Article :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/articles" target="_blank">https://developers.google.com/search/docs/data-types/articles</a></td></tr>';
		$html .= '<tr><th>Google Search Speakable (BETA) :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/speakable" target="_blank">https://developers.google.com/search/docs/data-types/speakable</a></td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @version 4.5.3
	 * @since   2.2.0
	 * @return  array $args
	 */
	private function get_default_options () {
		$args['name']               = '';
		$args['content_image']      = '';
		$args['default_image']      = '';
		$args['logo']               = '';
		$args['logo-height']        = 0;
		$args['logo-width']         = 0;
		$args['speakable_action']   = '';
		$args['speakable_type']     = '';
		$args['speakable_headline'] = '';
		$args['speakable_summary']  = '';

		return (array) $args;
	}
}