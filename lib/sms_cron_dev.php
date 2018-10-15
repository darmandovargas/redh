<?php
/**
 * This is a cron that will run each 10 minutes looking for the last two periods
 * in order to send an alert through sms to the involved people in order to warn
 * people that lives around water 
 * 
 * @author Diego Vargas 
 * @license Think Cloud Group
 * @since 2017-02-11
 */
 
// Obtiene la conexión a la bd
include ('class.MySQL.php');
// Get sms libs
include_once('sms.php');

// Get log lib
include_once('error.php');

$Message;

mysql_query("set names 'utf8'");
// Inicializa variables
$AccountID="CI00112133";
$Email="darmandovargas@gmail.com";
$Password="LM;K6Ck:R_zdD:6;"; 
$Message = "";
$specificMessage = array();

// Current value, this is used on level last value reset
$currentLevelValue = 0;

// This is used as a global variable in order to update it from checkAlarms 
// function when its needed to set a last level value or first alarm
$objTemp = array();
			
// Get alarms of estaciones
$estacionesList = $oMySQL->executeSQL('SELECT * FROM estaciones WHERE alarma IS NOT NULL AND alarma != ""');
$alarmUpdateList = array();
//var_dump($estacionesList);
$updatedIds = array();
// Iterates the alarms in order to send SMS		
foreach($estacionesList as $el){
	// This will ensure last alarm is sent once an hour
	$now = time();
	// Decode alarm json in order to analyze it
	$alarmJSON = json_decode($el['alarma']);
	// Initialize some needed variables per station
	$tabla = $el['estNombreTb'];
	$stationName = $el['estNombre'];
	$alarmJSONTemp = array();
	
	// Iterates the alarm json in order to send SMS
	foreach ($alarmJSON as $variable => $obj) {
		// Initialize some variables 
		$lessThan = $moreThan = false;
		$last_sms = 0;
		$objTemp = array();
		// Iterates the json of each variable
		foreach ($obj as $mathSymbol => $symbolValue) {
			// I assign the json object in a temporal variable
			$objTemp[$mathSymbol]=$symbolValue;
			// I setup variables for less than and more than values on each variable
			switch ($mathSymbol) {
				case '<':$lessThan=$symbolValue;	break;
				case '>':$moreThan=$symbolValue;  break;
				//case 'last_sms':$last_sms = $symbolValue;  break;
				default:break;
			} 
		}
		// If the last sms for this specific variable is older than an 2 hours ago, then it check for alarms on this variable
		if($now > $objTemp["last_sms"]+2*60*60){
			// Check alarm for this variable and add it to the general alarm message
			$response = checkAlarms($oMySQL, $tabla, $stationName, $variable, $lessThan, $moreThan, $objTemp["last_value"], $currentLevelValue, $objTemp, $Message);
			// If there were any alarm detected for this variable then update the last sms variable of the json for the specific variable
			if($response){
				$updatedIds[] = $el['id'];
				$objTemp["last_sms"]=time(); 
				if($variable == "nivel"){
					if($currentLevelValue < $objTemp["last_value"]){
						$objTemp["last_value"]=0; 	
					}
				}
			}	
		}		
		// In order to get the updated json we keep the alarmUpdateList variable up to date with all variables on each variable iteration until we have it completed
		$alarmJSONTemp[$variable] = $objTemp;
		$alarmUpdateList[$el['id']] = json_encode($alarmJSONTemp); 
	}
}

$oMySQL->closeConnection();
unset($oMySQL);
$oMySQL = new MySQL('bdsig', 'hidro', 'hidro', '201.131.90.70');

// Based on the list collected on the last iteration we update the json of the stations with last sms date of each alarm we sent
if(!empty($updatedIds)){
	foreach ($alarmUpdateList as $key => $value) {
		if(in_array($key, $updatedIds)){
			//$updateLastVariableAlarm = $oMySQL->executeSQL('UPDATE estaciones SET alarma = \''.$value.'\' WHERE id='.intval($key));	
		}		
	}	
}


var_dump($alarmUpdateList);
echo "</br></br>";
var_dump($Message);
// Make sure we have the right timezone
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	// Set message format with the date of the sms
	$msg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message."Visítanos redhidro.org";
//AltiriaSMS("573108311240", $msg, "dvargas", false);
exit();

