<?php
 

require_once('recaptcha-php-1.11/recaptchalib.php');
  
$privatekey = "6LdRjtcSAAAAAJB01z4dVnj4hK-3Bsi7L_a3PNjZ";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["challenge"],
                                $_POST["response"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    //
    die  ('{"status":"0",  "error":"'.$resp->error.'"}');
	
  } else {
  	
  	die ( '{"status":"'. $resp->is_valid.'"}');
   
  }
  ?>