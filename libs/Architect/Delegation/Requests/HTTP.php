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

/* @namespace Requests */
namespace Architect\Delegation\Requests;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	HTTP
 *
 *	HTTP requests for routing, inherits from {@see \Architect\Delegation\Request}.
 *
 *	@package Delegation
 *	@subpackage Request
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class HTTP extends \Architect\Delegation\Request {

	/**
	 *	Constructor
	 *
	 *	Sets properties for HTTP request.
	 *
	 *	@param string $default_resource Default resource.
	 *	@param string $default_resource_callback Default resource callback.
	 *
	 *	@return void
	 */
	public function __construct($default_resource, $default_resource_callback) {

		// Set resource
		$this->setProperty('default_resource', $default_resource);

		// Set resource callback
		$this->setProperty('default_resource_callback', $default_resource_callback);

		// Set additional properties
		$this->setAdditionalProperties();

	}

	/**
	 *	setAdditionalProperties
	 *
	 *	Sets additional HTTP request properties.
	 *
	 *	@return void
	 */
	private function setAdditionalProperties() {

		// Set request method
		$this->setProperty('method', strtolower($_SERVER['REQUEST_METHOD']));

		// Set XMLHTTPRequest property
		$this->setProperty('xml_http_request', (isset($_SERVER['HTTP_X_REQUESTED_WITH']) === true && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));

		// Set SSL property
		$this->setProperty('secure_socets_layer', (isset($_SERVER['HTTPS']) === true && strtoupper($_SERVER['HTTPS']) === 'ON'));

	}

}
?>