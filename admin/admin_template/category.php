<?php admin_template('header');?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="block span6">
				<form id="frm_add" action="admin_category.php?action=save" class="validate" method="post">
					<input type="hidden" name="submit" value="submit">
					<input type="hidden" name="cateid" value="<?php echo getgpc("cateid") ? getgpc("cateid") : 0 ;?>">
					<div class="form_well">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane active in" id="home">						
									<label>分类名字<span class="desc">这将是它在站点上显示的名字。</span></label>
									<input type="text" value="<?php echo $modify_cate['name'];?>" required_msg="必须填写分类名字" class="input-xlarge required" name="cate_name">
									<label>别名<span class="desc">“别名”是在 URL 中使用的别称，它可以令 URL 更美观。通常使用小写，只能包含字母，数字和连字符（-）。</span></label>
									<input type="text" value="<?php echo $modify_cate['alias'];?>" class="input-xlarge" name="alias">
									<label>父级</label>
									<select name="parent_id">
										<option value="0">无</option>
										<?php foreach ($parentCate as $p_cate) {
											$s = isset($modify_cate['parent_id'] )&&$modify_cate['parent_id'] == $p_cate['cate_id'] ? ' selected="selected" ' : '';
											echo '<option value="'.$p_cate['cate_id'].'"'.$s.'>'.$p_cate['name'].'</option>';
										}?>
									</select>
									<label>是否可用</label>
									<select name="visible">
										<option value="y">可用</option>
										<option value="n"<?php echo $modify_cate['visible'] == 'n' ? ' selected="selected" ':'' ;?>>不可用</option>
									</select>
									<label>分类描述</label>
									<textarea name="description" class="input-xlarge" name="cate_desc" style="width: 400px; height: 100px;"><?php echo $modify_cate['description'];?></textarea>
							</div>
						</div>
						<div class="btn-toolbar">
							<button class="btn btn-primary" type="submit">
								<i class="icon-save"></i> 保存
							</button>
							<div class="btn-group"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="block span6">
		        <p class="block-heading" data-toggle="collapse">Collapsible </p>
		        <div id="widget1container" class="block-body in collapse" style="height: auto;">
		            <table class="table">
		              <thead>
		                <tr>
		                  <th>分类名称</th>
		                  <th>别名</th>
		                  <th>是否可用</th>
		                  <th>文章</th>
						  <th style="width: 26px;"></th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php foreach ($categoties as $cate) :?>
		                <tr>
		                  <td><?php echo $cate['name']?></td>
		                  <td><?php echo $cate['alias']?></td>
		                  <td><?php echo $cate['visible'] == 'y' ? '可用' : '不可用' ;?></td>
		                  <td>1</td>
		                  <td><a href="?op=edit&cateid=<?php echo $cate['cate_id'];?>"><i class="icon-pencil"></i> </a>
							<a href="?op=del&cateid=<?php echo $cate['cate_id'];?>" data-confirm="确认是否删除分类  “<?php echo$cate['name'];?>”?" confirm-title="删除提示">
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
		var cate_name = $("input[name=cate_name]");
		if(checkformele(cate_name,cate_name.attr('required_msg'),true)){
			var loading = $('[type=submit]').attr('disabled',true).showLoading();
			$.post($(this).attr("action"),$(this).serialize(),function(data){
				loading.remove();
				 $('[type=submit]').attr('disabled',false);
				if(data['code'] == 0){
					$(data['msg']).showErrorNotice({show:0.9,f:true});
					setTimeout(function(){
						location.href= 'admin_category.php';
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