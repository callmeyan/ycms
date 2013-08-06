<?php
if(!YCMS_DEBUG){
	error_reporting(0);
	register_shutdown_function("when_shut_down");
	set_error_handler('error_handle');
}else{
	error_reporting(E_ALL);
}

/**
 * 自定义全局url
 */
global $diy_urls; 
$diy_urls = array();

global $ycms_db;
global $ycms_query;
global $SQL_ERROR;

$ycms_db = new YCMSDB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$ycms_query = Query::queryInit();

define("THEME_URI", get_template_directory_uri());

include_theme_functions();
