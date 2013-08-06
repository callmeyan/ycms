$(function(){
	
	$("[rel=tooltip]").tooltip();
	
	//nav 
	$("ul.nav-list>li").each(function(){
		var _me = $(this);
		if(_me.hasClass("active")){
			_me.parent().addClass("in").css("height","auto");
			_me.addClass("collapsed");
		}
	});
	
	if($("#ueditor").length > 0){
		editor = KindEditor.create('#ueditor', {
			allowFileManager : true
		});
	}
	
    $('.demo-cancel-click').click(function(){return false;});
    $("input.datetime").click(function(){
		if($(this).attr("data-pattern")){
			WdatePicker.call(this,eval("("+$(this).attr("data-pattern")+")"));
		}else{
			WdatePicker.call(this);
		}
	});
    
	$("input.file,a.link_up_image").each(function(index){
		var target = "swfupload_box_" + index;
		var div = $('<div style="position: relative;" class="swfupload_box"></div>');
		var w = $(this).width() + 4 + parseInt($(this).css("padding-top")) + parseInt($(this).css("padding-bottom")),
			h=$(this).height() + parseInt($(this).css("padding-left")) + parseInt($(this).css("padding-right"));

		var me = $(this);
		div.insertAfter(me);
		div.append(me);
		div.css({width:w,height:h});
		div.append('<span id="'+target+'"></span>');
		var statusbar = $('<span class="status_bar"></span>');
		statusbar.css("line-height",(h-2)+"px");
		div.append(statusbar);
		var isupload = false;
		
		var upstatus = fileUpdate(target,fileiploadPath,"*.jpg;*.png;*.gif;*.bmp",w,h,1024,function(resp){
			isupload = true;
			var data = eval("(" + resp + ")");
			
			if(data['code'] == 0){
				statusbar.hide();
				if(me.attr("complete") && typeof eval(me.attr("complete")) == "function"){
					eval(me.attr("complete")).call(me,data['url']);
				}else{
					me.val(data['url']);
				}
			}else{
				me.val("");
				statusbar.html("上传失败");
			}
		},function(p,t){
			statusbar.show().html("");
			var percent = p / t;
			var sp =  Math.round(percent * 100) + "%";
			statusbar.css("width",sp);
		});
		div.hover(function(){
			statusbar.show().css("width","100%");
			upstatus.setText('选择文件');
		},function(){
			statusbar.hide().css("width","0%");
			upstatus.setText('');
		});
	});	
    
    //点击执行链接并跳转
    $("a.messageOrgo").each(function(){
    	$(this).attr("btn-yes","messageOrGoPost");
    	if(!$(this).attr("data-confirm")){
        	$(this).click(function(){
        		messageOrGoPost($(this).attr("href"));
        		return false;
        	});
    	}
	});	
			
    $("a[data-confirm]").click(function(e){
		var $this = $(this)
    	, message = $this.attr('data-confirm')
    	, title = $this.attr('confirm-title') || '提示'
        , href = $this.attr('href')
        , target = $("#data_confirm_box") //strip for ie7
        , option = 'toggle';

      	target.find("h3.data_confirm_title").html(title);
      	target.find("span.data_confirm_message").html(message);      	

      	target.find("button.btn-yes").unbind("click");
		target.find("button.btn-yes").click(function(){
			target.modal('hide');
			var yesfn = $this.attr("btn-yes");
			//$this.focus();
			if(yesfn && typeof eval(yesfn) == "function"){
				yesfn = eval(yesfn);
				yesfn.call(target,href,this);
			}else{
				setTimeout(function(){
					location.href = href;
				}, 100);
			}
		});
      	var h = target.modal(option);
      	
      	return false;
    });
	
	$("li.add_link_to_menu>a").each(function(){
		var _me = $(this);
		_me.hover(function(){
			if(!$(this).attr("loading")){
				$(this).animate({width:"120px"},100);
			}
		},function(){
			if(!$(this).attr("loading")){
				$(this).animate({width:"0px"},100);
			}
		});
		_me.click(function(){
			$(this).css({width:"0px"});
			var _action = _me.attr("class");
			var _or = _me.parent().find("." + (_action == "add" ? "del" : "add"));
			_me.addClass("loading").attr("loading","true");
			$.post("admin_other.php",{action:'shortcut_link',url:admin_url + "&isquick=1",type:_action,name:url_name},function(data){
				_me.removeClass("loading").removeAttr("loading");
				if(data['code'] == 0){
					_me.hide();_or.show();
					$(data['msg']).showErrorNotice({show:1.3,f:true});
				}else{
					$(data['msg']).showErrorNotice({show:0,msg:data['msg'],close:true});
				}
			},"json");
			return false;
		});
	});
	
	$("form.validate").each(function(){
		$(this).find(":input").poshytip({
			className: 'tip-yellowsimple',
			content: '必须填写此项',
			showOn: 'none',
			alignTo: 'target',
			alignX: 'inner-left',
			offsetX: 100,
			offsetY: 3
		});
		$(this).find(":input.required").focusin(function(){
			$(this).poshytip('hide');
		});
		$(this).find(":input.required").focusout(function(){
			var _me = $(this);
			if(!_me.val()){
				if(_me.attr("required_msg")){
					_me.poshytip('update', _me.attr("required_msg"));
				}
				_me.poshytip('show');
			}
		});
	});
});



