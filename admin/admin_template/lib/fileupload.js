/**
 * 2012-11-20 swfupload
 * 
 * @author xiaoyan {@link http://www.xiaoyan.me}
 * @version 1.0.0
 */

function fileUpdate(target, url, type, w, h, sizelimit, onsuccess , onprogress , onerror) {

	function insertAfter(newEl, targetEl) {
		var parentEl = targetEl.parentNode;

		if (parentEl.lastChild == targetEl) {
			parentEl.appendChild(newEl);
		} else {
			parentEl.insertBefore(newEl, targetEl.nextSibling);
		}
	}

	function callError(msg) {
		if (typeof onerror == "function") {
			onerror.call(this, msg);
		}
	}

	if (!type) {
		type = "*.*";
	}
	if (!target) {
		target = "spanButtonPlaceholder";
	}
	if (!document.getElementById(target)) {
		throw new Error("上传文件容器不存在");
	}
	var swftarget = document.getElementById(target);
	var swfuploadstatus = document.createElement("a");
	swfuploadstatus.className = "swfuploadstatus";
	var showText = swftarget.innerHTML.replace(/(^\s+)|(\s+$)/g, "");
	//setStatusText(!!showText ? showText : "选择文件");
	swftarget.innerHTML = "";
	insertAfter(swfuploadstatus, swftarget);

	function setStatusText(txt) {
		try {
			swfuploadstatus.innerHTML = txt;
		} catch (e) {
			swfuploadstatus.innerText = txt;
		}
	}

	function salert(msg) {
		if (typeof showTips == "function") {
			$(data['msg']).showErrorNotice({show:3});
		} else {
			alert(msg);
		}
	}
	var dir = "admin_template/lib";
	var swfuploadhandler = {
		flash_url : dir + "/swfupload/swfupload.swf",
		upload_url : url,
		post_params : {},
		sizelimit : sizelimit,
		onsuccess : function() {

		},
		onComplete : function() {
			if (this.getStats().files_queued === 0) {
				setStatusText("");
				// swfuploadstatus.innerHTML = "上传成功";
			}
		},
		onsuccess : function(file, serverData) {
			if (this.getStats().files_queued === 0) {
				var _size = Math.ceil(file.size / 1024);
				onsuccess.call(this, serverData, _size);
			}
		},
		fileQueueError : function(file, errorCode, message) {
			if (errorCode == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
				setStatusText("选择文件");
				callError("");
				salert("文件太大,请重新选择文件上传");
				return false;
			}
			if (errorCode == SWFUpload.QUEUE_ERROR.INVALID_FILETYPE) {
				setStatusText("选择文件");
				callError("");
				salert("文件格式错误(只支持" + type + ")，请重新选择文件");
				return false;
			}
		},
		fileDialogComplete : function(numFilesSelected, numFilesQueued) {
			try {
				if (numFilesSelected > 0) {
					this.startUpload();
				}
			} catch (ex) {
				salert(ex);
			}
		},
		queueComplete : function() {
		},
		onprogress : function(file, bytesLoaded, bytesTotal) {
			var percent = bytesLoaded / bytesTotal;
			if (typeof onprogress == "function") {
				onprogress.call(this, bytesLoaded,bytesTotal);
			}
			//var sp = "上传中(" + Math.round(percent * 100) + "%)";
			//setStatusText(sp);
		},

		queueComplete : function() {
		},
		onError : function(file, errorCode, message) {
			setStatusText("重新上传");
			switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
				callError("");
				salert("上传服务不可用");
				break;
			default:
				callError("");
				salert("上传文件失败");
			}

		},
		initupload : function() {

		}
	};

	var settings = {
		flash_url : swfuploadhandler.flash_url,
		upload_url : swfuploadhandler.upload_url,
		post_params : swfuploadhandler.post_params,
		file_size_limit : (swfuploadhandler.sizelimit ? swfuploadhandler.sizelimit
				: 1024)
				+ " KB",
		file_types : type,
		file_types_description : "支持的文件",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {},
		button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE,
		debug : false,

		button_image_url : dir + "/swfupload/transparent.png",
		button_placeholder_id : target,
		button_width : w || 100,
		button_height : h || 30,
		button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor : SWFUpload.CURSOR.HAND,

		file_queue_error_handler : swfuploadhandler.fileQueueError,
		file_dialog_complete_handler : swfuploadhandler.fileDialogComplete,
		upload_progress_handler : swfuploadhandler.onprogress,
		upload_complete_handler : swfuploadhandler.onComplete,
		upload_success_handler : swfuploadhandler.onsuccess,
		upload_error_handler : swfuploadhandler.onError,
		queue_complete_handler : swfuploadhandler.queueComplete
	};
	new SWFUpload(settings);

	return {
		setText : function(text) {
			setStatusText(text);
		}
	};
}