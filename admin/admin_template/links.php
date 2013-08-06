<?php admin_template('header');?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="block span5">
				<form id="frm_add" action="admin_links.php?action=save" class="validate" method="post">
					<input type="hidden" name="submit" value="submit">
					<input type="hidden" name="linkid" value="<?php echo getgpc("linkid","0");?>">
					<div class="form_well">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane active in" id="home">						
									<label>链接名字<span class="desc">必填</span></label>
									<input type="text" value="<?php echo $modify_link['link_name'];?>" required_msg="必须填写链接名字" class="input-xlarge required" name="link_name">
									<label>链接地址<span class="desc">必填</span></label>
									<input type="text" value="<?php echo $modify_link['link_url'];?>" required_msg="必须填写链接地址" class="input-xlarge required"  name="link_url">
									<label>排序<span class="desc">数值越大排位越靠前</span></label>
									<input type="text" value="<?php echo $modify_link['link_order']?$modify_link['link_order']:"0";?>" class="input-xlarge"  name="link_order">
									<label>跳转方式</label>
									<select name="link_target">
										<option value="_blank">新窗口</option>
										<option value="_self"<?php echo $modify_link['link_target'] == '_self' ? ' selected="selected" ':'' ;?>>本窗口</option>
									</select>
									<label>状态</label>
									<select name="link_visible">
										<option value="y">显示</option>
										<option value="n"<?php echo $modify_link['link_visible'] == 'n' ? ' selected="selected" ':'' ;?>>隐藏</option>
									</select>
									<label>链接图像<span class="desc"></span></label>
									<input type="text" value="<?php echo $modify_link['link_image'];?>" class="input-xlarge file"  name="link_image">
									<label>链接描述</label>
									<textarea name="link_description" class="input-xlarge" name="cate_desc" style="width: 300px; height: 100px;"><?php echo $modify_link['link_description'];?></textarea>
							</div>
						</div>
						<div class="btn-toolbar">
							<button class="btn btn-primary" type="submit">
								<i class="icon-save"></i> <?php echo getgpc("linkid",0) > 0 ? "更新":"新增";?>
							</button>
							<a href="admin_links.php" class="btn" data-confirm="确认是否取消编辑?" confirm-title="取消提示">取消</a>
							<div class="btn-group"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="block span7">
		        <p class="block-heading" data-toggle="collapse">链接管理 </p>
		        <div id="widget1container" class="block-body in collapse" style="height: auto;">
		            <table class="table">
		              <thead>
		                <tr>
		                  <th>#</th>
		                  <th>链接名称</th>
		                  <th>排序</th>
		                  <th>状态</th>
		                  <th>跳转方式</th>
		                  <th>描述</th>
						  <th style="width: 26px;"></th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php foreach ($links as $key => $link) :?>
		                <tr>
		                  <td><?php echo $key+1; ?></td>
		                  <td><?php echo $link['link_name']?></td>
		                  <td><?php echo $link['link_order']?></td>
		                  <td><?php echo $link['link_visible'] == 'y' ? '显示' : '隐藏' ;?></td>
		                  <td><?php echo $link['link_target'] == '_self' ? '本窗口' : '新窗口' ;?></td>
		                  <td><span title="<?php echo $link['link_description'];?>"><?php echo subString($link['link_description'], 0, 17);?></span></td>
		                  <td><a href="?op=edit&linkid=<?php echo $link['link_id'];?>"><i class="icon-pencil"></i> </a>
							<a href="?action=del&linkid=<?php echo $link['link_id'];?>" data-confirm="确认是否删除本链接?" confirm-title="删除提示">
								<i class="icon-remove"></i> </a>
							</td>
		                </tr>
		                <?php endforeach;?>
		              </tbody>
		            </table>
		        </div>
		    </div>
		</div>
	</div>

<script type="text/javascript">
$(function(){
	$("#frm_add").submit(function(){
		var link_name = $("input[name=link_name]");
		var link_url = $("input[name=link_url]");
		var pass = checkformele(link_name,link_name.attr('required_msg'),true);
		pass = checkformele(link_url,link_url.attr('required_msg'),true);
		if(pass){
			var loading = $('[type=submit]').attr('disabled',true).showLoading();
			$.post($(this).attr("action"),$(this).serialize(),function(data){
				loading.remove();
				 $('[type=submit]').attr('disabled',false);
				if(data['code'] == 0){
					$(data['msg']).showErrorNotice({show:0.9,f:true});
					setTimeout(function(){
						location.href= data['data'];
					}, 1200);
				}else{
					$(data['msg']).showErrorNotice({show:2});
				}
			},"json");
		}
		return false;
	});
});
</script>

<?php admin_template('footer');?>