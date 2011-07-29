<?php
class PageController extends AppController {
	
	public function index(){
		
		if(!$dbdata = MCache::get('testdata')){
			
			//$dbdata = CCache::model('Test')->select('WHERE 1=1');
			$dbdata = 'Cached: '.date('Y-m-d H:i:s');
			MCache::set('testdata', $dbdata, 2);
			
		}
		
		$vd = array();
		$vd['title'] = 'Arpy\'s Running Sharpy';
		$vd['test'] = $dbdata;
		
		$this->view->set($vd);
		$this->view->renderInTemplate('pages/home.php');
	}
}
?>