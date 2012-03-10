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
 *	UserAgent
 *
 *	Parses and resolves information from a user agent string.
 *
 *	@package HTTP
 *
 *	@version 1.0.0
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 */
class UserAgent {

	/**
	 *	@var string REGEX_PLATFORM_UNIX Unix platform.
	 */
	const REGEX_PLATFORM_UNIX = '#^(?!.*linux).*x11.*$#i';

	/**
	 *	@var string REGEX_PLATFORM_LINUX Linux platform.
	 */
	const REGEX_PLATFORM_LINUX = '#(linux)#i';

	/**
	 *	@var string REGEX_PLATFORM_MAC Mac platform.
	 */
	const REGEX_PLATFORM_MAC = '#(mac_powerpc|macintosh|mac)([a-z\s]+)(?:os(?:\s+x)?)?\s+(?:([0-9\.\_]+))?#i';

	/**
	 *	@var string REGEX_PLATFORM_WINDOWS Windows platform.
	 */
	const REGEX_PLATFORM_WINDOWS = '#(windows)(?:\s+)?(?:(95|98|me|ce|nt|embedded\s+compact)(?:\s+)?(?:([0-9\.\_]+))?)?#i';

	/**
	 *	@var string REGEX_PLATFORM_DEVICE Device platform.
	 */
	const REGEX_PLATFORM_DEVICE = '#(android|webos|ip(?:ad|od|hone)|opera\s+mini|embedded\s+compact|ubuntu\s+mobile|maemo|windows\s+phone)#i';

	/**
	 *	@var string $user_agent User agent.
	 */
	protected $user_agent;

	/**
	 *	@var array $common_platform_runtimes Common platform runtime specifications.
	 */
	protected $common_platform_runtimes = array(
		'Unix' => array(
			'#(?:free|open|net)?bsd#i',
			'Solaris' => '#(sunos)#i'
		),
		'Linux' => array(
			'#(ubuntu|fedora)\/(?:([0-9\.\_]+))?#i',
			'#(gentoo|debian|red\s+hat)#i'
		),
		'Windows' => array(	
			'NT' => array(
				'4.0' => 'NT',
				'5.0' => '2000',
				'5.1' => 'XP',
				'5.2' => 'Server 2003',
				'6.0' => 'Vista',
				'6.1' => '7',
				'7.0' => '8'
			)
		)
	);

	/**
	 *	@var array $common_browser_vendors Common browser vendor names.
	 */
	protected $common_browser_vendors = array('MSIE', 'Firefox', 'Safari', 'Opera', 'Opera\s+Mini', 'Netscape', 'Konqueror', 'Chrome', 'Rockmelt', 'SeaMonkey');

	/**
	 *	@var array $common_browser_engines Common browser rendering engines.
	 */
	protected $common_browser_engines = array('WebKit', 'Gecko', 'Trident', 'Presto');
	
	/**
	 *	@var array $common_browser_crawlers Common web crawlers (bots).
	 */
	protected $common_browser_crawlers = array('Google', 'MSNBot', 'Bot', 'Slurp', 'Spider', 'Archiver', 'FacebookExternal', 'W3C_Validator');

	/**
	 *	@var array $common_browser_aliases Common browser aliases.
	 */
	protected $common_browser_aliases = array(
		'MSIE' => 'Internet Explorer'
	);

	/**
	 *	@var string $source User agent source. 
	 */
	protected $source;

	/**
	 *	@var string $platform Platform name. 
	 */
	protected $platform;
	
	/**
	 *	@var string $$platform_runtime Platform runtime. 
	 */
	protected $platform_runtime;
	
	/**
	 *	@var string $platform_version Platform version. 
	 */
	protected $platform_version;

	/**
	 *	@var string $browser_vendor Browser vendor name. 
	 */
	protected $browser_vendor;
	
	/**
	 *	@var string $browser_version Browser version. 
	 */
	protected $browser_version;
	
	/**
	 *	@var string $browser_engine Browser rendering engine. 
	 */
	protected $browser_engine;
	
	/**
	 *	@var bool $is_bot Specifies whether browser is a bot. 
	 */
	protected $is_bot = false;
	
	/**
	 *	@var bool $is_device Specifies whether browser running on a mobile device. 
	 */
	protected $is_device = false;

	/**
	 *	Constructor
	 *
	 *	Sets browser user agent.
	 *
	 *	@return void
	 */
	public function __construct($user_agent = null) {
	
		$this->parse($user_agent);
	
	}

	/**
	 *	setUserAgent
	 *
	 *	Sets defined user agent, or uses clients current user agent.
	 *
	 *	@param string $user_agent Optional parameter, user agent.
	 *
	 *	@return void
	 */
	public function setUserAgent($user_agent = false) {
	
		if(is_string($user_agent) === true) {
		
			$this->user_agent = $user_agent;
		
		} else {
		
			$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		}
	
	}

