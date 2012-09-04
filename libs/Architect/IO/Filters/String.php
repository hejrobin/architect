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

/* @namespace Filters */
namespace Architect\IO\Filters;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	String
 *
 *	Filters strings from unsafe characters.
 *
 *	@package IO
 *	@subpackage Filters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class String implements \Architect\IO\Filter {

	/**
	 *	filter
	 *
	 *	Filters strings from unsafe characters.
	 *
	 *	@param string $string String to filter.
	 *
	 *	@return string
	 */
	public function filter($string) {

		// Strip out any existing JavaScript
		$string = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $string);

		// Unallowed attributes for HTML
		$attributes = array('style', 'onload', 'onunload', 'onblur', 'onchange', 'onfocus', 'onreset', 'onselect', 'onsubmit', 'onabort', 'onkeydown', 'onkeypress', 'onkeyup', 'onclick', 'ondblclick', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup');

		foreach($attributes as $attribute) {

			$string = preg_replace('/(<[^>]+) ' . $attribute . '=".*?"/i', '$1', $string);

		}

		$string = htmlentities($string, ENT_QUOTES);

		return addslashes($string);

	}

	/**
	 *	output
	 *
	 *	Normalizes string for output.
	 *
	 *	@param string $string String to filter.
	 *
	 *	@return string
	 */
	public function output($string) {

		$string = html_entity_decode($string, ENT_QUOTES);

		return stripslashes($string);

	}

}
?>