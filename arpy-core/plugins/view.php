<?php
class View {
	
	private $data = array();
	
	function __construct(){
		// set up the defaults
		$this->view_path 	= 'views/';
		$this->template 	= 'templates/default.php';
	}
	
	public function set($key,$value=null){
		if (is_array($key)){
			$this->data = array_merge($this->data, $key);
		} else {
			$this->data[$key] = $value;
		}
	}
	
	public function get($name=false){
		if (!$name) return $this->data;
		
		if (isset($this->data[$name])){
			return $this->data[$name];
		} else {
			return false;
		}
	}
	
	public function render($file, $data=null, $output=true){
		
		if (is_null($data)){
			$data = $this->get();
		}
		
    	$output = $this->generate($file, $data);
		
		if ($output) echo $output;
		return $output;
    }
    
	public function renderInTemplate($file, $data=null, $output=true, $template=null){
		
		if (is_null($data)){
			$data = $this->get();
		}
		
    	$output = $this->generate($file, $data);
    	
    	$tdata = array();
		$tdata['title_for_template'] 	= isset($data['title']) ? $data['title'] : '';
		$tdata['content_for_template']	= $output;
		$data = array_merge($data, $tdata);
		
		$output = $this->generate(!is_null($template) ? $template : $this->template, $data);
		
		if ($output) echo $output;
		return $output;
    }
    
	private function generate($file, $data=null){
		
		if (is_null($data)){
			$data = $this->get();
		}
		
		if (is_readable($this->view_path.$file)){
			$file = $file;
		} else {
			throw new Exception("Unable to load view: $file");
		}
		
		return $this->get_include_contents($this->view_path.$file, $data);
	}
	
	// assigns the output of a file into a variable... lovely jubbly!
	private function get_include_contents($filename, $data=false) {
		$this->view_data = $data;	// set for easy use in views
		if (is_file($filename)) {
			if (is_array($data)){
				extract($data);
			}
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		return false;
	}
}
?>