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

/* @namespace Config */
namespace Architect\Core\Config;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	PackageConstantParser
 *
 *	Parses package.xml for data.
 *
 *	@package Core
 *	@subpackage Config
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class PackageConstantParser {

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

		\Rae\Console::log("Invoked \"" . __CLASS__ . "\".", __METHOD__, __FILE__, __LINE__);

		// Get package.xml file path
		$file_path = ARCH_ROOT_PATH . 'package.xml';

		// Create instance of FileInfo
		$file = new \Architect\Domain\File\Object($file_path);

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

	}

	/**
	 *	parseConstants
	 *
	 *	Parses constants defined in config.xml.
	 *
	 *	@return void
	 */
	private function parseConstants() {

		\Rae\Benchmark::log("PackageConstantParser", "Package constant parsing execution time.", __METHOD__, __FILE__, __LINE__);

		// Get lang node
		$node = $this->parser->query('//arch:license');

		// Get locale language code
		$framework_license = $node->getValue();

		// Get locale ISO code
		$framework_license_url = $node->getAttribute('url');

		// Get major version
		$version_major = $this->parser->query('//arch:version/arch:major')->getValue();

		// Get major version
		$version_minor = $this->parser->query('//arch:version/arch:minor')->getValue();

		// Constants array
		$constants = array(

			'framework_license' => trim($framework_license),

			'framework_license_url' => $framework_license_url,

			'framework_version' => "{$version_major}.{$version_minor}"

		);

		// Define locale constants
		foreach($constants as $constant_name => $constant_value) {

			// Define constant
			$this->defineConstant($constant_name, $constant_value);

		}

		\Rae\Benchmark::assert("PackageConstantParser");

	}

}
?>