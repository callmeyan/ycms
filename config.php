<?php
/**
 * 基础配置文件,请不要随意修改
 * 
 * @package ycms
 */

define('YCMS_ROOT', dirname(__FILE__).'/');
define('IN_YCMS', true);

/** 开发者专用： 调试模式 
 * 将这个值改为“true”，系统 将显示所有用于开发的提示。
 * 建议插件开发者在开发环境中启用本功能。
 */
define("YCMS_DEBUG", true);

/** 网站地址  */
define("SITE_URL", 'http://ycms.t2.52api.tk/');
define("URL_EXT", "");
define("BLOG_URL", SITE_URL);


// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** 数据库的名称  */
define('DB_NAME', 'test');

/** MySQL 数据库用户名 */
define('DB_USER', 'root');

/** MySQL 数据库密码 */
define('DB_PASSWORD', '123456');

/** MySQL 主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据表 前缀 */
define("DB_PREFIX", "y_");


//auth key
define('AUTH_KEY','db&v@e!**^0W%o)6J8eee0');
//cookie name
define('AUTH_NAME','YC_AUTHCOOKIE_UCA6LumREqugOekKxE2H');

