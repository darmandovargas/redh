<?php
	/**
	 * This function will build the SMS to be sent to the system operator(s)
	 *
	 */
	function checkAlarms (&$oMySQL, $tabla, $stationName, $variable, $lessThan='', $moreThan='', $station){
		global $Message, $currentLevelValue, $objTemp, $db_aguasName, $bd_aguasUser, $bd_aguasPassword, $bd_aguasIp, $dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp, $updatedIds;

		$bd_aguas = array("rio_otun_eljordan", "rio_azul_eljordan", "rio_barbo_pezfresco", "q_volcanes");
		
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
		$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 2";
		// Run query
		$rs = $oMySQL->executeSQL($query);
		// Initialize an average temporal variable
		$averageTemp = false;
		$vals = array();
		$averageCount = 0;
		$response = false;

		if($rs){
			// Iterates the last 2 registers in order to get the sum of both vallues
			foreach ($rs as $v) {
				// Obtengo la fecha
				$momento = $v['fecha'].' '.$v['hora'];
				// La pongo en mi zona horaria
				$dateColombia = new DateTime($momento, new DateTimeZone('America/Bogota'));
				// La paso a linux para validarla en el siguiente if de manera que la medición que se está iterando sea de la últimas 2 hora
				$linuxTimeMedicion = strtotime($dateColombia->format('Y-m-d H:i:sP'));
				// if the value is not empty and different to 0 it will sum the value
				if(!empty($v[$variable]) && $linuxTimeMedicion > $twoHoursAgo && strcmp ( $v[$variable] , "-" ) !== 0){ /*floatval($v[$variable]) != 0 &&*/ 
					$averageTemp += floatval($v[$variable]);
					$vals[] = floatval($v[$variable]);
					$averageCount++;	
				}
			}
		}		

		// If value is with the initialized value (false) then do nothing (continue ), OR if the count its different than 2 values, then also continue
		if(!$averageTemp || count($vals) != 2){
			return false;
		}

		// We avoid sms if the last register is lower than second to last register AND
		// if it's "nivel" we need to take into account last alarm value and the slope, so it will 
		// send an alert only if the slope is positive and current value is higher than the last alarm value,
		// if the current value is lower than last alarm value then last alarm  value will be restted
		$activeMoreThan = false;
		
		$lastValue = isset($objTemp["last_value"])?$objTemp["last_value"]:0;

		$firstCicleAlarm = isset($objTemp["first_cicle_alarm"])?$objTemp["first_cicle_alarm"]:0;

		if($variable == "nivel" && intval($lastValue) > 0 && intval($firstCicleAlarm) > 0){
			
			if( $vals[0] < floatval($firstCicleAlarm) ){
				$updatedIds[] = $station;
				$objTemp["last_value"] = 0;	
				$objTemp["first_cicle_alarm"] = 0;	
			}else if($vals[0] > floatval($lastValue)){
				$updatedIds[] = $station;
				$objTemp["last_value"] = $vals[0];	
			}
			//echo $tabla." - ".$variable." - ".$moreThan." - ".$objTemp["last_value"]."</br>";
			$currentLevelValue = $vals[0];

			if( $vals[0] > floatval($lastValue) && $vals[0] > floatval($moreThan) && $vals[1] > floatval($moreThan)){

				if($vals[0]<=$val[1]){
					return false;
				}else{
					// First depreciated solution
					// Check slope of the current measure, if its positive then send the alert, if its negative return false
					// set variables for slope equation
					/*$x1 = 1;
					$x2 = 2;
					$y1 = $vals[1];
					$y2 = $vals[0];
					// get the slope
					$m = ($y1-$y2)/($x1-$x2);
					if($m>0){
						// Possitive slope
						$activeMoreThan = true;
					}else{
						//echo "NEGATIVE SLOPE".$tabla." - ".$variable." - ".$moreThan." - ".$objTemp["last_value"]."</br>";
						// Negative or null slope
						return false;
					}*/
					//$vals[0]
					// Second solution, first cicle alarm value and last value
					if($vals[0] > floatval($lastValue)){
						// Possitive slope
						$activeMoreThan = true;
					}else{
						return false;
					}
				}
			}else{
				//echo "ELSE".$tabla." - ".$variable." - ".$moreThan." - ".$objTemp["last_value"]."</br>";
				return false;
			}
		}else{
			//echo "ELSE2".$tabla." - ".$variable." - ".$moreThan." - ".$objTemp["last_value"]."</br>";
			if($vals[0] > floatval($moreThan) && $vals[1] > floatval($moreThan)){
				if($vals[0]<=$val[1]){
					return false;
				}
			}else{
				return false;
			}
		}
		
		// If there is an averageTemp sum, then divide it by 2 in order to get the average
		$average = $vals[0];//$averageTemp / $averageCount;

		$measureSymbol = "";
		// Set measure symbol
		switch ($variable) {
			case 'temperatura': $measureSymbol = "grados C"; 
								$variable = "Temperatura"; 
								$valor=$average;  
							break;
			case 'precipitacion_real': $measureSymbol = "mm/h"; 
									   $variable = "Precipitación"; 
									   $valor = $average * 60 / 5; 
									   $moreThan = $moreThan * 60 / 5; 
									   $customMsg = "alta temperatura,"; 
									  break;
			case 'nivel': $measureSymbol = "cm"; 
						  $variable = "Nivel"; 
						  $valor=$average; 
						 break;
			default: $measureSymbol = "grados C"; 
					 $valor=$average; 
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
				case 'Nivel': $customMsg = "altos niveles del río,";  
							  // Sets first cicle alarm value
							  if(!$activeMoreThan){
							  	$objTemp["first_cicle_alarm"]=$valor; 	
							  }
							break;
			}
			
			//echo $stationName." -> ".$variable.": valor:".$valor." > ".floatval($moreThan)."</br>";
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