<?php 
// localhost/GitHub/php_simple_ui/php_simple_ui.php
// 方案1：输出jQuery语句在客户端创建
// 方案2：服务器端生成ui，需要消耗计算资源，如果便捷性大于速度牺牲的话有意义，用简短的代码，整洁的结构控制ui输出

// 相关项目
// phpQuery—基于jQuery的PHP实现http://www.cnblogs.com/in-loading/archive/2012/04/11/2442697.html

/*ui_Dom的使用
$ui = new ui_Dom('html');
$body = $ui->append('body');
$head = $ui->prepend('head');
$head->html('<title>php_simple_ui</title>');
$body->bgcolor = 'yellow';
// 链式
$body->append('input')->attr('type','button')->val('hello world');
// text('hello world');
echo $ui;
*/

/*
$ui = new ui_jQueryMobile();
echo $ui;

// <html><head><script src="http://code.jquery.com/jquery-1.8.3.min.js"></script><link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"><script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></head><body></body></html>
*/



class ui_Dom{
    public $attr = array(); // 'value'=>3 关联数组形式
    public $children = array();
    private $ele = null;
    private $innertext = '';  // 是否需要识别html标签的能力？可以解析出内容——html_simple_dom 选择性分析比较高效

    function __construct($ele='div',$content='') {
        $this->ele = $ele;
        if($content!='')$this->innertext = $content;
    }

    function __destruct() {
    	foreach($this->children as $child){
    		$child = null;
    	}
    }

    function __toString(){
    	$ret= '<'.$this->ele;
		foreach ($this->attr as $key => $value) {
			$ret.=' '.$key.'="'.$value.'"';
		}
		$ret.='>';
		foreach($this->children as $child){
			$ret.=$child;
		}
		return $ret.$this->innertext.'</'.$this->ele.'>';
	}

	function append($node,$content=''){
		$ret = is_string($node)? (new ui_Dom($node,$content) ): $node;
		array_push($this->children,$ret); 
		return $ret;
	}
	function prepend($node){$ret = new ui_Dom($node); array_unshift($this->children,$ret); return $ret;}
	function after($node){}
	function before($node){}
	function text($t){$this->innertext = $t;return $this;}
	function html($t){}
	function val($v){$this->attr['value']=$v;return $this;}
	function attr($name,$value){
		$this->attr[$name]=$value;
		return $this;
	}
	function __get($name) { return $this->attr[$name]; }
    function __set($name, $value) { $this->attr[$name] = $value; }

    function appendText($text) { $this->innertext .=$text; }
}

// jQuery Mobile UI 建模

class ui_jQuery extends ui_Dom{
	public $head;
	public $body;
	function __construct() {
        parent::__construct('html');
        $this->head = $this->append('head','<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>');
        $this->body = $this->append('body');
    }
}

class ui_jQueryMobile extends ui_jQuery{

	function __construct($page='') {
        parent::__construct();
        $script = new ui_Dom('script');
        $script->src='';
        $this->head->appendText('<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"><script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>');

        if($page) $this->body->append($page);
    }
    function appendPage($title){
    	$page = new ui_JMPage($title);
    	$this->body->append($page);
    	return $this;
    }
}

// <a href="#pagetwo" data-rel="dialog">转到页面二</a> 对话框形式打开页面

class ui_JMPage extends ui_Dom{
// 	<div data-role="page">

//   <div data-role="header">
//     <h1>欢迎访问我的主页</h1>
//   </div>

//   <div data-role="content">
//     <p>我是一名移动开发者！</p>
//   </div>

//   <div data-role="footer">
//     <h1>页脚文本</h1>
//   </div>

// </div>
	public $content;
	public $header;
	public $footer;
	function __construct($title='',$data) {
        parent::__construct();
        $this->attr('data-role','page');
        // $this->content = new ui_Dom('div',ATTR,'data-role="content">'); 比较麻烦
        // $this->content = new ui_Dom('div',attr('data-role',"content")); 
        if($title!=''){
        	$this->id = $title;
        	$this->header = new ui_Dom('div');
        	$this->header->attr('data-role','header')->text("<h1>$title</h1>");
        	$this->append($this->header);
        }
        $this->content = new ui_Dom('div');
        $this->content->attr('data-role','content')->text("<h1>$title</h1>");
        $this->append($this->content);

        if($data)$this->appendContent($data);
    }
    function appendContent($node){
    	$this->content->append($node);
    }
}

// 自动追加计数气泡

class ui_JMListView extends ui_Dom{
	function __construct($data,$order=false,$data_inset=false) {
        parent::__construct(($order)?'ol':'ul');
        $this->attr('data-role','listview');
        $this->attr('data-inset',$data_inset?'true':'false');
        $this->appendData($data);
        // if($id!='')$this->attr('id',$id);
    }
    // 数据可视化 $array 转为 list
	// 将数据装入ui容器中
    // function appendItem($title,$link=''){
	// 	$this->appendText('<li><a href="'.($link)?$link:'#'.'">'.$title.'</a></li>');
	// }
	// function appendDivider($title){
	// 	$this->appendText('<li data-role="list-divider">'.$title.'</li>');
	// }
	function appendList($data,$title=''){
		// 自动追加计数气泡
		if($title)$this->appendText('<li data-role="list-divider">'.$title.'<span class="ui-li-count">'.count($data).'</span></li>');
		foreach ($data as $key => $value) { // value可以是一个链接
			if(is_array($value))$this->appendText('<li>'.$value['link'].'</li>');
			else $this->appendText('<li>'.$value.'</li>');
		}
	}
	function appendData($data,$title=''){
		foreach ($data as $key => $value) {
			$this->appendList($value,$key);
		}
		return $this;
	}
	// setOption
	function addFilter($placeholder=''){
		$this->attr('data-filter','true');
		if($placeholder!='')$this->attr('data-filter-placeholder',$placeholder);
	}
}




