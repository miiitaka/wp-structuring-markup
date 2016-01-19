<?php
/**
 * Schema.org Type News Article
 *
 * @author  Kazuya Takami
 * @version 2.3.3
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/NewsArticle
 * @link    https://developers.google.com/structured-data/rich-snippets/articles
 */
class Structuring_Markup_Type_NewsArticle {

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.2.0
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
	 * @since   1.0.0
	 * @version 2.3.3
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
		$html .= '<caption>mainEntityOfPage ( recommended )</caption>';
		$html .= '<tr><th>@type :</th><td><small>"WebPage"</small></td></tr>';
		$html .= '<tr><th>@id :</th><td><small>Default : get_permalink( ID )</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>image ( required )</caption>';
		$html .= '<tr><th>@type :</th><td><small>"ImageObject"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Default : thumbnail</small></td></tr>';
		$html .= '<tr><th>height :</th><td><small>Auto : The height of the image, in pixels.</small></td></tr>';
		$html .= '<tr><th>width :</th><td><small>Auto : The width of the image, in pixels. Images should be at least 696 pixels wide.</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>publisher ( required )</caption>';
		$html .= '<tr><th>@type :</th><td><small>"Organization"</small></td></tr>';
		$html .= '<tr><th><label for="name">Organization Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>logo ( required )</caption>';
		$html .= '<tr><th>@type :</th><td><small>"ImageObject"</small></td></tr>';
		$html .= '<tr><th><label for="logo">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "logo" . ']" id="logo" class="regular-text" required value="' . esc_attr( $option['logo'] ) . '">';
		$html .= '<small>Default : bloginfo("logo") + "/images/logo.png"</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th>height :</th><td><small>Auto : height <= 60px.</small></td></tr>';
		$html .= '<tr><th>width :</th><td><small>Auto : width <= 600px.</small></td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/rich-snippets/articles" target="_blank">https://developers.google.com/structured-data/rich-snippets/articles</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since   2.2.0
	 * @version 2.2.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['name'] = get_bloginfo('name');
		$args['logo'] = get_bloginfo('url') . '/images/logo.png';

		return (array) $args;
	}
}