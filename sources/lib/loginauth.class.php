<?php
class LoginAuth{
	
	/**
	 * 验证用户是否处于登录状态
	 */
	public static function isLogin() {
		global $userData;
		$auth_cookie = '';
		if(isset($_SESSION[AUTH_NAME])){
			return true;
		}
		if(isset($_COOKIE[AUTH_NAME])) {
			$auth_cookie = $_COOKIE[AUTH_NAME];
		} elseif (isset($_POST[AUTH_NAME])) {
			$auth_cookie = $_POST[AUTH_NAME];
		} else{
			return false;
		}
		
		$userData = self::validateAuthCookie($auth_cookie);
		if($userData === false) {
			return false;
		}else{
			$_SESSION[AUTH_NAME] = $userData;
			return true;
		}
	}
	
	/**
	 * 保存登陆凭证
	 * @param string $username
	 * @param int $expiration
	 */
	public static function saveAuth($userid,$username,$saveCookie=false,$expiration=360000000){
		$hash = md5($username . '|' . AUTH_KEY);
		$expiration = time() + $expiration;
		$value = $username . '|' . $expiration.'|'.$hash.'|'.$userid;
		if($saveCookie){
			setcookie(AUTH_NAME,$value,$expiration,'/');
		}
		$_SESSION[AUTH_NAME] = self::validateAuthCookie($value);
	}
	
	public static  function getPassword($username,$password){
		return self::genPass($username, $password);
	}
	
	public static function checkPassword($username,$password,$stored_hash){
		$password = self::genPass($username, $password);
		return $password == $stored_hash;
	}
	
	private static function genPass($username,$password){
		$hash = hash_hmac('md5', $username.$password,AUTH_KEY);
		$password = '$P$B'.base64_encode($hash);
		return $password;
	}

	private static function validateAuthCookie($cookie) {
		if (empty($cookie)) {
			return false;
		}

		$cookie_elements = explode('|', $cookie);
		if (count($cookie_elements) != 4) {
			return false;
		}
		list($username, $expiration, $hmac, $uid) = $cookie_elements;
		
		$hash = md5($username . '|' . AUTH_KEY);
				
		if (!empty($expiration) && $expiration < time()) {
			return false;
		}

		if ($hmac != $hash) {
			return false;
		}
		
		$user = cache_get(CACHE_USER,$uid);
		if (!$user) {
			return false;
		}
		return $user;
	}
}