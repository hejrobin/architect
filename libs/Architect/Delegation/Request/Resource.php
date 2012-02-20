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

/* @namespace Request */
namespace Architect\Delegation\Request;

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
	 *	@var string $path Resource path.
	 */
	protected $path;

	/**
	 *	@var string $namespace Resource namespace.
	 */
	protected $namespace;

	/**
	 *	Constructor
	 *
	 *	Sets resource parameters.
	 *
	 *	@param string $type Resource type.
	 *	@param string $name Resource name.
	 *	@param string $path Resource path.
	 *	@param string $namespace Resource namespace.
	 *
	 *	@return void
	 */
	public function __construct($type, $name, $path = null, $namespace = null) {
	
		// Set resource type
		$this->type = ucfirst(strtolower($type));
		
		// Set resource name
		$this->name = $name;
		
		// Set resource path
		$this->path = $path;
		
		// Set resource namespace
		$this->namespace = $namespace;
	
	}

	/**
	 *	resolve
	 *
	 *	Resolves a path, or namespace and implodes registered namespace, resource type and resource name.
	 *
	 *	@param string $separator Path, or namespace separator.
	 *	@param string $object Input object name.
	 *	@param string $property Property name, may be 'namespace' or 'path'.
	 *
	 *	@return string
	 */
	protected function resolve($separator, $property) {
	
		// Implode resource namespace, name and input object
		$resolved_path = implode($separator, array($this->$property, $this->type, $this->name));
		
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
	 *	resolvePath
	 *
	 *	Resolves resource directory path.
	 *
	 *	@return string
	 */
	protected function resolvePath() {
	
		return $this->resolve(DIRECTORY_SEPARATOR, 'path');
			
	}

	/**
	 *	canImport
	 *
	 *	Returns boolean whether file exists or not.
	 *
	 *	@return bool
	 */
	public function canImport() {
	
		// Get resource path
		$resource_path = $this->resolvePath() . ARCH_FILE_EXTENSION;
		
		// Return whether file exists or not
		return file_exists($resource_path);
	
	}

	/**
	 *	import
	 *
	 *	Import resource.
	 *
	 *	@throws Exceptions\ResourceException
	 *
	 *	@return void
	 */
	public function import() {
	
		// Get resource path
		$resource_path = $this->resolvePath() . ARCH_FILE_EXTENSION;
		
		// Throw exception if resource path does not exist
		if($this->canImport() === false) {
		
			throw new Exceptions\ResourceException(
				'Could not import resource file.',
				"Input file '{$resource_path}' does not exist.",
				__METHOD__, Exceptions\ResourceException::UNEXPECTED_RESULT_EXCEPTION
			);
		
		}
		
		// Require resource file
		require_once($resource_path);
	
	}

	/**
	 *	createInstance
	 *
	 *	Creates a new instance of resource and returns it.
	 *
	 *	@return object
	 */
	public function createInstance() {
		
		// Create new instance of current resource
		$instance = call_user_func_array(array(new \ReflectionClass($this->resolveNamespace()), 'newInstance'), array());

		// Log memory usage of created class
		\Jarvis\Memory::log($instance, "Created instance of '" . $this->resolveNamespace() . "'.", 'Resource::createInstance', __FILE__, __LINE__);
		
		// Return instance
		return $instance;

	}

}
?>