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

// Chinese
// define('TEXT_BACK_TO_TOP','回顶部');
// define('TEXT_SHARE','分享');
// define('TEXT_HOME','主页');

// English
define('TEXT_BACK_TO_TOP','BACK TO TOP');
define('TEXT_SHARE','SHARE');
define('TEXT_HOME','HOME');
define('TEXT_SETTINGS','SETTINGS');

class ui_Dom{
    public $attr = array(); // 'value'=>3 关联数组形式
    public $children = array();
    private $tag = null;
    private $innertext = '';  // 是否需要识别html标签的能力？可以解析出内容——html_simple_dom 选择性分析比较高效

    function __construct($tag='div',$content='') {
        $this->tag = $tag;
        if($content!='')$this->innertext = $content;
    }

    function __destruct() {
    	foreach($this->children as $child){
    		$child = null;
    	}
    }

    function __toString(){
    	$ret= '<'.$this->tag;
		foreach ($this->attr as $key => $value) {
			$ret.=' '.$key.'="'.$value.'"';
		}
		$ret.='>';
		foreach($this->children as $child){
			$ret.=$child;
		}
		return $ret.$this->innertext.'</'.$this->tag.'>';
	}

	function append($node,$content=''){ // TODO 这里的$content改为属性集合
        // new Dom('div',$children,$attrs) new Dom('div',$innerHTML,$attrs)
        // append(new Dom('div','this is a div'), array('color'=>'yellow'));
		$ret = is_string($node)? (new ui_Dom($node,$content) ): $node;
		array_push($this->children,$ret); 
		return $ret;
	}
	function prepend($node){$ret = new ui_Dom($node); array_unshift($this->children,$ret); return $ret;}
	function after($node){}
	function before($node){}
	function text($t){
        // 不开放$this->innertext成员，用这个函数可以解析元素，然后插入
        $this->innertext = $t;return $this;
    }
	function html($t){}
	function val($v){$this->attr['value']=$v;return $this;}
	function attr($name,$value=''){
		if($value){
			$this->attr[$name]=$value;
			return $this; // 属性设置支持链式操作
		}
		else return $this->attr[$name]; // isset($this->attr[$name]) // Undefined index: name
	}
	function __get($name) { return $this->attr[$name]; }
    function __set($name, $value) { $this->attr[$name] = $value; }

    function appendText($text) { $this->innertext .=$text; }

    // jQuery 遍历 - 后代 不支持批量操作
    // $("div").children().css({"color":"red","border":"2px solid red"}); 
    function find($selector,$idx=null){ // lowercase
        if($selector=='*'){
            $found = $this->children;
        }
        else{
            $found = array();
            foreach ($this->children as $key => $child) {
                if($child->tag == $selector)array_push($found,$child);
            }
        }
        // TODO 添加解析innerHtml中搜索，innerHtml一般为不进行操作的元素，暂时不处理，可以在appendHTML的时候进行解析
        if (is_null($idx)) return $found;
        else if ($idx<0) $idx = count($found) + $idx;
        return (isset($found[$idx])) ? $found[$idx] : null;
    }
    // ui相关
    // 添加指向的标签
    function label($text){
    	$this->appendText('<label for="'.$this->attr('name').'">'.$text.'</label>');
    	return $this;
    }
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
	function __construct($id='',$data) { // $title直接作为id比较方便 首页 Home
        parent::__construct();
        $this->attr('data-role','page');
        // $this->content = new ui_Dom('div',ATTR,'data-role="content">'); 比较麻烦
        // $this->content = new ui_Dom('div',attr('data-role',"content")); 
        $this->header = new ui_Dom('div');
        $this->append($this->header);

        if($id!=''){
        	$this->id = $id;
        	$this->header->attr('data-role','header')->append(new ui_Dom('h1',$id)); //text("<h1>$id</h1>");
            // if($id==TEXT_HOME)
        }
        $this->content = new ui_Dom('div');
        $this->content->attr('data-role','content');
        $this->append($this->content);

        $this->footer = new ui_Dom('div');
        $this->footer->attr('data-role','footer')->text('<a href="#" data-role="button" data-icon="plus">'.TEXT_SHARE.'</a><a href="javascript:scroll(0,0)" data-role="button" data-icon="arrow-u">'.TEXT_BACK_TO_TOP.'</a>');
        $this->append($this->footer);

        if($data)$this->appendContent($data);
    }
    function title($t){
        $this->header->find('h1',0)->text($t); // 多个h1的情况？ 
    }
    function appendContent($node){
    	$this->content->append($node);
    }
}

