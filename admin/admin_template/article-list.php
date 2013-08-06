<?php admin_template('header');?>
<div class="container-fluid">
	<div class="row-fluid">
                    
		<div class="btn-toolbar">
			<a href="?action=edit&cateid=<?php echo $cateid;?>&pid=0" class="btn btn-primary"><i class="icon-plus"></i> 发布新文章</a>
			<div class="btn-group"></div>
		</div>
		<div class="well">
	    	<table class="table">
	      		<thead>
		        <tr>
		          <th>#</th>
		          <th>标题</th>
		          <th>作者</th>
		          <th>发布时间</th>
		          <th>浏览数</th>
		          <th>评论数</th>
		          <th>标签</th>
		          <th style="width: 26px;"></th>
		        </tr>
		      </thead>
		      <tbody>
		        <?php foreach ($articles as $key=>$article):
		        	?><tr>
		          <td><?php show($key+1) ?></td>
		          <td><?php show($article['title']) ?></td>
		          <td><?php show(loadUser($article['author'],1)) ?></td>
		          <td><?php show(date("Y-m-d H:i",$article['date'])) ?></td>
		          <td><?php show($article['views']) ?></td>
		          <td><?php show(getCommentsCount($article['pid'])) ?></td>
		          <td><?php show($article['tags']) ?></td>
		          <td>
		              <a href="?action=edit&cateid=<?php echo $cateid;?>&pid=<?php echo $article['pid'];?>"><i class="icon-pencil"></i></a>
		              <a href="#myModal" role="button"  data-confirm="确认是否删除此文章 ?"><i class="icon-remove"></i></a>
		          </td>
		        </tr>
		        <?php endforeach;?>
		      </tbody>
		    </table>
		</div>
		<div class="pagination">
		    <?php admin_pagination($totalcount, $pagesize, $pageNo, 'admin_edit.php?action=article&cateid=1');?>
		</div>                    
	</div>
</div>
<?php admin_template('footer');?>