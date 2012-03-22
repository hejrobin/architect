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

/* @namespace URI */
namespace Architect\URI;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	URI
 *
 *	Skeleton class used to handle URIs, should only be a parent of a scheme handler, see {@see Architect\URI\Schemes}.
 *
 *	@package URI
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
abstract class URI {

	/**
	 *	@var string $scheme URI scheme.
	 */
	protected $scheme;

	/**
	 *	@var string $host Host name.
	 */
	protected $host;

	/**
	 *	@var string $port URI port.
	 */
	protected $port;

	/**
	 *	@var string $request_path Request URI path.
	 */
	protected $request_path;

	/**
	 *	@var string $request_query_string Request query string.
	 */
	protected $request_query_string;

	/**
	 *	@var string $fragment URI fragment, anything after hash pund sign.
	 */
	protected $fragment;

	/**
	 *	@var string $script_file_name Script file name.
	 */
	protected $script_file_name;

	/**
	 *	@var string $script_file_path Script file path.
	 */
	protected $script_file_path;
	
	/**
	 *	@var Parser $parser Instance of Parser.
	 */
	protected $parser;

	/**
	 *	Constructor
	 *
	 *	Creates a new instance of {@see Parser}.
	 *
	 *	@return void
	 */
	public function __construct() {

		// Create instance of parser
		$this->parser = new Parser();
		
		// Set script name
		$this->script_file_name = trim(basename($_SERVER['SCRIPT_NAME']), '/');
		
		// Set script path
		$this->script_file_path = trim(dirname($_SERVER['SCRIPT_NAME']), '/');

	}

	/**
	 *	getParser
	 *
	 *	Returns parser instance.
	 *
	 *	@return Parser
	 */
	public function getParser() {

		return $this->parser;

	}

	/**
	 *	setScheme
	 *
	 *	Validates and sets URI scheme.
	 *
	 *	@param string $scheme URI scheme.
	 *
	 *	@return void
	 */
	public function setScheme($scheme) {

		// Set URI scheme if parameter is valid
		if($this->validateScheme("{$scheme}://") === true) {

			$this->scheme = strtolower($scheme);

		}

	}

	/**
	 *	getScheme
	 *
	 *	Returns scheme, or throws an exception if scheme is not set.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return string
	 */
	public function getScheme() {

		// Throw exception if scheme is empty
		if(is_string($this->scheme) === false) {

			throw new Exceptions\URIException(
				'Could not return URI scheme.',
				'URI scheme is not set.',
				__METHOD__, Exceptions\URIException::EMPTY_RESULT_EXCEPTION
			);

		}
		
		// Return scheme
		return $this->scheme;

	}

