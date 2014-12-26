<?php
/*======================================================================*\
SPIDER - the PHP web spider
Author: Zhenqiang Ying <yingzhenqiang@163.com>
Version: 1.2.1

The latest version of SPIDER can be obtained from:
https://github.com/baidut/php_web_spider

Feature:
-fetch web pages 网页抓取
-get data 数据提取
\*======================================================================*/
class spider{

    private $ch;        // cURL handle
    private $error;     // error messages sent here
    private $html;     	// carry last fetched html page

    function __construct() { 
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
        curl_setopt($this-> ch, CURLOPT_USERAGENT,     "Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; .NET4.0E; .NET4.0C; InfoPath.3; rv:11.0) like Gecko"); //"kind spider"
        curl_setopt($this-> ch, CURLOPT_COOKIEJAR,      "./cookie.txt");
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
        curl_setopt($this-> ch,CURLOPT_COOKIE,          "./cookie.txt"); 
        curl_setopt($this-> ch,CURLOPT_FOLLOWLOCATION,  true);
        return $this-> html = curl_exec($this-> ch);
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
        return $this-> html = curl_exec($this-> ch);
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
		// 添加智能分析判断表单位置 原来是直接取第一个表单
		$forms = $html-> find('form');
		foreach($forms as $form){
			if( $form-> find('input[type=password]',0) ){
				$inputs = $form-> find('input[name]');
				foreach($inputs as $input){
					switch($input-> type){
						case 'text': 
							$name_username = $input-> name;// 假设只有一个输入框，不需要填写验证码
							$fields[$name_username] = urlencode($_username);
							break;
						case 'password':
							$name_password = $input-> name;
							$fields[$name_password] = urlencode($_password);
							break;
						case 'radio':
							$name_radio =  $input-> name;
							if(!isset($first_radio[$name_radio]) || isset($input-> checked ) ){
								$first_radio[$name_radio]=false;
								$fields[$name_radio] = $input-> value;
							}
							break;
						default:
							if(trim($input-> value))
								$fields[$input-> name] = $input-> value;
					}
				}
				// 此处可能会有冲突问题，附加的hidden变化由js触发，需要重写
				if($_hidden) $fields = array_merge($fields, $_hidden); // 添加hidden
				// 添加自动补充默认选项的提交
				
				if( $action = $form-> action) $_url = $action; // 如果action为空的话，如果不为空还要分析出主机  // 修正bug
				//print_r($fields);
				//exit(0);
				return $this-> post($_url,$fields);
			}
		}
		$this-> error = 'ERROR: form cannnot be found!';
		return false;
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
/*======================================================================*\
    Output:     the title of the web page
\*======================================================================*/
    function fetch_title($_url=""){ 
        if($_url&&$this-> fetch($_url)){
            $html = str_get_html($this-> html);
            return $html->find('title',0)->plaintext;
        }
        return false;
    }
/*======================================================================*\
    Output:     basic information of the web page
\*======================================================================*/
    function fetch_info($_url=""){ 
        if($_url&&$this-> fetch($_url)){
            $html = str_get_html($this-> html);
            $header = $html->find('head',0);
            $info['title'] = $header ->find('title',0)->plaintext;
            if( $keywords = $header ->find('meta[name=keywords]',0) )
                $info['keywords'] = $keywords->content;
            if( $description = $header ->find('meta[name=description]',0))
                $info['description'] = $description ->content;
            return $info;
        }
        return false;
    }
/*======================================================================*\
    Purpose:    屏蔽网页分析工具实现，提供格式化的表格数据
    Input:       
    Output:     the text output from the post
	NOTICE:		没有声明url时，取上次页面
	解析为普通二维数组不便于查询
	解析为$data[第几个][某个字段]更自然
\*======================================================================*/
    function fetch_table($_how2find,$_url=""){ 
		if($_url)$this-> fetch($_url);
        $html = str_get_html($this-> html);
        $table = $html->find('table'.$_how2find,0);
		if(!$table) {
			$this-> error = "Failed to locate the informatin table";
			return false;
		}
		// 可能有th标题 这里暂不处理 thead tbody tfood 都暂不处理
		// table to array
		$trs = $table->find('tr');
		$th = array_shift($trs);
		foreach( $th-> find('td') as $key => $val) {
			$name[$key] = $val->innertext;//plaintext;
		}
		if(!$trs) {
			$error = "No data";
			return false;
		}
		foreach( $trs as $key => $val)
			foreach($val->find('td') as $k => $v)
				$data[$key][ $name[$k] ] = trim( $v-> innertext );
		
		//header("Content-type: text/html; charset=utf-8");
		//print_r($data);
		return $data;
    }

	// to be added
}

?>