<?php
/* Required namespace */
namespace app\Controllers;

class Teapot implements \Architect\Application\Controller {

	public function index() {
	
		$arch = \Architect::getInstance();

		echo af_render_view('Teapot.php', array(
		
			'base' => $arch->uri->getBaseURI()
		
		), ARCH_INTERNAL_PATH . 'Views' . DIRECTORY_SEPARATOR);
		
	}

	public function error() {
	
		echo __METHOD__ . '<br />';
	
	}

}
?>