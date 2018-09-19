<?php
/**
 * Schema.org Type LocalBusiness
 *
 * @author  Kazuya Takami
 * @version 4.6.0
 * @since   2.3.3
 * @see     wp-structuring-admin-db.php
 * @link    http://schema.org/LocalBusiness
 * @link    https://schema.org/GeoCircle
 * @link    https://developers.google.com/search/docs/data-types/local-businesses
 */
class Structuring_Markup_Type_LocalBusiness {

	/**
	 * Variable definition.
	 *
	 * @version 4.6.0
	 * @since   2.3.0
	 */
	/** LocalBusiness Type defined. */
	private $business_type_array = array(
		array( 'type' => 'LocalBusiness', 'display' => 'LocalBusiness' ),

		array( 'type' => 'AnimalShelter', 'display' => '- AnimalShelter' ),

		array( 'type' => 'AutomotiveBusiness', 'display' => '- AutomotiveBusiness' ),
		array( 'type' => 'AutoBodyShop',       'display' => '-- AutoBodyShop' ),
		array( 'type' => 'AutoDealer',         'display' => '-- AutoDealer' ),
		array( 'type' => 'AutoPartsStore',     'display' => '-- AutoPartsStore' ),
		array( 'type' => 'AutoRental',         'display' => '-- AutoRental' ),
		array( 'type' => 'AutoRepair',         'display' => '-- AutoRepair' ),
		array( 'type' => 'AutoWash',           'display' => '-- AutoWash' ),
		array( 'type' => 'GasStation',         'display' => '-- GasStation' ),
		array( 'type' => 'MotorcycleDealer',   'display' => '-- MotorcycleDealer' ),
		array( 'type' => 'MotorcycleRepair',   'display' => '-- MotorcycleRepair' ),

		array( 'type' => 'ChildCare', 'display' => '- ChildCare' ),

		array( 'type' => 'DryCleaningOrLaundry', 'display' => '- DryCleaningOrLaundry' ),

		array( 'type' => 'EmergencyService', 'display' => '- EmergencyService' ),
		array( 'type' => 'FireStation',      'display' => '-- FireStation' ),
		array( 'type' => 'Hospital',         'display' => '-- Hospital' ),
		array( 'type' => 'PoliceStation',    'display' => '-- PoliceStation' ),

		array( 'type' => 'EmploymentAgency', 'display' => '- EmploymentAgency' ),

		array( 'type' => 'EntertainmentBusiness', 'display' => '- EntertainmentBusiness' ),
		array( 'type' => 'AdultEntertainment',    'display' => '-- AdultEntertainment' ),
		array( 'type' => 'AmusementPark',         'display' => '-- AmusementPark' ),
		array( 'type' => 'ArtGallery',            'display' => '-- ArtGallery' ),
		array( 'type' => 'Casino',                'display' => '-- Casino' ),
		array( 'type' => 'ComedyClub',            'display' => '-- ComedyClub' ),
		array( 'type' => 'MovieTheater',          'display' => '-- MovieTheater' ),
		array( 'type' => 'NightClub',             'display' => '-- NightClub' ),

		array( 'type' => 'FinancialService',  'display' => '- FinancialService' ),
		array( 'type' => 'AccountingService', 'display' => '-- AccountingService' ),
		array( 'type' => 'AutomatedTeller',   'display' => '-- AutomatedTeller' ),
		array( 'type' => 'BankOrCreditUnion', 'display' => '-- BankOrCreditUnion' ),
		array( 'type' => 'InsuranceAgency',   'display' => '-- InsuranceAgency' ),

		array( 'type' => 'FoodEstablishment',  'display' => '- FoodEstablishment' ),
		array( 'type' => 'Bakery',             'display' => '-- Bakery' ),
		array( 'type' => 'BarOrPub',           'display' => '-- BarOrPub' ),
		array( 'type' => 'Brewery',            'display' => '-- Brewery' ),
		array( 'type' => 'CafeOrCoffeeShop',   'display' => '-- CafeOrCoffeeShop' ),
		array( 'type' => 'FastFoodRestaurant', 'display' => '-- FastFoodRestaurant' ),
		array( 'type' => 'IceCreamShop',       'display' => '-- IceCreamShop' ),
		array( 'type' => 'Restaurant',         'display' => '-- Restaurant' ),
		array( 'type' => 'Winery',             'display' => '-- Winery' ),

		array( 'type' => 'GovernmentOffice', 'display' => '- GovernmentOffice' ),
		array( 'type' => 'PostOffice',       'display' => '-- PostOffice' ),

		array( 'type' => 'HealthAndBeautyBusiness', 'display' => '- HealthAndBeautyBusiness' ),
		array( 'type' => 'BeautySalon',             'display' => '-- BeautySalon' ),
		array( 'type' => 'DaySpa',                  'display' => '-- DaySpa' ),
		array( 'type' => 'HairSalon',               'display' => '-- HairSalon' ),
		array( 'type' => 'HealthClub',              'display' => '-- HealthClub' ),
		array( 'type' => 'NailSalon',               'display' => '-- NailSalon' ),
		array( 'type' => 'TattooParlor',            'display' => '-- TattooParlor' ),

		array( 'type' => 'HomeAndConstructionBusiness', 'display' => '- HomeAndConstructionBusiness' ),
		array( 'type' => 'Electrician',                 'display' => '-- Electrician' ),
		array( 'type' => 'GeneralContractor',           'display' => '-- GeneralContractor' ),
		array( 'type' => 'HVACBusiness',                'display' => '-- HVACBusiness' ),
		array( 'type' => 'HousePainter',                'display' => '-- HousePainter' ),
		array( 'type' => 'Locksmith',                   'display' => '-- Locksmith' ),
		array( 'type' => 'MovingCompany',               'display' => '-- MovingCompany' ),
		array( 'type' => 'Plumber',                     'display' => '-- Plumber' ),
		array( 'type' => 'RoofingContractor',           'display' => '-- RoofingContractor' ),

		array( 'type' => 'InternetCafe', 'display' => '- InternetCafe' ),

		array( 'type' => 'LegalService', 'display' => '- LegalService' ),
		array( 'type' => 'Attorney',     'display' => '-- Attorney' ),
		array( 'type' => 'Notary',       'display' => '-- Notary' ),

		array( 'type' => 'Library',         'display' => '- Library' ),

		array( 'type' => 'LodgingBusiness', 'display' => '- LodgingBusiness' ),
		array( 'type' => 'BedAndBreakfast', 'display' => '-- BedAndBreakfast' ),
		array( 'type' => 'Hostel',          'display' => '-- Hostel' ),
		array( 'type' => 'Hotel',           'display' => '-- Hotel' ),
		array( 'type' => 'Motel',           'display' => '-- Motel' ),

		array( 'type' => 'MedicalOrganization', 'display' => '- MedicalOrganization' ),
		array( 'type' => 'Dentist',             'display' => '-- Dentist' ),
		array( 'type' => 'DiagnosticLab',       'display' => '-- DiagnosticLab' ),
		array( 'type' => 'Hospital',            'display' => '-- Hospital' ),
		array( 'type' => 'MedicalClinic',       'display' => '-- MedicalClinic' ),
		array( 'type' => 'Optician',            'display' => '-- Optician' ),
		array( 'type' => 'Pharmacy',            'display' => '-- Pharmacy' ),
		array( 'type' => 'Physician',           'display' => '-- Physician' ),
		array( 'type' => 'VeterinaryCare',      'display' => '-- VeterinaryCare' ),

		array( 'type' => 'ProfessionalService',  'display' => '- ProfessionalService' ),

		array( 'type' => 'RadioStation', 'display' => '- RadioStation' ),

		array( 'type' => 'RealEstateAgent', 'display' => '- RealEstateAgent' ),

		array( 'type' => 'RecyclingCenter', 'display' => '- RecyclingCenter' ),

		array( 'type' => 'SelfStorage', 'display' => '- SelfStorage' ),

		array( 'type' => 'ShoppingCenter',     'display' => '- ShoppingCenter' ),

		array( 'type' => 'SportsActivityLocation', 'display' => '- SportsActivityLocation' ),
		array( 'type' => 'BowlingAlley',           'display' => '-- BowlingAlley' ),
		array( 'type' => 'ExerciseGym',            'display' => '-- ExerciseGym' ),
		array( 'type' => 'GolfCourse',             'display' => '-- GolfCourse' ),
		array( 'type' => 'HealthClub',             'display' => '-- HealthClub' ),
		array( 'type' => 'PublicSwimmingPool',     'display' => '-- PublicSwimmingPool' ),
		array( 'type' => 'SkiResort',              'display' => '-- SkiResort' ),
		array( 'type' => 'SportsClub',             'display' => '-- SportsClub' ),
		array( 'type' => 'StadiumOrArena',         'display' => '-- StadiumOrArena' ),
		array( 'type' => 'TennisComplex',          'display' => '-- TennisComplex' ),

		array( 'type' => 'Store',                'display' => '- Store' ),
		array( 'type' => 'AutoPartsStore',       'display' => '-- AutoPartsStore' ),
		array( 'type' => 'BikeStore',            'display' => '-- BikeStore' ),
		array( 'type' => 'BookStore',            'display' => '-- BookStore' ),
		array( 'type' => 'ClothingStore',        'display' => '-- ClothingStore' ),
		array( 'type' => 'ComputerStore',        'display' => '-- ComputerStore' ),
		array( 'type' => 'ConvenienceStore',     'display' => '-- ConvenienceStore' ),
		array( 'type' => 'DepartmentStore',      'display' => '-- DepartmentStore' ),
		array( 'type' => 'ElectronicsStore',     'display' => '-- ElectronicsStore' ),
		array( 'type' => 'Florist',              'display' => '-- Florist' ),
		array( 'type' => 'FurnitureStore',       'display' => '-- FurnitureStore' ),
		array( 'type' => 'GardenStore',          'display' => '-- GardenStore' ),
		array( 'type' => 'GroceryStore',         'display' => '-- GroceryStore' ),
		array( 'type' => 'HardwareStore',        'display' => '-- HardwareStore' ),
		array( 'type' => 'HobbyShop',            'display' => '-- HobbyShop' ),
		array( 'type' => 'HomeGoodsStore',       'display' => '-- HomeGoodsStore' ),
		array( 'type' => 'JewelryStore',         'display' => '-- JewelryStore' ),
		array( 'type' => 'LiquorStore',          'display' => '-- LiquorStore' ),
		array( 'type' => 'MensClothingStore',    'display' => '-- MensClothingStore' ),
		array( 'type' => 'MobilePhoneStore',     'display' => '-- MobilePhoneStore' ),
		array( 'type' => 'MovieRentalStore',     'display' => '-- MovieRentalStore' ),
		array( 'type' => 'MusicStore',           'display' => '-- MusicStore' ),
		array( 'type' => 'OfficeEquipmentStore', 'display' => '-- OfficeEquipmentStore' ),
		array( 'type' => 'OutletStore',          'display' => '-- OutletStore' ),
		array( 'type' => 'PawnShop',             'display' => '-- PawnShop' ),
		array( 'type' => 'PetStore',             'display' => '-- PetStore' ),
		array( 'type' => 'ShoeStore',            'display' => '-- ShoeStore' ),
		array( 'type' => 'SportingGoodsStore',   'display' => '-- SportingGoodsStore' ),
		array( 'type' => 'TireShop',             'display' => '-- TireShop' ),
		array( 'type' => 'ToyStore',             'display' => '-- ToyStore' ),
		array( 'type' => 'WholesaleStore',       'display' => '-- WholesaleStore' ),

		array( 'type' => 'TelevisionStation', 'display' => '- TelevisionStation' ),

		array( 'type' => 'TouristInformationCenter', 'display' => '- TouristInformationCenter' ),

		array( 'type' => 'TravelAgency', 'display' => '- TravelAgency' )
	);

