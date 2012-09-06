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
 *	CachedPagedResultset
 *
 *	Cached paged resultset, stores and retrieves resultsets from cache.
 *
 *	@package Database
 *	@subpackage ResultsetAdapters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class CachedPagedResultset extends ResultsetAdapter {

	/**
	 *	fetchResultset
	 *
	 *	Called when cache does not exist.
	 *
	 *	@return array
	 */
	protected function fetchResultset() {

		$adapter = new \Architect\Database\ResultsetAdapters\PagedResultset($this->db, $this->sql, $this->offset, $this->limit);

		return $adapter->getResultset();

	}

	/**
	 *	getResultset
	 *
	 *	Executes query based on registered query, limit and offset and returns results.
	 *
	 *	@return array|object
	 */
	public function getResultset() {

		$arch = \Architect::getInstance();

		$cache_key_name = "{$this->sql}:{$this->offset}:{$this->limit}";

		if($arch->hasInstance('cache') === true && $arch->cache->has($cache_key_name)) {

			$results = $arch->cache->read($cache_key_name);

		} else {

			$results = $this->fetchResultset();

			$arch->cache->write($cache_key_name, $results, ARCH_CACHE_LIFETIME_QUERIES);

		}

		return $results;

	}

}
?>