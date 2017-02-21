<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://github.com/miiitaka/wp-structuring-markup
Description: Allows you to include schema.org JSON-LD syntax markup on your website
Version: 3.2.1
Author: Kazuya Takami
Author URI: https://www.terakoya.work/
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
 * @version 3.2.1
 * @since   1.0.0
 */
class Structuring_Markup {

	/**
	 * Variable definition.
	 *
	 * @version 3.2.1
	 * @since   1.3.0
	 */
	private $text_domain = 'wp-structuring-markup';
	private $version     = '3.2.1';

	/**
	 * Constructor Define.
	 *
	 * @version 2.4.0
	 * @since   1.0.0
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'create_table' ) );

		add_shortcode( $this->text_domain . '-breadcrumb', array( $this, 'short_code_init_breadcrumb' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init',           array( $this, 'create_post_type' ) );

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
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public function create_table() {
		$db = new Structuring_Markup_Admin_Db();
		$db->create_table( $this->text_domain, $this->version );
	}

	/**
	 * Breadcrumb ShortCode Register.
	 *
	 * @version 2.3.1
	 * @since   2.0.0
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
		} else {
			return __return_false();
		}
	}

	/**
	 * i18n.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	public function plugins_loaded () {
		load_plugin_textdomain( $this->text_domain, __return_false(), dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Create custom post type.
	 *
	 * @version 3.0.0
	 * @since   2.1.0
	 */
	function create_post_type () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-custom-post-event.php' );
		new Structuring_Markup_Custom_Post_Event( $this->text_domain );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-custom-post-video.php' );
		new Structuring_Markup_Custom_Post_Video( $this->text_domain );
	}

	/**
	 * admin init.
	 *
	 * @version 3.0.5
	 * @since   1.3.1
	 */
	public function admin_init () {
		/** version up check */
		$options = get_option( $this->text_domain );
		if ( !isset( $options['version'] ) || $options['version'] !== $this->version ) {
			$this->create_table();
		}

		wp_register_style( 'wp-structuring-markup-admin-style', plugins_url( 'css/style.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * admin_scripts
	 *
	 * @author  Justin Frydman
	 * @author  Kazuya Takami
	 * @version 3.0.5
	 * @since   2.4.0
	 */
	public function admin_scripts () {
		if ( isset( $_GET["type"] ) && $_GET["type"] === 'local_business' ) {
			wp_enqueue_script ( 'wp-structuring-markup-admin-main-js', plugins_url ( 'js/main.min.js', __FILE__ ), array( 'jquery' ), $this->version );
		}
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @version 3.1.6
	 * @since   1.0.0
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
		add_action( 'admin_print_styles-'  . $list_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_styles-'  . $post_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_scripts-' . $post_page, array( $this, 'admin_scripts' ) );
	}

	/**
	 * CSS admin add.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 */
	public function add_style () {
		wp_enqueue_style( 'wp-structuring-markup-admin-style' );
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	public function list_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-list.php' );
		new Structuring_Markup_Admin_List( $this->text_domain );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	public function post_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-admin-post.php' );
		new Structuring_Markup_Admin_Post( $this->text_domain );
	}

	/**
	 * Display Page Template Require.
	 *
	 * @version 3.2.1
	 * @since   1.3.0
	 */
	public function wp_head () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-opening-hours.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-display.php' );
		new Structuring_Markup_Display( $this->version );
	}
}