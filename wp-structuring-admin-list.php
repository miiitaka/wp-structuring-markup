<?php
require_once( 'wp-structuring-admin-db.php' );

new Structuring_Markup_Admin_List();

/**
 * Schema.org Admin List
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 */
class Structuring_Markup_Admin_List {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		$this->page_render();
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @since 1.0.0
	 */
	public function page_render() {
		$post_url   = "admin.php?page=wp-structuring-admin-post.php";

		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>Schema.org Setting List';
		$html .= '<a href="' . $post_url . '" class="page-title-action">新規追加</a>';
		$html .= '</h1>';
		$html .= '<table class="wp-list-table widefat fixed striped posts">';
		$html .= '<tr>';
		$html .= '<th scope="row">Schema&nbsp;Type</th>';
		$html .= '<th scope="row">Output&nbsp;Page</th>';
		$html .= '<th scope="row">Register&nbsp;Date</th>';
		$html .= '<th scope="row">Update&nbsp;Date</th>';
		$html .= '</tr>';
		echo $html;

		$db = new Structuring_Markup_Admin_Db();
		$results = $db->getAll_options();

		if ( $results ) {
			foreach ( $results as $row ) {
				$html  = '';
				$html .= '<tr><td>';
				$html .= '<a href="' . $post_url . '&schema_post_id=' . esc_html( $row->id ) . '">';
				$html .= esc_html( $row->type ) . '</a></td>';
				$html .= '<td>' . $this->unserialize_output( $row->output ) . '</td>';
				$html .= '<td>' . esc_html( $row->register_date ) . '</td>';
				$html .= '<td>' . esc_html( $row->update_date ) . '</td>';
				$html .= '</tr>';
				echo $html;
			}
		} else {
			echo '<td colspan="3">NOT FOUND</td>';
		}

		$html  = '</table>';
		$html .= '</div>';
		echo $html;
	}


	/**
	 * LIST Page HTML Render.
	 *
	 * @since 1.0.0
	 * @param  object $obj
	 * @return string
	 */
	public function unserialize_output( $obj ) {
		$args = unserialize( $obj );
		return implode( ",", $args );
	}
}