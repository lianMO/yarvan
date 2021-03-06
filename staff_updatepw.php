<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
if(!isset($_SESSION['name']))
{
	session_destroy();
	echo "<script>alert('请先登录');location='login.html';</script>";
}

if($_SESSION['type']==1)
{
require_once('MySQL/MySQL.php');
$mysql = new MySQL();
$data = $mysql->getStaffList($_SESSION['name'],$_SESSION['type']);
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]--><!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]--><!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]--><!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]--><!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="en"><!--<![endif]--> 
	<head>
		<meta charset="utf-8">
		<title>后台管理系统</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="css/site.css" rel="stylesheet">
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">后台管理系统</a>
						<div class="nav-collapse">
							<ul class="nav">	
							</ul>
							<ul class="nav pull-right">
								<li>
									<a href="hos_info_mgr.php">返回上级页面</a>
								</li>
								<li>
									<a href="staff.php?<?php echo $_SESSION['staff_id'];?>"><?php echo $_SESSION['name']; ?></a>
								</li>
								<li>
									<a href="logout.php">Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span3">
					<div class="well" style="padding: 8px 0;">
						<ul class="nav nav-list">
							<li class="nav-header">
								管理员
							</li>
							<li>
								<a href="staff.php"><i class="icon-folder-open"></i> 管理员列表</a>
							</li>
							<li>
								<a href="staff_updatepw.php"><i class="icon-user"></i> 修改密码</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="span9">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>
									注意：密码修改后不能撤销和查询，请确认你记住新密码无误
								</th>
								<th>
								</th>
							</tr>
						</thead>
					</table>
					<div class='center_form'  style='margin-left:100px;'>
                    <ul class="pager">
						<li class="next" >
							<form action="post_insert.php" method="post" onsubmit="return check()">
							<table class="table table-bordered table-striped" style="width:500px;">
							<th>修改密码</th>
							<tr>
								<td ><font size="4px">旧密码</font></td>
								<td><input type='password' name='old_pw' id='old_pw' /></td>				
							</tr>
							<tr>
								<td ><font size="4px">新密码</font></td>
								<td><input type='password' name='new_pw' id='new_pw' /></td>				
							</tr>
							<tr>
								<td ><font size="4px">确认密码</font></td>
								<td><input type='password' name='rpw' id='rpw' /></td>				
							</tr>
							<tr>
								<td colspan='3' style='text-align:center'><button type='submit' name='submit' value='staff_updatepw'>保存</button></td>
							</tr>

							</form>
						</li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
		<script>
			function check()
			{
				var pw = document.getElementById('old_pw');
				var npw = document.getElementById('new_pw');
				var rpw = document.getElementById('rpw');
				
				if(pw.value!=''&&npw.value!=''&&rpw.value!='')
				{
					
					if(npw.value!=rpw.value)
					{
						alert('重复密码错误');
						return false;
					}
					else{
						return true;
					}
				}
				else{
					alert('请填写完整');
						return false;
				}
			}
		</script>
	</body>
</html>
<?php
}
else
{
	echo "<script>location='hos_info_mgr.php';</script>";
}
?>