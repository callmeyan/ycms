<?php
include_once 'admin.php';

$action = getgpc("action","default");

if($action == "default"){
	//读取所有分类
	$categoties = MySql::getInstance()->fetch_table('categorys');
	$categoties = buildArray($categoties, 'cate_id');
	$parentCate = buildArray($categoties,'','parent_id',0);

	//删除
	if(getgpc("op") == 'del'){
		if(getgpc("cateid") > 0){
			MySql::getInstance()->delete_data('categorys','cate_id = '.getgpc("cateid"));
			Cache::getInstance()->updateCache('category');
			header_go("admin_category.php");
		}		
	}else if(getgpc("op") == "edit"){
		if(getgpc("cateid") > 0){
			$modify_cate = $categoties[getgpc("cateid")];
		}
	}
}else if($action == "save" && submit_check()){ ///　保存数据

	//检查空参数
	$null = check_null(array("cate_name"));
	if($null){
		show_ajax_data(-1,'','参数'.$null.'不能为空');	
	}
	$insert_data = array(
		'name'=>getgpc("cate_name"),
		'alias'=>getgpc('alias'),
		'parent_id'=>getgpc('parent_id'),
		'visible'=>getgpc('visible'),
		'description'=>getgpc("description"));
	$mysql = MySql::getInstance();
	
	if(getgpc("cateid",0) > 0){ //更新数据
		if($mysql->update('categorys',$insert_data,'cate_id='.getgpc("cateid"))){
			Cache::getInstance()->updateCache('category');
			show_ajax_data(0,'','更新分类成功');
		}
		
		show_ajax_data(-1,$mysql->querysql,'更新分类失败，请重试');	
	}
	// 新增数据
	if($mysql->insert('categorys',$insert_data)){
		Cache::getInstance()->updateCache('category');
		show_ajax_data(0,'','添加分类成功');
	}
	show_ajax_data(-1,'','添加分类失败，请重试');
}

include_once admin_template("category",1);