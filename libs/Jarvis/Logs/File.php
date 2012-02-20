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

/* Deny direct file access */
if(!defined('JARVIS_ROOT_PATH')) exit;

/**
 *	File
 *
 *	Asserts included files to store.
 *
 *	@package Logs
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class File extends Store {

	/**
	 *	@staticvar float $microtime Microtime when log store was created.
	 */
	protected static $microtime;

	/**
	 *	register
	 *
	 *	Creates store and saves microtime when log store was created.
	 *
	 *	@return void
	 */
	public function register() {
		
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		self::createStore($store_id);
		
		self::$microtime = microtime(true);
	
	}

	/**
	 *	analyze
	 *
	 *	Does nothing for console.
	 *
	 *	@return void
	 */
	public static function analyze() {
	
		$store_id = stripslashes(str_ireplace(__NAMESPACE__, '', __CLASS__));
		
		$included_file_paths = get_included_files();
		
		$file_sizes = array();
		
		foreach($included_file_paths as $file_path) {
			
			self::assertLog($store_id, array(
				'name' => basename($file_path),
				'text' => $file_path,
				'size' => filesize($file_path)
			));
			
			$file_sizes[filesize($file_path)] = $file_path;
		
		}
		
		// Get largest file
		$largest_file_size = max(array_keys($file_sizes));
		$largest_file = $file_sizes[$largest_file_size];
		
		// Get smallest file
		$smallest_file_size = min(array_keys($file_sizes));
		$smallest_file = $file_sizes[$smallest_file_size];
		
		// Save largest file
		self::$store[$store_id]['largest_file'] = array(
			'name' => $largest_file,
			'size' => $largest_file_size
		);
		
		// Save smallest file
		self::$store[$store_id]['smallest_file'] = array(
			'name' => $smallest_file,
			'size' => $smallest_file_size
		);
	
	}

}
?>