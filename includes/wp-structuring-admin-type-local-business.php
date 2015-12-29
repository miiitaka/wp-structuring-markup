<?php
/**
 * Schema.org Type Organization
 *
 * @author  Kazuya Takami
 * @since   2.3.0
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/LocalBusiness
 * @link    https://developers.google.com/structured-data/local-businesses/
 */
class Structuring_Markup_Type_LocalBusiness {

	/**
	 * Variable definition.
	 *
	 * @since 2.3.0
	 */
	/** contactType defined. */
	private $business_type_array = array(
		array("type" => "local_business", "display" => "Local Business"),
		array("type" => "restaurant",     "display" => "Restaurant")
	);
	/** Social Profile */
	private $social_array = array(
		array("type" => "facebook",   "display" => "Facebook"),
		array("type" => "twitter",    "display" => "Twitter"),
		array("type" => "google",     "display" => "Google+"),
		array("type" => "instagram",  "display" => "Instagram"),
		array("type" => "youtube",    "display" => "Youtube"),
		array("type" => "linkedin",   "display" => "LinkedIn"),
		array("type" => "myspace",    "display" => "Myspace"),
		array("type" => "pinterest",  "display" => "Pinterest"),
		array("type" => "soundcloud", "display" => "SoundCloud"),
		array("type" => "tumblr",     "display" => "Tumblr")
	);

	/**
	 * Constructor Define.
	 *
	 * @since 2.3.0
	 * @param array $option
	 */
	public function __construct ( array $option ) {
		/** Default Value Set */
		if ( empty( $option ) ) {
			$option = $this->get_default_options( $option );
		}
		$this->page_render( $option );
	}

	/**
	 * Form Layout Render
	 *
	 * @since   2.3.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		/** Local Business Type */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Local Business</caption>';
		$html .= '<tr><th><label for="business_type">Local Business Type :</label></th><td>';
		$html .= '<select id="business_type" name="option[' . "business_type" . ']">';
		foreach ( $this->business_type_array as $value ) {
			$html .= '<option value="' . $value['type'] . '"';
			if ( $value['type'] === $option['business_type'] ) {
				$html .= ' selected';
			}
			$html .= '>' . $value['display'] . '</option>';
		}
		$html .= '</select>';
		$html .= '<small>Default : "Local Business"</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="name">Business Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="url">Url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $option['url'] ) . '">';
		$html .= '<small>Default : bloginfo("url")</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="telephone">Telephone :</label></th><td>';
		$html .= '<input type="text" name="option[' . "telephone" . ']" id="telephone" class="regular-text" value="' . esc_attr( $option['telephone'] ) . '">';
		$html .= '<small>e.g. : +1-880-555-1212</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="menu">Menu url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "menu" . ']" id="menu" class="regular-text" value="' . esc_attr( $option['menu'] ) . '">';
		$html .= '<small>For food establishments, the fully-qualified URL of the menu.</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="accepts_reservations">Accepts Reservations :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "accepts_reservations" . ']" id="accepts_reservations" value="on"';
		if ( isset( $option['accepts_reservations'] ) &&  $option['accepts_reservations'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '><small>For food establishments, and whether it is possible to accept a reservation?</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		/** Postal Address */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Postal Address</caption>';
		$html .= '<tr><th>@type :</th><td><small>"PostalAddress"</small></td></tr>';
		$html .= '<tr><th><label for="street_address">Street Address :</label></th><td>';
		$html .= '<input type="text" name="option[' . "street_address" . ']" id="street_address" class="regular-text" value="' . esc_attr( $option['street_address'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="address_locality">Address Locality :</label></th><td>';
		$html .= '<input type="text" name="option[' . "address_locality" . ']" id="address_locality" class="regular-text" value="' . esc_attr( $option['address_locality'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="address_region">Address Region :</label></th><td>';
		$html .= '<input type="text" name="option[' . "address_region" . ']" id="address_region" class="regular-text" value="' . esc_attr( $option['address_region'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="postal_code">Postal Code :</label></th><td>';
		$html .= '<input type="text" name="option[' . "postal_code" . ']" id="postal_code" class="regular-text" value="' . esc_attr( $option['postal_code'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="address_country">Address Country :</label></th><td>';
		$html .= '<input type="text" name="option[' . "address_country" . ']" id="address_country" class="regular-text" value="' . esc_attr( $option['address_country'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		/** Geo Coordinates */
		$html  = '<table class="schema-admin-table">';
		$html .= '<tr><th>@type :</th><td><small>"GeoCoordinates"</small></td></tr>';
		$html .= '<caption>Geo Coordinates</caption>';
		$html .= '<tr><th><label for="latitude">Latitude :</label></th><td>';
		$html .= '<input type="text" name="option[' . "latitude" . ']" id="latitude" class="regular-text" value="' . esc_attr( $option['latitude'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="longitude">Longitude :</label></th><td>';
		$html .= '<input type="text" name="option[' . "longitude" . ']" id="longitude" class="regular-text" value="' . esc_attr( $option['longitude'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/customize/overview" target="_blank">https://developers.google.com/structured-data/customize/overview</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since   2.3.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['business_type']        = 'local_business';
		$args['name']                 = get_bloginfo('name');
		$args['url']                  = get_bloginfo('url');
		$args['telephone']            = '';
		$args['menu']                 = '';
		$args['accepts_reservations'] = '';
		$args['street_address']       = '';
		$args['address_locality']     = '';
		$args['address_region']       = '';
		$args['postal_code']          = '';
		$args['address_country']      = '';
		$args['latitude']             = '';
		$args['longitude']            = '';

		foreach ( $this->social_array as $value ) {
			$args['social'][$value['type']] = '';
		}

		return (array) $args;
	}
}