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
 *	CachedRenderer
 *
 *	Renderer for cached view files.
 *
 *	@package Renderers
 *	@subpackage Views
 *	@subpackage PHP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class CachedRenderer extends \Architect\Renderers\Views\Renderer {

	/**
	 *	fetchOutputBuffer
	 *
	 *	If output does not exist in store, return data from output buffer.
	 *
	 *	@return string
	 */
	protected function fetchOutputBuffer() {

		$renderer = new Renderer($this->view, $this->view_file);

		return $renderer->invoke();

	}

	/**
	 *	invoke
	 *
	 *	Fetches output buffer and returns it.
	 *
	 *	@return string
	 */
	public function invoke() {

		$arch = \Architect::getInstance();

		$cache_key_name = "{$this->view_file}:" . implode(':', $this->view->getVariables());

		if($arch->hasInstance('cache') === true && $arch->cache->has($cache_key_name)) {

			$output = $arch->cache->read($cache_key_name);

		} else {

			$output = $this->fetchOutputBuffer();

			$arch->cache->write($cache_key_name, $output, ARCH_CACHE_LIFETIME_VIEWS);

		}

		return $output;

	}

}
?>