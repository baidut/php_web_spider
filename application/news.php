<?php

// TODO
// 数据、视图缓存到数据库

// ISSUE
// 页脚重复，页面之间的导航等重复

// http://localhost/github/php_web_spider/application/news.php
// echo 'hello';exit();
// 添加svn 链接sae 发布
// 采集大学城内的活动信息 暂时只有北大信工
// TODO 按时间，按学院
// TODO 链接默认从新标签页打开 页面配置功能

// 已完成
// 分割链接，右侧小图标转入原来网页，默认进入子页面，将新闻内容提取并呈现

// TOOD
// 添加新闻缩略图
// 添加小图标 北大，清华，哈工大或者学院标记


// 用户、社区功能
// 小工具 通过AJAX 过滤时间
// 配置搜索选项，通过提交表单，不用AJAX

// 不建议使用appendText

// 可折叠 可折叠列表不支持计数气泡

// 分页 显示更多
// 自动填入过滤常用词 新闻 通知 学术讲座



// 获取配置信息
$filter = (isset($_GET['filter']))? $_GET['filter']:"week";

header("Content-type:text/html;charset=utf-8");

// 根据配置信息抓取数据
define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php'); // 会分析提交的UI配置信息

$sp = new Spider;

// $tmp = $sp-> fetch_news('http://www.phbs.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=419');
// print_r($tmp);exit(0);

// 网址信息数据 建议从数据库中获取，相同学院只是不同path，根地址相同

$news['信息工程学院'] = $sp-> fetch_news('http://www.ece.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=502',$filter); // 提供特殊形式链接
$news['汇丰商学院'] = $sp-> fetch_news('http://www.phbs.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=419',$filter);
// $news['化学生物学与生物技术学院'] = $sp-> fetch_news('http://www.scbb.pkusz.edu.cn/index.php?m=content&c=index&a=lists&catid=862');

// $news['环境与能源学院'] = $sp-> fetch_news('http://see.pkusz.edu.cn/news_cn.aspx');
// $news['城市规划与设计学院'] = $sp-> fetch_news('http://see.pkusz.edu.cn/news_cn.aspx');
// $news['城市规划与设计学院'] = $sp-> fetch_news('http://sam.pkusz.edu.cn/index.php?m=content&c=index&a=lists&catid=395');

// 讲座信息
$lecture['信息工程学院'] = $sp-> fetch_news('http://www.ece.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=503',$filter);
$lecture['汇丰商学院'] = $sp-> fetch_news('http://www.phbs.pku.edu.cn/list-812-1.html',$filter);
// $lecture['新材料学院'] = $sp-> fetch_news('http://sam.pkusz.edu.cn/index.php?m=content&c=index&a=lists&catid=809');
// Undefined variable: find_link in D:\Program Files\xampp\htdocs\GitHub\php_web_spider\core\php_web_spider.php on line 259

// 添加拆分按钮
// foreach ($news as $key => $value) {
// 	# code...
// }

// print_r($news);exit(0);
// 采用AJAX技术获取数据，或者只进行数据过滤 重新接收页面开销太大。AJAX为JS代码，处理成php端。
// 不需要采用AJAX，配置好后，需求一般不变更。记录到主页即可，get方式配置，或post+cookie进行配置

// $ui = new php_simple_ui(UI_JQueryMobile);
// $echo $ui;
// 配置项
// 新闻，学术讲座，活动
// 配置页面，提供学院选择配置，内容配置，日期配置


// 内容配置
$opt_schools = array(
	'信工'=>'SECE',
	'化生'=>'SCBB',
	'环能'=>'SEE',
	'城规'=>'SUPD',
	'新材料'=>'SAM',
	'汇丰'=>'PHBS',
	'法学院'=>'STL',
	'人文社科'=>'SHSS'
	);
$opt_content = array(
	'新闻'=>'NEWS',
	'活动'=>'ACT',
	'讲座'=>'LECTURE',

	);
