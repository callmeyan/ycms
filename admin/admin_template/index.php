<?php include_once admin_template('header');?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="save_notice" style="padding:10px;border: solid 1px #ccc;background: #FFF2F2;display: none;">保存成功</div>
			<form action="?action=index" method="post">
			<input type="hidden" name="submit" value="submit">
			<div class="btn-toolbar">
				<button class="btn btn-primary" type="submit">
					<i class="icon-save"></i> 保存
				</button>
				<div class="btn-group"></div>
			</div>
			<div class="well">
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane active in" id="home">						
							<label>活动1(左上)图片地址</label>
							<input type="text" value="<?php echo $conf['ads1_img'];?>" class="input-xlarge" name="ads1_img">
							<label>活动1(左上)链接地址</label>
							<input type="text" value="<?php echo $conf['ads1_link'];?>" class="input-xlarge" name="ads1_link">  
							<label>活动2(左下)图片地址</label>
							<input type="text" value="<?php echo $conf['ads2_img'];?>" class="input-xlarge" name="ads2_img">
							<label>活动2(左下)链接地址</label>
							<input type="text" value="<?php echo $conf['ads2_link'];?>" class="input-xlarge" name="ads2_link">
							<label>活动3(右)图片地址</label>
							<input type="text" value="<?php echo $conf['ads3_img'];?>" class="input-xlarge" name="ads3_img">
							<label>活动3(右)链接地址</label>
							<input type="text" value="<?php echo $conf['ads3_link'];?>" class="input-xlarge" name="ads3_link">
							<label>首页幻灯片<span class="desc">一行一页图片在前地址在后用|分割</span></label>
							<textarea class="input-xlarge" name="index_slider" style="width: 800px; height: 300px;"><?php echo $conf['index_slider'];?></textarea>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>

<script type="text/javascript">
$(function(){
	$("form").submit(function(){
		$.post($(this).attr("action"),$(this).serialize(),function(data){
			if(data['code'] == 0){
				$("div.save_notice").html(data['msg']).css({opacity:1}).show();
				setTimeout(function(){
					$("div.save_notice").animate({opacity:0},500,function(){
						$("div.save_notice").hide();
					});
				}, 1000);
			}else{
				$("div.save_notice").html(data['msg']).css({opacity:1}).show();
				setTimeout(function(){
					$("div.save_notice").animate({opacity:0},500,function(){
						$("div.save_notice").hide();
					});
				}, 3000);
			}
		},"json");
		return false;
	});
});
</script>

<?php include_once admin_template('footer');?>