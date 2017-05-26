<?php
/**
 * Schema.org Site Navigation Element
 *
 * @author  Kazuya Takami
 * @version 4.0.0
 * @since   4.0.0
 * @link    https://schema.org/SiteNavigationElement
 */
class Structuring_Markup_Meta_Site_Navigation {

	/**
	 * Setting schema.org Site Navigation
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		if ( isset( $options['menu_name'] ) && wp_get_nav_menu_items( $options['menu_name'] ) ) {
			$items_array = wp_get_nav_menu_items( $options['menu_name'] );
			$name_array  = array();
			$url_array   = array();

			foreach ( (array) $items_array as $key => $menu_item ) {
				$url_array[]  = $menu_item->url;
				$name_array[] = $menu_item->title;
			}

			if ( count( $items_array ) > 0 ) {
				$args = array(
					"@context" => "http://schema.org",
					"@type"    => "SiteNavigationElement",
					"name"     => $name_array,
					"url"      => $url_array
				);
				return (array) $args;
			}
		}
	}
}