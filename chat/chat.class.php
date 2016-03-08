<?php 
class Chat{
	
	public function __construct(){
		include 'conn.php';
	}
	
	public function getMessage($staff_id){
		$query = "SELECT * FROM query where `staff_id`='$staff_id' AND `isread`='0'";
		$res = mysql_query($query);
		$arr = mysql_fetch_array($res);
		//将改消息置为已读
		//$flag = $this->updateStatus($arr['id']);
		//var_dump($flag);
		return $arr;
	}
	
	public function updateStatus($id){
		$query = "UPDATE query SET `isread`='1' WHERE `id`='$id'";
		$res = mysql_query($query);
		return $res;
	}
	
	public function addMessage($user_id,$staff_id,$content,$createtime){
		$query = "INSERT INTO answer(user_id,staff_id,content,createtime,isAnswer)
					VALUES('$user_id','$staff_id','$content','$createtime','0')
				";
		$res = mysql_query($query);
		return $res;
	}
}

?>