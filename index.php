<?php
// Import Jeeves profiling libraries
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . str_ireplace('\/', DIRECTORY_SEPARATOR, 'libs/Jarvis/Bootstrap.php'));

try {

	// Log framework initialization
	\Jarvis\Benchmark::log('Initialization', 'Framework Initialization.', null, __FILE__);
	
	// Create root path constant
	define('ARCH_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
	
	// Include framework bootstrap
	require_once(ARCH_ROOT_PATH . implode(DIRECTORY_SEPARATOR, array('libs', 'Architect', 'Bootstrap.php')));
	
	// Get instance of Architect
	$arch = Architect::getInstance();
	
	// Parse route maps
	$route_maps_parser = new \Architect\Core\Config\RouteMapsParser();
	$parsed_route_maps = $route_maps_parser->getParsedRouteMaps();
	
	$default_controller = $parsed_route_maps['default_controller'];
	
	// Create new instance of a router based on protocol
	$arch->initialize('Architect\Delegation\Routers\\' . ARCH_ROUTER_PROTOCOL, 'router', array($default_controller, $parsed_route_maps));
	
	// Include custom bootstrap files
	\Architect\Core\BootstrapLoader::import();
	
	// Assert log time of framework initialization
	\Jarvis\Benchmark::assert('Initialization');
	
	// Analyze log entries 
	\Jarvis\Benchmark::analyze();
	\Jarvis\Console::analyze();
	\Jarvis\Memory::analyze();
	\Jarvis\File::analyze();

	// Delegate router request
	$arch->router->delegate();

} catch(Architect\HTTP\Exception $exception) {
	
	$arch = \Architect::getInstance();
	
	$arch->http->setStatusCode($exception->http_status_code);
	
	echo af_render_view('HTTPException.php', array(
	
		'base' => $arch->uri->getBaseURI(),
		
		'name' => $arch->http->getStatusMessage(),
		
		'type' => $arch->http->getStatusType(),
		
		'code' => $arch->http->getStatusCode(),
		
		'reason' => $exception->getReason(),
		
		'exception' => print_r($exception, true)
	
	), ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);

} catch(Architect\Exceptions\ErrorException $exception) {
	
	$arch = \Architect::getInstance();
	
	echo af_render_view('ErrorException.php', array(
	
		'base' => $arch->uri->getBaseURI(),
		
		'name' => ucfirst(strtolower($exception->getCodeAsString())),

		'type' => 'PHP Error',

		'message' => $exception->getMessage(),

		'file' => $exception->getFile(),

		'line' => $exception->getLine(),

		'code' => $exception->getCode(),

		'code_string' => $exception->getCodeAsString(),

		'trace' => $exception->getTrace()
	
	), ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);

} catch(Architect\Exceptions\ExceptionAbstract $exception) {
	
	$arch = \Architect::getInstance();
	
	echo af_render_view('Exception.php', array(
	
		'base' => $arch->uri->getBaseURI(),
		
		'name' => $exception->getExceptionName(),

		'type' => 'Framework Exception',

		'message' => $exception->getMessage(),

		'reason' => $exception->getReason(),

		'class' => $exception->getExceptionNamespace(),
	
		'context' => $exception->getContext(),

		'file' => $exception->getFile(),

		'line' => $exception->getLine(),

		'code' => $exception->getCode(),

		'code_string' => $exception->getCodeAsString(),

		'trace' => $exception->getTrace()
	
	), ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);

}
?>