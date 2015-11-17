<?php
/**
 * Schema.org Admin DB Connection
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 1.0.0
 */
class Structuring_Markup_Admin_Db {
	private $table_name;

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'structuring_markup';
	}

	/**
	 * Create Table.
	 *
	 * @since 1.0.0
	 */
	public function create_table() {
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
	 * @since   1.0.0
	 * @version 1.3.2
	 * @param   integer $id
	 * @return  array   $results
	 */
	public function get_options( $id ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE id = %d";
		$data     = array( esc_html( $id ) );
		$prepared = $wpdb->prepare( $query, $data );
		$args     = $wpdb->get_row( $prepared );
		$results  = array();

		if ( $args ) {
			$results['id']     = $args->id;
			$results['type']   = $args->type;
			$results['output'] = unserialize( $args->output );
			$results['option'] = unserialize( $args->options );
		}
		return (array) $results;
	}

	/**
	 * Get All Data.
	 *
	 * @since  1.0.0
	 * @return array $results
	 */
	public function get_list_options() {
		global $wpdb;

		$query = "SELECT * FROM " . $this->table_name . " ORDER BY update_date DESC";

		return (array) $wpdb->get_results( $query );
	}

	/**
	 * Get Select Data.
	 *
	 * @since  1.0.0
	 * @param  array $output
	 * @return array $results
	 */
	public function get_select_options( $output ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE output LIKE '%%%s%%'";
		$data     = array( $output );
		$prepared = $wpdb->prepare( $query, $data );
		$results  = $wpdb->get_results( $prepared );

		return (array) $results;
	}

	/**
	 * Insert Data.
	 *
	 * @since  1.0.0
	 * @param  array $post($_POST)
	 * @return integer $id
	 */
	public function insert_options( array $post ) {
		global $wpdb;

		$data = array(
			'type'          => $post['type'],
			'output'        => serialize( $post['output'] ),
			'options'       => isset( $post['option'] ) ? serialize( $post['option'] ) : "",
			'register_date' => date( "Y-m-d H:i:s" ),
			'update_date'   => date( "Y-m-d H:i:s" )
		);
		$prepared = array( '%s', '%s', '%s' );

		$wpdb->insert( $this->table_name, $data, $prepared );

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update Data.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 * @param   array $post($_POST)
	 */
	public function update_options( array $post ) {
		global $wpdb;

		$data = array(
			'type'        => $post['type'],
			'output'      => serialize( $post['output'] ),
			'options'     => isset( $post['option'] ) ? serialize( $post['option'] ) : "",
			'update_date' => date( "Y-m-d H:i:s" )
		);
		$key = array( 'id' => esc_html( $post['id'] ) );
		$prepared = array( '%s', '%s', '%s' );
		$key_prepared = array( '%d' );

		$wpdb->update( $this->table_name, $data, $key, $prepared, $key_prepared );
	}

	/**
	 * Delete Data.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 * @param   integer $id
	 */
	public function delete_options( $id ) {
		global $wpdb;

		$key = array( 'id' => esc_html( $id ) );
		$key_prepared = array( '%d' );

		$wpdb->delete( $this->table_name, $key, $key_prepared );
	}
}