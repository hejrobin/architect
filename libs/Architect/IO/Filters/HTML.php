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
 *	HTML
 *
 *	Filters HTML from unsafe attributes.
 *
 *	@package IO
 *	@subpackage Filters
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class HTML implements \Architect\IO\Filter {

	/**
	 *	@var array $allowed_tags Array containing allowed tag names.
	 */
	protected $allowed_tags = array(
		'div', 'span', 'p', 'a', 'img',
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		'ul', 'ol', 'li', 'dl', 'dt', 'dd', 'sub', 'sup',
		'strong', 'em', 'abbr', 'acronym', 'address',
		'blockquote', 'cite', 'q', 'code', 'ins', 'del', 'dfn', 'kbd', 'pre', 'samp', 'var',
		'table', 'thead', 'tbody', 'tfoot', 'th', 'tr', 'td', 'col', 'colgroup', 'caption'
	);

	/**
	 *	@var array $invalid_attributes Array containing invalid tag attributes.
	 */
	protected $invalid_attributes = array(
		'style',
		'onload',
		'onunload',
		'onblur',
		'onchange',
		'onfocus',
		'onreset',
		'onselect',
		'onsubmit',
		'onabort',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onclick',
		'ondblclick',
		'onmousedown',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup'
	);

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

		$array_map_callback = function($item) {
			return '<' . $item . '>'
		};

		$string = strip_tags($string, implode('', array_map($array_map_callback, $this->allowed_tags)));

		foreach($this->invalid_attributes as $attribute) {

			$string = preg_replace('/(<[^>]+) ' . $attribute . '(?:\s+)?=(?:\s+)?["|\'].*?["|\']/i', '$1', $string);

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