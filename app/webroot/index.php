<?php
// log the time the site started loading (for stats)
$starttime = microtime(true);


// change directory up one level so all 
// paths are relative to the app directory
chdir('../');


// define the path we can find the core files stored
define('ARPY_CORE', '../arpy-core/');


// load the autoloader
require_once(ARPY_CORE . 'functions.php');


// store the start time now we have the config loaded
Config::set('sys.starttime', $starttime);
unset($starttime);	// no longer needed thanks


// load the app config file
require_once('config/config.php');


// load the app custom functions
require_once('config/functions.php');


// initialise the router
Router::init();


// try loading the app controller
try {

	// load a single instance of the app controller
	$controller = CCache::controller(Router::controller());
	
} catch(Exception $e) {

	// if no controller found use the default / error controller
	$controller = CCache::controller('App');
	
}


// run the controller action
if (isset($controller) && $controller){
	$controller->{Router::action()}();
}
?>