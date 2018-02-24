<?php 
if(isset($_GET['bd']) && !empty($_GET['bd']) && $_GET['bd'] != "undefined" &&  $_GET['bd'] == 'wunderground'){    
    // Get yesterday date like this 2014-10-03
    $dt = new DateTime('', new DateTimeZone('America/Bogota'));
    $dt->sub(new DateInterval('P31D'));
    $yesterday = $dt->format('Y-m-d');
    $yesterday = explode("-", $yesterday);
    $ano = $yesterday[0];
    $mes = intval($yesterday[1])+1;
    $dia = intval($yesterday[2]);
    $service_url = 'https://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$idEstacion.'&day=22&month='.$mes.'&year='.$ano.'&graphspan=day&format=1';
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }
    curl_close($curl);
    $decoded = json_decode($curl_response);
    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
        die('error occured: ' . $decoded->response->errormessage);
    }
    
    $wundergroundData = array_map("str_getcsv", explode("\n", $curl_response));
    
    $counter=0;
    foreach($wundergroundData as $w){
        
        if($counter>1){
            if(strlen($w[0])>4){
                $fecha = explode("-", $w[0]);
                $ano = $fecha[0];                
                $mes = $fecha[1]-1;                
                $diaHora = explode(" ", $fecha[2]);
                $dia = $diaHora[0];                
                $horaMinutoSegundo = explode(":", $diaHora[1]);                
                $hora = $horaMinutoSegundo[0];
                $min = $horaMinutoSegundo[1];
                $seg = $horaMinutoSegundo[2];                
               
                
                $estInfo["temperature"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")", floatval($w[1]) );
                
                $estInfo["presure"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[3]));
                
                $estInfo["humidity"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[8]));
                
                $estInfo["realPrecipitation"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[9]));
                
                $estInfo["radiation"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[13]));
                
                $estInfo["windSpeed"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[6]));
                
                $estInfo["windDirection"][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($w[4]));
                
                
            }
        }        
        $counter++;
    }
    
    $serieNew["temperature"] = json_encode(array("data" => $estInfo["temperature"]));
    
    $serieNew["presure"] = json_encode(array("data" => $estInfo["presure"]));
    
    $serieNew["humidity"] = json_encode(array("data" => $estInfo["humidity"]));
    
    $serieNew["realPrecipitation"] = json_encode(array("data" => $estInfo["realPrecipitation"]));
    
    $serieNew["radiation"] = json_encode(array("data" => $estInfo["radiation"]));
    
    $serieNew["windSpeed"] = json_encode(array("data" => $estInfo["windSpeed"]));
    
    $serieNew["windDirection"] = json_encode(array("data" => $estInfo["windDirection"]));
    
    $variables = array("temperature", "presure", "humidity", "realPrecipitation", "radiation", "windSpeed", "windDirection");
}
?>