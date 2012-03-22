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
 *	Constant
 *
 *	Asserts console log messages.
 *
 *	@package Logs
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Constant extends Store {

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
	
	}

	/**
	 *	analyze
	 *
	 *	Gathers information about user defined constants.
	 *
	 *	@return void
	 */
	public static function analyze() {
	
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		$constants = get_defined_constants(true);
		$constants = $constants['user'];
		
		foreach($constants as $constant => $value) {
		
			self::assertLog($store_id, array(
				'name' => $constant,
				'text' => $value,
				'size' => strlen($value)
			));
		
		}
	
	}

}
?>