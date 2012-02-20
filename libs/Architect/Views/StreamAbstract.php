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

/* @namespace Views */
namespace Architect\Views;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	StreamAbstract
 *
 *	Abstract class to create view stream handlers.
 *
 *	@package Views
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class StreamAbstract {

	/**
	 *	@var int $pos Stream position.
	 */
	protected $pos = 0;
	
	/**
	 *	@var string $data Stream data.
	 */
	protected $data;
	
	/**
	 *	@var mixed $stat Stream stat.
	 */
	protected $stat;

	/**
	 *	stream_open
	 *
	 *	Called when a stream is opened.
	 *
	 *	@param string $path Stream path.
	 *	@param string $mode Stream mode.
	 *	@param string $options Stream options.
	 *	@param string $opened_path Stream opened path.
	 *
	 *	@return bool
	 */
	abstract public function stream_open($path, $mode, $options, $opened_path);

	/**
	 *	stream_seek
	 *
	 *	Seeks in stream.
	 *
	 *	@param string $offset Stream offset.
	 *	@param string $mode Stream mode.
	 *
	 *	@return bool
	 */
	public function stream_seek($offset, $mode) {

		switch($mode) {
	
			case SEEK_SET :
	
				if($offset < strlen($this->data) && $offset >= 0) {
				
					$this->pos = $offset;
				
				} else {
				
					return false;
				
				}

				return true;

			break;

			case SEEK_CUR :
	
				if($offset >= 0) {
				
					$this->pos += $offset;
				
				} else {
				
					return false;
				
				}

				return true;

			break;

			case SEEK_END :

				if(strlen($this->data) + $offset >= 0) {
				
					$this->pos = strlen($this->data) + $offset;
				
				} else {
				
					return false;
				
				}

				return true;

			break;
	
			default :
				return false;

		}

	}

	/**
	 *	stream_read
	 *
	 *	Reads stream.
	 *
	 *	@param int $count
	 *
	 *	@return string
	 */
	public function stream_read($count) {
	
		$return = substr($this->data, $this->pos, $count);

		$this->pos += strlen($return);

		return $return;

	}
	
	/**
	 *	tell
	 *
	 *	Returns current stream position.
	 *
	 *	@return int
	 */
	public function stream_tell() {

		return $this->pos;

	}

	/**
	 *	stream_stat
	 *
	 *	Returns stat.
	 *
	 *	@return array
	 */
	public function stream_stat() {

		return $this->stat;

	}

	/**
	 *	url_stat
	 *
	 *	Returns stat.
	 *
	 *	@return array
	 */
	public function url_stat() {

		return $this->stat;

	}

	/**
	 *	stream_eof
	 *
	 *	Returns boolean whether stream has reach end of file or not.
	 *
	 *	@return bool
	 */
	public function stream_eof() {

		return $this->pos >= strlen($this->data);

	}

}
?>