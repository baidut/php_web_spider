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
require_once('third_party/php_simple_ui/php_simple_ui.php');
// $ui = new php_simple_ui(UI_JQueryMobile);
// $echo $ui;

// 一级一级构建方式
$list = new ui_JMListView($news);
$list->addFilter('搜索活动');
$page = new ui_JMPage('南燕新闻',$list);
// $page->content->appendText();
$page->header->appendText('<a href="#" data-role="button" data-icon="home">首页</a>');
$page->header->appendText('<a href="#" data-role="button" data-icon="grid" class="ui-btn-right">选项</a>');
$ui = new ui_jQueryMobile($page);

echo $ui;

// print_r($data);
// 前端采用jQueryMobile 参考之前的成果

// 杂记
// 判断一个PHP数组是关联数组还是数字数组  return array_keys($arr) !== range(0, count($arr) - 1);