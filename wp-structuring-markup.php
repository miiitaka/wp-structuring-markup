<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://wordpress.org/plugins/wp-structuring-markup/
Description: It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an article or the fixed page.
Version: 2.0.1
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
 * @version 2.0.0
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
	 * @version 2.0.0
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'create_table' ) );
		add_shortcode( $this->text_domain . '-breadcrumb', array( $this, 'short_code_init_breadcrumb' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		} else {
			add_action( 'wp_head', array( $this, 'wp_head' ) );
		}
	}

	/**
	 * Create table.
	 *
	 * @since 2.0.0
	 */
	public function create_table() {
		$db = new Structuring_Markup_Admin_Db();
		$db->create_table();
	}

	/**
	 * Breadcrumb ShortCode Register.
	 *
	 * @since  2.0.0
	 * @return string $html
	 */
	public function short_code_init_breadcrumb () {
		/** DB Connect */
		$db = new Structuring_Markup_Admin_Db();
		$results = $db->get_type_options( 'breadcrumb' );
		$options = $results['option'];

		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-short-code-breadcrumb.php' );
		$obj = new Structuring_Markup_ShortCode_Breadcrumb();
		return $obj->short_code_display( $options );
	}

	/**
	 * i18n.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( $this->text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
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
	 * Add Menu to the Admin Screen.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 */
	public function admin_menu() {
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