$opt_time = array(
	'一周内'=>'week',
	'一月内'=>'month',
	);
// 视图界面配置
$opt_theme = array('a','b','c','d','e');
$opt_jq_version = array('1.11.1','1.8.3','1.6.2');
$opt_jm_version = array('1.4.5','1.3.2','1.2.1','1.1.2','1.0.1','1.0b2');

// w3cschool : jq1.8.3 + jm1.3.2

// 分页模式-每页显示个数，显示更多（AJAX）
// pageView传入提取内容的相关启发信息，得到页面的核心内容，然后呈现，并提供返回按钮 核心内容可能为图片

// 数据和视图分离 -----
require_once('third_party/php_simple_ui/php_simple_ui.php');
// 一级一级构建方式

// 先将数据放到显示组件容器中
$form_content = new ui_JMForm();
$form_content->appendSelect('schools',$opt_schools,true)->attr('data-native-menu','false')->label('选择1个或多个学院'); 
$form_content->appendSelect('time',$opt_time,false)->attr('data-native-menu','false')->label('选择时间段'); 

$form_view = new ui_JMForm();
$form_view->appendSelect('jq_version',$opt_jq_version,false)->attr('data-native-menu','false')->label('jQuery 版本'); 
$form_view->appendSelect('jm_version',$opt_jm_version,false)->attr('data-native-menu','false')->label('jQuery Mobile 版本'); 

$form_login_lib = new ui_JMForm('lib.php');
$form_login_lib->appendInput('text','student_no','登录名／证号')->label('用户名');
$form_login_lib->appendInput('password','lib_psw','初始密码为8位出生年月日')->label('密码');

$list_book = new ui_Dom('a','你还没有登陆，点击登陆');
$list_book->attr('href','#login');
$list_book->attr('data-rel','dialog');
// 注意如果一个元素的label有多个，则点击后会显示多个标签的文本。因此页面不要有重复元素 

$list['news'] = new ui_JMListView($news);
$list['news']->addFilter('搜索活动');
$list['lecture'] = new ui_JMListView($lecture);
$list['lecture']->addFilter('搜索活动');

// 视图加强，只是对jQueryMobile有效的样式属性的设置
// 逻辑相关的放在构造中，视图加强通过链式做不允许连续append

// 再将组件添加到页面中
// 可以添加多个页面，关联数组id直接生成id
$pages['home'] = new ui_JMPage('主页');
$pages['setting'] = new ui_JMPage('设置',array($form_content,$form_view));
$pages['login'] = new ui_JMPage('登陆',$form_login_lib); // 登陆弹窗
$pages['lib'] = new ui_JMPage('图书馆',$list_book);
$pages['article'] = new ui_JMPage('文章');
$pages['news'] = new ui_JMPage('新闻',$list['news']);
$pages['lecture'] = new ui_JMPage('讲座',$list['lecture']);
// 页面点缀
$pages['home']->rightAnchor('setting')->text('设置');;

// 最后汇总页面
$ui = new ui_jQueryMobile($pages);
echo $ui;

exit(0);

// 无需定义页面之间连接，直接自动生成导航栏、

$list = new ui_JMListView($news);
$list->addFilter('搜索活动');
$page = new ui_JMPage('南燕新闻',array($form,$list));
// $page->title('南燕助手');
// $page->appendContent($list);
// $page->content->appendText();
$page->header->appendText('<a href="#" data-role="button" data-icon="home">首页</a>');
$page->header->appendText('<a href="#" data-role="button" data-icon="grid" class="ui-btn-right">选项</a>');
$ui = new ui_jQueryMobile($page);

// TODO 添加配置页面
// $page = new ui_JMPage();

echo $ui;

// print_r($data);
// 前端采用jQueryMobile 参考之前的成果

// 杂记
// 判断一个PHP数组是关联数组还是数字数组  return array_keys($arr) !== range(0, count($arr) - 1);