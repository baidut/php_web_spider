<?php
/*======================================================================*\
SPIDER - the PHP web spider
Author: Zhenqiang Ying <yingzhenqiang@163.com>
Version: 1.2

The latest version of SPIDER can be obtained from:
https://github.com/baidut/php_web_spider
\*======================================================================*/
class spider{

    private $ch;        // cURL handle
    private $error;     // error messages sent here

    function __construct() { 
        require_once(PARSER_FILE);
        if(!extension_loaded('curl'))
            exit('Fatal error:The system does not extend php_curl.dll.');
        $this-> ch = curl_init();
        $this-> reset();
    }
    function __destruct() { 
        curl_close($this-> ch);
    }
/*======================================================================*\
    Purpose:    reset spider
\*======================================================================*/
    function reset(){
        curl_setopt($this-> ch, CURLOPT_USERAGENT,      "kind spider");
        curl_setopt($this-> ch, CURLOPT_COOKIEJAR,      COOKIE_FILE);
        curl_setopt($this-> ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this-> ch, CURLOPT_TIMEOUT,        120);
    }
/*======================================================================*\
    Purpose:    fetch   a web page by url
    Input:      $url    web page address
    Output:     web page content
\*======================================================================*/
    function fetch($url){
        curl_setopt($this-> ch,CURLOPT_URL,             $url);
        curl_setopt($this-> ch,CURLOPT_COOKIE,          COOKIE_FILE); 
        curl_setopt($this-> ch,CURLOPT_FOLLOWLOCATION,  true);
        return curl_exec($this-> ch);
    }
/*======================================================================*\
    Purpose:    submit a form
    Input:      $url    web page address
                $fields form content
                    format: $fields["name"] = "value";
    Output:     web page content
\*======================================================================*/
    function post($url,$fields){
        curl_setopt($this-> ch,CURLOPT_POST,1);
        curl_setopt($this-> ch,CURLOPT_POSTFIELDS,$fields);
        curl_setopt($this-> ch,CURLOPT_URL,$url);
        curl_setopt($this-> ch,CURLOPT_COOKIE, COOKIE_FILE); 
        curl_setopt($this-> ch,CURLOPT_FOLLOWLOCATION,true);
        // 返回跳转后的页面 如果只提交表单，则返回1表示成功
        return curl_exec($this-> ch);
    }
/*======================================================================*\
    Purpose:    login
    Input:      $_url       
                $_username  
                $_password  
                $_hidden    
    Output:     the text output from the post
\*======================================================================*/
    function login($_url,$_username,$_password,$_hidden=""){
    // 分析网页，获得表单并分析
        $html = file_get_html($_url);           // 获取页面成功 
        $form = $html-> find('form',0);         // 定位表单
        $fields = array(  
                   $form-> find('input[type=text]',0)-> name => urlencode($_username),
                   $form-> find('input[type=password]',0)-> name => urlencode($_password),
                  ); 
        if($_hidden) $fields = array_merge($fields, $_hidden); // 添加hidden

        if(! $action = $form-> action) $action = $_url; // 如果action为空的话，如果不为空还要分析出主机 
        return $this-> post($_url,$fields);
    }
/*======================================================================*\
    Purpose:    根据搜索的页面地址，以及输入框位置，模拟一次输入文本搜索的操作
    Input:      $_url       
                $_txt  
                $_how2find  
    Output:     the search result
\*======================================================================*/
    function search($_url,$_txt,$_how2find){ // 
    // 分析网页，获得表单并分析，这一步不需要模拟登陆工具
        $html = file_get_html($_url);                   // 获取页面成功 
        $form = $html->find('form'.$_how2find,0);       // 定位表单echo $form;exit(0);
        // 填写搜索框
        $text = $form-> find('input[type=text]',0);
        $fields = array( $text-> name => $_txt );
        // 添加hidden域
        $hiddens = $form-> find('input[type=hidden]');
        foreach ($hiddens as $key => $hidden) {
            $fields[ $hidden-> name ] = $hidden->value;
        }
        // 分析提交动作
        $method = $form-> method;
        $action = $form-> action; // 假设是绝对路径，没有处理相对路径
        // 下面执行模拟搜索
        if($action)
            $_url = $action;
        if($method=='get')
            return $data = $this-> fetch( $_url . '?' . http_build_query($fields) );
        if($method=='post'){
            // print_r($_url);print_r($fields);exit(0);
            // echo $this-> fetch($action);
            return $this-> post($_url,$fields);
        }
    }
}
?>