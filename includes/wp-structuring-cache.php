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
	 * The key name
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 */
	private $key; 
	
	/**
	 * The time to live in the cache
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 */
	private $ttl; 
	
	/**
	 * The cache value
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 */
	private $value; 
	
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
	 * @param   string $ttl The time to live in seconds to live in the cache. 0 will never expire
	 * @param    string $value The value to store in the cache, can use Wordpress Easier Expression of Time Constants
	 */
	public function __construct ( $key, $ttl, $value ) {
		assert( !empty( $key ) );
		assert( is_numeric( $ttl ) );
		assert( !empty( $value ) );
		
		$this->key 	= (string) $key;
		$this->ttl 	= (string) $ttl;
		$this->value 	= (string) $value;
	}

	/**
	 * Store a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return bool - If the transient was set properly
	 */	
	public function set() {
		return set_transient( $this->prepared_key(), $this->value, $this->ttl );
	}
	
	/**
	 * Get a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return string $transient_value - The value from the cache
	 */	
	public function get() {
		return get_transient( $this->prepared_key() );
	}
	
	/**
	 * Delete a transient
	 *
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return bool - If the transient was properly deleted
	 */	
	public function delete () {
		return delete_transient( $this->prepared_key() );
	}
	
	/**
	 * Prepare a transient for storage or retrieval 
	 * There is a 40 character transient limit
	 * @since   2.4.2
	 * @version 2.4.2
	 * @return string $prepared_key - The prefixed and MD5'd key
	 */	
	private function prepared_key() {
		return $prefix . md5( $this->key );
	}
}
