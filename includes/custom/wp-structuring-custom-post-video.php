<?php
/**
 * Schema.org Custom Post "Video"
 *
 * @author  Kazuya Takami
 * @version 4.1.1
 * @since   3.0.0
 * @link    https://schema.org/VideoObject
 * @link    https://developers.google.com/search/docs/data-types/videos
 */
class Structuring_Markup_Custom_Post_Video {

	/**
	 * Variable definition.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	private $text_domain;
	private $custom_type = 'schema_video_post';

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.6
	 * @since   3.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		/** Custom post menu controls */
		$show_flag = __return_false();
		if ( isset( $_POST['type'] ) && $_POST['type'] === 'video' ) {
			if ( isset( $_POST['activate'] ) && $_POST['activate'] === 'on' ) {
				$show_flag = __return_true();
			}
		} else {
			/** DB Connect */
			$db = new Structuring_Markup_Admin_Db();
			$results = $db->get_type_options('video');

			if ( isset( $results['activate'] ) && $results['activate'] == 'on' ) {
				$show_flag = __return_true();
			}
		}

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
				'public'          => $show_flag,
				'query_var'       => false,
				'rewrite'         => array( 'with_front' => true, 'slug' => 'videos' ),
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
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	public function admin_init () {
		add_action( 'save_post_' . $this->custom_type, array( $this, 'save_post' ) );
	}

	/**
	 * admin meta boxes.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	public function admin_menu () {
		$custom_field_title = esc_html__( 'Schema.org Type Video', $this->text_domain );
		add_meta_box( $this->custom_type, $custom_field_title, array( $this, 'set_custom_fields' ), $this->custom_type, 'normal' );
	}

	/**
	 * Set custom fields.
	 *
	 * @version 4.1.1
	 * @since   3.0.0
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
		$html .= '<table class="schema-admin-custom-post">';
		$html .= '<tr><td><label class="schema-admin-required" for="schema_video_name">';
		$html .= esc_html__( 'Video Name', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_name" . ']" id="schema_video_name" class="regular-text" required value="' . esc_attr( $args['schema_video_name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-required" for="schema_video_description">';
		$html .= esc_html__( 'Video Description', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<textarea name="option[' . "schema_video_description" . ']" id="schema_video_description" class="large-text code" required rows="3">' . esc_attr( $args['schema_video_description'] ) . '</textarea>';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-required" for="schema_video_thumbnail_url">';
		$html .= esc_html__( 'Thumbnail Url', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_thumbnail_url" . ']" id="schema_video_thumbnail_url" class="regular-text" required value="' . esc_attr( $args['schema_video_thumbnail_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-required" for="schema_video_upload_date">';
		$html .= esc_html__( 'Upload Date', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="date" name="option[' . "schema_video_upload_date" . ']" id="schema_video_upload_date" required value="' . esc_attr( $args['schema_video_upload_date'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_video_upload_time" . ']" id="schema_video_upload_time" required value="' . esc_attr( $args['schema_video_upload_time'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-recommended" for="schema_video_duration">';
		$html .= esc_html__( 'duration', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_duration" . ']" id="schema_video_duration" class="regular-text" value="' . esc_attr( $args['schema_video_duration'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-recommended" for="schema_video_content_url">';
		$html .= esc_html__( 'contentURL', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_content_url" . ']" id="schema_video_content_url" class="regular-text" value="' . esc_attr( $args['schema_video_content_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-recommended" for="schema_video_embed_url">';
		$html .= esc_html__( 'embedURL', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_embed_url" . ']" id="schema_video_embed_url" class="regular-text" value="' . esc_attr( $args['schema_video_embed_url'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-recommended" for="schema_video_interaction_count">';
		$html .= esc_html__( 'interactionCount', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="text" name="option[' . "schema_video_interaction_count" . ']" id="schema_video_interaction_count" class="regular-text" value="' . esc_attr( $args['schema_video_interaction_count'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><td><label class="schema-admin-recommended" for="schema_video_expires_date">';
		$html .= esc_html__( 'expires', $this->text_domain );
		$html .= '</label></td><td>';
		$html .= '<input type="date" name="option[' . "schema_video_expires_date" . ']" id="schema_video_expires_date" value="' . esc_attr( $args['schema_video_expires_date'] ) . '">';
		$html .= '<input type="time" name="option[' . "schema_video_expires_time" . ']" id="schema_video_expires_time" value="' . esc_attr( $args['schema_video_expires_time'] ) . '">';
		$html .= '</td></tr>';
		$html .= '</table>';

		echo $html;
	}

	/**
	 * Save custom post.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
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
			'schema_video_name'              => '',
			'schema_video_description'       => '',
			'schema_video_thumbnail_url'     => '',
			'schema_video_upload_date'       => '',
			'schema_video_upload_time'       => '',
			'schema_video_duration'          => '',
			'schema_video_content_url'       => '',
			'schema_video_embed_url'         => '',
			'schema_video_interaction_count' => '',
			'schema_video_expires_date'      => '',
			'schema_video_expires_time'      => '',
		);

		return (array) $args;
	}
}