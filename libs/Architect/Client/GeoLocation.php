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

/* @namespace Client */
namespace Architect\Client;

/* Deny direct file access */
if(!defined('ARCH_ROOT_PATH')) exit;

/**
 *	GeoLocation
 *
 *	Attempts to find the location of a client based on IP address. Utilizes geoplugin.com.
 *
 *	@link http://www.geoplugin.com/
 *
 *	@package Client
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class GeoLocation {

	/**
	 *	@var string $request_uri Request URI.
	 */
	private $request_uri = 'http://www.geoplugin.net/php.gp?ip=%s';

	/**
	 *	@var float $latitude Location latitude.
	 */
	private $latitude = 0;
	
	/**
	 *	@var float $longitude Location longitude.
	 */
	private $longitude = 0;
	
	/**
	 *	@var array $location_data Location data.
	 */
	private $location_data = array(
		'city' => null,
		'region' => null,
		'country' => null,
		'country_code' => null
	);

	/**
	 *	locate
	 *
	 *	Sends request to geoplugin.com and resolves location data.
	 *
	 *	@param string $ip IP address.
	 *
	 *	@return void
	 */
	public function locate($ip) {
	
		$request_uri = new \Architect\URI\Schemes\HTTP();

		$request_uri->parse(sprintf($this->request_uri, $ip, $this->request_uri));

		$request = new \Architect\HTTP\Request();
		
		$request->setRequestURI($request_uri);
		
		$response = $request->send();
		
		$location_data = (object) unserialize($response->getData());
		
		$this->latitude = $location_data->geoplugin_latitude;
		
		$this->longitude = $location_data->geoplugin_longitude;
		
		$this->location_data = array(
		
			'city' => $location_data->geoplugin_city,
			
			'region' => $location_data->geoplugin_region,
		
			'country' => $location_data->geoplugin_countryName,
		
			'country_code' => $location_data->geoplugin_countryCode
		
		);

	}

	/**
	 *	getCoordinates
	 *
	 *	Returns geolocation coordinates.
	 *
	 *	@return array
	 */
	public function getCoordinates() {
	
		return array(
			'latitude' => $this->latitude,
			'longitude' => $this->longitude
		);
	
	}

	/**
	 *	getLocationData
	 *
	 *	Return location data.
	 *
	 *	@return array
	 */
	public function getLocationData() {
	
		return $this->location_data;
	
	}

}
?>