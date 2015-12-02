<?php
/**
 * Schema.org Custom Post "Event"
 *
 * @author  Kazuya Takami
 * @since   2.1.0
 * @version 2.1.0
 */
class Structuring_Markup_Custom_Post_Event {

	/**
	 * Variable definition.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	private $text_domain;
	private $custom_type = 'schema_event_post';

	/**
	 * Constructor Define.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 * @param   String $text_domain
	 */
	public function __construct( $text_domain ) {
		$this->text_domain = $text_domain;

		register_post_type(
			$this->custom_type,
			array(
				'labels' => array(
					'name'          => esc_html__( 'Event Posts', $this->text_domain ),
					'singular_name' => esc_html__( 'Event Posts', $this->text_domain )
				),
				'public'        => true,
				'menu_position' => 5,
				'has_archive'   => true,
				'supports'      => array( 'title', 'editor', 'author' ),
				'rewrite'       => array( 'slug' => 'events' ),
			)
		);

		if ( is_admin() ) {
			add_action( 'admin_init',       array( $this, 'admin_init' ) );
			add_action( 'admin_meta_boxes', array( $this, 'admin_meta_boxes' ));
		}
	}

	/**
	 * admin init.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	public function admin_init() {
		add_action( 'save_post_' . $this->custom_type, array( $this, 'save_post' ) );
	}

	/**
	 * admin meta boxes.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	function admin_meta_boxes() {
		$custom_field_title = esc_html__( 'Schema.org Type Event', $this->text_domain );
		add_meta_box( $this->custom_type, $custom_field_title, array( $this, 'set_custom_fields' ), $this->custom_type, 'normal' );
	}

	/**
	 * Set custom fields.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	function set_custom_fields() {
		$args = get_post_meta( get_the_ID(), $this->custom_type, false );
		$args = isset( $args[0] ) ? unserialize( $args[0] ) : "";

		if ( !isset( $args['schema_event_name'] ) ) $args['schema_event_name'] = '';
		if ( !isset( $args['schema_event_date'] ) ) $args['schema_event_date'] = date( 'Y-m-d' );
		if ( !isset( $args['schema_event_time'] ) ) $args['schema_event_time'] = date( 'h:i' );
		if ( !isset( $args['schema_event_url'] ) )  $args['schema_event_url']  = '';
		if ( !isset( $args['schema_event_place_name'] ) )    $args['schema_event_place_name']    = '';
		if ( !isset( $args['schema_event_place_url'] ) )     $args['schema_event_place_url']     = '';
		if ( !isset( $args['schema_event_place_address'] ) ) $args['schema_event_place_address'] = '';

		$html  = '';
		$html .= '<table>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Event Name', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_name" . ']" id="schema_event_name" class="regular-text" required value="' . esc_attr( $args['schema_event_name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Start Date', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="date" name="option[' . "schema_event_date" . ']" id="schema_event_date" required value="' . esc_attr( $args['schema_event_date'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_event_time" . ']" id="schema_event_time" required value="' . esc_attr( $args['schema_event_time'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Event URL', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_url" . ']" id="schema_event_url" class="regular-text" required value="' . esc_attr( $args['schema_event_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place Name', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_name" . ']" id="schema_event_place_name" class="regular-text" required value="' . esc_attr( $args['schema_event_place_name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place URL', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_url" . ']" id="schema_event_place_url" class="regular-text" required value="' . esc_attr( $args['schema_event_place_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place Address', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "schema_event_place_address" . ']" id="schema_event_place_address" class="regular-text" required value="' . esc_attr( $args['schema_event_place_address'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';

		echo $html;
	}

	/**
	 * Save custom post.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 * @param   integer $post_id The post ID.
	 */
	public function save_post( $post_id ) {
		if ( isset( $_POST['option'] ) ) {
			update_post_meta( $post_id, $this->custom_type, serialize( $_POST['option'] ) );
		}
	}
}