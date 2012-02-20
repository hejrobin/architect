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
 *	Memory
 *
 *	Asserts memory usage logs of items or current process.
 *
 *	@package Logs
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Memory extends Store {

	/**
	 *	@staticvar float $microtime Microtime when log store was created.
	 */
	protected static $microtime;

	/**
	 *	register
	 *
	 *	Creates store and saves microtime when log store was created.
	 *
	 *	@return void
	 */
	public static function register() {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		self::createStore($store_id);
		
		self::$microtime = microtime(true);
		
		self::$store[$store_id]['memory_limit'] = intval(ini_get('memory_limit')) * 1024 * 1024;
	
	}

	/**
	 *	analyze
	 *
	 *	Saves memory peak usage.
	 *
	 *	@return void
	 */
	public static function analyze() {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		self::$store[$store_id]['memory_peak'] = memory_get_peak_usage();
	
	}

	/**
	 *	log
	 *
	 *	Logs a message to console log store.
	 *
	 *	@param mixed $object Mixed variable or an object.
	 *	@param string $message Message to log.
	 *	@param string $name Optional parameter, name of log message.
	 *	@param string $file Optional parameter, file name where store entry was asserted.
	 *	@param int $line Optional parameter, line in store entry file.
	 *
	 *	@return bool
	 */
	public static function log($object, $message, $name = null, $file = null, $line = null) {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		$name = ($name === null || is_string($name) === false) ? $store_id : $name;
		
		$bytes = (is_null($object) === false) ? strlen(serialize($object)) : memory_get_usage();
		
		$type = (defined($name) === true) ? 'constant' : gettype($object);
		
		return self::assertLog($store_id, array(
			'name' => $name,
			'text' => $message,
			'type' => $type,
			'bytes' => $bytes
		), null, $file, $line);
	
	}

}
?>