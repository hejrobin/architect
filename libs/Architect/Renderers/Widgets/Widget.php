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

/* @namespace idgets */
namespace Architect\Renderers\Widgets;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Widget
 *
 *	Widget interface.
 *
 *	@package Renderers
 *	@subpackage Widgets
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
interface Widget {

	/**
	 *	render
	 *
	 *	Should render widget data and return it.
	 *
	 *	@return mixed
	 */
	public function render();

}
?>