<?php
class Mysql_Database {

	var $queries = array();
	
	function __construct(){
		$this->conn = false;
	}
	
	function onError(){
		throw new Exception(mysql_error($this->conn));
	}
	
	function connect($server,$database,$user, $pass){
		// connect to a mysql server
		$this->conn = mysql_connect($server, $user, $pass) or $this->onError();
		mysql_select_db($database, $this->conn) or $this->onError();
	}
	
	function close(){
		mysql_close($this->conn);
	}
	
	function query($sql){
		$this->result = false;
		// execute a mysql query, the results are put into an array - $db->result
		$this->queries[] = $sql;
		$this->result = mysql_query($sql, $this->conn);

		if (!$this->result){
			$this->onError();
			$this->numrows = 0;
			return false;
		}
		
		// if we returned results loop through them and whack them in the results variabubble
		if(is_resource($this->result) && get_resource_type($this->result)=='mysql result'){
			$this->numrows = mysql_num_rows($this->result);
			// return false is no results were returned
			if ($this->numrows==0) return false;
			
			$data = array();
			while ($row = mysql_fetch_assoc($this->result)){
				$data[] = $row;
			}
			$this->result = $data;
		}
		
		return $this->result;
	}
	
	function select($sql_query,$return_maxrows=0,$start_row=0){
	
		//user wants to limit number of rows returned
		if ($return_maxrows>0) {
			$sql_query.=' LIMIT '.$start_row.','.$return_maxrows; // set a limit of results
		}
		//return all results
		$sql_result = mysql_query($sql_query, $this->conn);
		$this->queries[] = $sql_query;
		if (!$sql_result){
			$this->onError();
			$this->numrows = 0;
			return false;
		}
		
		$num_rows= mysql_num_rows($sql_result);
		if ($num_rows==0) return false;
		
		$i = 0;
		$fields = array();
		while ($i < mysql_num_fields($sql_result)){
			$column = mysql_fetch_field($sql_result,$i);
			if (!empty($column->table)){
				$fields[] = array($column->table,$column->name);
			} else {
				$fields[] = array(0,$column->name);
			}
			$i++;
		}
		
		$results = array();
		while ($row = mysql_fetch_row($sql_result)) {
			$resultRow = array();
			foreach ($row as $index => $field) {
				list($table, $column) = $fields[$index];
				$resultRow[$table][$column] = $row[$index];
			}
			$results[] = $resultRow;
		}
		return $results;
	
	}
	
	// escapes a string for a mysql string
	function escape($value){
		return mysql_real_escape_string($value, $this->conn);
	}
	
	// returns the last db query to be run
	function last_query(){
		$num = count($this->queries);
		return $this->queries[$num-1];
	}
	
	// returns the number of queries run
	function num_queries(){
		return count($this->queries);
	}
	
	function save($data){
		foreach ($data as $insert){
			if (isset($insert['id']) && !empty($insert['id'])){
				return $this->update($data);
			} else {
				return $this->insert($data);
			}
		}
	}
	
	function insert($data){
		// insert a new row, the array should have fieldnames as keys, 
		// $data = array('table_name'=>array('user_firstname' => 'Bob', 'user_lastname'=>'Dillan'));			
		// will return either the id of the inserted row or false
		
		foreach ($data as $table_name => $insert){
			$data_fields = array_keys($insert);
			$data_values = array_values($insert);
			$num_fields = count($data_fields);
		
			$sql = 'INSERT INTO '.$table_name.' (';
			for($i = 0; $i < $num_fields; $i++){
				$sql.= $data_fields[$i];
				if ($i<$num_fields-1){
					$sql.= ', ';
				}
			}
			$sql .= ') VALUES (';
			for($i = 0; $i < $num_fields; $i++){
				$value = '"'.$this->escape($data_values[$i]).'"';			
				$sql.= $value;
				if ($i<$num_fields-1){
					$sql.= ', ';
				}
			}
			$sql.= ');';
			// run the query and return either the id of the row inserted, or false
			if ($this->query($sql)){
				$result = mysql_insert_id($this->conn);
				return $result;
			}
			return false;
		}
	}
	
	function update($array){
		// same shiz as insert, returns true or false
		
		foreach ($array as $table_name => $data){
			$sql = 'UPDATE '.$table_name.' SET ';
		
			foreach($data as $field=>$value){
				$sql.= '`'.$field.'` = "'.$this->escape($value).'"';
				$sql.= ', ';
			}
			$sql = substr($sql,0,-2);
			$sql.= ' WHERE `id` = "'.$data['id'].'" LIMIT 1;';	// limit it to 1 update for speed
		
			// run the query and return true or false
			return $this->query($sql);
		}
	
	}

	function delete($id){
		// deletes a given id from the database
		$id = $this->escape($id);
		if ($this->query("DELETE FROM " . $this->table_name . " WHERE `" . $this->key_field . "` = '" . $id . "' LIMIT 1;")){
			return mysql_affected_rows($this->db);
		} else {
			return false;
		}
	}

}
?>