<?php
/**
 *	Architect Framework
 *
 *	Architect Framework is a light-weight and scalable object oriented web applications framework built for PHP 5.3 and later.
 *	Architect focuses on handling common tasks and processes used to quickly develop small, medium and large scale applications.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://architect.kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/lgpl-2.1.php LGPL
 */

/* @namespace Config */
namespace Architect\Core\Config;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	RouteMapsParser
 *
 *	Parses through each defined route node in config.xml.
 *
 *	@package Core
 *	@subpackage Config
 *
 *	@dependencies \Architect\Domain\File\Object, \Architect\Domain\XML\Parser
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class RouteMapsParser {

	/**
	 *	@var \Architect\Domain\XML\Parser $parser Instance of \Architect\Domain\XML\Parser.
	 */
	protected $parser;

	/**
	 *	@var string $current_route_map_pattern Current route map pattern.
	 */
	protected $current_route_map_pattern;

	/**
	 *	@var array $parsed_route_maps Array containing parsed route maps.
	 */
	protected $parsed_route_maps;

	/**
	 *	Constructor
	 *
	 *	Creates a new instance of \Architect\Domain\XML\Parser and invokes internal parser method.
	 *
	 *	@return void
	 */
	public function __construct() {

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Get Architect
		$arch = \Architect::getInstance();

		// Get config.xml file path
		$file_path = ARCH_ROOT_PATH . 'config.xml';

		// Create instance of FileInfo
		$file = new \Architect\Domain\File\Object($file_path);

		// Create instance of Parser
		$this->parser = new \Architect\Domain\XML\Parser($file);

		// Register XML namespace
		$this->parser->registerNamespace('arch', 'http://architect.kodlabbet.net/xmlns');

		// Invoke route maps parsing
		$this->parseRouteMaps();

	}

	/**
	 *	parseRouteMaps
	 *
	 *	Parses defined route maps in config.xml.
	 *
	 *	@return void
	 */
	private function parseRouteMaps() {

		\Rae\Benchmark::log("RouteMapsParser", "Route maps parsing execution time.", __METHOD__, __FILE__, __LINE__);

		// Get default controller
		$default_controller = $this->parser->query("//arch:settings/arch:defaultController")->getValue();

		// Set default controller
		$this->parsed_route_maps['default_controller'] = $default_controller;

		// Get default controller callback
		$default_controller_callback = $this->parser->query("//arch:settings/arch:defaultController")->getAttribute('callback');

		// Set default controller callback
		$this->parsed_route_maps['default_controller_callback'] = $default_controller_callback;

		// Get route maps
		$nodes = $this->parser->queryAll('//arch:routes/arch:route', null, true);

		// Iterate through each route nodes
		if($nodes->length > 0) {

			foreach($nodes as $current_index => $node) {

				// Set current index
				$this->current_index = $current_index;

				// Set current node
				$this->current_node = $node;

				// Select current node
				$node = $this->parser->select($node);

				// Get route map pattern
				$route_map_pattern = $node->getAttribute('map');
				$this->current_route_map_pattern = $route_map_pattern;

				// Set current route map to parsed route maps
				$this->parsed_route_maps[$this->current_route_map_pattern] = array();

				// Get default route SSL option
				$default_route_ssl_option = $this->parser->query('//arch:settings/arch:secureSocketsLayer', null, true)->getAttribute('mode', true, '/^(allow|enforce|restrict)$/');
				$default_route_ssl_option = (strlen($default_route_ssl_option) > 0) ? $default_route_ssl_option : 'allow';

				// Reselect current node
				$this->parser->select($this->current_node);

				// Get route SSL option
				$route_ssl_option = $node->getAttribute('secureSocketsLayer', true, '/^(allow|enforce|restrict)$/');
				$route_ssl_option = (strlen($route_ssl_option) > 0) ? $route_ssl_option : $default_route_ssl_option;

				// Set route SSL option
				$this->parsed_route_maps[$this->current_route_map_pattern]['secure_sockets_layer'] = $route_ssl_option;

				// Parse route component
				$this->parseRouteComponent();

				// Parse route controller
				$this->parseRouteController();

				// Parse route action
				$this->parseRouteAction();

				// Parse request rules
				$this->parseRouteRequestRules();

			}

		}

		// Get parsed route maps
		$parsed_route_maps = $this->parsed_route_maps;

		// Sort and reverse order
		ksort($parsed_route_maps);
		array_reverse($parsed_route_maps);

		// Set sorted route maps
		$this->parsed_route_maps = $parsed_route_maps;

		\Rae\Benchmark::assert("RouteMapsParser");

		\Rae\Memory::log($parsed_route_maps, "Size of parsed route maps.", __METHOD__, __FILE__, __LINE__);

	}

	/**
	 *	getParsedRouteParameters
	 *
	 *	Parses through parameter nodes and returns them as an array.
	 *
	 *	@return array
	 */
	protected function getParsedRouteParameters() {

		// Parameters
		$parameters = array();

		// Fetch controller nodes
		$nodes = $this->parser->queryAll('//arch:parameters/arch:parameter', $this->current_node, true);

		// Populate parameters array
		foreach($nodes as $node) {

			$parameters[] = $node->nodeValue;

		}

		// Return parameters
		return $parameters;

	}

	/**
	 *	parseRouteComponent
	 *
	 *	Parse component node.
	 *
	 *	@return void
	 */
	protected function parseRouteComponent() {

		// Get component, otherwise return null
		$component = $this->parser->query('arch:component', $this->current_node, true)->getValue();

		// Set component to current request
		$this->parsed_route_maps[$this->current_route_map_pattern]['component'] = $component;

	}

	/**
	 *	parseRouteAction
	 *
	 *	Parse action node.
	 *
	 *	@return void
	 */
	protected function parseRouteAction() {

		// Action is exactly the same as the controller
		$this->parsed_route_maps[$this->current_route_map_pattern]['action'] = $this->parsed_route_maps[$this->current_route_map_pattern]['controller'];

	}

	/**
	 *	parseRouteController
	 *
	 *	Parse controller node and parameters, calls {@see getParsedRouteParameters}.
	 *
	 *	@throws Exceptions\ConfigException
	 *
	 *	@return void
	 */
	protected function parseRouteController() {

		// Fetch action node
		$node = $this->parser->query('arch:controller', $this->current_node, true);

		// Get controller
		$controller = $node->getValue();

		// Throw exception if controller was not set
		if(is_null($controller) === true) {

			throw new Exceptions\ConfigException(
				"Could not set controller for current route map.",
				"XML node 'arch:controller' on position {$this->current_index} is missing.",
				__METHOD__, Exceptions\ConfigException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Controller callback
		$callback = $node->getAttribute('callback', true);

		// Set default callback method
		if($callback === null || $callback === '') {

			$callback = 'index';

		}

		// Set current route controller
		$this->parsed_route_maps[$this->current_route_map_pattern]['controller'] = array(
			'name' => $controller,
			'callback' => $callback,
			'parameters' => $this->getParsedRouteParameters()
		);

	}

	/**
	 *	parseRouteRequestRules
	 *
	 *	Parses request rules for a route.
	 *
	 *	@return void
	 */
	protected function parseRouteRequestRules() {

		// Create request types array
		$this->parsed_route_maps[$this->current_route_map_pattern]['request_rules'] = array();
		$this->parsed_route_maps[$this->current_route_map_pattern]['request_rules']['http'] = array();
		$this->parsed_route_maps[$this->current_route_map_pattern]['request_rules']['ajax'] = array();

		// Fetch request nodes
		$nodes = $this->parser->queryAll('arch:request', $this->current_node, true);

		if($nodes->length > 0) {

			foreach($nodes as $node) {

				// Select current node
				$node = $this->parser->select($node);

				// Get request method
				$request_method = $node->getAttribute('method');

				// Get request callback
				$request_callback = $node->getAttribute('callback');

				// Get request type
				$request_type = ($node->getAttribute('ajax', true) === 'true') ? 'ajax' : 'http';

				// Set request rule
				$this->parsed_route_maps[$this->current_route_map_pattern]['request_rules'][$request_type][$request_method] = $request_callback;

			}

		}

	}

	/**
	 *	getParsedRouteMaps
	 *
	 *	Returns parsed route maps.
	 *
	 *	@return array
	 */
	public function getParsedRouteMaps() {

		return $this->parsed_route_maps;

	}

}
?>