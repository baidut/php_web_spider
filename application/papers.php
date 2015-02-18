<?php

header("Content-type:text/html;charset=utf-8");

define('SPIDER_PATH','../core/');
require_once(SPIDER_PATH.'php_web_spider.php');
require_once(SPIDER_PATH.'simple_html_dom.php');

//$url = 'http://ieeexplore.ieee.org/search/searchresult.jsp?searchWithin%3Dp_Authors%3A.QT.Zhenyu+Wang.QT.%26refinements%3D4274688882%2C4268599920%2C4268757412%2C4274050053%2C4269644358%2C4269643024%2C4262616522&removeRefinement=4274688882&pageNumber=1&resultAction=REFINE';

// 如果不刷新数据，则显示静态数据

$sp = new Spider;
//echo $sp->fetch($url);
//print_r($sp->fetch_results($url));

$papers = array();
$authors = array(
    '王振宇'=>'Zhenyu+Wang',
    //    '孙晓鸥'=>'Xiaoou+Sun',
    '辛柏成'=>'Baicheng+Xin',
    //    '丁琳'=>'Lin+Ding',
    //    '李淞毅'=>'Songyi+Li',
    '蔡砚刚'=>'Yangang+Cai',
    '崔同兵'=>'Tongbing+Cui',
    '文浩丞'=>'Haocheng+Wen',
    '邢培银'=>'Peiyin+Xing',
    //    '陈钦水'=>'Qinshui+Chen',
    //    '丁磊'=>'Lei+Ding',
    '韩冰杰'=>'Bingjie+Han',
    '焦剑波'=>'Jianbo+Jiao',
    //    '李晨霞'=>'Chenxia+Li',
    //    '刘恒进'=>'Hengjin+Liu',
    //    '吕正光'=>'Zhengguang+Lv',
    //    '高龙飞'=>'Longfei+Gao',
    //    '李霖'=>'Lin+Li',
    //    '廖培'=>'Pei+Liao',
    //    '王劲卓'=>'Jinzhuo+Wang',
    //    '王秋斯'=>'Qiusi+Wang',
    //    '杨珺'=>'Jun+Yang',
    //    '张晓鹤'=>'Xiaohe+Zhang',
    //    '镇明敏'=>'Mingmin+Zhen',
    //    '曹洪彬'=>'Hongbin+Cao',
    '高璇'=>'Xuan+Gao',
    '李旭峰'=>'Xufeng+Li',
    '赵龙'=>'Long+Zhao',
    '万杰'=>'Jie+Wan',
    '吕浩'=>'Hao+Lv',
    '唐骋洲'=>'Chengzhou+Tang',
    '王磊'=>'Lei+Wang',
    '杨明辉'=>'Minghui+Yang',
    '杨爽'=>'Shuang+Yang',
    '张雷'=>'Lei+Zhang',
    '刘中欣'=>'Zhongxin+Liu',
    '彭祎'=>'Yi+Peng',
    '汤传新'=>'Chuanxin+Tang',
    '向国庆'=>'Guoqing+Xiang',
    '张艺'=>'Yi+Zhang',
    '杜实现'=>'Shixian+Du',
    '郭梦婷'=>'Mengting+Guo',
    '黄颖'=>'Ying+Huang',
    '魏莹荔'=>'Yingli+Wei',
    '张申'=>'Shen+Zhang',
    '张欣欣'=>'Xinxin+Zhang',
    '张杨'=>'Yang+Zhang',
    '张若楠'=>'Ruonan+Zhang',
    '黄泽湖'=>'Zehu+Huang',
    '罗佳佳'=>'Jiajia+Luo',

    '赵洋'=>'Yang+Zhao',
//    '杨俊'=>'Jun+Yang',  // not in pku
);
// 没有处理分页的问题，只取了一页的数据
set_time_limit(0);
foreach($authors as $key => $author){
    $papers[$key] = $sp->fetch_results('http://ieeexplore.ieee.org/search/searchresult.jsp?searchWithin%3Dp_Authors%3A.QT.'.$author.'.QT.%26refinements%3D4274688882%2C4268599920%2C4268757412%2C4274050053%2C4269644358%2C4269643024%2C4262616522&removeRefinement=4274688882&pageNumber=1&resultAction=REFINE');
//    print_r($papers[$key]);
}
//exit();

/*
 * View layer -----------------------------------------------
 */

require_once('third_party/php_simple_ui/php_simple_ui.php');

$list = new ui_JMListView($papers);
$list->addFilter('Search');

$page = new ui_JMPage('IEEE Xplore Papers',array($list));
$ui = new ui_jQueryMobile($page);

/**
 * Dump ui ------------------------------------------------
 */

echo $ui;