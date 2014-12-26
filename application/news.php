<?php

// http://localhost/github/php_web_spider/application/news.php
// echo 'hello';exit();

header("Content-type:text/html;charset=utf-8");

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

// 新闻抓取
$sp = new Spider;
$url = 'http://www.ece.pku.edu.cn/index.php?m=content&c=index&a=lists&catid=503';
$news = $sp-> fetch_news($url);
// print_r($data);
