<?php
/**
 * Schema.org Type Breadcrumb
 *
 * @author  Kazuya Takami
 * @version 4.0.0
 * @since   4.0.0
 * @see     wp-structuring-short-code-breadcrumb.php
 * @link    https://schema.org/BreadcrumbList
 * @link    https://developers.google.com/search/docs/data-types/breadcrumbs
 */
class Structuring_Markup_Meta_Breadcrumb {

	/**
	 * Setting schema.org Breadcrumb
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   array $options
	 * @return  array $args
	 */
	public function set_meta ( array $options ) {
		$obj = new Structuring_Markup_ShortCode_Breadcrumb();
		$item_array = $obj->breadcrumb_array_setting( $options );

		if ( $item_array ) {
			/** itemListElement build */
			$item_list_element = array();
			$position = 1;
			foreach ($item_array as $item) {
				$item_list_element[] = array(
					"@type"    => "ListItem",
					"position" => $position,
					"item"     => $item
				);
				$position++;
			}

			/** Breadcrumb Schema build */
			$args = array(
				"@context"        => "http://schema.org",
				"@type"           => "BreadcrumbList",
				"itemListElement" => $item_list_element
			);

			return (array) $args;
		}
	}

}