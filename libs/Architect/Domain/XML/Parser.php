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

/* @namespace XML */
namespace Architect\Domain\XML;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Parser
 *
 *	Generic parser for XML documents, utilizes XPath.
 *
 *	@package Domain
 *	@subpackage XML
 *	@subpackage Parser
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Parser {

	/**
	 *	@var \Architect\Domain\XML\Document $document Instance of \Architect\Domain\XML\Document.
	 */
	protected $document;

	/**
	 *	@var DOMXPath $xpath Instance of {@man DOMXPath}.
	 */
	protected $xpath;

	/**
	 *	@var DOMElement $current_node Current node 
	 */
	protected $current_node;

	/**
	 *	Constructor
	 *
	 *	Creates a new instace of \Architect\Domain\File\File and {@man DOMXPath}.
	 *
	 *	@param \Architect\Domain\File\FileInfo $file Instance of \Architect\Domain\File\FileInfo.
	 *
	 *	@return void
	 */
	public function __construct(\Architect\Domain\File\File $file) {
	
		// Pass FileInfo instance to a new Document object
		$this->document = new Document($file);
		
		// Create instance of XPath
		$this->xpath = new \DOMXPath($this->document);
	
	}
	
	/**
	 *	registerNamespace
	 *
	 *	Register XML namespace.
	 *
	 *	@param string $namespace XML namespace name.
	 *	@param string $namespace_url Namespace URL.
	 *
	 *	@return void
	 */
	public function registerNamespace($namespace, $namespace_url) {

		// Register XML namespace
		$this->xpath->registerNamespace($namespace, $namespace_url);

	}

	/**
	 *	getCurrentNode
	 *
	 *	Returns current node.
	 *
	 *	@return 
	 */
	public function getCurrentNode() {

		// Return current registered node
		return $this->current_node;

	}

	/**
	 *	select
	 *
	 *	Sets current node and returns self.
	 *
	 *	@param DOMElement $node Node to use as current node.
	 *
	 *	@return self
	 */
	public function select(\DOMElement $node) {

		// Set current node
		$this->current_node = $node;
		
		// Return self
		return $this;

	}

	/**
	 *	queryAll
	 *
	 *	Returns all nodes from XPath query.
	 *
	 *	@param string $xpath XPath query.
	 *	@param DOMElement $context_node Optional parameter, context node for relative XPath query.
	 *	@param bool $allow_empty_result Optional parameter, boolean to specify whether to allow an empty DOMElementList.
	 *
	 *	@throws Exceptions\ParserException
	 *
	 *	@return DOMElementList
	 */
	public function queryAll($xpath, $context_node = null, $allow_empty_result = false) {

		// Fetch all query nodes
		$nodes = $this->xpath->query($xpath, $context_node);
		
		// Throw exception if XPath query was malformed
		if($nodes === false) {

			throw new Exceptions\ParserException(
				"XPath query did not return any XML nodes.",
				'XPath query may be malformed or context node is invalid.',
				__METHOD__, Exceptions\ParserException::MALFORMED_ARGUMENT_EXCEPTION
			);

		}
		
		// Throw exception if DOMElementList is empty
		if($nodes->length === 0 && $allow_empty_result === false) {

			throw new Exceptions\ParserException(
				__METHOD__ . " call failed.",
				"DOMElementList returned from XPath query did not return any nodes.",
				__METHOD__, Exceptions\ParserException::EMPTY_RESULT_EXCEPTION
			);

		}
		
		// Return DOMElementList
		return $nodes;

	}

	/**
	 *	query
	 *
	 *	Returns first node from XPath query.
	 *
	 *	@param string $xpath XPath query.
	 *	@param DOMElement $context_node Optional parameter, context node for relative XPath query.
	 *	@param bool $allow_empty_result Optional parameter, boolean to specify whether to allow an empty DOMElementList.
	 *
	 *	@return self
	 */
	public function query($xpath, $context_node = null, $allow_empty_result = false) {

		// Fetch all nodes
		$nodes = $this->queryAll($xpath, $context_node, $allow_empty_result);

		// Return first DOMElement
		if($nodes->length >= 1) {

			// Set current node
			$this->current_node = $nodes->item(0);
			
			// Return current node
			return $this;

		} else {
		
			// Reset current node
			$this->current_node = null;
		
		}
		
		// Return self
		return $this;

	}
	
	/**
	 *	getNodeAttribute
	 *
	 *	Returns node attribute value if exists.
	 *
	 *	@param string $attribute Attribute name.
	 *	@param bool $is_optional Optional parameter, defines whether attribute is optional or not.
	 *
	 *	@throws Exceptions\ParserException
	 *
	 *	@return null|string
	 */
	protected function getNodeAttribute($attribute, $is_optional = false) {
		
		// Return null if current node is not set
		if($this->current_node === null) {

			return null;

		}
		
		// Get current node
		$node = $this->current_node;
		
		// Validate if node attribute 
		$has_attribute = $node->hasAttribute($attribute);
		
		// Throw exception if node attribute does not exist and is required
		if($has_attribute === false && $is_optional === false) {
		
			throw new Exceptions\ParserException(
				"Could not return node attribute value.",
				"Node attribute '{$attribute}' does not exist.",
				__METHOD__, Exceptions\ParserException::UNEXPECTED_RESULT_EXCEPTION
			);
		
		}
		
		// Return attribute value
		return $node->getAttribute($attribute);
	
	}

	/**
	 *	getNodeValue
	 *
	 *	Returns node value if exists.
	 *
	 *	@param bool $is_optional Optional parameter, defines whether attribute is optional or not.
	 *
	 *	@throws Exceptions\ParserException
	 *
	 *	@return null|string
	 */
	protected function getNodeValue($is_optional = false) {
		
		// Return null if current node is not set
		if($this->current_node === null) {

			return null;

		}
		
		// Get current node
		$node = $this->current_node;
		
		// Get node value
		$value = $node->nodeValue;
		
		// Throw exception if node value is empty and required
		if($value === '' && $is_optional === false) {
		
			throw new Exceptions\ParserException(
				"Could not return node value.",
				"Node value is empty, and required.",
				__METHOD__, Exceptions\ParserException::UNEXPECTED_RESULT_EXCEPTION
			);
		
		}
		
		// Return node value
		return $value;
	
	}

	/**
	 *	getAttribute
	 *
	 *	Returns attribute value of current node, if exist.
	 *
	 *	@param string $attribute Attribute name.
	 *	@param bool $is_optional Optional parameter, specifies whether attribute is optional or not.
	 *	@param string $regex Optional parameter, attribute value regex test.
	 *
	 *	@throws Exceptions\ParserException
	 *
	 *	@return mixed
	 */
	public function getAttribute($attribute, $is_optional = false, $regex = null) {
	
		// Return null if current node is not set
		if($this->current_node === null) {

			return null;

		}
		
		// Get attribute value
		$data = $this->getNodeAttribute($attribute, $is_optional);
		
		// Throw exception if attribute value did not pass regex test
		if($is_optional === false && is_string($regex) === true && preg_match($regex, $data) === 0) {
		
			throw new Exceptions\ParserException(
				"Could not return node attribute value.",
				"Node attribute value did not pass RegExp test.",
				__METHOD__, Exceptions\ParserException::EMPTY_RESULT_EXCEPTION
			);
		
		}
		
		// Return attribute
		return $data;
	
	}

	/**
	 *	getValue
	 *
	 *	Returns value of current node, if exists.
	 *
	 *	@param bool $is_optional Optional parameter, specifies whether attribute is optional or not.
	 *	@param string $regex Optional parameter, node value regex test.
	 *
	 *	@throws Exceptions\ParserException
	 *
	 *	@return mixed
	 */
	public function getValue($is_optional = false, $regex = null) {
	
		// Return null if current node is not set
		if($this->current_node === null) {

			return null;

		}
		
		// Get attribute value
		$data = $this->getNodeValue($is_optional);
		
		// Throw exception if attribute value did not pass regex test
		if($is_optional === false && is_string($regex) === true && preg_match($regex, $data) === 0) {
		
			throw new Exceptions\ParserException(
				"Could not return node value.",
				"Node value did not pass RegExp test.",
				__METHOD__, Exceptions\ParserException::EMPTY_RESULT_EXCEPTION
			);
		
		}
		
		// Return attribute
		return $data;
	
	}

}
?>