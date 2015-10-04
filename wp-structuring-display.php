<?php
/**
 * Schema.org Display
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
new Structuring_Markup_Display();

class Structuring_Markup_Display {
	private $schema_array = array();

	public function __construct() {
		add_action( 'wp_head', array( $this, 'set_schema' ) );
	}

	/**
	 * Setting schema.org
	 *
	 * @since 1.0.0
	 */
	public function set_schema() {
		$this->set_schema_website();
		$this->set_schema_organization();

		if ( is_single() ) {
			$this->set_schema_article();
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @since 1.0.0
	 * @param $args [Array]
	 */
	private function set_schema_json( $args ) {
		echo '<script type="application/ld+json">' , PHP_EOL;
		echo json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
		echo '</script>' , PHP_EOL;
	}

	/**
	 * Setting schema.org WebSite
	 *
	 * @since 1.0.0
	 */
	private function set_schema_website() {
		$this->schema_array = array(
			"@context"        => "http://schema.org",
			"@type"           => "WebSite",
			"url"             => "",
			"potentialAction" => array(
				"@type"       => "SearchAction",
				"target"      => "",
				"query-input" => ""
			)
		);
		$this->set_schema_json( $this->schema_array );
	}

	/**
	 * Setting schema.org Organization
	 *
	 * @since 1.0.0
	 */
	private function set_schema_organization() {
		$this->schema_array = array(
			"@context" => "http://schema.org",
			"@type"    => "Organization",
			"url"      => "",
			"logo"     => ""
		);
		$this->set_schema_json( $this->schema_array );
	}

	/**
	 * Setting schema.org Article( Article | NewsArticle | BlogPosting )
	 *
	 * @since 1.0.0
	 */
	private function set_schema_article() {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$this->schema_array = array(
				"@context"      => "http://schema.org",
				"@type"         => "BlogPosting",
				"headline"      => esc_html( $post->post_title ),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"image"         => array( esc_html( wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" )[0] ) ),
				"description"   => esc_html( $post->post_excerpt ),
				"articleBody"   => esc_html( $post->post_content )
			);
			$this->set_schema_json( $this->schema_array );
		}
	}
}