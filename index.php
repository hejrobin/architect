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

	// Invoke pre application flow hooks
	af_hooks_invoke('before:applicationflow');

	// Get instance of Architect
	$arch = Architect::getInstance();

	// Parse route maps
	$route_maps_parser = new \Architect\Core\Config\RouteMapsParser();

	// Get parsed route maps
	$parsed_route_maps = $route_maps_parser->getParsedRouteMaps();

	// Navigator
	$arch->initialize('\Architect\Delegation\Navigator', 'navigator', array('HTTP', $parsed_route_maps));

	// Include root bootstrap file, if exists
	if(file_exists(ARCH_ROOT_PATH . 'bootstrap.php') === true) {

		\Architect\Core\BootstrapLoader::load(ARCH_ROOT_PATH . 'bootstrap.php');

	}

	// Include custom bootstrap files
	\Architect\Core\BootstrapLoader::import();

	// Set route by URI request path
	$arch->navigator->route($arch->uri->getRequestPath());

	// Analyze and collect data from Rae
	\Rae\Environment::analyze();
	\Rae\Benchmark::analyze();
	\Rae\Constant::analyze();
	\Rae\Console::analyze();
	\Rae\Memory::analyze();
	\Rae\File::analyze();

	// Invoke pre delegation hooks
	af_hooks_invoke('before:delegation');

	// Delegate
	$arch->navigator->delegate();

	// Invoke post delegation hooks
	af_hooks_invoke('after:delegation');

	// Flush cache, if enabled
	if(ARCH_CACHE_ENABLED === true && $arch->hasInstance('cache') === true) {

		$arch->cache->flush();

	}

	// Invoke post application flow hooks
	af_hooks_invoke('after:applicationflow');

} catch(Architect\Delegation\Exceptions\DelegationException $exception) {

	// Invoke exception hooks
	af_hooks_invoke('exception');

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

	// Invoke exception hooks
	af_hooks_invoke('exception');

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

	// Invoke exception hooks
	af_hooks_invoke('exception');

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

} catch(\PDOException $database_exception) {

	// Invoke exception hooks
	af_hooks_invoke('exception');

	$message = $database_exception->getMessage();

	preg_match('/(SQLSTATE\[.*?\])\s+\[\d{1,4}\](.*)(\(\d+\))/', $message, $matches, PREG_OFFSET_CAPTURE);

	if(count($matches) > 0) {

		$message = trim($matches[2][0]);

	}

	$exception = new \Architect\Database\Exceptions\DatabaseException($message, null, 'PDO', $database_exception->getCode());

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
		'code_string' => $exception->getCode(),
		'trace' => $exception->getTrace()
	));

}
?>