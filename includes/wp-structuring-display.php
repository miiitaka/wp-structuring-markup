<?php
/**
 * Schema.org Display
 *
 * @author  Kazuya Takami
 * @author  Justin Frydman
 * @version 3.2.4
 * @since   1.0.0
 */
class Structuring_Markup_Display {

	/**
	 * Text Domain
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	private $text_domain = 'wp-structuring-markup';

	/**
	 * Constructor Define.
	 *
	 * @version 3.1.4
	 * @since   1.0.0
	 * @param   string $version
	 */
	public function __construct ( $version ) {
		$db = new Structuring_Markup_Admin_Db();
		$this->set_schema( $db, $version );
	}

	/**
	 * Setting schema.org
	 *
	 * @version 3.1.4
	 * @since   1.0.0
	 * @param   Structuring_Markup_Admin_Db $db
	 * @param   string $version
	 */
	private function set_schema ( Structuring_Markup_Admin_Db $db, $version ) {
		$structuring_markup_args = $db->get_list_options();

		echo '<!-- Markup (JSON-LD) structured in schema.org ver.' . $version . ' START -->' . PHP_EOL;

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
		echo '<!-- Markup (JSON-LD) structured in schema.org END -->' . PHP_EOL;
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @version 3.1.0
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
						case 'person':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_person( unserialize( $row->options ) );
							}
							break;
						case 'site_navigation':
							if ( isset( $row->options ) && $row->options ) {
								$this->set_schema_site_navigation( unserialize( $row->options ) );
							}
							break;
						case 'video':
							$this->set_schema_video();
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
	 * @since 3.0.0
	 * @since 1.0.0
	 * @param array   $args
	 * @param boolean $error
	 */
	private function set_schema_json ( array $args, $error = false ) {
		if ( $error ) {
			/** Error Display */
			if ( isset( $args["@type"] ) ) {
				foreach ( $args["message"] as $message ) {
					echo "<!-- Schema.org ", $args["@type"], " : ", $message, " -->", PHP_EOL;
				}
			}
		} else {
			echo '<script type="application/ld+json">', PHP_EOL;
			echo json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ), PHP_EOL;
			echo '</script>', PHP_EOL;
		}
	}

	/**
	 * Setting JSON-LD Template
	 *
	 * @version 3.1.0
	 * @since   1.1.3
	 * @param   string $text
	 * @return  string $text
	 */
	private function escape_text ( $text ) {
		$text = strip_tags( $text );
		$text = strip_shortcodes( $text );
		$text = str_replace( array( "\r", "\n" ), '', $text );

		return (string) $text;
	}

	/**
	 * Return image dimensions
	 *
	 * @version 3.2.1
	 * @since   2.3.3
	 * @author  Justin Frydman
	 * @author  Kazuya Takami
	 * @param   string $url
	 * @return  array | boolean $dimensions
	 */
	private function get_image_dimensions ( $url ) {
		$image = wp_get_image_editor( $url );

		if ( ! is_wp_error( $image ) ) {
			return $image->get_size();
		} else {
			return __return_false();
		}
	}

	/**
	 * Setting schema.org Article
	 *
	 * @version 3.2.4
	 * @since   1.1.0
	 * @param   array $options
	 */
	private function set_schema_article ( array $options ) {
		global $post;

		$excerpt = $this->escape_text( $post->post_excerpt );
		$content = $excerpt === "" ? mb_substr( $this->escape_text( $post->post_content ), 0, 110 ) : $excerpt;

		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "Article",
			"mainEntityOfPage" => array(
				"@type" => "WebPage",
				"@id"   => get_permalink( $post->ID )
			),
			"headline"      => mb_substr( esc_html( $post->post_title ), 0, 110 ),
			"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
			"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
			"author" => array(
				"@type" => "Person",
				"name"  => esc_html( get_the_author_meta( 'display_name', $post->post_author ) )
			),
			"description" => $content
		);

		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$images_args = array(
				"image" => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				)
			);
			$args = array_merge( $args, $images_args );
		}

		$options['logo'] = isset( $options['logo'] ) ? esc_html( $options['logo'] ) : "";
		if ( $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => $logo['width'],
						"height" => $logo['height']
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		} else if ( !empty( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => isset( $options['logo-width'] )  ? (int) $options['logo-width']  : 0,
						"height" => isset( $options['logo-height'] ) ? (int) $options['logo-height'] : 0
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		}

		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org BlogPosting
	 *
	 * @version 3.2.4
	 * @since   1.2.0
	 * @param   array $options
	 */
	private function set_schema_blog_posting ( array $options ) {
		global $post;

		$excerpt = $this->escape_text( $post->post_excerpt );
		$content = $excerpt === "" ? mb_substr( $this->escape_text( $post->post_content ), 0, 110 ) : $excerpt;

		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "BlogPosting",
			"mainEntityOfPage" => array(
				"@type" => "WebPage",
				"@id"   => get_permalink( $post->ID )
			),
			"headline"      => mb_substr( esc_html( $post->post_title ), 0, 110 ),
			"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
			"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
			"author" => array(
				"@type" => "Person",
				"name"  => esc_html( get_the_author_meta( 'display_name', $post->post_author ) )
			),
			"description" => $content
		);

		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

			$images_args = array(
				"image" => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				)
			);
			$args = array_merge( $args, $images_args );
		}

		$options['logo'] = isset( $options['logo'] ) ? esc_html( $options['logo'] ) : "";
		if ( $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => $logo['width'],
						"height" => $logo['height']
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		} else if ( !empty( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => isset( $options['logo-width'] )  ? (int) $options['logo-width']  : 0,
						"height" => isset( $options['logo-height'] ) ? (int) $options['logo-height'] : 0
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		}

		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org Breadcrumb
	 *
	 * @version 3.1.0
	 * @since   2.0.0
	 * @param   array $options
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
				"@context"        => "http://schema.org",
				"@type"           => "BreadcrumbList",
				"itemListElement" => $item_list_element
			);

			$this->set_schema_json($args);
		}
	}

	/**
	 * Setting schema.org Event
	 *
	 * @version 3.2.3
	 * @since   2.1.0
	 */
	private function set_schema_event () {
		global $post;
		$meta = get_post_meta( $post->ID, 'schema_event_post', false );

		if ( isset( $meta[0] ) ) {
			$meta = unserialize( $meta[0] );

			/* required items */
			if ( !isset( $meta['schema_event_type']) )             $meta['schema_event_type']            = 'Event';
			if ( !isset( $meta['schema_event_name']) )             $meta['schema_event_name']            = '';
			if ( !isset( $meta['schema_event_date']) )             $meta['schema_event_date']            = date('Y-m-d');
			if ( !isset( $meta['schema_event_time']) )             $meta['schema_event_time']            = date('h:i');
			if ( !isset( $meta['schema_event_url']) )              $meta['schema_event_url']             = '';
			if ( !isset( $meta['schema_event_place_name'] ) )      $meta['schema_event_place_name']      = '';
			if ( !isset( $meta['schema_event_place_url'] ) )       $meta['schema_event_place_url']       = '';
			if ( !isset( $meta['schema_event_place_address'] ) )   $meta['schema_event_place_address']   = '';
			if ( !isset( $meta['schema_event_offers_price'] ) )    $meta['schema_event_offers_price']    = 0;
			if ( !isset( $meta['schema_event_offers_currency'] ) ) $meta['schema_event_offers_currency'] = '';

			$args = array(
				"@context"  => "http://schema.org",
				"@type"     => esc_html( $meta['schema_event_type'] ),
				"name"      => esc_html( $meta['schema_event_name'] ),
				"startDate" => esc_html( $meta['schema_event_date'] ) . 'T' . esc_html( $meta['schema_event_time'] ),
				"url"       => esc_url( $meta['schema_event_url'] ),
				"location"  => array(
					"@type"   => "Place",
					"sameAs"  => esc_url( $meta['schema_event_place_url'] ),
					"name"    => esc_html( $meta['schema_event_place_name'] ),
					"address" => esc_html( $meta['schema_event_place_address'] )
				),
				"offers"    => array(
					"@type"         => "Offer",
					"price"         => esc_html( $meta['schema_event_offers_price'] ),
					"priceCurrency" => esc_html( $meta['schema_event_offers_currency'] ),
					"url"           => esc_url( $meta['schema_event_url'] )
				)
			);

			/* recommended items */
			if ( isset( $meta['schema_event_description'] ) && $meta['schema_event_description'] !== '' ) {
				$args['description'] = esc_html( $meta['schema_event_description'] );
			}
			if ( isset( $meta['schema_event_image'] ) && $meta['schema_event_image'] !== '' ) {
				$args['image'] = esc_html( $meta['schema_event_image'] );
			}
			if ( isset( $meta['schema_event_date_end'] ) && $meta['schema_event_date_end'] !== '' && isset( $meta['schema_event_time_end'] ) && $meta['schema_event_time_end'] !== '' ) {
				$args['endDate'] = esc_html( $meta['schema_event_date_end'] ) . 'T' . esc_html( $meta['schema_event_time_end'] );
			}

			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org LocalBusiness
	 *
	 * @version 3.1.4
	 * @since   2.3.0
	 * @param   array $options
	 */
	private function set_schema_local_business ( array $options ) {

		/** weekType defined. */
		$week_array = array(
			array( "type" => "Mo", "display" => "Monday" ),
			array( "type" => "Tu", "display" => "Tuesday" ),
			array( "type" => "We", "display" => "Wednesday" ),
			array( "type" => "Th", "display" => "Thursday" ),
			array( "type" => "Fr", "display" => "Friday" ),
			array( "type" => "Sa", "display" => "Saturday" ),
			array( "type" => "Su", "display" => "Sunday" )
		);

		$args = array(
			"@context"  => "http://schema.org",
			"@type"     => isset( $options['business_type'] ) ? esc_html( $options['business_type'] ) : "",
			"name"      => isset( $options['name'] )          ? esc_html( $options['name'] ) : "",
			"image"     => isset( $options['image'] )         ? esc_html( $options['image'] ) : "",
			"url"       => isset( $options['url'] )           ? esc_url( $options['url'] ) : "",
			"telephone" => isset( $options['telephone'] )     ? esc_html( $options['telephone'] ) : ""
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
			if ( isset( $options['serves_cuisine'] ) && $options['serves_cuisine'] !== '' ) {
				$args['servesCuisine'] = esc_html( $options['serves_cuisine'] );
			}
		}

		$address_array["address"] = array(
			"@type"           => "PostalAddress",
			"streetAddress"   => isset( $options['street_address'] )   ? esc_html( $options['street_address'] ) : "",
			"addressLocality" => isset( $options['address_locality'] ) ? esc_html( $options['address_locality'] ) : "",
			"addressRegion"   => isset( $options['address_region'] )   ? esc_html( $options['address_region'] ) : "",
			"postalCode"      => isset( $options['postal_code'] )      ? esc_html( $options['postal_code'] ) : "",
			"addressCountry"  => isset( $options['address_country'] )  ? esc_html( $options['address_country'] ) : ""
		);
		$args      = array_merge( $args, $address_array );
		$geo_array = array();

		if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
			$geo_array["geo"] = array(
				"@type"     => "GeoCoordinates",
				"latitude"  => isset( $options['latitude'] ) ? esc_html(floatval($options['latitude'])) : "",
				"longitude" => isset( $options['longitude'] ) ? esc_html(floatval($options['longitude'])) : ""
			);
		}

		if ( isset( $options['geo_circle_active'] ) && $options['geo_circle_active'] === 'on' ) {
			$place_array["location"]        = array( "@type" => "Place" );
			$place_array["location"]["geo"] = array(
				"@type"     => "GeoCircle",
				"geoRadius" => isset( $options['geo_circle_radius'] )  ? esc_html( floatval( $options['geo_circle_radius'] ) ) : ""
			);
			if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
				$place_array["location"]["geo"]["geoMidpoint"] = $geo_array["geo"];
			}
			$args = array_merge( $args, $place_array );
		} else {
			if ( isset( $options['geo_active'] ) && $options['geo_active'] === 'on' ) {
				$args = array_merge( $args, $geo_array );
			}
		}

		/* openingHours */
		$active_days = array();
		foreach ( $week_array as $value ) {
			if ( isset( $options[$value['type']] ) && $options[$value['type']] === 'on' ) {
				$active_days[$value['type']] = $options['week'][$value['type']];
			}
		}

		if( !empty( $active_days ) ) {

			$obj = new Structuring_Markup_Opening_Hours( $active_days );
			$opening_hours = $obj->display();

			$opening_array["openingHours"] = array();

			foreach( $opening_hours as $value ) {
				$opening_array["openingHours"][] = $value;
			}

			$args = array_merge( $args, $opening_array );

		}

		if ( isset( $options['holiday_active'] ) && $options['holiday_active'] === 'on' ) {
			$holiday_array["openingHoursSpecification"] = array(
				"@type"        => "OpeningHoursSpecification",
				"opens"        => isset( $options['holiday_open'] ) ? esc_html( $options['holiday_open'] ) : "",
				"closes"       => isset( $options['holiday_close'] ) ? esc_html( $options['holiday_close'] ) : "",
				"validFrom"    => isset( $options['holiday_valid_from'] ) ? esc_html( $options['holiday_valid_from'] ) : "",
				"validThrough" => isset( $options['holiday_valid_through'] ) ? esc_html( $options['holiday_valid_through'] ) : ""
			);
			$args = array_merge( $args, $holiday_array );
		}

		if ( isset( $options['price_range'] ) && $options['price_range'] !== '' ) {
			$price_array["priceRange"] = $options['price_range'];
			$args = array_merge( $args, $price_array );
		}

		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org NewsArticle
	 *
	 * @version 3.2.4
	 * @since   1.0.0
	 * @param   array $options
	 */
	private function set_schema_news_article ( array $options ) {
		global $post;

		$excerpt = $this->escape_text( $post->post_excerpt );
		$content = $excerpt === "" ? mb_substr( $this->escape_text( $post->post_content ), 0, 110 ) : $excerpt;

		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "NewsArticle",
			"mainEntityOfPage" => array(
				"@type" => "WebPage",
				"@id"   => get_permalink( $post->ID )
			),
			"headline"      => mb_substr( esc_html( $post->post_title ), 0, 110 ),
			"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
			"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
			"author" => array(
				"@type" => "Person",
				"name"  => esc_html( get_the_author_meta( 'display_name', $post->post_author ) )
			),
			"description" => $content
		);

		if ( has_post_thumbnail( $post->ID ) ) {
			$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

			$images_args = array(
				"image"    => array(
					"@type"  => "ImageObject",
					"url"    => $images[0],
					"width"  => $images[1],
					"height" => $images[2]
				)
			);
			$args = array_merge( $args, $images_args );
		}

		$options['logo'] = isset( $options['logo'] )  ? esc_html( $options['logo'] ) : "";
		if ( $logo = $this->get_image_dimensions( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => $logo['width'],
						"height" => $logo['height']
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		} else if ( !empty( $options['logo'] ) ) {
			$publisher_args = array(
				"publisher" => array(
					"@type" => "Organization",
					"name"  => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
					"logo"  => array(
						"@type"  => "ImageObject",
						"url"    => $options['logo'],
						"width"  => isset( $options['logo-width'] )  ? (int) $options['logo-width']  : 0,
						"height" => isset( $options['logo-height'] ) ? (int) $options['logo-height'] : 0
					)
				)
			);
			$args = array_merge( $args, $publisher_args );
		}

		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org Organization
	 *
	 * @version 3.2.0
	 * @since   1.0.0
	 * @param   array $options
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
			$contact_point_data = array(
				"@type"       => "ContactPoint",
				"telephone"   => isset( $options['telephone'] )    ? esc_html( $options['telephone'] ) : "",
				"contactType" => isset( $options['contact_type'] ) ? esc_html( $options['contact_type'] ) : ""
			);

			if ( !empty( $options['email'] ) ) {
				$contact_point_data['email'] = isset( $options['email'] ) ? esc_html( $options['email'] ) : "";
			}
			if ( !empty( $options['area_served'] ) ) {
				$contact_point_data['areaServed'][] = isset( $options['area_served'] ) ? esc_html( $options['area_served'] ) : "";
			}
			if ( isset( $options['contact_point_1'] ) &&  $options['contact_point_1'] === 'on' ) {
				$contact_point_data['contactOption'][] = 'HearingImpairedSupported';
			}
			if ( isset( $options['contact_point_2'] ) &&  $options['contact_point_2'] === 'on' ) {
				$contact_point_data['contactOption'][] = 'TollFree';
			}
			if ( !empty( $options['available_language'] ) ) {
				$contact_point_data['availableLanguage'][] = isset( $options['available_language'] ) ? esc_html( $options['available_language'] ) : "";
			}

			$contact_point["contactPoint"] = array( $contact_point_data	);
			$args = array_merge( $args, $contact_point );
		}

		/** Social Profiles */
		if ( isset( $options['social'] ) ) {
			$socials["sameAs"] = array();

			foreach ( $options['social'] as $value ) {
				if ( $value ) {
					$socials["sameAs"][] = esc_url( $value );
				}
			}
			if ( count( $socials["sameAs"] ) > 0 ) {
				$args = array_merge( $args, $socials );
			}
		}
		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org Person
	 *
	 * @version 3.1.2
	 * @since   2.4.0
	 * @param   array $options
	 */
	private function set_schema_person ( array $options ) {
		/** Logos */
		$args = array(
			"@context" => "http://schema.org",
			"@type"    => "Person",
			"name"     => isset( $options['name'] ) ? esc_html( $options['name'] ) : "",
			"url"      => isset( $options['url'] )  ? esc_url( $options['url'] )   : ""
		);

		/** Place */
		if ( isset( $options['addressCountry'] ) ) {
			$place["homeLocation"] = array(
				"@type"   => "Place",
				"address" => array(
					"@type"          => "PostalAddress",
					"addressCountry" => $options['addressCountry']
				)
			);
			$args = array_merge( $args, $place );
		}

		/** Social Profiles */
		if ( isset( $options['social'] ) ) {
			$socials["sameAs"] = array();

			foreach ( $options['social'] as $value ) {
				if ( !empty( $value ) ) {
					$socials["sameAs"][] = esc_html( $value );
				}
			}
			if ( count( $socials["sameAs"] ) > 0 ) {
				$args = array_merge( $args, $socials );
			}
		}
		$this->set_schema_json( $args );
	}

	/**
	 * Setting schema.org Site Navigation
	 *
	 * @version 3.2.3
	 * @since   3.1.0
	 * @param   array $options
	 */
	private function set_schema_site_navigation ( array $options ) {
		if ( isset( $options['menu_name'] ) && wp_get_nav_menu_items( $options['menu_name'] ) ) {
			$items_array = wp_get_nav_menu_items( $options['menu_name'] );
			$name_array  = array();
			$url_array   = array();

			foreach ( (array) $items_array as $key => $menu_item ) {
				$url_array[]  = $menu_item->url;
				$name_array[] = $menu_item->title;
			}

			if ( count( $items_array ) > 0 ) {
				$args = array(
					"@context" => "http://schema.org",
					"@type"    => "SiteNavigationElement",
					"name"     => $name_array,
					"url"      => $url_array
				);
				$this->set_schema_json ( $args );
			}
		}
	}

	/**
	 * Setting schema.org Video
	 *
	 * @version 3.2.3
	 * @since   3.0.0
	 */
	private function set_schema_video () {
		global $post;
		$meta = get_post_meta( $post->ID, 'schema_video_post', false );

		if ( isset( $meta[0] ) ) {
			$meta = unserialize( $meta[0] );

			if ( has_post_thumbnail( $post->ID ) ) {
				$images  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				$excerpt = $this->escape_text( $post->post_excerpt );
				$content = $excerpt === "" ? mb_substr( $this->escape_text( $post->post_content ), 0, 110 ) : $excerpt;
			} else {
				$images[0] = '';
				$content   = '';
			}

			/* required items */
			if ( !isset( $meta['schema_video_name']) )           $meta['schema_video_name']        = esc_html( $post->post_title );
			if ( !isset( $meta['schema_video_description'] ) )   $meta['schema_video_description'] = esc_html( $content );
			if ( !isset( $meta['schema_video_thumbnail_url'] ) ) $meta['schema_video_description'] = esc_html( $images[0] );
			if ( !isset( $meta['schema_video_upload_date'] ) )   $meta['schema_video_upload_date'] = get_post_modified_time( 'Y-m-d', __return_false(), $post->ID );
			if ( !isset( $meta['schema_video_upload_time'] ) )   $meta['schema_video_upload_time'] = get_post_modified_time( 'H:i:s', __return_false(), $post->ID );

			$args = array(
				"@context"     => "http://schema.org",
				"@type"        => "VideoObject",
				"name"         => esc_html( $meta['schema_video_name'] ),
				"description"  => esc_html( $meta['schema_video_description'] ),
				"thumbnailUrl" => esc_html( $meta['schema_video_thumbnail_url'] ),
				"uploadDate"   => esc_html( $meta['schema_video_upload_date'] ) . 'T' . esc_html( $meta['schema_video_upload_time'] )
			);

			/* recommended items */
			if ( isset( $meta['schema_video_duration'] ) && $meta['schema_video_duration'] !== '' ) {
				$args["duration"] = esc_html( $meta['schema_video_duration'] );
			}
			if ( isset( $meta['schema_video_content_url'] ) && $meta['schema_video_content_url'] !== '' ) {
				$args["contentUrl"] = esc_url( $meta['schema_video_content_url'] );
			}
			if ( isset( $meta['schema_video_embed_url'] ) && $meta['schema_video_embed_url'] !== '' ) {
				$args["embedUrl"] = esc_url( $meta['schema_video_embed_url'] );
			}
			if ( isset( $meta['schema_video_interaction_count'] ) && $meta['schema_video_interaction_count'] !== '' ) {
				$args["interactionCount"] = esc_html( $meta['schema_video_interaction_count'] );
			}
			if ( isset( $meta['schema_video_expires_date'] ) && $meta['schema_video_expires_date'] !== '' && isset( $meta['schema_video_expires_time'] ) && $meta['schema_video_expires_time'] !== '' ) {
				$args["expires"] = esc_html( $meta['schema_video_expires_date'] ) . 'T' . esc_html( $meta['schema_video_expires_time'] );
			}
			$this->set_schema_json( $args );
		}
	}

	/**
	 * Setting schema.org WebSite
	 *
	 * @version 3.1.0
	 * @since   1.0.0
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

		$search_array = array();

		if ( isset( $options['potential_action'] ) && $options['potential_action'] === 'on' ) {
			$action_array = array(
				"@type"       => "SearchAction",
				"target"      => isset( $options['target'] ) ? esc_url( $options['target'] ) . "{search_term_string}" : "",
				"query-input" => isset( $options['target'] ) ? "required name=search_term_string" : ""
			);
			$search_array[] = $action_array;
		}

		if ( count( $search_array ) > 0 ) {
			if ( isset( $options['potential_action_app'] ) && $options['potential_action_app'] === 'on' ) {
				$action_array = array(
					"@type"       => "SearchAction",
					"target"      => isset( $options['target_app'] ) ? $options['target_app'] . "{search_term_string}" : "",
					"query-input" => isset( $options['target_app'] ) ? "required name=search_term_string" : ""
				);
				$search_array[] = $action_array;
			}

			$potential_action["potentialAction"] = $search_array;
			$args = array_merge( $args, $potential_action );
		}

		$this->set_schema_json( $args );
	}
}