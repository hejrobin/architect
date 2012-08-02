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
 *	View
 *
 *	Simple PHP view class.
 *
 *	@package Renderers
 *	@subpackage Views
 *	@subpackage PHP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class View extends \Architect\Renderers\Views\ViewAbstract {

	/**
	 *	__construct
	 *
	 *	Register stream handler and set default include path.
	 *
	 *	@return void
	 */
	public function __construct() {

		$this->setStreamWrapper('phpview', '\Architect\Renderers\Views\PHP\Stream');

		$this->setIncludePath(ARCH_VIEWS_PATH);

	}

	/**
	 *	setVariables
	 *
	 *	Set view variables.
	 *
	 *	@param array $variables Associative array with variables to register.
	 *
	 *	@return void
	 */
	public function setVariables(array $variables) {

		// Iterate through each variables and set them
		foreach($variables as $variable => $data) {

			// Only set variable if key is string
			if(is_string($variable) === true) {

				$this->variables[$variable] = $data;

			}

		}

	}

	/**
	 *	Getter
	 *
	 *	@param string $variable Variable name.
	 *
	 *	@return mixed
	 */
	public function __get($variable) {

		return $this->variables[$variable];

	}

	/**
	 *	Setter
	 *
	 *	@param string $variable Variable name.
	 *	@param int|float|string $data Variable data.
	 *
	 *	@return void
	 */
	public function __set($variable, $data) {

		$this->variables[$variable] = $data;

	}

}
?>