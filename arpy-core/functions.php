<?php
function arpy_autoloader($name){

	// class name and file to load
	$name = strtolower($name);
	$file = false;
	
	if (substr($name,-10) == 'controller' && strlen($name) > 10 && is_readable('controllers/'.substr($name,0,-10).'_controller.php')){
		
		// is it any controller?
		$file = 'controllers/'.substr($name,0,-10).'_controller.php';
		
	} else if (substr($name,-5) == 'model' && strlen($name) > 5 && is_readable('models/'.substr($name,0,-5).'_model.php')){
	
		// is it any model?
		$file = 'models/'.substr($name,0,-5).'_model.php';
		
	} else if (is_readable('plugins/'.$name.'.php')){
		
		// is it an app plugin?
		$file = 'plugins/'.$name.'.php';
		
	} else if (is_readable(ARPY_CORE . 'plugins/'.$name.'.php')){
	
		// is it a core plugin?
		$file = ARPY_CORE . 'plugins/'.$name.'.php';
		
	}
	
	//echo '<br />'.realpath($file);
	if ($file && file_exists($file)){
		require $file;
		return true;
	}
	
	throw new Exception("Unable to load class: $file");
}

// registor our autoload function
spl_autoload_register('arpy_autoloader');
?>