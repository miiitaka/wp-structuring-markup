<?php
/**
 * Utility
 *
 * @author  Kazuya Takami
 * @version 4.1.0
 * @since   4.0.0
 */
class Structuring_Markup_Utility {

	/**
	 * Escape Text
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   string $text
	 * @return  string $text
	 */
	public function escape_text ( $text ) {
		$text = strip_tags( $text );
		$text = strip_shortcodes( $text );
		$text = str_replace( array( "\r", "\n" ), '', $text );

		return (string) $text;
	}

	/**
	 * Return image dimensions
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @author  Kazuya Takami
	 * @param   string $url
	 * @return  array | boolean $dimensions
	 */
	public function get_image_dimensions ( $url ) {
		$image = wp_get_image_editor( $url );

		if ( ! is_wp_error( $image ) ) {
			return $image->get_size();
		} else {
			return __return_false();
		}
	}

	/**
	 * Return image dimensions
	 *
	 * @version 4.1.0
	 * @since   4.1.0
	 * @author  Kazuya Takami
	 * @param   string $content
	 * @return  array | boolean $images
	 */
	public function get_content_image ( $content ) {
		$pattern = '/<img.*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i';

		if ( preg_match( $pattern, $content, $images ) ){
			if ( is_array( $images ) && isset( $images[1] ) ) {
				return $images[1];
			} else {
				return __return_false();
			}
		} else {
			return __return_false();
		}
	}
}