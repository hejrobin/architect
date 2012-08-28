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

/* @namespace Store */
namespace Architect\Store;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Cookie
 *
 *	Class used to handle cookie store.
 *
 *	@package Store
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Cookie extends \Architect\Store\Object {

	/**
	 *	@var bool $secure_sockets_layer_transfer Defines whether cookies should be transfered over HTTPS.
	 */
	protected $secure_sockets_layer_transfer = false;

	/**
	 *	@var bool $secure_sockets_layer_transfer Defines whether cookies should be transfered over HTTP, not accessable publicly.
	 */
	protected $http_transfer = false;

	/**
	 *	Constructor
	 *
	 *	Sets default cookie storage options.
	 *
	 *	@return void
	 */
	public function __construct($lifetime = 2592000, $store_path = '/') {

		$this->useCompression(false);

		$this->lifetime = $lifetime;

		$this->store_path = $store_path;

		$this->domain = "." . $_SERVER["SERVER_NAME"];

	}

	/**
	 *	transferOverSecureSocketsLayer
	 *
	 *	Activates SSL security option for cookies.
	 *
	 *	@return void
	 */
	public function transferOverSecureSocketsLayer() {

		$this->secure_sockets_layer_transfer = true;

	}

	/**
	 *	transferOverHTTPOnly
	 *
	 *	Activates HTTP transfer only, if set to true, cookies are only accessable via HTTP.
	 *
	 *	@return void
	 */
	public function transferOverHTTPOnly() {

		$this->http_transfer = true;

	}

	/**
	 *	generateKey
	 *
	 *	Returns hashed string for storeage keys.
	 *
	 *	@param string $key
	 *
	 *	@return string
	 */
	protected function generateKey($key) {

		$key_hash = af_hash($key, $this->fingerprint);

		return $key_hash;

	}

	/**
	 *	has
	 *
	 *	Returns boolean whether data entry exists in data store or not.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return bool
	 */
	public function has($key) {

		if(array_key_exists($this->generateKey($key), $_COOKIE) === true) {

			return true;

		}

		return false;

	}

	/**
	 *	read
	 *
	 *	Returns entry data, if exists, otherwise null.
	 *
	 *	@param string $key Data entry key.
	 *	@param bool $return_expire_time Optional parameter, if set to true method returns expire time instead of data.
	 *
	 *	@return mixed
	 */
	public function read($key, $return_expire_time = false) {

		if($this->has($key) === true) {

			$entry = unserialize($_COOKIE[$this->generateKey($key)]);

			if($entry !== false) {

				return $entry->data;

			}

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

		if(headers_sent() === false) {

			setcookie(
				$this->generateKey($key),
				$entry,
				time() + $this->lifetime,
				$this->store_path,
				$this->domain,
				$this->secure_sockets_layer_transfer,
				$this->http_transfer
			);

		}

	}

	/**
	 *	touch
	 *
	 *	Refreshes expire time of data entry.
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

			if(headers_sent() === false) {

				setcookie(
					$this->generateKey($key),
					'',
					time() - 3600 * 25,
					$this->store_path,
					$this->domain,
					$this->secure_sockets_layer_transfer,
					$this->http_transfer
				);

			}
		}

	}

	/**
	 *	flush
	 *
	 *	Deletes expired data entries.
	 *
	 *	@return void
	 */
	public function flush() {

		foreach($_COOKIE as $key => $entry) {

			$entry = unserialize($entry);

			if($entry !== false) {

				// Validate entry lifetime
				if(time() > $entry->expires) {

					// Delete entry
					$this->delete($key);

				}

			}

		}

	}

}
?>