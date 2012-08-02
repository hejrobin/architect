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
 *	Navigator
 *
 *	Class used to create instance of router and request, for easy access and handling.
 *
 *	@package Delegation
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Navigator {

	/**
	 *	@var \Architect\Delegation\Request $request Instance of {@see \Architect\Delegation\Request}.
	 */
	protected $request;

	/**
	 *	@var \Architect\Delegation\Router $router Instance of {@see \Architect\Delegation\Router}.
	 */
	protected $router;

	/**
	 *	@var array $parsed_route_maps Array containing parsed route maps.
	 */
	protected $parsed_route_maps;

	/**
	 *	Constructor
	 *
	 *	Creates instances of {@see \Architect\Delegation\Request} and {@see \Architect\Delegation\Router} based on adapter name.
	 *
	 *	@param string $adapter Adapter name.
	 *	@param array $parsed_route_maps Array containing parsed route maps.
	 *
	 *	@return void
	 */
	public function __construct($adapter, $parsed_route_maps = array()) {

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Set route maps
		$this->parsed_route_maps = $parsed_route_maps;

		// Create a new request object
		$this->request = call_user_func_array(array(new \ReflectionClass('\Architect\Delegation\Requests\\' . $adapter), 'newInstance'), array(
			$parsed_route_maps['default_controller'],
			$parsed_route_maps['default_controller_callback']
		));

		// Create a new router object
		$this->router = call_user_func_array(array(new \ReflectionClass('\Architect\Delegation\Routers\\' . $adapter), 'newInstance'), array(null, $this->request));

		// Pass parsed route maps array to router
		$this->router->setParsedRouteMaps($this->parsed_route_maps);

	}

	/**
	 *	route
	 *
	 *	Loads request based on input request path to use for delegation.
	 *
	 *	@param string $request_path Request path.
	 *
	 *	@return void
	 */
	public function route($request_path) {

		$this->router->setRequestPath($request_path);

		\Rae\Console::log("Set route to \"{$request_path}\".", __METHOD__, __FILE__, __LINE__);

	}

	/**
	 *	delegate
	 *
	 *	Calls registered router delegation method.
	 *
	 *	@return void
	 */
	public function delegate() {

		$this->router->delegate();

	}

}
?>