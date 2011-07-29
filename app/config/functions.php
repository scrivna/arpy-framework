<?php
function pr($data){
	if (Config::get('debug')) echo "<pre>\n".print_r($data, true)."\n</pre>";
}
?>