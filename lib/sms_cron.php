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

global $Message;

mysql_query("set names 'utf8'");
// Inicializa variables
$AccountID="CI00112133";
$Email="darmandovargas@gmail.com";
$Password="123456"; 
//$Recipient="3108311240";
$Recipient="3136355940";
$Message = "";
			
// Get alarms of estaciones
$estacionesList = $oMySQL->executeSQL('SELECT * FROM estaciones WHERE alarma IS NOT NULL AND alarma != ""');

$alarmUpdateList = array();
//var_dump($estacionesList);
// Iterates the alarms in order to send SMS		
foreach($estacionesList as $el){
	// This will ensure last alarm is sent once an hour
	$now = time();
	// Decode alarm json in order to analyze it
	$alarmJSON = json_decode($el['alarma']);
	// Initialize some needed variables
	$tabla = $el['estNombreTb'];
	$stationName = $el['estNombre'];
	$alarmJSONTemp = array();
	$currentId = 0;
	// Iterates the alarm json in order to send SMS
	foreach ($alarmJSON as $variable => $obj) {
		// Initialize some variables 
		$lessThan = $moreThan = false;
		$last_sms = 0;
		$objTemp = array();
		// Iterates the json of each station
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
		// If the last sms for this specific variable is older than an hour ago, then it check for alarms on this variable
		if($now > $objTemp["last_sms"]+60*60){
			// Check alarm for this variable and add it to the general alarm message
			$response = checkAlarms($oMySQL, $tabla, $stationName, $variable, $lessThan, $moreThan);
			// If there were any alarm detected for this variable then update the last sms variable of the json for the specific variable
			if($response){
				$currentId = $el['id'];
				$objTemp["last_sms"]=time(); 
			}	
		}		
		// In order to get the updated json we keep the alarmUpdateList variable up to date with all variables on each variable iteration until we have it completed
		if($currentId==$el['id']){
			$alarmJSONTemp[$variable] = $objTemp;
			$alarmUpdateList[$el['id']] = json_encode($alarmJSONTemp); 
		}	
	}
}

// Based on the list collected on the last iteration we update the json of the stations with last sms date of each alarm we sent
if(!empty($alarmUpdateList)){
	foreach ($alarmUpdateList as $key => $value) {
		$updateLastVariableAlarm = $oMySQL->executeSQL('UPDATE estaciones SET alarma = \''.$value.'\' WHERE id='.intval($key));
	}	
}
//echo "</br>".$Message."hey</br>";

