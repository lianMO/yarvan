<?php
	header("content-Type: text/html; charset=utf-8");
	//session_start();
	include 'chat.class.php';
	
	$chat = new Chat();
	
	
	if(isset($_POST['user_id'])){
		$user_id = $_POST['user_id'];
		$staff_id = $_POST['staff_id'];
		$content = $_POST['content'];
		$createtime = $_POST['createtime'];
		$res = $chat->addMessage($user_id,$staff_id,$content,$createtime);
		if($res) echo "1";
	}else{
		if(isset($_GET['staff_id']))
			$staff_id = $_GET['staff_id'];

		$ch = curl_init(); 
		$url = "http://120.24.78.54:8080/ServerForDate/online?id=".$staff_id; 

		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); 
		$page_content = curl_exec($ch); 
		curl_close($ch); 

		$message = $chat->getMessage($staff_id);
		echo json_encode($message);
	}
	

?>