<?php
/**
 * Schema.org Admin List
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 2.0.0
 * @see     wp-structuring-admin-db.php
 */
class Structuring_Markup_Admin_List {

	/**
	 * Variable definition.
	 *
	 * @since   1.3.0
	 * @version 2.0.0
	 */
	private $text_domain;

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   String $text_domain
	 */
	function __construct( $text_domain ) {
		$this->text_domain = $text_domain;
		$this->page_render( );
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 */
	private function page_render( ) {
		$post_url = admin_url() . 'admin.php?page=' . $this->text_domain . '/includes/wp-structuring-admin-post.php';

		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Schema.org Settings List', $this->text_domain );
		$html .= '</h1>';
		echo $html;

		$html  = '<hr>';
		$html .= '<table class="wp-list-table widefat fixed striped posts schema-admin-table-list">';
		$html .= '<tr>';
		$html .= '<th scope="row">' . esc_html__( 'Status',        $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Schema Type',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Output Page',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Register Date', $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Update Date',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">&nbsp;</th>';
		$html .= '</tr>';
		echo $html;

		$db         = new Structuring_Markup_Admin_Db();
		$results    = $db->get_list_options();
		$type_array = $db->type_array;

		if ( $results ) {
			foreach ( $results as $row ) {
				$html  = '<tr><td>';
				$html .= ( $row->activate === 'on' ) ? '<span class="active">Activate' : '<span class="stop">Stop';
				$html .= '</span></td>';
				$html .= '<td><a href="';
				$html .= $post_url . '&type=' . esc_html( $row->type ) . '&schema_post_id=' . esc_html( $row->id ) . '">' . $type_array[esc_html( $row->type )];
				$html .= '</a></td>';
				$html .= '<td>' . $this->unserialize_output( $row->output ) . '</td>';
				$html .= '<td>' . esc_html( $row->register_date ) . '</td>';
				$html .= '<td>' . esc_html( $row->update_date ) . '</td>';
				$html .= '<td><a href="';
				$html .= $post_url . '&type=' . esc_html( $row->type ) . '&schema_post_id=' . esc_html( $row->id ) . '">' . esc_html__( 'Edit', $this->text_domain );
				$html .= '</a></td>';
				$html .= '</tr>';
				echo $html;
			}
		} else {
			echo '<td colspan="6">' . esc_html__( 'Without registration.', $this->text_domain ) . '</td>';
		}

		$html  = '</table>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   string $obj
	 * @return  string $output
	 */
	private function unserialize_output( $obj ) {
		$output = implode( ",", unserialize( $obj ) );
		return (string) $output;
	}
}