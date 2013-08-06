<?php
include_once 'admin.php';

$YC_ADMIN['title'] = '插件管理';
$YC_ADMIN['url'] = 'admin_plugins.php';

$action = getgpc("action");
$plugin = getgpc("plugin");
$menu_key = "install_plugin";
$active_plugins = Option::get("active_plugins",null,1);
if($action == "active"){
	if($plugin){
		if(isset($active_plugins[md5($plugin)])){
			show_ajax_data(302);
		}
		$active_plugins[md5($plugin)] = $plugin;
		Option::updateOption("active_plugins", serialize($active_plugins));
		Cache::getInstance()->updateCache('options');
		show_ajax_data(0,'admin_plugins.php','active_plugin_success');
	}else{
		show_ajax_data(301);
	}
}
if($action == "deactive"){
	if($plugin){
		if(!isset($active_plugins[md5($plugin)])){
			show_ajax_data(303);
		}
		unset($active_plugins[md5($plugin)]);
		Option::updateOption("active_plugins", serialize($active_plugins));
		Cache::getInstance()->updateCache('options');
		show_ajax_data(0,'admin_plugins.php','deactive_plugin_success');
	}else{
		show_ajax_data(301);
	}
}
if($action == "del"){
	if($plugin){
		rmdirs(YCMS_ROOT.'content/plugins/'.preg_replace("/^([^\/]+)\/.*/", "$1", $plugin));
		if(isset($active_plugins[md5($plugin)])){
			unset($active_plugins[md5($plugin)]);
			Option::updateOption("active_plugins", serialize($active_plugins));
			Cache::getInstance()->updateCache('options');
		}
		show_ajax_data(0,'admin_plugins.php','del_plugin_success');
	}else{
		show_ajax_data(301);
	}
}

$plugin_status = getgpc("plugin_status","all");
$allplugins = getAllPlugins();
$allcount = count($allplugins);
$activeCount = 0;
$deactiveCount = 0;
$activePlugins = $deactivePlugins = array();
foreach ($allplugins as $key => $p) {
	if($active_plugins->option_value[md5($p['file'])]){
		$allplugins[$key]['active'] = true;
		$activePlugins[] = $allplugins[$key];
		$activeCount ++;
	}else{
		$allplugins[$key]['active'] = false;
		$deactivePlugins[] = $allplugins[$key];
		$deactiveCount ++;
	}
}
if($plugin_status == "active"){
	$allplugins = $activePlugins;
}
if($plugin_status == "inactive"){
	$allplugins = $deactivePlugins;
}

include_once admin_template('plugins',1);