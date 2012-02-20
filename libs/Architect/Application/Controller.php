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

/* @namespace Application */
namespace Architect\Application;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Controller
 *
 *	Application controller interface.
 *
 *	@package Application
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
interface Controller {

	/**
	 *	index
	 *
	 *	Initial controller method called by router.
	 *
	 *	@return void
	 */
	public function index();

	/**
	 *	error
	 *
	 *	Controller error method.
	 *
	 *	@return void
	 */
	public function error();

}
?>