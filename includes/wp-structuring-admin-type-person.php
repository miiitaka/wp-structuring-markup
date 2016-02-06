<?php
/**
 * Schema.org Type Person
 *
 * @author  Kazuya Takami
 * @version 2.4.0
 * @since   2.4.0
 * @see     wp-structuring-admin-db.php
 * @link    https://schema.org/Person
 * @link    https://developers.google.com/structured-data/customize/social-profiles
 */
class Structuring_Markup_Type_Person {

	/**
	 * Variable definition.
	 *
	 * @since   2.4.0
	 * @version 2.4.0
	 */
	/** Social Profile */
	private $social_array = array(
		array("type" => "facebook",   "display" => "Facebook"),
		array("type" => "twitter",    "display" => "Twitter"),
		array("type" => "google",     "display" => "Google+"),
		array("type" => "instagram",  "display" => "Instagram"),
		array("type" => "youtube",    "display" => "Youtube"),
		array("type" => "linkedin",   "display" => "LinkedIn"),
		array("type" => "myspace",    "display" => "Myspace"),
		array("type" => "pinterest",  "display" => "Pinterest"),
		array("type" => "soundcloud", "display" => "SoundCloud"),
		array("type" => "tumblr",     "display" => "Tumblr")
	);

	/**
	 * Constructor Define.
	 *
	 * @since   2.4.0
	 * @version 2.4.0
	 * @param   array $option
	 */
	public function __construct ( array $option ) {
		/** Default Value Set */
		if ( empty( $option ) ) {
			$option = $this->get_default_options( $option );
		}
		$this->page_render( $option );
	}

	/**
	 * Form Layout Render
	 *
	 * @since   2.4.0
	 * @version 2.4.0
	 * @param   array $option
	 */
	private function page_render ( array $option ) {
		/** Basic Settings */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Basic Settings</caption>';
		$html .= '<tr><th><label for="name">Name :</label></th><td>';
		$html .= '<input type="text" name="option[' . "name" . ']" id="name" class="regular-text" required value="' . esc_attr( $option['name'] ) . '">';
		$html .= '<small>Default : bloginfo("name")</small>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="url">url :</label></th><td>';
		$html .= '<input type="text" name="option[' . "url" . ']" id="url" class="regular-text" required value="' . esc_attr( $option['url'] ) . '">';
		$html .= '<small>Default : bloginfo("url")</small>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		/** Social Profiles */
		$html  = '<table class="schema-admin-table">';
		$html .= '<caption>Social Profiles</caption>';
		foreach ( $this->social_array as $value ) {
			$html .= '<tr><th><label for="' . $value['type'] . '">' . $value['display'] . ' :</label></th><td>';
			$html .= '<input type="text" name="option[' . "social" . '][' . $value['type'] . ']" id="' . $value['type'] . '" class="regular-text" value="' . esc_attr( $option['social'][$value['type']] ) . '">';
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		echo $html;

		echo '<p>Setting Knowledge : <a href="https://developers.google.com/structured-data/customize/social-profiles" target="_blank">https://developers.google.com/structured-data/customize/social-profiles</a></p>';
		submit_button();
	}

	/**
	 * Return the default options array
	 *
	 * @since   2.4.0
	 * @version 2.4.0
	 * @param   array $args
	 * @return  array $args
	 */
	private function get_default_options ( array $args ) {
		$args['name'] = get_bloginfo('name');
		$args['url']  = get_bloginfo('url');

		foreach ( $this->social_array as $value ) {
			$args['social'][$value['type']] = '';
		}

		return (array) $args;
	}
}