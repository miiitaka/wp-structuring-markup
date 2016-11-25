<?php
/**
 * Schema.org Type News Article
 *
 * @author  Kazuya Takami
 * @version 3.1.4
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/NewsArticle
 * @link    https://developers.google.com/search/docs/data-types/articles
 */
class Structuring_Markup_Type_NewsArticle {

	/**
	 * Constructor Define.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
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
	 * @version 3.1.4
	 * @since   1.0.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require">headline :</th><td><small>Default : post_title</small></td></tr>';
		$html .= '<tr><th class="require">datePublished :</th><td><small>Default : get_the_time( DATE_ISO8601, ID )</small></td></tr>';
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
		$html .= '<tr><th class="require">url :</th><td><small>Default : thumbnail</small></td></tr>';
		$html .= '<tr><th class="require">height :</th><td><small>Auto : The height of the image, in pixels.</small></td></tr>';
		$html .= '<tr><th class="require">width :</th><td><small>Auto : The width of the image, in pixels. Images should be at least 696 pixels wide.</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>author</caption>';
		$html .= '<tr><th>@type :</th><td><small>"Person"</small></td></tr>';
		$html .= '<tr><th class="require">name :</th><td><small>Default : get_the_author_meta( "display_name", author )</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>publisher</caption>';
		$html .= '<tr><th>@type :</th><td><small>"Organization"</small></td></tr>';
		$html .= '<tr><th class="require"><label for="name">Organization Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>publisher.logo</caption>';
		$html .= '<tr><th>@type :</th><td><small>"ImageObject"</small></td></tr>';
		$html .= '<tr><th class="require"><label for="logo">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "logo" . ']" id="logo" class="regular-text" required value="' . esc_attr( $option['logo'] ) . '">';
		$html .= '<small>Default : bloginfo("logo") + "/images/logo.png"</small>';
		$html .= '<small>Logos should be no wider than 600px, and no taller than 60px.</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th class="require">height :</th><td><small>Auto : height <= 60px.</small></td></tr>';
		$html .= '<tr><th class="require">width :</th><td><small>Auto : width <= 600px.</small></td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/search/docs/data-types/articles" target="_blank">https://developers.google.com/search/docs/data-types/articles</a></p>';
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