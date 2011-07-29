<?php
// router defaults
Config::set('router.default_controller', 'page');
Config::set('router.default_action', 'index');
	
// switch environments
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'live'){

	Config::set('debug', false);
	
	Config::set('sys.env', 'live');
	
	Config::set('site.url', 'http://localhost/');
	
	Config::set('database.host', 'localhost');
	Config::set('database.name', 'test');
	Config::set('database.user', 'root');
	Config::set('database.pass', 'root');
	Config::set('database.conn', false);

} else {

	Config::set('debug', true);
	
	Config::set('sys.env', 'dev');
	Config::set('router.default_controller', 'page');
	
	Config::set('site.url', 'http://127.0.0.1/Dropbox/Code/arpy-framework/');
	
	Config::set('database.host', 'localhost');
	Config::set('database.name', 'test');
	Config::set('database.user', 'root');
	Config::set('database.pass', 'root');
	Config::set('database.conn', false);
	
}

// email defaults
Config::set('email.admin_add',	'me@me.com');
Config::set('email.from_add', 	'me@me.com');
Config::set('email.from_name', 	'Me');
Config::set('email.reply_add', 	'me@me.com');
Config::set('email.reply_name', 'Reply Me');


// php settings based on environment
if (Config::set('sys.env')=='dev' || Config::get('debug')){
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}
?>