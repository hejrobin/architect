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

/* @namespace Request */
namespace Architect\Delegation\Request;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	HTTP
 *
 *	HTTP request object.
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
	 *	@var array $properties Array containing valid request properties.
	 */
	protected $properties = array(
		'default_controller' => 'string',
		'component' => 'string',
		'controller' => 'string',
		'controller_callback' => 'string',
		'controller_callback_parameters' => 'array',
		'action' => 'string',
		'action_callback' => 'string',
		'action_callback_parameters' => 'array',
		'method' => 'string',
		'rules' => 'array',
		'ssl' => 'bool',
		'xhr' => 'bool'
	);

	/**
	 *	Constructor
	 *
	 *	Sets default request properties.
	 *
	 *	@return void
	 */
	public function __construct() {	
	
		// Set default request properties
		$this->setRequest(array(

			'method' => strtolower($_SERVER['REQUEST_METHOD']),

			'ssl' => (isset($_SERVER['HTTPS']) === true && strtoupper($_SERVER['HTTPS']) === 'ON'),

			'xhr' => (isset($_SERVER['HTTP_X_REQUESTED_WITH']) === true && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')

		));
	
	}

	/**
	 *	setRequest
	 *
	 *	Sets request properties.
	 *
	 *	@param array $properties Array containging property value pairs.
	 *
	 *	@return void
	 */
	public function setRequest(array $properties) {
	
		// Iterate through each element in properties
		foreach($properties as $property => $value) {
		
			// Set property if property exists
			if(array_key_exists($property, $this->properties)) {
			
				// Get property type
				$type = $this->properties[$property];
				
				// Set property
				$this->setProperty($property, $value, $type);
			
			}
		
		}
	
	}

}
?>