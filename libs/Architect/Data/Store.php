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
namespace Architect\Data;

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
abstract class Store {

	/**
	 *	@var array $data Data store object. 
	 */
	protected $data = array();

	/**
	 *	@var int $lifetime Time in seconds each data entry is stored, defaults to one hour.
	 */
	protected $lifetime = 3600;

	/**
	 *	@var string $fingerprint Random string used as a salt when keys are generated.
	 */
	protected $fingerprint = '[,";;^"_==;?*<;-&;=]+@)#@/$=/.(]';

	/**
	 *	useCompression
	 *
	 *	Returns boolean whether entry data can be compressed.
	 *
	 *	@return bool
	 */
	protected function useCompression() {
	
		if(function_exists('gzcompress') === true && function_exists('gzuncompress') === true) {
		
			return true;
		
		}
		
		return false;
	
	}

	/**
	 *	generateKey
	 *
	 *	Should return a normalized and hashed string of input variable, should use {@see af_hash}.
	 *
	 *	@param string $key
	 *
	 *	@return string
	 */
	protected abstract function generateKey($key);

	/**
	 *	 has
	 *
	 *	Should return boolean whether data entry exists in data store or not.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return bool
	 */
	public abstract function has($key);
	
	/**
	 *	read
	 *
	 *	Should return data entry if exists, otherwise null.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return mixed
	 */
	public abstract function read($key);

	/**
	 *	write
	 *
	 *	Should write a new data entry, and overwrite existing data.
	 *
	 *	@param string $key Data entry key.
	 *	@param mixed $data Data entry.
	 *	@param int $lifetime Data entry lifetime.
	 *
	 *	@return bool
	 */
	public abstract function write($key, $data);

	/**
	 *	touch
	 *
	 *	Should refresh expire time of entry.
	 *
	 *	@return void
	 */
	public abstract function touch($key);

	/**
	 *	delete
	 *
	 *	Should delete an existing data entry.
	 *
	 *	@param string $key Data entry key.
	 *
	 *	@return void
	 */
	public abstract function delete($key);

	/**
	 *	flush
	 *
	 *	Should flush all data currently in store.
	 *
	 *	@return void
	 */
	public abstract function flush();

}
?>