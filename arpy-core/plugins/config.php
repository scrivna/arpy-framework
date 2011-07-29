<?php
class Config {
	
	static $data = array();
	
	function set($key,$value){
		self::$data[$key] = $value;
	}
	
	function get($key){
		if (!array_key_exists($key,self::$data)) return false;
		return self::$data[$key];
	}
}
?>