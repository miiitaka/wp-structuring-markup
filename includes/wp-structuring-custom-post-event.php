<?php
/**
 * Schema.org Custom Post "Event"
 *
 * @author  Kazuya Takami
 * @version 3.2.3
 * @since   2.1.0
 * @link    https://schema.org/Event
 * @link    https://developers.google.com/search/docs/data-types/events
 */
class Structuring_Markup_Custom_Post_Event {

	/**
	 * Variable definition.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	private $text_domain;
	private $custom_type = 'schema_event_post';

	/**
	 * Event Type.
	 *
	 * @version 3.1.3
	 * @since   3.1.3
	 */
	private $event_type = array(
		"Event",
		"BusinessEvent",
		"ChildrensEvent",
		"ComedyEvent",
		"DanceEvent",
		"DeliveryEvent",
		"EducationEvent",
		"ExhibitionEvent",
		"Festival",
		"FoodEvent",
		"LiteraryEvent",
		"MusicEvent",
		"PublicationEvent",
		"SaleEvent",
		"ScreeningEvent",
		"SocialEvent",
		"SportsEvent",
		"TheaterEvent",
		"VisualArtsEvent"
	);

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.6
	 * @since   2.1.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		/** Custom post menu controls */
		$show_flag = __return_false();
		if ( isset( $_POST['type'] ) && $_POST['type'] === 'event' ) {
			if ( isset( $_POST['activate'] ) && $_POST['activate'] === 'on' ) {
				$show_flag = __return_true();
			}
		} else {
			/** DB Connect */
			$db = new Structuring_Markup_Admin_Db();
			$results = $db->get_type_options('event');

			if ( isset( $results['activate'] ) && $results['activate'] == 'on' ) {
				$show_flag = __return_true();
			}
		}

