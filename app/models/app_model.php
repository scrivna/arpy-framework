<?php
class AppModel extends Model_Core {
	
	var $load_db 	= true;		// should i try and connect to the database?
	var $table 		= '';		// table name
	var $name 		= '';		// name to use in queries
	var $keyfield 	= 'id';		// key field for returning id's
	
	function __construct(){
		parent::__construct();
		
		if ($this->load_db){
		
			// assign the database object
			$this->db = CCache::Plugin('Mysql_Database');
			
			// connect to the database if not already
			if (!$this->db->conn){
				$this->db->connect(Config::get('database.host'), Config::get('database.name'), Config::get('database.user'), Config::get('database.pass')); 
			}
			
		}
	}
	
	function onDatabaseError($err){
	
		error_log($err);
		
		if (Config::get('debug')){
			echo '<div style="border:2px solid red; background-color:#fff; padding:10px; margin: 20px;">';
			echo '<strong>Database Error:</strong><br/><pre>'.$err.'</pre>';
			echo '<strong>Last Database Query:</strong><br/><pre>'.$this->db->last_query().'</pre>';
			//echo '<strong>Backtrace:</strong><pre>';
			//debug_print_backtrace();
			//echo '</pre>
			echo '</div>';
		}
		
		$msg  = $_SERVER['PHP_SELF'] . " @ " . date("Y-m-d H:ia") . "\n";
		$msg .= $err . "\n\n";
		$msg .= implode("\n", $this->db->queries) . "\n\n";
		$msg .= "CURRENT USER\n============\n"     . "\n" . $_SERVER['REMOTE_ADDR'] . "\n\n";
		$msg .= "POST VARIABLES\n==============\n" . var_export($_POST, true) . "\n\n";
		$msg .= "GET VARIABLES\n=============\n"   . var_export($_GET, true)  . "\n\n"; 
		
		$msg .= "Backtrace\n=============\n";
		$raw = debug_backtrace();
		foreach($raw as $entry){ 
			$msg.="\nFile: ".$entry['file']." (Line: ".$entry['line'].")\n"; 
			$msg.="Function: ".$entry['function']."\n"; 
			$msg.="Args: ".implode(", ", $entry['args'])."\n"; 
		}
		
		mail(Config::get('email.admin_add'), $_SERVER['PHP_SELF'], $msg, "From: ".Config::get('email.from_add'));
		
		// call the respondError method on the requests main controller
		CCache::controller(Router::controller())->respondError($err);
		
	}
}
?>