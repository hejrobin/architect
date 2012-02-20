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
class Connection extends \PDO {

	/**
	 *	@var string $dsn Database source name.
	 */
	private $dsn;

	/**
	 *	@var array $drivers Array containging DSN strings for varius databases
	 */
	protected $drivers = array(
	
		'pgsql' => 'host=%s port=%d dbname=%s',

		'mysql' => 'host=%s;port=%d;dbname=%s;',
		
		'firebird' => 'DataSource=%s;Port=%d;Database=%s;',
		
		'dblib' => 'host=%s:%d;dbname=%s;'
	
	);

	/**
	 *	Constructor
	 *
	 *	Validates database driver and sets DSN.
	 *
	 *	@param string $driver Database driver.
	 *	@param string $database Database name.
	 *	@param string $host Database host name.
	 *	@param int $port Database port.
	 *
	 *	@throws Exceptions\DatabaseException
	 *
	 *	@return void
	 */
	public function __construct($driver, $database, $host = 'localhost', $port = false) {
		
		// Throw exception if database driver does not exist
		if(array_key_exists(strtolower($driver), $this->drivers) === false) {
		
			throw new Exceptions\DatabaseException(
				'Could not establish database connection.',
				"Database driver '{$driver}' does not exist.",
				__METHOD__, Exceptions\DatabaseException::INVALID_ARGUMENT_EXCEPTION
			);
		
		}
		
		// Throw exception if driver 'dblib' does not have a port specified
		if(strtolower($driver) === 'dblib' && is_int($port) === false) {
		
			throw new Exceptions\DatabaseException(
				'Could not establish database connection.',
				"Database driver '{$driver}' requires that a port is specified.",
				__METHOD__, Exceptions\DatabaseException::INVALID_ARGUMENT_EXCEPTION
			);
		
		}
		
		// Set database source name
		$this->setDatabaseSourceName(strtolower($driver), $database, $host, $port);
	
	}

	/**
	 *	setDatabaseSourceName
	 *
	 *	Sets database source name.
	 *
	 *	@param string $driver Database driver.
	 *	@param string $database Database name.
	 *	@param string $host Database host name.
	 *	@param int $port Database port.
	 *
	 *	@return void
	 */
	private function setDatabaseSourceName($driver, $database, $host = 'localhost', $port = false) {
		
		// Get DSN string
		$dsn = $this->drivers[$driver];
		
		// Create DSN without port
		if($port === false) {
		
			$dsn = preg_replace('/(port=%d[;| ])/i', '', $dsn);
			
			$this->dsn = "{$driver}:" . sprintf($dsn, $host, $database);
			
		} else {
		
			// Create DSN with port
			$this->dsn = "{$driver}:" . sprintf($dsn, $host, $port, $database);
		
		}
	
	}
	
	/**
	 *	connect
	 *
	 *	Connects to specified database.
	 *
	 *	@param string $username Database username.
	 *	@param string $password Database password.
	 *
	 *	@return void 
	 */
	public function connect($username, $password) {
	
		parent::__construct($this->dsn, $username, $password);
	
		\Jarvis\Console::log('Established a new database connection.', 'Database Connection', __FILE__, __LINE__);
	
	}

}
?>