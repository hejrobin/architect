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

/* @namespace JSON */
namespace Architect\Domain\JSON;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Object
 *
 *	Class to encode, and decode JSON objects.
 *
 *	@package Domain
 *	@subpackage JSON
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Object {

	/**
	 *	@var array|object|string $data Source data.
	 */
	protected $data;

	/**
	 *	@var string $type Source object type.
	 */
	protected $type;

	/**
	 *	Constructor
	 *
	 *	Creates a new JSON object.
	 *
	 *	@param array|object|string $data Source data.
	 *
	 *	@return void
	 */
	public function __construct($data) {

		// Get data type
		$data_type = gettype($data);

		switch($data_type) {

			case 'array' :
			case 'object' :
			case 'string' :

				$this->type = $data_type;

			break;

		}

		// Throw exception if type data is invalid
		if(is_null($this->type) === true) {

			throw new Exceptions\DocumentException(
				"Could not create a new JSON Object.",
				'Input type must be either of "array", "object" or "string".',
				__METHOD__, Exceptions\DocumentException::DOMAIN_EXCEPTION
			);

		}

		// Set data
		$this->data = $data;

	}

	/**
	 *	getString
	 *
	 *	Returns JSON data as string.
	 *
	 *	@return string|null
	 */
	public function getString() {

		if($this->type === 'string') {

			return $this->data;

		} else {

			return json_encode($this->data);

		}

		return null;

	}

	/**
	 *	getObject
	 *
	 *	Returns JSON data as an object.
	 *
	 *	@return array|null
	 */
	public function getObject() {

		if($this->type === 'object') {

			return $this->data;

		} else {

			return json_decode(json_encode($this->data));

		}

		return null;

	}

	/**
	 *	getArray
	 *
	 *	Returns JSON data as an associative array.
	 *
	 *	@return array|null
	 */
	public function getArray() {

		if($this->type === 'array') {

			return $this->data;

		} else {

			return json_decode(json_encode($this->data), true);

		}

		return null;

	}

}
?>