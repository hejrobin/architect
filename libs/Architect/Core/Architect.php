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
 *	Architect
 *
 *	Base class of the framework, utilizes {@see InternalFactory\InternalFactory}.
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
	 *	@var InternalFactory\InternalFactor $factory Instance of InternalFactory\InternalFactor
	 */
	protected $factory;
	
	/**
	 *	Constructor
	 *
	 *	Visibility set to private to deny class initialization, see {@see getInstance} instead.
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
	 *	Visibility set to private and final to disable object cloning.
	 *
	 *	@return void
	 */
	final private function __clone() {}
	
	/**
	 *	getInstance
	 *
	 *	Returns instance reference to itself.
	 *
	 *	@return object
	 */
	public static function getInstance() {

		if(!is_object(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;

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