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
		          <th style="width: 120px;">标题</th>
		          <th>链接</th>
		          <th style="width: 40px;"></th>
		        </tr>
		      </thead>
		      <tbody>
		        <?php $index =0; foreach ($links as $key => $lnk):
		        	?><tr>
		          <td><?php show(++$index) ?></td>
		          <td class="text">
		          	<input type="text" class="name" style="width:100px;;display: none;" value="<?php show($lnk['name']) ?>">
		          	<span class="showtext"><?php show($lnk['name']) ?></span>
		          </td>
		          <td class="text">
		          	<input type="text" class="url" style="display: none;width: 250px;" value="<?php show($lnk['url']) ?>">
		          	<span class="showtext"><?php show($lnk['url']) ?></span>
		          </td>
		          <td>
		              <a class="edit_link_btn" href="?action=save&key=<?php echo $key;?>"><i class="icon-pencil"></i></a>
		              <a class="del_link_btn" btn-yes="a_link_del_fn" href="?action=del&key=<?php echo $key;?>" role="button" data-confirm="确认是否删除此快捷链接?"><i class="icon-remove"></i></a>
		          </td>
		        </tr>
		        <?php endforeach;?>
		      </tbody>
		    </table>
		</div>                
	</div>
</div>
<style>
.table td {height:30px;line-height:30px;}
.text input{margin: 0;}
</style>
<script type="text/javascript">
<!--
$(function(){
	function resize(i,table){
		i.removeClass("icon-ok").addClass("icon-pencil");
		table.find("span.showtext").show();
		table.find("input").hide().each(function(){
			$(this).next().html($(this).val());
		});		
	}
	$("a.edit_link_btn").click(function(){
		var i = $(this).find("i");
		var table = $(this).parent().parent();
		if(i.hasClass("icon-ok")){
			var url = table.find(".url").val();
			var name = table.find(".name").val();
			$.post($(this).attr("href"),{'url':url,'name':name},function(data){
				if(data['code'] == 0){
					resize(i,table);
				}else{
					$(data['msg']).showErrorNotice({show:1.2,msg:data['msg']});
				}
			},"json");
		}else{
			table.find("span.showtext").hide();
			table.find("input").show();	
			i.removeClass("icon-pencil").addClass("icon-ok");
		}
		return false;
	});
});

function a_link_del_fn(href){
	$.post(href,{},function(data){
		if(data['code'] == 0){
			location.href = data['data'];
		}else{
			$(data['msg']).showErrorNotice({show:1.2,msg:data['msg']});
		}
	},"json");
}

//-->
</script>
<?php admin_template('footer');?>