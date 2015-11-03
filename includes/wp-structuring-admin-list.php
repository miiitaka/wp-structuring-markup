<?php
/**
 * Schema.org Admin List
 *
 * @author  Kazuya Takami
 * @version 1.2.0
 * @since   1.0.0
 * @see     wp-structuring-admin-db.php
 */
class Structuring_Markup_Admin_List {

	/**
	 * Variable definition.
	 *
	 * @since 1.3.0
	 */
	private $text_domain;

	/** Schema.org Type defined. */
	private $type_array = array(
		"website"      => "Web Site",
		"organization" => "Organization",
		"article"      => "Article",
		"blog_posting" => "Blog Posting",
		"news_article" => "News Article"
	);

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 1.3.0
	 * @param   String $text_domain
	 */
	function __construct( $text_domain ) {
		$this->text_domain = $text_domain;

		$db = new Structuring_Markup_Admin_Db();
		$mode = "";

		if ( isset( $_GET['mode'] ) && $_GET['mode'] === 'delete' ) {
			if ( isset( $_GET['schema_post_id'] ) && is_numeric( $_GET['schema_post_id'] ) ) {
				$db->delete_options( $_GET['schema_post_id'] );
				$mode = "delete";
			}
		}

		$this->page_render( $db, $mode );
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @since   1.0.0
	 * @version 1.3.2
	 * @param   Structuring_Markup_Admin_Db $db
	 * @param   String $mode
	 */
	private function page_render( Structuring_Markup_Admin_Db $db, $mode = "" ) {
		$post_url = admin_url() . 'admin.php?page=' . $this->text_domain . '/includes/wp-structuring-admin-post.php';
		$self_url = $_SERVER['PHP_SELF'] . '?' . esc_html( $_SERVER['QUERY_STRING'] );

		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Schema.org Settings List', $this->text_domain );
		$html .= '<a href="' . $post_url . '" class="page-title-action">' . esc_html__( 'Add New', $this->text_domain ) . '</a>';
		$html .= '</h1>';
		echo $html;

		if ( $mode === "delete" ) {
			$this->information_render();
		}

		$html  = '<hr>';
		$html .= '<table class="wp-list-table widefat fixed striped posts">';
		$html .= '<tr>';
		$html .= '<th scope="row">' . esc_html__( 'Schema Type',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Output Page',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Register Date', $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Update Date',   $this->text_domain ) . '</th>';
		$html .= '<th scope="row">&nbsp;</th>';
		$html .= '</tr>';
		echo $html;

		$results = $db->get_list_options();

		if ( $results ) {
			foreach ( $results as $row ) {
				$html  = '';
				$html .= '<tr>';
				$html .= '<td>';
				$html .= '<a href="' . $post_url . '&mode=edit&schema_post_id=' . esc_html( $row->id ) . '">' . $this->type_array[esc_html( $row->type )] . '</a>';
				$html .= '</td>';
				$html .= '<td>' . $this->unserialize_output( $row->output ) . '</td>';
				$html .= '<td>' . esc_html( $row->register_date ) . '</td>';
				$html .= '<td>' . esc_html( $row->update_date ) . '</td>';
				$html .= '<td>';
				$html .= '<a href="' . $post_url . '&mode=edit&schema_post_id='   . esc_html( $row->id ) . '">';
				$html .= esc_html__( 'Edit', $this->text_domain );
				$html .= '</a>&nbsp;&nbsp;&nbsp;&nbsp;';
				$html .= '<a href="' . $self_url . '&mode=delete&schema_post_id=' . esc_html( $row->id ) . '">';
				$html .= esc_html__( 'Delete', $this->text_domain );
				$html .= '</a>';
				$html .= '</td>';
				$html .= '</tr>';
				echo $html;
			}
		} else {
			echo '<td colspan="3">' . esc_html__( 'Without registration.', $this->text_domain ) . '</td>';
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
	 * @return string $args
	 */
	private function unserialize_output( $obj ) {
		$args = unserialize( $obj );
		return (string) implode( ",", $args );
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	private function information_render() {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Deletion succeeds.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}