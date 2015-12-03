<?php
/**
 * Schema.org Type News Article
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/NewsArticle
 * @link    https://developers.google.com/structured-data/rich-snippets/articles
 */
class Structuring_Markup_Type_NewsArticle {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct () {
		$this->page_render();
	}

	/**
	 * Form Layout Render
	 *
	 * @since 1.0.0
	 */
	private function page_render () {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting( Post Page Only )</caption>';
		$html .= '<tr><th>headline :</th><td><small>Default : post_title</small></td></tr>';
		$html .= '<tr><th>datePublished :</th><td><small>Default : get_the_time( DATE_ISO8601, ID )</small></td></tr>';
		$html .= '<tr><th>image :</th><td><small>Default : thumbnail</small></td></tr>';
		$html .= '<tr><th>description :</th><td><small>Default : post_excerpt</small></td></tr>';
		$html .= '<tr><th>articleBody :</th><td><small>Default : post_content</small></td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/rich-snippets/articles" target="_blank">https://developers.google.com/structured-data/rich-snippets/articles</a></p>';
		submit_button();
	}
}