// 自动追加计数气泡
// 支持拆分按钮，也可在新闻页面提供原网址链接
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

    // < method="post" action="demoform.asp">
    //   <div data-role="fieldcontain">
    //     <label for="fullname">全名：</label>
    //     <input type="text" name="fullname" id="fullname">       
    //     <label for="bday">生日：</label>
    //     <input type="date" name="bday" id="bday">
    //     <label for="email">电邮：</label>
    //     <input type="email" name="email" id="email" placeholder="您的邮箱地址..">
    //   </div>
    //   <input type="submit" data-inline="true" value="提交">
    // </form>
class ui_JMForm extends ui_Dom{
	function __construct($action='#',$method='post') {
        parent::__construct('form');
        $this->attr('method',$method);
        $this->attr('action',$action);
    }
    // labels and doms
    // 与CI的表单辅助函数不同的是，这里不需要了解表单的结构，只需要明确需要什么数据即可
    // 视图方面默认提供最优的，如果需要调整则通过链式修改相应属性
    function appendSelect($name,$data,$multiple=false){
    	$select = new ui_Dom('select');
    	if($multiple){
    		$select->attr('multiple','multiple');
    		$select->appendText('<option>你可以选择多个</option>');
    	}
    	foreach ($data as $key => $value) {
    		// 对于不可能继续追加子元素的情况采用appendText比较高效简单
    		$select->appendText("<option value='$value'>$key</option>");
    	}
    	$this->append($select);
    	return $select; // 继续对select进行设置
    }
    function ajax(){
    	$this->attr('onsubmit','javascript:return ajax();');
    	// 数据改为通过ajax方式提交
    }
}

// 与ui呈现有关的参数和逻辑相关的参数应当分开配置 label的添加方式
function ui_JMSelect($name,$data,$multiple=false){
	$select = new ui_Dom('select');
	if($multiple){
		$select->attr('multiple','multiple');
		$select->appendText('<option>你可以选择多个</option>');
	}
	if(!$native_menu)$select->attr('data-native-menu','false');
	foreach ($data as $key => $value) {
		// 对于不可能继续追加子元素的情况采用appendText比较高效简单
		$select->appendText("<option value='$value'>$key</option>");
	}
	return $select;
}

// append( label(''),select('') );

// 列表转select
// <form method="post" action="demoform.asp">
//       <fieldset data-role="fieldcontain">
//         <label for="day">选择天</label>
//         <select name="day" id="day">
//          <option value="mon">星期一</option>
//          <option value="tue">星期二</option>
//          <option value="wed">星期三</option>
//          <option value="thu">星期四</option>
//          <option value="fri">星期五</option>
//          <option value="sat">星期六</option>
//          <option value="sun">星期日</option>
//         </select>
//       </fieldset>
//       <input type="submit" data-inline="true" value="提交">
//     </form>


// <form method="post" action="demoform.asp">
//    <fieldset>
//    <label for="day">您可以选择多天：</label>
//    <select name="day" id="day" multiple="multiple" data-native-menu="false">
// 	<option>天</option>
// 	<option value="mon">星期一</option>
// 	<option value="tue">星期二</option>
// 	<option value="wed">星期三</option>
// 	<option value="thu">星期四</option>
// 	<option value="fri">星期五</option>
// 	<option value="sat">星期六</option>
// 	<option value="sun">星期日</option>
//    </select>
//    </fieldset>
//    <input type="submit" data-inline="true" value="提交">
//   </form>

