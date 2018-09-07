<?php
/**
 * Schema.org Type Video
 *
 * @author  Kazuya Takami
 * @version 3.0.5
 * @since   3.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/VideoObject
 * @link    https://developers.google.com/search/docs/data-types/video
 */
class Structuring_Markup_Type_Videos {

	/**
	 * Constructor Define.
	 *
	 * @since 3.0.0
	 */
	public function __construct () {
		$this->page_render();
	}

	/**
	 * Form Layout Render
	 *
	 * @version 3.0.5
	 * @since   3.0.0
	 */
	private function page_render () {
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Setting</caption>';
		$html .= '<tr><th class="require">name :</th><td><small>Post Title</small></td></tr>';
		$html .= '<tr><th class="require">Description :</th><td><small>Post Description</small></td></tr>';
		$html .= '<tr><th class="require">thumbnailUrl :</th><td><small>Featured Image URL</small></td></tr>';
		$html .= '<tr><th class="require">uploadDate :</th><td><small>Update Date</small></td></tr>';
		$html .= '<tr><th>duration :</th><td><small>Input a custom post: field name "schema_video_duration"</small></td></tr>';
		$html .= '<tr><th>contentUrl :</th><td><small>Input a custom post: field name "schema_video_content_url"</small></td></tr>';
		$html .= '<tr><th>embedUrl :</th><td><small>Input a custom post: field name "schema_video_embed_url"</small></td></tr>';
		$html .= '<tr><th>interactionCount :</th><td><small>Input a custom post: field name "schema_video_interaction_count"</small></td></tr>';
		$html .= '<tr><th>expires :</th><td><small>Input a custom post: field name "schema_video_expires_date" & "schema_video_expires_time"</small></td></tr>';
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>Custom post name :</th>';
		$html .= '<td>schema_video_post</td></tr>';
		$html .= '<tr><th>Archive rewrite name :</th>';
		$html .= '<td>videos</td></tr>';
		$html .= '<tr><th>schema.org VideoObject :</th>';
		$html .= '<td><a href="https://schema.org/VideoObject" target="_blank">https://schema.org/VideoObject</a></td></tr>';
		$html .= '<tr><th>Google Search Video :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/video" target="_blank">https://developers.google.com/search/docs/data-types/video</a></td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}
}