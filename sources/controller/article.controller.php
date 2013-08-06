<?php
class Article extends Controller{

	public function showList($param){
		$this->display("list");
	}

	public function displayPost($param){
		$pid = $param['pid'];
		the_post($pid);
		$this->display("detail");
	}
	
	public function search($param){
		$pagesize = Option::get("pagesize",5);
		$keyword = urldecode($param[1]);
		$page = isset($param[4]) ? $param[4] : 1;
		
		$start = ($page - 1) * $pagesize;
		$limit = "";
		if($pagesize > 0){
			$limit = " limit $start,$pagesize";
		}
		
		$articles = MySql::getInstance()->fetch_table("posts"," (tags like '%$keyword%' or title like '%$keyword%') and cateid = 2".$limit);
		$total =  MySql::getInstance()-> getCount("posts"," (tags like '%$keyword%' or title like '%$keyword%') and cateid = 2");
		$pagestr = fetch_pagination($total, $pagesize, $page, "search/$keyword",5,1);
		$this->assign('articles', getsimpleArticle($articles));
		$this->assign("keyword", $keyword);
		$this->assign("pagestr", $pagestr);
		$this->display("category");
	}
	
	public function showCategory($param){
		$pagesize = Option::get("pagesize",5);
		$cateid = $param[1];
		$page = isset($param[4]) ? $param[4] : 1;
		$articles = getCategoryArticle($cateid, $page,$pagesize,1);
		$total = $articles['total'];
		$articles = $articles['list'];
		$pagestr = fetch_pagination($total, $pagesize, $page, "category/story",5,1);
		$this->assign('articles', $articles);
		$this->assign("pagestr", $pagestr);
		$this->assign("keyword", '');
		$this->display("category");
	}
}