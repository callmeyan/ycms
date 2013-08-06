<?php
include_once 'admin.php';

$action = getgpc("action","default");
$YC_ADMIN['title'] = '友情链接管理';
$YC_ADMIN['url'] = 'admin_links.php';

if($action == "default"){
	//读取所有分类
	$links = MySql::getInstance()->fetch_table('links','1=1 order by link_order desc');
	$link_s = buildArray($links, 'link_id');
	$linkid = getgpc("linkid",0);
	if(getgpc("op") == "edit" && $linkid > 0 && isset($link_s[$linkid])){
		$modify_link = $link_s[$linkid];
	}
	include_once admin_template("links",1);
}else if($action == "save" && submit_check()){ ///　保存数据

	//检查空参数
	$null = check_null(array("link_name","link_url"),array("链接名字","链接地址"));
	if($null){
		show_ajax_data(-1,'','admin_links.php'.'不能为空');
	}
	$insert_data = array(
		'link_name'=>getgpc("link_name"),
		'link_url'=>getgpc('link_url'),
		'link_image'=>getgpc('link_image'),
		'link_target'=>getgpc('link_target'),
		'link_visible'=>getgpc('link_visible'),
		'link_order'=>getgpc('link_order'),
		'link_added'=>time(),
		'link_updated'=>time(),
		'link_description'=>getgpc("link_description"));
	

	$mysql = MySql::getInstance();

	if(getgpc("linkid",0) > 0){ //更新数据
		if($mysql->update('links',$insert_data,'link_id='.getgpc("linkid"))){
			Cache::getInstance()->updateCache('links');
			show_ajax_data(0,'','更新链接成功');
		}

		show_ajax_data(-1,$mysql->querysql,'更新链接失败，请重试');
	}
	// 新增数据
	if($mysql->insert('links',$insert_data)){
		Cache::getInstance()->updateCache('links');
		show_ajax_data(0,'','添加链接成功');
	}
	show_ajax_data(-1,$mysql->querysql,'添加链接失败，请重试');
}else if($action == 'del'){ //删除
	if(getgpc("cateid") > 0){
		MySql::getInstance()->delete_data('links','link_id = '.getgpc("cateid"));
		Cache::getInstance()->updateCache('links');
		header_go("admin_links.php");
	}
	include_once admin_template("links",1);
}