	/** weekType defined. */
	private $week_array = array(
		array('type' => 'Mo', 'display' => 'Monday'),
		array('type' => 'Tu', 'display' => 'Tuesday'),
		array('type' => 'We', 'display' => 'Wednesday'),
		array('type' => 'Th', 'display' => 'Thursday'),
		array('type' => 'Fr', 'display' => 'Friday'),
		array('type' => 'Sa', 'display' => 'Saturday'),
		array('type' => 'Su', 'display' => 'Sunday')
	);

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.4
	 * @since   2.3.0
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
	 * @version 4.5.3
	 * @since   2.3.3
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		/** Local Business Type */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Local Business</caption>';
		$html .= $this->set_form_select( 'business_type', 'Local Business Type', $option['business_type'] );
		$html .= $this->set_form_text( 'image', 'An image of the business', $option['image'], true );
		$html .= $this->set_form_text( 'name', 'Business Name', $option['name'], true );
		$html .= $this->set_form_text( 'url', 'Url', $option['url'], true );
		$html .= $this->set_form_text( 'telephone', 'Telephone', $option['telephone'], false, 'e.g. : +1-880-555-1212' );
		$html .= '</table>';
		echo $html;

		/** For food establishments */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>For food establishments</caption>';
		$html .= $this->set_form_checkbox( 'food_active', 'Setting', $option['food_active'], 'Enabled' );
		$html .= $this->set_form_text( 'menu', 'Menu url', $option['menu'], false, 'For food establishments, the fully-qualified URL of the menu.' );
		$html .= $this->set_form_checkbox( 'accepts_reservations', 'Accepts Reservations', $option['accepts_reservations'], 'For food establishments, and whether it is possible to accept a reservation?' );
		$html .= $this->set_form_text( 'serves_cuisine', 'Serves Cuisine', $option['serves_cuisine'], false, 'If marking up a restaurant, provide the type of cuisine they serve.' );
		$html .= '</table>';
		echo $html;

