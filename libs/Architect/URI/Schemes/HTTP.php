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

/* @namespace Schemes */
namespace Architect\URI\Schemes;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	URI
 *
 *	Skeleton class used to handle URIs, should only be a parent of a scheme handler, see {@see Architect\URI\Schemes}.
 *
 *	@package URI
 *
 *	@versions 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class HTTP extends \Architect\URI\URI {

	/**
	 *	@var array $default_ports Default ports to ignore in {@see autodiscover}.
	 */
	private $default_ports = array(80, 8888);
	
	/**
	 *	@var int $default_ssl_port Default SSL port
	 */
	private $default_ssl_port = 443;

	/**
	 *	usesSecureSocketsLayer
	 *
	 *	Returns true if SSL is enabled for HTTP-based URIs.
	 *
	 *	@throws Exceptions\SchemeException
	 *
	 *	@reutrns bool
	 */
	public function usesSecureSocketsLayer($validate_port = false) {
	
		// Get SSL option
		$uses_secure_sockets_layer = !!(isset($_SERVER['HTTPS']) === true && strtoupper($_SERVER['HTTPS']) === 'ON');
		
		// Validate SSL port
		if($uses_secure_sockets_layer === true && intval($_SERVER['SERVER_PORT']) !== $this->default_ssl_port) {
		
			throw new Exceptions\SchemeException(
				'Could not parse and resolve URI object.',
				"URI scheme 'https' expects server port to be '{$this->default_ssl_port}'.",
				__METHOD__, Exceptions\SchemeException::UNEXPECTED_VALUE_EXCEPTION
			);
		
		}
		
		// Return URI SSL setting
		return $uses_secure_sockets_layer;
	
	}

	/**
	 *	parse
	 *
	 *	Parses input URI and resolves it via {@see resolve}.
	 *
	 *	@param string $uri URI to parse.
	 *
	 *	@throws Exceptions\SchemeException
	 *
	 *	@return void
	 */
	public function parse($uri) {
		
		// Get parsed URI object
		$uri = $this->getParser()->parse($uri);
	
		// Validate URI scheme, only accept HTTP and HTTPS
		if(in_array(strtolower($uri->scheme), array('http', 'https')) === false) {

			throw new Exceptions\SchemeException(
				'Could not parse and resolve URI object.',
				"URI scheme is invalid or malformed, expected 'http' or 'https', '{$uri->scheme}' given.",
				__METHOD__, Exceptions\SchemeException::INVALID_ARGUMENT_EXCEPTION
			);

		}
		
		// Validate SSL option
		if($uri->scheme === 'https' && $this->usesSecureSocketsLayer() === false) {
		
			throw new Exceptions\SchemeException(
				'Could not parse and resolve URI object.',
				"URI scheme 'https' expects SSL to be enabled.",
				__METHOD__, Exceptions\SchemeException::UNEXPECTED_RESULT_EXCEPTION
			);
		
		}
		
		// Resolve URI
		$this->resolve($uri);
	
	}

	/**
	 *	autodiscover
	 *
	 *	Attempts to resolve current URI and parses it via {@see parse}.
	 *
	 *	@return void
	 */
	public function autodiscover() {

		// Get URI schema, assume HTTP/HTTPS
		$schema = ($this->usesSecureSocketsLayer() === true) ? 'https' : 'http';

		// Start compiling URI
		$uri = "{$schema}://" . $_SERVER['SERVER_NAME'];
		
		// Append port if other than default
		if(in_array(intval($_SERVER['SERVER_PORT']), $this->default_ports) === false) {

			$uri .= ':' . $_SERVER['SERVER_PORT'];

		}
		
		// Append request URI
		$uri .= $_SERVER['REQUEST_URI'];
		
		// Parse and resolve URI
		$this->resolve($this->getParser()->parse($uri));

	}

}
?>