if(!empty($Message)){
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	$msg = $dt->format("Y-m-d H:i:s")." Alarma(s): ".$Message."  Para mayor información visita www.redhidro.org. Powered by Think Cloud Group thinkcloudgroup.com";
	
	$smsCount = $oMySQL->executeSQL('SELECT messages FROM sms WHERE id = 1');	
	
	if(intval($smsCount["messages"])>0){
		if(intval($smsCount["messages"])==2){
			$m = $dt->format("Y-m-d H:i:s")." Su saldo de mensajes de alerta de la Red Hidroclimatológica de Risaralda se ha agotado, por favor comuníquese con su administrador para hacer una recarga. Mensaje enviado por Think Cloud Group http://www.thinkcloudgroup.com";
			echo $m."</br></br>";
			@error_log(PHP_EOL.$m, 3, "/home/thinkclo/public_html/redh/sms.log");
			$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-1 WHERE id=1');
			$answer = SendMessage($AccountID, $Email, $Password, $Recipient, $m);
		}
		@error_log(PHP_EOL.$msg, 3, "/home/thinkclo/public_html/redh/sms.log");
		echo $msg."</br></br>";	
		$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-1 WHERE id=1');
		$answer = SendMessage($AccountID, $Email, $Password, $Recipient, $msg);
	}else{
		$response = $dt->format("Y-m-d H:i:s")." Alarma(s) NO ENVIADAS POR FALTA DE SALDO: ".$Message."  Para mayor información visita www.redhidro.org. Powered by Think Cloud Group thinkcloudgroup.com";
		@error_log(PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");
		echo $response."</br></br>";
	}
}


/**
 * This function will build the SMS to be sent to the system operator(s)
 *
 */
function checkAlarms (&$oMySQL, $tabla, $stationName, $variable, $lessThan='', $moreThan='', $msg=''){
	global $Message;

	$bd_aguas = array("rio_otun_eljordan2", "rio_azul_eljordan", "rio_barbo_pezfresco", "q_volcanes");
	if(in_array($tabla, $bd_aguas)){
		$oMySQL->closeConnection();
		unset($oMySQL);
		$oMySQL = new MySQL();	
		//include ('class.MySQL.php?bd=bd_aguas');
	}else{
		$oMySQL->closeConnection();
		unset($oMySQL);
		$oMySQL = new MySQL();
	}
	
	// Prepare query with last 2 registers from db
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 2";
			
	// Run query
	$rs = $oMySQL->executeSQL($query);
	//echo "</br>".$query."</br>";
	//var_dump($rs);
	// Initialize an average temporal variable
	$averageTemp = false;
	$vals = array();
	$averageCount = 0;
	$response = false;

	if($rs){
		// Iterates the last 2 registers in order to get the sum of both vallues
		foreach ($rs as $v) {
			// if the value is not empty and different to 0 it will sum the value
			if(!empty($v[$variable]) && /*floatval($v[$variable]) != 0 &&*/ strcmp ( $v[$variable] , "-" ) !== 0){

				$averageTemp += floatval($v[$variable]);
				$vals[] = floatval($v[$variable]);
				$averageCount++;	
			}
		}
	}		
	
	// If value is with the inicialized value (false) then do nothing (continue )
	if(!$averageTemp && count($vals) != 2){
		return false;
	}

	// We avoid sms if the last register is lower than second to last register
	if($variable == "nivel"){
		if($vals[0]<$val[1]){
			return false;
		}
	}
	
	// If there is an averageTemp sum, then divide it by 2 in order to get the average
	$average = $vals[0];//$averageTemp / $averageCount;

	$measureSymbol = "";
	// Set measure symbol
	switch ($variable) {
		case 'temperatura': $measureSymbol = "grados C"; $variable = "Temperatura"; $valor=$average;  break;
		case 'precipitacion_real': $measureSymbol = "mm/h"; $variable = "Precipitación"; $valor = $average * 60 / 5; $moreThan = $moreThan * 60 / 5; $customMsg = "alta temperatura,"; break;
		case 'nivel': $measureSymbol = "cm"; $variable = "Nivel"; $valor=$average; break;
		default: $measureSymbol = "grados C"; $valor=$average; break;
	}

	// Process alarm for symbol < (less than)
	if(!empty($lessThan) && $valor < floatval($lessThan)){
		switch ($variable) {
			case 'Temperatura': $customMsg = 'baja temperatura,'; break;
		}	
		//echo $stationName." -> ".$variable.": valor:".$valor." < ".floatval($lessThan)."</br>";
		$Message .= empty($msg)?"'".$stationName."' ".$customMsg." ".$valor." ".$measureSymbol.".  ":$msg;
		$response = true;
	}	

	// Process alarm for symbol > (more than)
	if(!empty($moreThan) && $valor > floatval($moreThan)){
		switch ($variable) {
			case 'Temperatura': $customMsg = 'alta temperatura,'; break;
			case 'Precipitación': $customMsg = "alta precipitación,"; break;
			case 'Nivel': $customMsg = "altos niveles del río,"; break;
		}
		//echo $stationName." -> ".$variable.": valor:".$valor." > ".floatval($moreThan)."</br>";
		$Message .= empty($msg)?"'".$stationName."' ".$customMsg."  ".$valor." ".$measureSymbol.".  ":$msg;
		$response = true;
	}

	return $response;
}
?>