		/** Postal Address */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Postal Address</caption>';
		$html .= $this->set_form_text( 'street_address', 'Street Address', $option['street_address'], true );
		$html .= $this->set_form_text( 'address_locality', 'Address Locality', $option['address_locality'], true );
		$html .= $this->set_form_text( 'address_region', 'Address Region', $option['address_region'], false );
		$html .= $this->set_form_text( 'postal_code', 'Postal Code', $option['postal_code'], true );
		$html .= $this->set_form_text( 'address_country', 'Address Country', $option['address_country'], true, '<a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">The 2-letter ISO 3166-1 alpha-2 country code.</a>' );
		$html .= '</table>';
		echo $html;

		/** Geo Circle */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Geo Circle</caption>';
		$html .= $this->set_form_checkbox( 'geo_circle_active', 'Setting', $option['geo_circle_active'], 'Enabled' );
		$html .= $this->set_form_text( 'geo_circle_radius', 'geoRadius', $option['geo_circle_radius'], false );
		$html .= '</table>';
		echo $html;

		/** Geo Coordinates */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Geo Coordinates</caption>';
		$html .= $this->set_form_checkbox( 'geo_active', 'Setting', $option['geo_active'], 'Enabled' );
		$html .= $this->set_form_text( 'latitude', 'Latitude', $option['latitude'], false );
		$html .= $this->set_form_text( 'longitude', 'Longitude', $option['longitude'], false );
		$html .= '</table>';
		echo $html;

