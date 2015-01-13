<?php

// http://localhost/github/php_web_spider/application/news.php
// echo 'hello';exit();
// 添加svn 链接sae 发布
// 采集大学城内的活动信息 暂时只有北大信工
// TODO 按时间，按学院
// TODO 链接默认从新标签页打开 页面配置功能

// TOOD
// 添加新闻缩略图
// 添加小图标 北大，清华，哈工大或者学院标记
// 分割链接，右侧小图标转入原来网页，默认进入子页面，将新闻内容提取并呈现

// 用户、社区功能

// 小工具 通过AJAX 过滤时间
// 配置搜索选项，通过提交表单，不用AJAX

// 不建议使用appendText

// 可折叠 可折叠列表不支持计数气泡

// 分页 显示更多
// 自动填入过滤常用词 新闻 通知 学术讲座

// Fatal error: Call to a member function find() on a non-object in D:\Program Files\xampp\htdocs\GitHub\php_web_spider\core\php_web_spider.php on line 222



header("Content-type:text/html;charset=utf-8");

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

// 新闻抓取
$sp = new Spider;
$url = 'http://www.ece.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=503';

// $tmp = $sp-> fetch_news('http://www.phbs.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=419');
// print_r($tmp);exit(0);

// 网址信息数据 建议从数据库中获取

$news['信息工程学院'] = $sp-> fetch_news($url); // 提供特殊形式链接
$news['汇丰商学院'] = $sp-> fetch_news('http://www.phbs.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=419');
// $news['化学生物学与生物技术学院'] = $sp-> fetch_news('http://www.scbb.pkusz.edu.cn/index.php?m=content&c=index&a=lists&catid=862');

// $news['环境与能源学院'] = $sp-> fetch_news('http://see.pkusz.edu.cn/news_cn.aspx');
// $news['城市规划与设计学院'] = $sp-> fetch_news('http://see.pkusz.edu.cn/news_cn.aspx');
// $news['城市规划与设计学院'] = $sp-> fetch_news('http://sam.pkusz.edu.cn/index.php?m=content&c=index&a=lists&catid=395');

// 讲座信息
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
$opt_date = array(
	'一周内'=>'week',
	'一月内'=>'month',
	);
// 分页模式-每页显示个数，显示更多（AJAX）
// pageView传入提取内容的相关启发信息，得到页面的核心内容，然后呈现，并提供返回按钮 核心内容可能为图片


// 根据get获取配置信息





// 数据和视图分离 -----
require_once('third_party/php_simple_ui/php_simple_ui.php');
// 一级一级构建方式
$form = new ui_JMForm();
$form->appendSelect('schools',$opt_schools,true)->label('选择1个或多个学院')->attr('data-native-menu','false'); // 视图加强，只是对jQueryMobile有效的样式属性的设置
// 逻辑相关的放在构造中，视图加强通过链式做不允许连续append


// 可以添加多个页面，关联数组id直接生成id
$pages['setting'] = new ui_JMPage('设置');
$pages['login'] = new ui_JMPage('登陆');
$pages['article'] = new ui_JMPage('文章');
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