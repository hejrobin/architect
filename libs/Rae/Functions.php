<?php
/**
 *	Rae
 *
 *	Rae ("Record-Analyze-Evolve") is a lightweight profiling and preformance analyzing library used to benchmark and analyze certain aspect of web based applications.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://kodlabbet.net
 *
 *	@license http://www.opensource.org/licenses/MIT MIT License
 */

/* @namespace Rae */
namespace Rae;

/* Deny direct file access */
if(!defined('RAE_ROOT_PATH')) exit;

/**
 *	getRecords
 *
 *	Returns array containing all stored records.
 *
 *	@return array
 */
function getRecords() {

	return \Rae\Collection::getStore();

}

/**
 *	getRecordID
 *
 *	Returns class name without current namespace (in this case "Rae").
 *
 *	@param string $class_name Class name to remove namespace from.
 *
 *	@return string
 */
function getRecordID($class_name) {

	return stripslashes(str_ireplace(__NAMESPACE__, '', $class_name));

}

/**
 *	getReadableExecutionTime
 *
 *	Converts a microtime timestamp into either milliseconds, seconds or minutes.
 *
 *	@param float $microtime Microtime timestamp.
 *	@param int $precision Conversion precision.
 *
 *	@return string
 */
function getReadableExecutionTime($microtime, $precision = 3) {

	$microtime = $microtime * 10000;

	$time_formats = array('ms', 's', 'm');

	$time_format_index = 0;

	$time_diff = $microtime;

	if($microtime >= 1000 && $microtime < 60000) {

		$time_format_index = 1;

		$time_diff = ($microtime / 1000);

	}

	if($microtime >= 60000) {

		$time_format_index = 2;

		$time_diff = ($microtime / 1000) / 60;

	}

	$formatted = number_format($time_diff, $precision, '.', '');

	$format = $time_formats[$time_format_index];

	return "{$formatted} {$format}";

}

/**
 *	getReadableByteSize
 *
 *	Returns
 *
 *	@param int $bytes Object size in bytes.
 *	@param string $byte_format Optional parameter, output format.
 *
 *	@return string
 */
function getReadableByteSize($bytes, $byte_format = null) {

	$byte_sizes =  array('bytes', 'KB', 'MB', 'GB', 'TB');

	if ($byte_format === null) {

		$byte_format = '%01.2f %s';

	}

	$last_byte_size = end($byte_sizes);

	foreach($byte_sizes as $byte_size) {

		if($bytes < 1024) {

			break;

		}

		if($byte_size != $last_byte_size) {

			$bytes /= 1024;

		}

	}

	if($byte_size == $byte_sizes[0]) {

		$byte_format = '%01d %s';

	}

	$readable_size = sprintf($byte_format, $bytes, $byte_size);

	return $readable_size;

}
?>