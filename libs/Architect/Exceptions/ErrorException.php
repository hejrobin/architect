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
namespace Architect\Exceptions;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	ErrorException
 *
 *	Error exception class, inherits from {@see Architect\Eceptions\ExceptionAbstract}.
 *
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class ErrorException extends ExceptionAbstract {

	/**
	 *	getCodeAsString
	 *
	 *	Returns error code as a string.
	 *
	 *	@return string
	 */
	public function getCodeAsString() {

		// Determine error code
		switch($this->getCode()) {

			case E_USER_ERROR :

				return 'FATAL_ERROR';

			break;
			case E_WARNING :
			case E_USER_WARNING :

				return 'WARNING';

			break;
			case E_NOTICE :
			case E_USER_NOTICE :
			case @E_STRICT :

				return 'NOTICE';

			break;
			case @E_RECOVERABLE_ERROR :

				return 'CATCHABLE';

			break;
			default :

				return 'UNKNOWN';

			break;

		}

	}

}
?>