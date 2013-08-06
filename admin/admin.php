<?php
define('IN_ADMIN', 1);

global $YC_ADMIN;
$YC_ADMIN = array();
global $settings_fields;
$settings_fields = array();

include dirname(dirname(__FILE__)).'/config.php';
if(!YCMS_DEBUG){
	error_reporting(E_ERROR);
}else{
	error_reporting(E_ERROR | E_WARNING  | E_PARSE);
}
include YCMS_ROOT.'sources/lib/ycms_cache.class.php';
include YCMS_ROOT."admin/language-cn.php";

global $ycms_db;
global $SQL_ERROR;

include YCMS_ROOT.'sources/lib/function.core.php';
include YCMS_ROOT.'sources/lib/function.db.php';
include YCMS_ROOT.'sources/lib/function.article.php';

$ycms_db = new YCMSDB(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
include YCMS_ROOT.'sources/lib/function.template.php';

$YC_ADMIN['config'] = include_once(YCMS_ROOT.'admin/admin_config.php');
$active_plugins = unserialize(Option::get("active_plugins",null)->option_value);
foreach ($active_plugins as $plugin) {
	if(checkPlugin($plugin)){
		include_once YCMS_ROOT . '/content/plugins/' . $plugin;
	}
}

session_start();

date_default_timezone_set("Asia/Shanghai");

header('Content-Type: text/html; charset=utf-8');

//检查是否已经登陆
if(!LoginAuth::isLogin() && !str_endwith('admin_login.php', $_SERVER['SCRIPT_NAME'])){
	$redirect = urlencode(substr($_SERVER[REQUEST_URI], 7));
	if(isAjax()){
		show_ajax_data(-1,'admin_login.php?redirect_to='.$redirect,'登陆信息超时，请重新登陆');
	}
	header_go('admin_login.php?redirect_to='.$redirect);
}
$YC_ADMIN['shortcut_links'] =  Option::get("shortcut_links",array(),true)->option_value;
initAdminMenus();
$YC_ADMIN['current_menu'] = "";

//初始化插件及其他菜单
function initAdminMenus(){
	global $YC_ADMIN;
	$currentThemesOptions = getThemeOption();
	if($currentThemesOptions){
		foreach ($currentThemesOptions as $opname => $setting) {
			$YC_ADMIN['config']['menus']['themes'][$setting['name']] = 'theme_option.php?op='.$opname;
		}
	}

}

function submit_check($key='submit'){
	return isset($_REQUEST[''.$key]) ? $_REQUEST[''.$key] : "";
}

function getThemeOption(){
	$file = YCMS_ROOT.get_template_directory_uri(0).'/functions.php';
	if(file_exists($file)){
		require_once $file;
	}
	global $settings_fields;
	do_action("add_theme_option");
	if($settings_fields){
		return $settings_fields;
	}
	return null;
}


function admin_template($name,$return = false){
	$file = YCMS_ROOT.'admin/admin_template/'.$name.'.php';
	if(file_exists($file)){
		if($return) return $file;
		include_once $file;
	}
	return false;
}
