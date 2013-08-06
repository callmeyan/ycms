<?php
final class YCMS_Cache{

	private static $instance = null;

	/**
	 * cache map
	 * @var array
	 */
	private $data_cache = array();

	private function __construct() {}

	/**
	 * 静态方法，返回YCMS_Cache 实例
	 * @return Cache
	 */
	public static function getInstance() {
		if (self::$instance != null) {
			return self::$instance;
		}
		self::$instance = new YCMS_Cache();
		return self::$instance;
	}

	/**
	 * 修改文件访问时间
	 * @param string $cacheType 文件类型
	 * @param mixed $param 参数
	 */
	public function touch($cacheType,$param=null){
		$cacheName = $this->getDataKey($cacheType, $param);
		$cachefile = CACHE_DIR . $cacheName . '.cache';
		if(file_exists($cachefile)){
			touch($cachefile);
		}
		return true;
	}


	/**
	 * 写入缓存
	 * @param mixed $cacheData 数据
	 * @param string $cacheType 文件类型
	 * @param int $expire 过期时间
	 * @param mixed $param 参数
	 */
	public function write($cacheData, $cacheType,$expire = 0,$param=null) {
		$cacheName = $this->getDataKey($cacheType, $param);
		$cachefile = CACHE_DIR . $cacheName . '.cache';
		$expire = $expire > 1 ? $expire + time() : 0;
		$cacheData = array("datacontent" => $cacheData , "expiretime" =>$expire);
		$cacheData = serialize($cacheData);
		@ $fp = fopen($cachefile, 'wb') OR errorMsg($cachefile.'读取缓存失败。如果您使用的是Unix/Linux主机，请修改缓存目录 (content/cache) 下所有文件的权限为777。如果您使用的是Windows主机，请联系管理员，将该目录下所有文件设为可写');
		@ $fw = fwrite($fp, $cacheData) OR errorMsg('写入缓存失败，缓存目录 (content/cache) 不可写');
		fclose($fp);
		if (isset($this->data_cache[$cacheName])){
			unset($this->data_cache[$cacheName]);
		}
	}

	/**
	 * 读取缓存文件
	 * @param string $cacheType 文件类型
	 * @param mixed $param 参数
	 */
	public function get($cacheType,$param=null) {
		$cacheName = $this->getDataKey($cacheType, $param);
		$cachefile = CACHE_DIR . $cacheName . '.cache';
		if (isset($this->data_cache[$cacheName])) {
			return $this->data_cache[$cacheName];
		} else {
			// 如果缓存文件不存在则自动生成缓存文件
			if (!file_exists($cachefile) || !is_file($cachefile) || filesize($cachefile) <= 0) {
				return false;
			}
			if ($fp = fopen($cachefile, 'r')) {
				$data = fread($fp, filesize($cachefile));
				fclose($fp);
				$data = unserialize($data);
				if($data['expiretime'] < time() && $data['expiretime'] > 0){
					@unlink($cachefile);
					return null;
				}
				$this->data_cache[$cacheName] = $data['datacontent'];
				return $this->data_cache[$cacheName];
			}
		}
		return null;
	}

	/**
	 * 获取cache文件名
	 * @param string $cacheName
	 * @param array|string $param
	 */
	private function getDataKey($cacheName,$param){
		$data_key = $cacheName;
		if($param){
			$data_key .= '_'.md5(serialize($param));
		}
		return $data_key;
	}
}
