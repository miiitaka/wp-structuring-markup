<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
new Structuring_Markup_Uninstall();

/**
 * Schema.org Plugin Uninstall
 *
 * @author  Kazuya Takami
 * @version 4.5.0
 * @since   1.0.0
 */
class Structuring_Markup_Uninstall {

	/**
	 * Variable definition.
	 *
	 * @version 4.5.0
	 * @since   2.1.0
	 */
	private $text_domain       = 'wp-structuring-markup';
	private $custom_type_event = 'schema_event_post';
	private $custom_type_video = 'schema_video_post';

	/**
	 * Constructor Define.
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->drop_table( 'structuring_markup' );
		$this->delete_custom_post( $this->custom_type_event );
		$this->delete_post_meta( $this->custom_type_event );
		$this->delete_custom_post( $this->custom_type_video );
		$this->delete_post_meta( $this->custom_type_video );
		delete_option( $this->text_domain );
	}

	/**
	 * Drop Table.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 * @param   string $table_name
	 */
	private function drop_table ( $table_name = null ) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		$wpdb->query( "DROP TABLE IF EXISTS " . $table_name );
	}

	/**
	 * Delete custom post.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 * @param   string $custom_type
	 */
	private function delete_custom_post ( $custom_type = null ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "posts";

		$key = array( 'post_type' => $custom_type );
		$key_prepared = array( '%s' );

		$wpdb->delete( $table_name, $key, $key_prepared );
	}

	/**
	 * Delete post meta.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 * @param   string $custom_type
	 */
	private function delete_post_meta ( $custom_type = null ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "postmeta";

		$key = array( 'meta_key' => $custom_type );
		$key_prepared = array( '%s' );

		$wpdb->delete( $table_name, $key, $key_prepared );
	}
}