<?php
/*
Plugin Name: 发表评论需验证码
Plugin URI: http://ycms.xiaoyan.me/plugins/cwvc
Description: 发表评需要填写验证码,一定程度上有助于防止垃圾评论
Author: xiaoyan
Version: 1.0
Author URI: http://xiaoyan.me
*/

if(isset($_GET["getcode"])){
	cwvc_create_vc_code();
}

session_start();
!defined('IN_YCMS') && exit('access deined!');

function cwvc_show_vc_code(){	
	//echo '<img src="content/plugins/commentwithverifiycode/commentwithverifiycode.php?getcode"><input type="text" name="commentwithverifiycode">';
	echo '<script type="text/javascript">';
	echo '(function(){
	function loadVCode(){
		var comment_form_p = document.getElementById("commentform").getElementsByTagName("p");
		
		var vc_img_c = document.createElement("p");
		
		vc_img_c.innerHTML = \'<img src="content/plugins/commentwithverifiycode/commentwithverifiycode.php?getcode"><input type="text" name="commentwithverifiycode">\';
		comment_form_p.item(0).appendChild(vc_img_c);
	}
	if (document.addEventListener ) {
        document.addEventListener("DOMContentLoaded", loadVCode, false);
    }else{
        window.attachEvent("onload", loadVCode );            
    }
})();
</script>';
}

function cwvc_comment_post(){
	if(strtoupper($_POST['commentwithverifiycode']) != $_SESSION["cwvc_vcode"]){
		errorMsg('评论失败：验证码输入错误');
	}
}

add_action('log_related','cwvc_show_vc_code');
add_action('comment_post','cwvc_comment_post');

function cwvc_create_vc_code(){
	require_once 'vcode.php';
	$vc=new VC();
	$vc->len=5;
	$vc->red=255;
	$vc->blue=255;
	$vc->green=255;
	$vc->_createVC();
}