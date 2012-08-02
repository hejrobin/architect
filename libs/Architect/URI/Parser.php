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

/* @namespace URI */
namespace Architect\URI;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Parser
 *
 *	Handles parsing of URIs and validation of parsed URI segments.
 *
 *	@package URI
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Parser {

	/**
	 *	@const string REGEX_HEX_CHARS Hex characters.
	 */
	const REGEX_HEX_CHARS = 'a-f0-9';

	/**
	 *	@const string REGEX_ALNUM_CHARS Alphanumeric characters.
	 */
	const REGEX_ALNUM_CHARS = 'a-z0-9';

	/**
	 *	@const string REGEX_URI_CHARS Valid URI characters.
	 */
	const REGEX_URI_CHARS = "a-z0-9-._~!$&'()*+,;=";

	/**
	 *	@const string REGEX_URI_SCHEMA_CHARS Valid URI schema characters.
	 */
	const REGEX_URI_SCHEMA_CHARS = 'a-z0-9+-._';

	/**
	 *	@const string REGEX_URI_UNWISE_CHARS Unwise, but not invalid URI characters.
	 */
	const REGEX_URI_UNWISE_CHARS = '{}|\\\\^`';

	/**
	 *	@var bool $allow_undiwse Specified whether to allow unwise URI chars.
	 */
	protected $allow_unwise;

	/**
	 *	@var string $uri_regex URI regex.
	 */
	protected $uri_regex;

	/**
	 *	@var string $uri_scheme_regex URI schema regex.
	 */
	protected $uri_scheme_regex;
	
	/**
	 *	@var string $uri_host_regex URI host regex.
	 */
	protected $uri_host_regex;
	
	/**
	 *	@var string $uri_request_path_regex URI request path regex.
	 */
	protected $uri_request_path_regex;
	
	/**
	 *	@var string $uri_query_string_regex URI query string regex.
	 */
	protected $uri_query_string_regex;

	/**
	 *	Constructor
	 *
	 *	Compiles regexes used for parsing.
	 *
	 *	@return void
	 */
	public function __construct($allow_unwise = false) {
		
		// Set allow unwise option
		$this->allow_unwise = (is_bool($allow_unwise) === true) ? true : false;
		
		// Compile regular expressions
		$this->compileRegularExpressions();

	}

	/**
	 *	compileRegex
	 *
	 *	Compiles all regular expressions used for parsing of URIs.
	 *
	 *	@return void
	 */
	protected function compileRegularExpressions() {

		// Compile URI regex
		$this->uri_regex = '`';
		$this->uri_regex .= "(?:([" . self::REGEX_URI_SCHEMA_CHARS . "]+)://)?";
		$this->uri_regex .= "(?:";
		$this->uri_regex .= 	"(?:((?:[" . self::REGEX_URI_CHARS . ":]|%[" . self::REGEX_HEX_CHARS . "]{2})*)@)?";
		$this->uri_regex .= 	"(?:\[((?:[" . self::REGEX_ALNUM_CHARS . ":])*)\])?";
		$this->uri_regex .= 	"((?:[" . self::REGEX_URI_CHARS . "]|%[" . self::REGEX_HEX_CHARS . "]{2})*)";
		$this->uri_regex .= 	"(?::(\d*))?";
		$this->uri_regex .= 	"(/(?:[" . self::REGEX_URI_CHARS . ":@/]|%[" . self::REGEX_HEX_CHARS . "]{2})*)?";
		$this->uri_regex .= 	"|";
		$this->uri_regex .= 	"(/?";
		$this->uri_regex .= 		"(?:[" . self::REGEX_URI_CHARS . ":@]|%[" . self::REGEX_HEX_CHARS . "]{2})+";
		$this->uri_regex .= 		"(?:[" . self::REGEX_URI_CHARS . ":@\/]|%[" . self::REGEX_HEX_CHARS . "]{2})*";
		$this->uri_regex .= 	")?";
		$this->uri_regex .= ")";
		$this->uri_regex .= "(?:\?((?:[" . self::REGEX_URI_CHARS . ":\/?@]|%[" . self::REGEX_HEX_CHARS . "]{2})*))?";
		$this->uri_regex .= "(?:#((?:[" . self::REGEX_URI_CHARS . ":\/?@]|%[" . self::REGEX_HEX_CHARS . "]{2})*))?";
		$this->uri_regex .= '`i';
		
		// Set URI schema regex
		$this->uri_scheme_regex = '/^[a-z][' . self::REGEX_ALNUM_CHARS . ']+\:\/\/$/i';
		
		// Set URI host regex
		$this->uri_host_regex = '/^[' . self::REGEX_URI_SCHEMA_CHARS . ']+$/i';
		
		// Get regex for unwise chars if option is set to true
		$uri_regex_unwise = ($this->allow_unwise === true) ? self::REGEX_URI_UNWISE_CHARS : '';
		
		// Set URI request path regex
		$this->uri_request_path_regex = '/^[' . self::REGEX_URI_CHARS . $uri_regex_unwise . '\/#@]+$/i';
		
		// Set URI query string regex
		$this->uri_query_string_regex = '/^[' . self::REGEX_URI_CHARS . $uri_regex_unwise . '#@]+$/i';

	}

	/**
	 *	isValidScheme
	 *
	 *	Verifies validity of input URI schema.
	 *
	 *	@param string $schema URI schema, including trailing semi-colon and double forward slash.
	 *
	 *	@return bool
	 */
	public function isValidScheme($scheme) {

		return (preg_match($this->uri_scheme_regex, $scheme) === 1) ? true : false;

	}

	/**
	 *	isValidHost
	 *
	 *	Verifies validity of input URI host.
	 *
	 *	@param string $host URI host.
	 *
	 *	@return bool
	 */
	public function isValidHost($host) {

		return (preg_match($this->uri_host_regex, $host) === 1) ? true : false;

	}

	/**
	 *	isValidRequestPath
	 *
	 *	Verifies validity of input URI request path.
	 *
	 *	@param string $request_path URI request path.
	 *
	 *	@return bool
	 */
	public function isValidRequestPath($request_path) {
		
		return (preg_match($this->uri_request_path_regex, $request_path) === 1) ? true : false;

	}

	/**
	 *	isValidQueryString
	 *
	 *	Verifies validity of input URI query string.
	 *
	 *	@param string $query_string URI query string.
	 *
	 *	@return bool
	 */
	public function isValidQueryString($query_string) {

		return (preg_match($this->uri_query_string_regex, $query_string) === 1) ? true : false;

	}

	/**
	 *	parse
	 *
	 *	Parses a URI and returns an object with matched segments.
	 *
	 *	@param string $uri URI to parse.
	 *
	 *	@return object
	 */
	public function parse($uri) {

		// Create empty result
		$result = (object) array(
			'scheme' => null,
			'host' => null,
			'port' => null,
			'path' => null,
			'query' => null,
			'fragment' => null,
			'credentials' => null
		);
	
		// Parse input URI
		preg_match($this->uri_regex, $uri, $match);
		
		// Populate segments object
		switch(count($match)) {
			
			case 10 :
			
				$result->fragment = $match[9];
			
			case 9 :
			
				$result->query = $match[8];
			
			case 8 :
			
				$result->path = $match[7];
			
			case 7 :
			
				$result->path = $match[6] . $result->path;
			
			case 6 :
			
				$result->port = $match[5];
			
			case 5 :
			
				$result->host = ($match[3]) ? "[" . $match[3] . "]" : $match[4];
			
			case 4 :

				// For security reasons, this was intentionally commented out.
				// You may, at your own risk uncomment the lines below.
				
				//$credentials = explode(':', $match[2]);

				//$result->credentials = (object) array(
				//	'username' => $credentials[0],
				//	'password' => (isset($credentials[1]) === true) ? $credentials[1] : null
				//);
			
			case 3 :

				$result->scheme = $match[1];
		
		}
		
		// Set script name 
		$result->script_name = trim(basename($_SERVER['SCRIPT_NAME']), '/');
		
		// Set script path 
		$result->script_path = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
		
		// Normalize request path
		$result->path = trim(str_ireplace(array($result->script_name), '', $result->path), '/');
		
		// Return resulting object
		return $result;

	}

}
?>