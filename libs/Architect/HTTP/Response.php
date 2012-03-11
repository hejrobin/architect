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

/* @namespace HTTP */
namespace Architect\HTTP;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Response
 *
 *	Class used to interpret a HTTP response.
 *
 *	@package HTTP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Response extends Client {

	/**
	 *	@var string $data HTTP response data.
	 */
	protected $data;
	
	/**
	 *	@var array $info HTTP response info.
	 */
	protected $info;

	/**
	 *	parseResponse
	 *
	 *	Parses a HTTP response header.
	 *
	 *	@param string $response_headers String with response headers.
	 *
	 *	@return void
	 */
	public function parseHeaders($response_headers) {

		// Split headers into an array
		$response_headers = explode("\n", $response_headers);

		// Get parsed headers
		$parsed_headers = array();
		
		foreach($response_headers as $header) {
		
			$segments = explode(':', $header, 1);
			
			if(isset($segments[0]) === true &&isset($segments[1]) === true) {
			
				$parsed_headers[trim($segments[0])] = trim($segments[1]);
			
			}
		
		}
		
		// Set parsed headers
		$this->setHeaders($parsed_headers);

	}

	/**
	 *	setData
	 *
	 *	Sets response data.
	 *
	 *	@param string $response_data Response data.
	 *
	 *	@return void
	 */
	public function setData($response_data) {

		$this->data = $response_data;

	}
	
	/**
	 *	getData
	 *
	 *	Returns response data.
	 *
	 *	@return string
	 */
	public function getData() {

		if(isset($this->data)) {
		
			return $this->data;
		
		}
			
	}
	
	/**
	 *	setInfo
	 *
	 *	Sets response info.
	 *
	 *	@param array $response_info
	 *
	 *	@return void
	 */
	public function setInfo(array $response_info) {

		$this->info = $response_info;

	}
	
	/**
	 *	getInfo
	 *
	 *	Returns response info.
	 *
	 *	@return array
	 */
	public function getInfo() {

		if(isset($this->info)) {
		
			return $this->info;
		
		}

	}

}
?>