<?php
/**
 * Schema.org Display
 *
 * @author  Kazuya Takami
 * @author  Justin Frydman
 * @version 2.3.0
 * @since   1.0.0
 */
class Structuring_Markup_Display {

	/**
	 * Constructor Define.
	 *
	 * @since 1.0.0
	 */
	public function __construct () {
		$db = new Structuring_Markup_Admin_Db();
		$this->set_schema( $db );
	}

	/**
	 * Setting schema.org
	 *
	 * @since   1.0.0
	 * @version 2.1.0
	 * @param   Structuring_Markup_Admin_Db $db
	 */
	private function set_schema ( Structuring_Markup_Admin_Db $db ) {
		echo '<!-- Markup (JSON-LD) structured in schema.org START -->' . PHP_EOL;
		$this->get_schema_data( $db, 'all' );
		if ( is_home() ) {
			$this->get_schema_data( $db, 'home' );
		}
		if ( is_single() && get_post_type() === 'post' ) {
			$this->get_schema_data( $db, 'post' );
		}
		if ( is_singular( 'schema_event_post' ) ) {
			$this->get_schema_data( $db, 'event' );
		}
		if ( is_page() ) {
			$this->get_schema_data( $db, 'page' );
		}
		echo '<!-- Markup (JSON-LD) structured in schema.org END -->' . PHP_EOL;
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @since   1.0.0
	 * @version 2.3.0
	 * @param   Structuring_Markup_Admin_Db $db
	 * @param   string $output
	 */
	private function get_schema_data ( Structuring_Markup_Admin_Db $db, $output ) {
		$results = $db->get_select_options( $output );

		if ( isset( $results ) ) {
			foreach ( $results as $row ) {
				if ( isset( $row->type ) && isset( $row->activate ) && $row->activate === 'on' ) {
					switch ( $row->type ) {
						case 'article':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_article( unserialize( $row->options ) );
							}
							break;
						case 'blog_posting':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_blog_posting( unserialize( $row->options ) );
							}
							break;
						case 'breadcrumb':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_breadcrumb( unserialize( $row->options ) );
							}
							break;
						case 'event':
							$this->set_schema_event();
							break;
						case 'local_business':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_local_business( unserialize( $row->options ) );
							}
							break;
						case 'news_article':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_news_article( unserialize( $row->options ) );
							}
							break;
						case 'organization':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_organization( unserialize( $row->options ) );
							}
							break;
						case 'website':
							if ( isset( $row->options ) && $row->options ) {
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
	private function set_schema_json ( array $args ) {
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
	private function escape_text_tags ( $text ) {
		return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
	}

	/**
	 * Return image dimensions
	 *
	 * @since   2.3.2
	 * @version 2.3.2
	 * @author  Justin Frydman
	 * @param   string $url
	 * @return  array $dimensions
	 */
	 private function get_image_dimensions ( $url ) {
	 	if( $image = wp_get_attachment_image_src( attachment_url_to_postid( $url ), 'full') ) {
	 		return array( $image[1], $image[2] );
	 	}

	 	if( function_exists('curl_version') ) {
	 		$headers = array('Range: bytes=0-32768');

	 		$curl = curl_init( $url );
	 		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
	 		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	 		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0);
	 		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0);
	 		$data = curl_exec( $curl );
	 		curl_close( $curl );

	 		$image = @imagecreatefromstring( $data );

	 		if( $image ) {
	 			$width  = imagesx( $image );
	 			$height = imagesy( $image );

	 			return array( $width, $height );
	 		}
	 	}

	 	if( $image = @getimagesize( $url ) ) {
	 		return array( $image[0], $image[1] );
	 	}

	 	if( $image = @getimagesize( str_replace('https://', 'http://', $url) ) ) {
	 		return array( $image[0], $image[1] );
	 	}

	 	return false;
	}

