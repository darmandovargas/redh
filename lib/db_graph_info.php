<?php 
// Inicializa variables
$estationTable = $privateEstationTable = array();

// Obtiene el tipo de estacion
$tipoEstacion = $_GET['tipo'];
// Carpeta
$carpeta = "";
if(isset($_GET['carpeta']))
    $carpeta = $_GET['carpeta'];
    
    
    if($tipoEstacion=="SNS"){
        $nombre_estacion = $carpeta;
    }   	
    
    
    // Si la estación existe en la base de datos entonces arme el json para graficar
    if ($idEstacion != "0" && !isset($serieNew["temperature"]) ){//&& $carpeta != "cam") {//
        
        // Inicializa array de variables a sensar y graficar
        $variables = array("temperature", "presure", "humidity", "realPrecipitation", "radiation", "windSpeed", "windDirection", "realETO");
        
        //Temperatura, Precipitación, Humedad Relativa, Radiación Solar, Presión Barométrica, Velocidad y Dirección del Viento
        // Dependiendo del tipo de estación, se debe revisar en estaciones o estacion_sensores
        switch ($tipoEstacion) {
            case 'ECT' :
                $tablaEstaciones = "tdp_stations"; break;
            case 'EHT' :
                $tablaEstaciones = "tdp_stations";
                // Inicializa array de variables a sensar y graficar
                $variables = array("temperature", "realPrecipitation", "level"); break;
            case 'ENT' :
                $tablaEstaciones = "tdp_stations";
                $variables = array("level");
                break;
            case 'EQT' :
                $tablaEstaciones = "tdp_stations";
                $variables = array("riverFlow");
                break;
            case 'EC' :
                $tablaEstaciones = "tdp_stations"; break;
            case 'SN' :
                $tablaEstaciones = "tdp_stations"; break;
            case 'SNS' :
                $tablaEstaciones = "tdp_stations"; break;
            case 'PD' :
                $tablaEstaciones = "tdp_stations";
                // Inicializa array de variables a sensar y graficar
                $variables = array("temperature", "presure", "humidity", "realPrecipitation", "level", "radiation", "windSpeed", "windDirection", "realETO");
                break;
            case 'PDT' :
                $tablaEstaciones = "tdp_stations";
                // Inicializa array de variables a sensar y graficar
                $variables = array("temperature", "realPrecipitation");
                break;
            default :
                $tablaEstaciones = "tdp_stations"; break;
        }
        
        // Obtiene la estación según el tipo que define la tabla
        $query = "SELECT * FROM " . $tablaEstaciones . " WHERE idStation=" . $idEstacion;	//." and activo='true'";

        
        //var_dump($query);
        $est = $oMySQL -> ExecuteSQL($query);
        //var_dump($est);

        
        
        // Obtiene el nombre de la tabla de la estación
        $tabla = $est["tableName"];
		
		//$nombre_estacion = $est["stationName"];
        
        /**
         * Gets variables for area graphs
         */
        // Get yesterday date like this 2014-10-03
        $dt = new DateTime('', new DateTimeZone('America/Bogota'));
        $dt->sub(new DateInterval('P1D'));
        //echo $dt->format('Y-m-d H:i:s');
        //echo "</br></br>";
        $yesterday = $dt->format('Y-m-d');//date("Y-m-d", strtotime("yesterday"));//date("Y-m-d", strtotime("yesterday"));
        // Get data from yesterday ordered from last measure	// AND stationTime <= '2018-01-25'
        $query = "SELECT * FROM " . $tabla . " WHERE stationTime >= '".$yesterday."' ORDER BY stationTime ASC";//LIMIT 5
        
        //var_dump($query);        
        $estacionesInfoSinceYesterday = $oMySQL -> ExecuteSQL($query);

        //var_dump($estacionesInfoSinceYesterday);        
        //echo "</br>".$query."</br></br></br>"; 
        
        //var_dump($estacionesInfoSinceYesterday);        
		//echo "</br></br></br>";
        
        
        $oMySQL -> closeConnection();
        $lastValue = array();
        foreach($variables as $v){
            $lastValue[$v] = 0;
        }
        
        foreach ($estacionesInfoSinceYesterday as $data) {
            $fecha = explode("-", $data["stationTime"]);
            $ano = $fecha[0];
            $mes = $fecha[1]-1;
            
            $diaHora = explode(" ", $fecha[2]);
            $dia = $diaHora[0];
            
            $horaMinutoSegundo = explode(":", $diaHora[1]);
            
            $hora = $horaMinutoSegundo[0];
            $min = $horaMinutoSegundo[1];
            $seg = $horaMinutoSegundo[2];

            // Get values for each variable
            foreach($variables as $v){
                if($data[$v]!="-" && $data[$v] != NULL){
                    $lastValue[$v] = $estInfo[$v][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",round(floatval($data[$v]), 2));
                }/*else{
                    $estInfo[$v][] = ($lastValue[$v]==0)?array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval(0)):$lastValue[$v];
                }*/
            }
        }
        
        // Sets series variables for Area Graph
        foreach($variables as $v){
            //echo $estInfo[$v][0][1];
            // This will patch the bug when the first value is 0, this is because on the db the first value is "-"
            if($estInfo[$v][0][1]==0){
                $estInfo[$v][0] = $estInfo[$v][1];
        }
        // This sets the jsons arrays values
        $serieNew[$v] = json_encode(array("data" => $estInfo[$v]));
        }
        
        // Arrange the variable data for OLD graph
        foreach($variables as $var){
            foreach ($estationTable[$var] as $data) {
                // Obtengo un solo array de datos y uno solo de horas en el eje x
                $jsonData[$var][] = $data["data"];
                $x[] = $data["hora"];
            }
        }

        $nombre_estacion = empty($nombre_estacion)?$est["stationName"]:$nombre_estacion;		
/*
        print_r($serieNew);
        echo "</br></br></br>----------</br></br></br>";
        print_r($jsonData);
        print_r($x);
        echo "</br></br></br>----------</br></br></br>";
        echo $nombre_estacion;
        echo "</br></br></br>----------</br></br></br>";
        */
    }
?>