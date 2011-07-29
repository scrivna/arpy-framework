<?php
class TestModel extends AppModel {
	
	function __construct(){
		parent::__construct();
		$this->name = 'test';
		$this->table = 'test';
	}	
}
?>