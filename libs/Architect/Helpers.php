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
 *	af_render_internal_view
 *
 *	Helper function to quickly render internal views.
 *
 *	@param string $view_file View file name.
 *	@param array $variables Optional parameter, template variables.
 *
 *	@return string
 */
function af_render_internal_view($view_file, $variables = array()) {

	// Create view object
	$view = new \Architect\Renderers\Views\PHP\View();

	// Set include path
	$view->setIncludePath(ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);

	// Set view file
	$view->setViewFile($view_file);

	// Store variables
	$view->setVariables($variables);

	// Render and return view
	return $view->render();

}

/**
 *	af_render_internal_http_status_view
 *
 *	Helper function to quickly render HTTP status views.
 *
 *	@param int $http_status_code HTTP status code.
 *
 *	@return string
 */
function af_render_internal_http_status_view($http_status_code) {

	$arch = \Architect::getInstance();

	// Set HTTP status code
	$arch->http->setStatusCode($http_status_code);

	// Return rendered view
	return af_render_internal_view('HTTPStatus.php', array(
		'name' => $arch->http->getStatusMessage(),
		'type' => $arch->http->getStatusType(),
		'code' => $arch->http->getStatusCode(),
		'trace' => $arch->http->getHeaders()
	));

}

/**
 *	af_render_view
 *
 *	Helper function to quickly render internal views.
 *
 *	@param string $view_file View file name.
 *	@param array $variables Optional parameter, template variables.
 *	@param string $include_path Optional parameter, include path.
 *	@param int $lifetime Optional parameter, if specified, view is cached.
 *
 *	@return string
 */
function af_render_view($view_file, $variables = array(), $include_path = null, $lifetime = null) {

	$rendrer = 'View';

	if(is_int($lifetime) === true) {

		$rendrer = "CachedView";

	}

	// Create view object
	$view = call_user_func_array(array(new \ReflectionClass('\Architect\Renderers\Views\PHP\\' . $rendrer), 'newInstance'), array());

	// Set cache lifetime
	if(is_int($lifetime) === true) {

		$view->setLifetime($lifetime);

	}

	// Set include path
	if(is_string($include_path) === true) {

		$view->setIncludePath($include_path);

	}

	// Set view file
	$view->setViewFile($view_file);

	// Set view variables
	$view->setVariables($variables);

	// Render and return view
	return $view->render();

}

/**
 *	af_load_model
 *
 *	Attempts to load in a model based on current controller path.
 *
 *	@param string $model_name Name of model, without namespace (\app\Model\) and file extension (.php).
 *
 *	@throws \Architect\Application\Exceptions\ApplicationException
 *
 *	@return null|object
 */
function af_load_model($model_name = null) {

	$instance = null;

	if(defined('ARCH_COMPONENT_PATH') === true) {

		$include_path = preg_replace('~(.*)' . preg_quote('Controllers', '~') . '~', '$1' . 'Models', ARCH_CONTROLLER_PATH, 1);

		$model_name = (is_null($model_name) === true) ? ARCH_CONTROLLER_NAME : $model_name;

		$model_include_path = $include_path . $model_name . ARCH_FILE_EXTENSION;

		if(file_exists($model_include_path) === true) {

			require_once $model_include_path;

			$class_name = "\app\Model\\" . $model_name;

			$instance = call_user_func_array(array(new \ReflectionClass($class_name), 'newInstance'), array());

		} else {

			throw new \Architect\Application\Exceptions\ApplicationException(
					'Could not import module resource.',
					'Input file for this module does not exist.',
					__FUNCTION__, \Architect\Application\Exceptions\ApplicationException::UNEXPECTED_RESULT_EXCEPTION
				);

		}

	}

	return $instance;

}

/**
 *	af_number_format
 *
 *	Formats numbers based on current locale settings,
 *
 *	@param int $number Number to format.
 *	@param int $decimal Number of decimals to format.
 *
 *	@return string
 */
function af_number_format($number, $decimal = 0) {

	$arch = \Architect::getInstance();

	$decimal_point = $arch->locale->numbers->number_format_decimal_point;
	$thousand_point = $arch->locale->numbers->number_format_thousand_point;

	return number_format($number, $decimal, ($decimal_point) ? $decimal_point : '.', ($thousand_point) ? $thousand_point : ',');

}

/**
 *	af_readable_number
 *
 *	This function returns a human readable number.
 *
 *	@param int $number Number to convert into a readable number.
 *	@param bool $trim If set to true, trims output string.
 *
 *	@return string
 */
function af_readable_number($number, $trim = true) {

	$arch = \Architect::getInstance();

	$readable_separator = $arch->locale->numbers->readable_number_separator;
	$readable_neg = $arch->locale->numbers->readable_number_negative;
	$readable_zero = $arch->locale->numbers->readable_number_zero;
	$readable_hundreds = $arch->locale->numbers->readable_number_hundreds;
	$readable_lows = $arch->locale->numbers->readable_number_lows;
	$readable_mids = $arch->locale->numbers->readable_number_mids;
	$readable_high = $arch->locale->numbers->readable_number_high;

	if($number == 0) {

		return $readable_zero;

	}

	$readable_number = ($number < 0) ? $readable_neg : '';
	$number = abs($number);

	for($i = count($readable_high); $i > 0; $i--) {

		$pow = pow(1000, $i);

		if($number > $pow) {

			$tmp = floor($number / $pow);
			$number -= $pow * $tmp;
			$readable_number .= af_readable_number($tmp, false) . $readable_separator . $readable_high[$i - 1];

		}

	}

	if($number > 100) {

		$tmp = floor($number / 100);
		$number -= 100 * $tmp;
		$readable_number .= af_readable_number($tmp, false) . $readable_separator . $readable_hundreds . $readable_separator;

	}

	if($number >= 20) {

		$tmp = floor($number / 10);
		$number -= 10 * $tmp;
		$readable_number .= $readable_separator . $readable_mids[$tmp - 1];

	}

	if($number) {

		$readable_number .= $readable_separator . $readable_lows[$number - 1];

	}

	if($trim === true) {

		return preg_replace('/\s+/', ' ', $readable_number);

	}

	return $readable_number;

}
?>