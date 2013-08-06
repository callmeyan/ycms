<?php

function getUserInfo($uid){
	$users = Cache::getInstance()->readCache("users");
	return $users[$uid];
}

function loadUser($uid,$return = false){
	global $ycms_db;
	$user = cache_get(CACHE_USER,$uid);
	if(!$user){
		$query = $ycms_db->prepare("SELECT * FROM ".DB_PREFIX."users WHERE UID = %d",$uid);
		$user = $ycms_db->get_row($query);
		if($user){cache_write($user, CACHE_USER,0,$uid);}		
	}
	if(!$user){return 'system';}
	$username = $user['user_nickname'] ? $user['user_nickname'] : $user['user_login'];
	if($return){
		return $username;
	}
	echo '<a href="admin_user.php?uid='.$user['uid'].'">'.$username.'</a>';
}


/**
 * 后台分页
 * Enter description here ...
 * @param unknown_type $totalCount
 * @param unknown_type $pagesize
 * @param unknown_type $pageNo
 * @param unknown_type $url
 * @param unknown_type $return
 */
function admin_pagination($totalCount,$pagesize,$pageNo,$url,$return = false){
	$pageCount  = ceil($totalCount / $pagesize);
	if($pageCount <2){
		return "";
	}
	$startNo = 1 ;
	$endNo = $pageCount;
	//显示页数至多11条
	//总页数大于11条时
	if($pageCount > 11){
		if($pageNo > $pageCount - 5){
			$startNo = $pageCount - 10;
		}else if($pageNo <= 6){
			$endNo = 11;
		}else if($pageNo > 6 && $pageNo <= $pageCount - 5){//当页数大于11条时
			$startNo = $pageNo - 5;
			$endNo	 = $pageNo + 5;
		}
	}

	$url .= strpos($url, "?") > -1 ? "&" : "?";
	$pageStr = "<ul>";
	$pageStr .= "<li><a href='".$url."'>First</li>";
	for($i = $startNo; $i <= $endNo ; $i++){
		if($pageNo == $i){
			$pageStr .= '<li><a href="javascript:;" style="color:#000;">'.$pageNo.'</a></li>';
		}else{
			$pageStr .= "<li><a href='".$url."pageNo=".$i."'>".$i."</a></li>";
		}
	}
	$pageStr .= '<li><a href="'.$url.'pageNo='.$pageCount.'">Last</a></li>';
	$pageStr .= "</ul>";
	if($return){
		return $pageStr;
	}
	echo $pageStr;
}

function fetch_pagination($totalCount,$pagesize,$pageNo,$url,$showcount = 7,$return = false){
	$pageCount  = ceil($totalCount / $pagesize);
	if($pageCount < 2){return "";}
	$startNo = 1 ;
	$endNo = $pageCount;
	$half = ($showcount - 1) /2;

	//显示页数至多11条
	//总页数大于11条时
	if($pageCount > 11){
		if($pageNo > $pageCount - $half){
			$startNo = $pageCount - $showcount - 1;
		}else if($pageNo <= $half+1){
			$endNo = $showcount;
		}else if($pageNo > $half+1 && $pageNo <= $pageCount - $half){//当页数大于11条时
			$startNo = $pageNo - $half;
			$endNo	 = $pageNo + $half;
		}
	}
	$url = SITE_URL.(strpos($url, "?") > -1 ? $url."&page=" : $url."/page/");

	$pageStr = "";
	$pageStr .= "<a class='page_index' href='".$url."1'>首页</a>";
	for($i = $startNo; $i <= $endNo ; $i++){
		if($pageNo == $i){
			$pageStr .= '<a class="page_a  page_this" href="javascript:;" style="color:#000;">'.$pageNo.'</a>';
		}else{
			$pageStr .= "<a class='page_a' href='".$url."".$i."'>".$i."</a>";
		}
	}
	$pageStr .= '<a class="page_end" href="'.$url.$pageCount.'">尾页</a>';
	if($return){
		return $pageStr;
	}
	echo $pageStr;
}

/********************* functions for themes **************************************/



function _merge_array($arr1,$arr2){
	foreach ($arr2 as $value) {
		if(!in_array($value, $arr1)){
			$arr1[] = $value;
		}
	}
	return $arr1;
}

/**
 * 获取配置
 */
