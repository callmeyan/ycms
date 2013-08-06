<?php
/**
 * 常用post类
 * @author yancheng
 */

final class YCMS_User{

	var $uid;
	var $user_login;
	var $user_pass;
	var $user_nickname;
	var $user_email;
	var $user_data;
	var $user_registered;
	var $user_status;

	public static function getInstace($user_id){
		global $ycms_db;
		$user_id = (int) $user_id;
		if ( ! $user_id ){
			return false;
		}
		$query = "SELECT * FROM ".$ycms_db->get_db_name("users")." WHERE UID = %d LIMIT 1";
		$userinfo = $ycms_db->get_row($ycms_db->prepare($query,$post_id));
		if(!$userinfo){
			return null;
		}
		return new YCMS_User($userinfo);
	}

	function __construct($userinfo){
		foreach ($userinfo as $key => $value) {
			$this->$key = $value;
		}
	}
}