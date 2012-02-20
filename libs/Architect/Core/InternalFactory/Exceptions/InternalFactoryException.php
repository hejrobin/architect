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

/* @namespace InternalFactory */
namespace Architect\Core\InternalFactory\Exceptions;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	InternalFactoryException
 *
 *	InternalFactory exceptions, inherits from {@see \Architect\Exceptions\Exception}.
 *
 *	@package Core
 *	@subpackage InternalFactory
 *	@subpackage Exceptions
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class InternalFactoryException extends \Architect\Exceptions\Exception {}
?>