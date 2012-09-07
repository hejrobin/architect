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

/* @namespace Views */
namespace Architect\Renderers\Views;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Renderer
 *
 *	Class used to create renderers for views.
 *
 *	@package Renderers
 *	@subpackage Views
 *
 *	@dependencies \Architect\Renderers\Views\ViewAbstract
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class ViewRenderer implements \Architect\Renderers\Renderer {

	/**
	 *	@var \Architect\Renderers\Views\ViewAbstract $view View object.
	 */
	protected $view;

	/**
	 *	@var string $view_file View file path.
	 */
	protected $view_file;

	/**
	 *	Constructor
	 *
	 *	Passes through view object and view file and registeres them to this class.
	 *
	 *	@param \Architect\Renderers\Views\ViewAbstract $view View object.
	 *	@param string $view_file View file path.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Renderers\Views\ViewAbstract $view, $view_file) {

		$this->view = $view;

		$this->view_file = $view_file;

	}

	/**
	 *	render
	 *
	 *	Must contain logic which fetches, or renderes data for views.
	 *
	 *	@return string
	 */
	public abstract function render();

}
?>