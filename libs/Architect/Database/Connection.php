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
	 *	@var array $database_drivers Array containing DSN pattern strings for different database types.
	 */
	protected $database_drivers = array(
		'dblib' => 'host=%s:%d;dbname=%s',
		'firebird' => 'DataSource=%s;Port=%d;Database=%s;',
		'mysql' => 'host=%s;port=%d;dbname=%s;',
		'pgsql' => 'host=%s port=%d dbname=%s'
	);

	/**
	 *	@var array $database_driver_options Additional database driver options.
	 */
	protected $database_driver_options = array();

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

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Throw exception if database driver does not exist
		if(array_key_exists(strtolower($driver), $this->database_drivers) === false) {

			throw new Exceptions\DatabaseException(
				'Could not establish database connection.',
				"Database driver \"{$driver}\" does not exist.",
				__METHOD__, Exceptions\DatabaseException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Throw exception if driver 'dblib' does not have a port specified
		if(strtolower($driver) === 'dblib' && is_int($port) === false) {

			throw new Exceptions\DatabaseException(
				'Could not establish database connection.',
				"Database driver \"{$driver}\" requires that a port is specified.",
				__METHOD__, Exceptions\DatabaseException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set database source name
		$this->setDatabaseSourceName(strtolower($driver), $database, $host, $port);

	}

	/**
	 *	setDataSourceName
	 *
	 *	Sets database source name based on database driver option.
	 *
	 *	@param string $driver Database driver name.
	 *	@param string $database Database name.
	 *	@param string $host Database host name.
	 *	@param int $port Database port.
	 *
	 *	@return void
	 */
	private function setDatabaseSourceName($driver, $database, $host = 'localhost', $port = false) {

		// Get DSN string
		$dsn = $this->database_drivers[$driver];

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
	 *	setDatabaseDriverOptions
	 *
	 *	Sets database driver specific options.
	 *
	 *	@param array $database_driver_options Array containing key value pairs of driver specific options.
	 *
	 *	@return void
	 */
	public function setDatabaseDriverOptions(array $database_driver_options) {

		$this->database_driver_options = $database_driver_options;

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

		// Invoke class parent
		parent::__construct($this->dsn, $username, $password, $this->database_driver_options);

		\Rae\Console::log("Established a new database connection.", __METHOD__, __FILE__, __LINE__);

	}

}
?>