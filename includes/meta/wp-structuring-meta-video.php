<?php
/**
 * Schema.org Type Video
 *
 * @author  Kazuya Takami
 * @version 4.0.0
 * @since   4.0.0
 * @link    https://schema.org/VideoObject
 * @link    https://developers.google.com/search/docs/data-types/videos
 */
class Structuring_Markup_Meta_Video {

	/**
	 * Utility
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 */
	private $utility;

	/**
	 * Constructor Define.
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   Structuring_Markup_Utility $utility
	 */
	public function __construct ( Structuring_Markup_Utility $utility ) {
		$this->utility = $utility;
	}

	/**
	 * Setting schema.org Video
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @return  array $args
	 */
	public function set_meta () {
		global $post;
		$meta = get_post_meta( $post->ID, 'schema_video_post', false );

		if ( isset( $meta[0] ) ) {
			$meta = unserialize( $meta[0] );

			if ( has_post_thumbnail( $post->ID ) ) {
				$images  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				$excerpt = $this->utility->escape_text( $post->post_excerpt );
				$content = $excerpt === "" ? mb_substr( $this->utility->escape_text( $post->post_content ), 0, 110 ) : $excerpt;
			} else {
				$images[0] = '';
				$content   = '';
			}

			/* required items */
			if ( !isset( $meta['schema_video_name']) )           $meta['schema_video_name']        = esc_html( $post->post_title );
			if ( !isset( $meta['schema_video_description'] ) )   $meta['schema_video_description'] = esc_html( $content );
			if ( !isset( $meta['schema_video_thumbnail_url'] ) ) $meta['schema_video_description'] = esc_html( $images[0] );
			if ( !isset( $meta['schema_video_upload_date'] ) )   $meta['schema_video_upload_date'] = get_post_modified_time( 'Y-m-d', __return_false(), $post->ID );
			if ( !isset( $meta['schema_video_upload_time'] ) )   $meta['schema_video_upload_time'] = get_post_modified_time( 'H:i:s', __return_false(), $post->ID );

			$args = array(
				"@context"     => "http://schema.org",
				"@type"        => "VideoObject",
				"name"         => esc_html( $meta['schema_video_name'] ),
				"description"  => esc_html( $meta['schema_video_description'] ),
				"thumbnailUrl" => esc_html( $meta['schema_video_thumbnail_url'] ),
				"uploadDate"   => esc_html( $meta['schema_video_upload_date'] ) . 'T' . esc_html( $meta['schema_video_upload_time'] )
			);

			/* recommended items */
			if ( isset( $meta['schema_video_duration'] ) && $meta['schema_video_duration'] !== '' ) {
				$args["duration"] = esc_html( $meta['schema_video_duration'] );
			}
			if ( isset( $meta['schema_video_content_url'] ) && $meta['schema_video_content_url'] !== '' ) {
				$args["contentUrl"] = esc_url( $meta['schema_video_content_url'] );
			}
			if ( isset( $meta['schema_video_embed_url'] ) && $meta['schema_video_embed_url'] !== '' ) {
				$args["embedUrl"] = esc_url( $meta['schema_video_embed_url'] );
			}
			if ( isset( $meta['schema_video_interaction_count'] ) && $meta['schema_video_interaction_count'] !== '' ) {
				$args["interactionCount"] = esc_html( $meta['schema_video_interaction_count'] );
			}
			if ( isset( $meta['schema_video_expires_date'] ) && $meta['schema_video_expires_date'] !== '' && isset( $meta['schema_video_expires_time'] ) && $meta['schema_video_expires_time'] !== '' ) {
				$args["expires"] = esc_html( $meta['schema_video_expires_date'] ) . 'T' . esc_html( $meta['schema_video_expires_time'] );
			}
			return (array) $args;
		}
	}
}