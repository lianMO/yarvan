<?php
header("Content-Type:text/html;charset=UTF-8");
class Parttime_appointment_info 
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
		$url = $this->base_url."/".$type."?Parttime_appointment_info=".$data;
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
	//增加兼职
	public function addParttimeInfo($shop_id,$item_id,$time,$count,$date,$period)
	{
		if((!$this->check($shop_id))&&(!$this->check($item_id))&&(!$this->check($time))&&(!$this->check($count)))
		{
		require_once("MySQL.php");
		$sql = new MySQL();
		$data = array("shop_id"=>$shop_id,"item_id"=>$item_id,"time_period"=>$time,"count"=>$count,"date"=>$date,"is_morning"=>$period);
		$result = $this->urlPost("addParttimeInfo",$data);
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
	//删除兼职
	public function deleteParttimeInfo($part_id)
	{
		if((!$this->check($part_id)))
		{
		$data = array("id"=>$part_id);
		$result = $this->urlPost("deleteParttimeInfo",$data);
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