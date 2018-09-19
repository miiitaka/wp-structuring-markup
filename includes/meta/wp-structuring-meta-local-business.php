<?php
/**
 * Schema.org Type LocalBusiness
 *
 * @author  Kazuya Takami
 * @version 4.6.0
 * @since   4.0.0
 * @see     wp-structuring-opening-hours.php
 * @link    http://schema.org/LocalBusiness
 * @link    https://schema.org/GeoCircle
 * @link    https://developers.google.com/search/docs/data-types/local-businesses
 */
class Structuring_Markup_Meta_LocalBusiness {

	/**
	 * Setting schema.org LocalBusiness
	 *
	 * @version 4.6.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {

		/** weekType defined. */
		$week_array = array(
			array( 'type' => 'Mo', 'display' => 'Monday' ),
			array( 'type' => 'Tu', 'display' => 'Tuesday' ),
			array( 'type' => 'We', 'display' => 'Wednesday' ),
			array( 'type' => 'Th', 'display' => 'Thursday' ),
			array( 'type' => 'Fr', 'display' => 'Friday' ),
			array( 'type' => 'Sa', 'display' => 'Saturday' ),
			array( 'type' => 'Su', 'display' => 'Sunday' )
		);

		$args = array(
			'@context'  => 'http://schema.org',
			'@type'     => isset( $options['business_type'] ) ? esc_html( $options['business_type'] ) : 'LocalBusiness',
			'name'      => isset( $options['name'] )          ? esc_html( $options['name'] ) : '',
			'image'     => isset( $options['image'] )         ? esc_html( $options['image'] ) : '',
			'url'       => isset( $options['url'] )           ? esc_url( $options['url'] ) : ''
		);

		if ( isset( $options['telephone'] ) && !empty( $options['telephone'] ) ) {
			$args['telephone'] = esc_html( $options['telephone'] );
		}

		if ( isset( $options['food_active'] ) && $options['food_active'] === 'on' ) {
			if ( isset( $options['menu'] ) && $options['menu'] !== '' ) {
				$args['menu'] = esc_url( $options['menu'] );
			}
			if ( isset( $options['accepts_reservations'] ) && $options['accepts_reservations'] === 'on' ) {
				$args['acceptsReservations'] = 'True';
			} else {
				$args['acceptsReservations'] = 'False';
			}
			if ( isset( $options['serves_cuisine'] ) && $options['serves_cuisine'] !== '' ) {
				$args['servesCuisine'] = esc_html( $options['serves_cuisine'] );
			}
		}

		$address_array['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => isset( $options['street_address'] )   ? esc_html( $options['street_address'] ) : '',
			'addressLocality' => isset( $options['address_locality'] ) ? esc_html( $options['address_locality'] ) : '',
			'postalCode'      => isset( $options['postal_code'] )      ? esc_html( $options['postal_code'] ) : '',
			'addressCountry'  => isset( $options['address_country'] )  ? esc_html( $options['address_country'] ) : ''
		);

		if ( isset( $options['address_region'] ) && !empty( $options['address_region'] ) ) {
			$address_array['address']['addressRegion'] = esc_html( $options['address_region'] );
		}

		$args      = array_merge( $args, $address_array );
		$geo_array = array();

		if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
			$geo_array['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => isset( $options['latitude'] )  ? esc_html(floatval($options['latitude'])) : '',
				'longitude' => isset( $options['longitude'] ) ? esc_html(floatval($options['longitude'])) : ''
			);
		}

		if ( isset( $options['geo_circle_active'] ) && $options['geo_circle_active'] === 'on' ) {
			$place_array['location']        = array( '@type' => 'Place' );
			$place_array['location']['geo'] = array(
				'@type'     => 'GeoCircle',
				'geoRadius' => isset( $options['geo_circle_radius'] )  ? esc_html( floatval( $options['geo_circle_radius'] ) ) : ''
			);
			if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
				$place_array['location']['geo']['geoMidpoint'] = $geo_array['geo'];
			}
			$args = array_merge( $args, $place_array );
		} else {
			if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
				$args = array_merge( $args, $geo_array );
			}
		}

		/* openingHours */
		$active_days = array();
		foreach ( $week_array as $value ) {
			if ( isset( $options[$value['type']] ) && $options[$value['type']] === 'on' ) {
				$active_days[$value['type']] = $options['week'][$value['type']];
			}
		}

		if( !empty( $active_days ) ) {

			$obj = new Structuring_Markup_Opening_Hours( $active_days );
			$opening_hours = $obj->display();

			$opening_array['openingHours'] = array();

			foreach( $opening_hours as $value ) {
				$opening_array['openingHours'][] = $value;
			}

			$args = array_merge( $args, $opening_array );

		}

		if ( isset( $options['holiday_active'] ) && $options['holiday_active'] === 'on' ) {
			$holiday_array['openingHoursSpecification'] = array(
				'@type'        => 'OpeningHoursSpecification',
				'opens'        => isset( $options['holiday_open'] )          ? esc_html( $options['holiday_open'] ) : '',
				'closes'       => isset( $options['holiday_close'] )         ? esc_html( $options['holiday_close'] ) : '',
				'validFrom'    => isset( $options['holiday_valid_from'] )    ? esc_html( $options['holiday_valid_from'] ) : '',
				'validThrough' => isset( $options['holiday_valid_through'] ) ? esc_html( $options['holiday_valid_through'] ) : ''
			);
			$args = array_merge( $args, $holiday_array );
		}

		if ( isset( $options['price_range'] ) && !empty( $options['price_range'] ) ) {
			$price_array['priceRange'] = $options['price_range'];
			$args = array_merge( $args, $price_array );
		}

		return (array) $args;
	}

}