<?php
function have_posts(){
	global $ycms_query;
	return $ycms_query->have_post();
}

function get_posts($count,$isNew=false,$isTop=false){
	global $ycms_query;
	$result = $ycms_query->get_posts($count,$isNew,$isTop);
	//print_r($result);
	return $result;
}

function the_post($pid = ''){
	global $ycms_query;
	return $ycms_query->the_post($pid);
}

function the_title(){
	global $ycms_query;
	echo $ycms_query->getPostValue('title');
}

function the_author(){
	global $ycms_query;
	echo $ycms_query->getPostValue('author');
}

function the_postlink(){
	global $ycms_query;
	echo $ycms_query->getPostValue('alias');
}

function the_category(){
	global $ycms_query;
	echo $ycms_query->getPostValue('cateid');
}

function the_tag(){
	global $ycms_query;
	echo $ycms_query->getPostValue('tags');
}

function the_content($return=false){
	global $ycms_query;
	$value = $ycms_query->getPostValue("content");
	return $return ? $value : print($value);
}

function the_time($d = ''){
	$d = $d ? $d : "Y-m-d";
	global $ycms_query;
	echo date($d,$ycms_query->getPostValue("date"));
}

function the_expert($return=false){
	global $ycms_query;
	$value = $ycms_query->getPostValue("excerpt");
	return $return ? $value : print($value);
}


final class Query{
	/**
	 * 是否初始化
	 * @var object
	 */
	public static $init = null;

	/** post **/
	private $current_post = 0;
	private $in_the_loop = false;
	private $is_single = false;
	private $post = null;
	private $post_list = null;

	public static function queryInit(){
		if(Query::$init){
			return Query::$init;
		}
		return new Query();
	}

	private function __construct(){
		$this->init = $this;
	}

	/**
	 * 是否有post
	 */
	public function have_post(){
		global $ycms_db;
		$post_list = cache_get(CACHE_POST,CACHE_POST_LIST);
		if($post_list == null){
			$posts = $ycms_db->get_results("SELECT pid,title,alias FROM ".$ycms_db->get_db_name("posts")." WHERE TYPE= 'post'");
			$post_list = buildArray($posts,"pid");
			cache_write($post_list,CACHE_POST,0,"post_list");
		}
		if($post_list && count($post_list) > 0){
			return true;
		}
		return false;
	}

	public function get_posts($count,$isNew=false,$isTop=false){
		global $ycms_db,$url_query;
		if($this->in_the_loop){
			if(!$this->post_list || count($this->post_list) <= $this->current_post){
				return false;
			}
			return true;
		}
		$this->resetPost();
		$query = "SELECT pid,title,alias,date,excerpt,author,cateid,views,password FROM ".$ycms_db->get_db_name("posts")." WHERE 1=1";
		$this->post_list = $ycms_db->get_results($query);
		if($this->post_list && count($this->post_list) > 0){
			return true;
		}
		return false;
	}

	public function getPostValue($key){
		if(is_object($this->post)){
			return $this->post->$key;
		}
		return $this->post[$key];
	}

	public function the_post($pid = ''){
		if($pid){
			$this->resetPost();
			$this->is_single = true;
			$this->post = getPostByPid($pid);
			return $this->post;
		}
		if($this->is_single){
			if($this->post){
				return true;
			}
			return false;
		}
		$this->in_the_loop = true;
		if ( $this->current_post == 0 ){
			do_action('posts_loop_start', array(&$this));
		}
		return $this->next_post();
	}

	private function next_post(){
		$post = $this->post_list[$this->current_post];
		$this->post = new YCMS_Post($post);
		$this->current_post ++;
		return $this->current_post;
	}

	/**
	 * 重置post相关变量
	 */
	private function resetPost(){
		$this->is_single = false;
		$this->in_the_loop = false;
		$this->post = null;
		$this->current_post = 0;
		$this->post_list = null;
	}


}