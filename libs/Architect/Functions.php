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

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

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

	switch($type) {

		case 'alnum' :

		case 'numeric' :

		case 'nozero' :

		case 'salt' :
	
			switch($type) {
	
				case 'alnum' :

					$char_pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

				break;

				case 'numeric' :

					$char_pool = '0123456789';

				break;
			
				case 'nozero' :
			
					$char_pool = '123456789';
					
				break;

				case 'salt' :
		
					$char_pool = '!@#$%^&*()_+=-{}][;";/?<>.,';
	
				break;
			}
	
			$string = '';
	
			for($n = 0; $n < $length; $n++) {
			
				$string .= substr($char_pool, mt_rand(0, strlen($char_pool) -1), 1);
			
			}
	
			return $string;
	
		break;

		case 'unique' :

			return sha1(uniqid(mt_rand()));

		break;

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

			header("Refresh: {$delay}; URL={$uri}");

		break;

		case 'location' :

			$status_code = (array_key_exists('http_status_code', $params) && isset($params['http_status_code'])) ? $params['http_status_code'] : 302;

			header("Location: {$uri}", true, $status_code);

			exit();

		break;

		case 'javascript' :

			$delay = $delay = (array_key_exists('delay', $params) && isset($params['delay'])) ? intval($params['delay']) * 1000 : 0;

			echo '<script type="text/javascript">setTimeout(function() { window.location = \'' . $uri . '\'; }, ' . $delay . ');</script>';
	
		break;

	}

}

/**
 *	af_render_view
 *
 *	Helper function to quickly render views.
 *
 *	@param string $view_file View file name.
 *	@param array $variables View variables.
 *	@param string $include_path Optional parameter, view file include path.
 *	@param string $renderer Optional parameter, renderer mode.
 *
 *	@return string
 */
function af_render_view($view_file, $variables, $include_path = null, $renderer = 'PHP') {

	$view = call_user_func_array(array(new \ReflectionClass('\Architect\Views\\' . $renderer . '\\View'), 'newInstance'), array());
	
	if(is_string($include_path) === true && is_dir($include_path) === true) {
	
		$view->setIncludePath($include_path);
	
	}
	
	$view->setViewFile($view_file);
	
	$view->setVariables($variables);
	
	return $view->render();

}

/**
 *	af_debug_console
 *
 *	Outputs Jarvis-console if Jarvis exists and debug mode is enabled.
 *
 *	@return void
 */
function af_debug_console() {

	$jarvis_console = ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR . 'Jarvis.php';

	if(ARCH_DEBUG_MODE === true && file_exists($jarvis_console)) {
	
		require_once $jarvis_console;
	
	}
}
?>