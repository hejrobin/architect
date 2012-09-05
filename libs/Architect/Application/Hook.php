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

/* @namespace Application */
namespace Architect\Application;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Hook
 *
 *	Hooks are classes that are registered to a triggerpoint throughout Architect.
 *
 *	@package Application
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
interface Hook {

	/**
	 *	register
	 *
	 *	Should contain logic which are called when a Hook is registered.
	 *
	 *	@return void
	 */
	public function register();

	/**
	 *	invoke
	 *
	 *	Called whenever registered Hook reaches it's registered triggerpoint. May return "true" on success, or "false" on failure.
	 *
	 *	@return bool
	 */
	public function invoke();

}
?>