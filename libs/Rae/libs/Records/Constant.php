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
 *	Constant
 *
 *	Collects information on all user defined constants.
 *
 *	@package Records
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Constant extends Collection implements Record {

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
	 *	Calculates execution application time.
	 *
	 *	@return void
	 */
	public static function analyze() {

		$store_id = getRecordID(__CLASS__);
		
		$constants = get_defined_constants(true);

		$constants = $constants['user'];
		
		foreach($constants as $constant => $value) {
		
			self::assertRecord($store_id, array(
				'name' => $constant,
				'text' => $value,
				'size' => strlen(serialize($value))
			));
		
		}

	}

}
?>