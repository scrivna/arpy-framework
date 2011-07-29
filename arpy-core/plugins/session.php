<?php
class Session {
	
	function set($key,$value){
		$_SESSION[$key] = $value;
	}
	
	function get($key){
		if (isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		return;
	}
	
	function destoy($part=''){
		if (empty($part)){			// destroy the session data
			session_unset();
			session_destroy();
			$_SESSION = array();
		} else {
			unset($_SESSION[$part]);
		}
		return true;
	}
}
?>