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
 *	Connection
 *
 *	Database connection class, extends {@man PDO}.
 *
 *	@package Database
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class ResultsetAdapter {

	/**
	 *	@var \Architect\Database\Connection $db Current database connection object.
	 */
	protected $db;

	/**
	 *	@var string $sql Current SQL query.
	 */
	protected $sql;

	/**
	 *	@var int $offset Query offset.
	 */
	protected $offset;

	/**
	 *	@var int $sql Query limit.
	 */
	protected $limit;

	/**
	 *	Constructor
	 *
	 *	Sets class properties passed from resultset handler.
	 *
	 *	@param \Architect\Database\Connection $db Database connection object.
	 *	@param string $sql Current SQL query.
	 *	@param int $offset Query offset.
	 *	@param int $limit Query limit.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Database\Connection $db, $sql, $offset, $limit) {

		$this->db = $db;

		$this->sql = $sql;

		$this->offset = $offset;

		$this->limit = $limit;

	}

	/**
	 *	getResultset
	 *
	 *	Should contian logic to return a resultset.
	 *
	 *	@return array|object
	 */
	public abstract function getResultset();

}
?>