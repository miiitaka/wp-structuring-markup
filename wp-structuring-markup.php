<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://wordpress.org/plugins/wp-structuring-markup/
Description: Allows you to include schema.org JSON-LD syntax markup on your website
Version: 2.5.1
Author: Kazuya Takami
Author URI: http://programp.com/
License: GPLv2 or later
Text Domain: wp-structuring-markup
Domain Path: /languages
*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-db.php' );

new Structuring_Markup();

/**
 * Schema.org Basic Class
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 2.5.1
 */
class Structuring_Markup {

	/**
	 * Variable definition.
	 *
	 * @since   1.3.0
	 * @version 2.5.1
	 */
	private $text_domain = 'wp-structuring-markup';
	private $version     = '2.5.1';

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.4.0
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'create_table' ) );

		add_shortcode( $this->text_domain . '-breadcrumb', array( $this, 'short_code_init_breadcrumb' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init',           array( $this, 'create_post_type_event' ) );

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts') );
		} else {
			add_action( 'wp_head', array( $this, 'wp_head' ) );
		}
	}

	/**
	 * Create table.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function create_table() {
		$db = new Structuring_Markup_Admin_Db();
		$db->create_table( $this->text_domain, $this->version );
	}

	/**
	 * Breadcrumb ShortCode Register.
	 *
	 * @since   2.0.0
	 * @version 2.3.1
	 * @param   string $args short code params
	 * @return  string $html
	 */
	public function short_code_init_breadcrumb ( $args ) {
		$db = new Structuring_Markup_Admin_Db();
		$results = $db->get_type_options( 'breadcrumb' );

		if ( isset( $results['option'] ) ) {
			$options = $results['option'];

			require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-short-code-breadcrumb.php');
			$obj = new Structuring_Markup_ShortCode_Breadcrumb();
			return $obj->short_code_display( $options, $args );
		}
	}

	/**
	 * i18n.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function plugins_loaded () {
		load_plugin_textdomain( $this->text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Create custom post type "event".
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	function create_post_type_event () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-custom-post-event.php' );
		new Structuring_Markup_Custom_Post_Event( $this->text_domain );
	}

	/**
	 * admin init.
	 *
	 * @since   1.3.1
	 * @version 2.1.1
	 */
	public function admin_init () {
		/** version up check */
		$options = get_option( $this->text_domain );
		if ( !isset( $options['version'] ) || $options['version'] !== $this->version ) {
			$this->create_table();
		}

		wp_register_style( 'wp-structuring-markup-admin-style', plugins_url( 'css/style.css', __FILE__ ) );
	}

	/**
	 * admin_scripts
	 *
	 * @since 2.4.0
	 * @version 2.4.0
	 * @author Justin Frydman
	 */
	public function admin_scripts () {
		wp_enqueue_script( 'wp-structuring-markup-admin-main-js', plugins_url( 'js/main.min.js', __FILE__ ), array('jquery'), '1.0' );
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 */
	public function admin_menu () {
		$list_page = add_menu_page(
			esc_html__( 'Schema.org Settings', $this->text_domain ),
			esc_html__( 'Schema.org Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		$post_page = add_submenu_page(
			$this->text_domain . '-post',
			esc_html__( 'Schema.org Setting Edit', $this->text_domain ),
			esc_html__( 'Edit', $this->text_domain ),
			'manage_options',
			$this->text_domain . '-post',
			array( $this, 'post_page_render' )
		);

		/** Using registered $page handle to hook stylesheet loading */
		add_action( 'admin_print_styles-' . $list_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_styles-' . $post_page, array( $this, 'add_style' ) );

		/** Custom post menu controls */
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->text_domain . '-post' && !empty( $_POST ) ) {
			if ( isset( $_POST['activate'] ) && $_POST['activate'] === 'on' ) {
				flush_rewrite_rules();
			}
			if ( !isset( $_POST['activate'] ) ) {
				remove_menu_page('edit.php?post_type=schema_event_post');
			}
		} else {
			/** DB Connect */
			$db = new Structuring_Markup_Admin_Db();
			$results = $db->get_type_options('event');

			if ( !isset( $results['activate'] ) || $results['activate'] !== 'on' ) {
				remove_menu_page( 'edit.php?post_type=schema_event_post' );
			}
		}
	}

	/**
	 * CSS admin add.
	 *
	 * @since   1.3.1
	 * @version 1.3.1
	 */
	public function add_style () {
		wp_enqueue_style( 'wp-structuring-markup-admin-style' );
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function list_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-list.php' );
		new Structuring_Markup_Admin_List( $this->text_domain );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function post_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-post.php' );
		new Structuring_Markup_Admin_Post( $this->text_domain );
	}

	/**
	 * Display Page Template Require.
	 *
	 * @since   1.3.0
	 * @version 2.4.2
	 */
	public function wp_head () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-cache.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-opening-hours.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-display.php' );
		new Structuring_Markup_Display();
	}
}