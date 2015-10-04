<?php
define( 'WP_STRUCTURING_MARKUP_DB', 'structuring_markup' );

/**
 * Schema.org Admin DB Connection
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Db {
	private $table_name;

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . WP_STRUCTURING_MARKUP_DB;
	}

	/**
	 * Create Table.
	 *
	 * @since 1.0.0
	 */
	public function  create_table() {
		global $wpdb;

		$prepared     = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name );
		$is_db_exists = $wpdb->get_var( $prepared );

		if ( is_null( $is_db_exists ) ) {
			$charset_collate = $wpdb->get_charset_collate();

			$query  = " CREATE TABLE " . $this->table_name;
			$query .= " (id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY";
			$query .= ",type tinytext NOT NULL";
			$query .= ",output text NOT NULL";
			$query .= ",options text NOT NULL";
			$query .= ",register_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",UNIQUE KEY id (id)) " . $charset_collate;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $query );
		}
	}

	/**
	 * Get Data.
	 *
	 * @since  1.0.0
	 * @param  integer $id
	 * @return array results
	 */
	public function get_options( $id ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE id = %d";
		$data     = array( $id );
		$prepared = $wpdb->prepare( $query, $data );
		$args     = $wpdb->get_row( $prepared );
		$results  = array();

		if ( $args ) {
			$results['id']   = $args->id;
			$results['type'] = $args->type;
			$results = array_merge( $results, unserialize( $args->options ) );
		}
		return $results;
	}

	/**
	 * Get All Data.
	 *
	 * @since  1.0.0
	 * @return array results
	 */
	public function getAll_options() {
		global $wpdb;

		$query = "SELECT * FROM " . $this->table_name . " ORDER BY update_date DESC";

		return $wpdb->get_results( $query );
	}

	/**
	 * Insert Data.
	 *
	 * @since  1.0.0
	 * @param  array $post($_POST)
	 * @param  array $args
	 * @return integer $id
	 */
	public function insert_options( $post, $args ) {
		global $wpdb;

		$data = array(
			'type'          => $post['type'],
			'output'        => serialize( $post['output'] ),
			'options'       => serialize( $args ),
			'register_date' => date( "Y-m-d H:i:s" )
		);
		$prepared = array( '%s', '%s', '%s' );

		$wpdb->insert( $this->table_name, $data, $prepared );

		return $wpdb->insert_id;
	}

	/**
	 * Update Data.
	 *
	 * @since 1.0.0
	 * @param array $post($_POST)
	 * @param array $args
	 */
	public function update_options( $post, $args ) {
		global $wpdb;

		$data = array(
			'type'        => $post['type'],
			'output'      => serialize( $post['output'] ),
			'options'     => serialize( $args ),
			'update_date' => date( "Y-m-d H:i:s" )
		);
		$key = array( 'id' => $post['id'] );
		$prepared = array( '%s', '%s', '%s' );
		$key_prepared = array( '%d' );

		$wpdb->update( $this->table_name, $data, $key, $prepared, $key_prepared );
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	public function information_render() {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Schema.org Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Error Message Render
	 *
	 * @since 1.0.0
	 */
	public function error_render() {
		$html  = '<div id="notice" class="notice notice-error is-dismissible below-h2">';
		$html .= '<p>Output No Select.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}