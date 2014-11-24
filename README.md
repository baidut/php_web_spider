php_web_spider
==============

php网络蜘蛛，信息收集工具

一个php实现的、基于cURL和simple html dom的轻量级网络爬虫

A web spider, using php, based on cURL & simple html dom.


### 配置

		<?php
		// 简单配置 cookie文件目录和第三方网页分析库simple_html_dom的位置
		define("COOKIE_FILE",		"./cookie.txt");
		define("PARSER_FILE",		'./simple_html_dom.php');
		// 添加php_web_spider，创建一个实例
		require_once('./php_web_spider.php');
		$sp = new spider;

### 应用场合一 抓取检索结果

		// eg1 可以直接get百度检索结果
		$url = 'http://www.baidu.com/s?ie=UTF-8&wd=%E4%BD%A0%E5%A5%BD';
		echo $sp-> fetch($url);
		// eg2 可以直接get豆瓣图书检索结果
		$url = 'http://book.douban.com/subject_search?search_text=php&cat=1001';
		echo $sp-> fetch($url);

### 应用场合二 登陆，抓取信息

		// eg 登陆我校图书馆获取图书信息
		$username = '你的用户名';
		$password = '你的密码';
		$url = 'http://www.lib.dlut.edu.cn/';
		$sp-> login($url,$username,$password) or die("Fail in login.");
		$sp-> fetch('http://opac.lib.dlut.edu.cn/reader/book_lst.php');
		// 提取当前借阅信息到关联数组
		$books = $sp-> fetch_table('[class=table_line]');
		header("Content-type: text/html; charset=utf-8");
		print_r ($books);
		// 将信息转为字符串输出
		$info = "";
		foreach($books as $key => $book){
			// 如果图书超期加上超期前缀
			$deadline = date_create($book['应还日期']); 
			$now= date_create();
			if($deadline>$now){
				$info .= "(超期)";
			}
			$book_name = $book['题名/责任者'];
			// 追加信息
			$info .= $book_name."\n";
		}
		echo  "图书信息：\n".$info;
		?>
