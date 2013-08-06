<?php admin_template('header');?>

<div class="container-fluid">
	<div class="row-fluid">
		<div id="current-theme" class="has-screenshot">
			<img src="<?php echo SITE_URL.$currentTheme['preview'];?>"
				alt="当前主题预览">
			<div class="theme-info">
				<h3>当前主题</h3>
				<h4><?php echo $currentTheme['name'];?></h4>
				<div class="theme-more-info">
					<div>
						<span>作者: 
							<a href="<?php echo $currentTheme['author_site'];?>" target="_blank"
							title="访问作者主页"><?php echo $currentTheme['author'];?> </a> </span>
						<span style="margin-left: 10px;">版本: <?php echo $currentTheme['version'];?>
						</span>
					</div>
					<p class="theme-description">
					<?php echo $currentTheme['description'];?>
					</p>
				</div>
				<?php if ($currentThemesOptions) :?>
				<div class="theme-options">
					<span>选项：</span>
					<ul>
						<?php foreach ($currentThemesOptions as $opname=>$setting) :?>
						<li><a href="theme_option.php?op=<?php echo $opname;?>"><?php echo $setting['name'];?></a></li>
						<?php endforeach;?>
					</ul>
				</div>
				<?php endif;?>
			</div>
			<br class="clear">
		</div>

		<div id="themes-list">
			<?php foreach ($themes as $theme) :?>
			<div class="available-theme">
				<img src="../<?php echo $theme['preview']?>" alt="">

				<div class="theme-name-version">
					<span class="theme-name"><?php echo $theme['name']?></span>
					<strong>版本号：</strong><?php echo $theme['version']?>
					</div>
				<div class="theme-author">
					作者为 <a href="<?php echo $theme['author_site']?>" target="_blank" title="访问作者主页"><?php echo $theme['author']?></a>
				</div>
				<div class="action-links">
					<a class="messageOrgo" href="?action=use&theme=<?php echo $theme['file']?>" class="useit">使用</a>
					<a class="messageOrgo" href="?action=del&theme=<?php echo $theme['file']?>" data-confirm="将删除“Thunder”主题 <br />点击“取消”放弃，点击“确认”删除。">删除</a>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
</div>

<?php admin_template('footer');?>