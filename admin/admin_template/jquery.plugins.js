$.fn.showErrorNotice = function(config){
	var _conf = {
		show:2,
		close:false,
		f:false,
		msg:''
	};
	$.extend(_conf,config);

	var box = $('<div class="jq_s_notice"><div class="box"><div class="msg"></div><div class="t_close" style="display:none;">x</div></div></div>');
	
	box.find(".msg").html(_conf.msg ? _conf.msg : this.selector);
	if(_conf.f){
		box.find(".box").addClass("right");
	}
	$('body').append(box);
	
	var _closeTimer = null;
	if(_conf.close){
		box.find(".t_close").show().click(function(){
			clearTimeout(_closeTimer);
			_remove();
			return false;
		});
	}else if(_conf.show > 0){
		_closeTimer = setTimeout(function(){
			_remove();
		}, _conf.show * 1000);
	}
	
	function _remove(){
		box.animate({top:"50px"},300,function(){box.remove();});
	}
};

$.fn.showLoading = function(positon){
	this.parent().css({position:'relative'});
	var loading = $('<span class="ajax_loading"></span>');

	if(positon && positon == 'left'){
		loading.insertBefore(this);
		loading.css({right:$('#save_post').width() + 40});
	}else{
		loading.insertAfter(this);
	}
	return loading;
};