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
 *	Router
 *
 *	Generic router class.
 *
 *	@package Delegation
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class Router {

	/**
	 *	@var Resource $request Request object.
	 */
	protected $request;

	/**
	 *	@var Resource $request_path Request path.
	 */
	public $request_path;

	/**
	 *	@var array $resources Array containging instances of {@see Resource}.
	 */
	protected $resources;

	/**
	 *	@var array $optional_resources Array containging names of optional resource types.
	 */
	protected $optional_resources = array();

	/**
	 *	@var array $parsed_route_maps Parsed route maps.
	 */
	protected $parsed_route_maps;

	/**
	 *	@var array $route_map_wildcards Route map pattern wildcards.
	 */
	protected $route_map_wildcards = array(

		':number' => '(\d+)',

		':word' => '(\w+)',

		':uri_chars' => '([A-Za-z\_\-\+\#\?\&\.\@]+)'

	);

	/**
	 *	Constructor
	 *
	 *	Sets router request.
	 *
	 *	@param string $request_path Request path.
	 *	@param Request $request Request object.
	 *
	 *	@return void
	 */
	public function __construct($request_path, Request $request) {

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Set request path
		$this->setRequestPath($request_path);

		// Set request object
		$this->request = $request;

	}

	/**
	 *	setRequestPath
	 *
	 *	Sets current request path.
	 *
	 *	@param string $request_path Request path.
	 *
	 *	@return void
	 */
	public function setRequestPath($request_path) {

		$this->request_path = trim($request_path, '/');

	}

	/**
	 *	setParsedRouteMaps
	 *
	 *	Sets parsed route maps to router.
	 *
	 *	@param array $parsed_route_maps Parsed route maps.
	 *
	 *	@return void
	 */
	public function setParsedRouteMaps($parsed_route_maps) {

		$this->parsed_route_maps = $parsed_route_maps;

	}

	/**
	 *	setResource
	 *
	 *	Sets a resource to current class.
	 *
	 *	@param string $key Resource name.
	 *	@param Resource $resource Instance of {@see Resource}.
	 *
	 *	@return void
	 */
	public function setResource($key, Resource $resource) {

		// Set resource
		$this->resources[$key] = $resource;

	}

	/**
	 *	getResource
	 *
	 *	Returns resource object.
	 *
	 *	@param string $key Resource name.
	 *
	 *	@return Resource
	 */
	public function getResource($key) {

		// Get resource
		return $this->resources[$key];

	}

	/**
	 *	setOptionalResource
	 *
	 *	Sets an resource type to optional.
	 *
	 *	@param string $name Resource type name.
	 *
	 *	@return void
	 */
	public function setOptionalResource($name) {

		// Only add optional resource if not already exists as optional
		if(in_array($name, $this->optional_resources) === false) {

			$this->optional_resources[] = $name;

		}

	}

	/**
	 *	getOptionalResource
	 *
	 *	Returns optional resource object.
	 *
	 *	@param string $key Resource name.
	 *
	 *	@return Resource
	 */
	public function getOptionalResource($key) {

		// Get resource
		return $this->optional_resources[$key];

	}

	/**
	 *	resolveCurrentRequestRoute
	 *
	 *	Should resolve extra request route parameters, if applicable.
	 *
	 *	@param string $route_map_pattern Current route map pattern.
	 *	@param array $route_map Current route map object.
	 *
	 *	@return void
	 */
	public abstract function resolveCurrentRequestRoute($route_map_pattern, $route_map);

	/**
	 *	resolveRequestRoute
	 *
	 *	Resolves route request based on parsed route maps and request path.
	 *
	 *	@return void
	 */
	protected function resolveRequestRoute() {

		$log_key = "Router_Request_" . time();

		\Rae\Benchmark::log($log_key, "Request route resolve time.", __METHOD__, __FILE__, __LINE__);

		// Store request path
		$request_path = $this->request_path;

		// Resolve current request
		$this->request->resolveRequest();

		// Store route match
		$route_match = false;

		// Only continue if there are any user-defined route map patterns
		if(count($this->parsed_route_maps) >= 3) {

			foreach($this->parsed_route_maps as $route_map_pattern => $route_map) {

				// Reset route match
				$route_match = false;

				// Ignore default map "patterns"
				if(in_array($route_map_pattern, array('default_component', 'default_controller', 'default_controller_callback')) === false) {

					// Get wildcards
					$wildcards = array_keys($this->route_map_wildcards);

					// Get wildcard regexes
					$wildcards_regex = array_values($this->route_map_wildcards);

					// Replace wildcards with wildcard regexes in route map pattern
					$route_map_pattern = str_ireplace($wildcards, $wildcards_regex, $route_map_pattern);

					// Get route action as object
					$action = (object) $route_map['action'];

					// Get route controller as object
					$controller = (object) $route_map['controller'];

					// Set route request string
					$route_request_parameters_string = implode('/', $controller->parameters);

					if(preg_match('#^' . $route_map_pattern . '$#', $request_path)) {

						// Set regex callback match for request parameters
						if(strpos($route_request_parameters_string, '$') !== false && strpos($route_map_pattern, '(') !== false) {

							// Replace regex callbacks
							$route_request_parameters_string = preg_replace('#^' . $route_map_pattern . '$#', $route_request_parameters_string, $request_path);

						}

						// Get component
						$_component = $route_map['component'];

						// Set controller
						$_controller = $controller->name;

						// Set controller callback
						$_controller_callback = $controller->callback;

						// Set controller callback parameters
						$_controller_callback_parameters = array_filter(explode('/', $route_request_parameters_string));

						// Set action
						$_action = (is_string($action->name) === true) ? $action->name : null;

						// Set action callback
						$_action_callback = $_controller_callback;

						// Set action callback parameters
						$_action_callback_parameters = $_controller_callback_parameters;

						// Set request component
						$this->request->setProperty('component', $_component);

						// Set request action
						$this->request->setProperty('action', $_action);
						$this->request->setProperty('action_callback', $_action_callback);
						$this->request->setProperty('action_callback_parameters', $_action_callback_parameters);

						// Set request controller
						$this->request->setProperty('controller', $_controller);
						$this->request->setProperty('controller_callback', $_controller_callback);
						$this->request->setProperty('controller_callback_parameters', $_controller_callback_parameters);

						$this->resolveCurrentRequestRoute($route_map_pattern, $route_map);

						// Match found
						$route_match = true;

						// Match found, break loop
						break;

					}

				}

			}

		}

		// Attempt to dynamically resolve route
		if($route_match === false) {

			$segments = array_filter(explode('/', $this->request_path));

			$_controller = (isset($segments[0])) ? ucfirst(strtolower($segments[0])) : $this->request->default_resource;

			$_controller_callback = (isset($segments[1])) ? trim($segments[1]) : $this->request->default_resource_callback;

			$_controller_callback_parameters = array_splice($segments, 2);

			// Set request action
			$this->request->setProperty('action', $_controller);
			$this->request->setProperty('action_callback', $_controller_callback);
			$this->request->setProperty('action_callback_parameters', $_controller_callback_parameters);

			// Set request controller
			$this->request->setProperty('controller', $_controller);
			$this->request->setProperty('controller_callback', $_controller_callback);
			$this->request->setProperty('controller_callback_parameters', $_controller_callback_parameters);

		}

		\Rae\Benchmark::assert($log_key);

	}

	/**
	 *	resolveResources
	 *
	 *	Should resolve router resources.
	 *
	 *	@return void
	 */
	protected abstract function resolveResources();

	/**
	 *	delegate
	 *
	 *	Resolves request parameters and resources and invokes resource callback methods.
	 *
	 *	@throws Exceptions\DelegationException
	 *
	 *	@return void
	 */
	public function delegate() {

		$log_key = "Router_Delegate_" . time();

		\Rae\Benchmark::log($log_key, "Request route delegation time.", __METHOD__, __FILE__, __LINE__);

		// Resolve current route
		$this->resolveRequestRoute();

		// Resolve router resources
		$this->resolveResources();

		// Iterate through each registered resource
		foreach($this->resources as $type => $resource) {

			// Determine whether current resource is optional or not
			$is_optional = (in_array($type, $this->optional_resources) === true) ? true : false;

			// Throw exception if required resource could not be imported
			if($is_optional === false && $resource->canImport() === false) {

				throw new Exceptions\DelegationException(
					'Could not import resource file for "' . $resource->getType() . '" (' . $resource->getName() . ').',
					"Input file for this resource does not exist.",
					__METHOD__, Exceptions\DelegationException::UNEXPECTED_RESULT_EXCEPTION, array(
						'http_status_code' => 404
					)
				);

			}

			// Continue if everything is allright
			if($resource->canImport() === true) {

				// Import resource file
				$resource->import();

				// Get resource instance
				$instance = $resource->createInstance();

				// Get resource callback
				$callback = $this->request->{"{$type}_callback"};

				// Get resource callback parameters
				$parameters = $this->request->{"{$type}_callback_parameters"};

				// Callback does not exist, and is not optional
				if($is_optional === false && method_exists($instance, $callback) === false) {

					// Call resource method and pass in arguments
					call_user_func_array(array($instance, 'error'), array());

				}

				// Only call callback if method exists
				if(method_exists($instance, $callback) === true) {

					// Call resource method and pass in arguments
					call_user_func_array(array($instance, $callback), $parameters);

				}

			}

		}

		\Rae\Benchmark::assert($log_key);

	}

}
?>