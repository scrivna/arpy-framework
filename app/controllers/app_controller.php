<?php
class AppController extends Controller_Core {
	
	function __construct(){
		parent::__construct();
		
		// start a new view here so we can set view data across all pages
		$this->view = new View;
		$this->view->set(array('meta_desc'=>'meta desc', 'meta_keywords'=>'meta keywords'));
	}
	
	// catch requests not handled by any other controller / action
	function __call($name, $args){
		
		// probably want to throw a 404 here
		$this->respond404();
		
	}
	
	// outputs a 404 response then dies
	function respond404($data = null){
		
		header('HTTP/1.0 404 Not Found');
		$view = new View;
		if ($data) $view->set($data);
		$view->renderInTemplate('errors/404.php');
		exit();
		
	}
	
	function respondError($data=false){
		
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: 30'); // x seconds
		
		$view = new View;
		if (is_array($data)) $view->set($data);
		$view->renderInTemplate('errors/down.php');
		exit();
		
	}
	
}
?>