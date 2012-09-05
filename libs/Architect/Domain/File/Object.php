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

/* @namespace File */
namespace Architect\Domain\File;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Object
 *
 *	Simple file object handler, does not extend {@man SPLFileInfo}.
 *
 *	@package Domain
 *	@subpackage File
 *
 *	@version 1.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Object {

	/**
	 *	@var resource $handle File object resource link.
	 */
	private $handle;

	/**
	 *	@var string $buffer File object data buffer.
	 */
	private $buffer;

	/**
	 *	@var string $path File object path.
	 */
	protected $path;

	/**
	 *	@var string $path_name File object path name.
	 */
	protected $path_name;

	/**
	 *	@var string $path File name.
	 */
	protected $name;

	/**
	 *	@var string $path File extension name.
	 */
	protected $extension;

	/**
	 *	@var bool $is_open Boolean telling whether a file handle exists or not.
	 */
	protected $is_open = false;

	/**
	 *	Constructor
	 *
	 *	Gathers information on file and path.
	 *
	 *	@param string $file_path File path.
	 *
	 *	@return void
	 */
	public function __construct($file_path) {

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Get file information
		$info = pathinfo($file_path);

		// Set file path
		$this->path = $file_path;

		// Set directory path name
		$this->path_name = $info['dirname'];

		// Set file name
		$this->name = $info['basename'];

		// Set file extension
		$this->extension = $info['extension'];

	}

	/**
	 *	getPath
	 *
	 *	Returns full file path.
	 *
	 *	@return string
	 */
	public function getPath() {

		return $this->path;

	}

	/**
	 *	getPathName
	 *
	 *	Returns file path without file name.
	 *
	 *	@return string
	 */
	public function getPathName() {

		return $this->path_name;

	}

	/**
	 *	getName
	 *
	 *	Returns file name.
	 *
	 *	@return string
	 */
	public function getName() {

		return $this->name;

	}

	/**
	 *	getExtension
	 *
	 *	Returns file extension.
	 *
	 *	@return string
	 */
	public function getExtension() {

		return $this->extension;

	}

	/**
	 *	getSize
	 *
	 *	Returns file size.
	 *
	 *	@return int
	 */
	public function getSize() {

		return filesize($this->path);

	}

	/**
	 *	isReadable
	 *
	 *	Checks whether file is readable or not.
	 *
	 *	@return bool
	 */
	public function isReadable() {

		return is_readable($this->path);

	}

	/**
	 *	isWritable
	 *
	 *	Checks whether file is writable or not.
	 *
	 *	@return bool
	 */
	public function isWritable() {

		return is_writable($this->path);

	}

	/**
	 *	exists
	 *
	 *	Checks whether file exists or not.
	 *
	 *	@return bool
	 */
	public function exists() {

		return file_exists($this->path);

	}

	/**
	 *	open
	 *
	 *	Opens a new file resource.
	 *
	 *	@param string $mode File open mode.
	 *
	 *	@throws Exceptions\FileException
	 *
	 *	@return void
	 */
	public function open($mode = 'r') {

		// Do nothing if file is already open
		if($this->is_open === true) {

			return;

		}

		// Open file
		$this->handle = @fopen($this->path, $mode);

		// Throw exception if file could not be open
		if($this->handle === false) {

			throw new Exceptions\FileException(
				"Could not open \"{$file_path}\".",
				"File may be invalid or corrupt.",
				__METHOD__, Exceptions\FileException::UNEXPECTED_RESULT_EXCEPTION
			);

		}

		// Tell object file is open
		$this->is_open = true;

	}

	/**
	 *	close
	 *
	 *	Closes current file handle.
	 *
	 *	@return void
	 */
	public function close() {

		if($this->is_open === true && is_resource($this->handle) === true) {

			// Close file resource
			@fclose($this->handle);

			// Reset handle
			$this->handle = null;

			// Tell object file is closed
			$this->is_open = false;

		}

	}

	/**
	 *	lock
	 *
	 *	Locks current file.
	 *
	 *	@param int $operation Should be either of LOCK_SH, LOCK_EX or LOCK_UN
	 *
	 *	@return bool
	 */
	public function lock($operation) {

		if($this->is_open === true && is_resource($this->handle) === true) {

			return flock($this->handle, $operation);

		}

		return false;

	}

	/**
	 *	touch
	 *
	 *	Sets access time of current file.
	 *
	 *	@return bool
	 */
	public function touch() {

		if($this->is_open === true && is_resource($this->handle) === true) {

			return touch($this->path);

		}

		return false;

	}

	/**
	 *	unlink
	 *
	 *	Closes file handle, removes registered file and resets all class properties.
	 *
	 *	@return void
	 */
	public function unlink() {

		if($this->is_open === true && is_resource($this->handle) === true) {

			$this->close();

		}

		// Unlink the file
		unlink($this->path);

		$this->name = null;

		$this->path = null;

		$this->path_name = null;

		$this->extension = null;

	}

	/**
	 *	buffer
	 *
	 *	Stores data into a buffer.
	 *
	 *	@param string $data Data to prepend to buffer.
	 *
	 *	@return void
	 */
	public function buffer($data) {

		// Initialize buffer
		if(is_string($this->buffer) === false) {

			$this->buffer = "";

		}

		// Store buffer data
		if(is_string($data) === true) {

			$this->buffer .= $data;

		}

	}

	/**
	 *	flushBuffer
	 *
	 *	Flushes data buffer.
	 *
	 *	@return void
	 */
	public function flushBuffer() {

		// Flush buffer
		$this->buffer = null;

	}

	/**
	 *	read
	 *
	 *	Reads file contents, either in full or by length.
	 *
	 *	@param int $bytes_length Bytes to read.
	 *
	 *	@return string
	 */
	public function read($bytes_length = null) {

		// File data
		$data = "";

		// Only read file data if handle exists
		if($this->is_open === true && is_resource($this->handle) === true) {

			if($this->isReadable() === false) {

				return $data;

			}

			// Get bytes length
			$bytes_length = (is_int($bytes_length) === true) ? $bytes_length : filesize($this->path);

			// Read file data
			$data = fread($this->handle, $bytes_length);

		}

		return $data;

	}

	/**
	 *	write
	 *
	 *	Writes data to file.
	 *
	 *	@param string $data File data to write.
	 *
	 *	@return bool
	 */
	public function write($data = null) {

		if($this->is_open === true && is_resource($this->handle) === true) {

			if($this->isWritable() === false) {

				return false;

			}

			$bytes_written = fwrite($this->handle, $data);

			if(is_int($bytes_written) === true) {

				return true;

			}

			return false;

		}

		return false;

	}

	/**
	 *	writeBuffer
	 *
	 *	Writes data currently in buffer.
	 *
	 *	@return bool
	 */
	public function writeBuffer() {

		return $this->write($this->buffer);

	}

	/**
	 *	seek
	 *
	 *	Sets file pointer to offset.
	 *
	 *	@param int $offset Pointer offset.
	 *	@param int $whence Seek whence property.
	 *
	 *	@return void
	 */
	public function seek($offset = 0, $whence = null) {

		// Only seek if file handle exists
		if($this->is_open === true && is_resource($this->handle) === true) {

			// Set $whence default value
			if(is_null($whence) === true) {

				$whence = SEEK_SET;

			}

			// Set file pointer
			fseek($this->handle, $offset, $whence);

		}

	}

	/**
	 *	truncate
	 *
	 *	Truncate file to bytes length.
	 *
	 *	@param int $bytes_length File bytes length.
	 *
	 *	@return bool
	 */
	public function truncate($bytes_length) {

		// Only truncate if file handle exists
		if($this->is_open === true && is_resource($this->handle) === true) {

			if(is_int($bytes_length) === true) {

				return ftruncate($this->handle, $bytes_length);

			}

			return false;

		}

		return false;

	}

}
?>