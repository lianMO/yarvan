<?php
header("Content-Type:text/html;charset=UTF-8");
class Staff
{
	public $base_url = 'http://120.24.78.54:8080/ServerForDate';
	//防注入函数
	public function check($sql_str) {  
		return preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);    // 进行过滤  
	}
	//接口请求
	public function urlPost($type,$data)
	{
		$data = json_encode($data);
		$url = $this->base_url."/".$type."?Staff=".$data;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//保存抓取内容而不直接输出
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		$result=curl_exec($ch);
		preg_match_all("/{(.*?)\}/", $result, $matches);
		$re = json_decode($matches[0][0]);
		curl_close($ch);
		return $re;
	}
	//增加管理员
	public function addStaff($name,$pwd,$type)
	{
		$pwd = md5($pwd);
		if((!$this->check($hos_name))&&(!$this->check($hos_address)))
		{
		if($type==0)
		{
			$msg = "操作失败"; $errinfo ="你没有权限";
		}
		else{
		require_once("MySQL.php");
		$sql = new MySQL();
		if($sql->isExistName($name))
		{
			$msg = "操作失败" ; $errinfo = "用户名已存在";
		}
		else
		{
		$data = array("staffName"=>$name,"pwd"=>$pwd);
		$result = $this->urlPost("addStaff",$data);
		$errinfo = $result->info;
			if($result->status)
			{
				$msg = "操作成功";
			}
			else{
				$msg = "操作失败";
			}
		}
		}
		}
		else {
			$msg = "操作失败";
			$errinfo = "防注入触发";
		}
		$re = $msg.",".$errinfo;
		return $re;
	}
	//删除管理员
	public function deleteStaff($id,$type)
	{
		if((!$this->check($id)))
		{
		if($type==0)
		{
			$msg = "操作失败"; $errinfo ="你没有权限";
		}
		else{
		$data = array("id"=>$id);
		$result = $this->urlPost("deleteStaff",$data);
		$errinfo = $result->info;
			if($result->status)
			{
				$msg = "操作成功";
			}
			else{
				$msg = "操作失败";
			}
		}
		}
		else {
			$msg = "操作失败";
			$errinfo = "防注入触发";
		}
		$re = $msg.",".$errinfo;
		return $re;
	}
	public function updateStaffPw($name,$old_pw,$new_pw,$type)
	{
		if((!$this->check($id))&&(!$this->check($ole_pw))&&(!$this->check($new_pw)))
		{
		if($type==0)
		{
			$msg = "操作失败"; $errinfo ="你没有权限";
		}
		else{
		require_once("MySQL.php");
		echo $name.$old_pw.$new_pw;
		$mysql = new MySQL();
		$check = $mysql->checkLogin($name,$old_pw);
		if($check)
		{
		$data = array("id"=>$check['id'],"pwd"=>$new_pw);
		$result = $this->urlPost("updateStaffPw",$data);
		$errinfo = $result->info;
			if($result->status)
			{
				$msg = "操作成功";
			}
			else{
				$msg = "操作失败";
			}
		}
		else
		{
			$msg = "操作失败";$errinfo="验证失败，密码错误";
		}
		}
		}
		else {
			$msg = "操作失败";
			$errinfo = "防注入触发";
		}
		$re = $msg.",".$errinfo;
		return $re;
	}
}
?>