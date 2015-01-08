<?php

// http://localhost/github/php_web_spider/application/news.php
// echo 'hello';exit();
// 添加svn 链接sae 发布
// 采集大学城内的活动信息 暂时只有北大信工
// TODO 按时间，按学院

header("Content-type:text/html;charset=utf-8");

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

// 新闻抓取
$sp = new Spider;
$url = 'http://www.ece.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=503';
$news['信工'] = $sp-> fetch_news($url);
// print_r($news);exit(0);

// 
require_once('../../php_simple_ui/php_simple_ui.php');
// $ui = new php_simple_ui(UI_JQueryMobile);
// $echo $ui;

// 一级一级构建方式
$list = new ui_JMListView($news);
$page = new ui_JMPage('南燕新闻',$list);
$ui = new ui_jQueryMobile($page);

echo $ui;

// print_r($data);
// 前端采用jQueryMobile 参考之前的成果

// 杂记
// 判断一个PHP数组是关联数组还是数字数组  return array_keys($arr) !== range(0, count($arr) - 1);