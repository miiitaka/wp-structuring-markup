<?php
/**
 * Schema.org Type BlogPosting
 *
 * @author  Kazuya Takami
 * @version 4.8.1
 * @since   4.0.0
 * @link    https://schema.org/BlogPosting
 * @link    https://developers.google.com/search/docs/data-types/articles
 * @link    https://developers.google.com/search/docs/data-types/speakable
 */
class Structuring_Markup_Meta_Blog_Posting {

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
	 * @version 4.5.0
	 * @since   4.0.0
	 * @param   Structuring_Markup_Utility $utility
	 */
	public function __construct ( Structuring_Markup_Utility $utility ) {
		$this->utility = $utility;
	}

	/**
	 * Setting schema.org BlogPosting
	 *
	 * @version 4.8.1
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		global $post;

		$excerpt = $this->utility->escape_text( $post->post_excerpt );
		$content = $excerpt === "" ? mb_substr( $this->utility->escape_text( $post->post_content ), 0, 110 ) : $excerpt;

		$args = array(
			"@context" => "https://schema.org",
			"@type"    => "BlogPosting",
			"mainEntityOfPage" => array(
				"@type" => "WebPage",
				"@id"   => get_permalink( $post->ID )
			),
			"headline"      => mb_substr( $this->utility->escape_text( $post->post_title ), 0, 110 ),
			"datePublished" => get_the_time( DATE_ISO8601, $post->ID ), "dateModified"  => get_the_modified_time( DATE_ISO8601, $post->ID ),
			"author" => array(
				"@type" => "Person",
				"name"  => esc_html( get_the_author_meta( 'display_name', $post->post_author ) )
			),
			"description" => $content
		);

		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$images_args = array(
					"image" => $this->set_image_object( $images[0], $images[1], $images[2] )
			);
			$args = array_merge( $args, $images_args );
		} elseif ( isset( $options['content_image'] ) &&  $options['content_image'] === 'on' ) {
			if ( $images = $this->utility->get_content_image( $post->post_content ) ) {
				if ( $size = $this->utility->get_image_dimensions( $images ) ) {
					$images_args = array(
							"image" => $this->set_image_object( $images, $size['width'], $size['height'] )
					);
					$args = array_merge( $args, $images_args );
				}
			} elseif ( isset( $options['default_image'] ) ) {
				if ($size = $this->utility->get_image_dimensions( $options['default_image'] ) ) {
					$images_args = array(
							"image" => $this->set_image_object( esc_html( $options['default_image'] ), $size['width'], $size['height'] )
					);
					$args = array_merge( $args, $images_args );
				}
			}
		} elseif ( isset( $options['default_image'] ) ) {
			if ( $size = $this->utility->get_image_dimensions( $options['default_image'] ) ) {
				$images_args = array(
						"image" => $this->set_image_object( esc_html( $options['default_image'] ), $size['width'], $size['height'] )
				);
				$args = array_merge( $args, $images_args );
			}
		}

		if ( isset( $options['name'] ) && !empty( $options['name'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => esc_html( $options['name'] ),
				)
			);

			$options['logo'] = isset( $options['logo'] ) ? esc_html( $options['logo'] ) : "";

			if ( $logo = $this->utility->get_image_dimensions( $options['logo'] ) ) {
				$publisher_args['publisher']['logo'] = $this->set_image_object( $options['logo'], $logo['width'], $logo['height'] );
			} else if ( !empty( $options['logo'] ) ) {
				$publisher_args['publisher']['logo'] = $this->set_image_object(
						$options['logo'],
						isset( $options['logo-width'] )  ? ( int ) $options['logo-width']  : 0,
						isset( $options['logo-height'] ) ? ( int ) $options['logo-height'] : 0
				);
			}
			$args = array_merge( $args, $publisher_args );
		}

		if ( isset( $options['speakable_action'] ) && $options['speakable_action'] === 'on' ) {
			$speakable_type = isset( $options['speakable_type'] ) ? $options['speakable_type'] : '';

			if ( !empty( $speakable_type ) ) {
				$action_array = array(
					"@type" => "SpeakableSpecification"
				);
				$action_array[$speakable_type] = array(
					isset( $options['speakable_headline'] ) ? stripslashes( $options['speakable_headline'] ) : '',
					isset( $options['speakable_summary'] )  ? stripslashes( $options['speakable_summary'] )  : ''
				);
				$args['speakable'] = $action_array;
			}
		}

		return ( array ) $args;
	}

	/**
	 * Setting ImageObject
	 *
	 * @version 4.7.0
	 * @since   4.7.0
	 * @param   string  $url
	 * @param   integer $width
	 * @param   integer $height
	 * @return  array $args
	 */
	public function set_image_object ( $url, $width, $height ) {
		$args = array(
				"@type"  => "ImageObject",
				"url"    => $url,
				"width"  => $width,
				"height" => $height
		);

		return ( array ) $args;
	}
}