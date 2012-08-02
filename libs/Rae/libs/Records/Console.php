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
 *	Console
 *
 *	Simple console used to log non-specific messages and events.
 *
 *	@package Records
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Console extends Collection implements Record {

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
		
		self::createStore(getRecordID(__CLASS__));
		
		self::$microtime = microtime(true);
	
	}

	/**
	 *	analyze
	 *
	 *	The analyze method does nothing for Console.
	 *
	 *	@return void
	 */
	public static function analyze() {}

	/**
	 *	log
	 *
	 *	Logs a message to console log store.
	 *
	 *	@param string $message Message to log.
	 *	@param string $name Optional parameter, name of log message.
	 *	@param string $file Optional parameter, file name where store entry was asserted.
	 *	@param int $line Optional parameter, line in store entry file.
	 *
	 *	@return bool
	 */
	public static function log($message, $name = null, $file = null, $line = null) {
		
		$store_id = getRecordID(__CLASS__);
		
		$name = ($name === null || is_string($name) === false) ? $store_id : $name;

		return self::assertRecord($store_id, array(
			'name' => $name,
			'text' => $message,
			'time' => time()
		), null, $file, $line);
	
	}

}
?>