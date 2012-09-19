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

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	af_dump
 *
 *	Wraps a var_dump within <pre>-elements.
 *
 *	@return string
 */
function af_dump() {

	echo '<pre style="font: 14px monaco, monospace;">';

	return call_user_func_array('var_dump', func_get_args());

	echo '</pre>';

}

/**
 *	af_randstr
 *
 *	Creates a random string based on character pool and length.
 *
 *	@param string $type Return string type.
 *	@param int $length Return string length.
 *
 *	@return string
 */
function af_randstr($type = 'alnum', $length = 8) {

	// Unique random string
	$char_pool_unique = sha1(uniqid(mt_rand()));

	// Numeric characters
	$char_pool_numeric = '0123456789';

	// Numeric character without zeroes
	$char_pool_nozero = substr($char_pool_numeric, 1);

	// Lowercase alphabetic characters
	$char_pool_alpha = 'abcdefghijklmnopqrstuvwxyz';

	// Uppercase alphabetic characters
	$char_pool_alpha_uppercase = strtoupper($char_pool_alpha);

	// Salt characters
	$char_pool_salt = '!@#$%^&*()_+=-{}][;";/?<>.,';

	// Create unique string
	if($type === 'unique') {

		return $char_pool_unique;

	} else {

		// Handle character pools
		switch ($type) {

			case 'alnum' :

				$char_pool = $char_pool_alpha . $char_pool_alpha_uppercase . $char_pool_numeric;

			break;

			case 'alnum.lowercase' :

				$char_pool = $char_pool_alpha . $char_pool_numeric;

			break;

			case 'alnum.uppercase' :

				$char_pool = $char_pool_alpha_uppercase . $char_pool_numeric;

			break;

			case 'numeric' :

				$char_pool = $char_pool_numeric;

			break;

			case 'numeric.nozero' :

				$char_pool = $char_pool_nozero;

			break;

			case 'salt' :

				$char_pool = $char_pool_unique. $char_pool_salt;

			break;

		}

		$random_string = '';

		// Generate random string based on char pool
		for($n = 0; $n < $length; $n++) {

			$random_string .= substr($char_pool, mt_rand(0, strlen($char_pool) -1), 1);

		}

		return $random_string;

	}

}

/**
 *	af_hash
 *
 *	Hashes a string with a salt as SHA-2 (SHA-512).
 *	@link http://en.wikipedia.org/wiki/SHA-2 SHA-2
 *
 *	@param string $string String to hash.
 *	@param string $salt Optional parameter, string hash salt.
 *
 *	@return string
 */
function af_hash($string, $salt = null) {

	// Generate salt
	if(!isset($salt) && !is_string($salt)) {

		$salt = af_randstr('salt', 16);

	}

	// Return hashed string
	return hash('sha512', $salt . $string);

}

/**
 *	af_uri_rewritable
 *
 *	Determines whether URIs/URLs are rewritable or not.
 *
 *	@return bool
 */
function af_uri_rewritable() {

	// Look for Apache rewrite module
	if(function_exists('apache_get_modules') === true) {

		$uri_rewritable = in_array('mod_rewrite', apache_get_modules());

	} else {

		// Check for other rewrite modules
		if(getenv('HTTP_MOD_REWRITE') === 'On') {

			$uri_rewritable = true;

		} else {

			$uri_rewritable = false;

		}

	}

	return $uri_rewritable;

}

/**
 *	af_get_uri_route
 *
 *	Return a normalized URI route, if URI's are rewriteable and rewrite options are enabled, do not prepend 'index.php'.
 *
 *	@param string $uri_route URI route path.
 *
 *	@return string
 */
function af_get_uri_route($uri_route = null) {

	// Normalize route
	$uri_route = trim(stripslashes($uri_route), '/');

	$uri_rewritable = false;

	// Validate rewrite modules
	if(af_uri_rewritable() === true && ARCH_ENABLE_URI_REWRITE === true) {

		$uri_rewritable = true;

	}

	// Append index.php if rewrite modules exist
	if($uri_rewritable === false) {

		$uri_route = "index.php/{$uri_route}";

	}

	// Strip excess slashes
	$uri_route = trim($uri_route, '/');

	// Return URI route
	return "/{$uri_route}/";

}

/**
 *	af_redirect
 *
 *	Redirects, either by a simple refresh or by changing the location header.
 *
 *	@param string $uri URI to redirect to.
 *	@param string $method Redirect method to use, 'location', 'refresh' or 'javascript'.
 *	@param array $params Array containing additional parameters to send.
 *
 *	@return void
 */
function af_redirect($uri, $method = 'location', $params = array()) {

	switch($method) {

		case 'refresh' :

			$delay = (array_key_exists('delay', $params) && isset($params['delay'])) ? $params['delay'] : 0;

			session_write_close();

			header("Refresh: {$delay}; URL={$uri}");
			exit;

		break;
		case 'location' :

			$status_code = (array_key_exists('http_status_code', $params) && isset($params['http_status_code'])) ? $params['http_status_code'] : 302;

			session_write_close();

			header("Location: {$uri}", true, $status_code);
			exit;

		break;
		case 'javascript' :

			$delay = $delay = (array_key_exists('delay', $params) && isset($params['delay'])) ? intval($params['delay']) * 1000 : 0;

			echo '<script type="text/javascript">setTimeout(function() { window.location = \'' . $uri . '\'; }, ' . $delay . ');</script>';

		break;

	}

}

/**
 *	af_hooks_register
 *
 *	Helper function to register a hook to a trigger.
 *
 *	@param \Architect\Application\Hook $hook Hook instance.
 *	@param string $trigger Hook trigger.
 *
 *	@retun void
 */
function af_hooks_register(\Architect\Application\Hook $hook, $trigger) {

	$arch = \Architect::getInstance();

	if($arch->hasInstance('hooks') === true) {

		$identifier = get_class($hook);

		$arch->hooks->registerHook($identifier, $hook);

		$arch->hooks->register($identifier, $trigger);

	}

}

/**
 *	af_hooks_invoke
 *
 *	Invokes hooks by trigger.
 *
 *	@param string $trigger Hook trigger.
 *
 *	@return void
 */
function af_hooks_invoke($trigger) {

	$arch = \Architect::getInstance();

	if($arch->hasInstance('hooks') === true) {

		$arch->hooks->invokeHooksByTrigger($trigger);

	}

}
?>