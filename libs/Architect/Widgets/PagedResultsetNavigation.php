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

/* @namespace Widgets */
namespace Architect\Widgets;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	PagedResultsetNavigation
 *
 *	Renderes a navigation object for a paged resultset.
 *
 *	@package Widgets
 *
 *	@dependencies \Architect\Data\FragmentCoordinates
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class PagedResultsetNavigation implements Widget {

	/**
	 *	@var string $navigation_url_base Item base URL.
	 */
	protected $item_base_url;

	/**
	 *	@var int $navigation_item_range Items range, number of items to render on each side of current item.
	 */
	protected $item_range;

	/**
	 *	@var \Architect\Data\FragmentCoordinates $coordinates Instance of {@see \Architect\Data\FragmentCoordinates}.
	 */
	protected $coordinates;

	/**
	 *	Constructor
	 *
	 *	Sets item base URL and item range.
	 *
	 *	@param string $item_base_url Item base URL.
	 *	@param int $item_range Items range, number of items to render on each side of current item.
	 *
	 *	@reutn void
	 */
	public function __construct(\Architect\Data\FragmentCoordinates $coordinates, $item_base_url, $item_range = 4) {

		$this->coordinates = $coordinates;

		$this->item_base_url = $item_base_url;

		$this->item_range = intval($item_range);

	}

	/**
	 *	getPageOffsets
	 *
	 *	Returns current page and total pages.
	 *
	 *	@return object
	 */
	public function getPageOffsets() {

		return (object) array(
			'current_page' => $this->coordinates->getCursor(),
			'total_pages' => $this->coordinates->getSegments()
		);

	}

	/**
	 *	render
	 *
	 *	Renderers navigation based on {@see \Architect\Data\FragmentCoordinates} object and returns an array with anchors.
	 *
	 *	@return array
	 */
	public function render() {

		$output = array();

		$coordinates = $this->coordinates;

		if($coordinates->getLength() > $coordinates->getLimit()) {

			if($coordinates->getCursor() >= 1) {

				$is_disabled = ($coordinates->getCursor() > 1) ? '' : 'disabled ';

				$previous_cursor = $coordinates->getCursor() - 1;

				if($previous_cursor <= 0) {

					$previous_cursor = 1;

				}

				$output[] = '<a href="' . af_get_uri_route("{$this->item_base_url}/1") . '" class="' . $is_disabled  . 'first-page"><span>First</span></a>';
				$output[] = '<a href="' . af_get_uri_route("{$this->item_base_url}/{$previous_cursor}") . '" class="' . $is_disabled  . 'previous-page"><span>&lt;</span></a>';

			}

			$output_pages = array();

			for($n = ($coordinates->getCursor() - $this->item_range); $n < (($coordinates->getCursor() + ($this->item_range * 2)) + 1); $n++) {

				if(($n > 0) && ($n <= $coordinates->getLength())) {

					$is_current = ($n == $coordinates->getCursor()) ? ' class="current"' : '';

					if($n >= ($coordinates->getCursor() - ($this->item_range + 1)) && $n <= $coordinates->getCursor() + ($this->item_range * 2) && count($output_pages) <= ($this->item_range * 2) && $n <= $coordinates->getSegments()) {

						$output_pages[] = '<a href="' . af_get_uri_route("{$this->item_base_url}/{$n}") . '"' . $is_current . '>' . $n . '</a>';

					}

				}

			}

			$output = array_merge($output, $output_pages);

			if($coordinates->getCursor() !== $coordinates->getSegments()) {

				$is_disabled = ($coordinates->getCursor() == $coordinates->getSegments()) ? 'disabled ' : '';

				$next_cursor = $coordinates->getCursor() + 1;

				if($next_cursor > $coordinates->getSegments()) {

					$next_cursor = $coordinates->getSegments();

				}

				$output[] = '<a href="' . af_get_uri_route("{$this->item_base_url}/{$next_cursor}") . '" class="' . $is_disabled  . 'next-page"><span>&gt;</span></a>';
				$output[] = '<a href="' . af_get_uri_route("{$this->item_base_url}/" . $coordinates->getSegments()) . '" class="' . $is_disabled  . 'last-page"><span>Last</span></a>';

			}

		}

		return implode(' ', $output);

	}

}
?>