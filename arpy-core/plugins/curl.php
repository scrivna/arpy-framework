<?php
class Curl {

	// Grabs the contents of a remote URL. Can perform basic authentication if un/pw are provided.
	function get($url, $username = null, $password = null){
		if(function_exists("curl_init")){
			$ch = curl_init();
			if(!is_null($username) && !is_null($password))
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' .  base64_encode("$username:$password")));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$html = curl_exec($ch);
			curl_close($ch);
			return $html;
		} elseif(ini_get("allow_url_fopen") == true){
			if(!is_null($username) && !is_null($password))
				$url = str_replace("://", "://$username:$password@", $url);
			$html = file_get_contents($url);
			return $html;
		} else {
			// Cannot open url. Either install curl-php or set allow_url_fopen = true in php.ini
			return false;
		}
	}
	
	function post($url,$data,$username = null, $password = null){
		
		$headers = array('Content-type: application/x-www-form-urlencoded;charset=UTF-8'); 
		
		$urlstring = '';
		foreach ($data as $key=>$value){
			$urlstring.= $key.'='.urlencode($value).'&';
		}
		$urlstring = substr($urlstring,0,-1);
		
		if(!is_null($username) && !is_null($password)){
			$headers[] = 'Authorization: Basic '.base64_encode("$username:$password");
		}
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $urlstring);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
	} 

}
?>