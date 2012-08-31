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
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Request {

	/**
	 *	@var string $default_resource Default resource.
	 */
	public $default_resource;

	/**
	 *	@var string $default_resource_callback Default resource callback.
	 */
	public $default_resource_callback;

	/**
	 *	@var string $component Request component.
	 */
	public $component = null;

	/**
	 *	@var string $triger Request trigger callback.
	 */
	public $trigger = null;

	/**
	 *	@var string $trigger_callback Request trigger callback name.
	 */
	public $trigger_callback = null;

	/**
	 *	@var array $trigger_callback_parameters Array containing parameters to pass through to the Controller and Action resources.
	 */
	public $trigger_callback_parameters = array();

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
	 *	setProperty
	 *
	 *	Validates request property and stores it.
	 *
	 *	@param string $property Property name.
	 *	@param bool|int|string $value Property value.
	 *
	 *	@return void
	 */
	public function setProperty($property, $value) {

		// Set property if value
		$this->$property = $value;

	}

	/**
	 *	getProperty
	 *
	 *	Returns request property.
	 *
	 *	@param string $property Property name.
	 *
	 *	@return mixed
	 */
	public function getProperty($property) {

		if(isset($this->$property) === true) {

			return $this->$property;

		}

		return null;

	}

	/**
	 *	setTriggers
	 *
	 *	Sets request triggers.
	 *
	 *	@param string $trigger Trigger name.
	 *	@param string $callback Trigger callback name.
	 *	@param array $parameters Trigger callback parameters.
	 *
	 *	@return void
	 */
	protected function setTriggers($trigger, $callback = null, $parameters = array()) {

		// Set trigger
		$this->setProperty('trigger', $trigger);

		// Set trigger callback
		$this->setProperty('trigger_callback', $callback);

		// Set trigger callback parameter
		$this->setProperty('trigger_callback_parameters', $parameters);

	}

	/**
	 *	resolveResource
	 *
	 *	Resolves resource name falls back to default trigger.
	 *
	 *	@return string
	 */
	protected function resolveResource() {

		// Store default resource
		$resource = $this->default_resource;

		// Set resource to trigger
		if(empty($this->trigger) === false) {

			$resource = $this->trigger;

		}

		// Return resource
		return $resource;

	}

	/**
	 *	resolveCallback
	 *
	 *	Normalizes and returns resource callback.
	 *
	 *	@return string
	 */
	protected function resolveCallback() {

		// Store default resource callback
		$callback = $this->default_resource_callback;

		// Set callback to trigger callback
		if(empty($this->trigger_callback) === false) {

			$callback = $this->trigger_callback;

		}

		// Return normalized callback
		return $callback;

	}

	/**
	 *	resolveCallbackParameters
	 *
	 *	Returns an array of callback parameters from trigger properties.
	 *
	 *	@return array
	 */
	protected function resolveCallbackParameters() {

		return $this->trigger_callback_parameters;

	}

	/**
	 *	resolveRequest
	 *
	 *	Sets default request parameters.
	 *
	 *	@return void
	 */
	public function resolveRequest() {

		// Set request action
		$this->setProperty('action', $this->resolveResource());
		$this->setProperty('action_callback', $this->resolveCallback());
		$this->setProperty('action_callback_parameters', $this->resolveCallbackParameters());

		// Set request controller
		$this->setProperty('controller', $this->resolveResource());
		$this->setProperty('controller_callback', $this->resolveCallback());
		$this->setProperty('controller_callback_parameters', $this->resolveCallbackParameters());

	}

}
?>