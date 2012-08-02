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

/* @namespace InternalFactory */
namespace Architect\Core\InternalFactory;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	InternalFactory
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
class InternalFactory extends InternalFactoryAbstract {

	/**
	 *	Constructor
	 *
	 *	Invokes constructor from {@see InternalFactoryAbstract}, does not accept any parameters, passes in an empty array as factory store.
	 *
	 *	@return void
	 */
	public function __construct() {

		// Invoke parent constructor
		parent::__construct(array());

	}

}