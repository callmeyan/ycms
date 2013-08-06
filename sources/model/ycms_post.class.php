<?php
/**
 * 常用post类
 * @author yancheng
 */
final class YCMS_Post{
	
	var $pid;
	var $title;
	var $data;
	var $modifydate;
	var $content;
	var $excerpt;
	var $alias;
	var $author;
	var $cateid;
	var $type;
	var $views;
	var $guid;
	var $p_order;
	var $visible;
	var $password;
	var $p_parent;
	var $reason;
	var $p_ip;
	var $tags;
	var $comment_count;
	var $p_picture;
	var $extra_data;
	var $status;
	
	public static function getInstace($post_id){
		global $ycms_db;
		$post_id = (int) $post_id;
		if ( ! $post_id ){
			return false;
		}
		$post = cache_get(CACHE_POST, $post_id);
		if(!$post){
			$query = "SELECT * FROM ".$ycms_db->get_db_name("posts")." WHERE PID = %d LIMIT 1";
			$post = $ycms_db->get_row($ycms_db->prepare($query,$post_id));
			cache_write($post, CACHE_POST,0,$post_id);
		}
		return new YCMS_Post($post);
	}
	
	function __construct($post){
		foreach ($post as $key => $value) {
			$this->$key = $value;
		}
	}
}