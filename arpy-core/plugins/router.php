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
		
		if (defined('STDIN')){
			$_this->is_cli = true;
			$_this->url = $argv[1];
		} else {
			$_this->is_cli = false;
			$_this->url = $_SERVER['QUERY_STRING'];
		}
		
		// get parts of url
		parse_str($_this->url, $params);
		
		if (isset($params['arpy_url'])){
			// if a directory is found
			$parts = explode('/', '/'.$params['arpy_url']);
			unset($params['arpy_url']);
			$_this->url_parts = array_merge($parts, $params);
			unset($_this->url_parts[0]);
		} else {
			// no dir so just look for params
			$_this->url_parts = $params;
		}
		
		$_this->controller 	= $_this->part(1) ? $_this->part(1) : Config::get('router.default_controller');
		$_this->action 		= $_this->part(2) ? $_this->part(2) : Config::get('router.default_action');
		
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