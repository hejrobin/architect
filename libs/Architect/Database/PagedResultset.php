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

/* @namespace Database */
namespace Architect\Database;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	PagedResultset
 *
 *	Class used to create and handle "paged" resultsets.
 *
 *	@package Database
 *
 *	@dependencies \Architect\Data\Collection, \Architect\Data\FragmentCoordinates, \Architect\Database\ResultsetAdapters\PagedResultset, \Architect\Database\ResultsetAdapters\CachedPagedResultset
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class PagedResultset extends \Architect\Data\Fragment {

	/**
	 *	@var bool $is_cached_resultset If set to true, this class uses {@see \Architect\Database\ResultsetAdapters\CachedPagedResultset} instead of {@see \Architect\Database\ResultsetAdapters\PagedResultset}.
	 */
	protected $is_cached_resultset = false;

	/**
	 *	@var int $lifetime Cache lifetime, only applies if {@see View::$is_cached_resultset} is set to true.
	 */
	protected $lifetime;

	/**
	 *	@var resource $db Instance of {@see Connection}.
	 */
	protected $db;

	/**
	 *	@var string $query SQL string, without limit and offset.
	 */
	protected $query;

	/**
	 *	@var int $count Row count used for segment.
	 */
	protected $count = 0;

	/**
	 *	@var int $limit Limit, number of rows to return.
	 */
	protected $limit = 0;

	/**
	 *	@var int $cursor Segment cursor.
	 */
	protected $cursor;

	/**
	 *	Constructor
	 *
	 *	Sets class properties.
	 *
	 *	@param resource $db Instance of {@link Connection}.
	 *	@param string $query SQL string, without limit and offset.
	 *	@param int $limit SQL query limit.
	 *	@param bool $is_cached_resultset Whether to use cache or not.
	 *
	 *	@return void
	 */
	public function __construct(Connection $db, $query, $limit = 20, $is_cached_resultset = false) {

		$this->db = $db;

		$this->query = $query;

		$this->limit = $limit;

		$this->is_cached_resultset = (is_bool($is_cached_resultset)) ? $is_cached_resultset : false;

	}

	/**
	 *	register
	 *
	 *	Registers a new collection and segment.
	 *
	 *	@return void
	 */
	public function register() {

		$this->registerObjects(new \Architect\Data\Collection(array()), new \Architect\Data\FragmentCoordinates($this->count, $this->limit));

	}

	/**
	 *	countRow
	 *
	 *	Executes a count query used to calculate data used in {@link \Architect\Data\Segment}.
	 *
	 *	@param string $query Count query string.
	 *
	 *	@return void
	 */
	public function countRow($query) {

		$query = $this->db->prepare($query);

		$is_success = $query->execute();

		if($is_success === true) {

			$this->count = $query->fetchColumn();

			$this->register();

		}

	}

	/**
	 *	point
	 *
	 *	Points to a segment in a data collection.
	 *
	 *	@return void
	 */
	public function point($cursor) {

		$this->coordinates->point($cursor);

	}

	/**
	 *	execute
	 *
	 *	Executes SQL query and returns results based on fragment coordinates pointer.
	 *
	 *	@return array
	 */
	public function extract() {

		$offset = $this->coordinates->getOffset();
		$limit = $this->coordinates->getLimit();

		if($this->is_cached_resultset === true) {

			$adapter = new \Architect\Database\ResultsetAdapters\CachedPagedResultset($this->db, $this->query, $offset, $limit);

		} else {

			$adapter = new \Architect\Database\ResultsetAdapters\PagedResultset($this->db, $this->query, $offset, $limit);

		}

		return $adapter->getResultset();

	}

}
?>