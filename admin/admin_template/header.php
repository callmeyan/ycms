<?php global $YC_ADMIN;?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <title>后台管理系统</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="admin_template/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="admin_template/lib/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="admin_template/lib/poshytip_1.2/tip-yellowsimple/tip-yellowsimple.css" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="admin_template/stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="admin_template/stylesheets/style.css">
	<style type="text/css">
	label span.desc{color: #CCC;font-size: 12px;margin-left: 10px}
	.swfupload_box{}
	.swfupload_box .status_bar{background-color: #EEEEEE;background: -webkit-gradient(linear, left bottom, left top, color-stop(0, #E6E6E6), color-stop(1, white));text-align: center;position: absolute;width: 0%;top: 1px;left: 1px;bottom: 3px;color:#f00;z-index: 90;}
	.swfupload{position: absolute;left: 0;top: 0;z-index: 99;}
	.swfuploadstatus{position: absolute;left: 10px;top: 5px;z-index: 91;}
	</style>
    <script src="admin_template/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="admin_template/lib/poshytip_1.2/jquery.poshytip.js" type="text/javascript"></script>
    <script src="admin_template/jquery.plugins.js" type="text/javascript"></script>
    <script src="admin_template/admin.core.js" type="text/javascript"></script>
	<script type="text/javascript">var admin_url = "<?php echo $YC_ADMIN['url'];?>",url_name="<?php echo $YC_ADMIN['title'];?>";</script>
	<script src="admin_template/lib/datepicker/WdatePicker.js"></script>
	<script src="admin_template/lib/swfupload/swfupload.js"></script>
	<script src="admin_template/lib/fileupload.js"></script>
	<script src="admin_template/lib/bootstrap/js/bootstrap.js"></script>
	<script charset="utf-8" src="admin_template/lib/editor/kindeditor-min.js"></script>
	<script charset="utf-8" src="admin_template/lib/editor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		var fileiploadPath = "swfupload.php";
		var editor = null;
	</script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<?php do_action('admin_head')?>
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    <li id="fat-menu" class="dropdown">
                    	<a href="../" role="button" target="_blank">
                            <i class="icon-home"></i> 首页
                        </a>
                    </li>
                    <li>                    
                        <a href="admin_login.php?action=logout" role="button">
                            <i class="icon-user"></i> 退出
                        </a>
                    </li>
                    
                </ul>
                <a class="brand" href="?action=activity"><span class="first">网站后台</span> <span class="second">管理系统</span></a>
                <span class="tipbox"><?php do_action('admin_header_tips')?></span>
        </div>
    </div>
    
    <div class="sidebar-nav">
        <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-dashboard"></i>Dashboard</a>
        <?php 	$isquick = getgpc("isquick");?>
        <ul id="dashboard-menu" class="nav nav-list collapse<?php if($isquick){echo " in";}?>">
        	<?php 
        	$current_uri = explode('/', $_SERVER['REQUEST_URI']);
        	$current_uri = $current_uri[count($current_uri)-1];
        
        	foreach ($YC_ADMIN['shortcut_links'] as $link):
        		$activ_quick = "";
        		if($isquick && $link['url'] == $current_uri){
        			$activ_quick = ' class="active"';
        		}
        	?>
            <li<?php echo $activ_quick;?>><a href="<?php echo $link['url'];?>"><?php echo $link['name'];?></a></li>
            <?php endforeach;?>
        </ul>
        <?php
        if($YC_ADMIN['config']['show_superman']):
        	foreach ($YC_ADMIN['config']['menus'] as $name => $m):
        	$id = substr(md5($name), 0,10);
        ?>
         <a href="#menu-<?php echo $id;?>" class="nav-header" data-toggle="collapse"><i class="icon-dashboard"></i><?php echo langAdmin("menu/$name");?></a>
         <ul id="menu-<?php echo $id;?>" class="nav nav-list collapse">
         	<?php 
        	foreach ($m as $link_name => $link_url):
        		$activ_me = "";
        		if($link_url == $current_uri || $link_name == $YC_ADMIN['current_menu'] ){
        			$activ_me = ' class="active"';
        		}
        	?>
            <li<?php echo $activ_me;?>><a href="<?php echo $link_url;?>"><?php echo langAdmin("menu/$link_name",$link_name);?></a></li>
            <?php endforeach;do_action("admin_menu_$name");?>
         </ul>
        <?php endforeach;endif;?>
    </div>
    <div class="content">

		<div class="header">
			<h1 class="page-title"><?php echo $YC_ADMIN['title'];?></h1>
		</div>
	
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a> <span class="divider">/</span></li>
			<li class="active"><?php echo $YC_ADMIN['title'];?></li>
			<?php if(!$isquick) :?>
			<li class="add_link_to_menu">
				<a href="#" class="add">添加到快捷菜单中</a>
				<a href="#" class="del" style="display:none;">从快捷菜单中删除</a>
			</li>
			<?php endif;?>
		</ul>