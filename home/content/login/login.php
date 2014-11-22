<?php
	//var_dump($_POST);
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(isset($username) && isset($password) && $username == 'eepereira' && $password == 'eepereira'){
		session_start();
		$_SESSION['sessid'] = session_id();
		$_SESSION['username'] = $username;
		echo "success";
	}else if(isset($username) && isset($password) && $username == 'admin' && $password == 'admin1234'){
		session_start();
		$_SESSION['sessid'] = session_id();
		$_SESSION['username'] = $username;
                $_SESSION['type'] = 'admin';
		echo "success";
	}else{
                echo "failure";
        }
?>