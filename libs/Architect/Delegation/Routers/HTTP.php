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
 *	HTTP router class, listens to HTTP URI schema and route maps array.
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
	 *	Constructor
	 *
	 *	Sets up router, request and URI schema.
	 *
	 *	@param string $default_controller Default controller for this router.
	 *	@param array $route_maps Array containging custom route maps.
	 *
	 *	@return void
	 */
	public function __construct($default_controller, $route_maps = array()) {
	
		// Create protocol instance
		$protocol = new \Architect\URI\Schemes\HTTP();
	
		// Autodiscover protocol URI
		$protocol->autodiscover();

		// Invoke parent constructor
		parent::__construct($protocol, new \Architect\Delegation\Request\HTTP());
		
		// Set custom route maps
		$this->route_maps = $route_maps;
		
		// Set default controller
		$this->default_controller = $default_controller;
		
		// Set default controller to request
		$this->request->setProperty('default_controller', $this->default_controller);
	
	}

	/**
	 *	resolveRouteMaps
	 *
	 *	Resolves custom request maps and sets request if a match is found.
	 *
	 *	@throws Exceptions\DelegationException
	 *
	 *	@return bool
	 */
	protected function resolveRouteMaps() {
	
		// Get request path
		$request_path = $this->protocol->getRequestPath();
		
		// Do nothing if route maps is empty
		if(count($this->route_maps) === 0) {
		
			return false;
		
		}
		
		\Jarvis\Benchmark::log('ResolveRouteMaps', 'Resolving custom route maps.', __FILE__, __LINE__);
		
		// Iterate through each route map until a match is found
		foreach($this->route_maps as $route_map_pattern => $route_map) {
		
			// Only apply if route map pattern is not default_controller
			if($route_map_pattern !== 'default_controller') {
			
				// Get wildcards
				$wildcards = array_keys($this->wildcards);
				
				// Get wildcard regexes
				$wildcards_regex = array_values($this->wildcards);
	
				// Replace wildcards with wildcard regexes in route map pattern
				$route_map_pattern = str_ireplace($wildcards, $wildcards_regex, $route_map_pattern);
				
				// Get route action as object
				$action = (object) $route_map['action'];
				
				// Get route controller as object
				$controller = (object) $route_map['controller'];
				
				// Set route request string
				$route_request_parameters_string = implode('/', $controller->parameters);
				
				// Check for route map match
				if(preg_match('#^' . $route_map_pattern . '$#', $request_path)) {
					
					\Jarvis\Console::log("Found match for route map '{$route_map_pattern}'.", 'HTTP::resolveRouteMaps', __FILE__, __LINE__);
					
					// Set regex callback match for request parameters 
					if(strpos($route_request_parameters_string, '$') !== false && strpos($route_map_pattern, '(') !== false) {
					
						// Replace regex callbacks
						$route_request_parameters_string = preg_replace('#^' . $route_map_pattern . '$#', $route_request_parameters_string, $request_path);
					
					}
					
					// SSL mode
					$_ssl = $route_map['ssl'];
					
					switch($_ssl) {
						case 'allow' :
							// Do nothing
						break;
						case 'enforce' :
							
							$this->protocol->setScheme('https');
							$this->protocol->parse($this->protocol->getRequestURI(true));
							
							// Send 101 Switching Protocol status
							header('Location: ', $this->protocol->getRequestURI(true), 101);
							
						break;
						case 'restrict' :
						
							// Throw error if SSL is not active
							if($this->request->ssl === false) {
								
								throw new Exceptions\DelegationException(
									"Could not complete delegation.",
									"This route must be over SSL.",
									__METHOD__, Exceptions\DelegationException::RUNTIME_EXCEPTION
								);
								
							}
						
						break;
					}
					
					// Get component
					$_component = $route_map['component'];
					
					// Set action
					$_action = (is_string($action->name) === true) ? $action->name : null;
					
					// Set action callback
					$_action_callback = (is_string($action->callback) === true) ? $action->callback : null;
					
					// Set action callback parameters
					$_action_callback_parameters = explode('/', $route_request_parameters_string);
					
					// Set controller
					$_controller = $controller->name;
					
					// Set controller callback
					$_controller_callback = $controller->callback;
					
					// Set controller callback parameters
					$_controller_callback_parameters = explode('/', $route_request_parameters_string);
					
					// Get request type
					$request_type = ($this->request->xhr === true) ? 'ajax' : 'http';
					
					// Get request method
					$request_method = $this->request->method;
					
					// Get request rules
					$request_rules = $route_map['request_rules'][$request_type];
					
					// Check if request rule method exists
					if(array_key_exists($request_method, $request_rules)) {
					
						// Set controller callback to request rule
						$_controller_callback = $request_rules[$request_method];
					
					}
					
					// Set request
					$this->request->setRequest(array(
					
						'component' => $_component,
						
						'action' => $_action,
						
						'action_callback' => $_action_callback,
						
						'action_callback_parameters' => $_action_callback_parameters,
						
						'controller' => $_controller,
						
						'controller_callback' => $_controller_callback,
						
						'controller_callback_parameters' => $_controller_callback_parameters
											
					));
					
					// Break loop
					break;
				
				}

			}
		
			// Log benchmark entry
			\Jarvis\Benchmark::assert('ResolveRouteMaps');
		
		}
	
	}

	/**
	 *	resolveResources
	 *
	 *	Registers required resources.
	 *
	 *	@return void
	 */
	public function resolveResources() {
		
		// Set namespace
		$namespace = 'app';
		
		// Set include path
		$include_path = 'app';
		
		// Append component to include path if set
		if(is_string($this->request->component) === true) {
		
			$include_path .= '/Components/' . $this->request->component;
		
		}
		
		// Register Actions resource
		$this->setResource('action', new \Architect\Delegation\Request\Resource('Actions', $this->request->action, $include_path, $namespace));
		
		// Set actions to optional
		$this->setOptionalResource('action');
		
		// Register Controllers resource
		$this->setResource('controller', new \Architect\Delegation\Request\Resource('Controllers', $this->request->controller, $include_path, $namespace));
	
	}

}