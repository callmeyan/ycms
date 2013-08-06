<?php admin_template('header');?>
<div class="container-fluid">
	<div class="row-fluid">          
		<?php foreach ($categories as $cate) {
			echo '<a class="article_cate_link" href="?action=article&cateid='.$cate['cate_id'].'">'.$cate['name'].'</a>';
		}?>	 
	</div>
</div>
<style>
a.article_cate_link{border: solid 1px #CEE;
width: 200px;
height: 100px;
display: inline-block;
text-align: center;
line-height: 100px;
margin: 20px;
box-shadow: 1px 1px 1px #F5F5F5;
font-size: 20px;}
a.article_cate_link:hover{box-shadow: 2px 2px 4px #D1EBEB;font-size: 22px;}
</style>
<?php admin_template('footer');?>