<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://github.com/miiitaka/wp-structuring-markup
Description: It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an article or the fixed page.
Version: 1.1.2
Author: Kazuya Takami
Author URI: http://programp.com/
License: GPLv2 or later
Text Domain: wp-structuring-markup
*/
require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-db.php' );

new Structuring_Markup();

/**
 * Schema.org Basic Class
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$db = new Structuring_Markup_Admin_Db();
		$db->create_table();

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'set_menu' ) );
		} else {
			add_action( 'wp_head', array( $this, 'display_page_render' ) );
		}
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @since 1.0.0
	 */
	public function set_menu() {
		add_menu_page(
			'Scheme.org Setting',
			'Scheme.org Setting',
			'manage_options',
			plugin_basename( __FILE__ ),
			array($this, 'list_page_render')
		);
		add_submenu_page(
			__FILE__,
			'Setting All',
			'Setting All',
			'manage_options',
			plugin_basename( __FILE__ ),
			array($this, 'list_page_render')
		);
		add_submenu_page(
			__FILE__,
			'Scheme.org Setting Post',
			'Add New',
			'manage_options',
			plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-post.php',
			array($this, 'post_page_render')
		);
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @since 1.0.0
	 */
	public function list_page_render() {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-list.php' );
		new Structuring_Markup_Admin_List();
	}

	/**
	 * POST Page Template Require.
	 *
	 * @since 1.0.0
	 */
	public function post_page_render() {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-post.php' );
		new Structuring_Markup_Admin_Post();
	}

	/**
	 * Display Page Template Require.
	 *
	 * @since 1.0.0
	 */
	public function display_page_render() {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-display.php' );
		new Structuring_Markup_Display();
	}
}