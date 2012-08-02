<?php
/**
 *	Rae
 *
 *	Rae ("Record-Analyze-Evolve") is a lightweight profiling and preformance analyzing library used to benchmark and analyze certain aspect of web based applications.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://kodlabbet.net
 *
 *	@license http://www.opensource.org/licenses/MIT MIT License
 */

/* @namespace Rae */
namespace Rae;

/* Deny direct file access */
if(!defined('RAE_ROOT_PATH')) exit;

/**
 *	Collection
 *
 *	Static data storage class used to hold records of data collected from child classes.
 *
 *	@package Storage
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Collection {

	/**
	 *	@staticvar array $store 
	 */
	protected static $store = array();

	/**
	 *	createStore
	 *
	 *	Creates a new data store within the collection object.
	 *
	 *	@return void
	 */
	protected static function createStore($store_id) {
	
		if(array_key_exists($store_id, self::$store) === false) {
		
			self::$store[$store_id] = array(
			
				'entries' => array(),
				
				'num_entries' => 0
			
			);
		
		}
	
	}

	/**
	 *	assertRecord
	 *
	 *	Asserts a log entry into log store section based on store ID, returns boolean.
	 *
	 *	@param string $store_id Store identifier, see keys in {@see self::$store}.
	 *	@param array $entry Array containing entry data.
	 *	@param string $key Optional parameter, name of a key to specified store entry.
	 *	@param string $file Optional parameter, file name where store entry was asserted.
	 *	@param int $line Optional parameter, line in store entry file.
	 *
	 *	@return bool
	 */
	protected static function assertRecord($store_id, $entry, $key = null, $file = null, $line = null) {

		if(RAE_ENABLED === false)
			return false;

		if(array_key_exists($store_id, self::$store) === true) {

			// Get defined store key, or calculate auto-incremented index
			$key = (is_string($key) === true) ? trim($key) : count(self::$store[$store_id]['entries']);
			
			if(is_array($entry) === true) {

				// Store entry
				self::$store[$store_id]['entries'][$key] = array(
					'entry' => $entry,
					'time' => time(),
					'file' => $file,
					'line' => $line
				);
				
				// Increment record entries number
				self::$store[$store_id]['num_entries'] += 1;
				
				return true;
			}
			
			return false;
		}
		
		return false;
	}

	/**
	 *	getStore
	 *
	 *	Returns collection storage data.
	 *
	 *	@return array
	 */
	public static function getStore() {

		return self::$store;

	}

}
?>