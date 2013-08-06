<?php admin_template('header');?>

<div class="container-fluid">
	<div class="row-fluid">
		<?php if ($save_success) :?>
		<div id="theme-options-save-notice" style="padding:10px;border: solid 1px #ccc;background: #FFF2F2;">保存成功</div>
		<?php endif;?>
		<div id="theme-options">
			<form action="?op=<?php echo $option;?>" method="post">
				<input type="hidden" name="submit" value="submit">
				<div class="btn-toolbar">
					<button class="btn btn-primary" type="submit">
						<i class="icon-save"></i> 保存
					</button>
					<div class="btn-group"></div>
				</div>
				<div class="well">
					<?php foreach ($settings as $id => $set) :
						$keyid = "theme_op_".$option."[".$id."]";
					?>
					<label><?php echo $set['title']?>
						<span class="desc"><?php echo $set['desc']?></span>
					</label>
					<?php 
						$value = str_replace('\"', '"', $settings_values[$id]);
						if($set['type'] == 'file'){
				    		echo '<input type="text" value="'.$value.'" class="input-xlarge file" name="'.$keyid.'">';	
				    	}
				    	if($set['type'] == 'text'){
				    		echo '<input type="text" value="'.$value.'" class="input-xlarge" name="'.$keyid.'">';	
				    	}
				    	if($set['type'] == 'textarea'){
				    		echo '<textarea class="input-xlarge" name="'.$keyid.'">'.$value.'</textarea>';	
				    	}
					endforeach;?>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
<!--
$(function(){
	var _save_notice = $("#theme-options-save-notice");
	if(_save_notice.length > 0){
		if(_save_notice.css("display") != "none"){
			setTimeout(function(){
				_save_notice.remove();
			}, 1000);
		}
	}
});
//-->
</script>
<?php admin_template('footer');?>