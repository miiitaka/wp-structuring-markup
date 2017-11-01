<?php
/**
 * Schema.org Admin List
 *
 * @author  Kazuya Takami
 * @version 4.1.4
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 */
class Structuring_Markup_Admin_List {

	/**
	 * Variable definition.
	 *
	 * @version 2.0.0
	 * @since   1.3.0
	 */
	private $text_domain;

	/**
	 * Constructor Define.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;
		$this->page_render();
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @version 4.1.4
	 * @since   1.0.0
	 */
	private function page_render () {
		$post_url = 'admin.php?page=' . $this->text_domain . '-post';

		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Schema.org Settings List', $this->text_domain );
		$html .= '</h1>';
		echo $html;

		$html  = '<hr>';
		$html .= '<table class="wp-list-table widefat fixed striped posts schema-admin-table-list">';
		$html .= '<tr>';
		$html .= '<th scope="row" class="schema-admin-table-list-type column-primary">' . esc_html__( 'Status', $this->text_domain ) . '&nbsp;&nbsp;/&nbsp;&nbsp;' . esc_html__( 'Schema.org Type', $this->text_domain ) . '</th>';
		$html .= '<th scope="row" class="schema-admin-table-list-output">' . esc_html__( 'Output On', $this->text_domain ) . '</th>';
		$html .= '</tr>';
		echo $html;

		$db         = new Structuring_Markup_Admin_Db();
		$results    = $db->get_list_options();
		$type_array = $db->type_array;

		if ( $results ) {
			foreach ( $results as $row ) {
				if ( $row->activate === 'on' ) {
					$html = '<tr class="active"><td class="column-primary"><span><span class="active">Enabled';
				} else {
					$html = '<tr class="stop"><td class="column-primary"><span><span class="stop">Disabled';
				}
				$html .= '</span></span>';
				$html .= '<strong><a href="';
				$html .= admin_url( $post_url . '&type=' . esc_html( $row->type ) . '&schema_post_id=' . esc_html( $row->id ) ) . '">' . $type_array[esc_html( $row->type )];
				$html .= '</a></strong>';
				$html .= '<div class="row-actions"><span class="edit"><a href="';
				$html .= admin_url( $post_url . '&type=' . esc_html( $row->type ) . '&schema_post_id=' . esc_html( $row->id ) ) . '" class="edit" aria-label="' . esc_html__( 'Edit', $this->text_domain ) . '">' . esc_html__( 'Edit', $this->text_domain );
				$html .= '</a></span></div>';
				$html .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details', $this->text_domain ) . '</span></button>';
				$html .= '</td>';
				$html .= '<td data-colname="' . esc_html__( 'Output On', $this->text_domain ) . '">' . $this->unserialize_output( $row->output ) . '</td>';
				$html .= '</tr>';
				echo $html;
			}
		} else {
			echo '<td colspan="2">' . esc_html__( 'Without registration.', $this->text_domain ) . '</td>';
		}
		$html  = '</table>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @param   string $obj
	 * @return  string $output
	 */
	private function unserialize_output ( $obj ) {
		$output = implode( ",", unserialize( $obj ) );
		return (string) $output;
	}
}