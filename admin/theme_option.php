<?php
include_once 'admin.php';

$ops = getThemeOption();
$option = getgpc("op","default");
$theme_name = Option::get("template")->option_value;
$datakey = "theme_data_".$theme_name."_".$option;
if(isset($ops[$option])){
	$save_success = false;
	if (submit_check()){
		$data = getgpc("theme_op_".$option);
		$value = serialize($data);
		$value = str_replace('\"', '\\\"', $value);
		
		Option::updateOption($datakey, $value);
		Cache::getInstance()->updateCache("options");
		$save_success = true;
		header_go("theme_option.php?op=".$option);
	}
	
	$YC_ADMIN['title'] = $ops[$option]['name'];
	$YC_ADMIN['url'] = 'theme_option.php?op='.$option;

	$settings_values = Option::get($datakey) ? unserialize(Option::get($datakey)->option_value) : array();
	
	$settings = $ops[$option]['setting'];
	include_once admin_template("theme_option",1);
}