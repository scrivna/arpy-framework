<?php
class Auth {
	
	function set($key,$value){
	
		if (isset($_SESSION['auth'])){
			$_SESSION['auth'][$key] = $value;
		} else {
			$_SESSION['auth'] = array($key=>$value);
		}
	}
	
	function get($key=''){
		if ($key){
			if (isset($_SESSION['auth']) && isset($_SESSION['auth'][$key])){
				return $_SESSION['auth'][$key];
			}
		} else {
			return $_SESSION['auth'];
		}
		return;
	}
	
	function loggedin(){
		if ((isset($_SESSION['auth']) && isset($_SESSION['auth']['loggedin']) && $_SESSION['auth']['loggedin'])){
			return true;
		}
		return false;
	}
}
?>