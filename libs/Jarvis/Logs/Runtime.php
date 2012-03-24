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
 *	Runtime
 *
 *	Asserts runtime information logs.
 *
 *	@package Logs
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Runtime extends Store {

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
		
		// Server Runtime
		self::assertLog($store_id, array('name' => 'Server Runtime', 'text' => $_SERVER['SERVER_SOFTWARE']));
		
		// Server Protocol
		self::assertLog($store_id, array('name' => 'Server Protocol', 'text' => $_SERVER['SERVER_PROTOCOL']));
		
		// Server Gateway Interface
		self::assertLog($store_id, array('name' => 'Server Gateway Interface', 'text' => $_SERVER['GATEWAY_INTERFACE']));
		
		// Server Document Root
		self::assertLog($store_id, array('name' => 'Server Document Root', 'text' => $_SERVER['DOCUMENT_ROOT']));
		
		// Server Name
		self::assertLog($store_id, array('name' => 'Server Name', 'text' => $_SERVER['SERVER_NAME']));
		
		// Server IP
		self::assertLog($store_id, array('name' => 'Server IP', 'text' => $_SERVER['SERVER_ADDR']));
		
		// Remote IP
		self::assertLog($store_id, array('name' => 'Remote IP', 'text' => $_SERVER['REMOTE_ADDR']));

		// HTTP User Agent
		self::assertLog($store_id, array('name' => 'HTTP Host', 'text' => $_SERVER['HTTP_USER_AGENT']));
		
		// HTTP hostname
		self::assertLog($store_id, array('name' => 'HTTP Host', 'text' => $_SERVER['HTTP_HOST']));
		
		// HTTP Accept
		if(array_key_exists('HTTP_ACCEPT', $_SERVER) === true)
			self::assertLog($store_id, array('name' => 'HTTP Accept', 'text' => $_SERVER['HTTP_ACCEPT']));
		
		// HTTP Accept Encoding
		if(array_key_exists('HTTP_ACCEPT_ENCODING', $_SERVER) === true)
			self::assertLog($store_id, array('name' => 'HTTP Accept Encoding', 'text' => $_SERVER['HTTP_ACCEPT_ENCODING']));
		
		// HTTP Accept Language
		if(array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER) === true)
			self::assertLog($store_id, array('name' => 'HTTP Accept Language', 'text' => $_SERVER['HTTP_ACCEPT_LANGUAGE']));
		
		// HTTP Accept Charset
		if(array_key_exists('HTTP_ACCEPT_CHARSET', $_SERVER) === true)
			self::assertLog($store_id, array('name' => 'HTTP Accept Charset', 'text' => $_SERVER['HTTP_ACCEPT_CHARSET']));
	
	}

}
?>