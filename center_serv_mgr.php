<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
//中心项目页面
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
	$data = $mysql->getServItem($page);
	$count = $mysql->getServItemCount();
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
		<script type="text/javascript">  
		$(document).ready(  function(){});  
		function hiden(){  
		$(".center_form").hide();//hide()函数,实现隐藏,括号里还可以带一个时间参数(毫秒)例如hide(2000)以2000毫秒的速度隐藏,还可以带slow,fast  
		}  
		function show(){  
		$(".center_form").show();//显示,参数说明同上  
		} 
		function submitCheck()
		{
			var checked = false;
			var serv_item = document.getElementById("serv_item").value;
			if((serv_item!="")&&(serv_item!=null))
			{
				checked = true;
			}
			else{
				alert("项目名不能为空！");
			}
			return checked;
		}
</script>  
	</head>
	<?php include("banner.php");?>
				<div class="span9">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>
									服务项目管理
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while($re=$data->fetch_array())
							{
								echo "<tr><td colspan='4'>".$re['item_name']."</td>";
								echo "<td><a href='deleteinfo.php?type=item&id=".$re['id']."' class='view-link'>删除</a></td></tr>";
							}
							//分页功能
							echo "<tr><td colspan='5'></td></tr>";
								if($count<=10)
								{
									echo "<tr><td colspan='5'><a href = 'center_serv_mgr.php'>首页</a>&nbsp;<a  href='?page=".$page."'>".$page."</a>&nbsp;<a href = 'center_serv_mgr.php' >尾页</a></td></tr>";
								}
								else{
									echo "<tr><td>";
								   echo "<a href = 'center_serv_mgr.php'>首页</a>&nbsp;";
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
					<div class='newitem' style='margin-right:50%;'>
					<ul class="pager">
						<li class="next">
							<button onclick="show()">新增项目</button>
						</li>
					</ul>
					</div>
					<div class='center_form'  style='margin-left:100px;display:none;'>
                    <ul class="pager">
						<li class="next" >
							<form action="post_insert.php" method="post" onsubmit="return submitCheck()">
							<table class="table table-bordered table-striped" style="width:500px;">
							<th>新增项目</th>
							<td style='text-align:right'><input type='button' onclick="hiden()" value='x' /">&nbsp;</td>
							<tr>
								<td ><font size="4px">项目名称</font></td>
								<td><input type='text' name='serv_item' id="serv_item" /></td>
							</tr>
							<tr>
								<td ><font size="4px">项目详情</font></td>
								<td><input type='text' name='serv_item_info'  /></td>
							</tr>
							<tr>
								<td colspan='3' style='text-align:center'><button type='submit' name='submit' value='item_add'>保存</button></td>
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
	</body>
</html>
<?php
}
?>