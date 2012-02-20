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
 *	Request
 *
 *	Class used to create HTTP request via cURL.
 *
 *	@package HTTP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Request extends Client {

	/**
	 *	@var resource $curl cURL resouce.
	 */
	protected $curl;

	/**
	 * 	@var array $curl_options Array containing cURL options
	 */
	protected $curl_options = array();

	/**
	 *	@var \Architect\URI\URI $uri URI object.
	 */
	protected $uri;

	/**
	 *	Constructor
	 */
	public function __construct() {
	
		// Invoke parent constructor
		parent::__construct();
	
		// Initialize cURL
		$this->curl = curl_init();
		
		// Set default cURL options
		$this->setOptions(array(
		
			CURLOPT_VERBOSE => true,

			CURLOPT_NOBODY => false,

			CURLOPT_HEADER => true,

			CURLOPT_FOLLOWLOCATION => true,

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_CONNECTTIMEOUT => 30,
			
			CURLINFO_HEADER_OUT => true
		
		));
		
	}

	/**
	 *	setOption
	 *
	 *	This method sets a cURL option.
	 *
	 *	@param const $option
	 *	@param string|int $data
	 *
	 *	@return void
	 */
	public function setOption($option, $data) {

		$this->curl_options[$option] = $data;

	}
	
	/**
	 *	setOptions
	 *
	 *	Does the same as {@see setOption} with each element in an associative array.
	 *
	 *	@param array $options Associative array with cURL options.
	 *
	 *	@return void
	 */
	public function setOptions($options = array()) {

		foreach($options as $option => $value) {
		
			$this->setOption($option, $value);
		
		}

	}

	/**
	 *	bindOptions
	 *
	 *	Binds cURL options to cURL resource.
	 *
	 *	@return void
	 */
	protected function bindOptions() {
	
		curl_setopt_array($this->curl, $this->curl_options);
	
	}

	/**
	 *	setRequestURL
	 *
	 *	Sets request URL.
	 *
	 *	@param \Architect\URI\URI $uri Instance of \Architect\URI\URI
	 *
	 *	@return void
	 */
	public function setRequestURI(\Architect\URI\URI $uri) {
	
		$this->uri = $uri;
		
		$append_query_string = ($this->getMethod() === 'GET') ? true : false;
		
		$this->setOption(CURLOPT_URL, $uri->getRequestURI($append_query_string));
	
	}

	/**
	 *	getRequestURI
	 *
	 *	Returns request URI.
	 *
	 *	@return string
	 */
	public function getRequestURI() {
		
		$append_query_string = ($this->getMethod() === 'GET') ? true : false;
		
		return $this->uri->getRequestURI($append_query_string);
	
	}


	/**
	 *	fauxAjax
	 *
	 *	Fake an XHR/Ajax request by sending X-Requested-With header.
	 *
	 *	@return void
	 */
	public function fauxAjax() {
	
		// This header is used by almost every javascript library
		// Used to fake an Ajax-request via PHP
		$this->setHeader('X-Requested-With', 'XMLHttpRequest');
	
	}

	/**
	 *	send
	 *
	 *	Preforms request and returns a new \Architect\HTTP\Response object.
	 *
	 *	@return \Architect\HTTP\Response
	 */
	public function send() {
		
		$time = time();
	
		// Log request time
		\Jarvis\Benchmark::log("Request_{$time}", 'Preforming URL request.', __FILE__, __LINE__);
		
		// Get request headers to send
		$request_headers = array();
		
		foreach($this->getHeaders() as $header => $value) {
		
			$request_headers[] = "{$header}: {$value}";
		
		}
		
		// Set headers option
		$this->setOption(CURLOPT_HTTPHEADER, $request_headers);
		
		// Set additional cURL options depending on request type
		switch($this->getMethod()) {
		
			case 'GET' :
			
				// Do nothing
			
			break;
			
			case 'POST' :
			
				$this->setOptions(array(

					CURLOPT_POST => true,

					CURLOPT_POSTFIELDS => $this->getParams($this->getMethod())

				));
			
			break;
			
			case 'PUT' :
			
			case 'DELETE' :
			
				$this->setOptions(array(

					CURLOPT_CUSTOMREQUEST => $this->getMethod(),

					CURLOPT_POSTFIELDS => $this->getParams($this->getMethod())

				));
			
			break;
		
		}
		
		/**
		 *	@private $send_request
		 *
		 *	Anonymous function used to send request.
		 *
		 *	@return object
		 */
		$send_request = function($curl) {
	
			// Make cURL request and return it
		 	return (object) array(

				'data' => curl_exec($curl),
	
				'info' => (object) curl_getinfo($curl)
	
			);
		
		};
		
		// Make a HEAD request
		$this->setOptions(array(
		
			CURLOPT_HEADER => true,
			
			CURLOPT_NOBODY => true
		
		));
		
		// Bind cURL options
		$this->bindOptions();
		
		// Get response headers
		$response_headers = $send_request($this->curl);
		$response_headers = $response_headers->data;
		
		// Make original request
		$this->setOptions(array(
		
			CURLOPT_HEADER => false,
			
			CURLOPT_NOBODY => false
		
		));
		
		// Bind cURL options
		$this->bindOptions();
		
		// Get response headers
		$response_object = $send_request($this->curl);
		$response_data = $response_object->data;
		
		// Create a new Response object
		$response = new Response();
		
		// Set response data
		$response->setData($response_data);
		
		// Set response info
		$response->setInfo($response_object->info);
		
		// Set status code
		$response->setStatusCode($response_object->info->http_code);
		
		// Parse response headers
		$response->parseHeaders($response_headers);
		
		\Jarvis\Benchmark::assert("Request_{$time}");
		
		// Return response object
		return $response;		
		
	}

}
?>