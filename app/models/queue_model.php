<?php
class QueueModel extends AppModel {
	
	function __construct(){
		parent::__construct();
		$this->name = 'queue';
		$this->table = 'queue';
		
		$this->lock_timeout = 120;
	}
	
	// add a task to the queue stack
	function addTask($task, $data){
		$ins = array();
		$ins['task'] 	= $task;
		$ins['data'] 	= json_encode($data);
		$ins['created'] = time();
		return $this->save($ins);
	}
	
	// get the next task in the stack
	function nextTask(){
		
		// release old ones that are locked but failed
		$sql = 'UPDATE '.$this->table.' SET retries=retries+1, status = "active", lock_acquired = 0, lock_key = "" WHERE status="locked" AND lock_acquired < '.(time()-$this->lock_timeout).';';
		$this->query($sql);
		
		// update the next task in the queue before selecting it to stop race conditions
		$lock_key = time().rand(0,100000);
		$sql = 'UPDATE '.$this->table.' SET status="locked", lock_acquired='.time().', lock_key='.$lock_key.' ';
		$sql.= 'WHERE status="active" ORDER BY created ASC LIMIT 1';
		$this->query($sql);
		
		$task = $this->find(array('conditions'=>array('status'=>'locked','lock_key'=>$lock_key)));
		if ($task){
			$task['Queue']['data'] = json_decode($task['Queue']['data'], true);
			return $task;
		}
		
		return false;
	}
	
	// get a specific task, only if it's sitting in the queue
	function getTask($id){
		// update the task in the queue before selecting it to stop race conditions
		$lock_key = time().rand(0,100000);
		$sql = 'UPDATE '.$this->table.' SET status="locked", lock_acquired='.time().', lock_key='.$lock_key.' ';
		$sql.= 'WHERE status="active" AND id="'.$id.'" ORDER BY created ASC LIMIT 1';
		$this->query($sql);
		
		$task = $this->find(array('conditions'=>array('status'=>'locked','lock_key'=>$lock_key,'id'=>$id)));
		if ($task){
			$task['Queue']['data'] = json_decode($task['Queue']['data'], true);
			return $task;
		}
		
		return false;
	}
	
	// mark a task as completed
	function completeTask($id){
		$upd = array();
		$upd['id'] 			= $id;
		$upd['status'] 		= 'completed';
		$upd['completed'] 	= time();
		return $this->save($upd);
	}
	
	// delete task
	function deleteTask($id){
		$upd = array();
		$upd['id'] 			= $id;
		$upd['status'] 		= 'deleted';
		$upd['completed'] 	= time();
		return $this->save($upd);
	}
	
	function countTasks(){
		$sql = 'SELECT id FROM '.$this->table.' WHERE status IN("active","locked") AND lock_acquired < '.(time()-$this->lock_timeout).';';
		$tasks = $this->select($sql);
		if ($tasks){
			return count($tasks);
		}
		return 0;
	}
}
?>