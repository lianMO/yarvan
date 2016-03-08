<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
//注册信息
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
if(!isset($_SESSION['name']))
{
	session_destroy();
	echo "<script>alert('请先登录');location='login.html';</script>";
}
else
{
	//分页已开启
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	else{
		$page =1;
	}
	require_once('MySQL/MySQL.php');
	$mysql = new MySQL();
	$data = $mysql->getDoctorInfo($page);
	$count = $mysql->getDoctorInfoCount();
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
	<?php include("banner.php");?>
				<div class="span9">
						

					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<tr>
								<th>
									姓名
								</th>
								<th>
									性别
								</th>
								<th>
									联系方式
								</th>
								<th>
									专业方向(科室)
								</th>
								<th>
									状态
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=0;
							while(isset($data[$i]))
							{
							switch($data[$i]['status'])
							{
								case '0':$status="<a href='update_info.php?type=doctor_accept&id=".$data[$i]['id']."'>接受</a>  <a href='update_info.php?type=doctor_refuse=".$data[$i]['id']."'>拒绝</a>";break;
								case '1':$status="accept";break;
								case '-1':$status="refuse";break;
							}
							echo "<tr><td>".$data[$i]['name']."</td><td>".$data[$i]['sex']."</td>";
							echo "<td>".$data[$i]['phone']."</td><td>".$data[$i]['item']."</td><td>".$status."</td>";
							$i++;
							}
							//分页功能
							echo "<tr><td colspan='5'></td></tr>";
								if($count<=10)
								{
									echo "<tr><td colspan='5'><a href = '?page=1'>首页</a>&nbsp;<a  href='?page=1'>".$page."</a>&nbsp;<a href = '?page=1' >尾页</a></td></tr>";
								}
								else{
									echo "<tr><td colspan='5'>";
								   echo "<a href = '?page=1'>首页</a>&nbsp;";
								   if($page>1)
								   {
									echo "<a  href='?page=".($page-1)."'>上一页</a>&nbsp;";
								   }
								   if($page>3)
									{
										echo "...";
									}
								   for($i=2;$page-$i<$page;$i--)
								   {
									if($page-$i<=0)
									{
										continue;
									}
									else{
										echo "<a href='?page=".($page-$i)."'>".($page-$i)."</a>";
									}
								   }
								   echo "<a href='?page=".($page)."'>".($page)."</a>";
								   for($i=1;($page+$i-1)*10<$count;$i++)
								   {
									 if($i>2)
									 {
										echo "...";
										break;
									 }
									 else{
										echo "<a href='?page=".($page+$i)."'>".($page+$i)."</a>"; 
									 }
								   }
								   if($page<(int)($count/10))
								   {
									echo "<a  href='?page=".($page+1)."'>下一页</a>&nbsp;";
									}									
								   echo "<a  href='?page=".((int)($count/10)+1)."'>尾页</a>&nbsp;";
								   echo "</td></tr>";
								}
							?>
						</tbody>
					</table>
					
					
				</div>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
	</body>
</html>
<?php
}
?>