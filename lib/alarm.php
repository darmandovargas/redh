<?php
	/**
	 * This function will build the SMS to be sent to the system operator(s)
	 *
	 */
	function checkAlarms (&$oMySQL, $tabla, $stationName, $variable, $lessThan='', $moreThan='', $stationId){
		global $Message, $objTemp, $db_aguasName, $bd_aguasUser, $bd_aguasPassword, $bd_aguasIp, $dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp, $updatedIds;
		// Create a list of the tables that are on the second database
		$bd_aguas = array("tst_san_juan", "tst_rio_azul_eljordan", "tst_rio_otun_eljordan", "tst_rio_barbo_pezfresco", "tst_q_volcanes");
		// If the current alarm we are checking is on the array then the conexion must be on the second database, otherway use the first one, the UTP
		if(in_array($tabla, $bd_aguas)){
			$oMySQL->closeConnection();
			unset($oMySQL);
			$oMySQL = new MySQL($db_aguasName, $bd_aguasUser, $bd_aguasPassword, $bd_aguasIp);	
		}else{
			$oMySQL->closeConnection();
			unset($oMySQL);
			$oMySQL = new MySQL($dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp);
		}
		// Get current time less than two hours in my timezone
		$dt = new DateTime('', new DateTimeZone('America/Bogota'));
		$dt->sub(new DateInterval('PT7200S'));
		$twoHoursAgo = $dt->format('Y-m-d H:i:sP');
		// convert two hours ago in linux time
		$twoHoursAgo = strtotime($twoHoursAgo);
		// Prepare query with last 2 registers from db
		$query = "SELECT * FROM ".$tabla." ORDER BY stationTime DESC LIMIT 2";
		// Run query
		$rs = $oMySQL->executeSQL($query);
		
		//print_r($rs);
		//echo "</br>---------------------------</br>";
		// Initialize some variables
		$vals = array();
		$response = false;
		// This is for the specific case of river flow where we need three different types of alarms, yellow, orange and red (f.i: riverFlow-yellow)
		$variableTmp = explode("-", $variable);
		$variable = $variableTmp[0];
		$alarmType = isset($variableTmp[1]) ? $variableTmp[1] : "";
		
		// If there are results on the query then iterate it
		if($rs){
			// Iterates the last 2 registers in order to get the sum of both values
			foreach ($rs as $v) {
				// Obtengo la fecha
				$momento = $v['statiomTime'];
				// La pongo en mi zona horaria
				$dateColombia = new DateTime($momento, new DateTimeZone('America/Bogota'));
				// La paso a linux para validarla en el siguiente if de manera que la medición que se está iterando sea de las últimas 2 horas
				$linuxTimeMedicion = strtotime($dateColombia->format('Y-m-d H:i:sP'));
				// if the value is not empty and different to 0 it will sum the value				
				if(!empty($v[$variable]) && $linuxTimeMedicion > $twoHoursAgo && strcmp ( $v[$variable] , "-" ) !== 0){ /*floatval($v[$variable]) != 0 &&*/ 
					$vals[] = floatval($v[$variable]);						
				}
			}
		}		
		// If the count on values taken from database are different than 2, then continue with the cron stations iteration
		if(count($vals) != 2){
			return false;
		}
		// Initialize last and second to last variables for the rest of the function
		$currentValue = $vals[0];
		$penultimateValue = $vals[1];

		// We avoid sms if the last register is lower than second to last register AND
		// if it's "riverFlow" we need to take into account last alarm value and the slope, so it will 
		// send an alert only if the slope is positive and current value is higher than the last alarm value,
		// if the current value is lower than last alarm value then last alarm value will be resetted with zero
		$activeMoreThan = false;
		// Set last value registered on the alarm json, if it does not exist initialize it with zero.
		$lastValue = isset($objTemp["last_value"]) ? $objTemp["last_value"] : 0;
		// Set 
		$firstCicleAlarm = isset($objTemp["first_cicle_alarm"]) ? $objTemp["first_cicle_alarm"] : 0;
		// If we are evaluating the rierFlow, and last value and first cicle alarm are more than zero 
		if($variable == "level" && intval($lastValue) > 0 && intval($firstCicleAlarm) > 0){
			// If the last value is below the alarm value then reset the variables
			if( $currentValue < floatval($moreThan) ){				
				$objTemp["last_value"] = 0;	
				$objTemp["first_cicle_alarm"] = 0;	
			}else if($currentValue > floatval($lastValue)){
				// If the last value in database is greater than the last value registered on the last alarm
				$objTemp["last_value"] = $currentValue;	
			}
			// Add the id to the updateIds array in order to override json value for the current alarm
			$updatedIds[] = $stationId;
			// If the last value is greater than last value registered in the alarm json and is greater than the alarm value and the second to last value is graeter than the alarm value
			if( $currentValue > floatval($lastValue) && $currentValue > floatval($moreThan) && $penultimateValue > floatval($moreThan)){
				// if last value is below the second to last value it means the river flow is decreasing so avoid the alarm
				if($currentValue <= $penultimateValue){
					return false;
				}else if( !empty($lessThan) && $currentValue > floatval($lessThan) && $penultimateValue > floatval($lessThan) ){
					return false;
				}else {
					// Second solution, first cicle alarm value and last value
					// If the last value is over the last registered value on the alarm json, 
					// set $activeMoreThan as true, otherway avoid the alarm
					if($currentValue > floatval($lastValue)){
						// Possitive slope
						$activeMoreThan = true;
					}else {
						return false;
					}
				}
			}else{
				return false;
			}
		}else{
			// If the last and second to last value is over the alarm value, otherway avoid the alarm
			if($currentValue > floatval($moreThan) && $penultimateValue > floatval($moreThan)){
				// If the last value is less than the second to last value it means it is going down, then avoid the alarm
				if($currentValue <= $penultimateValue){
					return false;
					//If there is a less than value, check if the last and second to last value are below the less than value, other way avoid the alarm
				}else if( !empty($lessThan) && $currentValue > floatval($lessThan) && $penultimateValue > floatval($lessThan) ){
					return false;
				}
			}else{
				return false;
			}
		}
		
		$measureSymbol = "";
		// Set measure symbol
		switch ($variable) {
			case 'temperature': $measureSymbol = "grados C"; 
								$variable = "Temperatura"; 
								$valor = round($currentValue,1);  
							break;
			case 'realPrecipitation': $measureSymbol = "mm/h"; 
									   $variable = "Precipitación"; 
									   $valor = $currentValue * 60 / 5; 
									   $moreThan = $moreThan * 60 / 5; 
									   $customMsg = "alta temperatura,"; 
									  break;
			case 'level': $measureSymbol = "cm"; 
						  $variable = "Nivel"; 
						  $valor = round($currentValue); 
						 break;
			default: $measureSymbol = ""; 
					 $valor = $currentValue; 
					break;
		}

		// Process alarm for symbol < (less than)
		/*
		Depreciated, there are no more less than conditions
		if(!empty($lessThan) && $valor < floatval($lessThan)){
			switch ($variable) {
				case 'Temperatura': $customMsg = 'baja temperatura,'; break;
			}	
			//echo $stationName." -> ".$variable.": valor:".$valor." < ".floatval($lessThan)."</br>";
			$Message .= empty($msg)?"'".$stationName."' ".$customMsg." ".$valor." ".$measureSymbol.".  ":$msg;
			setMessage($tabla,"'".$stationName."' ".$customMsg." ".$valor." ".$measureSymbol.". ");
			$response = true;
		}*/	

		// Process alarm for symbol > (more than)
		if( (!empty($moreThan) && $valor > floatval($moreThan)) || $activeMoreThan ){
			switch ($variable) {
				case 'Temperatura': $customMsg = 'alta temperatura,'; break;
				case 'Precipitación': $customMsg = "alta precipitación,"; break;
				case 'Nivel': $customMsg = " alerta  ".$alarmType." de nivel del río,";  
							// Sets first cicle alarm value
							if(!$activeMoreThan){
								$updatedIds[] = $stationId;
							  	$objTemp["first_cicle_alarm"]=$valor; 
							  	$objTemp["last_value"] = $valor;		
							}
							break;
			}
			
			$Message .= empty($msg)?"'".$stationName."' ".$customMsg."  ".$valor." ".$measureSymbol.".  ":$msg;
			setMessage($tabla,"'".$stationName."' ".$customMsg." ".$valor." ".$measureSymbol.". ");
			$response = true;
		}

		return $response;
	}

	function setMessage($table, $ms){
		global $specificMessage;
		$specificMessage[$table] .= $ms;
	}
?>