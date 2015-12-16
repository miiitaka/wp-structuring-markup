<?php
/**
 * Schema.org Admin Post
 *
 * @author  Kazuya Takami
 * @since   1.0.0
 * @version 2.2.0
 */
class Structuring_Markup_Admin_Post {

	/**
	 * Variable definition.
	 *
	 * @since   1.3.0
	 * @version 2.0.0
	 */
	private $text_domain;
	private $type_array;

	/**
	 * Constructor Define.
	 *
	 * @since   1.0.0
	 * @version 2.2.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		/**
		 * Update Status
		 *
		 * "ok"     : Successful update
		 * "output" : Output No Check
		 */
		$status = "";

		/** DB Connect */
		$db = new Structuring_Markup_Admin_Db();
		$this->type_array = $db->type_array;

		/** Set Default Parameter for Array */
		$options = array(
			"id"       => "",
			"type"     => "",
			"activate" => "",
			"output"   => array(),
			"option"   => array()
		);

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) {
			if ( isset( $_POST['output'] ) ) {
				$options['id'] = $db->update_options( $_POST );
				$status = "ok";
			} else {
				$status = "output";
			}
		}

		/** Key Set */
		if ( isset( $_GET['schema_post_id'] ) && is_numeric( $_GET['schema_post_id'] ) ) {
			$options['id'] = esc_html( $_GET['schema_post_id'] );
		}

		/** Type Set */
		if ( isset( $_GET['type'] ) && array_key_exists( $_GET['type'], $this->type_array) ) {
			$options['type'] = esc_html( $_GET['type'] );
		}

		$options = $db->get_options( $options['id'] );
		if ( $options['option'] === false ) {
			$options['option'] = array();
		}

		$this->page_render( $options, $status );
	}

	/**
	 * Setting Page of the Admin Screen.
	 *
	 * @since   1.0.0
	 * @version 2.2.0
	 * @param   array  $options
	 * @param   string $status
	 */
	private function page_render ( array $options, $status ) {
		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Schema.org Register', $this->text_domain );
		$html .= '<span class="schema-admin-h1-span">[ Schema Type : ' . esc_html( $this->type_array[$options['type']] ) . ' ]</span>';
		$html .= '</h1>';
		switch ( $status ) {
			case "ok":
				$html .= $this->information_render();
				break;
			case "output":
				$html .= $this->output_error_render();
				break;
			default:
				break;
		}
		echo $html;

		/** Output Page Select */
		$html  = '<hr>';
		$html .= '<form method="post" action="">';
		$html .= '<input type="hidden" name="id" value="'   . esc_attr( $options['id'] ) . '">';
		$html .= '<input type="hidden" name="type" value="' . esc_attr( $options['type'] ) . '">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th>Activate : </th><td><label>';
		$html .= '<input type="checkbox" name="activate" value="on"';
		$html .= ( isset( $options['activate'] ) && $options['activate'] === "on" ) ? ' checked' : '';
		$html .= '>Activate</label></td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Output Page', $this->text_domain ) . ' : </th><td>';
		echo $html;

		switch ( $options['type'] ) {
			case 'article':
				$html  = $this->output_checkbox_render( $options['output'], "post", "Post", esc_html__( 'Post Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-article.php' );
				new Structuring_Markup_Type_Article( $options['option'] );
				break;
			case 'blog_posting':
				$html  = $this->output_checkbox_render( $options['output'], "post", "Post", esc_html__( 'Post Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-blog-posting.php' );
				new Structuring_Markup_Type_Blog_Posting( $options['option'] );
				break;
			case 'breadcrumb':
				$html  = $this->output_checkbox_render( $options['output'], "all",  "All",   esc_html__( 'All Page',   $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-breadcrumb.php' );
				new Structuring_Markup_Type_Breadcrumb( $options['option'] );
				break;
			case 'event':
				$html  = $this->output_checkbox_render( $options['output'], "event",  "Event Post",   esc_html__( 'Event Post Page',   $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-event.php' );
				new Structuring_Markup_Type_Event();
				break;
			case 'news_article':
				$html  = $this->output_checkbox_render( $options['output'], "post", "Post", esc_html__( 'Post Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-news-article.php' );
				new Structuring_Markup_Type_NewsArticle( $options['option'] );
				break;
			case 'organization':
				$html  = $this->output_checkbox_render( $options['output'], "all",  "All",   esc_html__( 'All Page',   $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", "Top",   esc_html__( 'Top Page',   $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", "Post",  esc_html__( 'Post Page',  $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", "Fixed", esc_html__( 'Fixed Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-organization.php' );
				new Structuring_Markup_Type_Organization( $options['option'] );
				break;
			case 'website':
				$html  = $this->output_checkbox_render( $options['output'], "all",  "All",   esc_html__( 'All Page',   $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", "Top",   esc_html__( 'Top Page',   $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", "Post",  esc_html__( 'Post Page',  $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", "Fixed", esc_html__( 'Fixed Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-website.php' );
				new Structuring_Markup_Type_Website( $options['option'] );
				break;
		}

		$html  = '</form>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * CheckBox Build Render
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array  $option['output']
	 * @param   string $output
	 * @param   string $value
	 * @param   string $display
	 * @return  string $html
	 */
	private function output_checkbox_render ( array $option, $output, $value, $display ) {
		$html  = '<label>';
		$html .= '<input type="checkbox" name="output[' . $output . ']" value="' . $value . '""';
		$html .= isset( $option[$output] ) ? ' checked' : '';
		$html .= '>' . $display . '</label>';

		return (string) $html;
	}

	/**
	 * Information Message Render
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @return  string $html
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Schema.org Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		return (string) $html;
	}

	/**
	 * Error Message Render
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @return  string $html
	 */
	private function output_error_render () {
		$html  = '<div id="notice" class="notice notice-error is-dismissible below-h2">';
		$html .= '<p>Output No Select.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		return (string) $html;
	}
}