		/** Opening Hours Specification */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Opening Hours Specification</caption>';

		$i = 0;

		foreach ( $this->week_array as $value ) {
			if ( !isset( $option[$value['type']] ) ) {
				$option[$value['type']] = "";
			}

			$html .= $this->set_form_checkbox( $value['type'], $value['display'], $option[$value['type']], 'Enabled' );

			if ( isset( $option['week'][$value['type']] ) ) {
				foreach ( $option['week'][$value['type']] as $type ) {
					if ( !empty( $type['open'] ) ) {
						$html .= $this->set_form_time( $value['type'], '', $type['open'], $type['close'], '', $i );
						$i++;
					} else {
						$html .= $this->set_form_time( $value['type'], '', '', '', '', 0 );
						break;
					}
				}
			} else {
				$html .= $this->set_form_time( $value['type'], '', '', '', '', 0 );
			}

			$i = 0;
		}

		$html .= '</table>';
		echo $html;

		/** Holiday Opening Hours */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Holiday Opening Hours</caption>';
		$html .= $this->set_form_checkbox( 'holiday_active', 'Setting', $option['holiday_active'], 'Enabled' );
		$html .= $this->set_form_time_holiday( $option['holiday_open'], $option['holiday_close'] );
		$html .= $this->set_form_date( 'holiday_valid_from', 'validFrom', $option['holiday_valid_from'], false );
		$html .= $this->set_form_date( 'holiday_valid_through', 'validThrough', $option['holiday_valid_through'], false );

		$html .= '</table>';
		echo $html;

		/** Price Range */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Price Range</caption>';
		$html .= $this->set_form_text( 'price_range', 'Price Range', $option['price_range'], false, 'The price range of the business, for example $$$.' );
		$html .= '</table>';
		echo $html;

		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Setting Knowledge</caption>';
		$html .= '<tr><th>schema.org LocalBusiness :</th>';
		$html .= '<td><a href="http://schema.org/LocalBusiness" target="_blank">http://schema.org/LocalBusiness</a></td></tr>';
		$html .= '<tr><th>schema.org GeoCircle :</th>';
		$html .= '<td><a href="https://schema.org/GeoCircle" target="_blank">https://schema.org/GeoCircle</a></td></tr>';
		$html .= '<tr><th>Google Search LocalBusiness :</th>';
		$html .= '<td><a href="https://developers.google.com/search/docs/data-types/local-businesses" target="_blank">https://developers.google.com/search/docs/data-types/local-businesses</a></td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since   4.6.0
	 * @version 2.5.0
	 * @return  array $args
	 */
	private function get_default_options () {
		$args['business_type']        = 'LocalBusiness';
		$args['name']                 = '';
		$args['image']                = '';
		$args['url']                  = '';
		$args['telephone']            = '';
		$args['food_active']          = '';
		$args['menu']                 = '';
		$args['accepts_reservations'] = '';
		$args['serves_cuisine']       = '';
		$args['street_address']       = '';
		$args['address_locality']     = '';
		$args['address_region']       = '';
		$args['postal_code']          = '';
		$args['address_country']      = '';
		$args['geo_circle_active']    = '';
		$args['geo_circle_radius']    = '';
		$args['geo_active']           = '';
		$args['latitude']             = '';
		$args['longitude']            = '';
		$args['opening_active']       = '';

		foreach ( $this->week_array as $value ) {
			$args[$value['type']]                  = '';
			$args['week'][$value['type']]['open']  = '';
			$args['week'][$value['type']]['close'] = '';
		}

		$args['holiday_active']        = '';
		$args['holiday_open']          = '';
		$args['holiday_close']         = '';
		$args['holiday_valid_from']    = '';
		$args['holiday_valid_through'] = '';
		$args['price_range']           = '';

		return (array) $args;
	}

