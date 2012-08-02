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

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	af_render_internal_view
 *
 *	Helper function to quickly render internal views.
 *
 *	@param string $view_file View file name.
 *	@param array $variables Optional parameter, template variables.
 *
 *	@return string
 */
function af_render_internal_view($view_file, $variables = array()) {

	// Create view object
	$view = new \Architect\Renderers\Views\PHP\View();

	// Set include path
	$view->setIncludePath(ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);

	// Set view file
	$view->setViewFile($view_file);

	// Store variables
	$view->setVariables($variables);

	// Render and return view
	return $view->render();

}

/**
 *	af_render_internal_http_status_view
 *
 *	Helper function to quickly render HTTP status views.
 *
 *	@param int $http_status_code HTTP status code.
 *
 *	@return string
 */
function af_render_internal_http_status_view($http_status_code) {

	$arch = \Architect::getInstance();

	// Set HTTP status code
	$arch->http->setStatusCode($http_status_code);

	// Return rendered view
	return af_render_internal_view('HTTPStatus.php', array(
		'name' => $arch->http->getStatusMessage(),
		'type' => $arch->http->getStatusType(),
		'code' => $arch->http->getStatusCode(),
		'trace' => $arch->http->getHeaders()
	));

}

/**
 *	af_render_view
 *
 *	Helper function to quickly render internal views.
 *
 *	@param string $view_file View file name.
 *	@param array $variables Optional parameter, template variables.
 *	@param string $include_path Optional parameter, include path.
 *
 *	@return string
 */
function af_render_view($view_file, $variables = array(), $include_path = null) {

	// Create view object
	$view = new \Architect\Renderers\Views\PHP\View();

	// Set include path
	if(is_string($include_path) === true) {

		$view->setIncludePath($include_path);

	}

	// Set view file
	$view->setViewFile($view_file);

	// Set view variables
	$view->setVariables($variables);

	// Render and return view
	return $view->render();

}
?>