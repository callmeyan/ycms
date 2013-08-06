<?php

$admin_menus = array();
$admin_menus['content'] = array(
	'datas'=> 'admin_edit.php',
	'category'=> 'admin_category.php',
	'friend_links'=> 'admin_links.php',
	'shortcut_links'=> 'shortcut_links.php'
);
$admin_menus['themes'] = array(
	'template'=>'admin_template.php',
);
$admin_menus['plugin'] = array(
	'install_plugin'=>'admin_plugins.php',
);
$admin_menus['comment'] = array(

);
$admin_menus['setting'] = array(

);

$admin_configs = array(
	'menus' => $admin_menus, //显示菜单
	'show_superman' => true //是否显示多余的菜单
);
return $admin_configs;