// I check if there is a pending to send message
if(!empty($Message)){
	// Make sure we have the right timezone
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	// Set message format with the date of the sms
	$msg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message."Visítanos redhidro.org";
	// Get all the amount of remaining messages from the vendor
	$smsCount = $oMySQL->executeSQL('SELECT messages FROM sms WHERE id = 2');	
	// if I have more than 50 sms
	if(intval($smsCount["messages"])>50){
		$allStations = "573113858761,573137466206,573148216816,573128949483,573206310432,573163461538,573163609097,573223195922,573103545446,573148141462,573136355940,573116237381,573148331642,573206770012,573177342310,573163000496";
		// Get all stations dynamic count
		$allStationsCount = count(explode(",",$allStations));

		$allTablesNumbers = array('tb_cortaderal'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'tb_el_cedral'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'tb_ellago'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'tb_san_juan'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'rio_azul_eljordan'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'rio_otun_eljordan'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'rio_barbo_pezfresco'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'q_volcanes'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'bocatoma_belmonte'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258',
								'bocatoma_nuevo_libare'=>'573108982236,573113529335,573117486394,573113801014,573206911116,573207198793,573186953212,573154910741,573206650421,573183659258', 
								'tb_rioguatica'=>'573112163678,573108152873,573128468859,573103095998', 
								'tb_riomistrato'=>'573112163678,573108152873,573128468859,573103095998', 
								'tb_eldiamante'=>'573112163678,573108152873,573128468859,573103095998', 
								'tb_mairabajo'=>'573112163678,573108152873,573128468859,573103095998');

		// Send all stations message
		$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-'.$allStationsCount.' WHERE id=2');
		$resp["allstations"] = AltiriaSMS($allStations, $msg, "dvargas", false);
		@error_log(PHP_EOL.PHP_EOL."All Stations: ".$msg.PHP_EOL.$resp["allstations"], 3, "/home/thinkclo/public_html/redh/sms.log");
		echo "All Stations: ".$msg."</br>".$resp["allstations"]."</br>";
		// Check if there are specific tables alarm
		if($specificMessage){
			// This is an specific tables messages counter
			$totalSpecificMessages = 0;
			// Send sms to different organizations based on the table warning numbers defined in all tables numbers variable
			foreach ($specificMessage as $tab => $ms) {
				// If the tables is defined at $allTablesNumbers then proceed with the sms, otherway do nothing
				if(isset($allTablesNumbers[$tab])){
					// Sum the specific tables messages count
					$totalSpecificMessages += count(explode(",",$allTablesNumbers[$tab]));	
					// Prepare the message to be sent
					$ms = $dt->format("Y-m-d H:i:s")." Alarma: ".$ms."Visítanos redhidro.org";
					// Send message to specific numbers depending on the table
					$resp[$tab] = AltiriaSMS($allTablesNumbers[$tab], $ms, "dvargas", false);
					// Save logs
					@error_log(PHP_EOL.PHP_EOL.$tab." : ".$ms.PHP_EOL.$allTablesNumbers[$tab].PHP_EOL.$resp[$tab], 3, "/home/thinkclo/public_html/redh/sms.log");
					// Return response on manual cron run
					echo $tab.": ".$ms."</br>".$allTablesNumbers[$tab]."</br>";
				}
			}
			// Update pending messages based on the total amount of specific messages sent on this iteraction
			$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-'.$totalSpecificMessages.' WHERE id=2');	
		}
		// Sets the total remined messages after the total station and specific table messages sent
		$totalRemainMessages = $smsCount["messages"] - $totalSpecificMessages - $allStationsCount;
		// Sets the total sent messages
		$totalMessages = $totalSpecificMessages + $allStationsCount;
		// Save logs
		@error_log(PHP_EOL.PHP_EOL.$totalMessages."->".$smsCount["messages"].":".$totalRemainMessages, 3, "/home/thinkclo/public_html/redh/sms.log");
		// Return response on manual cron run
		echo $totalMessages."->".$smsCount["messages"].":".$totalRemainMessages."</br>";
	}else if(intval($smsCount["messages"])<=50){
		// Send out of messages warning
		$totalRemainMessages = $smsCount["messages"] - 4;
		$outOfMessagesMsg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message.". Sus mensajes se están agotando, solo le quedan ".$totalRemainMessages.", comuníquese con Think Cloud Group para comprar un paquete adicional.";
		$outOfMessages = "573136355940,573108311240";
		$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = '.$totalRemainMessages.' WHERE id=2');
		$resp["outofmessages"] = AltiriaSMS($outOfMessages, $outOfMessagesMsg, "dvargas", false);
		@error_log(PHP_EOL.PHP_EOL."OUT OF MESSAGES: ".$outOfMessagesMsg.PHP_EOL.$resp["outofmessages"], 3, "/home/thinkclo/public_html/redh/sms.log");
		echo "OUT OF MESSAGES: ".$msg."</br>".$resp["allstations"]."</br>";

	}else{
		$response = $dt->format("Y-m-d H:i:s")." Alarma(s) NO ENVIADAS POR FALTA DE SALDO: ".$Message."Visítanos redhidro.org";
		@error_log(PHP_EOL.PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");
		echo $response."</br></br>";
	}
}else{
	echo "There is no alarm to be sent";
}



?>