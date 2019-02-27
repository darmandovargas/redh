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
include_once('alarm.php');
// Get log lib
include_once('error.php');
// Set encoding
mysql_query("set names 'utf8'");
// Inicializa variables
$Message = "";
$specificMessage = array();

$currentTime = new DateTime('', new DateTimeZone('America/Bogota'));
$startDate = $currentTime->format("Y-m-d H:i:s");
//echo "Start Date:".$startDate."</br>";

// Current value, this is used on level last value reset
//$currentLevelValue = 0;

// This is used as a global variable in order to update it from checkAlarms 
// function when its needed to set a last level value or first alarm
$objTemp = array();
			
// Get alarms of estaciones
$estacionesList = $oMySQL->executeSQL("SELECT tia.id, tdps.idStation, tdps.stationName, tdps.tableName, tia.station_id, tia.alarm 
										FROM tdp_stations AS tdps
										JOIN ti_alarm AS tia ON tia.station_id = tdps.idStation
										WHERE tia.isEnable=1");// AND (tdps.idStation=40 or tdps.idStation=39) //$oMySQL->executeSQL('SELECT * FROM tdp_stations WHERE alarma IS NOT NULL AND alarma != ""');

$alarmUpdateList = array();
$updatedIds = array();
// Iterates the alarms in order to send SMS		
foreach($estacionesList as $el){ 
	// This will ensure last alarm is sent once an hour
	$now = time();
	// Decode alarm json in order to analyze it
	$alarmJSON = json_decode($el['alarm']);
	// Initialize some needed variables per station
	$tabla = $el['tableName'];
	$stationName = $el['stationName'];
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
				case '<':$lessThan=$symbolValue; break;
				case '>':$moreThan=$symbolValue; break;
				default:break;
			} 
		}
		// If the last sms for this specific variable is older than 2 hours ago, then it check for alarms on this variable
		if($now > $objTemp["last_sms"]+4*60*60){
			// Check alarm for this variable and add it to the general alarm message
			$response = checkAlarms($oMySQL, $tabla, $stationName, $variable, $lessThan, $moreThan, $el['id']);
			//echo  "tabla: ".$tabla." - stationName: ".$stationName." - variable: ".$variable." - lessThan: ".$lessThan." - moreThan: ".$moreThan." - id: ".$el['id']."</br>";
			// If there were any alarm detected for this variable then update the last sms variable of the json for the specific variable
			if($response){
				$updatedIds[] = $el['id'];
				$objTemp["last_sms"]=time(); 
			}	
		}		
		// In order to get the updated json we keep the alarmUpdateList variable up to date with all variables on each variable iteration until we have it completed
		$alarmJSONTemp[$variable] = $objTemp;
		$alarmUpdateList[$el['id']] = json_encode($alarmJSONTemp); 
	}
}

$oMySQL->closeConnection();
unset($oMySQL);
$oMySQL = new MySQL($dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp);

// Based on the list collected on the last iteration we update the json of the stations with last sms date of each alarm we sent
if(!empty($updatedIds)){
	foreach ($alarmUpdateList as $key => $value) {
		if(in_array($key, $updatedIds)){
			$updateLastVariableAlarm = $oMySQL->executeSQL('UPDATE ti_alarm SET alarm = \''.$value.'\' WHERE id='.intval($key));	
		}		
	}	
}

// TEST CODE
/*echo "</br></br>AlarmUpdateList: ";
var_dump($alarmUpdateList);
echo "</br></br>";*/
/*
if(!empty($Message)){
	//echo "</br></br>Message: ";
	//var_dump($Message);
	// Make sure we have the right timezone
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	// Set message format with the date of the sms
	$msg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message."Visítanos redhidro.org";

	// This calculates how many SMS are necessary in order to send the whole message
	$msgMultiple = ceil(strlen($Message)/160);
	$totalSentSMS = 1*$msgMultiple;
	//$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-'.$totalSentSMS.' WHERE id=2');
	//AltiriaSMS("573136355940", $msg, "dvargas", false);
	@error_log(PHP_EOL.PHP_EOL."TEST CODE: ".$msg.PHP_EOL, 3, "/home/thinkclo/public_html/redh/sms.log");
	echo $msg;
	
}else{
	echo "There is no alarm to be sent";
}
$currentTime = new DateTime('', new DateTimeZone('America/Bogota'));
$endDate = $currentTime->format("Y-m-d H:i:s");
echo "</br>End Date:".$endDate."</br>";
//$diff = $endDate->diff($startDate); //date_diff($startDate,$endDate);
echo "</br>Time: ".$endDate->diff($startDate)."</br>";
exit();
// END TEST CODE
*/

