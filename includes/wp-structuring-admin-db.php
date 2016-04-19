<?php
/**
 * Schema.org Admin DB Connection.
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 2.5.0
 */
class Structuring_Markup_Admin_Db {

	/**
	 * Variable definition.
	 *
	 * @since   1.0.0
	 * @version 2.4.0
	 */
	private $table_name;

	/** Schema.org Type defined. */
	public $type_array = array(
		"article"        => "Article",
		"blog_posting"   => "Blog Posting",
		"breadcrumb"     => "Breadcrumb",
		"event"          => "Event",
		"local_business" => "Local Business",
		"news_article"   => "News Article",
		"organization"   => "Organization",
		"person"         => "Person",
		"website"        => "Web Site"
	);

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 */
	public function __construct () {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'structuring_markup';
	}

	/**
	 * Create Table.
	 *
	 * @since   1.0.0
	 * @version 2.1.1
	 * @param   string $text_domain
	 * @param   string $version
	 */
	public function create_table ( $text_domain, $version ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$prepared        = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name );
		$is_db_exists    = $wpdb->get_var( $prepared );
		$charset_collate = $wpdb->get_charset_collate();

		if ( is_null( $is_db_exists ) ) {
			$this->create_table_execute( $charset_collate, $text_domain, $version );

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
			 * version up process.
			 *
			 * @since   2.0.0
			 * @version 2.1.1
			 * */
			$options = get_option( $text_domain );

			if ( !isset( $options['version'] ) || $options['version'] !== $version ) {
				$lists = $this->get_list_options();

				$wpdb->query( "DROP TABLE " . $this->table_name );
				$this->create_table_execute( $charset_collate, $text_domain, $version );

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
							$activate = isset( $list->activate ) ? $list->activate : "";

							$args = array(
								'type'          => $key,
								'activate'      => $activate,
								'output'        => $list->output,
								'options'       => $list->options,
								'register_date' => date( "Y-m-d H:i:s" ),
								'update_date'   => date( "Y-m-d H:i:s" )
							);

							// LocalBusiness Convert data(In the case of version 2.3.x)
							if ( $key === 'local_business' && $activate === 'on' && strpos( $options['version'], '2.3.' ) !== false ) {
								$args['options'] = $this->convert_local_business( $list->options );
							}
						}
					}
					$this->insert_options( $args );
				}
			}
		}
	}

	/**
	 * To convert the data for the new version of the "LocalBusiness".
	 * version 2.3.3 -> 2.4.0
	 *
	 * @since   2.4.0
	 * @param   string $options
	 * @return  string $convert
	 */
	private function convert_local_business( $options ) {
		$options = unserialize( $options );
		$convert = $options;

		$convert['Mo']                     = isset( $options['mon'] )       ? $options['mon']       : "";
		$convert['week']['Mo'][0]['open']  = isset( $options['mon-open'] )  ? $options['mon-open']  : "";
		$convert['week']['Mo'][0]['close'] = isset( $options['mon-close'] ) ? $options['mon-close'] : "";
		$convert['Tu']                     = isset( $options['tue'] )       ? $options['tue']       : "";
		$convert['week']['Tu'][0]['open']  = isset( $options['tue-open'] )  ? $options['tue-open']  : "";
		$convert['week']['Tu'][0]['close'] = isset( $options['tue-close'] ) ? $options['tue-close'] : "";
		$convert['We']                     = isset( $options['wed'] )       ? $options['wed']       : "";
		$convert['week']['We'][0]['open']  = isset( $options['wed-open'] )  ? $options['wed-open']  : "";
		$convert['week']['We'][0]['close'] = isset( $options['wed-close'] ) ? $options['wed-close'] : "";
		$convert['Th']                     = isset( $options['thu'] )       ? $options['mon']       : "";
		$convert['week']['Th'][0]['open']  = isset( $options['thu-open'] )  ? $options['thu-open']  : "";
		$convert['week']['Th'][0]['close'] = isset( $options['thu-close'] ) ? $options['thu-close'] : "";
		$convert['Fr']                     = isset( $options['fri'] )       ? $options['fri']       : "";
		$convert['week']['Fr'][0]['open']  = isset( $options['fri-open'] )  ? $options['fri-open']  : "";
		$convert['week']['Fr'][0]['close'] = isset( $options['fri-close'] ) ? $options['fri-close'] : "";
		$convert['Sa']                     = isset( $options['sat'] )       ? $options['sat']       : "";
		$convert['week']['Sa'][0]['open']  = isset( $options['sat-open'] )  ? $options['sat-open']  : "";
		$convert['week']['Sa'][0]['close'] = isset( $options['sat-close'] ) ? $options['sat-close'] : "";
		$convert['Su']                     = isset( $options['sun'] )       ? $options['sun']       : "";
		$convert['week']['Su'][0]['open']  = isset( $options['sun-open'] )  ? $options['sun-open']  : "";
		$convert['week']['Su'][0]['close'] = isset( $options['sun-close'] ) ? $options['sun-close'] : "";

		return (string) serialize( $convert );
	}

	/**
	 * Create table execute
	 *
	 * @since   2.0.0
	 * @version 2.1.1
	 * @param   string $charset_collate
	 * @param   string $text_domain
	 * @param   string $version
	 */
	private function create_table_execute ( $charset_collate, $text_domain, $version ) {
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

		$options = array( 'version' => $version );
		update_option( $text_domain, $options, 'yes' );
	}

	/**
	 * Get Data.
	 *
	 * @since   1.0.0
	 * @version 2.1.0
	 * @param   integer $id
	 * @return  array   $results
	 */
	public function get_options ( $id ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE id = %d";
		$data     = array( esc_html( $id ) );
		$prepared = $wpdb->prepare( $query, $data );
		$args     = $wpdb->get_row( $prepared );
		$results  = array();

		if ( $args ) {
			$results['id']       = $args->id;
			$results['activate'] = isset( $args->activate ) ? $args->activate : "";
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
	public function get_list_options () {
		global $wpdb;

		$query = "SELECT * FROM " . $this->table_name . " ORDER BY type ASC";

		return (array) $wpdb->get_results( $query );
	}

	/**
	 * Get Select Data.
	 *
	 * @since   1.0.0
	 * @version 2.5.0
	 * @param   array $output
	 * @return  array $results
	 */
	public function get_select_options ( $output ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE output LIKE '%%\"%s\"%%'";
		$data     = array( $output );
		$prepared = $wpdb->prepare( $query, $data );
		$results  = $wpdb->get_results( $prepared );

		return (array) $results;
	}

	/**
	 * Get Type Data.
	 *
	 * @since   2.0.0
	 * @version 2.1.0
	 * @param   string $type
	 * @return  array  $results
	 */
	public function get_type_options ( $type ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE type = %s";
		$data     = array( esc_html( $type ) );
		$prepared = $wpdb->prepare( $query, $data );
		$args     = $wpdb->get_row( $prepared );
		$results  = array();

		if ( $args ) {
			$results['id']       = $args->id;
			$results['activate'] = isset( $args->activate ) ? $args->activate : "";
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
	private function insert_options ( array $args ) {
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
	public function update_options ( array $post ) {
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