function get_option($key,$default=null){
	return Option::get($key,$default);
}

function getsimpleArticle($articles,$single = false){
	$arts = array();
	if($single){
		$arts[] = $articles;
	}
	foreach ($articles as $key => $art) {
		$arts[$key] = array(
			'pid'=>$art['pid'],
			'title'=>$art['title'],
			'alias'=>$art['alias'],
			'date'=>$art['date'],
			'modifydate'=>$art['modifydate'],
			'excerpt'=>$art['excerpt'],
			'views'=>$art['views'],
			'author'=>$art['author'],
			'cateid'=>$art['cateid'],
			'password'=>$art['password'],
			'tags'=>$art['tags'],
			'picture'=>$art['p_picture'],
			'comment_count'=>$art['comment_count'],
			'status'=>$art['status'],
			'extraData'=>$art['extra_data']
		);
	}
	return $arts;
}


function getArticleByAlias($alias){
	$allpost = Cache::getInstance()->readCache("postalias");
	if(isset($allpost[$alias])){
		$article = getArticlesByPid($allpost[$alias]);
		if($article){
			return $article[0];
		}
	}
	return null;
}

function getPostByPid($pid){
	global $ycms_db;
	if(!is_numeric($pid)){
		$aitlces = cache_get(CACHE_POST,CACHE_POST_LIST);
		if(!$aitlces){
			$aitlces = array();
		}		
		$articles = buildArray($aitlces,"alias");
		if(!isset($articles[$pid])){
			$query = $ycms_db->prepare("SELECT * FROM ".DB_PREFIX."POSTS WHERE ALIAS = %s",$pid);
			$article = $ycms_db->get_row($query);
			$articles[$article['alias']] = array();
			$articles = buildArray($aitlces,"pid");
			cache_write($articles, CACHE_POST,0,CACHE_POST_LIST);
			cache_write($article, CACHE_POST,0,$article['pid']);
			return $article;
		}
		$pid = $articles[$pid]['pid'];
	}
	$post = cache_get(CACHE_POST,$pid);
	if(!$post){
		$query = $ycms_db->prepare("SELECT * FROM ".DB_PREFIX."POSTS WHERE pid = %d",$pid);
		$post = $ycms_db->get_row($query);
		if(!$post){return null;}
		cache_write($post, CACHE_POST,0,$post['pid']);
	}
	return $post;
}

function getAuthorNameById($authorId){
	$users = Cache::getInstance()->readCache("users");
	return isset($users[$authorId]) ? $users[$authorId]['user_nicename'] : "";
}

function getArticleContentByAlias($alias){
	$article = getArticleByAlias($alias);
	if($article === null){
		return null;
	}
	return $article['content'];
}

function getArticlePictureByAlias($alias){
	$article = getArticleByAlias($alias);
	if($article === null){
		return null;
	}
	return $article['p_picture'];
}

function getRandomPosts($count,$cateid = 0){
	$allpost = null;
	$cache = Cache::getInstance();
	if(is_numeric($cateid) && $cateid < 1){
		$allpost = $cache->readCache("postalias");
	}else{
		$cates = $cache->readCache("category");
		$cates = is_numeric($cateid) ? $cates['cateid'] : $cates['alias'];
		if($cates[$cateid]){
			$allpost = $cache->readCache("categorypost");
			$allpost = $allpost[$cates[$cateid]['cate_id']];
		}
	}
	if(!$allpost){
		return array();
	}
	$count = count($allpost) > $count ? $count : count($allpost);
	$posts = array_rand($allpost,$count);
	$pids = array();
	foreach ($posts as $alias) {
		$pids[] = $allpost[$alias];
	}
	return getsimpleArticle(getArticlesByPid($pids));
}

/**
 * 根据tag获取文章
 * @param string|array $tag 多个tag可用arra或者英文逗号分开的字符串
 * @param int $count 读取多少个 ,0为读取全部，默认为0
 */
function getPostsByTags($tag,$count = 0){
	$tags = $tag;
	if(is_string($tag)){
		$tags = explode(",", $tag);
	}
	$article_tags = Cache::getInstance()->readCache('posttags');
	$articles_ids = array();
	foreach ($tags as $t) {
		if(isset($article_tags[$t])){
			$articles_ids = _merge_array($articles_ids,$article_tags[$t]);
		}
	}
	$count = count($articles_ids) > $count ? $count : count($articles_ids);
	if ($count > 0) {
		$articles_ids = getArrayByKeys($articles_ids, array_rand($articles_ids,$count));
	}
	return getsimpleArticle(getArticlesByPid($articles_ids));
}

