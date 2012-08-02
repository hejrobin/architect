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

/* @namespace LocatorServices */
namespace Architect\LocatorServices;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	LocatorService
 *
 *	Skeleton class used to create custom locator service classes.
 *
 *	@package LocatorServices
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class LocatorService {

	/**
	 *	@var string $file_extension File extension.
	 */
	protected $file_extension;

	/**
	 *	@var string $include_path Resolved include path.
	 */
	protected $include_path;

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

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Set include path
		$this->resolveIncludePath($include_path);

		// Set include file extension
		$this->setFileExtension($file_extension);

	}

	/**
	 *	setFileExtension
	 *
	 *	Sets file extension.
	 *
	 *	@param string $file_extension File extension, with dot prepended.
	 *
	 *	@throws Exceptions\LocatorServiceException
	 *
	 *	@return void
	 */
	protected function setFileExtension($file_extension) {

		// Throw exception if file extension is malformed
		if(preg_match('/^\.[a-z0-9\.]+$/', $file_extension) === 0) {

			throw new Exceptions\LocatorServiceException(
				'Could not set file extension.',
				'File extension is malformed.',
				__METHOD__, Exceptions\LocatorServiceException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Set file extension
		$this->file_extension = $file_extension;

	}

	/**
	 *	resolveIncludePath
	 *
	 *	Converts forward slashes into valid directory separators.
	 *
	 *	@param string $include_path Unresolved path.
	 *
	 *	@throws Exceptions\LocatorServiceException
	 *
	 *	@return void
	 */
	protected function resolveIncludePath($include_path) {

		// Resolve include path
		$resolved_include_path = str_ireplace('/', DIRECTORY_SEPARATOR, $include_path);

		// Append trailing directory separator if not already present
		if(substr($resolved_include_path, -1) !== DIRECTORY_SEPARATOR) {

			$resolved_include_path = $resolved_include_path . DIRECTORY_SEPARATOR;

		}

		// Throw exception if include path is invalid
		if(is_dir($resolved_include_path) === false) {

			throw new Exceptions\LocatorServiceException(
				'Could not resolve include path for current locator.',
				'Include path is not a valid directory path.',
				__METHOD__, Exceptions\LocatorServiceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set include to resolved include path
		$this->include_path = $resolved_include_path;

	}

	/**
	 *	resolveFilePath
	 *
	 *	Should resolve file class path and return the resolved path.
	 *
	 *	@param string $file_name File name to resolve path for.
	 *
	 *	@return string
	 */
	protected abstract function resolveFilePath($file_name);

	/**
	 *	import
	 *
	 *	Should call {@see resolveFilePath} and verify file path integrity.
	 *
	 *	@param string $file_name File name to import.
	 *
	 *	@return void
	 */
	public abstract function import($file_name);

}
?>