<?php
require_once( 'wp-structuring-admin-db.php' );

new Structuring_Markup_Admin_Post();

/**
 * Schema.org Admin Post
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Post {
	private $type_array = array();

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->type_array = array(
			array("type"=>"website",      "display"=>"Web Site"),
			array("type"=>"organization", "display"=>"Organization")
		);

		$this->page_render();
	}

	/**
	 * Setting Page of the Admin Screen.
	 *
	 * @since 1.0.0
	 */
	private function page_render() {
		$html  = '';
		$html .= '<link rel="stylesheet" href="' . plugin_dir_url( __FILE__ ) . '/css/style.css">';
		$html .= '<div class="wrap">';
		$html .= '<h1>Schema.org Post</h1>';
		$html .= '<hr>';
		$html .= '<form method="post" action="">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th><label for="type">Type :</label></th><td>';
		$html .= '<select id="type" name="type">';

		for ( $i = 0; $i < count( $this->type_array ); $i++ ) {
			$html .= '<option value="' . $this->type_array[$i]['type'] . '">' . $this->type_array[$i]['display'] . '</option>';
		}

		$html .= '</select>';
		$html .= '</td></tr>';
		echo $html;

		$html  = '<tr><th>Output :</th><td>';
		$html .= '<label><input type="checkbox" name="output[' . "home" . ']" value="Top">Top Page</label>';
		$html .= '<label><input type="checkbox" name="output[' . "post" . ']" value="Post">Post Page</label>';
		$html .= '<label><input type="checkbox" name="output[' . "page" . ']" value="Fixed">Fixed Page</label>';
		$html .= '</td></tr></table><hr>';
		echo $html;

		require_once( 'wp-structuring-admin-type-website.php' );

		$html  = '</form>';
		$html .= '</div>';
		echo $html;
	}
}