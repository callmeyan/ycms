<?php
include_once 'admin.php';
$file = isset($_FILES['Filedata']) ? $_FILES['Filedata'] : null;

if($file && $file['error'] == 0){
	$hashcode = md5_file($file['tmp_name']);
	$ext = substr($file['name'], -4);
	$url = "content/resources/".$hashcode.$ext;
	$result = array("code" => 0,"url" => $url);

	$savefile = YCMS_ROOT . $url;
	if(!file_exists($savefile)){
		file_put_contents($savefile, file_get_contents($file['tmp_name']));
		//if(!move_uploaded_file($file['tmp_name'], $savefile)){
		//	$result['code'] = -1;
		//}
		$result['url'] = $url;
	}
	exit(json_encode($result));
}else{
	exit(json_encode(array("code" => -1)));
}