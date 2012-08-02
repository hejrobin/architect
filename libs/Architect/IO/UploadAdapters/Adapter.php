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

/* @namespace UploadAdapters */
namespace Architect\IO\UploadAdapters;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Adapter
 *
 *	Handles files array for uploading, uploads normalized and validated files via input adapter.
 *
 *	@package Domain
 *	@subpackage File
 *	@subpackage UploadAdapters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class Adapter {

	/**
	 *	uploadFiles
	 *
	 *	Should contain file upload logic.
	 *
	 *	@param array $files Files array.
	 *	@param string $upload_path Upload path.
	 *
	 *	@return void
	 */
	public abstract function uploadFiles(array $files, $upload_path);

}
?>