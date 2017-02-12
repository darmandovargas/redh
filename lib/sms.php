<?php
	
	//$file = "c:\\EURECA.txt";
	/*
	$file = "c:\\Users\\HP\\AppData\\Local\\CommandSMS\\EURECA.txt";
	$commandSMSPath = "c:\\Users\\HP\\AppData\\Local\\CommandSMS\\CommandSMS.exe";
	$type = 4;
	*/
	
	/**
	$type:
	0 => string 'Account settings:' (length=17)
  	1 => string 'Is account valid = Yes' (length=22)
  	2 => string 'Remain credits for day = 200' (length=28)
  	3 => string 'Max messages for day = 200' (length=26)
  	4 => string 'Remain credits = 200' (length=20)  	
	 */
//	$_SESSION['oxygen']['remain_credits'] = showCredits($file,$commandSMSPath, $type);
	//$credits = explode("=", $credits[4]); echo $credits[1];
	
function SendMessage($AccountID, $Email, $Password, $Recipient, $Message) {
	  
	$Parameters['AccountID'] = $AccountID;
	$Parameters['Email'] = $Email;
	$Parameters['Password'] = $Password;
	$Parameters['Recipient'] = $Recipient;
	
	$vowelsAccent = array("á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ");
	$vowels = array("a", "e", "i", "o", "u", "n", "A", "E", "I", "O", "U", "N");
	
	// Produce: <body text='black'>
	$Message = str_replace($vowelsAccent, $vowels, $Message);
	
	$Parameters['Message'] = $Message;

	Request($Parameters, 'http://sms1.redoxygen.net/sms.dll?Action=SendSMS');
}

function showCredits($file,$commandSMSPath, $type) {
	//set up the command to get the credits and save the response into a file	
	$command = "\"".$commandSMSPath."\" verify -a CI00114652 -e jtonti@gmail.com -p 12345 -m 3127605371 > \"".$file."\" "; 
	//execute command
	system($command);		
	//open file with response
	$my_file = file_get_contents($file);
	//divide by line
	$my_file_by_line = explode("\r\n", $my_file);
	//answer
	$credits=array();
	//fill an array with all the answer of the command	
	foreach ($my_file_by_line as $key => $value) {				
		$credits[] = $value;				
	}	
	$credits = explode("=", $credits[$type]); 
	return $credits[1];
}

function showCreditsLinux($file,$commandSMSPath) {
	//set up the command to get the credits and save the response into a file	
	$command = "\"".$commandSMSPath."\" verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240 > \"".$file."\" "; 
	//execute command
	system($command);		
	//open file with response
	$my_file = file_get_contents($file);
	//divide by line
	$my_file_by_line = explode("\r\n", $my_file);
	//answer
	$credits=array();
	//fill an array with all the answer of the command	
	foreach ($my_file_by_line as $key => $value) {				
		$credits[] = $value;				
	}
	return $credits;
}

function showCreditsDepreciated($AccountID, $Email, $Password, $Recipient, $Message) {
	$Parameters['AccountID'] = $AccountID;
	$Parameters['Email'] = $Email;
	$Parameters['Password'] = $Password;
	$Parameters['Recipient'] = $Recipient;
	//$Parameters['Message'] = $Message;

	//Request($Parameters, 'http://sms1.redoxygen.net/sms.dll?Action=verify');
	//echo exec("CommandSMS verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240");
	//echo exec('cd C:\Users\HP\AppData\Local\CommandSMS');
	$answer=array('this is the answer'=>1);
	//$response = syscall('CommandSMS verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240');
	//$response = runAsynchronously("C:\Users\HP\AppData\Local\CommandSMS\CommandSMS.exe","verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240");
	//test();
	//return $response;
	return system ("\"c:\\Users\\HP\\AppData\\Local\\CommandSMS\\CommandSMS.exe\" verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240");
}

function test(){
	/**
  * This example works fine, there is only 2 double quotes
  */
  system ("\"c:\\Users\\HP\\AppData\\Local\\CommandSMS\\CommandSMS.exe\" CommandSMS verify -a CI00112133 -e darmandovargas@gmail.com -p b4VkkAh1 -m 3108311240");
 /** 
  * This one will fail, php will complain sthg like c:\\program is not an executable
  */
  //system ("\"c:\\program files\\myapp\\myapp.exe\" \"One param for myapp that contains space\"");
  //
  /** 
   * If you want your script to be able to run with older version of PHP (like 4.3.3), this is a trick:
   * Save the command in a temporary file and call that file
   */
  $tmpnam = tempname($writable_dir, "temp").".bat";
  $fp = fopen ($tmpnam, "w");
  fwrite($fp, $command);
  fclose ($fp);
  system($tmpnam, $status);
  unlink($tmpnam);
}

function runAsynchronously($path,$arguments) { 
    $WshShell = new COM("WScript.Shell"); 
    $oShellLink = $WshShell->CreateShortcut("temp.lnk"); 
    $oShellLink->TargetPath = $path; 
    $oShellLink->Arguments = $arguments; 
    $oShellLink->WorkingDirectory = dirname($path); 
    $oShellLink->WindowStyle = 1; 
    $oShellLink->Save(); 
    $oExec = $WshShell->Run("temp.lnk", 7, false); 
	return $oExec;
    unset($WshShell,$oShellLink,$oExec); 
    unlink("temp.lnk"); 
} 

function syscall($command){
    if ($proc = popen("($command)2>&1","r")){
        while (!feof($proc)) $result .= fgets($proc, 1000);
        pclose($proc);
        return $result; 
    }
}

function Request($Parameters, $URL) {
	$URL = preg_replace('@^http://@i', '', $URL);
	$Host = substr($URL, 0, strpos($URL, '/'));
	$URI = strstr($URL, '/');
	$Body = '';

	foreach ($Parameters as $Key => $Value) {
		if (!empty($Body)) {
			$Body .= '&';
		}

		//if($Key!="Message"){
			$Body .= $Key . '=' . urlencode($Value);	
		//}else{
			//$Value = htmlentities($Value, ENT_QUOTES,'UTF-8'); // así de sencillo
			
			//$Body .= $Key . '='. utf8_encode($Value);
			//$Body .= $Key . '='. urlencode($Value);
		//	$Body .= $Key . '='. urlencode("áéíóúñ@");
			//$Body .= $Key . '=áéíóúñ%40';
			
			
		//}
		
	}

	$ContentLength = strlen($Body);

	$Header = "POST $URI HTTP/1.1\n";
	$Header .= "Host: $Host\n";
	$Header .= "Content-Type: application/x-www-form-urlencoded\n";
	//$Header .= "Content-Type: text/plain;charset=utf-8\n";
	$Header .= "Content-Length: $ContentLength\n\n";
	$Header .= "$Body\n";
//var_dump($Body);
	$Socket = fsockopen($Host, 80, $ErrorNumber, $ErrorMessage);
	fputs($Socket, $Header);

	while (!feof($Socket)) {
		$Result[] = fgets($Socket, 4096);
	}

	fclose($Socket);
}
?>