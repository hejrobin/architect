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

/* @namespace Core */
namespace Architect\Core;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	Hooks
 *
 *	Class used to hold and invoke application hooks.
 *
 *	@package Core
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class Hooks {

	/**
	 *	@var array $triggers Array containing hook triggers.
	 */
	protected $triggers = array(
		'before:applicationflow',
		'after:applicationflow',
		'before:delegation',
		'after:delegation',
		'localechange',
		'exception'
	);

	/**
	 *	@var array $hooks Array containing registered hooks.
	 */
	protected $hooks = array();

		/**
	 *	@var array $associations Array key, value pairs of triggers and hooks registered to a trigger.
	 */
	protected $associations = array();

	/**
	 *	registerHook
	 *
	 *	Registers a hook.
	 *
	 *	@param string $identifier Hook identifier.
	 *	@param \Architect\Application\Hook $hook Instance of a custom hook.
	 *
	 *	@return bool
	 */
	public function registerHook($identifier, \Architect\Application\Hook $hook) {

		if(array_key_exists($identifier, $this->hooks) === false) {

			$this->hooks[$identifier] = $hook;

			return true;

		}

		return false;

	}

	/**
	 *	unregisterHook
	 *
	 *	Unregisters a registered hook.
	 *
	 *	@param string $identifier Hook identifier.
	 *
	 *	@return bool
	 */
	public function unregisterHook($identifier) {

		if(array_key_exists($identifier, $this->hooks) === true) {

			unset($this->hooks[$identifier]);

			foreach($this->associations as $trigger => $hooks) {

				unset($hooks[$identifier]);

			}

			return true;

		}

		return false;

	}

	/**
	 *	register
	 *
	 *	Associates a hook identifier with a trigger.
	 *
	 *	@param string $identifier Hook identifier.
	 *	@param string $trigger Hook trigger.
	 *
	 *	@return bool
	 */
	public function register($identifier, $trigger) {

		if(array_key_exists($trigger, $this->triggers) === true) {

			if(array_key_exists($trigger, $this->associations) === false && is_array($this->associations[$trigger]) === false) {

				$this->associations[$trigger] = array();

			}

			if(array_key_exists($identifier, $this->hooks) === true) {

				$this->associations[$trigger][$identifier] = true;

				return true;

			}

		}

		return false;

	}

	/**
	 *	invokeHook
	 *
	 *	Invokes a hook
	 *
	 *	@param string $identifier Hook identifier.
	 *
	 *	@return mixed
	 */
	public function invokeHook($identifier) {

		if(array_key_exists($identifier, $this->hooks) === true) {

			$hook = $this->hooks[$identifier];

			return $hook->invoke();

		}

		return null;

	}

	/**
	 *	invokeHooksByTrigger
	 *
	 *	Invokes all hooks associated with input trigger.
	 *
	 *	@param string $trigger Hook trigger.
	 *
	 *	@return bool
	 */
	public function invokeHooksByTrigger($trigger) {

		if(array_key_exists($trigger, $this->associations) === true) {

			foreach($this->associations[$trigger] as $identifier => $boolean) {

				$this->invokeHook($identifier);

			}

			return true;

		}

		return false;

	}

}
?>