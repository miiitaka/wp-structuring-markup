<?php
/**
 * Schema.org Type Event
 *
 * @author  Kazuya Takami
 * @version 2.1.3
 * @since   2.1.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/Event
 * @link    http://schema.org/Place
 * @link    http://schema.org/Offer
 * @link    https://developers.google.com/structured-data/events/
 * @link    https://developers.google.com/structured-data/rich-snippets/events
 */
class Structuring_Markup_Type_Event {

	/**
	 * Constructor Define.
	 *
	 * @since 2.1.0
	 */
	public function __construct () {
		$this->page_render();
	}

	/**
	 * Form Layout Render
	 *
	 * @since 2.1.0
	 */
	private function page_render () {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th>name :</th><td><small>Input a custom post: field name "schema_event_name"</small></td></tr>';
		$html .= '<tr><th>startDate :</th><td><small>Input a custom post: field name "schema_event_date" & "schema_event_time"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Input a custom post: field name "schema_event_url"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Location Type "Place" Setting</caption>';
		$html .= '<tr><th>name :</th><td><small>Input a custom post: field name "schema_event_place_name"</small></td></tr>';
		$html .= '<tr><th>url(sameAs) :</th><td><small>Input a custom post: field name "schema_event_place_url"</small></td></tr>';
		$html .= '<tr><th>address :</th><td><small>Input a custom post: field name "schema_event_place_address"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Location Type "Offers" Setting</caption>';
		$html .= '<tr><th>price :</th><td><small>Input a custom post: field name "schema_event_place_name"</small></td></tr>';
		$html .= '<tr><th>priceCurrency :</th><td><small>Input a custom post: field name "schema_event_place_address"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Input a custom post: field name "schema_event_place_url"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Custom post name "schema_event_post"</p>';
		echo '<p>Archive rewrite name "events"</p>';
		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/rich-snippets/events" target="_blank">https://developers.google.com/structured-data/rich-snippets/events</a></p>';

		submit_button();
	}
}