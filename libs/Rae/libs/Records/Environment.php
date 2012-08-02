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
 *	Environment
 *
 *	Collects information on server environment and configuration.
 *
 *	@package Records
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Environment extends Collection implements Record {

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
	 *	Gathers information on server environment.
	 *
	 *	@return void
	 */
	public static function analyze() {

		$store_id = getRecordID(__CLASS__);

		// Server Runtime
		self::assertRecord($store_id, array('name' => 'Server Runtime', 'text' => $_SERVER['SERVER_SOFTWARE']));
		
		// Server Protocol
		self::assertRecord($store_id, array('name' => 'Server Protocol', 'text' => $_SERVER['SERVER_PROTOCOL']));
		
		// Server Gateway Interface
		self::assertRecord($store_id, array('name' => 'Server Gateway Interface', 'text' => $_SERVER['GATEWAY_INTERFACE']));
		
		// PHP Version
		self::assertRecord($store_id, array('name' => 'PHP Version', 'text' => phpversion()));
		
		// Server Document Root
		self::assertRecord($store_id, array('name' => 'Server Document Root', 'text' => $_SERVER['DOCUMENT_ROOT']));
		
		// Server Name
		self::assertRecord($store_id, array('name' => 'Server Name', 'text' => $_SERVER['SERVER_NAME']));
		
		// Server IP
		self::assertRecord($store_id, array('name' => 'Server IP', 'text' => $_SERVER['SERVER_ADDR']));
		
		// Remote IP
		self::assertRecord($store_id, array('name' => 'Remote IP', 'text' => $_SERVER['REMOTE_ADDR']));

		// HTTP User Agent
		self::assertRecord($store_id, array('name' => 'HTTP Host', 'text' => $_SERVER['HTTP_USER_AGENT']));
		
		// HTTP hostname
		self::assertRecord($store_id, array('name' => 'HTTP Host', 'text' => $_SERVER['HTTP_HOST']));
		
		// HTTP Accept
		if(array_key_exists('HTTP_ACCEPT', $_SERVER) === true)
			self::assertRecord($store_id, array('name' => 'HTTP Accept', 'text' => $_SERVER['HTTP_ACCEPT']));
		
		// HTTP Accept Encoding
		if(array_key_exists('HTTP_ACCEPT_ENCODING', $_SERVER) === true)
			self::assertRecord($store_id, array('name' => 'HTTP Accept Encoding', 'text' => $_SERVER['HTTP_ACCEPT_ENCODING']));
		
		// HTTP Accept Language
		if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) === true)
			self::assertRecord($store_id, array('name' => 'HTTP Accept Language', 'text' => $_SERVER['HTTP_ACCEPT_LANGUAGE']));
		
		// HTTP Accept Charset
		if(array_key_exists('HTTP_ACCEPT_CHARSET', $_SERVER) === true)
			self::assertRecord($store_id, array('name' => 'HTTP Accept Charset', 'text' => $_SERVER['HTTP_ACCEPT_CHARSET']));

	}

}
?>