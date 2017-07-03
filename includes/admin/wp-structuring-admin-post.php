<?php
/**
 * Schema.org Admin Post
 *
 * @author  Kazuya Takami
 * @version 4.1.0
 * @since   1.0.0
 */
class Structuring_Markup_Admin_Post {

	/**
	 * Variable definition.
	 *
	 * @version 2.0.0
	 * @since   1.3.0
	 */
	private $text_domain;

	/**
	 * Variable definition.
	 *
	 * @version 2.0.0
	 * @since   1.3.0
	 */
	private $type_array;

	/**
	 * Variable definition.
	 *
	 * @version 2.5.0
	 * @since   2.5.0
	 */
	private $post_args = array();

	/**
	 * Defined nonce.
	 *
	 * @version 3.0.2
	 * @since   3.0.2
	 */
	private $nonce_name;
	private $nonce_action;

	/**
	 * Constructor Define.
	 *
	 * @version 4.1.0
	 * @since   1.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$args = array(
			'public'   => true,
			'_builtin' => false
		);
		$post_types = get_post_types( $args, 'objects' );

		foreach ( $post_types as $post_type ) {
			$this->post_args[] = array(
				'label' => esc_html( $post_type->label ),
				'value' => esc_html( $post_type->name )
			);
		}
		$this->text_domain  = $text_domain;
		$this->nonce_name   = "_wpnonce_" . $text_domain;
		$this->nonce_action = "edit-"     . $text_domain;

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
		if ( ! empty( $_POST ) && check_admin_referer( $this->nonce_action, $this->nonce_name ) ) {
			if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) {
				if ( isset( $_POST['output'] ) ) {
					$options['id'] = $db->update_options( $_POST );
					$status = "ok";
				} else {
					$status = "output";
				}
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
	 * @version 4.0.0
	 * @since   1.0.0
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
		$html .= '<p class="schema-admin-require">';
		$html .= esc_html__( 'Please note that all fields followed by an asterisk must be filled in.', $this->text_domain );
		$html .= '</p>';
		$html .= '<form method="post" action="">';
		echo $html;

		wp_nonce_field( $this->nonce_action, $this->nonce_name );

		$html  = '<input type="hidden" name="id" value="'   . esc_attr( $options['id'] ) . '">';
		$html .= '<input type="hidden" name="type" value="' . esc_attr( $options['type'] ) . '">';
		$html .= '<table class="schema-admin-table">';
		$html .= '<tr><th class="require">Enabled : </th><td>';
		$html .= '<input type="checkbox" name="activate" value="on"';
		$html .= ( isset( $options['activate'] ) && $options['activate'] === "on" ) ? ' checked' : '';
		$html .= '></td></tr>';
		$html .= '<tr><th class="require">' . esc_html__( 'Output On', $this->text_domain ) . ' : </th><td>';
		echo $html;

		switch ( $options['type'] ) {
			case 'article':
				$html  = $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "amp",  esc_html__( 'Posts(AMP)', $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-article.php' );
				new Structuring_Markup_Type_Article( $options['option'] );
				break;
			case 'blog_posting':
				$html  = $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "amp",  esc_html__( 'Posts(AMP)', $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-blog-posting.php' );
				new Structuring_Markup_Type_Blog_Posting( $options['option'] );
				break;
			case 'breadcrumb':
				$html  = $this->output_checkbox_render( $options['output'], "all", esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-breadcrumb.php' );
				new Structuring_Markup_Type_Breadcrumb( $options['option'] );
				break;
			case 'event':
				$html  = $this->output_checkbox_render( $options['output'], "event", esc_html__( 'Event Post Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-event.php' );
				new Structuring_Markup_Type_Event();
				break;
			case 'local_business':
				$html  = $this->output_checkbox_render( $options['output'], "all",  esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", esc_html__( 'Homepage',              $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',                 $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',                 $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';

				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-local-business.php' );
				new Structuring_Markup_Type_LocalBusiness( $options['option'] );
				break;
			case 'news_article':
				$html  = $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',      $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "amp",  esc_html__( 'Posts(AMP)', $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';

				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-news-article.php' );
				new Structuring_Markup_Type_NewsArticle( $options['option'] );
				break;
			case 'organization':
				$html  = $this->output_checkbox_render( $options['output'], "all",  esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", esc_html__( 'Homepage',              $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',                 $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',                 $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';

				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-organization.php' );
				new Structuring_Markup_Type_Organization( $options['option'] );
				break;
			case 'person':
				$html  = $this->output_checkbox_render( $options['output'], "all",  esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", esc_html__( 'Homepage',              $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',                 $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',                 $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';

				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-person.php' );
				new Structuring_Markup_Type_Person( $options['option'] );
				break;
			case 'site_navigation':
				$html  = $this->output_checkbox_render( $options['output'], "all",  esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", esc_html__( 'Homepage',              $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-site-navigation.php' );
				new Structuring_Markup_Type_Site_Navigation( $options['option'] );
				break;
			case 'video':
				$html  = $this->output_checkbox_render( $options['output'], "video", esc_html__( 'Video Post Page', $this->text_domain ) );
				$html .= '</td></tr></table><hr>';
				echo $html;

				require_once ( plugin_dir_path( __FILE__ ) . 'wp-structuring-admin-type-video.php' );
				new Structuring_Markup_Type_Videos();
				break;
			case 'website':
				$html  = $this->output_checkbox_render( $options['output'], "all",  esc_html__( 'All Pages (In Header)', $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "home", esc_html__( 'Homepage',              $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "post", esc_html__( 'Posts',                 $this->text_domain ) );
				$html .= $this->output_checkbox_render( $options['output'], "page", esc_html__( 'Pages',                 $this->text_domain ) );
				$html .= '</td></tr>';
				$html .= $this->output_custom_posts_render( $options['output'] );
				$html .= '</table><hr>';

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
	 * @version 2.5.0
	 * @since   1.0.0
	 * @param   array  $option['output']
	 * @param   string $output
	 * @param   string $value
	 * @return  string $html
	 */
	private function output_checkbox_render ( array $option, $output, $value ) {
		$html  = '<label>';
		$html .= '<input type="checkbox" name="output[' . $output . ']" value="' . $value . '""';
		$html .= isset( $option[$output] ) ? ' checked' : '';
		$html .= '>' . $value . '</label>';

		return (string) $html;
	}

	/**
	 * Custom Posts Build Render
	 *
	 * @version 4.0.2
	 * @since   2.5.0
	 * @param   array  $option['output']
	 * @return  string $html
	 */
	private function output_custom_posts_render ( array $option ) {
		$html = '';
		if ( count( $this->post_args ) > 0 ) {
			$html .= '<tr><th>' . esc_html__( 'Output On(Custom Posts)', $this->text_domain ) . ' : </th><td>';
			foreach ( $this->post_args as $post_type ) {
				$html .= $this->output_checkbox_render( $option, $post_type['value'], $post_type['label'] );
			}
			$html .= '</td></tr>';
		}
		return (string) $html;
	}

	/**
	 * Information Message Render
	 *
	 * @version 2.0.0
	 * @since   1.0.0
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
	 * @version 2.0.0
	 * @since   1.0.0
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