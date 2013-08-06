<?php
/**
 * @package Hello_Tips
 * @version 1.0
 */
/*
Plugin Name: Hello Tips
Plugin URI: http://ycms.xiaoyan.me/plugins/hello_tips
Description: 这是ycms的第一个plugin,它展示怎么在系统中使用plugin
Author: Xiaoyan
Version: 1.0
Author URI: http://xiaoyan.me/
*/

function get_hello_lyric() {
	/** These are the lyrics to Hello Tips */
	$lyrics = array(
		'你可以在文章中上传多个附件',
		'在撰写文章的时候你可以使用Tab键方便的缩进内容',
		'你可以为你的文章写一段漂亮的摘要，这样仅让摘要显示在首页并出现阅读全文链接',
		'你可以在文章中插入flash格式的多媒体文件',
		'不一样的心情，文章表情图标为您传达',
		'你可以把你未写完的文章保存到草稿箱里，等下次有时间的时候再写',
		'你可以把图片附件嵌入到内容中，让你的文章图文并茂',
		'你可以在写文章的时候为文章设置访问密码，只让你授予密码的人访问',
		'新建一个允许发表评论的页面，你会发现其实它还是一个简单的留言板',
		'检查你的站点目录下是否存在安装文件：install.php，有的话请删除它',
		'及时升级浏览器，更好的体验系统',
		'今天你备份数据了吗？',
		'从明天起，做一个幸福的人。喂马，砍柴，周游世界'
	);
	//随机一条Tip并返回
	$i = mt_rand(0, count($lyrics) - 1);
	return $lyrics[$i];	
}

// This just echoes the chosen line, we'll position it later
function hello_tips() {
	$chosen = get_hello_lyric();
	echo "<p id='dolly'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_header_tips', 'hello_tips' );

// We need some CSS to position the paragraph
function tips_css() {

	echo "
	<style type='text/css'>
	#dolly {
		float: left;
		padding: 0 0 0 20px;	
		margin: 0;
		font-size: 14px;
		background: url(../content/resources/static/icon_tips.png) 0 center no-repeat;
	}
	</style>
	";
}

add_action( 'admin_head', 'tips_css' );
?>
