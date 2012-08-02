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
 *	Memory
 *
 *	Analyzes memory usage of variables, and system memory usage.
 *
 *	@package Records
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Memory extends Collection implements Record {

	/**
	 *	@staticvar float $microtime Microtime when record store store was created.
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
		
		$store_id = getRecordID(__CLASS__);

		self::createStore($store_id);
		
		self::$microtime = microtime(true);

		self::$store[$store_id]['memory_limit'] = intval(ini_get('memory_limit')) * 1024 * 1024;
	
	}

	/**
	 *	analyze
	 *
	 *	Calculates execution application time.
	 *
	 *	@return void
	 */
	public static function analyze() {

		$store_id = getRecordID(__CLASS__);
		
		if(function_exists('memory_get_usage') === true) {

			self::$store[$store_id]['memory_alloc'] = memory_get_usage();

		}

		if(function_exists('memory_get_peak_usage') === true) {

			self::$store[$store_id]['memory_peak'] = memory_get_peak_usage();

		}

	}

	/**
	 *	log
	 *
	 *	Calculates memory usage of input object and a message to each object.
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
		
		$store_id = getRecordID(__CLASS__);
		
		$name = ($name === null || is_string($name) === false) ? $store_id : $name;
		
		$bytes = (is_null($object) === false) ? strlen(serialize($object)) : memory_get_usage();
		
		$type = (defined($name) === true) ? 'constant' : gettype($object);
		
		return self::assertRecord($store_id, array(
			'name' => $name,
			'text' => $message,
			'type' => $type,
			'bytes' => $bytes
		), null, $file, $line);
	
	}

}
?>