function checkformele(obj,message,pass){
	var me = $(obj);
	if(!me.val()){
		me.poshytip('update', message).poshytip('show');
		pass = false;
	}else{
		me.poshytip('hide');
	}
	return pass;
}

function messageOrGoPost(url){
	$.post(url,function(data){
		messageOrgo(data,1.2);
	},'json');
}

function messageOrgo(data,time){
	time = time || 1;
	if(data['code'] == 0){
		$(data['msg']).showErrorNotice({show:time,f:true});
		setTimeout(function(){
			location.href = data['data'];
		}, time * 1000);
	}else{
		$(data['msg']).showErrorNotice({show:time,msg:data['msg'],close:time == 0});
	}
}

(function() {
	  // Private array of chars to use
	  var CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
	 
	  Math.uuid = function (len, radix) {
	    var chars = CHARS, uuid = [], i;
	    radix = radix || chars.length;
	 
	    if (len) {
	      // Compact form
	      for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random()*radix];
	    } else {
	      // rfc4122, version 4 form
	      var r;
	 
	      // rfc4122 requires these characters
	      uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
	      uuid[14] = '4';
	 
	      // Fill in random data.  At i==19 set the high bits of clock sequence as
	      // per rfc4122, sec. 4.1.5
	      for (i = 0; i < 36; i++) {
	        if (!uuid[i]) {
	          r = 0 | Math.random()*16;
	          uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
	        }
	      }
	    }
	 
	    return uuid.join('');
	  };
	 
	  // A more performant, but slightly bulkier, RFC4122v4 solution.  We boost performance
	  // by minimizing calls to random()
	  Math.uuidFast = function() {
	    var chars = CHARS, uuid = new Array(36), rnd=0, r;
	    for (var i = 0; i < 36; i++) {
	      if (i==8 || i==13 ||  i==18 || i==23) {
	        uuid[i] = '-';
	      } else if (i==14) {
	        uuid[i] = '4';
	      } else {
	        if (rnd <= 0x02) rnd = 0x2000000 + (Math.random()*0x1000000)|0;
	        r = rnd & 0xf;
	        rnd = rnd >> 4;
	        uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
	      }
	    }
	    return uuid.join('');
	  };
	 
	  // A more compact, but less performant, RFC4122v4 solution:
	  Math.uuidCompact = function() {
	    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
	      var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
	      return v.toString(16);
	    });
	  };
	})();