<?php

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

// 需求说明：抓取豆瓣图书信息，添加图书馆是否有馆藏标签 需要对每个图书进行检索 pdf下载提供查询
// 小书签方式需要跨域，要用json后期再进行
