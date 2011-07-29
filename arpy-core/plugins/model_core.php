<?php
class Model_Core {

	function __construct(){
		$this->db 		= false;
		$this->table 	= '';
		$this->name 	= '';
		$this->keyfield = 'id';
	}
	
	// overwrite this
	function onDatabaseError($e){}
	
	function query($sql){
		try {
			return $this->db->query($sql);
		} catch(Exception $e){
			$this->onDatabaseError($e);
			return false;
		}
	}
	
	function select($sql=''){
		if (trim(strtolower(substr($sql,0,6)))!='select'){
			$sql = 'SELECT * FROM `'.$this->table.'` AS `'.$this->name.'` '.$sql;
		}
		try {
			return $this->db->select($sql);
		} catch(Exception $e){
			$this->onDatabaseError($e);
			return false;
		}
	}
	
	function save($data){
		if (isset($data[$this->keyfield]) && !empty($data[$this->keyfield])){
			return $this->update($data);
		} else {
			return $this->insert($data);
		}
		return false;
	}
	
	function insert($data){
		$insert = $data;
		
		$data_fields = array_keys($insert);
		$data_values = array_values($insert);
		$num_fields = count($data_fields);
	
		$sql = 'INSERT INTO `'.$this->table.'` (';
		for($i = 0; $i < $num_fields; $i++){
			$field = '`'.$data_fields[$i].'`';
			$sql.= $field;
			if ($i<$num_fields-1){
				$sql.= ', ';
			}
		}
		$sql .= ') VALUES (';
		for($i = 0; $i < $num_fields; $i++){
			$value = '"'.$this->db->escape($data_values[$i]).'"';			
			$sql.= $value;
			if ($i<$num_fields-1){
				$sql.= ', ';
			}
		}
		$sql.= ');';
		// run the query and return either the id of the row inserted, or false
		if ($this->query($sql)){
			$result = mysql_insert_id($this->db->conn);
			return $result;
		}
		return false;
	}
	
	function update($data){
		// same shiz as insert, returns true or false
		
		$id = $data[$this->keyfield];
		if (!is_array($id)){
			$id = array($id);
		}
		
		$sql = 'UPDATE `'.$this->table.'` as `'.$this->name.'` SET ';
	
		foreach($data as $field=>$value){
			$field = $this->realField($field);
			
			$sql.= $field.' = "'.$this->db->escape($value).'"';
			$sql.= ', ';
		}
		$sql = substr($sql,0,-2);
		$sql.= ' WHERE `'.$this->keyfield.'` IN('.implode(',',$id).')';
	
		// run the query and return true or false
		return $this->query($sql);
	}
	
	function delete($id){
		if (!is_array($id)) $id = array($id);
		
		$sql = 'DELETE FROM `'.$this->table.'` WHERE `'.$this->keyfield.'` IN('.implode(',',$id).')';
		return $this->query($sql);
	}
	
	function increment($id, $field, $qty=1){
		$sql = 'UPDATE `'.$this->table.'` SET `'.$field.'` = `'.$field.'`+'.$qty.' WHERE `'.$this->keyfield.'` = '.$id.' LIMIT 1';
		$this->query($sql);
	}
	
	function decrement($id, $field, $qty=1){
		$sql = 'UPDATE `'.$this->table.'` SET `'.$field.'` = `'.$field.'`-'.$qty.' WHERE `'.$this->keyfield.'` = '.$id.' LIMIT 1';
		$this->query($sql);
	}
	
	// returns a field formatted in the correct format for selecting
	function realField($field){
		$field = trim($field);
		if ($field == '*') return $field;
		if ($field == 'SQL_CALC_FOUND_ROWS') return $field;
		
		if (substr($field,0,6) == 'count(') return $field;
		if (substr($field,0,4) == 'min(') return $field;
		if (substr($field,0,4) == 'max(') return $field;
		
		$field = explode('.',$field);
		if (count($field)>1){
			if ($field[1]=='*'){
				return '`'.$field[0].'`.'.$field[1];
			}
			return '`'.$field[0].'`.`'.$field[1].'`';
		} else {
			return '`'.$this->name.'`.`'.$field[0].'`';
		}
	}
	
}
?>