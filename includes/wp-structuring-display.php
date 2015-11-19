<?php
/**
 * Schema.org Display
 *
 * @author  Kazuya Takami
 * @version 1.2.1
 * @since   1.0.0
 */
class Structuring_Markup_Display {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$db = new Structuring_Markup_Admin_Db();
		$this->set_schema( $db );
	}

	/**
	 * Setting schema.org
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   Structuring_Markup_Admin_Db $db
	 */
	private function set_schema( Structuring_Markup_Admin_Db $db ) {
		$this->get_schema_data( $db, 'all' );
		if ( is_home() ) {
			$this->get_schema_data( $db, 'home' );
		}
		if ( is_single() ) {
			$this->get_schema_data( $db, 'post' );
		}
		if ( is_page() ) {
			$this->get_schema_data( $db, 'page' );
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   Structuring_Markup_Admin_Db $db
	 * @param   string $output
	 */
	private function get_schema_data( Structuring_Markup_Admin_Db $db, $output ) {
		$results = $db->get_select_options( $output );

		if ( isset( $results ) ) {
			foreach ( $results as $row ) {
				if ( isset( $row->type ) && $row->activate === 'on' ) {
					switch ( $row->type ) {
						case 'article':
							$this->set_schema_article();
							break;
						case 'blog_posting':
							$this->set_schema_blog_posting();
							break;
						case 'news_article':
							$this->set_schema_news_article();
							break;
						case 'organization':
							if ( isset( $row->options ) ) {
								$this->set_schema_organization( unserialize( $row->options ) );
							}
							break;
						case 'website':
							if ( isset( $row->options ) ) {
								$this->set_schema_website( unserialize( $row->options ) );
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
	 * @since 1.0.0
	 * @param array $args
	 */
	private function set_schema_json( array $args ) {
		echo '<script type="application/ld+json">' , PHP_EOL;
		echo json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
		echo '</script>' , PHP_EOL;
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @since   1.1.3
	 * @version 2.0.0
	 * @param   string $text
	 * @return  string $text
	 */
	private function escape_text_tags( $text ) {
		return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
	}

	/**
	 * Setting schema.org Article
	 *
	 * @since   1.1.0
	 * @version 1.1.3
	 */
	private function set_schema_article() {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$args = array(
				"@context"      => "http://schema.org",
				"@type"         => "Article",
				"headline"      => $this->escape_text_tags( $post->post_title ),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"author"        => $this->escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) ),
				"image"         => array( $images[0] ),
				"description"   => $this->escape_text_tags( $post->post_excerpt ),
				"articleBody"   => $this->escape_text_tags( $post->post_content )
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org BlogPosting
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 */
	private function set_schema_blog_posting() {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$args = array(
				"@context"      => "http://schema.org",
				"@type"         => "BlogPosting",
				"headline"      => $this->escape_text_tags( $post->post_title ),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"dateModified"  => get_the_modified_time( DATE_ISO8601, $post->ID ),
				"author"        => $this->escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) ),
				"image"         => array( $images[0] ),
				"description"   => $this->escape_text_tags( $post->post_excerpt ),
				"articleBody"   => $this->escape_text_tags( $post->post_content )
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org NewsArticle
	 *
	 * @since   1.0.0
	 * @version 1.1.3
	 */
	private function set_schema_news_article() {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$args = array(
				"@context"      => "http://schema.org",
				"@type"         => "NewsArticle",
				"headline"      => $this->escape_text_tags( $post->post_title ),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"image"         => array( $images[0] ),
				"description"   => $this->escape_text_tags( $post->post_excerpt ),
				"articleBody"   => $this->escape_text_tags( $post->post_content )
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org Organization
	 *
	 * @since    1.0.0
	 * ＠version 1.2.1
	 * @param array $options
	 */
	private function set_schema_organization( array $options ) {
		/** Logos */
		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "Organization",
			"name"     => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"url"      => isset( $options['url'] ) ? esc_html( $options['url'] ) : "",
			"logo"     => isset( $options['logo'] ) ? esc_html( $options['logo'] ) : ""
		);

		/** Corporate Contact */
		if ( isset( $options['contact_point'] ) && $options['contact_point'] === 'on' ) {
			$contact_point["contactPoint"] = array(
				array(
					"@type"       => "ContactPoint",
					"telephone"   => isset( $options['telephone'] ) ? esc_html( $options['telephone'] ) : "",
					"contactType" => isset( $options['contact_type'] ) ? esc_html( $options['contact_type'] ) : ""
				)
			);
			$args = array_merge( $args, $contact_point );
		}

		/** Social Profiles */
		if ( isset( $options['social'] ) ) {
			$socials["sameAs"] = array();

			foreach ( $options['social'] as $value ) {
				if ( !empty( $value ) ) {
					$socials["sameAs"][] = esc_html( $value );
				}
			}
			$args = array_merge( $args, $socials );
		}
		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org WebSite
	 *
	 * @since 1.0.0
	 * @param array $options
	 */
	private function set_schema_website( array $options ) {
		$args = array(
			"@context"      => "http://schema.org",
			"@type"         => "WebSite",
			"name"          => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"alternateName" => isset( $options['alternateName'] ) ? esc_html( $options['alternateName'] ) : "",
			"url"           => isset( $options['url'] ) ? esc_html( $options['url'] ) : ""
		);

		if ( isset( $options['potential_action'] ) && $options['potential_action'] === 'on' ) {
			$potential_action["potentialAction"] = array(
				"@type"       => "SearchAction",
				"target"      => isset( $options['target'] ) ? esc_html( $options['target'] ) . "{search_term_string}" : "",
				"query-input" => isset( $options['target'] ) ? "required name=search_term_string" : ""
			);
			$args = array_merge( $args, $potential_action );
		}

		$this->set_schema_json( $args );
	}
}