		register_post_type(
			$this->custom_type,
			array(
				'labels' => array(
					'name'          => esc_html__( 'Event Posts',     $this->text_domain ),
					'singular_name' => esc_html__( 'Event Posts',     $this->text_domain ),
					'all_items'     => esc_html__( 'All Event Posts', $this->text_domain )
				),
				'capability_type' => 'post',
				'has_archive'     => true,
				'hierarchical'    => false,
				'menu_position'   => 5,
				'public'          => $show_flag,
				'query_var'       => false,
				'rewrite'         => array( 'with_front' => true, 'slug' => 'events' ),
				'show_in_menu'    => $show_flag,
				'show_ui'         => $show_flag,
				'supports'        => array( 'title', 'editor', 'author', 'thumbnail' )
			)
		);

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}
	}

	/**
	 * admin init.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public function admin_init () {
		add_action( 'save_post_' . $this->custom_type, array( $this, 'save_post' ) );
	}

	/**
	 * admin meta boxes.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	public function admin_menu () {
		$custom_field_title = esc_html__( 'Schema.org Type Event', $this->text_domain );
		add_meta_box( $this->custom_type, $custom_field_title, array( $this, 'set_custom_fields' ), $this->custom_type, 'normal' );
	}

	/**
	 * Set custom fields.
	 *
	 * @version 3.2.3
	 * @since   2.1.0
	 */
	public function set_custom_fields () {
		$custom_array = get_post_meta( get_the_ID(), $this->custom_type, false );
		$custom_array = isset( $custom_array[0] ) ? unserialize( $custom_array[0] ) : array();

		/** Default Value Set */
		$args = $this->get_default_options();

		if ( !empty( $args ) ) {
			$args = array_merge( $args, $custom_array );
		}

		$html  = '';
		$html .= '<table>';
		$html .= '<tr><th><label for="schema_event_type">';
		$html .= esc_html__( 'Event Type', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<select name="option[' . "schema_event_type" . ']" id="schema_event_type">';
		foreach( $this->event_type as $value) {
			$html .= '<option';
			if ( $value === $args['schema_event_type'] ) {
				$html .= ' selected="selected"';
			}
			$html .=  ' value="' . $value . '">' . $value . '</option>';
		}
		$html .= '</select>';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_name">';
		$html .= esc_html__( 'Event Name', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_name" . ']" id="schema_event_name" class="regular-text" required value="' . esc_attr( $args['schema_event_name'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_description">';
		$html .= esc_html__( 'Event Description', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<textarea name="option[' . "schema_event_description" . ']" id="schema_event_description" class="large-text code" rows="3">' . esc_attr( $args['schema_event_description'] ) . '</textarea>';
		$html .= '&nbsp;(recommended)</td></tr>';
		$html .= '<tr><th><label for="schema_event_image">';
		$html .= esc_html__( 'Event Image', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_image" . ']" id="schema_event_image" class="large-text" value="' . esc_attr( $args['schema_event_image'] ) . '">';
		$html .= '&nbsp;(recommended)</td></tr>';
		$html .= '<tr><th><label for="schema_event_date">';
		$html .= esc_html__( 'Start Date', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="date" name="option[' . "schema_event_date" . ']" id="schema_event_date" required value="' . esc_attr( $args['schema_event_date'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_event_time" . ']" id="schema_event_time" required value="' . esc_attr( $args['schema_event_time'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_date_end">';
		$html .= esc_html__( 'End Date', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="date" name="option[' . "schema_event_date_end" . ']" id="schema_event_date" value="' . esc_attr( $args['schema_event_date_end'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_event_time_end" . ']" id="schema_event_time" value="' . esc_attr( $args['schema_event_time_end'] ) . '">';
		$html .= '&nbsp;(recommended)</td></tr>';
		$html .= '<tr><th><label for="schema_event_url">';
		$html .= esc_html__( 'Event URL', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_url" . ']" id="schema_event_url" class="regular-text" required value="' . esc_attr( $args['schema_event_url'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_place_name">';
		$html .= esc_html__( 'Place Name', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_name" . ']" id="schema_event_place_name" class="regular-text" required value="' . esc_attr( $args['schema_event_place_name'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_place_url">';
		$html .= esc_html__( 'Place URL', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_url" . ']" id="schema_event_place_url" class="regular-text" required value="' . esc_attr( $args['schema_event_place_url'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_place_address">';
		$html .= esc_html__( 'Place Address', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_address" . ']" id="schema_event_place_address" class="regular-text" required value="' . esc_attr( $args['schema_event_place_address'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_offers_price">';
		$html .= esc_html__( 'Price', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="number" name="option[' . "schema_event_offers_price" . ']" id="schema_event_offers_price" required value="' . esc_attr( $args['schema_event_offers_price'] ) . '">';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '<tr><th><label for="schema_event_offers_currency">';
		$html .= esc_html__( 'Currency', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_offers_currency" . ']" id="schema_event_offers_currency" maxlength="3" required value="' . esc_attr( $args['schema_event_offers_currency'] ) . '">';
		$html .= '&nbsp;&nbsp;<small>( with <a hre="https://en.wikipedia.org/wiki/ISO_4217#Active_codes" target="_blank">ISO 4217 codes</a> e.g. "USD" )</small>';
		$html .= '&nbsp;(required)</td></tr>';
		$html .= '</table>';

		echo $html;
	}

	/**
	 * Save custom post.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 * @param   integer $post_id The post ID.
	 */
	public function save_post ( $post_id ) {
		if ( isset( $_POST['option'] ) ) {
			update_post_meta( $post_id, $this->custom_type, serialize( $_POST['option'] ) );
		}
	}

	/**
	 * Return the default options array
	 *
	 * @version 3.2.3
	 * @since   3.2.3
	 * @return  array $args
	 */
	private function get_default_options () {
		$args = array(
			'schema_event_type'            => 'Event',
			'schema_event_name'            => '',
			'schema_event_description'     => '',
			'schema_event_image'           => '',
			'schema_event_date'            => date( 'Y-m-d' ),
			'schema_event_time'            => date( 'h:i' ),
			'schema_event_date_end'        => '',
			'schema_event_time_end'        => '',
			'schema_event_url'             => '',
			'schema_event_place_name'      => '',
			'schema_event_place_url'       => '',
			'schema_event_place_address'   => '',
			'schema_event_offers_price'    => 0,
			'schema_event_offers_currency' => esc_html__( 'USD', $this->text_domain )
		);

		return (array) $args;
	}
}