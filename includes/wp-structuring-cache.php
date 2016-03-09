<?php
/**
 * WP Structuring Markup Transient Cache
 *
 * @author  Justin Frydman
 * @since   2.4.2
 * @version 2.4.2
 */
class Structuring_Markup_Cache {
	
	/**
	 * The key to be stored
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 */
	private $key; 
	
	/**
	 * The prefix for a stored transient
	 * This prefix should never exceed 7 characters
	 * 
	 * @since   2.4.2
	 * @version 2.4.2
	 */
	private $prefix = 'struct_'; 	
	
	/**
	 * Constructor Define.
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @param   string $key Unique key for this transient
	 */
	public function __construct ( $key ) {
		assert( !empty( $key ) );
		
		$this->key = (string) $key;
	}

	/**
	 * Store a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @see     https://codex.wordpress.org/Easier_Expression_of_Time_Constants
	 * @param   string $value - The value to be stored in the cache
	 * @param   string $ttl - The time to live in the cache. Wordpress Time Constants can be used
	 * @return  bool - If the transient was set properly
	 */	
	public function set( $value, $ttl ) {
		assert( !empty( $value ) );
		assert( !empty( $ttl ) );
		
		return set_transient( $this->prepared_key(), $value, $ttl );
	}
	
	/**
	 * Get a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return  string $transient_value - The value from the cache
	 */	
	public function get() {
		return get_transient( $this->prepared_key() );
	}
	
	/**
	 * Delete a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return  bool - If the transient was properly deleted
	 */	
	public function delete () {
		return delete_transient( $this->prepared_key() );
	}
	
	/**
	 * Prepare a transient for storage or retrieval 
	 * There is a 40 character transient limit
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return  string $prepared_key - The prefixed and MD5'd key
	 */	
	private function prepared_key() {
		return $this->prefix . md5( $this->key );
	}
}