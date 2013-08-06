<?php

class Option {
	//附件大小上限，单位：字节，默认20M
	const UPLOADFILE_MAXSIZE = 20971520;
	//附件上传路径
	const UPLOADFILE_PATH = '../content/uploadfile/';
	//允许上传的附件类型
	const ATTACHMENT_TYPE = 'rar,zip,gif,jpg,jpeg,png';

	static function get($option_name,$default = null,$serialize = false){
		$value = YCMS_Option::getInstace($option_name);
		if($value && $serialize){
			$value->option_value = unserialize($value->option_value);
		}
		return !!$value ? $value : $default;
	}

	static function getAll(){
		$options = cache_get(CACHE_OPTION);
		$allops = array();
		foreach ($options as $value) {
			$allops[$value['option_name']] = $value['option_value'];
		}
		return $allops;
	}

	static function getRoutingTable(){
		$routingtable = array(
			array(
				'model' => 'Article',
				'method' => 'showCategory',
				'reg' => '|^/category/([\w-]+)(/page/(\d+))?URLEXT$|',
				//'reg' => '|^.*/category/([\w-]+)(/page/(\d+))?URLEXT$|',
				'param' => array('category'=>1,'page'=>3)
			),
			array(
				'model' => 'Article',
				'method' => 'showTag',
				'reg' => '|^/tag/([\w-]+)(/page/(\d+))?URLEXT$|',
				'param' => array('tag'=>1,'page'=>3)
			),
			array(
				'model' => 'Article',
				'method' => 'search',
				'reg' => '|^/search/([\w-]+)(/page/(\d+))?URLEXT$|',
				'param' => array('search'=>1,'page'=>3)
			),
			array(
				'model' => 'Article',
				'method' => 'author',
				'reg' => '|^/author/?(\d+)(/page/(\d+))?URLEXT$|',
				'param' => array('author'=>1,'page'=>3)
			),
			array(
				'model' => 'Common',
				'method' => 'index',
				'reg' => '|^/page/(\d+)$|',
				'param' => array('pid'=>1)
			),
			array(
				'model' => 'Common',
				'method' => 'index',
				'reg' => '|^/?([\?&].*)?$|',
				'param' => array('pid'=>1)
			),
			array(
				'model' => 'Article',
				'method' => 'displayPost',
				'reg' => '/^\/([\x{4e00}-\x{9fa5}\w-]+?)$/u',
				'param' => array('pid'=>1)
			)
		);
		
		return $routingtable;
	}

	/**
	 * 获取允许上传的附件类型
	 */
	static function getAttType() {
		return explode(',', self::ATTACHMENT_TYPE);
	}


	/**
	 * 更新配置选项,如没有存在则添加
	 * @param $name
	 * @param $value
	 */
	static function updateOption($name, $value ,$updatecache = true){
		global $ycms_db;
		$ycms_db->replace(DB_PREFIX."options", array("option_name"=>$name,"option_value"=>$value));
		if($updatecache){
			self::updateCache($name, $value);
		}
	}

	/**
	 * 更新option cache
	 * @param string $name
	 * @param object $value
	 */
	static function updateCache($name, $value){
		global $ycms_db;
		$cache_options = cache_get(CACHE_OPTION);
		if(!$cache_options){			
			$options = $ycms_db->get_results("SELECT * FROM ".DB_PREFIX."options");
			$cache_options = buildArray($options,'option_name');
		}
		$cache_options[$name]['option_value'] = $value;
		cache_write($cache_options, CACHE_OPTION);
	}
	
	/**
	 * 更新配置选项,如没有存在则添加
	 * @param $name
	 * @param $value
	 */
	static function addOption($name, $value){
		$DB = MySql::getInstance();
		$DB->query('INSERT INTO '.DB_PREFIX."options (option_name,option_value) VALUES ('$name','$value')");
		self::updateCache($name, $value);
	}
	
	static function deleteOption($name){
		$DB = MySql::getInstance();
		$DB->query('DELETE '.DB_PREFIX."options WHERE where option_name='$name'");
	}
}
