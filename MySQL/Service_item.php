<?php
header("Content-Type:text/html;charset=UTF-8");
class Service_item
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
		$url = $this->base_url."/".$type."?Service_item=".$data;
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
	//增加服务项目
	public function addServiceItem($item_name,$info)
	{
		if((!$this->check($item_name))&&(!$this->check($info)))
		{
		$data = array("item_name"=>$item_name,"item_info"=>$info);
		$result = $this->urlPost("addServiceItem",$data);
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
	//删除服务项目
	public function deleteServiceItem($item_id)
	{
		if((!$this->check($item_id)))
		{
		$data = array("id"=>$item_id);
		$result = $this->urlPost("deleteServiceItem",$data);
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