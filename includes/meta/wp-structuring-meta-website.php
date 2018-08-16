<?php
/**
 * Schema.org Type WebSite
 *
 * @author  Kazuya Takami
 * @version 4.5.0
 * @since   4.0.0
 * @link    https://schema.org/WebSite
 * @link    https://developers.google.com/search/docs/guides/enhance-site#add-a-sitelinks-searchbox-for-your-site
 * @link    https://developers.google.com/search/docs/data-types/sitename
 */
class Structuring_Markup_Meta_WebSite {

	/**
	 * Setting schema.org WebSite
	 *
	 * @version 4.5.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		$args = array(
			"@context"      => "http://schema.org",
			"@type"         => "WebSite",
			"name"          => isset( $options['name'] ) ? esc_html( $options['name'] ) : '',
			"alternateName" => isset( $options['alternateName'] ) ? esc_html( $options['alternateName'] ) : '',
			"url"           => isset( $options['url'] ) ? esc_url( $options['url'] ) : ''
		);

		$search_array = array();

		if ( isset( $options['potential_action'] ) && $options['potential_action'] === 'on' ) {
			$action_array = array(
				"@type"       => "SearchAction",
				"target"      => isset( $options['target'] ) ? esc_url( $options['target'] ) . "{search_term_string}" : '',
				"query-input" => isset( $options['target'] ) ? "required name=search_term_string" : ''
			);
			$search_array[] = $action_array;
		}

		if ( count( $search_array ) > 0 ) {
			if ( isset( $options['potential_action_app'] ) && $options['potential_action_app'] === 'on' ) {
				$action_array = array(
					"@type"       => "SearchAction",
					"target"      => isset( $options['target_app'] ) ? $options['target_app'] . "{search_term_string}" : '',
					"query-input" => isset( $options['target_app'] ) ? "required name=search_term_string" : ''
				);
				$search_array[] = $action_array;
			}

			$potential_action["potentialAction"] = $search_array;
			$args = array_merge( $args, $potential_action );
		}

		return (array) $args;
	}
}