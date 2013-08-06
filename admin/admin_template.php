<?php
include_once 'admin.php';

$YC_ADMIN['title'] = '分类浏览';
$YC_ADMIN['url'] = 'admin_edit.php?action=category';

$dirs = getDirFiles(YCMS_ROOT.'content/themes');

$currentTheme = Option::get("template")->option_value;

$action = getgpc("action");
$act_theme = getgpc("theme");
if($action == "use"){
	if($currentTheme == $act_theme){
		show_ajax_data(201);
	}
	Option::updateOption("template",$act_theme);
	show_ajax_data(0,'admin_template.php','use_theme_success');
}
if($action == "del"){
	if($currentTheme == $act_theme){
		show_ajax_data(202);
	}
	//rmdirs(YCMS_ROOT.'content/themes/'.$act_theme);
	show_ajax_data(0,'admin_template.php','del_theme_success');
}
$themes = array();
foreach ($dirs as $file) {
	$themes[$file] = getThemeInfo($file);
}

$currentTheme = $themes[$currentTheme];
unset($themes[$currentTheme['file']]);
$currentThemesOptions = getThemeOption();

include_once admin_template("themes",1);