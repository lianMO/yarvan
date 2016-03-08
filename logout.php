<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
session_destroy();
echo "<script>alert('退出成功');location = 'login.html';</script>";
?>