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
else{
if(isset($_GET['type'])&&isset($_GET['id']))
{
	$type = $_GET['type'];
	$id = $_GET['id'];
	$msg=updateinfo($type,$id);
		
	
}
}
function updateinfo($type,$id)
{
	switch($type)
	{
		case 'doctor_accept': require_once('MySQL/Doctor.php');$doctor = new Doctor();$re=$doctor->updateDoctorStatus($id,1);break;
		case 'doctor_refuse': require_once('MySQL/Doctor.php');$doctor = new Doctor();$re=$doctor->updateDoctorStatus($id,-1);break;
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