function getArticleExtraData($article,$key){
	$extraData = $article['extraData'];
	if(!$extraData) return null;
	$extraData = unserialize($extraData);
	$keys = explode('/', $key);
	$value = null;
	switch (count($keys)) {
		case 1:
			$value = $extraData[$keys[0]];
			break;
		case 2:
			$value = $extraData[$keys[0]][$keys[1]];
			break;
	}
	return $value;
}

/**
 * 根据Id获取
 * Enter description here ...
 * @param unknown_type $pid
 */
function getArticlesByPid($pid){
	$pids = $pid;
	if(!is_array($pid)){
		$pids = explode(",", $pid);
	}
	$posts = array();
	$pids = implode(',', $pids);
	if($pids){
		return MySql::getInstance()->fetch_table('posts',' pid in(0,'.$pids.')');
	}
	return array();
}

/**
 * 内容分页
 * @param int|string  $pid 文章ID或者别名
 * @return array 分页后的数组
 */
function fetchArticleContent($pid,$returntitle = false){
	$article = null;

	if(is_numeric($pid)){
		$articles = getArticlesByPid($pid);
		if(count($articles) > 0){
			$article = $articles[0];
		}
	}else{
		$article = getArticleByAlias($pid);
	}
	if($article){
		$title = $article['title'];
		$article = explode("_ueditor_page_break_tag_", $article['content']);
		if($returntitle){
			return array("title"=>$title,'contents'=>$article);
		}
		return $article;
	}
	return null;
}

function fetchArticleContentPage($article){
	if($article){
		$article = explode("_ueditor_page_break_tag_", $article['content']);
		return $article;
	}
	return null;
}

/**
 * 根据文章获取评论数
 * @param int $pid
 */
function getCommentsCount($pid){
	return 0;
}

/**
 * 获取友情链接
 * @param int $count 数量
 * @param int $start 开始
 */
function getLinks($count = 0,$start = 0){
	$links = Cache::getInstance()->readCache("links");
	if($count < 1 && $start < 1){
		return $links;
	}
	$ls = array();
	$index = 0;
	$total = count($links);
	if($start > 0 && $total <= $start){
		return $ls;
	}
	$end = $start + $count;
	if($count < 1){
		$end = $total;
	}

	foreach ($links as $value) {
		if($index >= $start && $index < $end){
			$ls[] = $value;
		}
		$index ++;
	}
	return $ls;
}

/**
 * 读取分类文章
 * @param string|int $cateid 分类ID或者分类别名
 * @param int $page 	当前页数
 * @param int $pagesize 读取条数
 */
function getCategoryArticle($cateid,$page,$pagesize=0,$total=false){
	$category = Cache::getInstance()->readCache("category");
	if(!is_numeric($cateid) && is_string($cateid)) {
		if(isset($category['alias'][$cateid])){
			$cateid = $category['alias'][$cateid]['cate_id'];
		}
	}
	if (!isset($category['cateid'][$cateid])) {
		show_404_page();
	}

	$mysql = MySql::getInstance();
	$start = ($page - 1) * $pagesize;
	$limit = "";
	if($pagesize > 0){
		$limit = " limit $start,$pagesize";
	}
	$total = MySql::getInstance()-> getCount("posts"," visible = 'y' and cateid = $cateid");
	$articles = $mysql->fetch_table("posts"," visible = 'y' and cateid=$cateid order by date desc".$limit);
	if(!$total){
		return getsimpleArticle($articles);
	}
	return array("list"=>getsimpleArticle($articles),"total"=>$total);
}

function getThumbPicture($url,$width,$height){
	return $url;
}

function getAvailableCategory(){
	global $ycms_db;
	$cates = cache_get(CACHE_CATEGORY);
	if(!$cates){
		$cates = $ycms_db->get_results("SELECT * FROM ".DB_PREFIX."categorys WHERE visible = 'y'");
		$cates = buildArray($cates,'cate_id');
		cache_write($cates, CACHE_CATEGORY,3600 * 24);
	}
	return $cates;
}