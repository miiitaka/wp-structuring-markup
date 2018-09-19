<?php
/**
 * Breadcrumb ShortCode Settings
 *
 * @author  Kazuya Takami
 * @version 4.6.0
 * @since   2.0.0
 */
class Structuring_Markup_ShortCode_Breadcrumb {

	/**
	 * ShortCode Display.
	 *
	 * @version 4.5.1
	 * @since   2.0.0
	 * @access  public
	 * @param   array  $options
	 * @param   string $args
	 * @return  string $html
	 */
	public function short_code_display ( array $options, $args ) {
		extract( shortcode_atts( array (
			'id'    => "",
			'class' => ""
		), $args ) );

		$instance = array(
			'id'    => esc_attr( $id ),
			'class' => esc_attr( $class )
		);

		$item_array = $this->breadcrumb_array_setting( $options );
		$html = '';

		if ( isset( $item_array ) && count( $item_array ) > 0 ) {
			$html .= '<!-- Markup (JSON-LD) structured in schema.org Breadcrumb START -->' . PHP_EOL;

			if ( $id !== '' && $class !== '' ) {
				$html .= '<ol id="' . $id . '" class="' . $class . '">';
			} else if ( $id !== '' && $class === '' ) {
				$html .= '<ol id="' . $id . '">';
			} else if ( $id === '' && $class !== '' ) {
				$html .= '<ol class="' . $class . '">';
			} else {
				$html .= '<ol>';
			}
			$html .= PHP_EOL;

			$count  = 1;
			$length = count( $item_array );
			foreach ( $item_array as $item ) {
				$html .= '<li>';
				if ( $count === $length && ( !isset( $options['current_link'] ) || $options['current_link'] !== 'on' ) ) {
					$html .= esc_html( $item['name'] );
				} else {
					$html .= '<a href="' . esc_url( $item['@id'] ) . '">';
					$html .= esc_html( $item['name'] );
					$html .= '</a>';
				}
				$html .= '</li>' . PHP_EOL;
				$count++;
			}
			$html .= '</ol>' . PHP_EOL;
			$html .= '<!-- Markup (JSON-LD) structured in schema.org Breadcrumb END -->' . PHP_EOL;
		}

		return (string) $html;
	}

