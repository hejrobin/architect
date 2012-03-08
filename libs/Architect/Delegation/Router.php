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
 *	Generic router class, listens to input URI schema and route maps array.
 *
 *	@package Delegation
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class Router {

	/**
	 *	@var \Architect\URI\URI $protocol URI protocol instance.
	 */
	protected $protocol;

	/**
	 *	@var \Architect\Delegation\Request $request Request object.
	 */
	protected $request;

	/**
	 *	@var array $resources Array containging instances of \Architect\Delegation\Request\Resource
	 */
	protected $resources;

	/**
	 *	@var array $optional_resources Array containging names of optional resource types.
	 */
	protected $optional_resources = array();

	/**
	 *	@var string $default_controller Default request controller.
	 */
	protected $default_controller;

	/**
	 *	@var array $wildcards Route map pattern wildcards
	 */
	protected $wildcards = array(

		':numerical' => '(\d+)',

		':single_word' => '(\w+)',

		':uri_chars' => '([A-Za-z\_\-\+\#\?\&\.\@]+)'

	);

	/**
	 *	Constructor
	 *
	 *	Sets and creates instances used by a router class.
	 *
	 *	@param \Architect\URI\URI $protocol Instance of \Architect\URI\URI.
	 *	@param \Architect\Delegation\Request $request Instance of \Architect\Delegation\Request.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\URI\URI $protocol, \Architect\Delegation\Request $request) {
	
		// Set router URI protocol
		$this->protocol = $protocol;
		
		// Set router request object
		$this->request = $request;
		
		// Pass protocol to request object
		$this->request->setProtocol($this->protocol);
	
	}

	/**
	 *	setResource
	 *
	 *	Sets a resource to current class.
	 *
	 *	@param string $key Resource name.
	 *	@param \Architect\Delegation\Resource $resource Instance of \Architect\Delegation\Resource.
	 *
	 *	@return void
	 */
	public function setResource($key, \Architect\Delegation\Request\Resource $resource) {
	
		// Set resource
		$this->resources[$key] = $resource;
	
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
	 *	getResource
	 *
	 *	Gets resource.
	 *
	 *	@param string $key Resource name.
	 *
	 *	@return \Architect\Delegation\Resource
	 */
	public function getResource($key) {
	
		// Get resource
		return $this->resources[$key];
	
	}

	/**
	 *	resolveResources
	 *
	 *	Should register resources, mind registration order defines execution order.
	 *
	 *	@return void
	 */
	public abstract function resolveResources();

	/**
	 *	resolveRouteMaps
	 *
	 *	Should resolve custom request maps and sets request if a match is found.
	 *
	 *	@return bool
	 */
	protected abstract function resolveRouteMaps();

	/**
	 *	delegate
	 *
	 *	 Resolves request parameters and resources and invokes resource callback methods.
	 *
	 *	@throws \Architect\HTTP\Exception
	 *
	 *	@return void
	 */
	public function delegate() {
	
		// Log execution time of delegation
		\Jarvis\Benchmark::log('Delegation', 'Router request delegation.', null, __FILE__, __LINE__);
	
		// Resolve request parameters
		$this->request->resolveRequest();
		
		// Resolve custom route maps
		$this->resolveRouteMaps();
		
		// Resolve resources
		$this->resolveResources();
		
		// Iterate through each registered resource
		foreach($this->resources as $type => $resource) {
		
			// Determine whether current resource is optional or not
			$is_optional = (in_array($type, $this->optional_resources) === true) ? true : false;
			
			// Throw exception if required resource could not be imported
			if($is_optional === false && $resource->canImport() === false) {
				
				throw new \Architect\HTTP\Exception(
					'Could not import resource file.',
					"Input file for this request does not exist.",
					__METHOD__, \Architect\HTTP\Exception::UNEXPECTED_RESULT_EXCEPTION,
					array(
						'http_status_code' => 404
					)
				);
			
			}
			
			// Only continue if resource could be imported
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
				
					// Throw exception if resource callback method does not exist
					if(ARCH_ENABLE_CONTROLLER_ERROR_HANDLER === false) {
				
						throw new \Architect\HTTP\Exception(
							'Could not delegate router request.',
							"Resource (" . get_class($instance) . ") callback method '{$callback}' does not exist.",
							__METHOD__, \Architect\HTTP\Exception::BAD_CALL_EXCEPTION,
							array(
								'http_status_code' => 404
							)
						);
					
					} else {
					
						// Assert benchmark
						\Jarvis\Benchmark::assert('Delegation');
					
						// Call resource error method and pass in arguments
						call_user_func_array(array($instance, 'error'), $parameters);
					
					}
				
				}
				
				// Only call callback if method exists
				if(method_exists($instance, $callback) === true) {
					
					// Assert benchmark
					\Jarvis\Benchmark::assert('Delegation');
					
					// Call resource method and pass in arguments
					call_user_func_array(array($instance, $callback), $parameters);
				
				}
				
			}
			
		}
	
	}

}
?>