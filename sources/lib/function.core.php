<?php
/**
 * 自动加载基础函数库
 */
function __autoload($class) {
	$class = strtolower($class);
	if (file_exists(YCMS_ROOT . '/sources/model/' . $class . '.class.php')) {
		require_once(YCMS_ROOT . '/sources/model/' . $class . '.class.php');
	} elseif (file_exists(YCMS_ROOT . '/sources/lib/' . $class . '.class.php')) {
		require_once(YCMS_ROOT . '/sources/lib/' . $class . '.class.php');
	} elseif (file_exists(YCMS_ROOT . '/sources/controller/' . $class . '.controller.php')) {
		require_once(YCMS_ROOT . '/sources/controller/' . $class . '.controller.php');
	} else {
		errorMsg('加载'.$class . '类失败,请检查文件是否存在');
	}
}



/**
 * 读取缓存文件
 * @param string $cacheType 文件类型
 * @param mixed $param 参数
 */
function cache_get($cacheType,$param = null){
	return YCMS_Cache::getInstance()->get($cacheType,$param);
}

/**
 * 写入缓存
 * @param mixed $cacheData 数据
 * @param string $cacheType 文件类型
 * @param int $expire 过期时间
 * @param mixed $param 参数
 */
function cache_write($cacheData, $cacheType,$expire = 0,$param=null){
	return YCMS_Cache::getInstance()->write($cacheData,$cacheType,$expire,$param);
}
function langAdmin($key,$default = ""){
	global $adminLanguage;
	$default = $default ? $default : $key;
	$arr = explode('/', $key);
	$value = $default;
	switch (count($arr)){
		case 1:
			$value = isset($adminLanguage[$arr[0]]) ? $adminLanguage[$arr[0]] : $default;
			break;
		case 2:
			$value = isset($adminLanguage[$arr[0]][$arr[1]]) ? $adminLanguage[$arr[0]][$arr[1]] : $default;
			break;
		case 3:
			$value = isset($adminLanguage[$arr[0]][$arr[1]][$arr[2]]) ? $adminLanguage[$arr[0]][$arr[1]][$arr[2]] : $default;
			break;
	}
	return $value;
}

function initCategory($categories){
	$tree = array(); //格式化好的树
	foreach ($categories as $item){
		if (isset($categories[$item['pid']])){
			$categories[$item['pid']]['son'][] = &$categories[$item['id']];
		}else{
			$tree[] = &$categories[$item['id']];
		}
	}
	return $tree;
}

/**
 * 获取值
 * @param string $key
 * @param mixed $default
 */
function getgpc($key,$default=''){
	return isset($_REQUEST[''.$key]) ? $_REQUEST[''.$key] : $default;
}

function getThemeSettings($group="default",$key=""){
	$theme = Option::get("template");
	$opvalue = Option::get("theme_data_".$theme."_".$group);

	$value = $opvalue ? unserialize(str_replace('/\r\n/', "", $opvalue)) : null;
	if($key && isset($value[$key])){
		return str_replace('\"', '"', $value[$key]);
	}
	return $value;
}

/**
 * 去除多余的转义字符
 */
function doStripslashes() {
	if (get_magic_quotes_gpc()) {
		$_GET = stripslashesDeep($_GET);
		$_POST = stripslashesDeep($_POST);
		$_COOKIE = stripslashesDeep($_COOKIE);
		$_REQUEST = stripslashesDeep($_REQUEST);
	}
}

/**
 * 递归去除转义字符
 */
function stripslashesDeep($value) {
	$value = is_array($value) ? array_map('stripslashesDeep', $value) : stripslashes($value);
	return $value;
}

/**
 * 转换HTML代码函数
 *
 * @param unknown_type $content
 * @param unknown_type $wrap 是否换行
 */
function htmlClean($content, $wrap = true) {
	$content = htmlspecialchars($content);
	if ($wrap) {
		$content = str_replace("\n", '<br />', $content);
	}
	$content = str_replace('  ', '&nbsp;&nbsp;', $content);
	$content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
	return $content;
}
/**
 * 获取用户ip地址
 */
