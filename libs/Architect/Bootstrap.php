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

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	@const string NAMESPACE_SEPARATOR Native namespace separator constant.
 */
define('NAMESPACE_SEPARATOR', '\\');

/**
 *	@const string NAMESPACE_REGEX Namespace string regex.
 */
define('NAMESPACE_REGEX', '/^[a-z\\\\][a-z0-9\_\\\\]+$/i');

/**
 *	@private $import
 *
 *	Anonymous function used to import required class files.
 *
 *	@param array $namespace_segments Array containing namespace segments.
 *	@param string $class_name Class name to import
 *
 *	@return void
 */
$import = function(array $namespace_segments, $class_name) {

	// Prepend namespace segments and get class include path
	$namespace_segments = array_merge(array('libs', 'Architect'), $namespace_segments);
	$include_path = ARCH_ROOT_PATH . implode(DIRECTORY_SEPARATOR, $namespace_segments) . DIRECTORY_SEPARATOR;

	// Get file include path
	$class_file_path = implode('', array($include_path, "{$class_name}.php"));

	// Require class
	require_once $class_file_path;

};

/**
 *	af_error_handler
 *
 *	This callback function converts PHP errors into ErrorExceptions and then into Architect_ErrorException.
 *
 *	@see Architect_ErrorException
 *
 *	@param int $code
 *	@param string $message
 *	@param string $file
 *	@param int $line
 *
 *	@throws Architect_ErrorException
 */
$error_handler = function($code, $message, $file, $line) {

	// Create ErrorException
	$error = new \ErrorException($message, 0, $code, $file, $line);

	// Throw Architect_ErrorException
	throw new \Architect\Exceptions\ErrorException($message, $message, 'PHP', $code, array(
		'file' => $error->getFile(),
		'line' => $error->getLine(),
		'trace' => $error->getTrace()
	));

};

// Set custom error handler
error_reporting(E_ALL ^ E_NOTICE);
set_error_handler($error_handler, E_ALL ^ E_NOTICE);

// Import core functions
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Functions.php');
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Helpers.php');

// Import required exceptions
$import(array('Exceptions'), 'ExceptionAbstract');
$import(array('Exceptions'), 'ErrorException');
$import(array('Exceptions'), 'Exception');

// Load Architect and InternalFactory classes
$import(array('Core', 'InternalFactory', 'Exceptions'), 'InternalFactoryException');
$import(array('Core', 'InternalFactory'), 'InternalFactoryAbstract');
$import(array('Core', 'InternalFactory'), 'InternalFactoryObject');
$import(array('Core', 'InternalFactory'), 'InternalFactory');
$import(array('Core'), 'Architect');

// Load locator services
$import(array('LocatorServices'), 'LocatorService');
$import(array('LocatorServices'), 'AutoloaderService');
$import(array('LocatorServices', 'Exceptions'), 'AutoloaderServiceException');
$import(array('LocatorServices', 'Locators'), 'ClassLocatorService');

/**
 *	Architect
 *
 *	Global static class used for easy access of {@see Architect\Core\Architect::getInstance()}.
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Architect {

	/**
	 *	getInstance
	 *
	 *	Returns singleton instance of \Architect\Core\Architect.
	 *	Wrapped in a static class for easy access.
	 *
	 *	@return \Architect\Core\Architect
	 */
	public static function getInstance() {

		return Architect\Core\Architect::getInstance();

	}

}

// Get instance of Architect
$arch = Architect::getInstance();

// Set up autoloader services
$arch->initialize('Architect\LocatorServices\AutoloaderService', 'autoloader');
$arch->autoloader->register();

// Register namespace locator service for namespace 'Architect'
$arch->autoloader->registerNamespaceLocator('Architect', new Architect\LocatorServices\Locators\ClassLocatorService(ARCH_ROOT_PATH . 'libs' . DIRECTORY_SEPARATOR, '.php'));

// Define constants from config.xml
new \Architect\Core\Config\ConstantParser();

// Define constants from package.xml
new \Architect\Core\Config\PackageConstantParser();

// Initialize URI object for HTTP
$arch->initialize('Architect\URI\Schemes\HTTP', 'uri');
$arch->uri->autodiscover();

// Intitalize HTTP Client
$arch->initialize('Architect\HTTP\Client', 'http');

// Initialize FilterFactory
$arch->initialize('Architect\IO\FilterFactory', 'filter');

// Set timezone
date_default_timezone_set(ARCH_DATE_TIMEZONE);
?>