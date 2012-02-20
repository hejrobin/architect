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
namespace Architect\Domain\File;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	File
 *
 *	Extends native SPL {@man SplFileObject}.
 *
 *	@package Domain
 *	@subpackage File
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class File extends \SplFileObject {

	/**
	 *	getFileExtension
	 *
	 *	Returns file extension of current registered file.
	 *
	 *	@return stirng
	 */
	public function getFileExtension() {
	
		return strtolower(pathinfo($this->getFilename(), PATHINFO_EXTENSION));
	
	}

}
?>