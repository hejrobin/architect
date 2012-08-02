<?php
/* Required namespace */
namespace app\Controller;

class Teapot implements \Architect\Application\Controller {

	public function index() {
	
		echo af_render_internal_view('Default.php');
		
	}

	public function error() {
	
		echo af_render_internal_http_status_view(404);
	
	}

}
?>