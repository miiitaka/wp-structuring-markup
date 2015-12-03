<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
new Structuring_Markup_Uninstall();

/**
 * Schema.org Plugin Uninstall
 *
 * @author  Kazuya Takami
 * @version 2.1.0
 * @since   1.0.0
 */
class Structuring_Markup_Uninstall {

	/**
	 * Variable definition.
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	private $custom_type = 'schema_event_post';

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 */
	public function __construct () {
		$this->drop_table( 'structuring_markup' );
		$this->delete_custom_post( $this->custom_type );
		$this->delete_post_meta( $this->custom_type );
		delete_option( 'wp_structuring_markup' );
	}

	/**
	 * Drop Table.
	 *
	 * @since   1.0.0
	 * @version 2.1.0
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
	 * @since   2.1.0
	 * @version 2.1.0
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
	 * @since   2.1.0
	 * @version 2.1.0
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