<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://wordpress.org/plugins/wp-structuring-markup/
Description: It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an article or the fixed page.
Version: 1.3.2
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
 * @version 1.3.2
 */
class Structuring_Markup {

	/**
	 * Variable definition.
	 *
	 * @since 1.3.0
	 */
	private $text_domain = 'wp-structuring-markup';

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		$db = new Structuring_Markup_Admin_Db();
		$db->create_table();

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		} else {
			add_action( 'wp_head', array( $this, 'wp_head' ) );
		}
	}

	/**
	 * admin init.
	 *
	 * @since   1.3.1
	 * @version 1.3.1
	 */
	public function admin_init() {
		wp_register_style( 'wp-structuring-markup-admin-style', plugins_url( 'css/style.css', __FILE__ ) );
	}

	/**
	 * i18n.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function plugins_loaded() {
		load_plugin_textdomain(
			$this->text_domain,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 */
	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Scheme.org Settings', $this->text_domain ),
			esc_html__( 'Scheme.org Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		add_submenu_page(
			__FILE__,
			esc_html__( 'All Settings', $this->text_domain ),
			esc_html__( 'All Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		$page = add_submenu_page(
			__FILE__,
			esc_html__( 'Scheme.org Setting Post', $this->text_domain ),
			esc_html__( 'Add New', $this->text_domain ),
			'manage_options',
			plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-post.php',
			array( $this, 'post_page_render' )
		);

		/** Using registered $page handle to hook stylesheet loading */
		add_action( 'admin_print_styles-' . $page, array( $this, 'add_style' ) );
	}

	/**
	 * CSS admin add.
	 *
	 * @since   1.3.1
	 * @version 1.3.1
	 */
	public function add_style() {
		wp_enqueue_style( 'wp-structuring-markup-admin-style' );
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function list_page_render() {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-list.php' );
		new Structuring_Markup_Admin_List( $this->text_domain );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 */
	public function post_page_render() {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-post.php' );
		new Structuring_Markup_Admin_Post( $this->text_domain );
	}

	/**
	 * Display Page Template Require.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function wp_head() {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-display.php' );
		new Structuring_Markup_Display();
	}
}