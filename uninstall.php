<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
new Structuring_Markup_Uninstall();

/**
 * Schema.org Plugin Uninstall
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup_Uninstall {

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 */
	function __construct() {
		$this->drop_table();
		delete_option( 'wp_structuring_markup' );
	}

	/**
	 * Drop Table.
	 *
	 * @since 1.0.0
	 */
	private function drop_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . "structuring_markup";
		$wpdb->query( "DROP TABLE IF EXISTS " . $table_name );
	}
}