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
	<link rel="stylesheet" href="admin_template/lib/poshytip_1.2/tip-yellowsimple/tip-yellowsimple.css" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="admin_template/stylesheets/theme.css">
    <link rel="stylesheet" href="admin_template/lib/font-awesome/css/font-awesome.css">

    <script src="admin_template/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="admin_template/lib/poshytip_1.2/jquery.poshytip.js" type="text/javascript"></script>
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
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
                    
                </ul>
                <a class="brand" href="#"><span class="first">网站后台</span> <span class="second">管理系统</span></a>
        </div>
    </div>
    


    

    
        <div class="row-fluid">
    <div class="dialog">
        <div class="block">
            <p class="block-heading">Sign In</p>
            <div class="block-body" style="margin-top:0px;">
            	<h3 class="login_error" style="color:red;margin: 0;text-align: center;font-size: 13px;height: 20px;"></h3>
                <form action="/admin/admin_login.php?redirect_to=<?php echo $redurect_to;?>" method="post">
                    <label>登陆ID</label>
                    <input type="text" name="loginid" class="span12">
                    <label>登陆密码</label>
                    <input type="password" name="loginpass" class="span12" value="">
					<input type="hidden"  name="submit" value="submit">
                    <input type="submit" class="btn btn-primary pull-right" value="登 陆">
                    <label class="remember-me"><input type="checkbox" name="remberme"> 记住我</label>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>


    


    <script src="admin_template/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">
       eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('$("[p=f]").f();$("1.o").2({q:\'s-t\',u:\'必须填写此项\',v:\'w\',y:\'z\',A:\'B-C\',D:J,O:3});$("P").n(6(){i b=r;5(!$("1[4=d]").h()){$(\'1[4=d]\').2(\'m\',\'必须填写登陆x\').2(\'j\');b=7}8{$(\'1[4=d]\').2(\'g\')}5(!$("1[4=9]").h()){$(\'1[4=9]\').2(\'m\',\'必须填写登陆密码\').2(\'j\');b=7}8{$(\'1[4=9]\').2(\'g\')}5(b){i c=$(E);$.F(c.G("H"),c.I(),6(a){5(a[\'K\']==0){L.M=a[\'N\']}8{$("k.l").e(a[\'Q\']);R(6(){$("k.l").e(\'\')},S)}},"T")}U 7});',57,57,'|input|poshytip||name|if|function|false|else|loginpass||||loginid|html|tooltip|hide|val|var|show|h3|login_error|update|submit|span12|rel|className|true|tip|yellowsimple|content|showOn|none|ID|alignTo|target|alignX|inner|left|offsetX|this|post|attr|action|serialize|100|code|location|href|main|offsetY|form|msg|setTimeout|5000|json|return'.split('|'),0,{}))
    </script>
    
  </body>
</html>