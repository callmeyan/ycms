<?php
class Common extends Controller{
	public function index(){
		$this->display('index');
	}
	
	public function showCustomerPage($data){
		if($data['page']){
			$this->display($data['page']);
		}
		if($data['callback'] && function_exists($data['callback'])){
			call_user_func($data['callback'],$data['param']);
		}
	}
}