<?php
header("Content-Type:text/html;charset=UTF-8");
session_start();
//兼职发布页面
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
	//兼职发布的要素 中心--id 日期---date 时间段---is_morning  页数-----page
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		
	}
	else{
	
		$getshopfid = $mysql->getServList();
		$shopfid = $getshopfid->fetch_array();
		$id=$shopfid['id'];
	}
	
	if(isset($_GET['date']))
	{
		$date = $_GET['date'];
	}
	else{
		$date=date("m.d");
	}
	
	if(isset($_GET['period']))
	{
		$period = $_GET['period'];
	}
	else{$period=1;}
	
	//分页开启
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	else{ $page=1;} 
	
	$shop = $mysql->getServList();
	$data = $mysql->getParttimeServ($id,$date,$period,$page);
	$item = $mysql->getServItem($id);
	$count = $mysql->getParttimeServCount($id,$date,$period);

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
	<style>
	#period{height:30px;width:120px;border:2px solid;border-radius:8px;}
	#period:hover{cursor:pointer;}
	#morning{height:30px;width:60px;float:left;border:0px;border-radius:5px;color:#FFFFFF;text-align:center;line-height:30px;font-weight:bold;
	}
	#moring:hover{cursor:pointer;}
	#afternoon{height:30px;width:60px;float:left;border:0px;border-radius:5px;text-align:center;line-height:30px;font-weight:bold;}
	#afternoon:hover{cursor:pointer;}
	a:hover {cursor:pointer} 
	</style>
	</head>
	<?php include("banner.php");?>
				<div class="span9">
					<table>
					<tr>
						<td width='55px'>分中心<select id="shop_name" name="shop_name" onchange="changeList()">
								  <?php 
									//动态输出中心
									$centerlist = array();
									$ci = 0;
									while($s=$shop->fetch_array())
									{
										if($s['id']==$id)
										{
											$centerlist[$ci] = "<option value='".$s['id']."' selected>".$s['shop_name']."</option>";
											echo $centerlist[$ci];
											$ci++;
										}
										else{
											$centerlist[$ci] = "<option value='".$s['id']."' >".$s['shop_name']."</option>";
											echo $centerlist[$ci];
											$ci++;
											}
									}
									?>
								  </select>
						</td>
						<td width='30px'>
						</td>
						<td width='40px'>日期<select id="date" name="date" onchange="changeList()">
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
						<td width='30px'>
						</td>
						<td width='55px'>时间段</td>
						<td>
							<div id='period'>
							<div id='morning'  onclick="changeList(1,<?php echo $page;?>)">上午</div>
							<div id='afternoon' onclick="changeList(0,<?php echo $page;?>)">下午</div>
							
							</div>
						</td>
					</tr>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>
									已发布的兼职
								</th>
								<th>
									时间
								</th>
								<th>
									需要人数
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							while(isset($data[$i]))
							{	
								echo "<tr><td>".$data[$i]['item_name']."</td><td>".$data[$i]['time']."</td><td>".$data[$i]['count']."</td></tr>";
								$i++;
							}
							//分页开启
							echo "<tr><td colspan='3'></td></tr>";
								if($count<=10)
								{
									echo "<tr><td colspan='3'><a onclick=\"changeList(".$period.",1)\" >首页</a>&nbsp;<a onclick=\"changeList(".$period.",1)\">".$page."</a>&nbsp;<a onclick=\"changeList(".$period.",1)\" >尾页</a></td></tr>";
								}
								else
								{
									echo "<tr><td colspan='3'>";
								   echo "<a onclick=\"changeList(".$period.",1)\">首页</a>&nbsp;";
								   if($page>1)
								   {
									echo "<a onclick=\"changeList(".$period.",".($page-1).")\">上一页</a>&nbsp;";
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
										echo "<a onclick=\"changeList(".$period.",".($page-$i).")\">".($page-$i)."</a>";
									}
								   }
								   echo "<a onclick=\"changeList(".$period.",".($page).")\">".($page)."</a>";
								   for($i=1;($page+$i-1)*10<$count;$i++)
								   {
									 if($i>2)
									 {
										echo "...";
										break;
									 }
									 else{
										echo "<a onclick=\"changeList(".$period.",".($page+$i).")\">".($page+$i)."</a>"; 
									 }
								   }
								   if($page<(int)($count/10))
								   {
									echo "<a  onclick=\"changeList(".$period.",".($page+1).")\">下一页</a>&nbsp;";
									}									
								   echo "<a  onclick=\"changeList(".$period.",".((int)($count/10.0)+1).")\">尾页</a>&nbsp;";
								   echo "</td></tr>";
								}
							?>
							</tr>
						</tbody>
					</table>
					<div class='newitem' style='margin-right:50%;'>
					<ul class="pager">
						<li class="next">
							<button onclick="show()">发布新兼职</button>
						</li>
					</ul>
					</div>
					<div class='center_form'  style='margin-left:100px;display:none;'>
                    <ul class="pager">
						<li class="next" >
							<form action="post_insert.php" method="post" onsubmit="return submitCheck()">
							<table class="table table-bordered table-striped" style="width:500px;">
							<th>发布新兼职</th>
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
								<td><select name="serv_item">
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
								<input type='text' name='from_hour' style="width:20px" id='fh'>:
								<input type='text' name='from_min'  style="width:20px" id='fm'>~
								<input type='text' name='to_hour'  style="width:20px" id='th'>:
								<input type='text' name='to_min'  style="width:20px" id='tm'> 
								</td>
							</tr>
							<tr>
								<td><font size="4px">时间段</font></td>
								<td><input type='radio' name='is_morning' value='1' <?php if($period=='1'){echo "checked";}?> />上午
									<input type='radio' name='is_morning' value='0' <?php if($period=='0'){echo "checked";}?>/>下午
								</td>
							</tr>
							<tr>
								<td><font size="4px">需要人数</font></td>
								<td><input type='text' name='count' id='count' style="width:20px"></td>
							</tr>
							<tr>
								<td colspan='3' style='text-align:center'><button type='submit' name='submit' value='parttime_add'>保存</button></td>
							</tr>

							</form>
						</li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">  
		//js浮现
		$(document).ready(  function(){});  
		function hiden(){  
		$(".center_form").hide();//hide()函数,实现隐藏,括号里还可以带一个时间参数(毫秒)例如hide(2000)以2000毫秒的速度隐藏,还可以带slow,fast  
		}  
		function show(){  
		$(".center_form").show();//显示,参数说明同上  
		}  
		
		//改变搜素条件，以新的url做请求
		function changeList(period,page){
		var p;
		//缺省参数处理
		if(period == undefined)
		{
			p=1;
		}
		else{
			p=period;
		}
		var pa;
		if(page == undefined)
		{
			pa = <?php echo $page;?>;
		}
		else 
		{
			pa=page;
		}
		//获取条件分中心和日期
		var shop=document.getElementById("shop_name").value;
		var date=document.getElementById("date").value;
		
		//构造新的url
		var url = "doctor_add.php?id="+shop;
		url = url+"&date="+date+"&period="+p+"&page="+pa;
		
		url=encodeURI(url);
		url=decodeURI(url);
		location = url;
		}
		function submitCheck()
		{
			var count = document.getElementById("count").value;
			var fh = document.getElementById("fh").value;
			var fm = document.getElementById("fm").value;
			var th = document.getElementById("th").value;
			var tm = document.getElementById("tm").value;
			if((count=="")||(count==null))
			{
				alert("需要人数请填写完整!");	
				return false;
			}
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
		<script> 
		//js动态时间段效果
		window.onload=function(){
			var pe = <?php echo $period;?>;

			var obj1=document.getElementById("morning");
			var obj2=document.getElementById("afternoon");
			if(pe==1)
			{
				obj1.style.backgroundColor="#5b9bd5";
				obj1.style.color="#FFFFFF";
				obj2.style.backgroundColor="#FFFFFF";
				obj2.style.color="#000000";
			}
			else if(pe==0)
			{
				obj2.style.backgroundColor="#5b9bd5";
				obj2.style.color="#FFFFFF";
				obj1.style.backgroundColor="#FFFFFF";
				obj1.style.color="#000000";
			}
			}
		</script> 
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
	</body>
</html>
<?php
}
?>