// Make sure we have the right timezone
$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	
// I check if there is a pending to send message
if(!empty($Message)){
	
	/* Prueba de mensajes solo a Camilo y a mí 	
	// Get all the amount of remaining messages from the vendor
	$smsCount = $oMySQL->executeSQL('SELECT messages FROM ti_sms WHERE id = 2');
	
	$outOfMessagesMsg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message."Visítanos redhidro.org";
	
	$cantidad = 2;
	if(strlen($outOfMessagesMsg) > 160){
		$cantidad = 4;	
	}	
		
	$totalRemainMessages = intval($smsCount["messages"]) - $cantidad;
	$updateSMS = $oMySQL->executeSQL('UPDATE ti_sms SET messages = '.$totalRemainMessages.' WHERE id=2');
	$outOfMessages = "573136355940,573108311240";	
	$resp["outofmessages"] = AltiriaSMS($outOfMessages, $outOfMessagesMsg, "dvargas", false);
	@error_log(PHP_EOL.PHP_EOL."TEST MESSAGES TO CAMILO AND DIEGO CELL: ".$outOfMessagesMsg.PHP_EOL.$resp["outofmessages"], 3, "/home/thinkclo/public_html/redh/sms.log");
	@error_log(PHP_EOL.PHP_EOL."2->".$smsCount["messages"].":".$totalRemainMessages, 3, "/home/thinkclo/public_html/redh/sms.log");
	exit();
	*/
	
	// Set message format with the date of the sms
	$msg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message."Visítanos redhidro.org";
	// Get all the amount of remaining messages from the vendor
	$smsCount = $oMySQL->executeSQL('SELECT messages FROM ti_sms WHERE id = 2');	
	// if I have more than 50 sms
	if(intval($smsCount["messages"])>=30){
		$allStations = "573137466206,573148216816,573128949483,
						573206310432,573163461538,573163609097,
						573223195922,573117875490,573148141462,
						573136355940,573116237381,573177342310,
						573176387793,573126182847,573136388565";
		// Get all stations dynamic count
		$allStationsCount = count(explode(",",$allStations));

		$allTablesNumbers = array('tst_cortaderal'      =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_el_cedral'         =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_san_juan'          =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_rio_azul_eljordan'    =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_rio_otun_eljordan'    =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_rio_barbo_pezfresco'  =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_q_volcanes'	       =>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_bocatoma_belmonte'	   =>'573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421',
								'tst_bocatoma_nuevo_libare'=>'573113530751,573113529335,573117486394,573113801014,573174743771,573218949324,573137433664,573206911116,573207198793,573104725992,573186953212,573122838488,573154910741,573183659258,573127942143,573206650421', 
								'tst_rioguatica'        =>'573112163678,573108152873,573128468859', 
								'tst_riomistrato'       =>'573112163678,573108152873,573128468859', 
								'tst_eldiamante'        =>'573112163678,573108152873,573128468859', 
								'tst_mairabajo'         =>'573112163678,573108152873,573128468859');

		// This calculates how many SMS are necessary in order to send the whole message
		$msgMultiple = ceil(strlen($msg)/160);
		$totalSentSMSAll = $allStationsCount*$msgMultiple;
		// Send all stations message
		$updateSMS = $oMySQL->executeSQL('UPDATE ti_sms SET messages = messages-'.$totalSentSMSAll.' WHERE id=2');
		$resp["allstations"] = AltiriaSMS($allStations, $msg, "dvargas", false);
		@error_log(PHP_EOL.PHP_EOL."All Stations: ".$msg.PHP_EOL.$resp["allstations"], 3, "/home/thinkclo/public_html/redh/sms.log");
		echo "All Stations: ".$msg."</br>".$resp["allstations"]."</br>";
		// Check if there are specific tables alarm
		if($specificMessage){
			// This is an specific tables messages counter
			$currentTotal = 0;
			$totalSentSMSSpecific = 0;
			// Send sms to different organizations based on the table warning numbers defined in all tables numbers variable
			foreach ($specificMessage as $tab => $ms) {
				// If the tables is defined at $allTablesNumbers then proceed with the sms, otherway do nothing
				if(isset($allTablesNumbers[$tab])){
					// Prepare the message to be sent
					$ms = $dt->format("Y-m-d H:i:s")." Alarma: ".$ms."Visítanos redhidro.org";

					// Sum the specific tables messages count
					$currentTotal = count(explode(",",$allTablesNumbers[$tab]));
					// This calculates how many SMS are necessary in order to send the whole message
					$msgMultiple = ceil(strlen($ms)/160);
					$totalSentSMSSpecific += $currentTotal*$msgMultiple;	

					// Send message to specific numbers depending on the table
					$resp[$tab] = AltiriaSMS($allTablesNumbers[$tab], $ms, "dvargas", false);
					// Save logs
					@error_log(PHP_EOL.PHP_EOL.$tab." : ".$ms.PHP_EOL.$allTablesNumbers[$tab].PHP_EOL.$resp[$tab], 3, "/home/thinkclo/public_html/redh/sms.log");
					// Return response on manual cron run
					echo $tab.": ".$ms."</br>".$allTablesNumbers[$tab]."</br>";
				}
			}
			// Update pending messages based on the total amount of specific messages sent on this iteraction
			$updateSMS = $oMySQL->executeSQL('UPDATE ti_sms SET messages = messages-'.$totalSentSMSSpecific.' WHERE id=2');	
		}
		// Sets the total remined messages after the total station and specific table messages sent
		$totalRemainMessages = $smsCount["messages"] - $totalSentSMSSpecific - $totalSentSMSAll;
		// Sets the total sent messages
		$totalMessages = $totalSentSMSSpecific + $totalSentSMSAll;
		// Save logs
		@error_log(PHP_EOL.PHP_EOL.$totalMessages."->".$smsCount["messages"].":".$totalRemainMessages, 3, "/home/thinkclo/public_html/redh/sms.log");
		// Return response on manual cron run
		echo $totalMessages."->".$smsCount["messages"].":".$totalRemainMessages."</br>";
	}else if(intval($smsCount["messages"])<30){
		// Send out of messages warning
		$outOfMessagesMsg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message.". Sus mensajes se están agotando, solo le quedan xx, comuníquese con Think Cloud Group para comprar un paquete adicional.";
	
		$cantidad = 2;
		if(strlen($outOfMessagesMsg) > 160){
			$cantidad = 4;	
		}
		
		if(intval($smsCount["messages"])>$cantidad){
			
	
			$totalRemainMessages = intval($smsCount["messages"]) - $cantidad;
			$outOfMessagesMsg = $dt->format("Y-m-d H:i:s")." Alarma: ".$Message.". Sus mensajes se están agotando, solo le quedan ".$totalRemainMessages.", comuníquese con Think Cloud Group para comprar un paquete adicional.";	
			
			
			$outOfMessages = "573136355940,573234335384";
			$updateSMS = $oMySQL->executeSQL('UPDATE ti_sms SET messages = '.$totalRemainMessages.' WHERE id=2');
			$resp["outofmessages"] = AltiriaSMS($outOfMessages, $outOfMessagesMsg, "dvargas", false);
			@error_log(PHP_EOL.PHP_EOL."OUT OF MESSAGES: ".$outOfMessagesMsg.PHP_EOL.$resp["outofmessages"], 3, "/home/thinkclo/public_html/redh/sms.log");
			echo "OUT OF MESSAGES: ".$msg."</br>".$resp["allstations"]."</br>";	
		}else{
			
			$response = $dt->format("Y-m-d H:i:s")." Alarma(s) NO ENVIADAS POR FALTA DE SALDO: ".$Message."Visítanos redhidro.org";
			@error_log(PHP_EOL.PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");
			echo $response."</br></br>";			
		}
		

	}else{
		$response = $dt->format("Y-m-d H:i:s")." Alarma(s) NO ENVIADAS POR FALTA DE SALDO: ".$Message."Visítanos redhidro.org";
		@error_log(PHP_EOL.PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");
		echo $response."</br></br>";
	}
}else{
	//$response = $dt->format("Y-m-d H:i:s")." No hay alarmas pendientes para enviar. Visítanos redhidro.org";
	//error_log(PHP_EOL.PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");
	echo "</br>There is no alarm to be sent</br>";
}
?>