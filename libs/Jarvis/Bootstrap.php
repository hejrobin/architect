<?php
/**
 *	Jarvis
 *
 *	Jarvis is a lightweight profiling and system preformance analyzing libary.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://kodlabbet.net/
 *
 *	@license http://www.opensource.org/licenses/MIT MIT License
 */

/* @namespace Jarvis */
namespace Jarvis;

/**
 *	@const string JARVIS_ROOT_PATH
 */
define('JARVIS_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 *	Bootstrap
 */
require_once(JARVIS_ROOT_PATH . 'Data' . DIRECTORY_SEPARATOR . 'Store.php');

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'Benchmark.php');
\Jarvis\Benchmark::register();

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'Memory.php');
\Jarvis\Memory::register();

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'Console.php');
\Jarvis\Console::register();

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'Constant.php');
\Jarvis\Constant::register();

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'File.php');
\Jarvis\File::register();

require_once(JARVIS_ROOT_PATH . 'Logs' . DIRECTORY_SEPARATOR . 'Runtime.php');
\Jarvis\Runtime::register();



/**
 *	readable_time
 *
 *	Returns a human readable time.
 *
 *	@param int|float $time Time in seconds or milliseconds.
 *
 *	@return string
 */
function readable_time($time, $precision = 3) {

	$time_formats = array('ms', 's', 'm');
	
	$time_format = 0;
	
	$time_diff = $time;
	
	if($time >= 1000 && $time < 60000) {

		$time_format = 1;

		$time_diff = ($time / 1000);

	}
	
	if($time >= 60000) {

		$time_format = 2;
	
		$time_diff = ($time / 1000) / 60;

	}

	$formatted = number_format($time_diff, $precision, '.', '');
	
	$format = $time_formats[$time_format];

	return "{$formatted} {$format}";

}

/**
 *	readable_bytesize
 *
 *	Returns readable filesize.
 *
 *	@param int $size Object size in bytes.
 *	@param string $byte_format Optional parameter, output format.
 *
 *	@return string
 */
function readable_bytesize($size, $byte_format = null) {
	
	$byte_sizes =  array('bytes', 'KB', 'MB', 'GB', 'TB');

	if ($byte_format === null) {

		$byte_format = '%01.2f %s';

	}
	
	$last_byte_size = end($byte_sizes);
	
	foreach($byte_sizes as $byte_size) {
	
		if($size < 1024) {

			break;

		}

		if($byte_size != $last_byte_size) {
		
			$size /= 1024;

		}

	}
	
	if($byte_size == $byte_sizes[0]) {

		$byte_format = '%01d %s';

	}

	$readable_size = sprintf($byte_format, $size, $byte_size);

	return $readable_size;

}

/**
 *	get_logs
 *
 *	Outputs log data.
 *
 *	@return string
 */
function get_logs() {

	echo '<pre style="font:13px monaco, monospace;">';

	print_r(Console::getLogs());

	echo '</pre>';

}
?>