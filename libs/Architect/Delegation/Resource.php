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

/* @namespace Delegation */
namespace Architect\Delegation;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Resource
 *
 *	Request resource object, containging logic for loading and invocation of resources, such as a Controller or Action.
 *
 *	@package Delegation
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Resource {

	/**
	 *	@var string $type Resource type.
	 */
	protected $type;

	/**
	 *	@var string $name Resource name.
	 */
	protected $name;

	/**
	 *	@var string $include_path Resource include path.
	 */
	protected $include_path;

	/**
	 *	@var string $namespace Resource namespace.
	 */
	protected $namespace;

	/**
	 *	@var string $component Resource component name.
	 */
	protected $component;

	/**
	 *	@var bool $pluralize_resource_type Pluralize resource type name.
	 */
	public $pluralize_resource_type = true;

	/**
	 *	Constructor
	 *
	 *	Sets resource parameters.
	 *
	 *	@param string $type Resource type name.
	 *	@param string $name Resource name.
	 *	@param string $include_path Resource include path.
	 *	@param string $namespace Resource namespace.
	 *
	 *	@return void
	 */
	public function __construct($type, $name, $include_path = null, $namespace = null) {

		$this->setType($type);

		$this->setName($name);

		if($include_path !== null) {

			$this->setIncludePath($include_path);

		}

		if($namespace !== null) {

			$this->setNamespace($namespace);

		}

	}

	/**
	 *	setType
	 *
	 *	Sets current recoure type name.
	 *
	 *	@param string $type Resource type name.
	 *
	 *	@throws Exception\ResourceException
	 *
	 *	@return void
	 */
	public function setType($type) {

		// Throw exception if argument is malformed
		if(is_string($type) === false) {

			throw new Exceptions\ResourceException(
				'Could not set resource type name, expects type of "string".',
				'Resource type must be a "string", "' . gettype($type) . '" given.',
				__METHOD__, Exceptions\ResourceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Capitalize input string
		$this->type = ucfirst(strtolower($type));

	}

	/**
	 *	getType
	 *
	 *	Returns current resource type.
	 *
	 *	@return string
	 */
	public function getType() {

		return $this->type;

	}

	/**
	 *	setName
	 *
	 *	Sets current recoure name.
	 *
	 *	@param string $name Resource name.
	 *
	 *	@throws Exception\ResourceException
	 *
	 *	@return void
	 */
	public function setName($name) {

		// Throw exception if argument is malformed
		if(is_string($name) === false) {

			throw new Exceptions\ResourceException(
				'Could not set resource name, expects type of "string".',
				'Resource name must be a "string", "' . gettype($name) . '" given.',
				__METHOD__, Exceptions\ResourceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Capitalize input string
		$this->name = $name;

	}

	/**
	 *	getName
	 *
	 *	Returns current resource name.
	 *
	 *	@return string
	 */
	public function getName() {

		return $this->name;

	}

	/**
	 *	setIncludePath
	 *
	 *	Sets current recoure include path.
	 *
	 *	@param string $include_path Resource include path.
	 *
	 *	@throws Exception\ResourceException
	 *
	 *	@return void
	 */
	public function setIncludePath($include_path) {

		// Throw exception if argument is malformed
		if(is_string($include_path) === false) {

			throw new Exceptions\ResourceException(
				'Could not set resource include path, expects type of "string".',
				'Resource include path must be a "string", "' . strtolower(gettype($include_path)) . '" given.',
				__METHOD__, Exceptions\ResourceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Capitalize input string
		$this->include_path = $include_path;

	}

	/**
	 *	getIncludePath
	 *
	 *	Returns current resource include path.
	 *
	 *	@return string
	 */
	public function getIncludePath() {

		return $this->include_path;

	}

	/**
	 *	setNamespace
	 *
	 *	Sets current recoure namespace.
	 *
	 *	@param string $namespace Resource namespace.
	 *
	 *	@throws Exception\ResourceException
	 *
	 *	@return void
	 */
	public function setNamespace($namespace) {

		// Throw exception if argument is malformed
		if(is_string($namespace) === false) {

			throw new Exceptions\ResourceException(
				'Could not set resource namespace, expects type of "string".',
				'Resource name must be a "string", "' . gettype($namespace) . '" given.',
				__METHOD__, Exceptions\ResourceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Capitalize input string
		$this->namespace = $namespace;

	}

	/**
	 *	getNamespace
	 *
	 *	Returns current resource namespace.
	 *
	 *	@return string
	 */
	public function getNamespace() {

		return $this->namespace;

	}

	/**
	 *	setComponentName
	 *
	 *	Sets current recoure component name.
	 *
	 *	@param string $component Resource component name.
	 *
	 *	@throws Exception\ResourceException
	 *
	 *	@return void
	 */
	public function setComponentName($component) {

		// Allow null value for component name
		if(is_null($component) === true) {

			$this->component = null;

			return false;

		}

		// Throw exception if argument is malformed
		if(is_string($component) === false) {

			throw new Exceptions\ResourceException(
				'Could not set resource component name, expects component of "string".',
				'Resource component must be a "string", "' . gettype($component) . '" given.',
				__METHOD__, Exceptions\ResourceException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set component name
		$this->component = "Components" . DIRECTORY_SEPARATOR . $component;

	}

	/**
	 *	getComponentName
	 *
	 *	Returns current resource component.
	 *
	 *	@return string
	 */
	public function getComponentName() {

		return $this->component;

	}

	/**
	 *	resolve
	 *
	 *	Resolves an include path, or namespace and implodes registered namespace, resource type and resource name.
	 *
	 *	@param string $separator Path separator or namespace separator.
	 *	@param string $property Property name, may be 'namespace' or 'include_path'.
	 *
	 *	@return string
	 */
	protected function resolve($separator, $property) {
		
		// Throw exception if input parameter is invalid
		if(in_array($property, array('namespace', 'include_path')) === false) {

			throw new Exceptions\ResourceException(
				'Could not resolve include path or namespace.',
				'Input parameter must be "namespace" or "include_path", "' . $prtoperty . '" given.',
				__METHOD__, Exceptions\ResourceException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Store resource type
		$resource_type = $this->type;

		// Include paths require additional manipulation
		if($property === 'include_path') {

			// Append 's' to resource types, if applicable
			if($this->pluralize_resource_type === true) {

				$resource_type .= 's';

			}

			// Prepend component directory to resource type
			if($this->component !== null && is_string($this->component) === true) {

				$resource_type = $this->component . DIRECTORY_SEPARATOR . $resource_type;

			}

		}

		// Implode resource namespace, name and input object
		$resolved_path = implode($separator, array($this->$property, $resource_type, $this->name));
		
		// Return resolved path
		return $resolved_path;
	
	}

	/**
	 *	resolveNamespace
	 *
	 *	Resolves resource namespace path.
	 *
	 *	@return string
	 */
	protected function resolveNamespace() {
	
		return NAMESPACE_SEPARATOR . trim($this->resolve(NAMESPACE_SEPARATOR, 'namespace'), NAMESPACE_SEPARATOR);
	
	}

	/**
	 *	resolveIncludePath
	 *
	 *	Resolves resource directory path.
	 *
	 *	@return string
	 */
	protected function resolveIncludePath() {
	
		return ARCH_ROOT_PATH . $this->resolve(DIRECTORY_SEPARATOR, 'include_path');
			
	}

	/**
	 *	canImport
	 *
	 *	Returns boolean whether resource file exists or not.
	 *
	 *	@return bool
	 */
	public function canImport() {
	
		// Get resource path
		$resource_path = $this->resolveIncludePath() . ARCH_FILE_EXTENSION;
		
		// Return whether file exists or not
		return file_exists($resource_path);
	
	}

	/**
	 *	import
	 *
	 *	Imports current resource.
	 *
	 *	@throws Exceptions\ResourceException
	 *
	 *	@return void
	 */
	public function import() {

		// Get resource path
		$resource_path = $this->resolveIncludePath() . ARCH_FILE_EXTENSION;

		// Throw exception if resource path does not exist
		if($this->canImport() === false) {

			throw new Exceptions\ResourceException(
				"Could not import resource file for \"{$this->type}\\{$this->name}\".",
				"Input file \"{$resource_path}\" does not exist or is invalid.",
				__METHOD__, Exceptions\ResourceException::UNEXPECTED_RESULT_EXCEPTION
			);

		}

		// Require resource file
		require_once $resource_path;

	}

	/**
	 *	createInstance
	 *
	 *	Creates a new instance of imported resource and returns it.
	 *
	 *	@return object
	 */
	public function createInstance() {
		
		// Create new instance of current resource
		$instance = call_user_func_array(array(new \ReflectionClass($this->resolveNamespace()), 'newInstance'), array());

		// Return instance
		return $instance;

	}

}
?>