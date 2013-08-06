<?php include_once admin_template('header',1);?>

<div class="container-fluid">
	<div class="subnav">
		<ul class="subsubsub">
			<li<?php if($plugin_status == "all"){echo ' class="active"';}?>><a href="admin_plugins.php?plugin_status=all">全部 <span
					class="count">(<?php echo $allcount;?>)</span> </a> |</li>
			<li<?php if($plugin_status == "active"){echo ' class="active"';}?>><a href="admin_plugins.php?plugin_status=active">启用 <span
					class="count">(<?php echo $activeCount;?>)</span> </a> |</li>
			<li<?php if($plugin_status == "inactive"){echo ' class="active"';}?>><a href="admin_plugins.php?plugin_status=inactive">未启用
					<span class="count">(<?php echo $deactiveCount;?>)</span> </a></li>
		</ul>
	</div>
	<div class="row-fluid">
		<div class="well">
	    	<table class="table">
	      		<thead>
		        <tr>
		          <th>插件</th>
		          <th>状态</th>
		          <th>版本</th>
		          <th>描述</th>
		          <th style="width: 76px;"></th>
		        </tr>
		      </thead>
		      <tbody>
		        <?php foreach ($allplugins as $plugin):
		        	?><tr>
		          <td><?php echo $plugin['name']; ?></td>
		          <td><?php echo $plugin['active'] ? "已启用" : "未启用"; ?></td>
		          <td><?php echo $plugin['version']; ?></td>
		          <td><?php echo $plugin['description']; ?>
						<div class="plugin_more">
							作者: <a href="<?php echo $plugin['author_site']; ?>" title="访问作者主页"><?php echo $plugin['author']; ?></a> |
							<a href="<?php echo $plugin['install']; ?>" title="访问插件主页">访问插件主页</a>
						</div>
					</td>
		          <td>
		              <a class="messageOrgo" href="?action=<?php echo $plugin['active']?"deactive":"active"?>&plugin=<?php echo urlencode($plugin['file']);?>"><?php echo $plugin['active']?"停":"启"?>用</a>
		              <a class="messageOrgo" href="?action=del&plugin=<?php echo urlencode($plugin['file']);?>" role="button"  data-confirm="确认是否删除插件“<?php echo $plugin['name'];?>” ?">删除</a>
		          </td>
		        </tr>
		        <?php endforeach;?>
		      </tbody>
		    </table>
		</div>                  
	</div>
</div>
<?php admin_template('footer');?>