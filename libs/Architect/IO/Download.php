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

/* @namespace I/O */
namespace Architect\IO;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Download
 *
 *	Handles file forced download.
 *
 *	@package I/O
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Download {

	/**
	 *	@var \Architect\Domain\File\Object $file Instance of {@see \Architect\Domain\File\Object}.
	 */
	protected $file;

	/**
	 *	Constructor
	 *
	 *	Validates file for download.
	 *
	 *	@param \Architect\Domain\File\Object $file Instance of {@see \Architect\Domain\File\Object}.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Domain\File\Object $file) {

		// Set file object
		$this->file = $file;

		// Throw exception if file does not exist
		if($this->file->exists() === false) {

			throw new Exceptions\IOException(
				"Could not create file download.",
				"Input file for this resource does not exist.",
				__METHOD__, Exceptions\IOException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Open file
		$this->file->open();

		// Get Architect
		$arch = \Architect::getInstance();

		// Set required headers
		$arch->http->setHeaders(array(
			'Content-Description' => 'File Transfer',
			'Content-Type' => 'application/force-download',
			'Content-Disposition' => 'attachment; filename=' . $this->file->getName() .'',
			'Content-Length' => $this->file->getSize()
		));

	}

	/**
	 *	send
	 *
	 *	Attempts to send the file to the user via HTTP headers.
	 *
	 *	@return void
	 */
	public function send() {

		// Get Architect
		$arch = \Architect::getInstance();

		// Send headers
		$arch->http->sendHeaders();


		// Output data
		echo $this->file->read();

	}

}
?>