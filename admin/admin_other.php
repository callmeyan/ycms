<?php
include_once 'admin.php';

$action = getgpc("action");

//快捷链接
if($action == "shortcut_link"){
	$shortcut = Option::get("shortcut_links",array(),true);
	$url = getgpc("url");
	if(getgpc("type") == "add"){
		$shortcut[md5($url)] = array('name' => getgpc("name"),'url' => $url);
	}else{
		if (isset($shortcut[md5($url)])){
			unset($shortcut[md5($url)]);
		}
	}
	Option::updateOption("shortcut_links", serialize($shortcut));
	Cache::getInstance()->updateCache('options');
	show_ajax_data(0,'',getgpc("type") == 'add'?'添加成功':'删除成功');
}