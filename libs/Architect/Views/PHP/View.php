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

/* @namespace PHP */
namespace Architect\Views\PHP;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	View
 *
 *	View class.
 *
 *	@package Views
 *	@subpackage PHP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class View extends \Architect\Views\ViewAbstract {

	/**
	 *	@var array $variables View variables.
	 */
	protected $variables = array();

	/**
	 *	__construct
	 *
	 *	Register stream handler and set default include path.
	 *
	 *	@return void
	 */
	public function __construct() {

		$this->setStreamWrapper('php-view', '\Architect\Views\PHP\Stream');

		$this->setIncludePath(ARCH_VIEWS_PATH);

	}

	/**
	 *	setVariables
	 *
	 *	@param array $variables Associative array with variables to register.
	 *
	 *	@return void
	 */
	public function setVariables($variables) {

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