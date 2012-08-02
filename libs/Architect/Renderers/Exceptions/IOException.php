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

/* @namespace Exceptions */
namespace Architect\Renderers\Exceptions;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	RendererException
 *
 *	Renderer exception, inherits from {@see \Architect\Exceptions\Exception}.
 *
 *	@package Renderers
 *	@subpackage Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class RendererException extends \Architect\Exceptions\Exception {}
?>