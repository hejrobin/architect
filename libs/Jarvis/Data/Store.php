<?php
/**
 *	Jarvis
 *
 *	Jarvis is a lightweight profiling and system preformance analyzing libary.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/MIT MIT License
 */

/* @namespace Jarvis */
namespace Jarvis;

/* Deny direct file access */
if(!defined('JARVIS_ROOT_PATH')) exit;

/**
 *	Store
 *
 *	Static data log store object.
 *
 *	@package Data
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Store {

	/**
	 *	@staticvar array $store
	 */
	protected static $store = array();
	
	/**
	 *	createStore
	 *
	 *	Creates a new data store.
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
	 *	assertLog
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
	protected static function assertLog($store_id, $entry, $key = null, $file = null, $line = null) {

		if(array_key_exists($store_id, self::$store) === true) {

			$key = (is_string($key) === true) ? trim($key) : count(self::$store[$store_id]['entries']);
			
			if(is_array($entry) === true) {

				self::$store[$store_id]['entries'][$key] = array(
					'entry' => $entry,
					'time' => time(),
					'file' => $file,
					'line' => $line
				);
				
				self::$store[$store_id]['num_entries'] += 1;
				
				return true;
			}
			
			return false;
		}
		
		return false;
	}

	/**
	 *	getLogs
	 *
	 *	Returns log store.
	 *
	 *	@return array
	 */
	public static function getLogs() {

		return self::$store;

	}

}
?>