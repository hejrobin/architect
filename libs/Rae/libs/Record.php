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
 *	Record
 *
 *	Interface used to define Record classes for Rae.
 *
 *	@package Records
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
interface Record {

	/**
	 *	register
	 *
	 *	Must contain logic to create store, should use {@see Rae\Collection::createStore} and {@see Rae\getRecordID}.
	 *
	 *	@return void
	 */
	public static function register();

	/**
	 *	analyze
	 *
	 *	Can contain logic to analyze recored data.
	 *
	 *	@return void
	 */
	public static function analyze();

}
?>