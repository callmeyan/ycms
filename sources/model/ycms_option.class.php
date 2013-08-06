<?php
/**
 * 常用option类
 * @author yancheng
 */
final class YCMS_Option{
	
	var $option_id;
	var $option_name;
	var $option_value;
	var $autoload;
	
	public static function getInstace($option_name){
		global $ycms_db;
		if ( ! $option_name ){
			return false;
		}
		$options = cache_get(CACHE_OPTION);
		if(!isset($options[$option_name])){
			$query = "SELECT * FROM ".$ycms_db->get_db_name("options");
			$optionArray = $ycms_db->get_results($query);
			$options = buildArray($optionArray,'option_name');
			cache_write($options, CACHE_OPTION);
		}
		if(!isset($options[$option_name])){
			return null;
		}
		return new YCMS_Option($options[$option_name]);
	}
	
	function __construct($option){
		foreach ($option as $key => $value) {
			$this->$key = $value;
		}
	}
}