<?php get_header();?>

<div id="content">
	<div id="contentleft">
		<?php if(the_post()) : ?>
		<div class="post">
		

	
		<h2><?php the_title();?></h2>
		<p class="date">作者：<?php the_author();?> 发布于：<?php the_time('Y-m-d');?>
				分类：<?php the_category()?>
	 		</p>
		<div class="post-desc"><?php the_content(); ?></div>
		<p class="tag">标签:	<?php the_tag();?></p>

		<div id="related_log" style="font-size:12px">
		<p><b>相关日志：</b></p>
		<p><a href="http://be-evil.org/custom-your-chrome-inspector-code-theme.html">个性化你的Chrome开发者工具代码主题</a></p>
		</div>
		<div class="nextlog">
				« <a href="http://be-evil.org/how-to-resize-your-broswer-window.html">前端开发技巧分享：如何将你的浏览器窗口设定到指定分辨率</a>
				|
				 <a href="http://be-evil.org/post-306.html">PHP JSON_DECODE/JSON_ENCODE 数据时某些字段为NULL的分析</a>»
		</div>
		
	   <div id="pagenavi">
			<span>1</span> <a href="http://be-evil.org/page/2">2</a> <a
				href="http://be-evil.org/page/3">3</a> <a
				href="http://be-evil.org/page/4">4</a> <a
				href="http://be-evil.org/page/5">5</a> <a
				href="http://be-evil.org/page/6">6</a> <em>...</em> <a
				href="http://be-evil.org/page/56" title="尾页">&raquo;</a>
		</div>
		<div id="post-comments">
			<?php do_action("simple-comment-area")?>
			<!-- UY BEGIN -->
			<div id="uyan_frame"></div>
			<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=96922"></script>
			<!-- UY END -->
		</div>
		<div style="clear:both;"></div>
	</div>
		<?php endif;?>
	</div>
	<!-- end #contentleft-->
	<?php  get_view('sidebar');?>
	<!--end #siderbar-->
</div>
<?php  get_footer();?>