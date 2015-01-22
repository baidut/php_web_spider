<?php 

/**
 * @author    Zhenqiang Ying <https://github.com/baidut>
 * @todo      切换语言，版本，主题需要重新获取网页，不适合需要方便切换外观的用户
 */

// http://localhost/github/php_web_spider/application/news.php?lang=Chinese&jm_version=1.3.2
// 这里可以定义全局变量，避免使用临时变量
switch(isset($_POST['lang'])?$_POST['lang']:'English'){
case 'Chinese': //简体中文
	define('TEXT_BACK_TO_TOP','回顶部');
	define('TEXT_BACK','返回');
	define('TEXT_SHARE','分享');
	define('TEXT_HOME','主页');
	define('TEXT_SETTINGS','设置');
	define('TEXT_SUBMIT','提交');
	break;
default:
case 'English':
	define('TEXT_BACK_TO_TOP','TOP');
	define('TEXT_BACK','BACK');
	define('TEXT_SHARE','SHARE');
	define('TEXT_HOME','HOME');
	define('TEXT_SETTINGS','SETTINGS');
	define('TEXT_SUBMIT','SUBMIT');
	break;
}

define('THEME',isset($_POST['theme'])? $_POST['theme']:'a');

// Latest version can be found in http://jquerymobile.com/download/
define('JQ_VERSION',isset($_POST['jq_version'])? $_POST['jq_version']:'1.11.1');
define('JM_VERSION',isset($_POST['jm_version'])? $_POST['jm_version']:'1.4.5');

// This version have trouble fetching AJAX data(always post to self)
//define('JQ_VERSION',isset($_POST['jq_version'])? $_POST['jq_version']:'1.8.3');
//define('JM_VERSION',isset($_POST['jm_version'])? $_POST['jm_version']:'1.3.2');

define('JQ_JS','http://code.jquery.com/jquery-'.JQ_VERSION.'.min.js');
define('JM_CSS','http://code.jquery.com/mobile/'.JM_VERSION.'/jquery.mobile-'.JM_VERSION.'.min.css');
define('JM_JS','http://code.jquery.com/mobile/'.JM_VERSION.'/jquery.mobile-'.JM_VERSION.'.min.js');
