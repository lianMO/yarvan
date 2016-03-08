<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
//医院管理页面
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
	$data = $mysql->getHosList($page);
	$count = $mysql->getHosListCount();

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
			var hos_name = document.getElementById("hos_name").value;
			if((hos_name!="")&&(hos_name!=null))
			{
				checked = true;
			}
			else{
				alert("医院名不能为空！");
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
									医院列表
								</th>
								<th>
									地址
								</th>
								
								<th>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while($re=$data->fetch_array())
							{
								echo "<tr><td><a href='hos_info_item.php?hos_id=".$re['id']."'>".$re['name']."</a></td><td>".$re['address']."</td>";
								echo "<td><a href='deleteinfo.php?type=hos&id=".$re['id']."' class='view-link'>删除</a></td></tr>";
							}
							//分页功能
							echo "<tr><td colspan='3'></td></tr>";
								if($count<=10)
								{
									echo "<tr><td colspan='3'><a href = '?page=1'>首页</a>&nbsp;<a  href='?page=".$page."'>".$page."</a>&nbsp;<a href = '?page=1' >尾页</a></td></tr>";
								}
								else{
									echo "<tr><td colspan='3'>";
								   echo "<a href = 'hos_info_mgr.php?page=1'>首页</a>&nbsp;";
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
							<button onclick="show()">新增医院</button>
						</li>
					</ul>
					</div>
					<div class='center_form'  style='margin-left:100px;display:none;'>
                    <ul class="pager">
						<li class="next" >
							<form action="post_insert.php" method="post" onsubmit="return submitCheck()">
							<table class="table table-bordered table-striped" style="width:500px;">
							<th>新增医院</th>
							<td style='text-align:right'><input type='button' onclick="hiden()" value='x' /">&nbsp;</td>
							<tr>
								<td ><font size="4px">医院名称</font></td>
								<td><input type='text' name='hos_name' id='hos_name' /></td>
							</tr>
							<tr>
								<td ><font size="4px">医院地址</font></td>
								<td><input type='text' name='address'  /></td>
							</tr>
							
							<tr>
								<td colspan='3' style='text-align:center'><button type='submit' name='submit' value='hos_add'>保存</button></td>
							</tr>
							</table>
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