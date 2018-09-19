<?php
/**
 * Schema.org Type Organization
 *
 * @author  Kazuya Takami
 * @version 4.6.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/Organization
 * @link    https://developers.google.com/search/docs/data-types/logo
 * @link    https://developers.google.com/search/docs/data-types/corporate-contact
 * @link    https://developers.google.com/search/docs/data-types/social-profile
 */
class Structuring_Markup_Type_Organization {

	/**
	 * Variable definition.
	 *
	 * @version 4.6.0
	 * @since   1.0.0
	 */
	/** Organization Type defined */
	private $organization_type_array = array(
		array('type' => 'Organization',            'display' => 'Organization'),
		array('type' => 'Airline',                 'display' => '- Airline'),
		array('type' => 'Consortium',              'display' => '- Consortium'),
		array('type' => 'Corporation',             'display' => '- Corporation'),
		array('type' => 'EducationalOrganization', 'display' => '- EducationalOrganization'),
		array('type' => 'GovernmentOrganization',  'display' => '- GovernmentOrganization'),
		array('type' => 'LibrarySystem',           'display' => '- LibrarySystem'),
		array('type' => 'LocalBusiness',           'display' => '- LocalBusiness'),
		array('type' => 'MedicalOrganization',     'display' => '- MedicalOrganization'),
		array('type' => 'NewsMediaOrganization',   'display' => '- NewsMediaOrganization'),
		array('type' => 'NGO',                     'display' => '- NGO'),
		array('type' => 'PerformingGroup',         'display' => '- PerformingGroup'),
		array('type' => 'SportsOrganization',      'display' => '- SportsOrganization'),
		array('type' => 'WorkersUnion',            'display' => '- WorkersUnion')
	);
	/** contactType defined. */
	private $contact_type_array = array(
		array('type' => 'customer service',    'display' => 'customer service'),
		array('type' => 'technical support',   'display' => 'technical support'),
		array('type' => 'billing support',     'display' => 'billing support'),
		array('type' => 'bill payment',        'display' => 'bill payment'),
		array('type' => 'sales',               'display' => 'sales'),
		array('type' => 'reservations',        'display' => 'reservations'),
		array('type' => 'credit card_support', 'display' => 'credit card support'),
		array('type' => 'emergency',           'display' => 'emergency'),
		array('type' => 'baggage tracking',    'display' => 'baggage tracking'),
		array('type' => 'roadside assistance', 'display' => 'roadside assistance'),
		array('type' => 'package tracking',    'display' => 'package tracking')
	);
	/** Social Profile */
	private $social_array = array(
		array('type' => 'facebook',   'display' => 'Facebook'),
		array('type' => 'twitter',    'display' => 'Twitter'),
		array('type' => 'google',     'display' => 'Google+'),
		array('type' => 'instagram',  'display' => 'Instagram'),
		array('type' => 'youtube',    'display' => 'Youtube'),
		array('type' => 'linkedin',   'display' => 'LinkedIn'),
		array('type' => 'myspace',    'display' => 'Myspace'),
		array('type' => 'pinterest',  'display' => 'Pinterest'),
		array('type' => 'soundcloud', 'display' => 'SoundCloud'),
		array('type' => 'tumblr',     'display' => 'Tumblr')
	);

	/**
	 * Constructor Define.
	 *
	 * @version 4.1.0
	 * @since   1.0.0
	 * @param   array $option
	 */
	public function __construct ( array $option ) {
		/** Default Value Set */
		$option_array = $this->get_default_options();

		if ( !empty( $option ) ) {
			$option_array = array_merge( $option_array, $option );
		}
		$this->page_render( $option_array );
	}

	/**
	 * Form Layout Render
	 *
	 * @version 4.6.0
	 * @since   1.0.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		/** Organization Type */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Organization</caption>';
		$html .= $this->set_form_select( 'organization_type', 'Organization Type', $option['organization_type'] );
		$html .= '</table>';
		echo $html;

