<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
if(isset($_POST['submit']))
{
	
	if($_POST['submit']=='login')
	{
		
		require_once("MySQL/MySQL.php");
		$mysql = new MySQL();
		$re=$mysql->checkLogin($_POST['user'],$_POST['password']);
		if($re)
		{
			
			$_SESSION['name'] = $re['staffName'];
			$_SESSION['staff_id'] = $re['id'];
			$_SESSION['type'] = $re['type'];
			echo "<script>location = 'hos_info_mgr.php';</script>";
		}
		else
		{
			echo "<script>alert('用户名或密码错误');location='javascript:history.back()';</script>";
		}
	}
	else{
		echo "<script>alert('非法操作');location = 'login.html';</script>";
	}
}
else
{
	echo "<script>alert('非法操作');location = 'login.html';</script>";
}
?>