	/**
	 *	getUserAgent
	 *
	 *	Returns user agent.
	 *
	 *	@return string
	 */
	public function getUserAgent() {
	
		return $this->user_agent;
	
	}

	/**
	 *	parsePlatform
	 *
	 *	Parses platform information.
	 *
	 *	@return void
	 */
	public function parsePlatform() {
	
		$this->platform = 'Unknown';
		
		if(preg_match(self::REGEX_PLATFORM_UNIX, $this->getUserAgent(), $matches) === 1) {
		
			$this->platform = 'Unix';
			
			if(array_key_exists($this->platform, $this->common_platform_runtimes) === true) {
				
				$common_platform_runtimes = $this->common_platform_runtimes[$this->platform];
				
				foreach($common_platform_runtimes as $key => $regex_platform_runtime) {

					if(preg_match($regex_platform_runtime, $this->getUserAgent(), $matches) === 1) {
					
						if(isset($matches[1]) === true) {
						
							$this->platform_runtime = trim($matches[1]);
							
							if(is_string($key) === true) {
							
								$this->platform_runtime = $key;
							
							}
						
						}
					
					}
				
				}
			
			}
		
		}
		
		if(preg_match(self::REGEX_PLATFORM_LINUX, $this->getUserAgent(), $matches) === 1) {
		
			$this->platform = 'Linux';
			
			if(array_key_exists($this->platform, $this->common_platform_runtimes) === true) {
				
				$common_platform_runtimes = $this->common_platform_runtimes[$this->platform];
				
				foreach($common_platform_runtimes as $key => $regex_platform_runtime) {

					if(preg_match($regex_platform_runtime, $this->getUserAgent(), $matches) === 1) {
					
						if(isset($matches[1]) === true) {
						
							$this->platform_runtime = trim($matches[1]);
							
							if(is_string($key) === true) {
							
								$this->platform_runtime = $key;
							
							}
						
						}
						
						if(isset($matches[2]) === true) {
						
							$this->platform_version = trim($matches[2]);

						}
					
					}
				
				}
			
			}
		
		}
		
		if(preg_match(self::REGEX_PLATFORM_MAC, $this->getUserAgent(), $matches) === 1) {
		
			$this->platform = 'Mac';
			
			if(isset($matches[2]) === true) {
			
				$this->platform_runtime = trim($matches[2]);
				
				if(isset($matches[3]) === true) {
				
					$this->platform_version = str_replace('_', '.', trim($matches[3]));
	
				}
			
			}
		
		}
		
		if(preg_match(self::REGEX_PLATFORM_WINDOWS, $this->getUserAgent(), $matches) === 1) {
		
			$this->platform = 'Microsoft';
			
			$this->platform_runtime = 'Windows';
			
			if(isset($matches[2]) === true) {
			
				$this->platform_version = trim($matches[2]);
				
				if(array_key_exists($this->platform_runtime, $this->common_platform_runtimes) === true) {
				
					$common_platform_runtimes = $this->common_platform_runtimes[$this->platform_runtime];
					
					if(array_key_exists($this->platform_version, $common_platform_runtimes) === true) {
						
						$common_platform_runtimes = $common_platform_runtimes[$this->platform_version];
												
						if(isset($matches[3]) === true) {
							
							$this->platform_version = $common_platform_runtimes[trim($matches[3])];
						
						}

					}
				
				}
			
			}
		
		}
	
		if(preg_match(self::REGEX_PLATFORM_DEVICE, $this->getUserAgent(), $matches) === 1) {
		
			$this->is_device = true;
			
			if(preg_match('#ip(?:ad|od|hone)#i', $this->getUserAgent()) === 1) {
			
				$this->platform_runtime = 'iOS';
				
				if(preg_match('#([0-9\.\_]+)#', $this->getUserAgent(), $matches) === 1) {
				
					$this->platform_version = str_replace('_', '.', trim($matches[0]));
				
				}
			
			}
			
			if(preg_match('#android(?:\s+([0-9\.]+))#i', $this->getUserAgent(), $matches) === 1) {
			
				$this->platform_runtime = 'Android';
				
				if(isset($matches[1]) === true) {
				
					$this->platform_version = trim($matches[1]);
				
				}
			
			}
			
			if(preg_match('#webos\/([0-9\.]+)#i', $this->getUserAgent(), $matches) === 1) {
				
				$this->platform = 'Linux';
				
				$this->platform_runtime = 'WebOS';
				
				if(isset($matches[1]) === true) {
				
					$this->platform_version = trim($matches[1]);
				
				}
			
			}
			
		}
	
	}

