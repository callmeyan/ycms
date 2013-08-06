<?php get_header();?>

<div id="content">
	<div id="contentleft">
		<?php if(have_posts()) : ?>
		<?php while (get_posts(3)) : the_post();?>
		<div class="post">
			<h2>
				<a href="<?php the_postlink(); ?>"><?php the_title();?></a>
			</h2>
			<p class="date">
				作者：<?php the_author();?>
				发布于：<?php the_time('Y-m-d');?>
				分类：<?php the_category();?>

			</p>
			<div class="post-desc">
				<p><?php the_expert();?></p>
			</div>
			<p class="tag">
				标签: <a href="http://be-evil.org/tag/php">php</a>
			</p>
			<p class="count">
				<a href="http://be-evil.org/fix-connect-local-mysql-slow-in-windows.html#comments">评论(0)</a>
				<a href="http://be-evil.org/fix-connect-local-mysql-slow-in-windows.html#tb">引用(0)</a>
				<a href="http://be-evil.org/fix-connect-local-mysql-slow-in-windows.html">浏览(332)</a>
			</p>
			<div class="clear"></div>
		</div>
		<?php endwhile;?>
		<?php endif;?>
		<div id="pagenavi">
			<span>1</span> <a href="http://be-evil.org/page/2">2</a> <a
				href="http://be-evil.org/page/3">3</a> <a
				href="http://be-evil.org/page/4">4</a> <a
				href="http://be-evil.org/page/5">5</a> <a
				href="http://be-evil.org/page/6">6</a> <em>...</em> <a
				href="http://be-evil.org/page/56" title="尾页">&raquo;</a>
		</div>

	</div>
	<!-- end #contentleft-->
	<?php  get_view('sidebar');?>
	<!--end #siderbar-->
</div>
<?php  get_footer();?>