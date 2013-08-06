<?php admin_template('header');?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<form id="frm_add" action="admin_edit.php?action=save" class="validate" method="post">
				<div class="block span8" style="border:none;padding:10px;">				
					<input type="hidden" name="submit" value="submit">		
					<input type="hidden" name="cateid" value="<?php echo getgpc("cateid");?>">			
					<input type="hidden" name="password" value="">			
					<input type="hidden" name="reason" value="">			
					<input type="hidden" name="pid" value="<?php echo $pid;?>">
					<div class="form_main">						
						<input type="text" class="input-xlarge txt_input_100" name="title" placeholder="在此填入标题" value="<?php echo $log['title'];?>" style="width: 98%;">
						<div class="new-post-alias"><strong>固定链接：</strong><?php echo SITE_URL;?><span id=""><input type="text" class="post-alias" name="alias" value="<?php echo $log['alias'];?>"></span></div>
						<textarea name="content" class="input-xlarge txt_input_100" id="ueditor" style="height: 350px;"><?php echo $log['content'];?></textarea>
					</div>
				
					<div class="block">
						<a href="#post-excerpt" class="block-heading" data-toggle="collapse">摘要 </a>
				        <div id="post-excerpt" class="block-body collapse in">
				          <textarea name="excerpt" class="text_area_post"><?php echo $log['excerpt'];?></textarea>
				        </div>
					</div>
					
					<div class="block border_span">
						<a href="#post-other" class="block-heading" data-toggle="collapse">其他信息</a>
				        <div id="post-other" class="block-body collapse in">
				        	<label>文章排序<span class="desc">数值越大排位越前</span></label>
				          	<input type="text" class="text_area_post" name="p_order" value="<?php echo $log['p_order'];?>">
				        	
				        </div>
					</div>
				</div>
			
				<div class="block span4 border_span">
			        <p class="block-heading" data-toggle="collapse">发布 </p>
			        <div id="widget1container" class="block-body in collapse" style="height: auto;">
			            <div id="button_save_groups" style="margin-top: 1em;">
			            	<button class="btn" id="save-draft">保存草稿</button>
			            	<button class="btn pull-right" id="preview_post" loading="left">预览</button>
			            </div>
			            <div id="post-type"></div>
			            <div id="post-status"></div>
			            <div id="post-datetime"></div>
			            <div id="post-save_button" class="btn-toolbar">
			            	<button class="btn pull-right" id="save_post" loading="left">
									<i class="icon-save"></i> 保存
							</button>
							<div class="cl_btn_groups">  </div>
			            </div>
			        </div>			        
			    </div>
			    
			    <div class="block span4 border_span">
					<a href="#post-tags" class="block-heading" data-toggle="collapse">标签 </a>
				    <div id="post-tags" class="block-body collapse in">
				        <input type="text" class="text_area_post" name="tags" value="<?php echo $log['tags'];?>">
				        <label class="input_notice">多个标签请用英文逗号（,）分开</label>
					</div>
			    </div>
			    
			    <div class="block span4 border_span">
					<a href="#post-picture" class="block-heading" data-toggle="collapse">特色封面</a>
				    <div id="post-picture" class="block-body collapse in">
				    	<div class="link_up_image_box">
				    		<input type="hidden" name="p_picture" id="p_picture" value="<?php echo $log['p_picture'];?>">
				        	<a href="#p_picture" class="link_up_image" type="file" complete="p_picture_updone">添加封面图片</a>
				    	</div>
				    	<?php 
				    		if($log['p_picture']){
				    	?>
				    	<div class="have-post_picture">
				    		<img src="../<?php echo $log['p_picture'];?>" class="image-post_picture">
				    		<a href="#" class="remove-post_picture">移除封面图片</a>
				    	</div>
				    	<?php }?>
					</div>
			    </div>
			    <?php 
			    	global $hooks_arr;
			    	if($hooks_arr['admin_article_post_view']){
			    		$extdata = unserialize($log['extra_data']);
			    		foreach ($hooks_arr['admin_article_post_view'] as $value) {
			    			$data = call_user_func($value);
			    			if($data['id'] && $data['options']){
			    				$curextData = isset($extdata[$data['id']]) ? $extdata[$data['id']] : array();
			    			?>
			    <div class="block span4 border_span">
					<a href="#post-<?php echo $data['id'];?>" class="block-heading" data-toggle="collapse"><?php echo $data['name'];?></a>
				    <div id="post-<?php echo $data['id'];?>" class="block-body collapse in">
				    		<?php 
				    		foreach ($data['options'] as $option) {
				    		?>
				    		<label><?php echo $option['name']?><span class="desc"><?php echo $option['desc']?></span></label>
							
				    		<?php 
				    			$keyname = "extra_data[".$data['id']."][".$option['id']."]";
				    			$value = isset($extdata[$data['id']][$option['id']]) ? $extdata[$data['id']][$option['id']] : "";
				    			if($option['type'] == 'file'){
				    				echo '<input type="text" value="'.$value.'" class="input-xlarge file" name="'.$keyname.'">';	
				    			}
				    			if($option['type'] == 'text'){
				    				echo '<input type="text" value="'.$value.'" class="input-xlarge" name="'.$keyname.'">';	
				    			}
				    		}?>
				    </div>
				</div>
			    			<?php
			    			}
			    		}
			    	}
			    ?>
			    <div class="cl_btn_groups">  </div>
			</form>
		</div>
	</div>

<script type="text/javascript">
$(function(){
	$("#save_post,#preview_post,#save-draft").click(function(e){
		save_post.call(this, "save");
		return false;
	});
	$(".have-post_picture").each(function(){
		setTimeout(function(){
			$("div.link_up_image_box").hide();
		}, 200);
		$(this).find(".remove-post_picture").click(function(){
			$(this).parent().remove();
			$("#p_picture").val('');
			$("div.link_up_image_box").show();
			return false;
		});
	});
	function save_post(op){
		var position = $(this).attr("loading") ? $(this).attr("loading") : 'right';
		var loading = $(this).attr('disabled',true).showLoading(position);
		var button = this;
		$.post($("#frm_add").attr("action") + "&op=" + op,$("#frm_add").serialize(),function(data){
			loading.remove();
			$(button).attr('disabled',false);
			if(data['code'] == 0){
				$(data['msg']).showErrorNotice({show:0.9,f:true});
				setTimeout(function(){
					location.href= 'admin_edit.php?action=article&cateid=<?php echo getgpc("cateid");?>';
				}, 500);
			}else{
				$().showErrorNotice({show:0,msg:JSON.stringify(data['msg']),close:1});
			}
		},"json");
	}
	
});
function p_picture_updone(url){
	var img = $('<img src="../' + url + '" class="image-post_picture" />');
	var remove = $('<a href="#" class="remove-post_picture">移除封面图片</a>');
	$(this.attr("href")).val(url);
	var _p = this.parent().parent().parent();
	_p.append(img).append(remove);
	this.parent().parent().hide();
	var me = this;
	remove.click(function(){
		me.show();
		remove.remove();
		img.remove();
		return false;
	});
}
</script>

<?php admin_template('footer');?>