	/**
	 *	validateScheme
	 *
	 *	Tests URI scheme for validity and returns a boolean.
	 *
	 *	@param string $scheme URI scheme.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return bool
	 */
	protected function validateScheme($scheme) {

		// Throw exception if URI scheme is malformed
		if($this->parser->isValidScheme($scheme) === false) {

			throw new Exceptions\URIException(
				'Input parameter is not a valid URI scheme.',
				"URI scheme may be malformed or invalid.",
				__METHOD__, Exceptions\URIException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Scheme test passed, return true
		return true;

	}

	/**
	 *	setHost
	 *
	 *	Validates and sets host name.
	 *
	 *	@param string $host_name Host name.
	 *
	 *	@return void
	 */
	public function setHost($host_name) {

		// Set URI host name if parameter is valid
		if($this->validateHost($host_name) === true) {

			$this->host = strtolower($host_name);

		}

	}

	/**
	 *	getHost
	 *
	 *	Returns host name, throws an exception if host name has not been set.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return string
	 */
	public function getHost() {

		// Throw exception if scheme is empty
		if(is_string($this->host) === false) {

			throw new Exceptions\URIException(
				'Could not get URI host name.',
				'URI host name is not set.',
				__METHOD__, Exceptions\URIException::EMPTY_RESULT_EXCEPTION
			);

		}

		// Return scheme
		return $this->host;

	}

	/**
	 *	validateHost
	 *
	 *	Tests host name for validity and returns a boolean.
	 *
	 *	@param string $host_name Host name.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return bool
	 */
	protected function validateHost($host) {

		// Throw exception if host name is malformed
		if($this->parser->isValidHost($host) === false) {

			throw new Exceptions\URIException(
				'Input parameter is not a valid URI host name.',
				"URI host name is malformed.",
				__METHOD__, Exceptions\URIException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Host name test passed, return true
		return true;

	}

	/**
	 *	setPort
	 *
	 *	Validates and sets port number.
	 *
	 *	@param int|string $port Port number.
	 *
	 *	@return void
	 */
	public function setPort($port) {

		// Set port number if parameter is valid
		if($this->validatePort($port) === true) {

			$this->port = intval($port);

		}

	}

	/**
	 *	getPort
	 *
	 *	Returns port.
	 *
	 *	@return int
	 */
	public function getPort() {

		// Return port
		return $this->port;

	}

	/**
	 *	validatePort
	 *
	 *	Tests port for validity and returns a boolean.
	 *
	 *	@param int|string $port Port number.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return bool
	 */
	protected function validatePort($port) {

		// Allow port to be null
		if($port === null)
			return false;

		// Throw exception if port is malformed
		if(is_int(intval($port)) === false) {
	
			throw new Exceptions\URIException(
				'Input parameter is not a valid port.',
				"Port is malformed, expected numerical value, '" . gettype($port) . "' given.",
				__METHOD__, Exceptions\URIException::INVALID_ARGUMENT_EXCEPTION
			);

		}

		// Port test passed, return true
		return true;

	}

	/**
	 *	setRequestPath
	 *
	 *	Validates and sets request path.
	 *
	 *	@param string $request_path Request path.
	 *
	 *	@return void
	 */
	public function setRequestPath($request_path) {

		// Set request path if parameter is valid
		if($this->validateRequestPath($request_path) === true) {

			$this->request_path = $request_path;

		}

	}

	/**
	 *	getRequestPath
	 *
	 *	Returns request path, or null if request path is not set.
	 *
	 *	@return string|null
	 */
	public function getRequestPath() {

		return trim(str_ireplace(trim(dirname($_SERVER['SCRIPT_NAME']), '/'), '', $this->request_path), '/');

	}

	/**
	 *	validateRequestPath
	 *
	 *	Tests request path for validity and returns a boolean.
	 *
	 *	@param string $request_path Request path.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return bool
	 */
	protected function validateRequestPath($request_path) {

		// Allow request path to be null
		if($request_path === null || $request_path === '') {
		
			return false;
		
		}
		
		// Throw exception if request path is malformed
		if($this->parser->isValidRequestPath($request_path) === false) {

			throw new Exceptions\URIException(
				'Input parameter is not a valid URI request path.',
				"Request path is malformed, expected numerical letters, dots, forward slashes, hash signs and question marks.",
				__METHOD__, Exceptions\URIException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}

		// Request path test passed, return true
		return true;

	}

	/**
	 *	setQueryString
	 *
	 *	Validates and sets query string.
	 *
	 *	@param string $request_path Query string.
	 *
	 *	@return void
	 */
	public function setQueryString($query_string) {

		// Set request query string if parameter is valid
		if($this->validateQueryString($query_string) === true) {

			$this->request_query_string = $query_string;

		}

	}

	/**
	 *	getQueryString
	 *
	 *	Returns query string, or null if request path is not set.
	 *
	 *	@return string|null
	 */
	public function getQueryString() {

		return $this->request_query_string;

	}

	/**
	 *	validateQueryString
	 *
	 *	Tests URI request path for validity and returns a boolean.
	 *
	 *	@param string $request_path Query string.
	 *
	 *	@throws Exceptions\URIException
	 *
	 *	@return bool
	 */
	protected function validateQueryString($query_string) {

		// Allow query string to be null
		if($query_string === null) {
		
			return false;
		
		}
		
		// Throw exception if query string is malformed
		if($this->parser->isValidQueryString($query_string) === false) {

			throw new Exceptions\URIException(
				'Input parameter is not a valid query string.',
				'Query string is malformed or contains illegal characters.',
				__METHOD__, Exceptions\URIException::USER_EXCEPTION
			);

		}

		// Query string test passed, return true
		return true;

	}

	/**
	 *	getScriptName
	 *
	 *	Returns script file name.
	 *
	 *	@return string
	 */
	public function getScriptName() {

		return $this->script_file_name;

	}

	/**
	 *	getScriptPath
	 *
	 *	Returns script file path.
	 *
	 *	@return string
	 */
	public function getScriptPath() {

		return $this->script_file_path;

	}

	/**
	 *	getScriptLocation
	 *
	 *	Returns script location including path and file.
	 *
	 *	@return string
	 */
	public function getScriptLocation() {

		if(stristr($_SERVER['REQUEST_URI'], $this->getScriptName())) {

			$script_location = trim(implode('', array(trim($this->getScriptPath(), '/'), '/', $this->getScriptName(), '/')), '/') . '/';

		} else {

			$script_location = $this->getScriptPath() . '/' . trim($this->getRequestPath(), '/');

		}

		// Return script location
		return str_ireplace($this->getRequestPath(), '', $script_location);

	}

	/**
	 *	getSegment
	 *
	 *	Return request path segment.
	 *
	 *	@param int $offset
	 *
	 *	@return string|null
	 */
	public function getSegment($offset) {
		
		// Get segments
		$segments = explode('/', $this->getRequestPath());
		
		// Return existing offset
		if($offset > 0 && $offset <= count($segments)) {
		
			return $segments[$offset - 1];
		
		}
	
		// No offset exists, return null
		return null;
	
	}

	/**
	 *	getBaseURI
	 *
	 *	Returns base URI, schema, host and script path.
	 *
	 *	@return string
	 */
	public function getBaseURI() {
		
		$base_uri = trim(implode('', array(
			$this->getScheme(),
			'://',
			$this->getHost()
		)), '/') . '/';
		
		// Append script path if present
		if(is_string($this->getScriptPath()) === true && $this->getScriptPath() !== '') {
		
			$base_uri .= $this->getScriptPath() . '/';
		
		}
		
		return $base_uri;

	}

	/**
	 *	getRequestURI
	 *
	 *	Returns full request URI, if input parameter is set to true it also appends query string.
	 *
	 *	@param bool $append_query_string Optional parameter, specifies whether to append query string or not.
	 *
	 *	@return string
	 */
	public function getRequestURI($append_query_string = false) {

		// Get request URI
		$uri = $this->getScriptLocation();
		$uri .= $this->getRequestPath();
		
		// Remove script path
		$uri = str_ireplace($this->getScriptPath(), '', $uri);
		
		// Normalize request URI
		$uri = trim(preg_replace('/[\/]+/i', '/', $uri), '/');
		$uri = $this->getBaseURI() . "{$uri}/";
		$uri = trim($uri, '/') . '/';
		
		// Append query string if parameter is set to true
		if($append_query_string === true && is_string($this->getQueryString()) === true) {

			$uri .= '?' . $this->getQueryString();

		}
		
		// Return request URI
		return $uri;

	}

	/**
	 *	usesSecureSocketsLayer
	 *
	 *	Must return boolean whether URI protocol uses SSL.
	 *
	 *	@return bool
	 */
	public abstract function usesSecureSocketsLayer();

	/**
	 *	parse
	 *
	 *	Should call parser and parse input URI and call {@see resolve}.
	 *
	 *	@param string $uri URI to parse.
	 *
	 *	@return void
	 */
	public abstract function parse($uri);

	/**
	 *	autodiscover
	 *
	 *	Should attempt to analyze and parse current URI.
	 *
	 *	@return void
	 */
	public abstract function autodiscover();

	/**
	 *	resolve
	 *
	 *	Sets URI properties based on input URI object from URI parser.
	 *
	 *	@param object $parsed_uri Parsed URI object.
	 *
	 *	@return void
	 */
	protected function resolve($parsed_uri) {

		// Set URI scheme
		$this->setScheme($parsed_uri->scheme);
		
		// Set URI host
		$this->setHost($parsed_uri->host);
		
		// Set URI port
		$this->setPort($parsed_uri->port);
		
		// Set URI request path
		$this->setRequestPath($parsed_uri->path);
		
		// Set URI query string
		$this->setQueryString($parsed_uri->query);

	}

}
?>