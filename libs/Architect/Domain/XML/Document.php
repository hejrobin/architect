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

/* @namespace File */
namespace Architect\Domain\XML;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Document
 *
 *	Class to create an XML document, extends {@man DOMDocument}.
 *
 *	@package Domain
 *	@subpackage XML
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
	 *	@var \Architect\Domain\File\File $file Instance of \Architect\Domain\File\File.
	 */
	protected $file;

	/**
	 *	Constructor
	 *
	 *	Creates instance of parent class (DOMDocument) and validates file name.
	 *
	 *	@param \Architect\Domain\File\File $file Instance of \Architect\Domain\File\File.
	 *	@param string $version XML version.
	 *	@param string $encoding Document encoding.
	 *
	 *	@throws Exceptions\DocumentException
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Domain\File\File $file, $version = '1.0', $encoding = 'UTF-8') {
	
		// Set file object
		$this->file = $file;
		
		// Validate file extension, must be '.xml'
		if($this->file->getFileExtension() !== 'xml') {
		
			throw new Exceptions\DocumentException(
				'Could not create instance of ' . __CLASS__ . '.',
				'Input file for ' . get_class($this->file) . ' is not a valid XML file.',
				__METHOD__, Exceptions\ParserException::DOMAIN_EXCEPTION 
			);
		
		}
		
		// Invoke parent constructor
		parent::__construct($version, $encoding);
		
		// Load input file, if exists
		if(file_exists($this->file->getFilename())) {
		
			$this->load($this->file->getFilename());
			
			\Jarvis\Console::log("Loaded '" . $this->file->getFilename() . "'.", 'XML\\Document', __FILE__, __LINE__);
		
		}
	
	}

}
?>