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
 *	Architect
 *
 *	Singleton factory class used throughout the framework which allows invocation of classes and registers them onto itself.
 *
 *	@package Core
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Architect {

	/**
	 *	@staticvar object $_instance Instance variable to itself.
	 */
	protected static $_instance;

	/**
	 *	@var InternalFactory\InternalFactory $factory Instance of {@see InternalFactory\InternalFactory}.
	 */
	protected $factory;

	/**
	 *	Constructor
	 *
	 *	Creates a new instance of InternalFactory\InternalFactory, visibility is set to "private" since this is a singleton class. Utilizes {@see InternalFactory\InternalFactory}.
	 *
	 *	@return void
	 */
	private function __construct() {

		// Create a new instance of InternalFactory
		$this->factory = new InternalFactory\InternalFactory(array());

	}

	/**
	 *	Clone mutator
	 *
	 *	State is set to "final" and visibility is set to "private" to disable object cloning.
	 *
	 *	@return void
	 */
	final private function __clone() {}

	/**
	 *	getInstance
	 *
	 *	Returns instance of self, if no instance exists, one is created.
	 *
	 *	@return self
	 */
	public static function getInstance() {

		// No instance exists, create one
		if(is_object(self::$_instance) === false) {

			self::$_instance = new self();

		}

		return self::$_instance;

	}

	/**
	 *	getFactoryStore
	 *
	 *	Calls {@see InternalFactory\InternalFactory::getFactoryStore}.
	 *
	 *	@return array
	 */
	public function getFactoryStore() {

		return $this->factory->getFactoryStore();

	}

	/**
	 *	Setter
	 *
	 *	Calls {@see InternalFactory\InternalFactory::set}.
	 *
	 *	@param string $name
	 *	@param object $instance
	 *
	 *	@return void
	 */
	public function __set($name, $instance) {

		$this->factory->set($name, $instance);

	}

	/**
	 *	Getter
	 *
	 *	Calls {@see InternalFactory\InternalFactory::get}.
	 *
	 *	@param string $name
	 *
	 *	@return object|InternalFactoryObject
	 */
	public function __get($name) {

		return $this->factory->get($name);

	}

	/**
	 *	hasInstance
	 *
	 *	Calls {@see InternalFactory\InternalFactory::hasInstance}.
	 *
	 *	@param string $identifier
	 *
	 *	@return bool
	 */
	public function hasInstance($name) {

		return $this->factory->hasInstance($name);

	}

	/**
	 *	initialize
	 *
	 *	Calls {@see InternalFactory\InternalFactory::initialize}.
	 *
	 *	@return void
	 */
	public function initialize($class_name, $instance, $parameters = array(), $class_method = 'newInstance') {

		$this->factory->initialize($class_name, $instance, $parameters, $class_method);

	}

}
?>