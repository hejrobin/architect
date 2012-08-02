<?php
/**
 *	Architect Framework
 *
 *	Architect Framework is a light-weight and scalable object oriented web applications framework built for PHP 5.3 and later.
 *	Architect focuses on handling common tasks and processes used to quickly develop small, medium and large scale applications.
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
	 *	load
	 *
	 *	Asserts a file to file store.
	 *
	 *	@param string $file_path File to import.
	 *
	 *	@return void
	 */
	public static function load($file_path) {

		if(array_key_exists($file_path, self::$store) !== true) {

			self::$store[] = $file_path;

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

			// Get file path
			$file_path = $obj->getPathname() . DIRECTORY_SEPARATOR . 'Bootstrap.php';

			// Only require file if it exists and is readable
			if(file_exists($file_path) === true && is_readable($file_path) === true) {

				self::load($file_path);

			}

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

		// Autoload bootstrap files
		self::autoload();

		foreach(self::$store as $file_path) {

			if(file_exists($file_path) === true) {

				require_once $file_path;

				\Rae\Console::log("Imported bootstrap file \"{$file_path}\".", __METHOD__, __FILE__, __LINE__);

			}

		}

	}

}
?>