	/**
	 * Breadcrumb array setting.
	 *
	 * @version 4.6.0
	 * @since   2.0.0
	 * @access  public
	 * @param   array $options
	 * @return  array $item_array
	 */
	public function breadcrumb_array_setting ( array $options ) {
		global $post;

		/** item build */
		$item_array  = array();

		if ( isset( $options['home_url'] ) ) {
			switch ( $options['home_url'] ) {
				case 'home_url':
					$current_url = esc_url( home_url() . $_SERVER['REQUEST_URI'] );
					break;
				case 'site_url':
					$current_url = esc_url( site_url() . $_SERVER['REQUEST_URI'] );
					break;
				default:
					$current_url = esc_url( home_url() . $_SERVER['REQUEST_URI'] );
					break;
			}
		} else {
			$current_url = esc_url( home_url() . $_SERVER['REQUEST_URI'] );
		}

		if ( get_option( 'show_on_front' ) === 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
		} else {
			$front_page_id = null;
		}

		if ( isset( $options['home_on'] ) && $options['home_on'] === 'on' ) {
			if ( isset( $options['home_name'] ) && $options['home_name'] !== '' ) {
				$item_array[] = $this->set_schema_breadcrumb_item( home_url(), $options['home_name'] );
			} else {
				if ( is_null( $front_page_id ) ) {
					$item_array[] = $this->set_schema_breadcrumb_item( home_url(), get_bloginfo( 'name' ) );
				} else {
					$front_page   = get_post( $front_page_id );
					$item_array[] = $this->set_schema_breadcrumb_item( home_url(), esc_html( $front_page->post_title ) );
				}
			}
		}

		if ( is_search() ) {
			$search_query = get_search_query();
			if ( $search_query !== '' ) {
				$item_array[] = $this->set_schema_breadcrumb_item( $current_url, get_search_query() );
			}
		} elseif ( is_tag() ) {
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, single_tag_title( '', false ) );
		} elseif ( is_date() ) {
			$item_array[] = $this->set_schema_breadcrumb_item( get_year_link( get_query_var( 'year' ) ), get_query_var( 'year' ) );
			if ( get_query_var( 'day' ) !== 0 ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ), get_query_var( 'monthnum' ) );
				$item_array[] = $this->set_schema_breadcrumb_item( get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) ), get_query_var( 'day' ) );
			} elseif ( get_query_var( 'monthnum' ) !== 0 ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ), get_query_var( 'monthnum' ) );
			}
		} elseif ( is_category() ) {
			$categories = get_queried_object();
			if( $categories->parent !== 0 ) {
				$ancestors = array_reverse( get_ancestors( $categories->cat_ID, 'category' ) );
				foreach( $ancestors as $ancestor ) {
					$item_array[] = $this->set_schema_breadcrumb_item( get_category_link( $ancestor ), get_cat_name( $ancestor ) );
				}
			}
			$item_array[] = $this->set_schema_breadcrumb_item( get_category_link( $categories->term_id ), $categories->name );
		} elseif ( is_author() ) {
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );
		} elseif ( is_page() && (int) $front_page_id !== $post->ID ) {
			if( $post->post_parent !== 0 ) {
				$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
				foreach( $ancestors as $ancestor ) {
					if ( (int) $front_page_id !== $ancestor ) {
						$item_array[] = $this->set_schema_breadcrumb_item( get_permalink( $ancestor ), get_the_title( $ancestor ) );
					}
				}
			}
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, $post->post_title );
		} elseif ( is_attachment() ) {
			if ( $post->post_parent !== 0 ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_permalink( $post->post_parent ), get_the_title( $post->post_parent ) );
			}
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, $post->post_title );
		} elseif ( is_404() ) {
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, wp_get_document_title() );
		} elseif ( is_post_type_archive() ) {
			if ( get_post_type_archive_link( get_post_type() ) ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_post_type_archive_link( get_post_type() ), post_type_archive_title( '', false) );
			}
		} elseif ( is_archive() ) {
			if ( get_post_type_archive_link( get_post_type() ) ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_post_type_archive_link( get_post_type() ), get_post_type_object( get_post_type() )->label );
			}
			if( is_tax() ){
				$tax_slug  = get_query_var( 'taxonomy' );
				$term_slug = get_query_var( 'term' );
				$term      = get_term_by( "slug", $term_slug, $tax_slug );

				if( $term->parent !== 0 ) {
					$ancestors = array_reverse( get_ancestors( $term->term_taxonomy_id, $tax_slug ) );
					foreach( $ancestors as $ancestor ) {
						$ancestor_term = get_term( $ancestor, $tax_slug );
						$item_array[]  = $this->set_schema_breadcrumb_item( esc_url( get_term_link( $ancestor ) ), esc_html( $ancestor_term->name ) );
					}
				}
				$item_array[] = $this->set_schema_breadcrumb_item( get_term_link( $term_slug, $tax_slug ), esc_html( $term->name ) );
			}
		} elseif ( is_singular( 'post' ) ) {
			$args = $this->set_taxonomy_item( $post->ID, 'category' );
			if ( count( $args ) > 0 ) {
				$item_array = array_merge( $item_array, $args );
			}
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, $post->post_title );
		} elseif ( is_single() ) {
			if ( get_post_type_archive_link( get_post_type() ) ) {
				$item_array[] = $this->set_schema_breadcrumb_item( get_post_type_archive_link( get_post_type() ), get_post_type_object( get_post_type() )->label );
			}
			$taxonomy_names = get_post_taxonomies();
			if ( count( $taxonomy_names ) > 0 ) {
				$args = $this->set_taxonomy_item( $post->ID, $taxonomy_names[0] );
				if ( count( $args ) > 0 ) {
					$item_array = array_merge( $item_array, $args );
				}
			}
			$item_array[] = $this->set_schema_breadcrumb_item( $current_url, $post->post_title );
		}

		if ( !isset( $options['current_on'] ) || $options['current_on'] !== 'on' ) {
			array_pop( $item_array );
		}

		return (array) $item_array;
	}

	/**
	 * taxonomy item settings
	 *
	 * @version 4.2.0
	 * @since   4.0.0
	 * @param   int    $id
	 * @param   string $taxonomy
	 * @return  array  $args
	 */
	private function set_taxonomy_item ( $id, $taxonomy ) {
		$terms       = get_the_terms( $id, $taxonomy );
		$term_bottom = array();
		$args        = array();

		if ( $terms && ! is_wp_error( $terms ) ) {
			$parent_ids  = array();

			foreach ( $terms as $term ) {
				if ( $term->parent != 0 ) {
					$parent_ids[] = $term->parent;
				}
			}
			foreach ( $terms as $term ) {
				if ( !in_array( $term->term_id, $parent_ids ) ) {
					$term_bottom[] = $term->term_id;
				}
			}
		}

		if ( count( $term_bottom ) > 0 ) {
			$ancestors   = array_reverse( get_ancestors( $term_bottom[0], $taxonomy ) );
			$ancestors[] = $term_bottom[0];

			foreach ( $ancestors as $ancestor ) {
				$term   = get_term( $ancestor, $taxonomy );
				$args[] = $this->set_schema_breadcrumb_item( esc_url( get_term_link( $ancestor ) ), esc_html( $term->name ) );
			}
		}
		return (array) $args;
	}

	/**
	 * Breadcrumb item settings
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 * @param   string $id
	 * @param   string $name
	 * @return  array  $args
	 */
	private function set_schema_breadcrumb_item ( $id, $name ) {
		$args = array(
			"@id"  => esc_url( $id ),
			"name" => esc_html( $name )
		);
		return (array) $args;
	}
}