	/**
	 * Setting schema.org Article
	 *
	 * @since   1.1.0
	 * @version 2.2.0
	 * @param   array $options
	 */
	private function set_schema_article ( array $options ) {
		global $post;

		$options['logo'] = isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "";

		if ( has_post_thumbnail( $post->ID ) && $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$excerpt = $this->escape_text_tags( $post->post_excerpt );
			$content = $excerpt === "" ? mb_substr( $this->escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;

			$args = array(
				"@context" => "http://schema.org",
				"@type"    => "Article",
				"mainEntityOfPage" => array(
					"@type" => "WebPage",
					"@id"   => get_permalink( $post->ID )
				),
				"headline" => $this->escape_text_tags( $post->post_title ),
				"image"    => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
				"author" => array(
					"@type" => "Person",
					"name"  => $this->escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
				),
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "",
						"width"  => $logo[0],
						"height" => $logo[1]
					)
				),
				"description" => $content
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org BlogPosting
	 *
	 * @since   1.2.0
	 * @version 2.2.0
	 * @param   array $options
	 */
	private function set_schema_blog_posting ( array $options ) {
		global $post;

		$options['logo'] = isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "";

		if ( has_post_thumbnail( $post->ID ) && $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$excerpt = $this->escape_text_tags( $post->post_excerpt );
			$content = $excerpt === "" ? mb_substr( $this->escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;

			$args = array(
				"@context" => "http://schema.org",
				"@type"    => "BlogPosting",
				"mainEntityOfPage" => array(
					"@type" => "WebPage",
					"@id"   => get_permalink( $post->ID )
				),
				"headline" => $this->escape_text_tags( $post->post_title ),
				"image"    => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
				"author" => array(
					"@type" => "Person",
					"name"  => $this->escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
				),
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "",
						"width"  => $logo[0],
						"height" => $logo[1]
					)
				),
				"description" => $content
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org Breadcrumb
	 *
	 * @since    2.0.0
	 * ＠version 2.0.0
	 * @param    array $options
	 */
	private function set_schema_breadcrumb ( array $options ) {
		require_once( plugin_dir_path( __FILE__ ) . 'wp-structuring-short-code-breadcrumb.php' );
		$obj = new Structuring_Markup_ShortCode_Breadcrumb();
		$item_array = $obj->breadcrumb_array_setting( $options );

		if ( $item_array ) {
			/** itemListElement build */
			$item_list_element = array();
			$position = 1;
			foreach ($item_array as $item) {
				$item_list_element[] = array(
					"@type"    => "ListItem",
					"position" => $position,
					"item"     => $item
				);
				$position++;
			}

			/** Breadcrumb Schema build */
			$args = array(
				"@context" => "http://schema.org",
				"@type"    => "BreadcrumbList",
				"itemListElement" => $item_list_element
			);

			$this->set_schema_json($args);
		}
	}

	/**
	 * Setting schema.org Event
	 *
	 * @since   2.1.0
	 * @version 2.1.0
	 */
	private function set_schema_event () {
		global $post;
		$meta = get_post_meta( $post->ID, 'schema_event_post', false );

		if ( isset( $meta[0] ) ) {
			$meta = unserialize( $meta[0] );

			if ( !isset( $meta['schema_event_name']) ) $meta['schema_event_name'] = '';
			if ( !isset( $meta['schema_event_date']) ) $meta['schema_event_date'] = date('Y-m-d');
			if ( !isset( $meta['schema_event_time']) ) $meta['schema_event_time'] = date('h:i');
			if ( !isset( $meta['schema_event_url']) )  $meta['schema_event_url']  = '';
			if ( !isset( $meta['schema_event_place_name'] ) )      $meta['schema_event_place_name']      = '';
			if ( !isset( $meta['schema_event_place_url'] ) )       $meta['schema_event_place_url']       = '';
			if ( !isset( $meta['schema_event_place_address'] ) )   $meta['schema_event_place_address']   = '';
			if ( !isset( $meta['schema_event_offers_price'] ) )    $meta['schema_event_offers_price']    = 0;
			if ( !isset( $meta['schema_event_offers_currency'] ) ) $meta['schema_event_offers_currency'] = '';

			$args = array(
					"@context"  => "http://schema.org",
					"@type"     => "Event",
					"name"      => $this->escape_text_tags( $meta['schema_event_name'] ),
					"startDate" => $this->escape_text_tags( $meta['schema_event_date'] ) . 'T' . $this->escape_text_tags( $meta['schema_event_time'] ),
					"url"       => esc_url( $meta['schema_event_url'] ),
					"location"  => array(
						"@type"   => "Place",
						"sameAs"  => esc_url( $meta['schema_event_place_url'] ),
						"name"    => $this->escape_text_tags( $meta['schema_event_place_name'] ),
						"address" => $this->escape_text_tags( $meta['schema_event_place_address'] )
					),
					"offers"    => array(
						"@type"         => "Offer",
						"price"         => $this->escape_text_tags( $meta['schema_event_offers_price'] ),
						"priceCurrency" => $this->escape_text_tags( $meta['schema_event_offers_currency'] ),
						"url"           => esc_url( $meta['schema_event_url'] )
					)
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org LocalBusiness
	 *
	 * @since   2.3.0
	 * @version 2.3.0
	 * @param   array $options
	 */
	private function set_schema_local_business ( array $options ) {

		/** weekType defined. */
		$week_array = array(
			array("type" => "mon", "display" => "Monday"),
			array("type" => "tue", "display" => "Tuesday"),
			array("type" => "wed", "display" => "Wednesday"),
			array("type" => "thu", "display" => "Thursday"),
			array("type" => "fri", "display" => "Friday"),
			array("type" => "sat", "display" => "Saturday"),
			array("type" => "sun", "display" => "Sunday")
		);

		$args = array(
			"@context"  => "http://schema.org",
			"@type"     => isset( $options['name'] ) ? esc_html( $options['business_type'] ) : "",
			"name"      => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"url"       => isset( $options['name'] ) ? esc_url( $options['url'] ) : "",
			"telephone" => isset( $options['name'] ) ? esc_html( $options['telephone'] ) : ""
		);

		if ( isset( $options['food_active'] ) && $options['food_active'] === 'on' ) {
			if ( isset( $options['menu'] ) && $options['menu'] !== '' ) {
				$args['menu'] = esc_url( $options['menu'] );
			}
			if ( isset( $options['accepts_reservations'] ) && $options['accepts_reservations'] === 'on' ) {
				$args['acceptsReservations'] = "True";
			} else {
				$args['acceptsReservations'] = "False";
			}
		}

		$address_array["address"] = array(
			"@type"           => "PostalAddress",
			"streetAddress"   => isset( $options['name'] ) ? esc_html( $options['street_address'] ) : "",
			"addressLocality" => isset( $options['name'] ) ? esc_html( $options['address_locality'] ) : "",
			"addressRegion"   => isset( $options['name'] ) ? esc_html( $options['address_region'] ) : "",
			"postalCode"      => isset( $options['name'] ) ? esc_html( $options['postal_code'] ) : "",
			"addressCountry"  => isset( $options['name'] ) ? esc_html( $options['address_country'] ) : ""
		);
		$args = array_merge( $args, $address_array );

		if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
			$geo_array["geo"] = array(
				"@type"     => "GeoCoordinates",
				"latitude"  => isset( $options['name'] ) ? esc_html( floatval( $options['latitude'] ) ) : "",
				"longitude" => isset( $options['name'] ) ? esc_html( floatval( $options['longitude'] ) ) : ""
			);
			$args = array_merge( $args, $geo_array );
		}

		foreach ( $week_array as $value ) {
			if ( isset( $options[$value['type']] ) && $options[$value['type']] === 'on' ) {
				$opening_array["openingHoursSpecification"][] = array(
					"@type"     => "OpeningHoursSpecification",
					"dayOfWeek" => $value['display'],
					"opens"     => isset( $options['name'] ) ? esc_html( $options[$value['type'] . '-open'] ) : "",
					"closes"    => isset( $options['name'] ) ? esc_html( $options[$value['type'] . '-close'] ) : ""
				);
				$args = array_merge( $args, $opening_array );
			}
		}

		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org NewsArticle
	 *
	 * @since   1.0.0
	 * @version 2.2.0
	 * @param   array $options
	 */
	private function set_schema_news_article ( array $options ) {
		global $post;

		$options['logo'] = isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "";

		if ( has_post_thumbnail( $post->ID ) && $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$images  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$excerpt = $this->escape_text_tags( $post->post_excerpt );
			$content = $excerpt === "" ? mb_substr( $this->escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;

			$args = array(
				"@context" => "http://schema.org",
				"@type"    => "NewsArticle",
				"mainEntityOfPage" => array(
					"@type" => "WebPage",
					"@id"   => get_permalink( $post->ID )
				),
				"headline" => $this->escape_text_tags( $post->post_title ),
				"image"    => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				),
				"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
				"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
				"author" => array(
					"@type" => "Person",
					"name"  => $this->escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
				),
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => isset( $options['logo'] )  ? esc_url( $options['logo'] ) : "",
						"width"  => $logo[0],
      					"height" => $logo[1]
					)
				),
				"description" => $content
			);
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org Organization
	 *
	 * @since    1.0.0
	 * ＠version 2.2.0
	 * @param array $options
	 */
	private function set_schema_organization ( array $options ) {
		/** Logos */
		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "Organization",
			"name"     => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"url"      => isset( $options['url'] )  ? esc_url( $options['url'] ) : "",
			"logo"     => isset( $options['logo'] ) ? esc_url( $options['logo'] ) : ""
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
	 * @since   1.0.0
	 * @version 2.2.0
	 * @param   array $options
	 */
	private function set_schema_website ( array $options ) {
		$args = array(
			"@context"      => "http://schema.org",
			"@type"         => "WebSite",
			"name"          => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"alternateName" => isset( $options['alternateName'] ) ? esc_html( $options['alternateName'] ) : "",
			"url"           => isset( $options['url'] ) ? esc_url( $options['url'] ) : ""
		);

		if ( isset( $options['potential_action'] ) && $options['potential_action'] === 'on' ) {
			$potential_action["potentialAction"] = array(
				"@type"       => "SearchAction",
				"target"      => isset( $options['target'] ) ? esc_url( $options['target'] ) . "{search_term_string}" : "",
				"query-input" => isset( $options['target'] ) ? "required name=search_term_string" : ""
			);
			$args = array_merge( $args, $potential_action );
		}

		$this->set_schema_json( $args );
	}
}
