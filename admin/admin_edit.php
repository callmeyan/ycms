<?php
include_once 'admin.php';

$action = getgpc("action","category");
$uid = $_SESSION[AUTH_NAME]['uid'];
global $YC_ADMIN,$ycms_db;
if($action == "category"){
	$YC_ADMIN['title'] = '分类浏览';
	$YC_ADMIN['url'] = 'admin_edit.php?action=category';
	
	$categories = MySql::getInstance()->fetch_table("categorys");

	include_once admin_template("article-cate",1); 
}else if($action == "article"){
	$categories = getAvailableCategory();
	$cateid = getgpc("cateid");
	$cate = $categories[$cateid];
	if($cateid < 1 || !$cate){
		header_go("admin_edit.php?action=category");
	}
	$YC_ADMIN['title'] = $cate['name'];
	$YC_ADMIN['url'] = 'admin_edit.php?action=article&cateid='.$cateid;
	$pagesize = 10;
	$pageNo = getgpc("pageNo",1);
	$limit = " limit ".(($pageNo - 1) * $pagesize).",".$pagesize;
	$query = $ycms_db->prepare("SELECT * FROM ".DB_PREFIX."posts WHERE cateid=%d order by p_order desc,pid desc".$limit,$cateid);	
	$articles = $ycms_db->get_results($query);
	$query = $ycms_db->prepare("SELECT count(*) FROM ".DB_PREFIX."posts WHERE cateid=%d",$cateid);
	$totalcount = $ycms_db->get_col($query);
	$totalcount = $totalcount[0];
	include_once admin_template("article-list",1); 
}else if($action == "edit"){
	$pid = getgpc("pid",0);
	$mysql = MySql::getInstance();
	$log = $mysql->fetch_array($mysql->query("SELECT * FROM ".DB_PREFIX."posts WHERE pid = $pid"));
	if(!$log){$pid = 0;}else{	
		$YC_ADMIN['title'] = '修改 '.$log['title'];
		$YC_ADMIN['url'] = 'admin_edit.php?action=edit&pid='.$pid.'&cateid='.getgpc("cateid");
	}
	include_once admin_template("article-post",1); 
}else if($action == "save"){
	$insert_data = array(
		'title' => getgpc('title'),
		'date' => time(),
		'modifydate' => time(),
		'p_order' => getgpc("p_order"),
		'content' => getgpc('content'),
		'excerpt' => getgpc('excerpt'),
		'alias' => getgpc('alias')?getgpc('alias'):getgpc('title'),
		'author' => $uid,
		'cateid' => getgpc('cateid'),
		'password' => getgpc('password'),
		'reason' => getgpc('reason'),
		'p_ip' => getClientIP(),
		'tags' => getgpc('tags'),
		'p_picture' => getgpc('p_picture'),
		'extra_data' => serialize(getgpc("extra_data"))
	);
	$arr = array();
	$pid = getgpc("pid");
	
	$mysql = MySql::getInstance();
	if($pid){
		//　获取要修改的数据对象
		$log = $mysql->fetch_array($mysql->query("SELECT * FROM ".DB_PREFIX."posts WHERE pid = $pid"));
		if($log){
			unset($insert_data['date']);
			$update_data = array_merge($log,$insert_data);
			//保存数据并更新缓存
			if($mysql->update('posts',$update_data,'pid='.$pid)){
				Cache::getInstance()->update_postnewarticle(true,$pid);
				show_ajax_data(0,'','更新文章成功');
			}
			show_ajax_data(-1,$mysql->querysql,'更新文章成功，请重试');	
		}
	}
	// 新增数据
	if($mysql->insert('posts',$insert_data)){
		Cache::getInstance()->update_postnewarticle();
		show_ajax_data(0,'','添加文章成功');
	}
	show_ajax_data(-1,'','添加分类失败，请重试');
}