<?php
// Enable garbage collection
gc_enable();

// Anonymous function used to import files
$import = function($include_path) {

	// Normalize include path
	$include_path = implode(DIRECTORY_SEPARATOR, array(
		dirname(__FILE__),
		str_ireplace('\/', DIRECTORY_SEPARATOR, $include_path))
	);

	// Require file
	require_once $include_path;

};

// Include Rae
$import('libs/Rae/Bootstrap.php');

try {

	// Create root path constant
	define('ARCH_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

	// Include framework bootstrap
	$import('libs/Architect/Bootstrap.php');

	// Get instance of Architect
	$arch = Architect::getInstance();

	// Parse route maps
	$route_maps_parser = new \Architect\Core\Config\RouteMapsParser();

	// Get parsed route maps
	$parsed_route_maps = $route_maps_parser->getParsedRouteMaps();

	// Navigator
	$navigator = new \Architect\Delegation\Navigator('HTTP', $parsed_route_maps);

	// Include root bootstrap file, if exists
	if(file_exists(ARCH_ROOT_PATH . 'bootstrap.php') === true) {

		\Architect\Core\BootstrapLoader::load(ARCH_ROOT_PATH . 'bootstrap.php');

	}

	// Include custom bootstrap files
	\Architect\Core\BootstrapLoader::import();

	// Set route by URI request path
	$navigator->route($arch->uri->getRequestPath());

	// Analyze and collect data from Rae
	\Rae\Environment::analyze();
	\Rae\Benchmark::analyze();
	\Rae\Constant::analyze();
	\Rae\Console::analyze();
	\Rae\Memory::analyze();
	\Rae\File::analyze();

	// Delegate
	$navigator->delegate();

} catch(Architect\Delegation\Exceptions\DelegationException $exception) {

	$arch = \Architect::getInstance();

	// Set HTTP status code
	$arch->http->setStatusCode($exception->http_status_code);

	echo af_render_internal_view('HTTPStatus.php', array(
		'name' => $arch->http->getStatusMessage(),
		'type' => $arch->http->getStatusType(),
		'message' => $exception->getMessage(),
		'reason' => $exception->getReason(),
		'file' => $exception->getFile(),
		'line' => $exception->getLine(),
		'code' => $arch->http->getStatusCode(),
		'code_string' => $exception->getCodeAsString(),
		'trace' => $exception->getTrace()
	));

} catch(Architect\Exceptions\ErrorException $exception) {

	echo af_render_internal_view('ErrorException.php', array(
		'name' => ucfirst(strtolower($exception->getCodeAsString())),
		'type' => 'PHP Error',
		'message' => $exception->getMessage(),
		'file' => $exception->getFile(),
		'line' => $exception->getLine(),
		'code' => $exception->getCode(),
		'code_string' => $exception->getCodeAsString(),
		'trace' => $exception->getTrace()
	));

} catch(Architect\Exceptions\ExceptionAbstract $exception) {

	echo af_render_internal_view('Exception.php', array(
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
	));

}
?>