	/**
	 *	parseBrowser
	 *
	 *	Parses and sets browser information.
	 *
	 *	@return void
	 */
	protected function parseBrowser() {
	
		// Validate browser vendor
		$regex_browser_vendor = '#(';
		$regex_browser_vendor .= strtolower(implode('|', $this->common_browser_vendors));
		$regex_browser_vendor .= ')[/ ]+([0-9]+(?:\.[0-9]+)?)#i';
		
		preg_match_all($regex_browser_vendor, $this->getUserAgent(), $matches);
	
		// Set browser vendor name
		if(isset($matches[1]) === true && isset($matches[1][0]) === true) {
		
			$this->browser_vendor = trim($matches[1][0]);
		
		} else {
		
			$this->browser_vendor = 'Unknown';
		
		}
		
		// Set browser alias
		if(array_key_exists($this->browser_vendor, $this->common_browser_aliases) === true) {
		
			$this->browser_vendor = $this->common_browser_aliases[$this->browser_vendor];
		
		}
	
		// Set browser version
		if(isset($matches[2]) === true && isset($matches[2][0]) === true) {
		
			$this->browser_version = trim($matches[2][0]);
		
		}
		
		// Validate browser engine
		$regex_browser_engine = '#(';
		$regex_browser_engine .= strtolower(implode('|', $this->common_browser_engines));
		$regex_browser_engine .= ')#i';
		
		preg_match($regex_browser_engine, $this->getUserAgent(), $matches);
		
		if(isset($matches[1]) === true) {
		
			$this->browser_engine = trim($matches[1]);
		
		}
	
		// Validate web crawler
		$regex_browser_crawler = '#(';
		$regex_browser_crawler .= strtolower(implode('|', $this->common_browser_crawlers));
		$regex_browser_crawler .= ')#i';
		
		if(preg_match($regex_browser_crawler, $this->getUserAgent()) === 1) {
		
			$this->is_bot = true;
		
		}
	
	}

	/**
	 *	parse
	 *
	 *	Parses user agent.
	 *
	 *	@param string $user_agent User agent string.
	 *
	 *	@return string
	 */
	public function parse($user_agent = null) {
		
		$this->setUserAgent($user_agent);
		
		$this->parsePlatform();
		
		$this->parseBrowser();
	
	}

	/**
	 *	isBot
	 *
	 *	Returns whether current user agent string is from a bot.
	 *
	 *	@return bool
	 */
	public function isBot() {
	
		return $this->is_bot;
	
	}

	/**
	 *	isDevice
	 *
	 *	Returns whether current user agent string is from a mobile device.
	 *
	 *	@return bool
	 */
	public function isDevice() {
	
		return $this->is_device;
	
	}

	/**
	 *	getDevice
	 *
	 *	Attempts to determine device name, and returns it's name.
	 *
	 *	@return string|null
	 */
	public function getDevice() {
	
		if($this->isDevice() === true) {
		
			if(preg_match('#android#i', $this->getUserAgent()) === 1) {
			
				return 'Android';
			
			}
			
			if(preg_match('#ip(?:ad|od|hone)#i', $this->getUserAgent(), $matches) === 1) {
			
				if(isset($matches[0]) === true) {
				
					return trim($matches[0]);
				
				}
			
			}
			
			if(preg_match('#windows\s+phone\s+os\s+([0-9\.]+)#i', $this->getUserAgent(), $matches) === 1) {
			
				if(isset($matches[1]) === true) {
					
					$this->platform_version = 'Phone ' . trim($matches[1]);
					
					return 'Windows Phone';
				
				}
			
			}
		
			return 'Unknown';
		
		}
	
		return null;
	
	}

	/**
	 *	getSource
	 *
	 *	Returns source of user agent string.
	 *
	 *	@return string
	 */
	public function getSource() {
	
		$this->source = 'Desktop';
		
		if($this->isDevice() === true) {
		
			$this->source = 'Device';
		
		}
		
		if($this->isBot() === true) {
		
			$this->source = 'Bot';
		
		}
	
		return $this->source;
	
	}

	/**
	 *	getPlatform
	 *
	 *	Returns platform name.
	 *
	 *	@return string
	 */
	public function getPlatform() {
	
		return $this->platform;
	
	}

	/**
	 *	getRuntime
	 *
	 *	Returns platform runtime (operating system).
	 *
	 *	@return string
	 */
	public function getRuntime() {
	
		return $this->platform_runtime;
	
	}

	/**
	 *	getRuntimeVersion
	 *
	 *	Returns platform runtime version, may be version name.
	 *
	 *	@return string
	 */
	public function getRuntimeVersion() {
	
		return $this->platform_version;
	
	}

	/**
	 *	getBrowser
	 *
	 *	Returns browser vendor name.
	 *
	 *	@return string
	 */
	public function getBrowser() {
	
		return $this->browser_vendor;
	
	}

	/**
	 *	getBrowserVersion
	 *
	 *	Returns browser version.
	 *
	 *	@return string
	 */
	public function getBrowserVersion() {
	
		return $this->browser_version;
	
	}

	/**
	 *	getBrowserEngine
	 *
	 *	Returns browser engine.
	 *
	 *	@return string
	 */
	public function getBrowserEngine() {
	
		return $this->browser_engine;
	
	}

}
?>