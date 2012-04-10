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

/* @namespace Database */
namespace Architect\Database;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	PagedResultset
 *
 *	Paged resultsets.
 *
 *	@package Database
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class PagedResultset {

	/**
	 *	@var resource $db Database Connection
	 */
	protected $db;

	/**
	 *	@var string $table Database table name.
	 */
	protected $table;

	/**
	 *	@var string $query SQL query.
	 */
	protected $query;
	
	/**
	 *	@var int $offset SQL limit offset.
	 */
	protected $offset;
	
	/**
	 *	@var int $limit SQL limit.
	 */
	protected $limit;

	/**
	 *	@var int $rows_total Total number of rows.
	 */
	protected $rows_total;
	
	/**
	 *	@var int $current_page Current page.
	 */
	protected $current_page;
	
	/**
	 *	@var int $pages_total Total number of pages.
	 */
	protected $pages_total;

	/**
	 *	@var int $navigation_item_range Navigation items range.
	 */
	protected $navigation_item_range = 3;
	
	/**
	 *	@var string $navigation_url_base Navigation item base URL.
	 */
	protected $navigation_item_base_url;

	/**
	 *	Constructor
	 *
	 *	Sets database connection and database table.
	 *
	 *	@param Connection $db Database connection.
	 *	@param string $table
	 *
	 *	@return void
	 */
	public function __construct(Connection $db, $table) {
	
		$this->db = $db;
		
		$this->associate($table);
	
	}

	/**
	 *	associate
	 *
	 *	Associate database table.
	 *
	 *	@param string $table Database table.
	 *
	 *	@return void
	 */
	public function associate($table) {
	
		if(is_string($table) === false) {
			throw new \Architect\Database\DatabaseException(
				'Could not associate table.',
				"Table must be a string.",
				__METHOD__, \Architect\Database\DatabaseException::MALFORMED_ARGUMENT_EXCEPTION
			);
		}
	
		$this->table = $table;
	
	}

	/**
	 *	countColumn
	 *
	 *	Counts number of rows based on column.
	 *
	 *	@param string $column
	 *
	 *	@return int
	 */
	public function countColumn($column) {

		if(!is_int($this->rows_total)) {

			$query = $this->db->query("SELECT COUNT({$column}) FROM {$this->table};");

			$this->rows_total = $query->fetchColumn();
			
		}

		return $this->rows_total;
	}

	/**
	 *	setQuery
	 *
	 *	Sets SQL query.
	 *
	 *	@param string $query SQL query.
	 *
	 *	@return void
	 */
	public function setQuery($query) {

		$this->query = $query;

	}

	/**
	 *	navigationBaseURL
	 *
	 *	Sets navigation URL base.
	 *
	 *	@param string $navigation_item_base_url
	 *
	 *	@return void
	 */
	public function navigationBaseURL($navigation_item_base_url) {

		$this->navigation_item_base_url = $navigation_item_base_url;

	}

	/**
	 *	setLimit
	 *
	 *	Sets the number of rows to return on each page.
	 *
	 *	@param int $limit SQL limit.
	 *
	 *	@return void
	 */
	public function setLimit($limit) {

		$this->limit = $limit;

	}
	
	/**
	 *	numPages
	 *
	 *	Returns the number of pages associated with current resultset.
	 *
	 *	@return int
	 */
	public function numPages() {

		return $this->pages_total;

	}
	
	/**
	 *	currentPage
	 *
	 *	Returns the number of pages associated with current resultset.
	 *
	 *	@return int
	 */
	public function currentPage() {

		return $this->current_page;

	}

	/**
	 *	setPage
	 *
	 *	Sets current page index (sets current page).
	 *
	 *	@param int $page_index
	 *
	 *	@return void
	 */
	public function setPage($page_index = 0) {

		$this->pages_total = ceil($this->rows_total / $this->limit);
    $this->current_page = min(max($page_index, 1), $this->pages_total);
    $this->offset = ($this->current_page - 1) * $this->limit;
 
	}

	/**
	 *	fetchResultset
	 *
	 *	Fetches resultset based on limit and offset.
	 *
	 *	@return object
	 */
	public function fetchResultset() {
	
		$query = $this->db->prepare($this->query);
		$query->bindParam(':limit', $this->limit, \PDO::PARAM_INT);
		$query->bindParam(':offset', $this->offset, \PDO::PARAM_INT);
		
		$query->execute();
		$resultset = $query->fetchAll(\PDO::FETCH_OBJ);
		
		return $resultset;
	}

	/**
	 *	buildNavigation
	 */
	public function buildNavigation() {
	
		$navigation = array();
		
		if($this->rows_total > $this->limit) {
		
			if($this->current_page >= 1) {
			
				$is_disabled = ($this->current_page > 1) ? '' : ' class="disabled"';
				
				$prev_page = $this->current_page - 1;

				if($prev_page == 0)
					$prev_page = 1;
				
				$navigation[] = "<a href=\"{$this->navigation_item_base_url}1\"{$is_disabled}>&#8676;</a>";
				$navigation[] = "<a href=\"{$this->navigation_item_base_url}{$prev_page}\"{$is_disabled}>&#8606;</a>";

			}
		
			for($n = ($this->current_page - $this->navigation_item_range); $n < (($this->current_page + $this->navigation_item_range) + 1); $n++) {

				if(($n > 0) && ($n <= $this->pages_total)) {

					$is_current = ($n == $this->current_page) ? ' class="current"' : '';
					$navigation[] = "<a href=\"{$this->navigation_item_base_url}{$n}\"{$is_current}>{$n}</a>";

				}

			}
		
			if($this->current_page !== $this->pages_total) {

				$is_disabled = ($this->current_page == $this->pages_total) ? ' class="disabled"' : '';
				
				$next_page = $this->current_page + 1;
		
				if($next_page > $this->pages_total)
					$next_page = $this->pages_total;
				
				$navigation[] = "<a href=\"{$this->navigation_item_base_url}{$next_page}\"{$is_disabled}>&#8608;</a>";
				$navigation[] = "<a href=\"{$this->navigation_item_base_url}{$this->pages_total}\"{$is_disabled}>&#8677;</a>";
		
			}
		
		}
		
		return $navigation;
	}

}
?>