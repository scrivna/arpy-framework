<?php
// cache data to disk
class FCache {
	
	private $data;
	static $set_keys;
	static $fetched_keys;
	
	// expires = seconds until it expires eg 86400
	function set($key,$value,$expires=null){
		$_this =& self::getInstance();
		
		$content = array();
		$content['key'] 	= $key;
		$content['created'] = time();
		$content['expires'] = time()+$expires;
		$content['data'] 	= $value;
		file_put_contents($_this->cache_dir.$key, json_encode($content));
		
		self::$set_keys[] = $key;
	}
	
	function get($key){
		$_this =& self::getInstance();
		if (!file_exists($_this->cache_dir.$key) || !is_readable($_this->cache_dir.$key)){
			return false;
		}
		
		$content = file_get_contents($_this->cache_dir.$key);
		$content = json_decode($content,true);
		if ($content['expires'] < time()){
			// remove the cached file
			unlink($_this->cache_dir.$key);
			return false;
		}
		self::$fetched_keys[] = $key;
		return $content['data'];
	}
	
	public function clear($key){
		$_this =& self::getInstance();
		if (!file_exists($_this->cache_dir.$key) || !is_readable($_this->cache_dir.$key)){
			return;
		}
		unlink($_this->cache_dir.$key);
	}
	
	function &getInstance(){ // this implements the 'singleton' design pattern.
    
        static $instance;
        
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c;
            $instance->cache_dir = 'var/cache/';
            $instance->set_keys = array();
            $instance->fetched_keys = array();
        }
        return $instance;
    }
	
	
}
?>