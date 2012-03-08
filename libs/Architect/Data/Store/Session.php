<?php
/**
 *	Architect Framework
 *
 *	Architect Framework is a object oriented and flexible web applications framework built for PHP 5.3 and later.
 *	Architect is built to scale with application size, ranging from small webapps to enterprise-worthy solutions.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://architect.kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/lgpl-2.1.php LGPL
 */

/* @namespace Exceptions */
namespace Architect\Data\Store;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Store
 *
 *	Abstract class used used to create data store objects.
 *
 *	@package Data
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Session extends \Architect\Data\Store {

	/**
	 *	@var string $session_id Unique session ID.
	 */
	protected $session_id;
	
	/**
	 *	Constructor
	 *
	 *	Sets session ID.
	 *
	 *	@return void
	 */
	public function __construct($session_id = null) {
		
		$this->session_id = $session_id;
		
		if($this->session_id === null) {
		
			$this->session_id = af_randstr('unique');
		
		}
		
		session_id($this->session_id);
	
	}

	/**
	 *	generateKey
	 *
	 *	Returns hashed string for
	 *
	 *	@param string $key
	 *
	 *	@return string
	 */
	protected function generateKey($key) {
	
		$key_hash = af_hash($key, $this->fingerprint);
		
		if($this->useCompression() === true) {
			
			$key_hash = gzcompress($key_hash);
			
		}
	
		return $key_hash;
	
	}

	/**
	 *	 has
	 *
	 *	Should return boolean whether data entry exists in data store or not.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return bool
	 */
	public function has($key) {
	
		if(array_key_exists($this->generateKey($key), $_SESSION) === true) {
		
			return true;
		
		}
	
		return false;
	
	}
	
	/**
	 *	read
	 *
	 *	Should return data entry if exists, otherwise null.
	 *
	 *	@param string $key Data entry key.
	 *	@param bool $return_expire_time Optional parameter, if set to true method returns expire time instead of data.
	 *
	 *	@return mixed
	 */
	public function read($key, $return_expire_time = false) {
	
		if($this->has($key) === true) {

			// Get entry
			$entry = $_SESSION[$this->generateKey($key)];
			
			// Uncompress entry if compressed
			if($this->useCompression() === true) {
			
				$entry = gzuncompress($entry);
			
			}
		
			// Unserialize entry
			$entry = unserialize($entry);
			
			// Validate entry lifetime
			if(time() > $entry->expires) {
				
				// Delete entry
				$this->delete($key);
				
				return null;
			
			}
			
			if($return_expire_time === true) {
			
				return $entry->expires;
			
			}
			
			return unserialize($entry->data);
		
		}
		
		return null;
	
	}

	/**
	 *	write
	 *
	 *	Should write a new data entry, and overwrite existing data.
	 *
	 *	@param string $key Data entry key.
	 *	@param mixed $data Data entry.
	 *
	 *	@return void
	 */
	public function write($key, $data) {
	
		$entry = (object) array(

			'data' => serialize($data),

			'expires' => time() + $this->lifetime

		);
		
		// Serialize entry
		$entry = serialize($entry);
		
		// Compress entry if possible
		if($this->useCompression() === true) {
			
			$entry = gzcompress($entry);
			
		}
		
		// Save entry
		$_SESSION[$this->generateKey($key)] = $entry;

	}

	/**
	 *	touch
	 *
	 *	Refreshes expire time of entry.
	 *
	 *	@return void
	 */
	public function touch($key) {
	
		if($this->has($key) === true) {
		
			// Get entry data
			$data = $this->read($key);
			
			// Update expire time to current data
			$this->write($key, $data);
		
		}
	
	}

	/**
	 *	delete
	 *
	 *	Deletes an existing data entry.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return void
	 */
	public function delete($key) {
	
		if($this->has($key) === true) {
		
			unset($_SESSION[$this->generateKey($key)]);
		
		}
	
	}

	/**
	 *	flush
	 *
	 *	Deletes expired cache.
	 *
	 *	@return void
	 */
	public function flush() {
	
		foreach($_SESSION as $key => $entry) {
		
			// Uncompress entry if compressed
			if($this->useCompression() === true) {
			
				$entry = gzuncompress($entry);
			
			}
			
			// Validate entry lifetime
			if(time() > $entry->expires) {
				
				// Delete entry
				unset($_SESSION[$key]);
			
			}
		
		}
	
	}

}
?>