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

/* @namespace Routers */
namespace Architect\Delegation\Routers;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	HTTP
 *
 *	Router class for HTTP routing.
 *
 *	@package Delegation
 *	@subpackage Routers
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class HTTP extends \Architect\Delegation\Router {

	/**
	 *	resolveCurrentRequestRoute
	 *
	 *	Resolves extra parameters used only for HTTP requests.
	 *
	 *	@param string $route_map_pattern Current route map pattern.
	 *	@param array $route_map Current route map object.
	 *
	 *	@return void
	 */
	public function resolveCurrentRequestRoute($route_map_pattern, $route_map) {

		// Get controller name
		$_controller = $route_map['controller']['name'];

		// Get controller name
		$_controller_callback = $route_map['controller']['callback'];

		// Get request type
		$request_type = ($this->request->xml_http_request === true) ? 'ajax' : 'http';
		
		// Get request method
		$request_method = $this->request->method;
		
		// Get request rules
		$request_rules = $route_map['request_rules'][$request_type];
		
		// Check if request rule method exists
		if(array_key_exists($request_method, $request_rules)) {
		
			// Set controller callback to request rule
			$_controller_callback = $request_rules[$request_method];
		
		}

		// Set request action
		$this->request->setProperty('action', $_controller);

		// Set request action callback
		$this->request->setProperty('action_callback', $_controller_callback);

		// Set request controller
		$this->request->setProperty('controller', $_controller);

		// Set request controller callback
		$this->request->setProperty('controller_callback', $_controller_callback);

	}

	/**
	 *	resolveResources
	 *
	 *	Resolves router resources.
	 *
	 *	@return void
	 */
	protected function resolveResources() {

		// Action resource
		$action = new \Architect\Delegation\Resource('Action', $this->request->action, ARCH_APPLICATION_PATH_NAME, ARCH_APPLICATION_NAMESPACE);
		$action->setComponentName($this->request->component);

		// Register action resource
		$this->setResource('action', $action);

		// Controller resource
		$controller = new \Architect\Delegation\Resource('Controller', $this->request->controller, ARCH_APPLICATION_PATH_NAME, ARCH_APPLICATION_NAMESPACE);
		$controller->setComponentName($this->request->component);

		// Register controller resource
		$this->setResource('controller', $controller);

		// Set actions to optional
		$this->setOptionalResource('action');

	}

}
?>