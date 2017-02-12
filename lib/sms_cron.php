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
include_once ('class.MySQL.php');

// Get sms libs
	include_once('sms.php');

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
// Iterates the alarms in order to send SMS		
foreach($estacionesList as $el){
	// This will ensure last alarm is sent once an hour
	$now = time();
	$lastAlarm = $el['ultima_alarma']+60*60;
	if($now > $lastAlarm) {
		// Decode alarm json in order to analyze it
		$alarmJSON = json_decode($el['alarma']);
		$tabla = $el['estNombreTb'];
		$stationName = $el['estNombre'];
		// Iterates the alarm json in order to send SMS
		foreach ($alarmJSON as $variable => $obj) {
			$lessThan = $moreThan = false;
			foreach ($obj as $mathSymbol => $symbolValue) {
				
				switch ($mathSymbol) {
					case '<':$lessThan=$symbolValue;	break;
					case '>':$moreThan=$symbolValue;  break;
					default:break;
				} 
			}
			// This function will perform the SMS send if is the case			
			checkAlarms($oMySQL, $tabla, $stationName, $variable, $lessThan, $moreThan);
		}
		// This will update the last alarm action so we can do it only once an hour
		$updateLastAlarm = $oMySQL->executeSQL('UPDATE estaciones SET ultima_alarma = '.time().' WHERE id='.$el["id"]);	
	}
}

if(!empty($Message)){
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	$msg = $dt->format("Y-m-d H:i:s")." Alarmas: ".$Message." Un mensaje enviado desde www.redh.org por Think Cloud Group www.thinkcloudgroup.com";
	
	$smsCount = $oMySQL->executeSQL('SELECT messages FROM sms WHERE id = 1');	
	
	if(intval($smsCount["messages"])>0){
		if(intval($smsCount["messages"])==2){
			$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-1 WHERE id=1');
			$m = $dt->format("Y-m-d H:i:s")." Su saldo de mensajes de alerta de la Red Hidroclimatológica de Risaralda se ha agotado, por favor comuníquese con su administrador para hacer una recarga. Mensaje enviado por Think Cloud Group http://www.thinkcloudgroup.com";
			echo $m;
			$answer = SendMessage($AccountID, $Email, $Password, $Recipient, $m);
		}
		//var_dump($smsCount["messages"]);
		$updateSMS = $oMySQL->executeSQL('UPDATE sms SET messages = messages-1 WHERE id=1');
		echo $msg;	
		$answer = SendMessage($AccountID, $Email, $Password, $Recipient, $msg);
	}else{
		echo $dt->format("Y-m-d H:i:s")." Alarmas NO ENVIADAS POR FALTA DE SALDO: ".$Message." Un mensaje enviado desde www.redh.org por Think Cloud Group www.thinkcloudgroup.com";;
	}
}


/**
 * This function will build the SMS to be sent to the system operator(s)
 *
 */
function checkAlarms (&$oMySQL, $tabla, $stationName, $variable, $lessThan=false, $moreThan=false, $msg=''){
	global $Message;	
	// Prepare query with last 2 registers from db
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 2";//LIMIT 5
			
	// Run query
	$rs = $oMySQL->executeSQL($query);
	// Initialize an average temporal variable
	$averageTemp = false;
	$averageCount = 0;
	
	if($rs){
		// Iterates the last 2 registers in order to get the sum of both vallues
		foreach ($rs as $v) {
			// if the value is not empty and different to 0 it will sum the value
			if(!empty($v[$variable]) && floatval($v[$variable]) != 0){
				$averageTemp += floatval($v[$variable]);
				$averageCount++;	
			}	
		}
	}		
	
	// If value is with the inicialized value (false) then do nothing (continue )
	if(!$averageTemp){
		return true;
	}
	
	// If there is an averageTemp sum, then divide it by 2 in order to get the average
	$average = $averageTemp / $averageCount;

	// Process alarm for symbol < (less than)
	if($lessThan && $average<floatval($lessThan)){
		$Message .= empty($msg)?"'".$stationName."' ".$variable."  ".$average." < ".$lessThan.". ":$msg;
	}	

	// Process alarm for symbol > (more than)
	if($moreThan && $average>floatval($moreThan)){
		$Message .= empty($msg)?"'".$stationName."' ".$variable."  ".$average." > ".$moreThan.". ":$msg;
	}
}
?>