<?php
/**
 *	Architect Framework
 *
 *	Architect Framework is a light-weight and scalable object oriented web applications framework built for PHP 5.3 and later.
 *	Architect focuses on handling common tasks and processes used to quickly develop small, medium and large scale applications.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://architect.kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/lgpl-2.1.php LGPL
 */

/* @namespace Cache */
namespace Architect\Store\Cache;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	APC
 *
 *	Cache handler for APC cache storage.
 *
 *	@package Store
 *	@subpackage Cache
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class APC extends \Architect\Store\Object {

	/**
	 *	generateKey
	 *
	 *	Generates cache entry storage key.
	 *
	 *	@param string $key
	 *
	 *	@return string
	 */
	protected function generateKey($key) {
	
		return af_hash($key, $this->fingerprint) . ARCH_CACHE_FILE_EXTENSION;
	
	}

	/**
	 *	 has
	 *
	 *	Returns boolean whether cache data entry exists or not.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return bool
	 */
	public function has($key) {
		
		if(apc_exists($this->generateKey($key)) === true) {
		
			return true;
		
		}
	
		return false;

	}
	
	/**
	 *	read
	 *
	 *	Returns cache data, or null if no data exists.
	 *
	 *	@param string $key Data entry key.
	 *	@param bool $return_expire_time Optional parameter, if set to true method returns expire time instead of data.
	 *
	 *	@return mixed
	 */
	public function read($key, $return_expire_time = false) {
	
		if($this->has($key) === true) {
			
			// Get entry
			$entry = apc_fetch($this->generateKey($key));
			
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
	 *	Writes new data entry overwrites existing data.
	 *
	 *	@param string $key Data entry key.
	 *	@param mixed $data Data entry.
	 *
	 *	@throws Exceptions\CacheException
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
		
		// Save key
		$this->data[$this->generateKey($key)] = $key;
		
		// Throw exception is file write failed
		if(apc_store($key, $entry) === false) {
		
			throw new Exceptions\CacheException(
				'Could not write cache entry',
				'File write failed.',
				__METHOD__, Exceptions\CacheException::RUNTIME_EXCEPTION
			);
		
		}
	
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
			
			// Remove entry
			apc_delete($this->generateKey($key));

			// Remove key from store
			unset($this->data[$this->generateKey($key)]);
		
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
	
		foreach(array_values($this->data) as $key) {
		
			$expires = $this->read($key, true);
			
			if(time() > $expires) {
			
				$this->delete($key);
			
			}
		
		}
	
	}

}
?>