		/** Logos */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Logos</caption>';
		$html .= '<tr><th class="require"><label for="name">Organization Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th class="require"><label for="url">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $option['url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th class="require"><label for="logo">logo :</label></th><td>';
		$html .= '<input type="text" name="option[' . "logo" . ']" id="logo" class="regular-text" required value="' . esc_attr( $option['logo'] ) . '">';
		$html .= '<button id="media-upload" class="dashicons-before dashicons-admin-media schema-admin-media-button"></button>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		/** Corporate Contact */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Corporate Contact</caption>';
		$html .= '<tr><th><label for="contact_point">contactPoint :</label></th><td>';
		$html .= '<input type="checkbox" name="option[' . "contact_point" . ']" id="contact_point" value="on"';
		if ( isset( $option['contact_point'] ) &&  $option['contact_point'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>Enabled';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="telephone">telephone :</label></th><td>';
		$html .= '<input type="text" name="option[' . "telephone" . ']" id="telephone" class="regular-text" value="' . esc_attr( $option['telephone'] ) . '" placeholder="e.g. : +1-880-555-1212">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="contact_type">contactType :</label></th><td>';
		$html .= '<select id="contact_type" name="option[' . "contact_type" . ']">';
		foreach ( $this->contact_type_array as $value ) {
			$html .= '<option value="' . $value['type'] . '"';
			if ( $value['type'] === $option['contact_type'] ) {
				$html .= ' selected';
			}
			$html .= '>' . $value['display'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="email">email :</label></th><td>';
		$html .= '<input type="email" name="option[' . "email" . ']" id="email" class="regular-text" value="' . esc_attr( $option['email'] ) . '" placeholder="e.g. : info@example.com">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="area_served">areaServed :</label></th><td>';
		$html .= '<input type="text" name="option[' . "area_served" . ']" id="area_served" class="regular-text" value="' . esc_attr( $option['area_served'] ) . '">';
		$html .= '<small>Default : "US"&nbsp;&nbsp;Multiple : "US,CA"</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th>contactOption :</th><td>';
		$html .= '<label><input type="checkbox" name="option[' . "contact_point_1" . ']" id="contact_point_1" value="on"';
		if ( isset( $option['contact_point_1'] ) && $option['contact_point_1'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>HearingImpairedSupported</label><br>';
		$html .= '<label><input type="checkbox" name="option[' . "contact_point_2" . ']" id="contact_point_2" value="on"';
		if ( isset( $option['contact_point_2'] ) && $option['contact_point_2'] === 'on' ) {
			$html .= ' checked="checked"';
		}
		$html .= '>TollFree</label><br>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="available_language">available&nbsp;Language :</label></th><td>';
		$html .= '<input type="text" name="option[' . "available_language" . ']" id="available_language" class="regular-text" value="' . esc_attr( $option['available_language'] ) . '">';
		$html .= '<small>Default : "English"&nbsp;&nbsp;Multiple : "French,English"</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		/** Social Profiles */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Social Profiles</caption>';
		foreach ( $this->social_array as $value ) {
			$html .= '<tr><th><label for="' . $value['type'] . '">' . $value['display'] . ' :</label></th><td>';
			$html .= '<input type="text" name="option[' . "social" . '][' . $value['type'] . ']" id="' . $value['type'] . '" class="regular-text" value="' . esc_attr( $option['social'][$value['type']] ) . '">';
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>schema.org Organization :</th>';
		$html .= '<td><a href="https://schema.org/Organization" target="_blank">https://schema.org/Organization</a></td></tr>';
		$html .= '<tr><th>Google Search Logos :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/logo" target="_blank">https://developers.google.com/search/docs/data-types/logo</a></td></tr>';
		$html .= '<tr><th>Google Search Corporate Contact :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/corporate-contact" target="_blank">https://developers.google.com/search/docs/data-types/corporate-contact</a></td></tr>';
		$html .= '<tr><th>Google Search Social Profile :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/social-profile" target="_blank">https://developers.google.com/search/docs/data-types/social-profile</a></td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}

	/**
	 * Return the form select
	 *
	 * @version 4.6.0
	 * @since   4.6.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_select ( $id, $display, $value = "", $note = "" ) {
		$value = esc_attr( $value );

		$format  = '<tr><th class="require"><label for=%s>%s :</label></th><td>';
		$format .= '<select id="%s" name="option[%s]">';
		foreach ( $this->organization_type_array as $args ) {
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

	/**
	 * Return the default options array
	 *
	 * @version 4.6.0
	 * @since   1.0.0
	 * @return  array $args
	 */
	private function get_default_options () {
		$args['organization_type']  = 'Organization';
		$args['name']               = '';
		$args['url']                = '';
		$args['logo']               = '';
		$args['contact_point']      = '';
		$args['telephone']          = '';
		$args['contact_type']       = 'customer service';
		$args['email']              = '';
		$args['area_served']        = 'US';
		$args['contact_option_1']   = '';
		$args['contact_option_2']   = '';
		$args['available_language'] = 'English';

		foreach ( $this->social_array as $value ) {
			$args['social'][$value['type']] = '';
		}

		return (array) $args;
	}
}