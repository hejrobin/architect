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

/* @namespace Data */
namespace Architect\Data;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Collection
 *
 *	Data collection object.
 *
 *	@package Data
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Collection {

	/**
	 *	@var array $collection Data collection.
	 */
	protected $collection;

	/**
	 *	Constructor
	 *
	 *	Sets collection data.
	 *
	 *	@param array $collection Data collection.
	 *
	 *	@return void
	 */
	public function __construct(array $collection) {

		$this->setCollection($collection);

	}

	/**
	 *	setCollection
	 *
	 *	Sets collection data.
	 *
	 *	@param array $collection Data collection.
	 *
	 *	@return void
	 */
	public function setCollection(array $collection) {

		$this->collection = $collection;

	}

	/**
	 *	getCollection
	 *
	 *	Returns collection.
	 *
	 *	@return array
	 */
	public function getCollection() {

		return $this->collection;

	}

}
?>