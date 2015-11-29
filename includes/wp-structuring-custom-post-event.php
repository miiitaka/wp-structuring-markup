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
					'name'          => esc_html__( 'Event Posts' ),
					'singular_name' => esc_html__( 'Event Posts' )
				),
				'public'      => true,
				'has_archive' => true,
				'supports'    => array( 'title', 'editor', 'author' ),
				'rewrite'     => array( 'slug' => 'events' ),
			)
		);

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'add_custom_fields' ));
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
	 * Add custom fields.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	function add_custom_fields() {
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

		if ( !isset( $args['name'] ) ) $args['name'] = '';
		if ( !isset( $args['date'] ) ) $args['date'] = date( 'Y-m-d' );
		if ( !isset( $args['time'] ) ) $args['time'] = date( 'h:i' );
		if ( !isset( $args['url'] ) )  $args['url']  = '';
		if ( !isset( $args['place_name'] ) )    $args['place_name']    = '';
		if ( !isset( $args['place_url'] ) )     $args['place_url']     = '';
		if ( !isset( $args['place_address'] ) ) $args['place_address'] = '';

		$html  = '';
		$html .= '<table>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Event Name', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $args['name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Start Date', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="date" name="option[' . "date" . ']" id="date" required value="' . esc_attr( $args['date'] ) . '">';
		$html .= '<input type="time" name="option[' . "time" . ']" id="time" required value="' . esc_attr( $args['time'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Event URL', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $args['url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place Name', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "place_name" . ']" id="place_name" class="regular-text" required value="' . esc_attr( $args['place_name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place URL', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "place_url" . ']" id="place_url" class="regular-text" required value="' . esc_attr( $args['place_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th>';
		$html .= esc_html__( 'Place Address', $this->text_domain );
		$html .= '</th><td>';
		$html .= '<input type="text" name="option[' . "place_address" . ']" id="place_address" class="regular-text" required value="' . esc_attr( $args['place_address'] ) . '">';
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
			if ( !add_post_meta( $post_id, $this->custom_type, serialize( $_POST['option'] ), true ) ) {
				update_post_meta( $post_id, $this->custom_type, serialize( $_POST['option'] ) );
			}
		}
	}
}