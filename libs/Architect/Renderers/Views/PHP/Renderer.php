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

/* @namespace PHP */
namespace Architect\Renderers\Views\PHP;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Renderer
 *
 *	Simple PHP view class.
 *
 *	@package Renderers
 *	@subpackage Views
 *	@subpackage PHP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Renderer extends \Architect\Renderers\Views\Renderer {

	/**
	 *	invoke
	 *
	 *	Fetches output buffer and returns it.
	 *
	 *	@return string
	 */
	public function invoke() {

		// Capture output buffer
		@ob_start();

		// Import view file
		$this->view->import($this->view_file);

		// Return and clean buffer
		return ob_get_clean();

	}

}
?>