<?php

session_name("fancyform");
session_start();


$_SESSION['n1'] = rand(1,20);
$_SESSION['n2'] = rand(1,20);
$_SESSION['expect'] = $_SESSION['n1']+$_SESSION['n2'];


$str='';
if($_SESSION['errStr'])
{
	$str='<div class="error">'.$_SESSION['errStr'].'</div>';
	unset($_SESSION['errStr']);
}

$success='';
if($_SESSION['sent'])
{
	$success='<div align="center"><h1>Gracias!</h1></div>';
	
	$css='<style type="text/css">#contact-form{display:none;}</style>';
	
	unset($_SESSION['sent']);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contactenos</title>

<link rel="stylesheet" type="text/css" href="jqtransformplugin/jqtransform.css" />
<link rel="stylesheet" type="text/css" href="formValidator/validationEngine.jquery.css" />
<link rel="stylesheet" type="text/css" href="demo.css" />

<?=$css?>
<!--
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
-->
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="jqtransformplugin/jquery.jqtransform.js"></script>
<script type="text/javascript" src="formValidator/jquery.validationEngine.js"></script>
<!--
<script type="text/javascript" src="script.js"></script>
-->
<script type="text/javascript" src="js/contactus.js"></script>

<style>
			.loader {
				opacity: 0.4;
				filter: alpha(opacity=40); /* For IE8 and earlier */
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 2;
				background: url('wait.gif') 50% 50% no-repeat rgb(249,249,249);
			}

			.confirm {
				opacity: 0.8;
				filter: alpha(opacity=40); /* For IE8 and earlier */
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 2;
				font-size: 24px;
				font-weight: 500;
				background: url('img/white.jpg') 50% 50% no-repeat rgb(249,249,249);
			}

			.errorMsg {
				opacity: 0.8;
				filter: alpha(opacity=40); /* For IE8 and earlier */
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 2;
				font-size: 24px;
				font-weight: 500;
				/*background: url('img/white.jpg') 50% 50% no-repeat rgb(249,249,249);*/
				background-color: #F75D59;
			}
		</style>
		
</head>

<body>

<div id="main-container">

	<div id="form-container">
		<!-- Loading Gif -->
		<div class="loader" style="display: none"></div>
	<!--	
    <h1>Contactenos</h1>    
    <h2>Drop us a line and we will get back to you</h2>
    -->
    <form id="contact-form" name="contact-form" method="post" action="submit.php">
      <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td width="15%"><label for="name">Nombre</label></td>
          <td width="70%"><input type="text" class="validate[required,custom[onlyLetter]]" name="name" id="name" value="<?=$_SESSION['post']['name']?>" /></td>
          <td width="15%" id="errOffset">&nbsp;</td>
        </tr>
        <tr>
          <td><label for="email">Email</label></td>
          <td><input type="text" class="validate[required,custom[email]]" name="email" id="email" value="<?=$_SESSION['post']['email']?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><label for="subject">Asunto</label></td>
          <td><input type="text" class="validate[required]" name="subject" id="subject" value="<?=$_SESSION['post']['subject']?>" /> </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><label for="message">Mensaje</label></td>
          <td>
          	<textarea name="message" id="message" class="validate[required]" cols="35" rows="5"><?=$_SESSION['post']['message']?></textarea>
          	<span id="loading_black" hidden  style="z-index:2; position:fixed; top:120px; left:<?= $isCom?'113px':'103px'; ?>">
				<img src="loading_black.gif" width="30%" alt="loading" />									
			</span>	
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>       
        
			<td nowrap class='title'> Captcha &nbsp;&nbsp;*&nbsp;: </td>
			<td id="captchaBlock">
				<script type="text/javascript">
					var RecaptchaOptions = {
						theme : 'custom',
						custom_theme_widget : 'recaptcha_widget'
					};
				</script>
				<div id="recaptcha_widget" style="display:none">
					<div id="recaptcha_image" onchange="changeCaptchaSize();"></div>
					<div style="margin-top:5px;" class='title'>
						<?= ($isSpanish) ? 'Ingrese las letras de arriba' : 'Enter the words above'; ?>:
						<a id="aRecaptcha" title="New Captcha"  href="javascript:Recaptcha.reload()"> <img id="recaptcha_reload" width="25" height="17" src="http://www.google.com/recaptcha/api/img/red/refresh.gif" alt="Get a new challenge" style="margin: 5px 0 0 5px;" /> </a>
					</div>
					<input type="text" class="validate[required]" id="recaptcha_response_field" name="recaptcha_response_field" />

				</div>
				<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LdRjtcSAAAAADrU3CO__CymiB9D8s_PU_2Kq49g"></script>
				<script type="text/javascript">
					$('#recaptcha_image').css({
						'width' : '155px',
						'height' : '48px'
					});
				</script>
				<noscript>
					<iframe src="http://www.google.com/recaptcha/api/noscript?k=6LdRjtcSAAAAADrU3CO__CymiB9D8s_PU_2Kq49g" height="250" width="500" frameborder="0"></iframe>
					<br>
					<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
					<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
				</noscript>
			<td valign="top">&nbsp;</td>


          
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
          <td colspan="2"><input type="submit" name="button" id="button" value="Enviar" />
          <input type="reset" name="button2" id="button2" value="Limpiar" />
          
          <?=$str?>          <img id="loading" src="img/ajax-load.gif" width="16" height="16" alt="loading" /></td>
        </tr>
      </table>
      </form>
      <?=$success?>
      
      <div align="center">
      	</br></br>
    Mayor Información: </br></br>
    Facultad de Ciencas Ambientales Of f-213 </br> 
	Email: redhidroclimatologica@utp.edu.co </br>
	Juan Camilo Berrio Carvajal </br>
	Telefono: 3137249 </br></br></br>
	</div> 
    </div>
</div>
</body>
</html>
