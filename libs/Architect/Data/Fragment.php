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
 *	Abstract class containing logic to extract segment from a collection of data based on {@see Segment}.
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
	 *	@var SegmentObject $segment Instance of {@see Segment}.
	 */
	protected $segment;

	/**
	 *	registerObjects
	 *
	 *	Passes through {@see Collection} and {@see Segment} instances.
	 *
	 *	@param Collection $collection Instance of {@see Collection}.
	 *	@param Segment $segment Instance of {@see Segment}.
	 *
	 *	@return void
	 */
	public function registerObjects(Collection $collection, Segment $segment) {

		$this->collection = $collection;

		$this->segment = $segment;

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