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

/* @namespace InternalFactory */
namespace Architect\Core\InternalFactory;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	InternalFactoryObject
 *
 *	Inherits from {@see InternalFactoryAbstract}.
 *
 *	@package Core
 *	@subpackage InternalFactory
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class InternalFactoryObject extends InternalFactoryAbstract {

	/**
	 *	Constructor
	 *
	 *	Invokes constructor from {@see InternalFactoryAbstract}.
	 *
	 *	@param array $store Factory store array, contains class instance references.
	 *	@param string $identifier Instance identifier.
	 *	@param InternalFactoryAbstract $parent Reference to parent class, an instance of InternalFactoryAbstract.
	 *
	 *	@return void 
	 */
	public function __construct(array $store, $identifier = null, $parent = null) {

		// Invoke parent constructor
		parent::__construct($store, $identifier, $parent);

	}

}
?>