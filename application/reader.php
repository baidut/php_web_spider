<?php
// 过滤出核心信息
// 先提取页面主体部分

// http://localhost/github/php_web_spider/application/reader.php?url=http://www.phbs.pku.edu.cn/content-419-2333-1.html
// $for = $_GET['for'];

$url = urldecode($_GET['url']);

header("Content-type:text/html;charset=utf-8");

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

// 提取核心内容
$sp = new Spider;
$article = $sp-> fetch_main_content($url);
$info = $sp->fetch_info();
// $echo $article;exit(0);

// UI呈现
require_once('third_party/php_simple_ui/php_simple_ui.php');
$page = new ui_JMPage(/*$info['title']*/'新闻详情',$article);
$page->header->appendText('<a href="javascript:history.go(-1);" data-role="button" data-icon="home">返回</a>');
$ui = new ui_jQueryMobile($page);

echo $ui;