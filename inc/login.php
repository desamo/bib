<?php
session_start();

		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$db = new Database();
		$check = $db->check_login ($username, $password) ;
		
		if ($check != false){
		$_SESSION['USER'] = $check ;
		echo "success";	
		} 

 ?>