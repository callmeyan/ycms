<?php
function userlogin($loginid,$loginpass){
	global $ycms_db;
	$query = "SELECT * FROM ".$ycms_db->get_db_name("users")." WHERE user_login=%s AND user_pass=%s LIMIT 1";
	$userinfo = $ycms_db->get_row($ycms_db->prepare($query,array($loginid,$loginpass)));
	if(!$userinfo){
		return null;
	}
	return new YCMS_User($userinfo);
}