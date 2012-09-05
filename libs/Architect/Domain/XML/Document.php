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

/* @namespace XML */
namespace Architect\Domain\XML;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Document
 *
 *	Class to create an XML documents, extends {@man DOMDocument}.
 *
 *	@package Domain
 *	@subpackage XML
 *
 *	@dependencies \Architect\Domain\File\Object
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Document extends \DOMDocument {

	/**
	 *	@var string $version XML version, usually '1.0'.
	 */
	protected $version = '1.0';

	/**
	 *	@var string $encoding Document encoding.
	 */
	protected $encoding = 'UTF-8';

	/**
	 *	@var \Architect\Domain\File\Object $file Instance of {@see \Architect\Domain\File\Object}.
	 */
	protected $file;

	/**
	 *	Constructor
	 *
	 *	Creates instance of parent class (DOMDocument) and validates file name.
	 *
	 *	@param \Architect\Domain\File\Object $file Instance of {@see \Architect\Domain\File\Object}.
	 *	@param string $version XML version.
	 *	@param string $encoding Document encoding.
	 *
	 *	@throws Exceptions\DocumentException
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Domain\File\Object $file, $version = '1.0', $encoding = 'UTF-8') {

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Set file object
		$this->file = $file;

		// Validate file extension, must be '.xml'
		if($this->file->getExtension() !== 'xml') {

			throw new Exceptions\DocumentException(
				'Could not create instance of "' . __CLASS__ . '".',
				'Input file for ' . get_class($this->file) . ' is not a valid XML file.',
				__METHOD__, Exceptions\DocumentException::DOMAIN_EXCEPTION
			);

		}

		// Invoke parent constructor
		parent::__construct($version, $encoding);

		// Load input file, if it exists
		if($this->file->exists() === true) {

			$this->load($this->file->getPath());

		}

	}

}
?>