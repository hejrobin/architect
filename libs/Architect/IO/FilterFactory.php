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
 *	FilterFactory
 *
 *	Factory class used to hold and invoke registers filters via a call mutator.
 *
 *	@package I/O
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class FilterFactory {

	/**
	 *	@var array $filters Array containing registered filters.
	 */
	protected $filters = array();

	/**
	 *	registerFilter
	 *
	 *	Registers a filter object.
	 *
	 *	@param string $identifier Filter identifier.
	 *	@param object $filter Instance of {@see Filter}.
	 *
	 *	@return void
	 */
	public function registerFilter($identifier, Filter $filter) {

		if(is_string($identifier) === false) {

			throw new Exceptions\IOException(
				'Could not register filter instance object.',
				"Input parameter is not valid, expected 'string', '" . gettype($store) . "' given.",
				__METHOD__, Exceptions\IOException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		if(array_key_exists($identifier, $this->filters) === false) {

			$this->filters[$identifier] = $filter;

		}

	}

	/**
	 *	unregisterFilter
	 *
	 *	Unregisters a filter from internal store, if it exists.
	 *
	 *	@param string $identifier Filter identifier.
	 *
	 *	@return void
	 */
	public function unregisterFilter($identifier) {

		if(is_string($identifier) === false) {

			throw new Exceptions\IOException(
				'Could not register filter instance object.',
				"Input parameter is not valid, expected 'string', '" . gettype($store) . "' given.",
				__METHOD__, Exceptions\IOException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		if(array_key_exists($identifier, $this->filters) === true) {

			unset($this->filters[$identifier]);

		}

	}

	/**
	 *	getFilter
	 *
	 *	Returns a registered filter object.
	 *
	 *	@param string $identifier Filter identifier.
	 *
	 *	@return object
	 */
	public function getFilter($identifier) {

		if(array_key_exists($identifier, $this->filters) === false) {

			throw new Exceptions\IOException(
				"Could not fetch filter registered for '{$identifier}'.",
				"Filter does not exists in internal store.",
				__METHOD__, Exceptions\IOException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		return $this->filters[$identifier];

	}

	/**
	 *	Call mutator
	 *
	 *	Calls for registered filter's filter method.
	 *
	 *	@param string $identifier Filter identifier, may be prepended with "output_".
	 *	@param array $arguments Arguments to pass to filter method.
	 *
	 *	@return mixed
	 */
	public function __call($identifier, $arguments = array()) {

		$callback = 'filter';

		if(strstr($identifier, 'output_') != false) {

			$callback = 'output';

			$identifier = str_ireplace('output_', '', $identifier);

		}

		return call_user_func_array(array($this->getFilter($identifier), $callback), $arguments);

	}

}
?>