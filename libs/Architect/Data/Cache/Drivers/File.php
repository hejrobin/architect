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

/* @namespace Drivers */
namespace Architect\Data\Cache\Drivers;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	File
 *
 *	File cache class.
 *
 *	@package Data
 *	@subpackage Cache
 *	@subpackage Drivers
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class File extends \Architect\Data\Store {

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
	
		return af_hash($key, $this->fingerprint) . ARCH_CACHE_FILE_EXTENSION;
	
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
		
		if(file_exists(ARCH_CACHE_SAVE_PATH . $this->generateKey($key)) === true) {
		
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
			
			// Get file path
			$file_path = ARCH_CACHE_SAVE_PATH . $this->generateKey($key);
			
			// Open and lock file
			$file = fopen($file_path, 'r');

			flock($file, LOCK_SH);
			
			// Get entry
			$entry = file_get_contents($file_path);
			
			fclose($file);
			
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
	 *	@throws Exceptions\DriverException
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
		
		// Get file handle
		$file = fopen(ARCH_CACHE_SAVE_PATH . $this->generateKey($key), 'a+');
		
		// Lock and truncate file
		flock($file, LOCK_EX);
		fseek($file ,0);
		ftruncate($file, 0);
		
		// Throw exception is file write failed
		if(fwrite($file, $entry) === false) {
		
			throw new Exceptions\DriverException(
				'Could not write cache entry',
				'File write failed.',
				__METHOD__, Exceptions\DriverException::RUNTIME_EXCEPTION
			);
		
		}
		
		// Close file
		fclose($file);
	
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
		
			// Remove cache file
			unlink(ARCH_CACHE_SAVE_PATH . $this->generateKey($key));
			
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