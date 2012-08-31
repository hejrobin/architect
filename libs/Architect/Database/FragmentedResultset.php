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
 *	FragmentedResultset
 *
 *	Class used to create and handle fragmented resultsets, also known as "paged resultsets".
 *
 *	@package Database
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class FragmentedResultset extends \Architect\Data\Fragment {

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
	 *	Sets class constructor.
	 *
	 *	@param resource $db Instance of {@link Connection}.
	 *	@param string $query SQL string, without limit and offset.
	 *
	 *	@return void
	 */
	public function __construct(Connection $db, $query, $limit = 20) {

		$this->db = $db;

		$this->query = $query;

		$this->limit = $limit;

	}

	/**
	 *	register
	 *
	 *	Registers a new collection and segment.
	 *
	 *	@return void
	 */
	public function register() {

		$this->registerObjects(new \Architect\Data\Collection(array()), new \Architect\Data\Segment($this->count, $this->limit));

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

		$this->segment->point($cursor);

	}

	/**
	 *	execute
	 *
	 *	Executes SQL query and returns results based on segment pointer.
	 *
	 *	@return array
	 */
	public function extract() {

		$results = array();

		$sql = "{$this->query} LIMIT :offset, :limit";

		$query = $this->db->prepare($sql);

		$query->bindValue(':offset', intval($this->segment->getOffset()), \PDO::PARAM_INT);
		$query->bindValue(':limit', intval($this->segment->getLimit()), \PDO::PARAM_INT);

		$is_success = $query->execute();

		if($is_success === true) {

			$results = $query->fetchAll();

		}

		return $results;

	}

}
?>