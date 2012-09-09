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

/* @namespace Localization */
namespace Architect\Localization;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Locale
 *
 *	Class used to handle localization objects.
 *
 *	@package Localization
 *
 *	@dependencies \Architect\Domain\File\Object, \Architect\Domain\XML\Parser
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Locale {

	/**
	 *	@var string $previous Previous locale ISO code.
	 */
	protected $previous;

	/**
	 *	@var string $current Current locale ISO code.
	 */
	protected $current;

	/**
	 *	@var array $data Parsed locale data.
	 */
	protected $data = array();

	/**
	 *	parseLocaleDefinitions
	 *
	 *	Parses through current locale definiton file.
	 *
	 *	@return void
	 */
	protected function parseLocaleDefinitions() {

		$definitions_path = ARCH_LOCALIZATIONS_PATH . $this->current . DIRECTORY_SEPARATOR;

		$definitions_file = "{$definitions_path}locale.xml";

		if(file_exists($definitions_file) === true) {

			// Create instance of FileInfo
			$file = new \Architect\Domain\File\Object($definitions_file);

			// Create instance of Parser
			$parser = new \Architect\Domain\XML\Parser($file);

			// Register XML namespace
			$parser->registerNamespace('arch', 'http://architect.kodlabbet.net/xmlns');

			$nodes = $parser->queryAll('arch:definitionFiles/arch:definition');

			foreach($nodes as $node) {

				$identifier = $node->getAttribute('identifier');

				$definition_file = $definitions_path . 'Definitions' . DIRECTORY_SEPARATOR . "{$identifier}.json";

				if(file_exists($definition_file) === true) {

					$this->data[$identifier] = json_decode(file_get_contents($definition_file));

				}

			}

		}

	}

	/**
	 *	setLocale
	 *
	 *	Changes current locale to a new one.
	 *
	 *	@param string $locale Locale ISO code.
	 *
	 *	@return bool
	 */
	public function setLocale($locale) {

		$definitions_path = ARCH_LOCALIZATIONS_PATH . $locale . DIRECTORY_SEPARATOR;

		if(is_dir($definitions_path) === true) {

			$this->previous = $this->current;

			$this->current = $locale;

			$this->parseLocaleDefinitions();

			af_hooks_invoke('localechange');

			return true;

		}

		return false;

	}

	/**
	 *	Getter
	 *
	 *	Returns definition object, if exists.
	 *
	 *	@return object|null
	 */
	public function __get($identifier) {

		if(array_key_exists($identifier, $this->data) === true) {

			return $this->data[$identifier];

		}

		return null;

	}

}
?>