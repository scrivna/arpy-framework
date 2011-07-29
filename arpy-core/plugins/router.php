<?php
class Router {
	
	function &getInstance(){ // implement the singleton design pattern
    
        static $instance;
        
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c;
        }
        return $instance;
    }
	
	function init(){
		$_this =& self::getInstance();
		
		global $argv, $argc;
		
		$_this->is_cli = false;
		
		if (defined('STDIN')){
			$_this->url = $argv[1];
			$_this->is_cli = true;
			
			$_this->controller = 'background';
			$_this->action = 'cli';
			$_this->url_parts = array('','background','cli');
			
			return;
			
		} else {
			$_this->url = substr($_SERVER['QUERY_STRING'],4);
		}
		
		$data = explode('&',$_this->url);
		
		$parts = explode('/','/'.$data[0]);
		$parts = array_filter($parts);
		
		for($i=1;$i<count($data);$i++){
		
			if (!empty($data[$i])){
				$bit = explode('=',$data[$i]);
				if (isset($bit[1])){
					$parts[$bit[0]] = $bit[1];
				}
			}
		}
		foreach ($parts as $key => $part){
			if (stristr($part,':')){
				$a = explode(':',$part);
				$parts[$a[0]] = $a[1];
				unset($parts[$key]);
			}
		}
		
		$parts['full'] = $_this->full_url();
		$_this->url_parts = $parts;
		
		
		$_this->controller = !$_this->part(1) ? 'page' : $_this->part(1);
		$_this->action = ($_this->part(2)) ? $_this->part(2) : 'index';
		
		require_once('config/routes.php');
	}
	
	function controller(){
		$_this =& self::getInstance();
		return $_this->controller;
	}
	
	function action(){
		$_this =& self::getInstance();
		return $_this->action;
	}
	
	// return part of a string, given the numeric placement of that string in the url
	public function part($part){
		$_this =& self::getInstance();
		if (array_key_exists($part,$_this->url_parts)){
			return $_this->url_parts[$part];
		}
		return false;
	}
	
	public function full_url(){
		$_this =& self::getInstance();
		
		if ($_this->is_cli) return false;
		
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}
}
?>