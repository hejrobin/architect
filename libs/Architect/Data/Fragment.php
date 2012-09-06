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
 *	Fragment
 *
 *	Abstract class that should contain logic to extract fragments of data from a larger collection, may utilize {@see FragmentCoordinates}.
 *
 *	@package Data
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class Fragment {

	/**
	 *	@var array $collection Instance of {@see Collection}.
	 */
	protected $collection;

	/**
	 *	@var FragmentCoordinates $coordinates Instance of {@see FragmentCoordinates}.
	 */
	protected $coordinates;

	/**
	 *	registerObjects
	 *
	 *	Passes through {@see Collection} and {@see Segment} instances.
	 *
	 *	@param Collection $collection Instance of {@see Collection}.
	 *	@param FragmentCoordinates $coordinates Instance of {@see FragmentCoordinates}.
	 *
	 *	@return void
	 */
	public function registerObjects(Collection $collection, FragmentCoordinates $coordinates) {

		$this->collection = $collection;

		$this->coordinates = $coordinates;

	}

	/**
	 *	getCollectionObject
	 *
	 *	Returns registered colleciton object.
	 *
	 *	@return object
	 */
	public function getCollectionObject() {

		return $this->collection;

	}

	/**
	 *	getCoordinatesObject
	 *
	 *	Returns registered coordinates object.
	 *
	 *	@return object
	 */
	public function getCoordinatesObject() {

		return $this->coordinates;

	}

	/**
	 *	extract
	 *
	 *	Should contain logic to extract a segment form {@see Fragment::$collection} based on {@see Fragment::$segment}, may return a new {@see Collection} object.
	 *
	 *	@return void|Collection
	 */
	public abstract function extract();

}
?>