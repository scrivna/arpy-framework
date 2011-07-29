<?php
class Cookie {

	function set($key,$value){
		$expire=time()+60*60*24*7;
		setcookie($key, $value, $expire, '/');
	}
	
	function get($key){
		if (isset($_COOKIE[$key])){
			return $_COOKIE[$key];
		}
		return;
	}
	
	public function destoy($key=null){
		if ($key){
			setcookie($key, '', time()-3600);
		} else {
			foreach ($_COOKIE as $key=>$value){
				setcookie($key, '', time()-3600);
			}
		} 
		return true;
	}
}
?>