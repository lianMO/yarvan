<?php
header("Content-Type:text/html;charset=UTF-8");
class Doctor
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
		$url = $this->base_url."/".$type."?Doctor=".$data;
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
	//审核医生
	public function updateDoctorStatus($id,$status)
	{
		if((!$this->check($id))&&(!$this->check($status)))
		{
		$data = array("id"=>$id,"status"=>$status);
		$result = $this->urlPost("updateDoctorStatus",$data);
		$errinfo = $result->info;
			if($result->status)
			{
				$msg = "操作成功";
			}
			else{
				$msg = "操作失败";
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