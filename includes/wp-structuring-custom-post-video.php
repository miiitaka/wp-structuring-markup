<?php
/**
 * Schema.org Custom Post "Video"
 *
 * @author  Kazuya Takami
 * @since   3.0.0
 * @version 3.0.0
 */
class Structuring_Markup_Custom_Post_Video {

	/**
	 * Variable definition.
	 *
	 * @since   3.0.0
	 * @version 3.0.0
	 */
	private $text_domain;
	private $custom_type = 'schema_video_post';

	/**
	 * Constructor Define.
	 *
	 * @since   3.0.0
	 * @version 3.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		register_post_type(
			$this->custom_type,
			array(
				'labels' => array(
					'name'          => esc_html__( 'Video Posts',     $this->text_domain ),
					'singular_name' => esc_html__( 'Video Posts',     $this->text_domain ),
					'all_items'     => esc_html__( 'All Video Posts', $this->text_domain )
				),
				'capability_type' => 'post',
				'has_archive'     => true,
				'hierarchical'    => false,
				'menu_position'   => 5,
				'public'          => true,
				'query_var'       => false,
				'rewrite'         => array( 'with_front' => true, 'slug' => 'videos' ),
				'show_in_menu'    => true,
				'show_ui'         => true,
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
	 * @since   3.0.0
	 * @version 3.0.0
	 */
	public function admin_init () {
		add_action( 'save_post_' . $this->custom_type, array( $this, 'save_post' ) );
	}

	/**
	 * admin meta boxes.
	 *
	 * @since   3.0.0
	 * @version 3.0.0
	 */
	public function admin_menu () {
		$custom_field_title = esc_html__( 'Schema.org Type Video', $this->text_domain );
		add_meta_box( $this->custom_type, $custom_field_title, array( $this, 'set_custom_fields' ), $this->custom_type, 'normal' );
	}

	/**
	 * Set custom fields.
	 *
	 * @since   3.0.0
	 * @version 3.0.0
	 */
	public function set_custom_fields () {
		$args = get_post_meta( get_the_ID(), $this->custom_type, false );
		$args = isset( $args[0] ) ? unserialize( $args[0] ) : "";

		if ( !isset( $args['schema_video_duration'] ) )          $args['schema_video_duration'] = '';
		if ( !isset( $args['schema_video_content_url'] ) )       $args['schema_video_content_url'] = '';
		if ( !isset( $args['schema_video_embed_url'] ) )         $args['schema_video_embed_url'] = '';
		if ( !isset( $args['schema_video_interaction_count'] ) ) $args['schema_video_interaction_count'] = '';
		if ( !isset( $args['schema_video_expires_date'] ) )      $args['schema_video_expires_date'] = '';
		if ( !isset( $args['schema_video_expires_time'] ) )      $args['schema_video_expires_time'] = '';

		$html  = '';
		$html .= '<table>';
		$html .= '<tr><th><label for="schema_video_duration">';
		$html .= esc_html__( 'duration', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_video_duration" . ']" id="schema_video_duration" class="regular-text" value="' . esc_attr( $args['schema_video_duration'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="schema_video_content_url">';
		$html .= esc_html__( 'contentURL', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_video_content_url" . ']" id="schema_video_content_url" class="regular-text" value="' . esc_attr( $args['schema_video_content_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="schema_video_embed_url">';
		$html .= esc_html__( 'embedURL', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_video_embed_url" . ']" id="schema_video_embed_url" class="regular-text" value="' . esc_attr( $args['schema_video_embed_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="schema_video_interaction_count">';
		$html .= esc_html__( 'interactionCount', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="text" name="option[' . "schema_video_interaction_count" . ']" id="schema_video_interaction_count" class="regular-text" value="' . esc_attr( $args['schema_video_interaction_count'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="schema_video_expires_date">';
		$html .= esc_html__( 'expires', $this->text_domain );
		$html .= '</label></th><td>';
		$html .= '<input type="date" name="option[' . "schema_video_expires_date" . ']" id="schema_video_expires_date" value="' . esc_attr( $args['schema_video_expires_date'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_video_expires_time" . ']" id="schema_video_expires_time" value="' . esc_attr( $args['schema_video_expires_time'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';

		echo $html;
	}

	/**
	 * Save custom post.
	 *
	 * @since   3.0.0
	 * @version 3.0.0
	 * @param   integer $post_id The post ID.
	 */
	public function save_post ( $post_id ) {
		if ( isset( $_POST['option'] ) ) {
			update_post_meta( $post_id, $this->custom_type, serialize( $_POST['option'] ) );
		}
	}
}