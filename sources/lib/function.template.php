<?php
initConstants();

function initConstants(){
	define("CACHE_DIR", YCMS_ROOT . 'content/cache/');

	define("CACHE_POST", "cache_post");
	define("CACHE_POST_LIST", "post_list");
	define("CACHE_USER", "cache_user");
	define("CACHE_OPTION", "cache_option");
	define("CACHE_CATEGORY", "cache_category");
	define("CACHE_LINK", "cache_link");
	define("CACHE_COMMENT", "cache_comment");

	if(!defined("THEME_PATH")){
		define("THEME_PATH", YCMS_ROOT.get_template_directory_uri(0).'/');
	}
}

/**
 * 获取当前模版目录
 */
function get_template_directory_uri($full = true){
	return ($full ? BLOG_URL : "") . "content/themes/" . Option::get("template")->option_value;
}

function get_header( $name = null ) {
	do_action('get_header', $name);
	$templates = array();
	if(file_exists(THEME_PATH.'header.php')){
		load_template(THEME_PATH.'header.php');
	}
}


function get_footer( $name = null ) {
	do_action('get_footer', $name);
	$templates = array();
	if(file_exists(THEME_PATH.'footer.php')){
		load_template(THEME_PATH.'footer.php');
	}
}

function get_view($file){
	load_template(YCMS_ROOT.get_template_directory_uri(0).'/'.$file.'.php');
}

function load_template($_template_file, $require_once = true ) {
	global $ycms_db;
	//if ( is_array( $wp_query->query_vars ) ){
	//	extract( $wp_query->query_vars, EXTR_SKIP );
	//}
	if ($require_once){
		require_once($_template_file);
	}else{
		require($_template_file);
	}
}

function ycms_parse_args( $args, $defaults = '' ) {
	if ( is_object( $args ) ){
		$r = get_object_vars( $args );
	}elseif ( is_array( $args ) ){
		$r = &$args;
	}else{
		wp_parse_str($args, $r);
	}
	if (is_array( $defaults )){
		return array_merge( $defaults, $r );
	}
	return $r;
}

function ycms_parse_str(){
	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() ){
		$array = stripslashes_deep( $array );
	}
}

function stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = stripslashes($value);
	}

	return $value;
}

function parse_query($query =  '') {

	do_action_ref_array('parse_query', array(&$this));
}
