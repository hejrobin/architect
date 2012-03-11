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
 *	Request
 *
 *	Request object to pass to {@see \Architect\Delegation\Router}.
 *
 *	@package Delegation
 *	@subpackage Request
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class Request {

	/**
	 *	@var string $controller Default request controller.
	 */
	public $default_controller = null;

	/**
	 *	@var string $component Request component.
	 */
	public $component = null;

	/**
	 *	@var string $controller Request controller.
	 */
	public $controller = null;
	
	/**
	 *	@var string $controller_callback Request controller callback.
	 */
	public $controller_callback = null;
	
	/**
	 *	@var array $controller_callback_parameters Request controller callback parameters.
	 */
	public $controller_callback_parameters = array();
	
	/**
	 *	@var string $action Request action.
	 */
	public $action = null;

	/**
	 *	@var string $action_callback Request action callback.
	 */
	public $action_callback = null;
	
	/**
	 *	@var array $action_callback_parameters Request action callback parameters.
	 */
	public $action_callback_parameters = array();

	/**
	 *	@var \Architect\URI\URI $protocol URI protocol.
	 */
	protected $protocol;

	/**
	 *	setProtocol
	 *
	 *	Sets URI protocol.
	 *
	 *	@param \Architect\URI\URI $protocol Instance of \Architect\URI\URI.
	 *
	 *	@return void
	 */
	public function setProtocol(\Architect\URI\URI $protocol) {
	
		$this->protocol = $protocol;
	
	}

	/**
	 *	setProperty
	 *
	 *	Sets request property.
	 *
	 *	@param string $property Property name.
	 *	@param bool|int|string $value Property value.
	 *	@param string $type Optional parameter, value type.
	 *
	 *	@return void
	 */
	public function setProperty($property, $value, $type = 'string') {
	
		// Set property if value is of correct type
		if(call_user_func("is_{$type}", $value) === true) {
		
			$this->$property = $value;
		
		}
	
	}
	
	/**
	 *	setRequest
	 *
	 *	Should set request properties, should use {@see setProperty}.
	 *
	 *	@param array $properties Array containging property value pairs.
	 *
	 *	@return void
	 */
	abstract public function setRequest(array $properties);

	/**
	 *	resolveResource
	 *
	 *	Returns resource (controller and action) based on first URI segment.
	 *
	 *	@param string $default_resource Default resource name.
	 *
	 *	@return null|string
	 */
	protected function resolveResource($default_resource = null) {
	
		// Get resource based on segment
		$resource = ($this->protocol->getSegment(1) !== '') ? ucfirst($this->protocol->getSegment(1)) : $default_resource;
		
		// Return resource
		return $resource;
	
	}

	/**
	 *	resolveCallback
	 *
	 *	Normalizes and returns callback based on URI segment, returns null if second URI segment does not exist.
	 *
	 *	@param string $default_callback Default callback name.
	 *
	 *	@return null|string
	 */
	protected function resolveCallback($default_callback = null) {
	
		// Remove multiple occurances of dash sign
		$callback = preg_replace('/(\-+)/', '-', trim($this->protocol->getSegment(2), '-'));
		
		// Remove multiple occurances of underscore
		$callback = preg_replace('/(\_+)/', '_', trim($callback, '_'));
	
		// Replace dash sign with underscore, and dot with "_dot_"
		$callback = str_ireplace(array('-', '.'), array('_', '_dot_'), $callback);
		
		// Remove multiple occurances of underscore (again, just to be sure)
		$callback = preg_replace('/(\_+)/', '_', trim($callback, '_'));
		
		// Get normalized callback if second URI segment exist
		$callback = (is_string($this->protocol->getSegment(2)) === true) ? $callback : $default_callback;
	
		// Return normalized callback
		return $callback;
		
	}

	/**
	 *	resolveCallbackParameters
	 *
	 *	Returns an array of additional URI segments if exists.
	 *
	 *	@return array
	 */
	protected function resolveCallbackParameters() {
	
		return array_slice(explode('/', $this->protocol->getRequestPath()), 2);
	
	}

	/**
	 *	resolveRequest
	 *
	 *	Resolves controller and action name, callback and callback parameters.
	 *
	 *	@return void
	 */
	public function resolveRequest() {
	
		// Set request properties
		$this->setRequest(array(
		
			'controller' => $this->resolveResource($this->default_controller),
			
			'controller_callback' => $this->resolveCallback('index'),
			
			'controller_callback_parameters' => $this->resolveCallbackParameters(),
			
			'action' => $this->resolveResource($this->default_controller),
			
			'action_callback' => $this->resolveCallback('index'),
			
			'action_callback_parameters' => $this->resolveCallbackParameters(),
		
		));
	
	}

}
?>