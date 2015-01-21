<?php
/**
 * html dom
 * @example 
 *  include('php_simple_ui.php');
 *  $ui = new ui_Dom('html');
 *  $body = $ui->append('body');
 *  $head = $ui->prepend('head');
 *  $head->html('<title>php_simple_ui</title>');
 *  $body->bgcolor = 'yellow';
 *  $body->append('input')->attr('type','button')->val('hello world'); // 链式
 *  echo $ui;
 */

echo $_POST['student_no'].$_POST['lib_psw'];
