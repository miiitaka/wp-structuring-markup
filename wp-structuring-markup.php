<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://github.com/miiitaka/wp-structuring-markup
Description: Allows you to include schema.org JSON-LD syntax markup on your website
Version: 4.8.1
Author: Kazuya Takami
Author URI: https://www.terakoya.work/
License: GPLv2 or later
Text Domain: wp-structuring-markup
Domain Path: /languages
*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/wp-structuring-admin-db.php' );

new Structuring_Markup();

/**
 * Schema.org Basic Class
 *
 * @author  Kazuya Takami
 * @version 4.8.1
 * @since   1.0.0
 */
class Structuring_Markup {

	/**
	 * Variable definition version.
	 *
	 * @version 4.8.1
	 * @since   1.3.0
	 */
	private $version = '4.8.1';

	/**
	 * Variable definition Text Domain.
	 *
	 * @version 3.2.5
	 * @since   1.3.0
	 */
	private $text_domain = 'wp-structuring-markup';

	/**
	 * Constructor Define.
	 *
	 * @version 4.1.1
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
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		} else {
			add_action( 'wp_head', array( $this, 'wp_head' ) );
			add_filter( 'amp_post_template_metadata', array( $this, 'amp_post_template_metadata' ), 9 );
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
	 * @version 4.0.0
	 * @since   2.1.0
	 */
	function create_post_type () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/custom/wp-structuring-custom-post-event.php' );
		new Structuring_Markup_Custom_Post_Event( $this->text_domain );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/custom/wp-structuring-custom-post-video.php' );
		new Structuring_Markup_Custom_Post_Video( $this->text_domain );
	}

	/**
	 * admin init.
	 *
	 * @version 4.1.1
	 * @since   1.3.1
	 */
	public function admin_init () {
		/** version up check */
		$options = get_option( $this->text_domain );
		if ( !isset( $options['version'] ) || $options['version'] !== $this->version ) {
			$this->create_table();
		}

		wp_register_style( 'wp-structuring-markup-admin-style', plugins_url( 'css/style.css', __FILE__ ), array(), $this->version );
		wp_register_style( 'wp-structuring-markup-admin-post',  plugins_url( 'css/schema-custom-post.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 */
	public function admin_menu () {
		add_menu_page(
			esc_html__( 'Schema.org Settings', $this->text_domain ),
			esc_html__( 'Schema.org Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		$list_page = add_submenu_page(
			__FILE__,
			esc_html__( 'Schema.org List', $this->text_domain ),
			esc_html__( 'Schema.org List', $this->text_domain ),
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
		$config_page = add_submenu_page(
			__FILE__,
			esc_html__( 'Schema.org Config', $this->text_domain ),
			esc_html__( 'Schema.org Config', $this->text_domain ),
			'manage_options',
			$this->text_domain . '-config',
			array( $this, 'config_page_render' )
		);

		/** Using registered $page handle to hook stylesheet loading */
		add_action( 'admin_print_styles-post.php',         array( $this, 'add_style_post' ) );
		add_action( 'admin_print_styles-'  . $list_page,   array( $this, 'add_style' ) );
		add_action( 'admin_print_styles-'  . $post_page,   array( $this, 'add_style' ) );
		add_action( 'admin_print_styles-'  . $config_page, array( $this, 'add_style' ) );
		add_action( 'admin_print_scripts-' . $post_page,   array( $this, 'admin_scripts' ) );
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @version 4.1.1
	 * @since   4.1.1
	 * @param   array  $links
	 * @return  array  $links
	 */
	public function plugin_action_links( $links ) {
		$url = admin_url( 'admin.php?page=' . $this->text_domain . '/' . $this->text_domain . '.php' );
		$url = '<a href="' . esc_url( $url ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $url );
		return $links;
	}

	/**
	 * CSS admin add. (Custom Post)
	 *
	 * @version 4.1.1
	 * @since   4.1.1
	 */
	public function add_style_post () {
		wp_enqueue_style( 'wp-structuring-markup-admin-post' );
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
	 * admin_scripts
	 *
	 * @author  Justin Frydman
	 * @author  Kazuya Takami
	 * @version 3.2.2
	 * @since   2.4.0
	 */
	public function admin_scripts () {
		if ( isset( $_GET["type"] ) && $_GET["type"] === 'local_business' ) {
			wp_enqueue_script ( 'wp-structuring-markup-admin-main-js', plugins_url ( 'js/main.min.js', __FILE__ ), array( 'jquery' ), $this->version );
		}
		if ( isset( $_GET["type"] ) ) {
			switch ( $_GET["type"] ) {
				case "article":
				case "blog_posting":
				case "news_article":
				case "organization":
					wp_enqueue_script ( 'wp-structuring-markup-admin-media-js', plugins_url ( 'js/media-uploader-main.js', __FILE__ ), array( 'jquery' ), $this->version );
					wp_enqueue_media();
					break;
			}
		}
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @version 4.0.0
	 * @since   1.0.0
	 */
	public function list_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/wp-structuring-admin-list.php' );
		new Structuring_Markup_Admin_List( $this->text_domain );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @version 4.0.0
	 * @since   1.0.0
	 */
	public function post_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/wp-structuring-admin-post.php' );
		new Structuring_Markup_Admin_Post( $this->text_domain );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 */
	public function config_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/admin/wp-structuring-admin-config.php' );
		new Structuring_Markup_Admin_Config( $this->text_domain );
	}

	/**
	 * Display Page Template Require.
	 *
	 * @version 4.5.0
	 * @since   1.3.0
	 */
	public function wp_head () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-display.php' );
		new Structuring_Markup_Display( $this->version, $this->text_domain );
	}

	/**
	 * Display Page Template Require.
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   array $metadata
	 * @return  array $metadata
	 */
	public function amp_post_template_metadata ( array $metadata ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-structuring-display-amp.php' );
		$amp = new Structuring_Markup_Display_Amp();

		if ( !empty( $amp->json_ld ) ) {
			$metadata = $amp->json_ld;
		}
		return (array) $metadata;
	}
}