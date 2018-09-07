<?php
/**
 * Schema.org Type Event
 *
 * @author  Kazuya Takami
 * @version 4.5.3
 * @since   2.1.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/Event
 * @link    http://schema.org/Place
 * @link    http://schema.org/Offer
 * @link    https://developers.google.com/search/docs/data-types/events
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
	 * @version 4.5.3
	 * @since   2.1.0
	 */
	private function page_render () {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require">type :</th><td><small>Select a event type: field name "schema_event_type"</small></td></tr>';
		$html .= '<tr><th class="require">name :</th><td><small>Input a custom post: field name "schema_event_name"</small></td></tr>';
		$html .= '<tr><th class="require">description :</th><td><small>Input a custom post: field name "schema_event_description"</small></td></tr>';
		$html .= '<tr><th class="require">image :</th><td><small>Input a custom post: field name "schema_event_image"</small></td></tr>';
		$html .= '<tr><th class="require">startDate :</th><td><small>Input a custom post: field name "schema_event_date" & "schema_event_time"</small></td></tr>';
		$html .= '<tr><th class="require">endtDate :</th><td><small>Input a custom post: field name "schema_event_date_end" & "schema_event_time_end"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Input a custom post: field name "schema_event_url"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Location Type "Place" Setting</caption>';
		$html .= '<tr><th class="require">name :</th><td><small>Input a custom post: field name "schema_event_place_name"</small></td></tr>';
		$html .= '<tr><th>url(sameAs) :</th><td><small>Input a custom post: field name "schema_event_url"</small></td></tr>';
		$html .= '<tr><th class="require">address :</th><td><small>Input a custom post: field name "schema_event_place_address"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Location Type "Offers" Setting</caption>';
		$html .= '<tr><th>price :</th><td><small>Input a custom post: field name "schema_event_offers_price"</small></td></tr>';
		$html .= '<tr><th class="require">priceCurrency :</th><td><small>Input a custom post: field name "schema_event_offers_currency"</small></td></tr>';
		$html .= '<tr><th>url :</th><td><small>Input a custom post: field name "schema_event_place_url"</small></td></tr>';
		$html .= '<tr><th>validFrom :</th><td><small>Input a custom post: field name "schema_event_offers_date" & "schema_event_offers_time"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Location Type "PerformingGroup" Setting</caption>';
		$html .= '<tr><th>name :</th><td><small>Input a custom post: field name "schema_event_performer_name"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>Custom post name :</th>';
		$html .= '<td>schema_event_post</td></tr>';
		$html .= '<tr><th>Archive rewrite name :</th>';
		$html .= '<td>events</td></tr>';
		$html .= '<tr><th>schema.org Event :</th>';
		$html .= '<td><a href="http://schema.org/Event" target="_blank">http://schema.org/Event</a></td></tr>';
		$html .= '<tr><th>schema.org Place :</th>';
		$html .= '<td><a href="http://schema.org/Place" target="_blank">http://schema.org/Place</a></td></tr>';
		$html .= '<tr><th>schema.org Offer :</th>';
		$html .= '<td><a href="http://schema.org/Offer" target="_blank">http://schema.org/Offer</a></td></tr>';
		$html .= '<tr><th>Google Search Breadcrumb :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/events" target="_blank">https://developers.google.com/search/docs/data-types/events</a></td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}
}