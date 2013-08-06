<?php
/**
 * @package index_bottom_data
 * @version 1.0
 */

/*
Plugin Name: 首页底部图片数据保存
Plugin URI: http://ycms.xiaoyan.me/plugins/index_bottom_data
Description: 发表文章时保存额外的数据
Author: Xiaoyan
Version: 1.0
Author URI: http://xiaoyan.me/
*/

function admin_post_index_bottom_data(){
	$shortname = "index_bottom_";
	$filed = array(
		'id'	=> $shortname.'data',
		'name'	=> '首页展示图片'
	);
	$filed['options'][] = array(
		'name' => '大图',
		'desc' => '大图',
		'id'   => $shortname.'bigpicture',
		'type'   => 'file'
	);
	$filed['options'][] = array(
		'name' => '小图',
		'desc' => '小图',
		'id'   => $shortname.'smallpicture',
		'type'   => 'file'
	);
	$filed['options'][] = array(
		'name' => '产品长图',
		'desc' => '首页随机和产品页面的长图',
		'id'   => $shortname.'longpicture',
		'type'   => 'file'
	);
	
	$filed['options'][] = array(
		'name' => '我去',
		'desc' => '这么回事我去呢',
		'id'   => $shortname.'ofuckpicture',
		'type'   => 'text'
	);
	return $filed;
}
add_action("admin_article_post_view", "admin_post_index_bottom_data");


function admin_post_index_bottom_data_save(){
	
}
add_action("admin_article_post", "admin_post_index_bottom_data_save");