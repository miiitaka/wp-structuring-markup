<?php
/**
 * Schema.org Type Organization
 *
 * @author  Kazuya Takami
 * @version 4.6.0
 * @since   4.0.0
 * @link    https://schema.org/Organization
 * @link    https://developers.google.com/search/docs/guides/enhance-site
 */
class Structuring_Markup_Meta_Organization {

	/**
	 * Setting schema.org Organization
	 *
	 * @version 4.6.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		/** Logos */
		$args = array(
			'@context' => 'http://schema.org',
			'@type'    => isset( $options['organization_type'] ) ? esc_html( $options['organization_type'] ) : 'Organization',
			'name'     => isset( $options['name'] )              ? esc_html( $options['name'] ) : '',
			'url'      => isset( $options['url'] )               ? esc_url( $options['url'] ) : '',
			'logo'     => isset( $options['logo'] )              ? esc_url( $options['logo'] ) : ''
		);

		/** Corporate Contact */
		if ( isset( $options['contact_point'] ) && $options['contact_point'] === 'on' ) {
			$contact_point_data = array(
				'@type'       => 'ContactPoint',
				'telephone'   => isset( $options['telephone'] )    ? esc_html( $options['telephone'] ) : '',
				'contactType' => isset( $options['contact_type'] ) ? esc_html( $options['contact_type'] ) : ''
			);

			if ( !empty( $options['email'] ) ) {
				$contact_point_data['email'] = isset( $options['email'] ) ? esc_html( $options['email'] ) : '';
			}
			if ( !empty( $options['area_served'] ) ) {
				$array = explode( ',', esc_html(  $options['area_served'] ) );
				for ( $i = 0; $i < count( $array ); $i++ ) {
					$contact_point_data['areaServed'][] = isset( $options['area_served'] ) ? $array[$i] : '';
				}
			}
			if ( isset( $options['contact_point_1'] ) &&  $options['contact_point_1'] === 'on' ) {
				$contact_point_data['contactOption'][] = 'HearingImpairedSupported';
			}
			if ( isset( $options['contact_point_2'] ) &&  $options['contact_point_2'] === 'on' ) {
				$contact_point_data['contactOption'][] = 'TollFree';
			}
			if ( !empty( $options['available_language'] ) ) {
				$array = explode( ',', esc_html( $options['available_language'] ) );
				for ( $i = 0; $i < count( $array ); $i++ ) {
					$contact_point_data['availableLanguage'][] = isset( $options['available_language'] ) ? $array[$i] : '';
				}
			}

			$contact_point['contactPoint'] = array( $contact_point_data	);
			$args = array_merge( $args, $contact_point );
		}

		/** Social Profiles */
		if ( isset( $options['social'] ) ) {
			$socials['sameAs'] = array();

			foreach ( $options['social'] as $value ) {
				if ( $value ) {
					$socials['sameAs'][] = esc_url( $value );
				}
			}
			if ( count( $socials['sameAs'] ) > 0 ) {
				$args = array_merge( $args, $socials );
			}
		}
		return (array) $args;
	}
}