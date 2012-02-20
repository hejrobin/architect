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

/* @namespace InternalFactory */
namespace Architect\Core\InternalFactory;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	InternalFactoryAbstract
 *
 *	Handles class initialization and enables chaining functionality to classes initialized {@see InternalFactoryAbstract::initialize}.
 *
 *	@package Core
 *	@subpackage InternalFactory
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class InternalFactoryAbstract {

	/**
	 *	@var array $store Factory store array, contains class instance references.
	 */
	protected $store = array();

	/**
	 *	@var string $identifier Instance identifier to an {@see InternalFactoryAbstract} object.
	 */
	protected $identifier;

	/**
	 *	@var null|InternalFactoryAbstract $parent Reference to parent class, defaults to null.
	 */
	protected $parent = null;

	/**
	 *	Constructor
	 *
	 *	Validates input parameters, throws exception if any of them are invalid and sets class members.
	 *
	 *	@param array $store Factory store array, contains class instance references.
	 *	@param string $identifier Instance identifier.
	 *	@param InternalFactoryAbstract $parent Reference to parent class, an instance of InternalFactoryAbstract.
	 *
	 *	@throws Exceptions\InternalFactoryException
	 *
	 *	@return void
	 */
	public function __construct($store, $identifier = null, $parent = null) {

		// Throws exception if input parameter $store is not valid
		if(is_array($store) === false) {

			throw new Exceptions\InternalFactoryException(
				'Could not set class member InternalFactoryAbstract::$store.',
				"Input parameter is not valid, expected 'array', '" . gettype($store) . "' given.",
				__METHOD__, Exceptions\InternalFactoryException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set InternalFactoryAbstract::$store
		$this->store = $store;

		// Throws exception if input parameter $identifier is not valid
		if(!is_null($identifier) && is_string($identifier) === true) {

			throw new Exceptions\InternalFactoryException(
				'Could not set class member InternalFactoryAbstract::identifier.',
				"Input parameter is not valid, expected 'string', '" . gettype($store) . "' given.",
				__METHOD__, Exceptions\InternalFactoryException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set InternalFactoryAbstract::$identifier
		$this->identifier = $identifier;

		// Throws exception if input parameter $parent is not valid
		if(is_object($parent) && ($parent instanceof InternalFactoryAbstract) !== false) {

			throw new Exceptions\InternalFactoryException(
				'Could not set class member InternalFactoryAbstract::$parent.',
				"Input parameter is not valid, expected instance of InternalFactoryAbstract.",
				__METHOD__, Exceptions\InternalFactoryException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set InternalFactoryAbstract::$parent
		$this->parent = $parent;

	}

	/**
	 *	set
	 *
	 *	Sets item to factory store.
	 *
	 *	@param string $key Identifier to factory store item.
	 *	@param mixed $object Mixed object to store in factory store.
	 *
	 *	@return void
	 */
	public function set($key, $object) {

		// Register item to factory store
		$this->store[$key] = $object;

		// Register this store to parent store
		if($this->parent === true && is_string($this->identifier)) {

			$_key = $this->identifier;

			$this->parent->$_key = $this->store;

		}

	}

	/**
	 *	Setter
	 *
	 *	Calls method {@see set}.
	 *
	 *	@param string $key Identifier to factory store item.
	 *	@param mixed $object Mixed object to store in factory store.
	 *
	 *	@return void
	 */
	public function __set($key, $object) {

		$this->set($key, $object);

	}

	/**
	 *	get
	 *
	 *	Returns an item from factory store, or an empty stdClass.
	 *
	 *	@param string $key Identifier to factory store item.
	 *
	 *	@return InternalFactoryObject|stdClass
	 */
	public function get($key) {

		// Register an empty array to factory store, if store does not exist
		if(array_key_exists($key, $this->store) === false)
			$this->store[$key] = array();

		// Fetch factory store, return instance of InternalFactoryObject
		if(array_key_exists($key, $this->store)) {

			// Fetch store
			$store = $this->store[$key];

			// Return new instance of InternalFactoryObject
			if(is_array($store) === true)
				return new InternalFactoryObject($store, $key, $this);

			// Store is not an array, most likely already an instance of InternalFactoryObject
			return $store;

		}

		// Return stdClass
		return new \stdClass();

	}

	/**
	 *	Getter
	 *
	 *	Calls method {@see get}.
	 *
	 *	@param string $key Identifier to factory store item.
	 *
	 *	@return void
	 */
	public function __get($key) {

		$this->get($key);

	}

	/**
	 *	getFactoryStore
	 *
	 *	Returns {@see InternalFactoryAbstract::$store}.
	 *
	 *	@return array
	 */
	public function getFactoryStore() {

		return $this->store;

	}

	/**
	 *	initialize
	 *
	 *	Creates a new instance of input class via a ReflectionClass, throw exception if initialization failed.
	 *
	 *	@param string $class_name Class name, including namespace.
	 *	@param string $instance Name of class instance name.
	 *	@param array $parameters Optional parameter, should be an array of parameters for {@see $class_method}.
	 *	@param string $class_method Optional parameter, name of class method. Defaults to 'newInstance'.
	 *
	 *	@throws Exceptions\InternalFactoryException
	 *
	 *	@return void
	 */
	public function initialize($class_name, $instance, $parameters = array(), $class_method = 'newInstance') {

		// Attempt to create a new instance of input class, via a ReflectionClass
		$this->set($instance, call_user_func_array(array(new \ReflectionClass($class_name), $class_method), $parameters));
		
		// Log memory usage of initialized class (except PDO)
		if(is_a($this->get($instance), 'PDO') === false) {
		
			\Jarvis\Memory::log($this->get($instance), stripslashes(str_ireplace(__NAMESPACE__, '', __METHOD__)), get_class($this->get($instance)), __FILE__, __LINE__);
		
		}
		
		// Throw exception if function call_user_func_array failed
		if($this->get($instance) === false) {

			unset($this->store[$instance]);

			throw new Exceptions\InternalFactoryException(
				'Could not register class to factory store.',
				"Could not create a new instance of '{$class}'.",
				__METHOD__, Exceptions\InternalFactoryException::UNEXPECTED_RESULT_EXCEPTION
			);

		}

	}

}
?>