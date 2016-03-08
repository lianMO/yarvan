<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
//分中心发布任务管理页面
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
if(!isset($_SESSION['name']))
{
	session_destroy();
	echo "<script>alert('请先登录');location='login.html';</script>";
}
else
{
	require_once('MySQL/MySQL.php');
	$mysql = new MySQL();
	
	if(isset($_GET['id'])&&isset($_GET['date']))
	{
		$id = $_GET['id'];
		$date = $_GET['date'];
	}
	else{ 
		$getshopfid = $mysql->getServList();
		$shopfid = $getshopfid->fetch_array();
		$id=$shopfid['id']; $date=date("m.d");
		} 
	//分页开启,修改只要打开这里还有下边输出的内容
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	else{
		$page =1;
	}
	$shop = $mysql->getServList();
	$data = $mysql->getServAppointInfo($id,$date,$page);
	$item = $mysql->getServItem();
	$count = $mysql->getServAppointInfoCount($id,$date);

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
		<style>
		a:hover {cursor:pointer} 
		</style>
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<script type="text/javascript">  
		$(document).ready(  function(){});  
		function hiden(){  
		$(".center_form").hide();//hide()函数,实现隐藏,括号里还可以带一个时间参数(毫秒)例如hide(2000)以2000毫秒的速度隐藏,还可以带slow,fast  
		}  
		function show(){  
		$(".center_form").show();//显示,参数说明同上  
		}  
		function changeList(page){
		var pa;
		if(page == undefined)
		{
			pa = <?php echo $page;?>;
		}
		else 
		{
			pa=page;
		}
		var shop=document.getElementById("shop_name").value;
		var date=document.getElementById("date").value;
		var url = "center_serv_add.php?id="+shop;
		url = url+"&date="+date+"&page="+pa;
		//alert(url);
		url=encodeURI(url);
		url=decodeURI(url);
		location = url;
		
		}
		function submitCheck()
		{
			var fh = document.getElementById("fh").value;
			var fm = document.getElementById("fm").value;
			var th = document.getElementById("th").value;
			var tm = document.getElementById("tm").value;

			if((fh=="")||(fh==null))
			{
				alert("时间请填写完整!");	
				return false;
			}
			if((fm=="")||(fm==null))
			{
				alert("时间请填写完整!");	
				return false;
			}
			if((th=="")||(th==null))
			{
				alert("时间请填写完整!");	
				return false;
			}
			if((tm=="")||(tm==null))
			{
				alert("时间请填写完整!");	
				return false;
			}
			if((parseInt(fh)>23)||(parseInt(th)>23)||(parseInt(fm)>59)||(parseInt(tm)>59)||(parseInt(fh)>parseInt(th)))
			{
				alert("时间格式有误");
				return false;
			}
			return true;
		}
		</script>  
	</head>
	<?php include("banner.php");?>
				<div class="span9">
					<table>
					<tr>
						<td>分中心<select id="shop_name" name="shop_name" onchange="changeList()">
								  <?php 
								  $centerlist = array();
									$ci = 0;
									while($s=$shop->fetch_array())
									{
										if($s['id']==$id)
										{$centerlist[$ci] = "<option value='".$s['id']."' selected>".$s['shop_name']."</option>";
											echo $centerlist[$ci];
											$ci++;}
										else{$centerlist[$ci] = "<option value='".$s['id']."' >".$s['shop_name']."</option>";
											echo $centerlist[$ci];
											$ci++;}
									}
									?>
								  </select>
						</td>
						<td>
						</td>
						<td>日期<select id="date" name="date" onchange="changeList()">
								<?php
									for($i=0;$i<5;$i++)
									{
										if(date("m.d",time()+3600*24*$i)==$date)
										{
											echo "<option value='".date("m.d",time()+3600*24*$i)."' selected>".date("m.d",time()+3600*24*$i)."</option>";
										}
										else
										{
											echo "<option value='".date("m.d",time()+3600*24*$i)."'>".date("m.d",time()+3600*24*$i)."</option>";
										}
									}
								  ?>
								  </select>
						</td>
					</tr>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>
									已发布的服务
								</th>
								<th>
									时间
								</th>
								<th>
									服务详情
								</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$i=0;
							while(isset($data[$i]))
							{
								echo "<tr><td>".$data[$i]['item_name']."</td><td>".$data[$i]['time']."</td><td>".$data[$i]['item_info']."</td></tr>";
								$i++;
							}
							//分页功能
							echo "<tr><td colspan='3'></td></tr>";
								if($count<=10)
								{
									echo "<tr><td colspan='3'><a onclick=\"changeList(1)\" >首页</a>&nbsp;<a onclick=\"changeList(1)\">".$page."</a>&nbsp;<a onclick=\"changeList(1)\"  >尾页</a></td></tr>";
								}
								else
								{
									echo "<tr><td colspan='3'>";
								   echo "<a onclick=\"changeList(1)\">首页</a>&nbsp;";
								   if($page>1)
								   {
									echo "<a   onclick=\"changeList(".($page-1).")\">上一页</a>&nbsp;";
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
										echo "<a onclick=\"changeList(".($page-$i).")\">".($page-$i)."</a>";
									}
								   }
								   echo "<a onclick=\"changeList(".($page).")\">".($page)."</a>";
								   for($i=1;($page+$i-1)*10<$count;$i++)
								   {
									 if($i>2)
									 {
										echo "...";
										break;
									 }
									 else{
										echo "<a onclick=\"changeList(".($page+$i).")\">".($page+$i)."</a>"; 
									 }
								   }
								   if($page<(int)($count/10))
								   {
									echo "<a  onclick=\"changeList(".($page+1).")\">下一页</a>&nbsp;";
									}									
								   echo "<a  onclick=\"changeList(".((int)($count/10.0)+1).")\">尾页</a>&nbsp;";
								   echo "</td></tr>";
								}
							?>
						</tbody>
					</table>
					<div class='newitem' style='margin-right:50%;'>
					<ul class="pager">
						<li class="next">
							<button onclick="show()">发布新服务</button>
						</li>
					</ul>
					</div>
					<div class='center_form'  style='margin-left:100px;display:none;'>
                    <ul class="pager">
						<li class="next" >
							<form action="post_insert.php" method="post" onsubmit="return submitCheck()">
							<table class="table table-bordered table-striped" style="width:500px;">
							<th>发布新项目</th>
							<td style='text-align:right'><input type='button' onclick="hiden()" value='x' /">&nbsp;</td>
							<tr>
								<td>
								<font size="4px">分中心</font>
								</td>
								<td>
								<select name="shop_id" >
								<?php 
									$fi = 0;
									while(isset($centerlist[$fi]))
									{
										echo $centerlist[$fi];
										$fi++;
									}
								?>
								</select>
								</td>
							</tr>
							<tr>
								<td>
								<font size="4px">日期</font>
								</td>
								<td>
								<select  name="date">
								<?php
									//之后5天
									for($i=0;$i<5;$i++)
									{
										if(date("m.d",time()+3600*24*$i)==$date)
										{
											echo "<option value='".date("m.d",time()+3600*24*$i)."' selected>".date("m.d",time()+3600*24*$i)."</option>";
										}
										else
										{
											echo "<option value='".date("m.d",time()+3600*24*$i)."'>".date("m.d",time()+3600*24*$i)."</option>";
										}
									}
								  ?>
								  </select>
								</td>
							</tr>
							<tr>
								<td >
								<font size="4px">服务项目</font>
								
								</td>
								<td><select name="serv_item" id="serv_item" >
								  <?php
								  while($it=$item->fetch_array())
								  {
									echo "<option value='".$it['id']."'>".$it['item_name']."</option>";
								  }
								  ?>
								  </select>
								</td>
							</tr>
							<tr>
								<td ><font size="4px">时间</font></td>
								<td>
								<input type='text' name='from_hour' style="width:20px" id='fh' />:
								<input type='text' name='from_min'  style="width:20px" id='fm' />~
								<input type='text' name='to_hour'  style="width:20px"  id='th' />:
								<input type='text' name='to_min'  style="width:20px" id='tm' /> 
								</td>
							</tr>

							<tr>
								<td colspan='3' style='text-align:center'><button type='submit' name='submit' value='serv_appoint_add'>保存</button></td>
							</tr>

							</form>
							</table>
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