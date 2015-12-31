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
		$html .= $this->set_form_select( 'business_type', 'Local Business Type', $option['business_type'], 'Default : "Local Business"' );
		$html .= $this->set_form_text( 'name', 'Business Name', $option['name'], true, 'Default : bloginfo("name")' );
		$html .= $this->set_form_text( 'url', 'Url', $option['url'], true, 'Default : bloginfo("url")' );
		$html .= $this->set_form_text( 'telephone', 'Telephone', $option['telephone'], true, 'e.g. : +1-880-555-1212' );
		$html .= $this->set_form_text( 'menu', 'Menu url', $option['menu'], true, 'For food establishments, the fully-qualified URL of the menu.' );
		$html .= $this->set_form_checkbox( 'accepts_reservations', 'Accepts Reservations', $option['accepts_reservations'], 'For food establishments, and whether it is possible to accept a reservation?' );
		$html .= '</table>';
		echo $html;

		/** Postal Address */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Postal Address</caption>';
		$html .= $this->set_form_text( 'street_address', 'Street Address', $option['street_address'], false );
		$html .= $this->set_form_text( 'address_locality', 'Address Locality', $option['address_locality'], false );
		$html .= $this->set_form_text( 'address_region', 'Address Region', $option['address_region'], false );
		$html .= $this->set_form_text( 'postal_code', 'Postal Code', $option['postal_code'], false );
		$html .= $this->set_form_text( 'address_country', 'Address Country', $option['address_country'], false );
		$html .= '</table>';
		echo $html;

		/** Geo Coordinates */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Geo Coordinates</caption>';
		$html .= $this->set_form_checkbox( 'geo_active', 'Setting', $option['geo_active'], 'Active' );
		$html .= $this->set_form_text( 'latitude', 'Latitude', $option['latitude'], false );
		$html .= $this->set_form_text( 'longitude', 'Longitude', $option['latitude'], false );
		$html .= '</table>';
		echo $html;

		/** Opening Hours Specification */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Opening Hours Specification</caption>';
		$html .= $this->set_form_checkbox( 'opening_active', 'Setting', $option['opening_active'], 'Active' );
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/local-businesses/" target="_blank">https://developers.google.com/structured-data/local-businesses/</a></p>';
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
		$args['geo_active']           = '';
		$args['latitude']             = '';
		$args['longitude']            = '';
		$args['opening_active']       = '';

		return (array) $args;
	}

	/**
	 * Return the form text
	 *
	 * @since   2.3.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   boolean $required
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_text ( $id, $display, $value, $required = false, $note = "" ) {
		$value = esc_attr( $value );

		$format  = '<tr><th><label for=%s>%s :</label></th><td>';
		$format .= '<input type="text" name="option[%s]" id="%s" class="regular-text" value="%s"';
		if ( $required ) {
			$format .= ' required';
		}
		$format .= '><small>%s</small></td></tr>';

		return (string) sprintf( $format, $id, $display, $id, $id, $value, $note );
	}

	/**
	 * Return the form checkbox
	 *
	 * @since   2.3.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_checkbox ( $id, $display, $value = "", $note = "" ) {
		$value = esc_attr( $value );

		$format  = '<tr><th><label for=%s>%s :</label></th><td>';
		$format .= '<input type="checkbox" name="option[%s]" id="%s" value="on"';
		if ( $value === 'on' ) {
			$format .= ' checked="checked"';
		}
		$format .= '><small>%s</small></td></tr>';

		return (string) sprintf( $format, $id, $display, $id, $id, $note );
	}

	/**
	 * Return the form select
	 *
	 * @since   2.3.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_select ( $id, $display, $value = "", $note = "" ) {
		$value = esc_attr( $value );

		$format  = '<tr><th><label for=%s>%s :</label></th><td>';
		$format .= '<select id="%s" name="option[%s]">';
		foreach ( $this->business_type_array as $args ) {
			$format .= '<option value="' . $args['type'] . '"';
			if ( $args['type'] === $value ) {
				$format .= ' selected';
			}
			$format .= '>' . $args['display'] . '</option>';
		}
		$format .= '</select>';
		$format .= '<small>%s</small></td></tr>';

		return (string) sprintf( $format, $id, $display, $id, $id, $note );
	}
}