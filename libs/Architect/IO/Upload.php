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
 *	Upload
 *
 *	Handles files array for uploading, uploads normalized and validated files via input adapter.
 *
 *	@package I/O
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Upload {

	/**
	 *	@var array $files Array of files.
	 */
	protected $files;

	/**
	 *	@var string $uploads_path Uploads path.
	 */
	protected $uploads_path;

	/**
	 *	@var int $file_max_size File max size, in bytes.
	 */
	protected $file_max_size = 1048576;

	/**
	 *	@var array $file_extension_restrictions Array containging valid file extensions.
	 */
	protected $file_extension_restrictions = array();

	/**
	 *	@var UploadAdapters\Adapter $adapter Upload adapter.
	 */
	protected $adapter;

	/**
	 *	Constructor
	 *
	 *	Sorts files array and sets upload adapter.
	 *
	 *	@param array $files Array containing files, should be $_FILES.
	 *	@param UploadAdapters\Adapter $adapter Upload adapter.
	 *
	 *	@return void
	 */
	public function __construct(array $files, UploadAdapters\Adapter $adapter) {

		if(count($_files) === 0) {

			throw new Exceptions\UploadException(
				"Could not initialize file upload.",
				"There were no files to upload.",
				__METHOD__, Exceptions\UploadException::DOMAIN_EXCEPTION
			);

		}

		// Sort files array
		$this->sortFiles($files);

		// Set default uploads path
		$this->setUploadsPath(ARCH_UPLOADS_PATH);

		// Set adapter
		$this->adapter = $adapter;

	}

	/**
	 *	setUploadsPath
	 *
	 *	Sets file uploads path.
	 *
	 *	@param string $uploads_path Uploads path.
	 *
	 *	@return void
	 */
	public function setUploadsPath($uploads_path) {

		$this->uploads_path = $uploads_path;

	}

	/**
	 *	setFileMaxSize
	 *
	 *	Set file upload max size.
	 *
	 *	@param int $file_max_size File max size, in bytes.
	 *
	 *	@return void
	 */
	public function setFileMaxSize($file_max_size) {

		$this->file_max_size = $file_max_size;

	}

	/**
	 *	setExtensionRestrictions
	 *
	 *	Set file extension restrictions.
	 *
	 *	@param array $file_extension_restrictions Array containging valid file extensions.
	 *
	 *	@return void
	 */
	public function setExtensionRestrictions(array $file_extension_restrictions) {

		$this->file_extension_restrictions = $file_extension_restrictions;

	}

	/**
	 *	sortFiles
	 *
	 *	Sorts the files array.
	 *
	 *	@return void
	 */
	protected function sortFiles($_files) {

		foreach($_files as $batch => $files) {

			$this->files[$batch] = array();

			foreach($files as $key => $data) {

				if(is_array($data) === true) {

					foreach($data as $index => $file) {

						if(isset($this->files[$batch][$index]) === true && is_array($this->files[$batch][$index]) === false) {

							$this->files[$batch][$index] = array();

						}

						$this->files[$batch][$index][$key] = $file;

					}

				} else {

					$this->files[$batch] = array();

					$this->files[$batch][] = $files;

				}

			}

		}

	}

	/**
	 *	validateFiles
	 *
	 *	Validates files array.
	 *
	 *	@return bool
	 */
	public function validateFiles() {

		foreach($this->files as $batch => $files) {

			foreach($files as $key => $file) {

				$file = (object) $file;

				if($file->error > 0) {

					switch($file->error) {

						case UPLOAD_ERR_INI_SIZE :
						case UPLOAD_ERR_FORM_SIZE :

							throw new Exceptions\UploadException(
								"Could not initialize file upload.",
								"Input file extension is too big, allowed size is " . $this->file_max_size . " bytes.",
								__METHOD__, Exceptions\UploadException::DOMAIN_EXCEPTION
							);

						break;

					}

					unset($this->files[$batch][$key]);

					return false;

				}

				if(count($this->file_extension_restrictions) > 0) {

					if(in_array(strtolower(pathinfo($file->name, PATHINFO_EXTENSION)), $this->file_extension_restrictions) === false) {

						throw new Exceptions\UploadException(
							"Could not initialize file upload.",
							"Input file extension is invalid.",
							__METHOD__, Exceptions\UploadException::DOMAIN_EXCEPTION
						);

						return false;

					}

				}

			}

		}

		return true;

	}

	/**
	 *	upload
	 *
	 *	Uploads files via adapter.
	 *
	 *	@return int
	 */
	public function upload() {

		$uploaded_files = $this->validateFiles();

		$num_uploaded_files = $this->adapter->uploadFiles($this->files, $this->uploads_path);

		return $num_uploaded_files;

	}

}
?>