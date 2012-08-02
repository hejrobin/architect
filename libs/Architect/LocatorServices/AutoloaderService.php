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
 *	AutoloaderService
 *
 *	Autoloader service used to autoload classes based on namespace and class names.
 *
 *	@package LocatorServices
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class AutoloaderService {

	/**
	 *	@var array $namespace_locators Array containing namespace based locators.
	 */
	protected $namespace_locators = array();

	/**
	 *	registerNamespaceLocator
	 *
	 *	Registeres a new namespace locator class to a defined namespace.
	 *
	 *	@param string $namespace Namespace, full or in part, to associate to a locator class.
	 *	@param LocatorService $locator Instance of a custom locator service class.
	 *
	 *	@throws Exceptions\AutoloaderServiceException
	 *
	 *	@return void
	 */
	public function registerNamespaceLocator($namespace, LocatorService $locator) {

		// Throw exception if namespace is malformed
		if(preg_match(NAMESPACE_REGEX, $namespace) === 0) {

			throw new Exceptions\AutoloaderServiceException(
				sprintf("Could not register namespace locator \"%s\" to namespace \"%s\".", get_class($locator), $namespace),
				'Namespace contains illegal characters, must be alphanumeric and may contain underscores and namespace separator.',
				__METHOD__, Exceptions\AutoloaderServiceException::INVALID_ARGUMENT_EXCEPTION
			);

		}
		
		// Throw exception if namespace locator is already registered
		if(array_key_exists($namespace, $this->namespace_locators) === true) {

			throw new Exceptions\AutoloaderServiceException(
				sprintf("Could not register namespace locator \"%s\" to namespace \"%s\".", get_class($locator), $namespace),
				'Namespace already has a locator registered to it.',
				__METHOD__, Exceptions\AutoloaderServiceException::BOUNDS_EXCEPTION
			);

		}
		
		// Register namespace locator
		$this->namespace_locators[$namespace] = $locator;

	}

	/**
	 *	unregisterNamespaceLocator
	 *
	 *	Unregisters an existing namespace locator from class member store.
	 *
	 *	@param string $namespace Namespace, full or in part, to associate to a locator class.
	 *
	 *	@return void
	 */
	public function unregisterNamespaceLocator($namespace) {

		// Unregister only if namespace exists
		if(array_key_exists($namespace, $this->namespace_locators) === true) {

			// Remove namespace locator
			unset($this->namespace_locators[$namespace]);

		}

	}

	/**
	 *	getNamespaceLocator
	 *
	 *	Attempts to get a registered namespace locator.
	 *
	 *	@param string $class_name Class name including namespace.
	 *
	 *	@return bool
	 */
	protected function getNamespaceLocator($class_name) {

		// Namespace locator
		$namespace_locator = null;
		
		// Iterate through registered locators to find namespace
		foreach($this->namespace_locators as $namespace => $locator) {
			
			// Check if a namespace locator exists
			if(strlen(stristr($class_name, $namespace)) > 0) {

				$namespace_locator = $locator;

				break;

			}

		}
		
		// Return locator
		return $namespace_locator;

	}

	/**
	 *	register
	 *
	 *	Registerers {@see load} as an autoloader callback.
	 *
	 *	@return bool
	 */
	public function register() {

		return spl_autoload_register(array($this, 'load'));

	}

	/**
	 *	unregister
	 *
	 *	Unregisterers {@see load} as an autoloader callback.
	 *
	 *	@return bool
	 */
	public function unregister() {

		return spl_autoload_unregister(array($this, 'load'));

	}

	/**
	 *	load
	 *
	 *	Fetches namespace locator and invokes it's "import" method.
	 *
	 *	@param string $class_name Class name including namespace.
	 *
	 *	@return bool
	 */
	public function load($class_name) {
		
		// Get registered namespace locator	
		$locator = $this->getNamespaceLocator($class_name);
		
		// Ignore loading if locator does not exist
		if($locator === null) {

			return false;

		}
		
		// Use namespace locator to import class
		$locator->import($class_name);

		// Return boolean if class exists
		return class_exists($class_name);

	}

}
?>