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
 *	ExceptionAbstract
 *
 *	Skeleton exception class used to handle framework specific exceptions, inherits from native {@man Exception} class.
 *
 *	@package Exceptions
 *	@subpackage Abstracts
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class ExceptionAbstract extends \Exception {

	/**
	 *	@const int BAD_CALL_EXCEPTION Bad function or method call exception.
	 */
	const BAD_CALL_EXCEPTION = 1;

	/**
	 *	@const int DOMAIN_EXCEPTION Invalid domain exception.
	 */
	const DOMAIN_EXCEPTION = 2;

	/**
	 *	@const int INVALID_ARGUMENT_EXCEPTION Invalid argument exception.
	 */
	const INVALID_ARGUMENT_EXCEPTION = 4;

	/**
	 *	@const int MALFORMED_ARGUMENT_EXCEPTION Malformed argument exception.
	 */
	const MALFORMED_ARGUMENT_EXCEPTION = 8;

	/**
	 *	@const int LENGTH_EXCEPTION Length exception.
	 */
	const LENGTH_EXCEPTION = 16;

	/**
	 *	@const int BOUNDS_EXCEPTION Out of bounds exception.
	 */
	const BOUNDS_EXCEPTION = 32;

	/**
	 *	@const int UNEXPECTED_RESULT_EXCEPTION Unexpected result exception.
	 */
	const UNEXPECTED_RESULT_EXCEPTION = 64;
	
	/**
	 *	@const int UNEXPECTED_VALUE_EXCEPTION Unexpected value exception.
	 */
	const UNEXPECTED_VALUE_EXCEPTION = 128;

	/**
	 *	@const int EMPTY_RESULT_EXCEPTION Empty result exception.
	 */
	const EMPTY_RESULT_EXCEPTION = 256;

	/**
	 *	@const int RUNTIME_EXCEPTION Runtime exception.
	 */
	const RUNTIME_EXCEPTION = 512;

	/**
	 *	@var string $reason Exception reason associated with exception message.
	 */
	protected $reason;

	/**
	 *	@var string $context Exception context, may be method call or process.
	 */
	protected $context;

	/**
	 *	Constructor
	 *
	 *	Invokes parent constructor and sets class members.
	 *
	 *	@param string $message Exception message.
	 *	@param string $reason Exception reasone.
	 *	@param string $context Exception context.
	 *	@param int $code Exception type code.
	 *	@param array $meta Optional parameter, additional exception data.
	 *
	 *	@return void
	 */
	public function __construct($message, $reason, $context, $code, $meta = array()) {

		// Invoke parent constructor
		parent::__construct($message, $code);

		// Set exception reason
		$this->reason = (is_string($reason) === true) ? $reason : null;

		// Set exception context
		$this->context = (is_string($context) === true) ? $context : null;

		// Populate additional exception data
		foreach($meta as $key => $obj) {

			// Set property if variable 'key' is a string
			if(is_string($key) === true) {

				$this->$key = $obj;

			}

		}

	}

	/**
	 *	getExceptionName
	 *
	 *	Returns exception class name, without namespace.
	 *
	 *	@return string
	 */
	public function getExceptionName() {
		
		$segments = explode(NAMESPACE_SEPARATOR, get_class($this));
		
		return end($segments);

	}

	/**
	 *	getExceptionNamespace
	 *
	 *	Returns full class name including namespace.
	 *
	 *	@return string
	 */
	public function getExceptionNamespace() {
	
		return get_class($this);
	
	}

	/**
	 *	getReason
	 *
	 *	Returns exception reason.
	 *
	 *	@return string
	 */
	public function getReason() {

		return $this->reason;

	}

	/**
	 *	getContext
	 *
	 *	Returns exception context.
	 *
	 *	@return string
	 */
	public function getContext() {

		return $this->context;

	}

	/**
	 *	getCodeAsString
	 *
	 *	Must return name of code as a string.
	 *
	 *	@return string
	 */
	abstract public function getCodeAsString();

}
?>