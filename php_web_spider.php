<?php
define("MY_COOKIE",		"cookie.txt");

if(!extension_loaded('curl')) exit('系统没有扩展php_curl.dll,出错了。');
$ch = curl_init();

// 可复用的代码提取为函数
// 自动分析出登录页面是自身 从post的目的地看即可
// 简单地抽取第一个表单form和第一个找到的txt域作为用户名
// 分析工具制作

function login($loginUrl,$username,$password,$hidden=""){
global $ch;
	require_once("./lib/simple_html_dom.php");
// 分析网页，获得表单并分析，这一步不需要模拟登陆工具
	$html = file_get_html($loginUrl);		// 获取页面成功 
	$form = $html->find('form',0);		 	// 定位表单
	$fields = array(  
               $form-> find('input[type=text]',0)-> name => urlencode($username),
               $form-> find('input[type=password]',0)-> name => urlencode($password),
              ); 
	if($hidden) $fields = array_merge($fields, $hidden); // 添加hidden
	if(! $action = $form-> action) $action = $loginUrl; // 如果action为空的话，如果不为空还要分析出主机 
	// 下面进行登录
	curl_setopt($ch,CURLOPT_USERAGENT,"Dut helper");
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
	
    curl_setopt($ch,CURLOPT_COOKIEJAR, MY_COOKIE); //保存连接的cookie
    curl_setopt($ch,CURLOPT_COOKIE, MY_COOKIE); // 用保存的cookie连接该地址
	
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch,CURLOPT_URL,$action);
    $data=curl_exec($ch);
    if(curl_error($ch)){
		exit("登录失败".curl_error($ch).$data);
		return false;
	}
	return true;
}	
function fetch($url){
global $ch;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_COOKIE, MY_COOKIE); 
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    return curl_exec($ch); 
}
function post($url,$fields){
global $ch;
	curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_COOKIE, MY_COOKIE); // 用保存的cookie连接该地址 不保存cookie的话PHPSESSID会变
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    return curl_exec($ch);
}
function close_my_curl(){
global $ch;
	curl_close($ch);
}
?>