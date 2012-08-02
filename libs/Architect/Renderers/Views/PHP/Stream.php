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

/* @namespace PHP */
namespace Architect\Renderers\Views\PHP;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Stream
 *
 *	Stream wrapper for PHP views.
 *
 *	@package Renderers
 *	@subpackage Views
 *	@subpackage PHP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Stream extends \Architect\Renderers\Views\StreamAbstract {

	/**
	 *	open
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
	public function stream_open($path, $mode, $options, $opened_path) {

		// Remove stream protocol from path
		$path = str_ireplace('phpview://', '', $path);

		// Get contents of view file
		$this->data = file_get_contents($path);

		// Set stat
		$this->stat = stat($path);

		// Return false if data cold not be set
		if($this->data === false) {

			return false;

		}

		// Normalize short open tags
		if(ini_get('short_open_tag') === false) {

			$this->data = str_replace(array('<?=', '<?'), array('<?php echo', '<?php '), $this->data);

		}

		// Fix $this keyword
		$this->data = str_replace(array('@$', '$view->'), '$this->', $this->data);

		// Return true
		return true;

	}

}
?>