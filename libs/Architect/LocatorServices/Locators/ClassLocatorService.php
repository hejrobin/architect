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

/* @namespace Locators */
namespace Architect\LocatorServices\Locators;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	ClassLocatorService
 *
 *	Resolves class namespace into a directory path, validates class file name integrity and imports class file.
 *
 *	@package LocatorServices
 *	@subpackage Locators
 *
 *	@dependencies \Architect\LocatorServices\LocatorService
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class ClassLocatorService extends \Architect\LocatorServices\LocatorService {

	/**
	 *	Constructor
	 *
	 *	Sets include path and file extension.
	 *
	 *	@param string $include_path Unresolved path.
	 *	@param string $file_extension File extension, with dot prepended.
	 *
	 *	@return void
	 */
	public function __construct($include_path, $file_extension) {

		// Invoke parent constructor
		parent::__construct($include_path, $file_extension);

	}

	/**
	 *	resolveFilePath
	 *
	 *	Resolves class path by converting namespace separators into directory separators.
	 *
	 *	@param string $file_name Class file name including namespace, without file extension.
	 *
	 *	@throws Exceptions\ClassLocatorServiceException
	 *
	 *	@return string
	 */
	protected function resolveFilePath($class_name) {

		// Resolve class directory path
		$class_directory_path = str_ireplace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name);

		// Set class file include path
		$include_path = $this->include_path . $class_directory_path . $this->file_extension;

		// Verify include path integrity
		if(!$handle = @fopen($include_path, 'r', true)) {

			// Path integrity seem corrupted, throw exception
			throw new Exceptions\ClassLocatorServiceException(
				"Could not resolve class file path for \"{$class_directory_path}\".",
				"Class file path may be invalid or corrupt.",
				__METHOD__, Exceptions\ClassLocatorServiceException::UNEXPECTED_RESULT_EXCEPTION
			);

		}

		// Close file handle
		@fclose($handle);

		// Unset handle variable
		unset($handle);

		// Return resolved path
		return $include_path;

	}

	/**
	 *	import
	 *
	 *	Imports class file path.
	 *
	 *	@param string $file_name File name to import.
	 *
	 *	@return void
	 */
	public function import($file_name) {

		// Resolve file path
		$include_path = $this->resolveFilePath($file_name);

		// Import file
		require_once $include_path;

	}

}
?>