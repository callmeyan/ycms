<?php
include_once 'admin.php';

$redurect_to = getgpc("redirect_to");
if(submit_check()){
	$loginid = $_POST['loginid'];
	$loginpass = $_POST['loginpass'];
	$array = array("code"=>0,"msg"=>"");
	if(!$loginid || !$loginpass){
		$array = array("code"=>-1,"msg"=>"登陆ID和密码都不能为空");
	}else{
		$user = userlogin($loginid, LoginAuth::getPassword($loginid, $loginpass));
		if($user){
			$saveCookie = false;
			if(getgpc('remberme')){
				$saveCookie = true;
			}
			LoginAuth::saveAuth($user->uid,$user->user_login,$saveCookie);

			$array['main'] = $redurect_to ? $redurect_to : 'index.php?frm=index';
		}else{
			$array = array("code"=>-1,"msg"=>"登陆ID或密码错误");
		}
	}
	exit(json_encode($array));
}
include_once admin_template("sign-in",1);