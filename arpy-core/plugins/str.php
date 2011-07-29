<?php
class Str {
	
	// converts a string into a sane url
	function slugify($string, $max_words=false, $max_chars=false){
		if ($max_words && $max_words > 0){
			$string = max_words($string, $max_words);
		}
		
		$string = html_entity_decode(strtolower($string));
		// replace & with -and-
		$string = str_replace('&','-and-',$string);
		// replace . with 
		$string = str_replace('.','',$string);
		//replace illegal chars with -'s
		$string = str_replace(' ','-',$string);
		$string = ereg_replace("[^a-z0-9]", "-", $string);
		// remove duplicate -'s
		while (strpos($string,'--')!==false){
			$string = str_replace('--','-',$string);
		}
		
		if ($max_chars && $max_chars > 0){
			$string = substr($string, 0, $max_chars);
		}
		
		// remove trailing -'s and return
		return trim($string,'-');
	}
	
	function strip_xss($string,$allowtags=NULL,$allowattributes=NULL){
		if (is_array($allowtags)){
			
		} else if ($allowtags=='full'){
			$allowtags = array('<h1>','<h2>','<h3>','<h4>','<h5>','<h6>','<b>','<i>',
							'<u>','<a>','<ul>','<ol>','<li>','<pre>','<hr>','<blockquote>',
							'<img>','<font>','<span>','<table>','<thead>','<th>','<tr>','<td>',
							'<em>','<strong>','<applet>','<div>','<center>','<pre>','<ins>',
							'<del>','<em>','<kbd>','<dd>','<tbody>','<tfooter>','<big>','<button>',
							'<input>','<option>','<textarea>','<fieldset>','<form>','<legend>','<code>',
							'<object>','<param>','<embed>','<p>','<br>');
		} else if ($allowtags=='discussion_content'){
			$allowtags = array('<b>','<i>','<u>','<a>','<img>','<em>','<strong>','<s>','<del>');
			$allowattributes = array('border','style','font','class','href','src','target');
		} else if ($allowtags=='media'){
			$allowtags = array('<b>','<i>','<u>','<a>','<em>','<strong>');
		} else if ($allowtags=='blog_content'){
			$allowtags = array('<h1>','<h2>','<h3>','<h4>','<h5>','<h6>','<b>','<i>',
							'<u>','<a>','<ul>','<ol>','<li>','<pre>','<hr>','<blockquote>',
							'<img>','<font>','<span>','<table>','<thead>','<th>','<tr>','<td>',
							'<em>','<strong>','<applet>','<div>','<center>','<pre>','<ins>',
							'<del>','<em>','<kbd>','<dd>','<tbody>','<tfooter>','<big>','<button>',
							'<input>','<option>','<textarea>','<fieldset>','<form>','<legend>','<code>',
							'<object>','<param>','<embed>','<p>','<br>','<script>','<iframe>');
			$allowattributes = array('frameborder','title','border','style','font','class','href','src','target',
									'align','height','width','type','name','value','cellspacing','cellpadding');
		} else {
			$allowtags = array();
		}
		
		$allowtags = implode(',',$allowtags);
	    $string = strip_tags($string,$allowtags); 
	    
	    if (!is_null($allowattributes)) { 
	        if(is_array($allowattributes)) 
	            $allowattributes = implode(")(?<!",$allowattributes); 
	        if (strlen($allowattributes) > 0) 
	            $allowattributes = "(?<!".$allowattributes.")"; 
	        $string = preg_replace_callback("/<[^>]*>/i",create_function( 
	            '$matches', 
	            'return preg_replace("/ [^ =]*'.$allowattributes.'=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'    
	        ),$string); 
	    } 
	    return $string; 
	}
	
	function truncate($text,$len){
		if (strlen($text) < $len) return $text;
		
		$text = substr($text,0,$len-3).'...';
		return $text;
	}
	
	function autolink($text, $nofollow=true) {
		if ($nofollow){
			return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" rel=\"nofollow\">\\0</a>", $text);
		}
		return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $text);
	}
	
	function size_format($filesize){
		if ($filesize >= 1073741824) {
			$filesize = number_format($filesize / 1073741824, 2, '.', '') . ' GB';
		} else { 
			if ($filesize >= 1048576) {
				$filesize = number_format($filesize / 1048576, 2, '.', '') . ' MB';
			} else { 
				if ($filesize >= 1024) {
					$filesize = number_format($filesize / 1024, 0) . ' KB';
				} else {
					$filesize = number_format($filesize, 0) . ' Bytes';
				}
			}
		}
		return $filesize;
	}
	
}
?>