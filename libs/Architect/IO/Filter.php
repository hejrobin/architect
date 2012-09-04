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

/* @namespace I/O */
namespace Architect\IO;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Filter
 *
 *	Interface used to filter input and output.
 *
 *	@package I/O
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
interface Filter {

	/**
	 *	filter
	 *
	 *	Should filter object, may be string, array or object and return filtered object.
	 *
	 *	@param mixed $object Object to filter.
	 *
	 *	@return mixed
	 */
	public function filter($object);

	/**
	 *	output
	 *
	 *	Should, or could reverse filter method, to normalize or sanitize data for output.
	 *
	 *	@param mixed $object Object to filter.
	 *
	 *	@return mixed
	 */
	public function output($object);

}
?>