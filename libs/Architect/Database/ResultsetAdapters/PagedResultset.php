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

/* @namespace ResultsetAdapters */
namespace Architect\Database\ResultsetAdapters;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	PagedResultset
 *
 *	Paged resultset, returns a resultset based on offset and limit.
 *
 *	@package Database
 *	@subpackage ResultsetAdapters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class PagedResultset extends ResultsetAdapter {

	/**
	 *	getResultset
	 *
	 *	Executes query based on registered query, limit and offset and returns results.
	 *
	 *	@return array|object
	 */
	public function getResultset() {

		$results = array();

		$sql = "{$this->sql} LIMIT :offset, :limit";

		$query = $this->db->prepare($sql);

		$query->bindValue(':offset', intval($this->offset), \PDO::PARAM_INT);
		$query->bindValue(':limit', intval($this->limit), \PDO::PARAM_INT);

		$is_success = $query->execute();

		if($is_success === true) {

			$results = $query->fetchAll();

		}

		return $results;

	}

}
?>