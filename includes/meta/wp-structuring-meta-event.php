<?php
/**
 * Schema.org Type Event
 *
 * @author  Kazuya Takami
 * @version 4.0.2
 * @since   4.0.0
 * @link    http://schema.org/Event
 * @link    http://schema.org/Place
 * @link    http://schema.org/Offer
 * @link    https://developers.google.com/search/docs/data-types/events
 */
class Structuring_Markup_Meta_Event {

	/**
	 * Setting schema.org Event
	 *
	 * @version 4.0.2
	 * @since   4.0.0
	 * @return  array $args
	 */
	public function set_meta () {
		global $post;
		$meta = get_post_meta( $post->ID, 'schema_event_post', false );

		if ( isset( $meta[0] ) ) {
			$meta = unserialize( $meta[0] );

			// @type: Event
			if ( !isset( $meta['schema_event_type']) )             $meta['schema_event_type']            = 'Event';
			if ( !isset( $meta['schema_event_name']) )             $meta['schema_event_name']            = '';
			if ( !isset( $meta['schema_event_date']) )             $meta['schema_event_date']            = date('Y-m-d');
			if ( !isset( $meta['schema_event_time']) )             $meta['schema_event_time']            = date('h:i');
			if ( !isset( $meta['schema_event_url']) )              $meta['schema_event_url']             = '';
			if ( !isset( $meta['schema_event_place_name'] ) )      $meta['schema_event_place_name']      = '';
			if ( !isset( $meta['schema_event_place_url'] ) )       $meta['schema_event_place_url']       = '';
			if ( !isset( $meta['schema_event_place_address'] ) )   $meta['schema_event_place_address']   = '';
			if ( !isset( $meta['schema_event_offers_price'] ) )    $meta['schema_event_offers_price']    = 0;
			if ( !isset( $meta['schema_event_offers_currency'] ) ) $meta['schema_event_offers_currency'] = '';

			$args = array(
				"@context"  => "http://schema.org",
				"@type"     => esc_html( $meta['schema_event_type'] ),
				"name"      => esc_html( $meta['schema_event_name'] ),
				"startDate" => esc_html( $meta['schema_event_date'] ) . 'T' . esc_html( $meta['schema_event_time'] ),
				"url"       => esc_url( $meta['schema_event_url'] ),
				"location"  => array(
					"@type"   => "Place",
					"sameAs"  => esc_url( $meta['schema_event_place_url'] ),
					"name"    => esc_html( $meta['schema_event_place_name'] ),
					"address" => esc_html( $meta['schema_event_place_address'] )
				)
			);

			// @type: Event recommended items
			if ( isset( $meta['schema_event_description'] ) && $meta['schema_event_description'] !== '' ) {
				$args['description'] = esc_html( $meta['schema_event_description'] );
			}
			if ( isset( $meta['schema_event_image'] ) && $meta['schema_event_image'] !== '' ) {
				$args['image'] = esc_html( $meta['schema_event_image'] );
			}
			if ( isset( $meta['schema_event_date_end'] ) && $meta['schema_event_date_end'] !== '' && isset( $meta['schema_event_time_end'] ) && $meta['schema_event_time_end'] !== '' ) {
				$args['endDate'] = esc_html( $meta['schema_event_date_end'] ) . 'T' . esc_html( $meta['schema_event_time_end'] );
			}

			// @type: Offer
			$offer = array(
				"@type"         => "Offer",
				"price"         => esc_html( $meta['schema_event_offers_price'] ),
				"priceCurrency" => esc_html( $meta['schema_event_offers_currency'] ),
				"url"           => esc_url( $meta['schema_event_url'] )
			);

			// @type: Offer recommended items
			if ( isset( $meta['schema_event_offers_availability'] ) && $meta['schema_event_offers_availability'] !== '' ) {
				$offer['availability'] = "http://schema.org/" . esc_html( $meta['schema_event_offers_availability'] );
			}
			if ( isset( $meta['schema_event_offers_date'] ) && $meta['schema_event_offers_date'] !== '' && isset( $meta['schema_event_offers_time'] ) && $meta['schema_event_offers_time'] !== '' ) {
				$offer['validFrom'] = esc_html( $meta['schema_event_offers_date'] ) . 'T' . esc_html( $meta['schema_event_offers_time'] );
			}

			$args['offers'] = $offer;

			// @type: PerformingGroup recommended items
			if ( isset( $meta['schema_event_performer_name'] ) && $meta['schema_event_performer_name'] !== '' ) {
				$args['performer'] = array(
					"@type" => "PerformingGroup",
					"name"  => esc_html( $meta['schema_event_performer_name'] )
				);
			}

			return (array) $args;
		}
	}
}