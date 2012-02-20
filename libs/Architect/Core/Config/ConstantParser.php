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

/* @namespace Config */
namespace Architect\Core\Config;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	ConstantParser
 *
 *	Parses through each defined constant nodes in config.xml.
 *
 *	@package Core
 *	@subpackage Config
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class ConstantParser {

	/**
	 *	@const CONSTANT_PREFIX Constant prefix.
	 */
	const CONSTANT_PREFIX = 'ARCH_';

	/**
	 *	@var \Architect\Domain\XML\Parser $parser Instance of \Architect\Domain\XML\Parser.
	 */
	protected $parser;

	/**
	 *	Constructor
	 *
	 *	Creates a new instance of \Architect\Domain\XML\Parser and invokes internal parser method.
	 *
	 *	@return void
	 */
	public function __construct() {

		// Get config.xml file path
		$file_path = ARCH_ROOT_PATH . 'config.xml';
	
		// Create instance of FileInfo
		$file = new \Architect\Domain\File\File($file_path);
	
		// Create instance of Parser
		$this->parser = new \Architect\Domain\XML\Parser($file);
		
		// Register XML namespace
		$this->parser->registerNamespace('arch', 'http://architect.kodlabbet.net/xmlns');
		
		// Invoke constant parsing
		$this->parseConstants();

	}

	/**
	 *	defineConstant
	 *
	 *	Defines a constant, prepends constant prefix.
	 *
	 *	@param string $name Constant name, without prefix.
	 *	@param mixed $value Constant value.
	 *
	 *	@return void
	 */
	protected function defineConstant($name, $value) {

		// Set constant name
		$constant_name = self::CONSTANT_PREFIX . strtoupper($name);
		
		// Define constant
		define($constant_name, $value);

		// Log memory usage constant
		\Jarvis\Memory::log(constant($constant_name), constant($constant_name), $constant_name, __FILE__, __LINE__);

	}
	
	/**
	 *	parseConstants
	 *
	 *	Parses constants defined in config.xml.
	 *
	 *	@return void
	 */
	private function parseConstants() {
		
		// Log route map parsing
		\Jarvis\Benchmark::log('ConstantParsing', 'Constant parsing.', null, __FILE__, __LINE__);
		
		// Get constant nodes
		$nodes = $this->parser->queryAll('//arch:constant');
		
		// Iterate through each constant node
		foreach($nodes as $node) {
		
			// Select current node
			$node = $this->parser->select($node);
		
			// Constant name
			$constant_name = $node->getAttribute('name', false, '/^[a-z\_]+$/i');
			
			// Get constant type
			$constant_type = $node->getAttribute('type', true);
			
			// Fall back to 'string' type
			if(is_string($constant_type) === false)
				$constant_type = 'string';
			
			// Get constant value
			$constant_value = $node->getValue();
			
			// Resolve constant value by constant type
			switch($constant_type) {
				case 'bool' :
				case 'boolean' :
					if(strtolower($constant_value) === 'true') {
						$constant_value = true;
					} else {
						$constant_value = false;
					}
				break;
				
				case 'int' :
				case 'integer' :
					$constant_value = intval($constant_value);
				break;
					
				case 'path' :
					$constant_value = ARCH_ROOT_PATH . $constant_value;
				break;
				
				case 'php.ini' :
					$constant_value = ini_get($constant_value);
				break;
			}
		
			// Define constant
			$this->defineConstant($constant_name, $constant_value);	
			
		}
		
		// Parse locale constants
		$this->parseLocaleConstants();
		
		// Log total excecution time
		\Jarvis\Benchmark::assert('ConstantParsing');
		
	}

	/**
	 *	parseLocaleConstants
	 *
	 *	Parses and defines locale constants.
	 *
	 *	@return void
	 */
	private function parseLocaleConstants() {

		// Get lang node
		$node = $this->parser->query('//arch:vars/arch:lang');
		
		// Get locale language code
		$locale_language_code = $node->getValue(false, '/^[a-z]{2}$/i');
		
		// Get locale ISO code
		$locale_iso_code = $node->getAttribute('iso', false, '/^[a-z]{2}\-[A-Z]{2}$/');
		
		// Constants array
		$constants = array(

			'locale_language_code' => $locale_language_code,

			'locale_language_iso_code' => $locale_iso_code

		);
		
		// Define locale constants
		foreach($constants as $constant_name => $constant_value) {

			// Define constant
			$this->defineConstant($constant_name, $constant_value);

		}

		// Get charset node
		$charset = $this->parser->query('//arch:vars/arch:charset')->getValue();
		
		// Define charset constant
		$this->defineConstant('locale_charset', $charset);

	}

}
?>