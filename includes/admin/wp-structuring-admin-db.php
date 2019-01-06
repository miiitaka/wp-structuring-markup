<?php
/**
 * Schema.org Admin DB Connection.
 *
 * @author  Kazuya Takami
 * @version 4.6.2
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Db {

	/**
	 * Variable definition.
	 *
	 * @version 3.1.0
	 * @since   1.0.0
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
		"site_navigation"=> "Site Navigation",
		"video"          => "Video",
		"website"        => "Web Site"
	);

	/**
	 * Constructor Define.
	 *
	 * @version 1.3.2
	 * @since   1.0.0
	 */
	public function __construct () {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'structuring_markup';
	}

	/**
	 * Create Table.
	 *
	 * @version 4.3.0
	 * @since   1.0.0
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
			 * @version 4.4.0
			 * @since   2.0.0
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

							// version up default value setting.
							if ( $version >= '4.3.0' && $key === 'breadcrumb' ) {
								$works = unserialize( $list->options );
								$works['current_link'] = 'on';
								$list->options = serialize( $works );
							}

							$args = array(
								'type'          => $key,
								'activate'      => $activate,
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
	 * @version 4.6.2
	 * @since   2.0.0
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

		$args = array( 'version' => $version );
		$options = get_option( $text_domain );

		if ( $options ) {
			$options = array_merge( $options, $args );
		} else {
			$options = $args;
		}
		update_option( $text_domain, $options );
	}

	/**
	 * Get Data.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
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
	 * Get Type Data.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
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
	 * @version 2.0.0
	 * @since   1.0.0
	 * @param   array $args
	 */
	private function insert_options ( array $args ) {
		global $wpdb;

		$prepared = array( '%s', '%s', '%s', '%s', '%s', '%s' );
		$wpdb->insert( $this->table_name, $args, $prepared );
	}

	/**
	 * Update Data.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @param   array   $post($_POST)
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

	/**
	 * Update Config Data.
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 * @param   array   $post
	 * @param   string  $text_domain
	 * @return  boolean
	 */
	public function update_config ( array $post, $text_domain ) {
		$options = get_option( $text_domain );

		if ( !$options ) {
			return __return_false();
		} else {
			$args = array(
				'compress' => isset( $post['compress'] ) ? $post['compress'] : ''
			);
			$options = array_merge( $options, $args );
			update_option( $text_domain, $options );
			return __return_true();
		}
	}
}