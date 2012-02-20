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

/* @namespace Exceptions */
namespace Architect\Exceptions;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Exception
 *
 *	General framework exception, inherits from {@see Architect\Eceptions\ExceptionAbstract}.
 *
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Exception extends ExceptionAbstract {

	/**
	 *	getCodeAsString
	 *
	 *	Returns constant name from exception code as defined in {@see ExceptionAbstract}.
	 *
	 *	@return string
	 */
	public function getCodeAsString() {

		// There is no native function to get class constants, use a reflection
		// @link http://php.net/manual/en/reflectionclass.getconstants.php
		$self = new \ReflectionClass(get_parent_class($this));
		$constants = array_combine(array_values($self->getConstants()), array_keys($self->getConstants()));

		// Return constant name
		return $constants[$this->getCode()];

	}

}
?>