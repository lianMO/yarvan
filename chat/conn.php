<?php 
@header('content-type:text/html;charset=urf-8');
mysql_connect('localhost','root','76a4aa3224') or die('数据库连接失败：'.mysql_error());
mysql_select_db('yarvan') or die(mysql_error());
mysql_query('set names utf8');
?>