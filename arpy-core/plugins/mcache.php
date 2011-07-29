<?php
// cache data in memory
class MCache {
	
	static $set_keys;
	static $fetched_keys;
	
	// expires = seconds until it expires eg 86400
	function set($key,$value,$expires=0){
		self::$set_keys[] = $key;
		return apc_store($key, $value, $expires);
	}
	
	function get($key){
		self::$fetched_keys[] = $key;
		return apc_fetch($key);
	}
	
	// increase a value, returns new value
	function inc($key, $step=1){
		self::$set_keys[] = $key;
		return apc_inc($key, $step);
	}
	
	// decrease a value, returns new value
	function dec($key, $step=1){
		self::$set_keys[] = $key;
		return apc_dec($key, $step);
	}
	
	public function clear($key){
		return apc_delete($key);
	}
}
?>