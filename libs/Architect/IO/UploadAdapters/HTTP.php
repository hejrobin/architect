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
 *	HTTP
 *
 *	HTTP file upload adapter.
 *
 *	@package IO
 *	@subpackage UploadAdapters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class HTTP extends Adapter {

	/**
	 *	uploadFiles
	 *
	 *	Moves uploaded files into uploads folder.
	 *
	 *	@param array $files Files array.
	 *	@param string $upload_path Upload path.
	 *
	 *	@return int
	 */
	public function uploadFiles(array $files, $upload_path) {

		$num_uploaded_files = 0;

		foreach($files as $batch => $files) {

			foreach($files as $key => $file) {

				$file = (object) $file;

				if(move_uploaded_file($file->tmp_name, $upload_path . $file->name) !== true) {

					throw new Exceptions\AdapterException(
						"Could not upload files.",
						"Could not move uploaded files.",
						__METHOD__, Exceptions\AdapterException::DOMAIN_EXCEPTION
					);

				}

				$num_uploaded_files++;

			}

		}

		return $num_uploaded_files;

	}

}
?>