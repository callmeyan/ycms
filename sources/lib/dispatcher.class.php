<?php
/**
 * 请求路由分发
 */

class Dispatcher {

	/** 对象 */
	static $_instance;

	/**
	 * 请求模块
	 */
	private $_model = '';

	/**
	 * 请求模块方法
	 */
	private $_method = '';

	/**
	 * 请求参数
	 */
	private $_params;

	/**
	 * 路由表
	 */
	private $_routingTable;

	/**
	 * 访问路径
	 */
	private $_path = NULL;

	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new Dispatcher();
			return self::$_instance;
		} else {
			return self::$_instance;
		}
	}

	private function __construct() {
		$this->_path = urldecode($this->setPath());
		$this->_routingTable = Option::getRoutingTable();
		global $diy_urls;

		$matched_customer = false;
		if($diy_urls){
			foreach ($diy_urls as $route) {
				if (preg_match('|'.$route['url'].'|', $this->_path, $matches)) {
					$this->_model = "Common";
					$this->_method = "showCustomerPage";
					$this->_params = array('page'=>$route['template'],'callback'=>$route['callback'],'param'=>$matches);
					$matched_customer = true;
				}
			}
		}
		if(!$matched_customer){
			$isurlrewrite = Option::get('isurlrewrite')->option_value;
			if($isurlrewrite){
				foreach ($this->_routingTable as $route) {
					$param = isset($route['param']) ? $route['param'] : null;
					if(preg_match($route['reg'], $this->_path , $matches)){
						$this->_model = $route['model'];
						$this->_method = $route['method'];
						$this->_params = $this->parseParam($matches,$param);
						break;
					}
				}
			}
				
		}
		header('Content-Type: text/html; charset=UTF-8');

		if (empty($this -> _model)) {
			show_404_page();
		}
	}
	
	private function parseParam($url,$fetchdata = null){
		$params = array();
		if(is_array($url) && $fetchdata && is_array($fetchdata)){
			foreach ($fetchdata as $key => $index) {
				if(isset($url[$index])){
					$params[$key] = $url[$index];
				}
			}
		}else if(is_string($url)){
			
		}
		return $params;
	}

	public function run(){
		$module = new $this->_model();
		$method = $this->_method;
		try {
			$module->$method($this->_params);
		} catch (Exception $e) {
			errorMsg($e);
		}
	}

	public static function setPath(){
		$path = '';
		if (isset($_SERVER['REQUEST_URI'])){
			$path = $_SERVER['REQUEST_URI'];
		} else {
			if (isset($_SERVER['argv'])) {
				$path = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
			} else {
				$path = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
			}
		}

		//for iis6 path is GBK
		if (isset($_SERVER['SERVER_SOFTWARE']) && false !== stristr($_SERVER['SERVER_SOFTWARE'], 'IIS')) {
			if (function_exists('mb_convert_encoding')) {
				$path = mb_convert_encoding($path, 'UTF-8', 'GBK');
			} else {
				$path = @iconv('GBK', 'UTF-8', @iconv('UTF-8', 'GBK', $path)) == $path ? $path : @iconv('GBK', 'UTF-8', $path);
			}
		}
		//for ie6 header location
		$r = explode('#', $path, 2);
		$path = $r[0];
		//for iis6
		$path = str_ireplace('index.php', '', $path);
		//for subdirectory
		$t = parse_url(BLOG_URL);
		$path = str_replace($t['path'], '/', $path);

		return $path;
	}
}
