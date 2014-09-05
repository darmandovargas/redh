<?php
	//var_dump($_POST);
	session_start();
	if(isset($_SESSION['sessid']) && $_SESSION['sessid'] = session_id()){		
		echo "success";
	}else{
		echo "failure";
	}
?>