function getClientIP(){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif(!empty($_SERVER["REMOTE_ADDR"])){
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else{
		$cip = "0.0.0.0";
	}
	return $cip;
}

function gettime($date){
	$year  = substr($date,0,4);
	$month = substr($date,5,2);
	$day   = substr($date,8,2);
	$hour  = intval(substr($date,10,2));
	$minute= intval(substr($date,12,2));
	$second= intval(substr($date,14,2));

	return mktime($hour,$minute,$second,$month,$day,$year);
}

function check_null($keys,$msg=null){
	if(!is_array($keys)){
		return isset($_POST[$keys]) ? !!$_POST[$keys] : false;
	}
	$isnull = array();
	foreach ($keys as $index => $key) {
		if(!isset($_POST[$key]) || !$_POST[$key]){
			if($msg && isset($msg[$index])){
				$isnull[] = $msg[$index];
			}else{
				$isnull[] = $key;
			}
		}
	}
	return count($isnull) == 0 ? false : implode($isnull, ",");
}



/**
 * Makes directory
 * @link http://www.php.net/manual/en/function.mkdir.php
 * @param dir string <p>
 * The directory path.
 * </p>
 */
function mkdirs($dir)
{
	if(!is_dir($dir))
	{
		if(!mkdirs(dirname($dir))){
			return false;
		}
		if(!mkdir($dir,0777)){
			return false;
		}
	}
	return true;
}

/**
 * del Dir
 * @param string $dir
 */
function rmdirs($dir)
{
	error_reporting(E_ALL);
	if(!is_dir($dir)){
		return unlink($dir);
	}
	$d = dir($dir);
	while (false !== ($child = $d->read())){
		if($child != '.' && $child != '..'){
			if(is_dir($dir.'/'.$child))
			rmdirs($dir.'/'.$child);
			else unlink($dir.'/'.$child);
		}
	}
	$d->close();
	rmdir($dir);
}

function getDirFiles($dirpath,$filter = ''){
	$dir = opendir($dirpath);
	$files = array();
	while (($file = readdir($dir)) !== false)
	{
		if(!str_startwith($file, '.') && !str_startwith($file, '..')){
			if($filter){
				if(preg_match('/'.$filter.'/', $file)){
					$files[] = $file;
				}
			}else{
				$files[] = $file;
			}
		}
	}
	closedir($dir);
	return $files;
}

function is_utf8($word)
{
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 检查插件
 */
function checkPlugin($plugin) {
	if (is_string($plugin) && preg_match("/^[\w\-\/]+\.php$/", $plugin) && file_exists(YCMS_ROOT . '/content/plugins/' . $plugin)) {
		return true;
	} else {
		return false;
	}
}

function getAllPlugins(){
	$plugin_dir = YCMS_ROOT . '/content/plugins/';
	$dir = opendir($plugin_dir);
	$plugins = array();
	while (($file = readdir($dir)) !== false)
	{
		if($file != "." && $file != ".."){
			if(is_dir($plugin_dir.$file)){
				$plugins[] = getPluginInfo($file.'/'.$file.'.php');
			}else{
				$plugins[] = getPluginInfo($file);
			}
		}
	}
	closedir($dir);
	return $plugins;
}

function getPluginInfo($plugin){
	$pluginInfo = array('file'=>$plugin);
	if(checkPlugin($plugin)){
		$plugin_content = file_get_contents(YCMS_ROOT . '/content/plugins/'.$plugin);
		$lines = explode("\n", $plugin_content);
		foreach ($lines as $line) {
			if(str_startwith('Plugin Name:', $line)){
				$pluginInfo['name'] = trim(substr($line, strlen('Plugin Name:')));
			}else if(str_startwith('Plugin URI:', $line)){
				$pluginInfo['install'] = trim(substr($line, strlen('Plugin URI:')));
			}else if(str_startwith('Description:', $line)){
				$pluginInfo['description'] = trim(substr($line, strlen('Description:')));
			}else if(str_startwith('Author:', $line)){
				$pluginInfo['author'] = trim(substr($line, strlen('Author:')));
			}else if(str_startwith('Version:', $line)){
				$pluginInfo['version'] = trim(substr($line, strlen('Version:')));
			}else if(str_startwith('Author URI:', $line)){
				$pluginInfo['author_site'] = trim(substr($line, strlen('Author URI:')));
			}
		}
	}
	return $pluginInfo;
}

function getThemeInfo($theme){
	$themedir = 'content/themes/'.$theme."/";
	$themeInfo = array('file'=>$theme,'preview'=>$themedir.'preview.png');
	$themeFile = YCMS_ROOT . $themedir."style.css";
	if(file_exists($themeFile)){
		$plugin_content = file_get_contents($themeFile);
		$lines = explode("\n", $plugin_content);
		foreach ($lines as $line) {
			if(str_startwith('Theme Name:', $line)){
				$themeInfo['name'] = trim(substr($line, strlen('Theme Name:')));
			}else if(str_startwith('Theme URI:', $line)){
				$themeInfo['install'] = trim(substr($line, strlen('Theme URI:')));
			}else if(str_startwith('Description:', $line)){
				$themeInfo['description'] = trim(substr($line, strlen('Description:')));
			}else if(str_startwith('Author:', $line)){
				$themeInfo['author'] = trim(substr($line, strlen('Author:')));
			}else if(str_startwith('Version:', $line)){
				$themeInfo['version'] = trim(substr($line, strlen('Version:')));
			}else if(str_startwith('Author URI:', $line)){
				$themeInfo['author_site'] = trim(substr($line, strlen('Author URI:')));
			}
		}
		return $themeInfo;
	}
	return null;
}

/**
 * 自定义页面
 * @param string $url 访问路径 ，暂时只支持
 * @param unknown_type $template
 */
function add_customer_url($url,$template,$callback = null){
	global $diy_urls;
	$diy_urls[md5($url)] = array('url' => $url ,"template" => $template , "callback" => $callback);
}

function include_theme_functions(){
	$filename = YCMS_ROOT.get_template_directory_uri(0).'/functions.php';
	if(file_exists($filename)){
		return include_once($filename);
	}
	return false;
}

/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上
 *
 * @param string $hook
 * @param string $func
 */
function add_action($hook,$func){
	global $hooks_arr;
	if(empty($hooks_arr)){
		$hooks_arr = array();
	}
	$hooks_arr[$hook][] = $func;
	return true;
}

function add_settings_field($id, $title,$desc,$type,$values = null,$group = null){
	global $settings_fields;
	$group = $group ? $group : array('default','主题设置');
	if(count($group) == 1){
		$group[] = $group[0];
	}
	if(!isset($settings_fields[$group['0']])){
		$settings_fields[$group['0']] = array('name'=>$group['1'],'setting'=>array());
	}
	$settings_fields[$group['0']]['setting'][$id] = array(
		'title' => $title,
		'desc' => $desc,
		'type' => $type,
		'values' => $values
	);
}

/**
 * 执行挂在钩子上的函数,支持多参数 eg:do_action('post_comment', $author, $email, $url, $comment);
 *
 * @param string $hook
 */
function do_action($hook){
	global $hooks_arr;
	$args = array_slice(func_get_args(), 1);
	$datas = array();
	if(isset($hooks_arr[$hook]) && $hooks_arr[$hook]){
		foreach ($hooks_arr[$hook] as $function) {
			$datas[$function] = call_user_func_array($function, $args);
		}
	}
	return $datas;
}

function str_endwith($str,$search){
	return preg_match('['.$str.'$]', $search);
	if(substr($str, 0,0-strlen($search)) == $search){
		return true;
	}
	return false;
}

function str_startwith($str,$search){
	return preg_match('[^'.$str.']', $search);
}
/**
 * 获取文件名后缀
 */
function getFileSuffix($fileName) {
	return strtolower(pathinfo($fileName,  PATHINFO_EXTENSION));
}

/**
 * 转换文件大小单位
 *
 * @param string $fileSize 文件大小 byte
 */
function changeFileSize($fileSize) {
	if ($fileSize >= 1073741824) {
		$fileSize = round($fileSize / 1073741824, 2) . 'GB';
	} elseif ($fileSize >= 1048576) {
		$fileSize = round($fileSize / 1048576, 2) . 'MB';
	} elseif ($fileSize >= 1024) {
		$fileSize = round($fileSize / 1024, 2) . 'KB';
	} else {
		$fileSize = $fileSize . '字节';
	}
	return $fileSize;
}
/**
 * 截取编码为utf8的字符串
 *
 * @param string $strings 预处理字符串
 * @param int $start 开始处 eg:0
 * @param int $length 截取长度
 */
function subString($strings, $start, $length) {
	if (function_exists('mb_substr') && function_exists('mb_strlen')) {
		$sub_str = mb_substr($strings, $start, $length, 'utf8');
		return mb_strlen($sub_str, 'utf8') < mb_strlen($strings, 'utf8') ? $sub_str . '...' : $sub_str;
	}
	$str = substr($strings, $start, $length);
	$char = 0;
	for ($i = 0; $i < strlen($str); $i++) {
		if (ord($str[$i]) >= 128)
		$char++;
	}
	$str2 = substr($strings, $start, $length + 1);
	$str3 = substr($strings, $start, $length + 2);
	if ($char % 3 == 1) {
		if ($length <= strlen($strings)) {
			$str3 = $str3 .= '...';
		}
		return $str3;
	}
	if ($char % 3 == 2) {
		if ($length <= strlen($strings)) {
			$str2 = $str2 .= '...';
		}
		return $str2;
	}
	if ($char % 3 == 0) {
		if ($length <= strlen($strings)) {
			$str = $str .= '...';
		}
		return $str;
	}
}
/**
 * 验证email地址格式
 */
function checkMail($email) {
	if (preg_match("/^[\w\.\-]+@\w+([\.\-]\w+)*\.\w+$/", $email) && strlen($email) <= 60) {
		return true;
	} else {
		return false;
	}
}

function errorMsg($msg){
	$detail_array = debug_backtrace();
	if(isAjax()){
		show_ajax_data(-1,$detail_array,$msg);
	}
	$detail = '<a href="#" class="click_more" onclick="document.getElementById(\'more_erro\').style.display=\'block\';this.style.display=\'none\';return false;">detail</a><div id="more_erro" class="detail">';

	array_shift($detail_array);

	foreach ($detail_array as $file) {
		$file_path = substr($file['file'], strlen(YCMS_ROOT));
		$line = $file['line'];
		$detail .= "<div>$file_path($line)</div>";
	}
	$detail .= "</div>";
	$title = "ERROR";	
	$data = <<<PRECODE
<style type="text/css">
*{margin:0px;padding:0px;}
body{background-color:#f4f5f6;font-family:'miscrosoft yahei',Arial, Helvetica, sans-serif;}
#error_message_box_bg{position: absolute;top: 0;left: 0;right: 0;bottom:0;z-index: 9998;background: black;opacity: 0.3;}
#error_message_box{position: absolute;top: 150px;width: 100%;left: 0;right: 0;z-index: 9999;}
.message{margin: 0px auto;background-color: #FBFBDB;border: 1px solid #D6D6D6;width:450px;}
.message h2{color:#f00;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #d6d6d6;margin-right: 10px;margin-left: 10px;line-height: 40px;}
.message h2 img{margin-bottom: -7px;}
.message .notice .img{height: 72px;width: 72px;float: left;}
.message .notice .clear{clear: both;}
.message .notice{padding: 10px;}
.message .notice .msg_c{font-size:16px;}
.message .message_content{line-height: 25px;color: #333;font-weight: bold;margin-bottom: 10px;}
.message .notice .msg_c .title{border-bottom-width: 1px;border-bottom-style: dashed;border-bottom-color: #D6D6D6;margin-bottom: 5px;padding-bottom: 5px;}
.author{text-align: right;margin-right: 10px;margin-bottom: 3px;color:#ccc;}
.author a{color:#ccc;text-decoration:none;}
#more_erro{margin: 10px 0;line-height: 22px;word-wrap: break-word;word-break: break-all;display:none;color:#5F5F5F;}
a{color:#727272;text-decoration:none;}
a:hover{text-decoration:underline;color:#0032B4;}
</style>
<div id="error_message_box_container">
	<div id="error_message_box_bg"></div>
	<div id="error_message_box">
	    <div class="message">
	        <h2>
	            $title
	        </h2>
	        <div class="notice">
	            <div class="msg_c">
	                <div class="message_content">
	                    $msg
	                </div>
	            </div>
	            <div class="clear">
	            </div>
	            $detail
	        </div>
	        <div class="author">
	            power by
	            <a href="http://ycms.xiaoyan.me" target="target">
	                ycms
	                </span>
	        </div>
	    </div>
	</div>
</div>
<script type="text/javascript">
var html = document.getElementById("error_message_box_container");
html.parentNode.removeChild(html)
document.body.appendChild(html)
</script>
PRECODE;

            exit($data);
}

function show_404_page(){
	$filename = get_template_directory_uri(0).'/404.php';
	if(!file_exists($filename)){
		exit("404");
	}
	require_once $filename;exit;
}

function isAjax(){
	if(isset($_SERVER['X-Requested-With']) && strtolower($_SERVER['X-Requested-With']) == "xmlhttprequest") {
		return true;
	}
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"){
		return true;
	}
	return false;
}

function show_ajax_data($code,$data='',$msg=''){
	header("Content-type: application/json");
	$msg = $msg ? $msg : $code;
	$msg = langAdmin("message/$msg",$msg);
	exit(json_encode(array('code'=>$code,'data'=>$data,'msg'=>$msg)));
}

function header_go($url,$code='',$msg=''){
	if($code){

	}
	header('Location:'.$url);
	exit;
}

function buildArray($array,$key = null,$eqkey = null,$eqvalue = null){
	$arr = array();
	foreach ($array as $value) {
		if($eqkey){
			if($value[$eqkey] == $eqvalue){
				$arr[] = $value;
			}
		}else{
			if($value[$key]){
				$arr[$value[$key]] = $value;
			}
		}
	}
	return $arr;
}

function buildArrayForValue($array){
	$arr = array();
	foreach ($array as $key=>$value) {
		$arr[$value] = $key;
	}
	return $arr;
}
function getArrayByKeys($array,$keys){
	$arr = array();
	if(!is_array($keys)){
		$keys = explode(",", $keys);
	}
	foreach ($keys as $key) {
		$arr[$key] = $array[$key];
	}
	return $arr;
}

function getArrayValue($array,$index){
	$i = 0;
	if($index < count($array)){
		foreach ($array as $value) {
			if($i == $index){
				return $value;
			}
			$i ++;
		}
	}
	return null;
}

function show($data,$format=''){
	if(!$format){
		return print($data);
	}
}


function create_thumb($filename,$width,$height){
	if (preg_match('/^http:\/\/.*/', $filename) || !$filename) {
		return $filename;
	}
	$filename = YCMS_ROOT.$filename;
	$return_file  = 'content/resources/thumb/'.md5($filename)."_".$width.'x'.$height.".".getFileSuffix($filename);
	$thumb_name = YCMS_ROOT.$return_file;
	if(file_exists($thumb_name)){
		return $return_file;
	}
	$img = new Image($filename);
	$img->resizeImage($width, $height);

	if($img->save($thumb_name)){
		return $return_file;
	}
	return false;
}

/**
 * 获取Gravatar头像
 * http://en.gravatar.com/site/implement/images/
 * @param $email
 * @param $s size
 * @param $d default avatar
 * @param $g
 */
function getGravatar($email, $s = 40, $d = 'mm', $g = 'g') {
	$hash = md5($email);
	$avatar = "http://www.gravatar.com/avatar/$hash?s=$s&d=$d&r=$g";
	return $avatar;
}
function error_handle($errno, $errstr, $errfile, $errline,$errcontext){
	if (!(error_reporting() & $errno)) {
        return;
    }
	echo "<b>Custom error:</b> [$errno] $errstr<br />";
	echo " Error on line $errline in $errfile<br />";
//	print_r($errcontext);
	//exit;
}
function when_shut_down(){
    if ($error = error_get_last()) {
        var_dump($error);
    }
}
