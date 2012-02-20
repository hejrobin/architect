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
 *	Benchmark
 *
 *	Asserts benchmark entries to log store.
 *
 *	@package Logs
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Benchmark extends Store {

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
	public function register() {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		self::createStore($store_id);
		
		self::$microtime = microtime(true);
		
		self::$store[$store_id]['max_execution_time'] = intval(ini_get('max_execution_time')) * 1000;
	
	}

	/**
	 *	analyze
	 *
	 *	Calculates execution time.
	 *
	 *	@return void
	 */
	public static function analyze() {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		self::$store[$store_id]['execution_time'] = (microtime(true) - self::$microtime) * 1000;
	
	}

	/**
	 *	log
	 *
	 *	Asserts a benchmark and saves start time, use {@see assert} to assert end time.
	 *
	 *	@param string $key Benchmark identifier.
	 *	@param string $message Message to log.
	 *	@param string $name Optional parameter, name of log message.
	 *	@param string $file Optional parameter, file name where store entry was asserted.
	 *	@param int $line Optional parameter, line in store entry file.
	 *
	 *	@return bool
	 */
	public static function log($key, $message, $name = null, $file = null, $line = null) {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		return self::assertLog($store_id, array(
			'name' => $key,
			'text' => $message,
			'time_start' => microtime(true),
			'time_finish' => null
		), $key, $file, $line);
	
	}

	/**
	 *	assert
	 *
	 *	Sets end time of a benchmark entry if exists.
	 *
	 *	@param string $key Benchmark identifier.
	 *
	 *	@return bool
	 */
	public static function assert($key) {
	
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));

		if(isset(self::$store[$store_id]['entries'][$key]) === true) {
		
			self::$store[$store_id]['entries'][$key]['entry']['time_finish'] = microtime(true);

			$time_start = self::$store[$store_id]['entries'][$key]['entry']['time_start'];
			
			$time_finish = self::$store[$store_id]['entries'][$key]['entry']['time_finish'];
			
			$time_diff = $time_finish - $time_start;
			
			self::$store[$store_id]['entries'][$key]['entry']['time_diff'] = ($time_diff > 0) ? $time_diff : 0;
			
			return true;
		}
	
		return false;
	}

}
?>