<?php
include_once 'admin.php';

$links = unserialize(Option::get("shortcut_links")->option_value);
$key = getgpc("key","");
if(getgpc("action") == "save" && $key){
	$links[$key] = array("name"=>getgpc("name"),"url"=>getgpc("url"));
	Option::updateOption("shortcut_links", serialize($links));
	show_ajax_data(0,'',"修改链接成功");
}else if(getgpc("action") == "del" && $key){
	if(isset($links[getgpc("key")])){
		unset($links[$key]);
		Option::updateOption("shortcut_links", serialize($links));
	}
	show_ajax_data(0,'shortcut_links.php',"删除链接成功");
}
include_once admin_template("links-list",1);