<?php
/**
 * Schema.org Display (AMP)
 *
 * @author  Kazuya Takami
 * @version 4.0.0
 * @since   4.0.0
 */
class Structuring_Markup_Display_Amp {

	/**
	 * Utility
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 */
	private $utility;

	/**
	 * JSON-LD output data
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 */
	public $json_ld = array();

	/**
	 * Constructor Define.
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 */
	public function __construct () {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-utility.php' );
		$this->utility = new Structuring_Markup_Utility();

		$db = new Structuring_Markup_Admin_Db();
		$this->set_schema( $db );
	}

	/**
	 * Setting schema.org
	 *
	 * @version 4.0.0
	 * @since   4.0.0
	 * @param   Structuring_Markup_Admin_Db $db
	 */
	private function set_schema ( Structuring_Markup_Admin_Db $db ) {
		$structuring_markup_args = $db->get_list_options();

		if ( is_single() && get_post_type() === 'post' ) {
			$this->get_schema_data( 'amp', $structuring_markup_args );
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @version 4.0.0
	 * @since   4.0.0
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
								$this->json_ld = $obj->set_meta( unserialize( $row->options ) );
							}
							break;
						case 'blog_posting':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-blog-posting.php' );
								$obj = new Structuring_Markup_Meta_Blog_Posting( $this->utility );
								$this->json_ld = $obj->set_meta( unserialize( $row->options ) );
							}
							break;
						case 'news_article':
							if ( isset( $row->options ) && $row->options ) {
								require_once( plugin_dir_path( __FILE__ ) . 'meta/wp-structuring-meta-news-article.php' );
								$obj = new Structuring_Markup_Meta_NewsArticle( $this->utility );
								$this->json_ld = $obj->set_meta( unserialize( $row->options ) );
							}
							break;
					}
				}
			}
		}
	}
}