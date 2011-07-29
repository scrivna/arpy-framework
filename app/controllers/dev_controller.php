<?php
class DevController extends AppController {

	function __construct(){
		parent::__construct();
		
		// only respond if in debug mode
		if (!Config::get('debug')) $this->respond404();
	}
	
	public function info(){
		phpinfo();
	}
	
}
?>