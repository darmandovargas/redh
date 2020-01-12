<?php
$emailAddress = 'redhidroclimatologica@utp.edu.co';
//$emailAddress = 'darmandovargas@gmail.com';

require "class.phpmailer.php";

foreach($_POST as $k=>$v){
	if(ini_get('magic_quotes_gpc'))
	$_POST[$k]=stripslashes($_POST[$k]);	
	$_POST[$k]=htmlspecialchars(strip_tags($_POST[$k]));
}

$err = array();
$len = 2;

if(!checkLen('name')){
    $err[]='The name field is too short or empty!';
}
if(!checkLen('email'))
    $err[]='The email field is too short or empty!';
else if(!checkEmail($_POST['email']))
    $err[]='Your email is not valid!';

if(!checkLen('subject'))
    $err[]='You have not selected a subject!';

if(!checkLen('message'))
    $err[]='The message field is too short or empty!';

if(count($err))
{
    if($_POST['ajax']){
        echo '-1';
    }else if($_SERVER['HTTP_REFERER']){
        //$_SESSION['errStr'] = implode('<br />',$err);
        //$_SESSION['post']=$_POST;       
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }    
    exit;
}

// Based on clients need
$msg = $_POST['name'].'-*'.$_POST['email'].'-*'.$_SERVER['REMOTE_ADDR'].'-*'.nl2br($_POST['message']);

$mail = new PHPMailer();
$mail->IsMail();
$mail->AddReplyTo($_POST['email'], $_POST['name']);
$mail->AddAddress($emailAddress);
$mail->SetFrom($_POST['email'], $_POST['name']);
$mail->Subject = "REDH | ".mb_strtolower($_POST['subject'])." | ".$_POST['name']." | Contactenos";
$mail->MsgHTML($msg);
$mail->Send();

//unset($_SESSION['post']);
return '1';

/**
 * This function validates the lenght of each post value
 */
function checkLen($str, $len=2){
    return isset($_POST[$str]) && mb_strlen(strip_tags($_POST[$str]),"utf-8") > $len;
}

/**
 * This function validates the valid pattern of the email
 */
function checkEmail($str){
    return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}
?>
