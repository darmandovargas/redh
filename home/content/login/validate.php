<?php
	//var_dump($_POST);
	session_start();
	if(isset($_SESSION['sessid']) && $_SESSION['sessid'] == session_id()){
		$_SESSION['sess'] = true;		
		echo "success";
	}else{
		$_SESSION['sess'] = false;
		echo "failure";
	}
?>