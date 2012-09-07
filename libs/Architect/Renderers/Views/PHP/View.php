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
 *	@dependencies \Architect\Renderers\Views\Renderer, \Architect\Renderers\Views\PHP\Stream, \Architect\Renderers\Views\PHP\Renderer, \Architect\Renderers\Views\CachedRenderer
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class View extends \Architect\Renderers\Views\ViewAbstract {

	/**
	 *	@var bool $is_cached_view If set to true, this class uses {@see \Architect\Renderers\Views\CachedRenderer} instead of {@see \Architect\Renderers\Views\Renderer}.
	 */
	protected $is_cached_view = false;

	/**
	 *	@var int $lifetime Cache lifetime, only applies if {@see View::$is_cached_view} is set to true.
	 */
	protected $lifetime;

	/**
	 *	__construct
	 *
	 *	Register stream handler and set default include path, and creates a renderer for this view.
	 *
	 *	@return void
	 */
	public function __construct($is_cached_view = false) {

		$this->setStreamWrapper('phpview', '\Architect\Renderers\Views\PHP\Stream');

		$this->setIncludePath(ARCH_VIEWS_PATH);

		$this->is_cached_view = (is_bool($is_cached_view)) ? $is_cached_view : false;

		$this->setLifetime();

	}

	/**
	 *	setLifetime
	 *
	 *	Sets cache lifetime for this view.
	 *
	 *	@param int $lifetime Cache lifetime.
	 *
	 *	@return void
	 */
	public function setLifetime($lifetime = null) {

		if(is_int($lifetime) === false || is_null($lifetime) === true) {

			$this->lifetime = 3600 * 24 * 30;

		}

		$this->lifetime = $lifetime;

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
	 *	getVariables
	 *
	 *	Returns registered view variables.
	 *
	 *	@return array
	 */
	public function getVariables() {

		return $this->variables;

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

	/**
	 *	render
	 *
	 *	Renders a PHP based view.
	 *
	 *	@throws Exceptions\ViewException
	 *
	 *	@return string
	 */
	public function render() {

		// Throw exception if view tries to render itself within a loop
		if($this->view_file === $this->previous_view_file) {

			throw new Exceptions\ViewException(
				"Could not render view file.",
				"File \"{$this->view_file}\" is already rendered.",
				__METHOD__, Exceptions\ViewException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set previous view file
		$this->previous_view_file = $this->view_file;

		if($this->is_cached_view === true) {

			$renderer = new \Architect\Renderers\Views\PHP\CachedRenderer($this, $this->view_file);

		} else {

			$renderer = new \Architect\Renderers\Views\PHP\Renderer($this, $this->view_file);

		}

		$rendered_view = $renderer->render();

		// Return rendered view
		return $rendered_view;

	}

}
?>