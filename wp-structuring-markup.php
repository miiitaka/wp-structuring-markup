<?php
/*
Plugin Name: Markup (JSON-LD) structured in schema.org
Plugin URI: https://github.com/miiitaka/wp-structuring-markup
Description: It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an article or the fixed page.
Version: 1.0.0
Author: Kazuya Takami
Author URI: http://programp.com/
License: GPLv2 or later
Text Domain: wp-structuring-markup
*/
require_once( 'wp-structuring-admin-db.php' );

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
	function __construct() {
		$db = new Structuring_Markup_Admin_Db();
		$db->create_table();

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'set_menu' ) );
		} else {
			require_once( 'wp-structuring-display.php' );
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
			'wp-structuring-admin-post.php',
			array($this, 'post_page_render')
		);
	}

	/**
	 * LIST Page Template Require.
	 *
	 * @since 1.0.0
	 */
	public function list_page_render() {
		require_once( 'wp-structuring-admin-list.php' );
	}

	/**
	 * POST Page Template Require.
	 *
	 * @since 1.0.0
	 */
	public function post_page_render() {
		require_once( 'wp-structuring-admin-post.php' );
	}
}