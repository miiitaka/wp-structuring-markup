<?php
/**
 * Schema.org Type Person
 *
 * @author  Kazuya Takami
 * @version 4.0.0
 * @since   4.0.0
 * @link    https://schema.org/Person
 * @link    https://developers.google.com/search/docs/data-types/social-profile-links
 */
class Structuring_Markup_Meta_Person {

	/**
	 * Setting schema.org Person
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		/** Logos */
		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "Person",
			"name"     => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"url"      => isset( $options['url'] )  ? esc_url( $options['url'] )   : ""
		);

		/** Place */
		if ( isset( $options['addressCountry'] ) ) {
			$place["homeLocation"] = array(
				"@type"   => "Place",
				"address" => array(
					"@type"          => "PostalAddress",
					"addressCountry" => $options['addressCountry']
				)
			);
			$args = array_merge( $args, $place );
		}

		/** Social Profiles */
		if ( isset( $options['social'] ) ) {
			$socials["sameAs"] = array();

			foreach ( $options['social'] as $value ) {
				if ( !empty( $value ) ) {
					$socials["sameAs"][] = esc_html( $value );
				}
			}
			if ( count( $socials["sameAs"] ) > 0 ) {
				$args = array_merge( $args, $socials );
			}
		}
		return (array) $args;
	}
}