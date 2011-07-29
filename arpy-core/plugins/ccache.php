<?php
// class cache
// loads classes and keeps a single instance in memory for this request
// CCache::Model('YourModelName')->doSomething();
class CCache {
	
	static $data = array();
	
	function controller($key){
		if (array_key_exists($key,self::$data)){
			return self::$data[$key];
		}
		$class = $key.'Controller';
		self::$data[$key] = new $class;
		return self::$data[$key];
	}
	
	function model($key){
		if (array_key_exists($key,self::$data)){
			return self::$data[$key];
		}
		$class = $key.'Model';
		self::$data[$key] = new $class;
		return self::$data[$key];
	}
	
	function plugin($key, $cache=true){
		if (array_key_exists($key,self::$data)){
			return self::$data[$key];
		}
		$class = $key;
		self::$data[$key] = new $class;
		return self::$data[$key];
	}
}
?>