<?php
class Controller{
	protected $pagedatas = array();

	public function __construct(){
	}
	protected function assign($key,$value){
		$this->pagedatas[$key] = $value;
	}

	protected function display($file,$data = array()){
		$options_cache = Option::getAll();
		$initData =  array_merge($this->pagedatas,$data);
		$initData['config'] = $options_cache;
		extract($initData);
		$filename = YCMS_ROOT.'content/themes/'.$options_cache['template'].'/'.$file.'.php';
		if(!file_exists($filename)){
			show_404_page();
		}
		load_template($filename);
	}

	public function __default(){
		return "hello!";
	}
}