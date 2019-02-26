<?php
/**
 * Schema.org Display
 *
 * @author  Kazuya Takami
 * @author  Justin Frydman
 * @version 4.6.4
 * @since   1.0.0
 */
class Structuring_Markup_Display {

	/**
	 * Utility
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 */
	private $utility;

	/**
	 * wp_options data
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 */
	private $options;

	/**
	 * Constructor Define.
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 * @param   string $version
	 * @param   string $text_domain
	 */
	public function __construct ( $version, $text_domain ) {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-utility.php' );
		$this->utility = new Structuring_Markup_Utility();

		$this->options = get_option( $text_domain );

		$db = new Structuring_Markup_Admin_Db();
		$this->set_schema( $db, $version );
	}

	/**
	 * Setting schema.org
	 *
	 * @version 4.5.0
	 * @since   1.0.0
	 * @param   Structuring_Markup_Admin_Db $db
	 * @param   string $version
	 */
	private function set_schema ( Structuring_Markup_Admin_Db $db, $version ) {
		$structuring_markup_args = $db->get_list_options();

		if ( !isset( $this->options['compress'] ) || $this->options['compress'] !== 'on' ) {
			echo '<!-- Markup (JSON-LD) structured in schema.org ver.' . $version . ' START -->' . PHP_EOL;
		}

		$this->get_schema_data( 'all', $structuring_markup_args );
		if ( is_home() || is_front_page() ) {
			$this->get_schema_data( 'home', $structuring_markup_args );
		}
		if ( is_single() && get_post_type() === 'post' ) {
			$this->get_schema_data( 'post', $structuring_markup_args );
		}
		if ( is_singular( 'schema_event_post' ) ) {
			$this->get_schema_data( 'event', $structuring_markup_args );
		}
		if ( is_singular( 'schema_video_post' ) ) {
			$this->get_schema_data( 'video', $structuring_markup_args );
		}
		if ( is_page() ) {
			$this->get_schema_data( 'page', $structuring_markup_args );
		}
		$args = array(
			'public'   => true,
			'_builtin' => false
		);
		$post_types = get_post_types( $args, 'objects' );

		unset( $post_types['schema_event_post'] );
		unset( $post_types['schema_video_post'] );

		foreach ( $post_types as $post_type ) {
			if ( is_singular( $post_type->name ) ) {
				$this->get_schema_data( $post_type->name, $structuring_markup_args );
			}
		}

		if ( !isset( $this->options['compress'] ) || $this->options['compress'] !== 'on' ) {
			echo '<!-- Markup (JSON-LD) structured in schema.org END -->' . PHP_EOL;
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @version 4.6.3
	 * @since   1.0.0
	 * @param   string $output
	 * @param   array  $structuring_markup_args
	 */
	private function get_schema_data ( $output, array $structuring_markup_args ) {

		foreach ( $structuring_markup_args as $row ) {
			/** Output page check. */
			$output_args = unserialize( $row->output );
			if ( array_key_exists( $output, $output_args ) ) {
				
				/** Activate check. */
				if ( isset( $row->type ) && isset( $row->activate ) && $row->activate === 'on' ) {
					switch ( $row->type ) {
						case 'article':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-article.php' );
								$obj = new Structuring_Markup_Meta_Article( $this->utility );
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_article', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'blog_posting':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-blog-posting.php' );
								$obj = new Structuring_Markup_Meta_Blog_Posting( $this->utility );
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_blog_posting', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'breadcrumb':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-breadcrumb.php' );
								require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-short-code-breadcrumb.php' );
								$obj = new Structuring_Markup_Meta_Breadcrumb();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_breadcrumb', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'event':
							require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-event.php' );
							$obj = new Structuring_Markup_Meta_Event();
							$out = $obj->set_meta();
							$out = apply_filters( 'structuring_markup_meta_event', $out );
							$this->set_schema_json( $out );
							break;
						case 'local_business':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-local-business.php' );
								require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-opening-hours.php' );
								$obj = new Structuring_Markup_Meta_LocalBusiness();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_local_business', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'news_article':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-news-article.php' );
								$obj = new Structuring_Markup_Meta_NewsArticle( $this->utility );
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_news_article', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'organization':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-organization.php' );
								$obj = new Structuring_Markup_Meta_Organization();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_organization', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'person':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-person.php' );
								$obj = new Structuring_Markup_Meta_Person();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_person', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'site_navigation':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-site-navigation.php' );
								$obj = new Structuring_Markup_Meta_Site_Navigation();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_site_navigation', $out );
								$this->set_schema_json( $out );
							}
							break;
						case 'video':
							require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-video.php' );
							$obj = new Structuring_Markup_Meta_Video( $this->utility );
							$out = $obj->set_meta();
							$out = apply_filters( 'structuring_markup_meta_video', $out );
							$this->set_schema_json( $out );
							break;
						case 'website':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-website.php' );
								$obj = new Structuring_Markup_Meta_WebSite();
								$out = $obj->set_meta( unserialize( $row->options ) );
								$out = apply_filters( 'structuring_markup_meta_website', $out );
								$this->set_schema_json( $out );
							}
							break;
					}
				}
			}
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @since 4.6.4
	 * @since 1.0.0
	 * @param array   $args
	 * @param boolean $error
	 */
	private function set_schema_json ( $args, $error = false ) {
		if ( $error ) {
			/** Error Display */
			if ( isset( $args["@type"] ) ) {
				foreach ( $args["message"] as $message ) {
					echo "<!-- Schema.org ", $args["@type"], " : ", $message, " -->", PHP_EOL;
				}
			}
		} else {
			$output = '';
			if ( is_array( $args ) ) {
				if ( isset( $this->options['compress'] ) && $this->options['compress'] === 'on' ) {
					$output .= '<script type="application/ld+json">';
					$output .= json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
					$output .= '</script>';
				} else {
					$output .= '<script type="application/ld+json">' . PHP_EOL;
					$output .= json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
					$output .= '</script>' . PHP_EOL;
				}
			}
			echo apply_filters( 'structuring_markup_output', $output );
		}
	}
}
