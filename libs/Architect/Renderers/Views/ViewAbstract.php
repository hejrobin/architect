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

/* @namespace Views */
namespace Architect\Renderers\Views;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	ViewAbstract
 *
 *	Abstract class to create view handlers.
 *
 *	@package Renderers
 *	@subpackage Views
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class ViewAbstract {

	/**
	 *	@var string $include_path Include path.
	 */
	protected $include_path;

	/**
	 *	@var string $protocol View stream protocol.
	 */
	protected $protocol;

	/**
	 *	@var string $view_file View file path.
	 */
	protected $view_file;

	/**
	 *	@var string $previous_view_file Previous view file path.
	 */
	protected $previous_view_file;

	/**
	 *	@var array $variables View variables.
	 */
	protected $variables = array();

	/**
	 *	Constructor
	 *
	 *	Must be implemented by subclasses.
	 *
	 *	@return void
	 */
	abstract public function __construct();

	/**
	 *	setIncludePath
	 *
	 *	This method sets the include path
	 *
	 *	@param string $include_path
	 */
	public function setIncludePath($include_path) {

		$this->include_path = $include_path;

	}

	/**
	 *	setStreamWrapper
	 *
	 *	Registers a stream wrapper class to defined protocol.
	 *
	 *	@param string $protocol Protocol name.
	 *	@param object $stream_handler Stream wrapper class.
	 *
	 *	@throws Exceptions\ViewException
	 *
	 *	@return void
	 */
	public function setStreamWrapper($protocol, $stream_handler) {

		// Set stream protocol
		$this->protocol = $protocol;

		// Only continue if stream is not already registered
		if(in_array($protocol, stream_get_wrappers()) === false) {

			// Throw exception if stream wrapper could not be set
			if(stream_wrapper_register($protocol, $stream_handler) === false) {

				throw new Exceptions\ViewException(
					"Could not register stream wrapper \"" . get_class($stream_handler) . "\" to protocol \"{$protocol}\".",
					"Could not register stream wrapper \"" . get_class($stream_handler) . "\" to protocol \"{$protocol}\".",
					__METHOD__, Exceptions\ViewException::INVALID_ARGUMENT_EXCEPTION
				);

			}

		}

	}

	/**
	 *	setViewFile
	 *
	 *	Sets view file.
	 *
	 *	@param string $view_file View file name.
	 *
	 *	@throws Exceptions\ViewException
	 *
	 *	@return void
	 */
	public function setViewFile($view_file) {

		// Throw exception if view file does not exist or may be corrupt
		if(file_exists($this->include_path . $view_file) === false || is_readable($this->include_path . $view_file) === false) {

			throw new Exceptions\ViewException(
				"Could not set view file.",
				"File \"{$view_file}\" does not exist or may be corrupt.",
				__METHOD__, Exceptions\ViewException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Set view file
		$this->view_file = $view_file;

	}

	/**
	 *	import
	 *
	 *	Imports view file.
	 *
	 *	@param string $view_file View file name.
	 *
	 *	@return void
	 */
	public function import($view_file) {

		require_once $this->protocol . '://' . $this->include_path . $view_file;

	}

	/**
	 *	render
	 *
	 *	Renders output from view file.
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

		// Capture output buffer
		@ob_start();

		// Import view file
		$this->import($this->view_file);

		// Get and clear buffer
		$rendered_view = ob_get_clean();

		// Return rendered view
		return $rendered_view;

	}

	/**
	 *	String Mutator
	 *
	 *	Returns rendered view.
	 *
	 *	@return string
	 */
	public function __tostring() {

		return $this->render();

	}

}
?>