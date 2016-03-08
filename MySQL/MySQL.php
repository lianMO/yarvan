<?php
define('EACHPAGE',10);//每页显示多少条记录
class MySQL {
	
	private function getConn(){
		
		//数据库配置
		$host 	= 'localhost';
		$user = 'root';
		$pw = '76a4aa3224';
		$dbName = 'yarvan';
		
		try {
			$conn = new mysqli($host, $user, $pw, $dbName);
			// $conn = new mysqli($host, $ak, $sk, $dbName);
			if(!$conn) {
				die("Connect Server Failed、: " . mysqli_error($conn));
			}
			$conn->set_charset("utf8");//设置编码utf8
			return $conn;
		}
		catch (mysqli_sql_exception $e) {
			throw $e->getMessage();
		}
	}
	/********防注入**************/
	//请在每次用数据库操作前调用一下来处理参数防止注入
	public function inject_check($sql_str) {  
		return preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);    // 进行过滤  
	}  
	/********************/
	/******管理员账户管理*********/
	public function checkLogin($name,$pw) //登录验证
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($name))&&(!$mysql->inject_check($pw)))
		{
		$pw = md5($pw);
		
		$sql = "SELECT id,staffName,type FROM staff where staffName = '$name' and pwd = '$pw' and method!='delete'";
		try {
			
			$conn = $mysql->getConn();		
			$query = $conn->query($sql) or die("验证失败");
			$result = $query->fetch_array();
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		
		if(isset($result['staffName']))
		{
			return $result;
		}
		else{
			return false;
		}
		}
		else{	return false;}
	}
	public function isExistName($name)//检查用户名是否已被使用
	{
		
		$sql = "SELECT count(id) AS count FROM staff where staffName = '$name' and method!='delete' ";
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();		
			$query = $conn->query($sql) or die("验证失败");
			$result = $query->fetch_array();
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		if($result['count']>=1)
		{
			return true;
		}
		else{
			return false;
		}
	}
	
	
	/////超级管理员功能
	public function getStaffList($name,$type)
	{
		if($type=='1')
		{	
				$mysql = new MySQL();
				$sql = "SELECT id,staffName from staff where staffName!='$name' and method!='delete'";
				try {
				$conn = $mysql->getConn();		
				$query = $conn->query($sql) or die("读取失败");
				$conn->close();
				}
				catch (Exception $e) {
					echo $e->getMessage();
				}
				return $query;
		}
		
		else{
		  echo "<script>alert('你没有权限');location='login.htm';</script>";
		}
	}
	public function addStaff($type,$name,$pw) //新增管理员
	{
		if($type=='1')
		{
			$mysql = new MySQL();
			$re = $mysql->isExistName($name);
			if($re)
			{
				return "该用户名已被使用";
			}
			else{
				$pw = md5($pw);
				$sql = "INSERT INTO staff (staffName,pwd) VALUES ('$name','$pw')";
				try {
				$conn = $mysql->getConn();		
				$query = $conn->query($sql) or die("注册失败");
				$conn->close();
				}
				catch (Exception $e) {
					echo $e->getMessage();
				}
				return $query;
				}
		}
		else{
		 return "你没有权限";
		}
	}
	
	public function deleteStaff($type,$id) //删除管理员
	{
		if($type=='1')
		{
			$mysql = new MySQL();
			
				
				$sql = "DELETE FROM staff where id='$id'";
				try {
				$conn = $mysql->getConn();		
				$query = $conn->query($sql) or die("注册失败");
				$conn->close();
				}
				catch (Exception $e) {
					echo $e->getMessage();
				}
				return $query;
				
		}
		else{
			return "你没有权限";
		}
	}
	public function updatePw($name,$pw,$npw,$type) //更改密码,只有超级管理员才有权限
	{
		if($type=='0')
		{
			return false;
		}
		$mysql = new MySQL();
		if((!$mysql->inject_check($name))&&(!$mysql->inject_check($pw))&&(!$mysql->inject_check($npw)))
		{
		$result =  $mysql->checkLogin($name,$pw);
		if($result)
		{
			return true;
		}
		else{
			return false;
		}
		}
		else{
			return false;
		}
	}
	/************************/
	
	//医院挂号
	/**********医院列表*********/
	public function setHosList($name,$address)//设置医院
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($name))&&(!$mysql->inject_check($address)))
		{
		$sql = "INSERT INTO hospital (name,address) VALUES (?,?)";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("ss",$name,$address);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	public function getHosList($page)//获取医院
	{
		
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM hospital where method!='delete' limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM hospital";
		}
		
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();		
			$result = $conn->query($sql);
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	public function getHosListCount()//获取医院数目
	{
		$sql = "SELECT count(id) AS count FROM hospital where method!='delete'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询医院数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	
	public function getHosListById($hos_id)//通过id找医院
	{
		$mysql = new MySQL();
		if(!$mysql->inject_check($hos_id))
		{
		$sql = "SELECT * FROM hospital WHERE id = '$hos_id' and method!='delete'";
		try {
			
			$conn = $mysql->getConn();		
			$query = $conn->query($sql);
			$result = $query->fetch_array();
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	
	public function delHosList($hos_id)//删除医院
	{
		if(!$mysql->inject_check($hos_id))
		{
		$mysql = new MySQL();
		$sql = "DELETE FROM hospital where id = ?";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("i",$hos_id);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false; }
	}
	/******************/
	
	/*******科室列表**********/
	public function setHosItem($hos_id,$name)//设置医院的科室
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($hos_id))&&(!$mysql->inject_check($name)))
		{
		$sql = "INSERT INTO department (hospital_id,name) VALUES (?,?)";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("ss",$hos_id,$name);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	public function getHosItem($hos_id,$page)//获取对应医院的科室
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM department where hospital_id = '$hos_id' and method!='delete' limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM department where hospital_id = '$hos_id' and method!='delete'";
		}
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql);
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	public function getHosItemCount($hos_id)//获取医院对应科室数目
	{
		$mysql = new MySQL();
		if(!$mysql->inject_check($hos_id))
		{
		$sql = "SELECT count(id) AS count FROM department where hospital_id = $hos_id";
		try{
			
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询医院科室数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
		}
		else{ return false;}
	}

	public function getHosItemByID($hos_id,$item_id)//通过id找医院科室
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($hos_id))&&(!$mysql->inject_check($item_id)))
		{
		$sql = "SELECT * FROM department where hospital_id = '$hos_id' and id = '$item_id' and method!='delete'";
		try {
			
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
			$result = $query->fetch_array();
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result['name'];
		}
		else{ return false;}
	}
	
	public function delHosItem($item_id)//删除科室
	{
		$mysql = new MySQL();
		if(!$mysql->inject_check($item_id))
		{
		$sql = "DELETE FROM department where id = ?";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("i",$item_id);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	/*******************************/
	
	/**********查看医院预约*********/
	public function getHosAppoint($page)//获取医院预约
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM registration ORDER BY apply_time DESC limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM registration ORDER BY apply_time DESC";
		}
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo = array();
		while($re=$query->fetch_array())
		{
			
			$getinfo[$i] = $mysql->getHostAppointInfo($re['user_id'],$re['hosipital_id']);
			$getinfo[$i]['id'] = $re['id'];
			$getinfo[$i]['item'] = $re['department'];
			$getinfo[$i]['date'] = $re['apply_time'];
			$getinfo[$i]['time'] = $re['registration_time'];
			$getinfo[$i]['remark'] = $re['remark'];
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getHosAppointCount()//获取医院预约数
	{
		$sql = "SELECT count(id) AS count FROM registration";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询医院预约数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	
	public function getHostAppointinfo($user_id,$hospital_id)//获取医院预约信息，返回数组
	{
		$mysql = new MySQL();
		$info = array();
		$info = $mysql->getUserInfoByID($user_id);
		$hos_info = $mysql->getHosListById($hospital_id);
		$info['hospital'] = $hos_info['name'];
		
		return $info;
	}
	
	public function getUserInfoByID($user_id)//通过id获取用户信息
	{
		$sql = "SELECT * FROM user WHERE id = '$user_id'";
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);		
			$result = $query->fetch_array();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		
		$conn->close();
		return $result;
	}
	
	public function getTimeByID($time_id)//通过时间获取时间段
	{
		$sql = "SELECT * FROM time_period WHERE id = '$time_id'";
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);		
			$result = $query->fetch_array();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		
		$conn->close();
		return $result['time_period'];
	}
	public function delHostAppoint($id)//删除医院预约
	{
		$mysql = new MySQL();
		if($mysql->inject_check($id))
		{
		$sql = "DELETE FROM hospital_appointment where id = ?";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("i",$item_id);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	public function updateHosAppStatus($id,$status)//更新预约处理状态
	{
		$result = false;
		$mysql = new MySQL();
		if((!$mysql->inject_check($id))&&(!$mysql->inject_check($status)))
		{
		$sql = "UPDATE hospital_appointment SET status='$status' where id='$id'";
		try{
			
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
			if($conn->affected_rows==1)
			{
			$result = true;
			}
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	/******************************/
	
	//分中心管理
	/************分中心列表********/
	public function setServList($name,$address,$contact_way)//设置中心
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($name))&&(!$mysql->inject_check($address))&&(!$mysql->inject_check($address)))
		{
		$sql = "INSERT INTO service_shop (shop_name,address,contact_way) VALUES (?,?,?)";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("sss",$name,$address,$contact_way);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	public function getServList()//获取中心信息
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM service_shop where method!='delete' limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM service_shop where method!='delete'";
		}
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql);		
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	public function getServListCount()//获取中心数目
	{
		$sql = "SELECT count(id) AS count FROM service_shop where method!='delete'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询中心数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	public function delServList($shop_id)//删除中心
	{
		$mysql = new MySQL();
		if(!$mysql->inject_check($shop_id))
		{
		$sql = "DELETE FROM service_shop where id = ?";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("i",$shop_id);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	/***********************************/
	
	/************服务项目管理*********/
	public function setServItem($name,$info)//设置中心服务项目
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($name))&&(!$mysql->inject_check($info)))
		{
		$sql = "INSERT INTO service_item (item_name,item_info) VALUES (?,?)";
		try{
			
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("ss",$name,$info);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	public function getServItem()//获取中心服务项目
	{
		$sql = "SELECT * FROM service_item where method!='delete'";
		
		
		try {
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql);
			$conn->close();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	public function getServItemCount()//获取中心服务项目数目
	{
		$sql = "SELECT count(id) AS count FROM service_item where method!='delete'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询中心服务项目数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	
	public function delServItem($item_id)//删除项目
	{
		$mysql = new MySQL();
		if(!$mysql->inject_check($item_id))
		{
		$sql = "DELETE FROM service_item where id = ?";
		try{
			$conn = $mysql->getConn();
			$ps = $conn->prepare($sql);
			$ps->bind_param("i",$item_id);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	/*****************************/
	
	/***********服务发布************/
	public function getServAppointInfo($shop_id,$date,$page)//获取中心服务
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM service_appointment_info where shop_id = '$shop_id' and date = '$date' and method!='delete' limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM service_appointment_info where shop_id = '$shop_id' and date = '$date' and method!='delete'";
		}
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql);
			
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo = array();
		while($re=$result->fetch_array())
		{
			$getinfo[$i]=$mysql->getServInfoByID($shop_id,$re['item_id']);
			$getinfo[$i]['time']=$re['time_period'];
			$getinfo[$i]['date']=$date;
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getServAppointInfoCount($shop_id,$date)//获取中心服务数目
	{
		$sql = "SELECT count(id) AS count FROM service_appointment_info where shop_id = '$shop_id' and date = '$date' and method!='delete'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询中心服务预约数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	public function getServInfoByID($shop_id,$item_id)//通过id找服务信息
	{
		$sql1 = "SELECT shop_name FROM service_shop where id = '$shop_id' and method!='delete'";
		$sql3 = "SELECT item_name ,item_info  FROM service_item where id = '$item_id' and method!='delete'";
		try{
			$result = array();
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql1) or die("操作失败！");;
			$result = $query->fetch_array();
			$info['shop_name'] = $result['shop_name'];
			$query = $conn->query($sql3) or die("操作失败！");;
			$result = $query->fetch_array();
			$info['item_name'] = $result['item_name'];
			$info['item_info'] = $result['item_info'];
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $info;
	}
	
	public function setServAppoint($shop_id,$item_id,$time,$date)//新增服务
	{
		$mysql = new MySQL();
		if((!$mysql->inject_check($shop_id))&&(!$mysql->inject_check($item_id))&&(!$mysql->inject_check($time))&&(!$mysql->inject_check($date)))
		{
		$sql = "INSERT INTO service_appointment_info (shop_id,item_id,time_id,date) VALUES (?,?,?,?)";
		try{
			
			$conn = $mysql->getConn();
			$time_id = $mysql->getTimeID($time);
			$ps = $conn->prepare($sql);
			$ps->bind_param("ssss",$shop_id,$item_id,$time_id,$date);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
		}
		else{ return false;}
	}
	
	public function getTimeID($time)//获取时间id，如果没有就插入
	{
		$sql = "SELECT id FROM time_period where time_period='$time'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql) or die("查询失败!");
			$row = $query->num_rows;
			$re = $query->fetch_array();
			$result = $re['id'];
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		if($row==0)
		{
			$sql = "INSERT INTO time_period (time_period) VALUES ('$time')";
			try{
				$query = $conn->query($sql) or die("插入失败!");
				$conn->close();
				$result = $mysql->getTimeID($time);
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}	
		}
		
		
		return $result;
	}
	/******************************/
	
	/************查看预约********/
	
	public function getServAppoint($page)//获取中心预约
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM service_appointment  ORDER BY id limit $p,".EACHPAGE."";
		}
		else{$sql = "SELECT * FROM service_appointment  ORDER BY id limit $p,".EACHPAGE."";}
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("操作失败");

		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo=array();
		while($re=$result->fetch_array())
		{
			$getinfo[$i]=$mysql->getAppointInfoByID($re['user_id'],$re['service_appointment_info_id']);
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getServAppointCount()//获取中心服务预约数
	{
		$sql = "SELECT count(id) AS count FROM service_appointment ";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询中心预约数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	
	public function getAppointInfoByID($user_id,$saiid)//获取预约信息的ID
	{
		$sql = "SELECT * FROM user WHERE id = '$user_id'";
		$sql2 = "SELECT * FROM service_appointment_info WHERE id = '$saiid' ";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query1 = $conn->query($sql) or die("操作失败");
			$query2 = $conn->query($sql2) or die("操作失败");
			$user = $query1->fetch_array();
			$re = $query2->fetch_array();
			$getinfo=$mysql->getServInfoByID($re['shop_id'],$re['item_id']);
			$getinfo['user'] = $user['name'];
			$getinfo['phone'] = $user['phone'];
			$getinfo['identity_card'] = $user['identity_card'];
			$getinfo['id'] = $saiid;
			$getinfo['date'] = $re['date'];
			$getinfo['time'] = $re['time_period'];
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		return $getinfo;
	}
	/*******************/
	
	//医生兼职
	/****************兼职发布*************/
	public function setParttimeServ($shop_id,$item_id,$time,$count,$date,$period)//新增兼职
	{
		$sql = "INSERT INTO parttime_appointment_info (shop_id,item_id,time_id,count,date,is_morning) VALUES (?,?,?,?,?,?)";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$time_id = $mysql->getTimeID($time);
			$ps = $conn->prepare($sql);
			$ps->bind_param("ssssss",$shop_id,$item_id,$time_id,$count,$date,$period);
			$result = $ps->execute();
			$ps->close();
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result; 
	}
	
	public function getParttimeServ($shop_id,$date,$period,$page)//获取兼职
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM parttime_appointment_info where shop_id = '$shop_id' and date = '$date' and is_morning='$period' and method!='delete' ORDER BY id limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM parttime_appointment_info where shop_id = '$shop_id' and date = '$date' and is_morning='$period' and method!='delete' ORDER BY id limit $p,".EACHPAGE."";
		}
		
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			
			$result = $conn->query($sql) or die("查询兼职操作失败");
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo = array();
		while($re=$result->fetch_array())
		{
			$getinfo[$i]=$mysql->getServInfoByID($shop_id,$re['item_id']);
			$getinfo[$i]['count']=$re['count'];
			$getinfo[$i]['date']=$date;
			$getinfo[$i]['period']=$period;
			$getinfo[$i]['time']=$re['time_period'];
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getParttimeServCount($id,$date,$period)//获取兼职数
	{
		$sql = "SELECT count(id) AS count FROM parttime_appointment_info where shop_id = '$id' and date = '$date' and is_morning='$period' and method!='delete'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询兼职数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	/***********查看已定预约***************/
	public function getParttimeAppoint($page)//查看兼职预约
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM parttime_appointment  ORDER BY id limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM parttime_appointment  ORDER BY id limit $p,".EACHPAGE."";
		}
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo = array();
		while($re=$query->fetch_array())
		{
			
			$getinfo[$i]=$mysql->getParttimeInfoByID($re['user_id'],$re['parttime_appointment_info_id']);
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getParttimeAppointCount()//获取兼职预约数
	{
		$sql = "SELECT count(id) AS count FROM parttime_appointment ";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询兼职预约数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	
	public function getParttimeInfoByID($doctor_id,$paiid)//通过id找兼职信息
	{
		$sql = "SELECT * FROM parttime_appointment_info WHERE id = '$paiid' ";
		$sql1 = "SELECT * FROM user WHERE id = '$doctor_id'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
			$query1 = $conn->query($sql1);
			$re = $query->fetch_array();
			$doctor = $query1->fetch_array();
			$info = $mysql->getServInfoByID($re['shop_id'],$re['item_id']);
			$info['doctor'] = $doctor['name'];
			$info['identity_card'] = $doctor['identity_card'];
			$info['phone'] = $doctor['phone'];
			$info['date'] = $re['date'];
			$info['time'] = $re['time_period'];
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		return $info;
	}
	/***********************************/
	
	
	
	/*************查看注册信息***************/
	public function getDoctorInfo($page)//获取医生注册信息
	{
		if(isset($page))
		{
		$p = ($page-1)*EACHPAGE;
		$sql = "SELECT * FROM doctor ORDER BY status limit $p,".EACHPAGE."";
		}
		else{
		$sql = "SELECT * FROM doctor ORDER BY status limit $p,".EACHPAGE."";
		}
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$i = 0;
		$getinfo=array();
		while($re=$query->fetch_array())
		{
			$getinfo[$i] = $re;
			$getinfo[$i]['item'] = $mysql->getItemByID($re['item_id']);
			$i++;
		}
		$conn->close();
		return $getinfo;
	}
	public function getDoctorInfoCount()//获取医生数
	{
		$sql = "SELECT count(id) AS count FROM doctor";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$result = $conn->query($sql) or die("查询医生数目操作失败");
			$re = $result->fetch_array();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		$conn->close();
		
		return $re['count'];
	}
	public function getItemByID($item_id)//通过ID找服务项目
	{
		$sql = "SELECT * FROM service_item WHERE id = '$item_id'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
			$re = $query->fetch_array();
			$item_name = $re['item_name'];
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $item_name;
	}
	
	public function updateDoctorStatus($id,$status)//更新医生状态
	{
		$result = false;
		$sql = "UPDATE doctor SET status='$status' where id='$id'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql);
			if($conn->affected_rows==1)
			{
			$result = true;
			}
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
	}
	/****************************************/


	//删除处理
	public function upService_shop($id)
	{
		$sql1="update parttime_appointment_info set method='delete' where shop_id='$id'";
		$sql2="update service_appointment_info set method='delete' where shop_id='$id'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql1);
			$query = $conn->query($sql2);
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
	}
	public function upHospital($id)
	{
		$sql1="update hospital_appointment_info set method='delete' where hospital_id='$id'";
		$sql2="update department set method='delete' where hospital_id='$id'";
		try{
			$mysql = new MySQL();
			$conn = $mysql->getConn();
			$query = $conn->query($sql1);
			$query = $conn->query($sql2);
			$conn->close();
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		return $result;
	}
}
?>