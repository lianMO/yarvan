<?php
header("Content-Type:text/html;charset=UTF-8");

session_start();
/* Report all errors except E_NOTICE */
error_reporting(E_ALL^E_NOTICE);
if(!isset($_SESSION['name']))
{
	session_destroy();
	echo "<script>alert('非法操作');location='login.html';</script>";
}
else
{
	if(isset($_POST['submit']))
	{
			$msg = post_insert($_POST);
			
	}
	else{
	echo "<script>alert('非法路径');location='javascript:history.back()';</script>";
	}
	

}
function post_insert($post)
{
	switch($post['submit'])
	{
		case 'shop_add':
				require_once('MySQL/Service_shop.php');
				$shop = new Service_shop();
				$re=$shop->addServiceShop($post['shop_name'],$post['address'],$post['contact_way']);break;
		case 'serv_appoint_add':
				require_once('MySQL/Service_appointment_info.php');
				$sp = new Service_appointment_info();
				$time=$post['from_hour'].":".$post['from_min']."~".$post['to_hour'].":".$post['to_min'];
				echo $time;
				$re=$sp->addServiceAppointInfo($post['shop_id'],$post['serv_item'],$time,$post['date']);
				break;
		case 'item_add': 
				require_once('MySQL/Service_item.php');
				$shop = new Service_item();
				$re=$shop->addServiceItem($post['serv_item'],$post['serv_item_info']);break;
		case 'parttime_add':
				require_once('MySQL/Parttime_appointment_info.php');
				$shop = new Parttime_appointment_info();
				$time=$post['from_hour'].":".$post['from_min']."~".$post['to_hour'].":".$post['to_min'];
				$re=$shop->addParttimeInfo($post['shop_id'],$post['serv_item'],$time,$post['count'],$post['date'],$post['is_morning']);
				break;
		case 'hos_item_add':
				require_once('MySQL/Department.php');
				$shop = new Department();
				$re=$shop->addDepartment($post['hos_id'],$post['hos_item']);
				break;
		case 'hos_add': 
				require_once('MySQL/Hospital.php');
				$shop = new Hospital();
				$re=$shop->addHospital($post['hos_name'],$post['address']);
				break;
		case 'staff_add':
				require_once('MySQL/Staff.php');
				$shop = new Staff();
				$re=$shop->addStaff($post['name'],$post['password'],$_SESSION['type']);
				break;
		case 'staff_updatepw': 
				require_once('MySQL/Staff.php');
				$staff = new Staff();
				$re=$staff->updateStaffPw($_SESSION['name'],$post['old_pw'],$post['new_pw'],$_SESSION['type']);
				break; 
	}
	return $re;
}
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
						<h1><?php echo $msg;?></h1>    
						<a href="javascript:history.back()">1秒后系统会自动跳转，也可点击本处直接跳</a> </div></div></div>   
					</table>
					
					
				</div>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
		<script>  
		function jumpurl(){  
			location='javascript:history.back()';  
			}  
		setTimeout('jumpurl()',1000);  
		</script>   
	</body>
</html>