	/**
	 * Return the form text
	 *
	 * @version 4.1.3
	 * @since   2.3.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   boolean $required
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_text ( $id, $display, $value = "", $required = false, $note = "" ) {
		$value = esc_attr( $value );

		if ( $required ) {
			$format = '<tr><th class="require"><label for=%s>%s :</label></th><td>';
		} else {
			$format = '<tr><th><label for=%s>%s :</label></th><td>';
		}

		$format .= '<input type="text" name="option[%s]" id="%s" class="regular-text" value="%s"';

		if ( $required ) {
			$format .= ' required';
		}
		if ( $note === "" ) {
			$format .= '></td></tr>';
		} else {
			$format .= '><small>%s</small></td></tr>';
		}

		return (string) sprintf( $format, $id, $display, $id, $id, $value, $note );
	}

	/**
	 * Return the form date
	 *
	 * @since   3.0.5
	 * @version 2.5.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value
	 * @param   boolean $required
	 * @param   string  $note
	 * @return  string  $html
	 */
	private function set_form_date ( $id, $display, $value = "", $required = false, $note = "" ) {
		$value = esc_attr( $value );

		$format  = '<tr><th><label for=%s>%s :</label></th><td>';
		$format .= '<input type="date" name="option[%s]" id="%s" value="%s"';
		if ( $required ) {
			$format .= ' required';
		}
		$format .= '><small>%s</small></td></tr>';

		return (string) sprintf( $format, $id, $display, $id, $id, $value, $note );
	}

	/**
	 * Return the form checkbox
	 *
	 * @version 3.0.5
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
		$format .= '>%s</td></tr>';

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

		$format  = '<tr><th class="require"><label for=%s>%s :</label></th><td>';
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

	/**
	 * Return the form time
	 *
	 * @version 3.0.5
	 * @since   2.3.0
	 * @param   string  $id
	 * @param   string  $display
	 * @param   string  $value1
	 * @param   string  $value2
	 * @param   string  $note
	 * @param   int     $count
	 * @return  string  $html
	 */
	private function set_form_time ( $id, $display, $value1 = "", $value2 = "", $note = "", $count = 0 ) {
		$value1 = esc_attr( $value1 );
		$value2 = esc_attr( $value2 );

		$format  = '<tr class="opening-hours %s"><th><label for=%s>%s :</label></th><td>';
		$format .= 'Open Time : <input type="time" name="option[week][%s][%d][open]" id="%s-open" value="%s">';
		$format .= ' Close Time : <input type="time" name="option[week][%s][%d][close]" id="%s-close" value="%s">';
		$format .= '%s<a class="dashicons dashicons-plus markup-time plus"></a>';
		if( $count !== 0 ) {
			$format .= '<a class="dashicons dashicons-minus markup-time minus"></a>';
		}
		$format .= '</td></tr>';

		return (string) sprintf( $format, $id, $id, $display, $id, $count, $id, $value1, $id, $count, $id, $value2, $note );
	}

	/**
	 * Return the form time (Holiday)
	 *
	 * @since   2.5.0
	 * @version 2.5.0
	 * @param   string  $value1
	 * @param   string  $value2
	 * @return  string  $html
	 */
	private function set_form_time_holiday ( $value1 = "", $value2 = "" ) {
		$value1 = esc_attr( $value1 );
		$value2 = esc_attr( $value2 );

		$format  = '<tr><th>Holiday Time :</th><td>';
		$format .= 'Open Time : <input type="time" name="option[holiday_open]" value="%s">';
		$format .= 'Close Time : <input type="time" name="option[holiday_close]" value="%s">';
		$format .= '</td></tr>';

		return (string) sprintf( $format, $value1, $value2 );
	}
}