<?php
	//var_dump($_POST);
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(isset($username) && isset($password) && $username == 'eepereira' && $password == 'eepereira'){
		session_start();
		$_SESSION['sessid'] = session_id();
		$_SESSION['username'] = $username;
		echo "success";
	}else{
		echo "failure";
	}
?>