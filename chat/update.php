<?php
	include 'chat.class.php';
	$chat = new Chat();
	if($id=$_POST['messageId']){
		$chat->updateStatus($id);
	}
	
	
?>