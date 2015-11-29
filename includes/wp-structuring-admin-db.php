<?php
/**
 * Schema.org Admin DB Connection
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 2.0.0
 */
class Structuring_Markup_Admin_Db {

	/**
	 * Variable definition.
	 *
	 * @since   1.0.0
	 * @version 2.1.0
	 */
	private $table_name;

	/** Schema.org Type defined. */
	public $type_array = array(
		"article"      => "Article",
		"blog_posting" => "Blog Posting",
		"breadcrumb"   => "Breadcrumb",
		"event"        => "Event",
		"news_article" => "News Article",
		"organization" => "Organization",
		"website"      => "Web Site"
	);

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
	 * @since   1.0.0
	 * @version 2.1.0
	 */
	public function create_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$prepared        = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name );
		$is_db_exists    = $wpdb->get_var( $prepared );
		$charset_collate = $wpdb->get_charset_collate();

		if ( is_null( $is_db_exists ) ) {
			$this->create_table_execute( $charset_collate );

			foreach ( $this->type_array as $key => $value ) {
				$args = array(
					'type'          => $key,
					'activate'      => "",
					'output'        => serialize( array() ),
					'options'       => serialize( array() ),
					'register_date' => date( "Y-m-d H:i:s" ),
					'update_date'   => date( "Y-m-d H:i:s" )
				);
				$this->insert_options( $args );
			}
		} else {
			/**
			 * version 1.x.x -> 2.1.0 migration process.
			 *
			 * @since   2.0.0
			 * @version 2.1.0
			 * */
			$options = get_option( 'wp_structuring_markup' );
			if ( !isset( $options['version'] ) || $options['version'] !== '2.0.0' ) {
				$lists = $this->get_list_options();

				$wpdb->query( "DROP TABLE " . $this->table_name );
				$this->create_table_execute( $charset_collate );

				foreach ( $this->type_array as $key => $value ) {
					$args = array(
						'type'          => $key,
						'activate'      => "",
						'output'        => serialize( array() ),
						'options'       => serialize( array() ),
						'register_date' => date( "Y-m-d H:i:s" ),
						'update_date'   => date( "Y-m-d H:i:s" )
					);
					foreach ( $lists as $list ) {
						if ( $list->type === $key ) {
							$args = array(
								'type'          => $key,
								'activate'      => "on",
								'output'        => $list->output,
								'options'       => $list->options,
								'register_date' => date( "Y-m-d H:i:s" ),
								'update_date'   => date( "Y-m-d H:i:s" )
							);
						}
					}
					$this->insert_options( $args );
				}
			}
		}
	}

	/**
	 * Create table execute
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 * @param   string $charset_collate
	 */
	private function create_table_execute( $charset_collate ) {
		$query  = " CREATE TABLE " . $this->table_name;
		$query .= " (id MEDIUMINT(9) NOT NULL AUTO_INCREMENT PRIMARY KEY";
		$query .= ",type TINYTEXT NOT NULL";
		$query .= ",activate TINYTEXT NOT NULL";
		$query .= ",output TEXT NOT NULL";
		$query .= ",options TEXT NOT NULL";
		$query .= ",register_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL";
		$query .= ",update_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL";
		$query .= ",UNIQUE KEY id (id)) " . $charset_collate;

		dbDelta( $query );

		$options = array( 'version' => '2.1.0' );
		add_option( 'wp_structuring_markup', $options, false, 'yes' );
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
			$results['id']       = $args->id;
			$results['activate'] = $args->activate;
			$results['type']     = $args->type;
			$results['output']   = unserialize( $args->output );
			$results['option']   = unserialize( $args->options );
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

		$query = "SELECT * FROM " . $this->table_name . " ORDER BY type ASC";

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
	 * Get Type Data.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 * @param   string $type
	 * @return  array  $results
	 */
	public function get_type_options( $type ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE type = %s";
		$data     = array( esc_html( $type ) );
		$prepared = $wpdb->prepare( $query, $data );
		$args     = $wpdb->get_row( $prepared );
		$results  = array();

		if ( $args ) {
			$results['id']       = $args->id;
			$results['activate'] = $args->activate;
			$results['type']     = $args->type;
			$results['output']   = unserialize( $args->output );
			$results['option']   = unserialize( $args->options );
		}
		return (array) $results;
	}

	/**
	 * Insert Data.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array  $args
	 */
	private function insert_options( array $args ) {
		global $wpdb;

		$prepared = array( '%s', '%s', '%s', '%s', '%s', '%s' );
		$wpdb->insert( $this->table_name, $args, $prepared );
	}

	/**
	 * Update Data.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $post($_POST)
	 * @return  integer $post['id']
	 */
	public function update_options( array $post ) {
		global $wpdb;

		$data = array(
			'type'        => $post['type'],
			'activate'    => isset( $post['activate'] ) ? $post['activate'] : "",
			'output'      => serialize( $post['output'] ),
			'options'     => isset( $post['option'] ) ? serialize( $post['option'] ) : "",
			'update_date' => date( "Y-m-d H:i:s" )
		);
		$key = array( 'id' => esc_html( $post['id'] ) );
		$prepared = array( '%s', '%s', '%s', '%s', '%s' );
		$key_prepared = array( '%d' );

		$wpdb->update( $this->table_name, $data, $key, $prepared, $key_prepared );
		return (integer) $post['id'];
	}
}