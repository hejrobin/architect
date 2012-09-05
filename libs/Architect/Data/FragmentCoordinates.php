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
 *	FragmentCoordinates
 *
 *	Class containing information about data collection fragment. May be used to create paged collections.
 *
 *	@package Data
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class FragmentCoordinates {

	/**
	 *	@var int $length Collection length.
	 */
	protected $length;

	/**
	 *	@var int $cursor Collection cursor.
	 */
	protected $cursor;

	/**
	 *	@var int $limit Data segment limit.
	 */
	protected $limit;

	/**
	 *	@var int $offset Collection offset.
	 */
	protected $offset;

	/**
	 *	@var int $segments Number of segments.
	 */
	protected $segments;

	/**
	 *	Constructor
	 *
	 *	Sets length and limit and points to first segment in collection.
	 */
	public function __construct($length, $limit) {

		$this->setLength($length);

		$this->setLimit($limit);

		$this->point(0);

	}

	/**
	 *	setLength
	 *
	 *	Sets collection length.
	 *
	 *	@return void
	 */
	public function setLength($length) {

		$this->length = $length;

	}

	/**
	 *	getLength
	 *
	 *	Gets collection length.
	 *
	 *	@return void
	 */
	public function getLength() {

		return $this->length;

	}

	/**
	 *	setCursor
	 *
	 *	Sets collection cursor.
	 *
	 *	@return void
	 */
	public function setCursor($cursor) {

		$this->cursor = $cursor;

	}

	/**
	 *	getCursor
	 *
	 *	Gets collection cursor.
	 *
	 *	@return void
	 */
	public function getCursor() {

		return $this->cursor;

	}

	/**
	 *	setLimit
	 *
	 *	Sets collection limit.
	 *
	 *	@return void
	 */
	public function setLimit($limit) {

		$this->limit = $limit;

	}

	/**
	 *	getLimit
	 *
	 *	Gets collection limit.
	 *
	 *	@return void
	 */
	public function getLimit() {

		return $this->limit;

	}

	/**
	 *	setOffset
	 *
	 *	Sets collection offset.
	 *
	 *	@return void
	 */
	public function setOffset($offset) {

		$this->offset = $offset;

	}

	/**
	 *	getOffset
	 *
	 *	Gets collection offset.
	 *
	 *	@return void
	 */
	public function getOffset() {

		return $this->offset;

	}

	/**
	 *	setSegments
	 *
	 *	Sets collection segments.
	 *
	 *	@return void
	 */
	public function setSegments($segments) {

		$this->segments = $segments;

	}

	/**
	 *	getSegments
	 *
	 *	Gets collection segments.
	 *
	 *	@return void
	 */
	public function getSegments() {

		return $this->segments;

	}

	/**
	 *	point
	 *
	 *	Points to a segment in a data collection.
	 *
	 *	@return void
	 */
	public function point($cursor = null) {

		if(is_null($cursor) === true) {

			$cursor = $this->getCursor();

		}

		$this->setSegments(ceil($this->getLength() / $this->getLimit()));

		$this->setCursor(min(max($cursor, 1), $this->getLength()));

		$this->setOffset(($this->getCursor() - 1) * $this->getLimit());

	}

	/**
	 *	prev
	 *
	 *	Sets pointer to previous segment.
	 *
	 *	@return void
	 */
	public function prev() {

		$this->point($this->getCursor() - 1);

	}

	/**
	 *	next
	 *
	 *	Sets pointer to next segment.
	 *
	 *	@return void
	 */
	public function next() {

		$this->point($this->getCursor() + 1);

	}

	/**
	 *	getCoordinates
	 *
	 *	Returns an object containing segment location in collection.
	 *
	 *	@return object
	 */
	public function getCoordinates() {

		return (object) array(
			'length' => $this->getLength(),
			'segments' => $this->getSegments(),
			'limit' => $this->getLimit(),
			'offset' => $this->getOffset(),
			'cursor' => $this->getCursor()
		);

	}

}
?>