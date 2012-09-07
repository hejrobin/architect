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
 *	Image
 *
 *	Image handler class.
 *
 *	@package Domain
 *	@subpackage File
 *
 *	@version 1.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Image {

	/**
	 *	@var \Architect\Domain\File\Object $file File object.
	 */
	public $file;

	/**
	 *	@var int $width Image width.
	 */
	protected $width;

	/**
	 *	@var int $height Image height.
	 */
	protected $height;

	/**
	 *	@var string $type Image type.
	 */
	protected $type;

	/**
	 *	@var int $quality Image quality.
	 */
	protected $quality = 75;

	/**
	 *	@var resource $resource Image resource.
	 */
	protected $resource;

	/**
	 *	@var array $color_allocations Array containing allocated image color references.
	 */
	protected $color_allocations = array();

	/**
	 *	@var array $allowed_image_types Allowed image types.
	 */
	protected $allowed_image_types = array('png', 'jpg', 'jpeg', 'gif');

	/**
	 *	Constructor
	 *
	 *	Registers input file object.
	 *
	 *	@param \Architect\Domain\File\Object $file File object.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Domain\File\Object $file, $width = null, $height = null) {

		$this->file = $file;

		$this->setup();

		if(is_int($width) === true && is_int($height) === true) {

			$this->setWidth($width);

			$this->setHeight($height);

		}

	}

	/**
	 *	setup
	 *
	 *	Gathers information from input image.
	 *
	 *	@return void
	 */
	protected function setup() {

		if($this->file->exists() === true) {

			list($width, $height) = getimagesize($this->file->getPath());

			if(in_array($this->file->getExtension(), $this->allowed_image_types) === false) {

				throw new Exceptions\ImageException(
					'Could not create a new instance of ' . __CLASS__ . '.',
					"Image type not allowed, must be either of: " . implode(', ', $this->allowed_image_types) . ".",
					__METHOD__, Exceptions\ImageException::DOMAIN_EXCEPTION
				);

			}

			$this->setWidth($width);

			$this->setHeight($width);

		}

		$this->type = $this->file->getExtension();

	}

	/**
	 *	setWidth
	 *
	 *	Sets image width.
	 *
	 *	@param int $width Image width.
	 *
	 *	@return void
	 */
	public function setWidth($width = null) {

		if(is_int($width)) {

			$this->width = $width;

		}

	}

	/**
	 *	getWidth
	 *
	 *	Returns image width.
	 *
	 *	@return int
	 */
	public function getWidth() {

		return $this->width;

	}

	/**
	 *	setHeight
	 *
	 *	Sets image height.
	 *
	 *	@param int $height Image height.
	 *
	 *	@return void
	 */
	public function setHeight($height = null) {

		if(is_int($height)) {

			$this->height = $height;

		}

	}

	/**
	 *	getHeight
	 *
	 *	Returns image height.
	 *
	 *	@return int
	 */
	public function getHeight() {

		return $this->height;

	}

	/**
	 *	setQuality
	 *
	 *	Sets image quality.
	 *
	 *	@param int $quality Image quality.
	 *
	 *	@return void
	 */
	public function setQuality($quality = null) {

		if(is_int($quality)) {

			$this->quality = $quality;

		}

	}

	/**
	 *	getQuality
	 *
	 *	Returns image quality.
	 *
	 *	@return int
	 */
	public function getQuality() {

		return $this->quality;

	}

	/**
	 *	getResource
	 *
	 *	Returns image resource.
	 *
	 *	@return resource|null
	 */
	public function getResource() {

		if(isset($this->resource) === true) {

			return $this->resource;

		}

		return null;

	}

	/**
	 *	create
	 *
	 *	Creates image resource.
	 *
	 *	@param bool $ignore_existing_file If set to true, creates a new empty resource.
	 *
	 *	@return void
	 */
	public function create($ignore_existing_file = false) {

		if($this->file->exists() === true && $ignore_existing_file === false) {

			switch($this->type) {

				case 'png' :

					$this->resource = imagecreatefrompng($this->file->getPath());

				break;
				case 'jpg' :
				case 'jpeg' :

					$this->resource = imagecreatefromjpeg($this->file->getPath());

				break;
				case 'gif' :

					$this->resource = imagecreatefromgif($this->file->getPath());

				break;

			}

		} else {

			$this->resource = imagecreatetruecolor($this->getWidth(), $this->getHeight());

		}

	}

	/**
	 *	Call mutator
	 *
	 *	Magic method used to access functions from GD.
	 *
	 *	@param string $function Function name, without "image" prepended.
	 *	@param array $arguments Array containing
	 *
	 *	@return mixed
	 */
	public function __call($function, $arguments = array()) {

		$function = strtolower("image{$function}");

		if(function_exists($function) === true) {

			return call_user_func_array($function, $arguments);

		}

		return false;

	}

	/**
	 *	createColor
	 *
	 *	Creates and allocates memory for a color reference, returns existing references if already allocated.
	 *
	 *	@param int $r Red.
	 *	@param int $g Green.
	 *	@param int $b Blue.
	 *	@param int $a Alpha channel, 1 to 100.
	 *
	 *	@return resource|bool
	 */
	public function createColor($r, $g, $b, $a = null) {

		$color = false;

		if(is_null($a) === false && is_int($a) === true) {

			$key = "{$r},{$g},{$b},{$a}";

			$alpha = ceil((127 / 100) * $a);

			$color = imagecolorallocatealpha($this->resource, $r, $g, $b, $alpha);

		} else {

			$key = "{$r},{$g},{$b}";

			$color = imagecolorallocate($this->resource, $r, $g, $b);

		}

		if($color !== false) {

			if(array_key_exists($key, $this->color_allocations) === true) {

				return $this->color_allocations[$key];

			}

			$this->color_allocations[$key] = $color;

			return $color;

		}

		return false;

	}

	/**
	 *	resize
	 *
	 *	Resizes current image, and returns a new {@see Image} object.
	 *
	 *	@param int $width New image width.
	 *	@param int $height New image height.
	 *
	 *	@return object
	 */
	public function resize($width, $height) {

		if(is_null($this->resource) === true) {

			$this->create();

		}

		$resource = $this->getResource();

		$file = new \Architect\Domain\File\Object($this->file->getPath());

		$resized = new Image($file, $width, $height);
		$resized->create(true);

		imagecopyresampled($resized->getResource(), $resource, 0, 0, 0, 0, $width, $height, imagesx($resource), imagesy($resource));

		return $resized;

	}

	/**
	 *	crop
	 *
	 *	Crops current image, and returns a new {@see Image} object.
	 *
	 *	@param int $width Image crop width.
	 *	@param int $height Image crop height.
	 *	@param int $x Image crop horizontal offset.
	 *	@param int $y Image crop vertical offset.
	 *
	 *	@return void
	 */
	public function crop($width, $height, $x = 0, $y = 0) {

		if(is_null($this->resource) === true) {

			$this->create();

		}

		$file = new \Architect\Domain\File\Object($this->file->getPath());

		$cropped = new Image($file, $width, $height);
		$cropped->create(true);

		$cropped->setWidth($width);
		$cropped->setHeight($height);

		$is_success = imagecopy($cropped->getResource(), $this->getResource(), 0, 0, $x, $y, $width, $height);

		return $cropped;

	}

	/**
	 *	output
	 *
	 *	Outputs raw image data.
	 *
	 *	@return string|null
	 */
	public function output($image_save_path = null) {

		if(is_string($image_save_path) === true && is_dir(dirname($image_save_path)) === false) {

			throw new Exceptions\ImageException(
				'Could not save image output.',
				'Image save path does is not a valid directory.',
				__METHOD__, Exceptions\ImageException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		switch($this->type) {

			case 'png' :

				$quality = ceil($this->getQuality() / 10);

				if($quality > 9) {

					$quality = 9;

				}

				return imagepng($this->resource, $image_save_path, $quality);

			break;
			case 'jpg' :
			case 'jpeg' :

				return imagejpeg($this->resource, $image_save_path, $this->getQuality());

			break;
			case 'gif' :

				return imagegif($this->resource, $image_save_path);

			break;

		}

		return null;

	}

	/**
	 *	save
	 *
	 *	Saves image to image path, returns true on success, and false on failure.
	 *
	 *	@return bool
	 */
	public function save($image_save_path = null) {

		if(is_null($image_save_path)) {

			$image_save_path = $this->image->getPath();

		}

		$data = $this->output($image_save_path);

		if(is_null($data) === false) {

			return true;

		}

		return false;

	}

	/**
	 *	flush
	 *
	 *	Flushes memory and allocations used by image.
	 *
	 *	@return void
	 */
	public function flush() {

		foreach($this->color_allocations as $color_allocation) {

			imagecolordeallocate($this->resource, $color_allocation);

		}

		if(is_null($this->resource) === false) {

			imagedestroy($this->resource);

		}

	}

	/**
	 *	Destructor
	 *
	 *	Calls {@see Image::flush}.
	 *
	 *	@return void
	 */
	public function __destruct() {

		$this->flush();

	}

}
?>