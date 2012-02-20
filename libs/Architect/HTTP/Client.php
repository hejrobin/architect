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
 *	Client
 *
 *	Holds HTTP status codes and parameters, as well as outputting and sending of HTTP headers.
 *
 *	@package HTTP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Client {

	/**
	 *	@var string $http_protocol HTTP protocol.
	 */
	protected $http_protocol = "HTTP/1.1";
	
	/**
	 *	@var int $http_status_code HTTP status code, see {@see $http_status_codes}.
	 */
	protected $http_status_code;
	
	/**
	 *	@var string $http_status_type HTTP status type, see {@see $http_status_types}.
	 */
	protected $http_status_type;
	
	/**
	 *	@var array $http_headers Registered HTTP headers.
	 */
	protected $http_headers = array();
	
	/**
	 *	@var array $http_status_codes HTTP status codes and names.
	 */
	protected $http_status_codes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		509 => 'Bandwidth Limit Exceeded'
	);
	
	/**
	 *	@var array $http_status_types HTTP status code types.
	 */
	protected $http_status_types = array(
		'Informational' => array(100, 101, 418),
		'Success' => array(200, 201, 202, 203, 204, 205, 206),
		'Redirect' => array(300, 301, 302, 303, 304, 305, 307),
		'Client Error' => array(400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417),
		'Server Error' => array(500, 501, 502, 503, 504, 505, 509)
	);

	/**
	 *	@var string $request_method
	 */
	protected $request_method;

	/**
	 *	@var array $request_methods
	 */
	protected $request_methods = array('GET', 'POST', 'PUT', 'DELETE');

	/**
	 *	@var array $request_data
	 */
	protected $request_data = array();

	/**
	 *	Constructor
	 *
	 *	Prepares request data parameters and default headers.
	 *
	 *	@return void
	 */
	public function __construct() {
	
		$this->prepareParams();
	
		$this->setDefaultHeaders();
	
	}

	/**
	 *	getProtocol
	 *
	 *	Returns HTTP protocol.
	 *
	 *	@return string
	 */
	public function getProtocol() {

		return $this->http_protocol;

	}
	
	/**
	 *	setStatusCode
	 *
	 *	Sets current HTTP status code.
	 *
	 *	@param int $status_code
	 */
	public function setStatusCode($status_code) {

		$this->http_status_code = $status_code;

	}
	
	/**
	 *	getStatusCode
	 *
	 *	Returns the HTTP status code registered to this instance.
	 *
	 *	@return int
	 */
	public function getStatusCode() {

		return $this->http_status_code;

	}
	
	/**
	 *	getStatusMessage
	 *
	 *	Returns the status message associated with status code.
	 *
	 *	@return string|null
	 */
	public function getStatusMessage() {

		// Get status code
		$status_code = $this->getStatusCode();
		
		// Get status codes
		$status_codes = $this->http_status_codes;
		
		// Return status message if exists
		if(array_key_exists($status_code, $status_codes) === true) {
		
			return $status_codes[$status_code];
		
		}

		// Return null
		return null;

	}
	
	/**
	 *	getStatusType
	 *
	 *	Returns the type of the HTTP status message.
	 *
	 *	@return string
	 */
	public function getStatusType() {

		// Only continue if status type is not set
		if(is_string($this->http_status_type) === false) {
		
			// Set status type
			$status_type = null;

			// Iterate through each status types to find status type
			foreach($this->http_status_types as $status_type => $status_codes) {

				// Return status type code
				if(in_array($this->http_status_code, $status_codes)) {
				
					$this->http_status_type = $status_type;
				
				}

			}

		}
		
		// Return HTTP status type
		return $this->http_status_type;

	}
	
	/**
	 *	getStatus
	 *
	 *	Returns the full HTTP status including protocol, status code and status message.
	 *
	 *	@return string
	 */
	public function getStatus() {

		return sprintf("%s %s %s", $this->http_protocol, $this->http_status_code, $this->getStatusMessage());

	}

	/**
	 *	setMethod
	 *
	 *	This method sets request method, can either be GET, POST, PUT or DELETE.
	 *
	 *	@param string $request_method
	 *
	 *	@return void
	 */
	public function setMethod($request_method) {
	
		if(in_array(strtoupper($request_method), $this->request_methods) === true) {

			$this->request_method = $request_method;

		} else {

			$this->request_method = false;

		}
	}
	
	/**
	 *	getMethod
	 *
	 *	This method returns the request method used, attempts to set it if not set.
	 *
	 *	@return string
	 */
	public function getMethod() {

		if(isset($this->request_method)) {
		
			return $this->request_method;
		
		} else {
		
			$this->setMethod($_SERVER['REQUEST_METHOD']);
			
			if($this->request_method === false) {
			
				$this->request_method = 'GET';
			
			}

		}

		// Return request type	
		return strtoupper($this->request_method);

	}

	/**
	 *	isSuccess
	 *
	 *	Returns a bool whether current status code type is "sucessful".'
	 *
	 *	@return bool
	 */
	public function isSuccess() {

		$status_int = floor($this->http_status_code / 100);

		if($status_int === 1 || $status_int === 2) {
		
			return true;
		
		}
			
		return false;

	}
	
	/**
	 *	isRedirect
	 *
	 *	Returns a bool whether current status code type is "redirect".'
	 *
	 *	@return bool
	 */
	public function isRedirect() {

		$status_int = floor($this->http_status_code / 100);

		if($status_int === 3) {
		
			return true;
		
		}
			
		return false;

	}
	
	/**
	 *	isError
	 *
	 *	Returns a bool whether current status code type is "error".'
	 *
	 *	@return bool
	 */
	public function isError() {

		$status_int = floor($this->http_status_code / 100);

		if($status_int === 4 || $status_int === 5) {
		
			return true;
		
		}

		return false;
	}
	
	/**
	 *	isGet
	 *
	 *	This method checks if request is GET.
	 *
	 *	@return bool
	 */
	public function isGet() {

		return !!($this->getMethod() === 'GET');

	}
	
	/**
	 *	isPost
	 *
	 *	This method checks if request is POST.
	 *
	 *	@return bool
	 */
	public function isPost() {

		return !!($this->getMethod() === 'POST');

	}
	
	/**
	 *	isPut
	 *
	 *	This method checks if request is PUT.
	 *
	 *	@return bool
	 */
	public function isPut() {

		return !!($this->getMethod() === 'PUT');

	}
	
	/**
	 *	isDelete
	 *
	 *	This method checks if request is DELETE.
	 *
	 *	@return bool
	 */
	public function isDelete() {

		return !!($this->getMethod() === 'DELETE');

	}
	
	/**
	 *	isAjax
	 *
	 *	This method checks whether current request is an Ajax request, this method is not entierly reliable.
	 *
	 *	@return bool
	 */
	public function isAjax() {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
			return true;
		
		}

		return false;
	}

	/**
	 *	setHeader
	 *
	 *	Sets a HTTP header.
	 *
	 *	@param string $header
	 *	@param string $parameter
	 */
	public function setHeader($header, $parameter) {

		if(preg_match("/^[a-zA-Z0-9-]+$/", $header)) {
		
			$this->http_headers[$header] = $parameter;
		
		}

	}

	/**
	 *	getHeader
	 *
	 *	Returns a HTTP header (if registered).
	 *
	 *	@param string $header
	 *
	 *	@return string|bool
	 */
	public function getHeader($header) {
	
		if(array_key_exists($header, $this->http_headers)) {
		
			return $this->http_headers[$header];
		
		}

		return false;

	}

	/**
	 *	setHeaders
	 *
	 *	Sets headers from an associative array.
	 *
	 *	@param array $headers
	 */
	public function setHeaders($headers) {

		if(is_array($headers) === true) {

			foreach($headers as $header => $parameter) {
			
				$this->setHeader($header, $parameter);
			
			}

		}

	}

	/**
	 *	getHeaders
	 *
	 *	Returns all registered HTTP headers.
	 *
	 *	@param array
	 */
	public function getHeaders() {

		return $this->http_headers;

	}

	/**
	 *	setDefaultHeaders
	 *
	 *	This method sets default request headers.
	 *
	 *	@return void
	 */
	protected function setDefaultHeaders() {

		// Set status code
		$this->setStatusCode(200);

		// Set headers
		$this->setHeaders(array(

			'Date' => date('r'),

			'Accept-Language' => ARCH_LOCALE_LANGUAGE_CODE,

			'Accept-Charset' => ARCH_LOCALE_CHARSET,

			'User-Agent' => $_SERVER['HTTP_USER_AGENT']

		));

	}

	/**
	 *	outputHeaders
	 *
	 *	Returns an output of all registered headers, including status code.
	 *
	 *	@return string
	 */
	public function outputHeaders() {

		// Output HTTP status
		$output = $this->getStatus() . "\n";
		
		// Output HTTP headers
		foreach($this->getHeaders() as $header => $parameter) {
		
			$output .= "{$header}: {$parameter}\n";
		
		}
		
		// Return headers as string
		return $output;

	}
	
	/**
	 *	sendHeaders
	 *
	 *	Sends registered output headers and status code.
	 */
	public function sendHeaders() {

		// Send HTTP status
		header($this->getStatus());
		
		// Send HTTP headers
		foreach($this->getHeaders() as $header => $parameter) {
		
			header("{$header}: {$parameter}");
		
		}

	}

	/**
	 *	prepareParams
	 *
	 *	Merges $_GET, $_POST, $_REQUEST and $_COOKIE with an internal store object.
	 *
	 *	@return void
	 */
	protected function prepareParams() {

		$this->request_data = array(
			'raw' => array_merge((isset($this->request_data->raw) ? $this->request_data->raw : array()), $_REQUEST),
			'get' => array_merge((isset($this->request_data->get) ? $this->request_data->get : array()), $_GET),
			'post' => array_merge((isset($this->request_data->post) ? $this->request_data->post : array()), $_POST),
			'put' => array_merge((isset($this->request_data->put) ? $this->request_data->put : array()), array()),
			'delete' => array_merge((isset($this->request_data->delete) ? $this->request_data->delete : array()), array())
		);
		
		// Clean parameters
		$this->cleanParams();

	}
	
	/**
	 *	cleanParam
	 *
	 *	This method strips slashes from a variable or each item in an array.
	 *
	 *	@param string|array $param
	 *
	 *	$return string
	 */
	protected function cleanParam($param) {
	
		// Accept input parameter as an array
		if(is_array($param) === true) {
		
			// Map each element in array to this method
			return array_map(array($this, 'cleanParam'), $param);
		
		} else {
		
			// Strip slashes from param
			return stripslashes($param);
		
		}

	}
	
	/**
	 *	cleanParams
	 *
	 *	Cleans request and response parameters.
	 *
	 *	@return void
	 */
	protected function cleanParams() {

		// Continue if magic quotes is active
		if(get_magic_quotes_gpc() === 1) {
	
			// Iterate through each request group parameters
			foreach($this->request_data as $group => $params) {
			
				$this->cleanParam($params);
			
			}
				
		}

	}
	
	/**
	 *	setParam
	 *
	 *	Sets a request or response data parameter.
	 *
	 *	@param string $param HTTP parameter name.
	 *	@param string|int $data Parameter data, must be string or numerical value.
	 *	@param string $request_method Optional parameter, HTTP request method.
	 *
	 *	@return void
	 */
	public function setParam($param, $data, $request_method = false) {

		// Get request method
		$request_method = strtolower((is_string($request_method)) ? $request_method : $this->getMethod());

		// Get request group
		$group = $this->request_data[$request_method];
		
		// Set group data parameter if set
		if(is_array($group) === true && isset($data) === true ) {
		
			$group[$param] = $data;
		
		}
		
		// Set request data
		$this->request_data[$request_method] = $group; 

	}
	
	/**
	 *	getParam
	 *
	 *	Returns a request parameter if set, otherwise returns false.
	 *
	 *	@param string $param HTTP parameter name.
	 *	@param string $request_method Optional parameter, HTTP request method.
	 *
	 *	@return string|bool
	 */
	public function getParam($param, $request_method = false) {

		// Get request method
		$request_method = strtolower((is_string($request_method)) ? $request_method : $this->getMethod());

		// Get request group
		$group = $this->request_data[$request_method];

		// Only continue if group exists
		if(is_array($group) === true) {
		
			// Return parameter, or false if it does not exists
			return (isset($group[$param]) === true) ? $group[$param] : false;
		
		}
	
		return false;
	}
	
	/**
	 *	setParams
	 *
	 *	Sets an array of parameters, uses {@see setParam}.
	 *
	 *	@param array $params Array containing parameters to set.
	 *	@param string $request_method Optional parameter, HTTP request method.
	 *
	 *	@return array
	 */
	public function setParams($params, $request_method = false) {

		// Get request method
		$request_method = strtolower((is_string($request_method)) ? $request_method : $this->getMethod());

		// Iterate through each param and set them
		foreach($params as $param => $data) {
		
			$this->setParam($param, $data, $request_method);
		
		}
	
	}
	
	/**
	 *	getParams
	 *
	 *	Returns all request parameters from a specific group (request type).
	 *
	 *	@param string $request_method Optional parameter, HTTP request method.
	 *
	 *	@return array
	 */
	public function getParams($request_method = false) {

		// Get request method
		$request_method = strtolower((is_string($request_method)) ? $request_method : $this->getMethod());

		// Return request parameters to current request method
		if(array_key_exists($request_method, $this->request_data)) {
		
			return $this->request_data[$request_method];
		
		}

		// No parameters exist, returns an empty array
		return array();

	}

}
?>