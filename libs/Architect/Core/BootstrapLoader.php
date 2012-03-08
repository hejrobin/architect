<?php
/**
 *	Architect Framework
 *
 *	Architect Framework is a object oriented and flexible web applications framework built for PHP 5.3 and later.
 *	Architect is built to scale with application size, ranging from small webapps to enterprise-worthy solutions.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://architect.kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/lgpl-2.1.php LGPL
 */

/* @namespace Core */
namespace Architect\Core;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	BootstrapLoader
 *
 *	Static class used to load custom bootstraps files before delegation.
 *
 *	@package Core
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class BootstrapLoader {

	/**
	 *	@staticvar array $store File store.
	 */
	protected static $store = array();

	/**
	 *	@staticvar array $package_ignore_list Packages to ignore when autoloading bootstrap files.
	 */
	public static $package_ignore_list = array('Architect', 'Jarvis');

	/**
	 *	assert
	 *
	 *	Asserts a file to file store.
	 *
	 *	@param string $file_path File to import.
	 *
	 *	@return void
	 */
	public static function assert($file_path) {
	
		if(array_key_exists($file_path, self::$store) !== true) {
		
			self::$store[] = $file_path;
		
		}
	
	}

	/**
	 *	import
	 *
	 *	Imports existing files from file store.
	 *
	 *	@return void
	 */
	public static function import() {
		
		self::autoload();
		
		foreach(self::$store as $file_path) {
		
			if(file_exists($file_path) === true) {
			
				require_once($file_path);
			
			}
		
		}
	
	}

	/**
	 *	autoload
	 *
	 *	Iterates through each library in the library path and attempts to load it's bootstrap file.
	 *
	 *	@return void
	 */
	public static function autoload() {
	
		$iterator = new \DirectoryIterator(ARCH_LIBRARY_PATH);
		
		foreach($iterator as $obj) {
		
			$file_path = $obj->getPathname() . DIRECTORY_SEPARATOR . 'Bootstrap.php';
			
			if(file_exists($file_path) === true && is_readable($file_path) === true) {
			
				self::assert($file_path);
			
			}
		
		}
	
	}

}
?>