<?php 
class SocketTCP{
	
	private $ip = "";
	private $port = 0;
	private $socket = null;
	
	public function __construct(){
		error_reporting(E_ALL);
		set_time_limit(0);
	}
	
	public function socketOnline($i,$p){
		$this->ip = $i;
		$this->port = $p;
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket < 0) {
			echo "socket_create() failed!\n";
		}else {
			//echo "socket_create() succeed!\n";
		}
		$result = socket_connect($this->socket, $this->ip, $this->port);
		if ($result < 0) {
			echo "socket_connect() failed!\n";
		}else {
			//echo "socket_connect() succeed!\n";
			return true;
		}
	}
	
	public function socketSend($msg){
		if(!socket_write($this->socket, $msg, strlen($msg))) {
			echo "socket_write() failed!\n";
		}else {
			//echo "socket_write() succeed!\n";
			return true;
		}
	}
	
	public function socketReceive(){
		$msg = '';
		$msg .= socket_read($this->socket, 8192);
		return $msg;
	}
	
	public function socketOffline(){
		$result = socket_close($this->socket);
		return $result;
	}
}
?>