<?php
/**
 * return the library information
 * $ret['container'] = 'list'; // specify the ui container to display the data
 * $ret['content'] = $data;
 */

//echo 'hello world';
//echo $_POST['student_no'].$_POST['lib_psw'];


require_once('third_party/php_simple_ui/php_simple_ui.php');

$books = array('当前借阅'=> array('php','js','jQuery','html'));

$list = new ui_JMListView($books);

echo $list;