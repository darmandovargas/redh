<?php
// Obtiene la conexión a la bd
include_once ('../lib/class.MySQL.php');
// Estaciones pÃºblicas
//$publicEstations = array("tb_san_jose", "tb_ellago", "tb_cortaderal", "tb_el_cedral", "tb_san_juan", "tb_el_nudo", "tb_quinchia");
// Inicializa variables
$estationTable = $privateEstationTable = array();
// Obtiene id de la estación
$idEstacion = $_GET['id'];
// Obtiene el tipo de estacion
$tipoEstacion = $_GET['tipo'];
// Si la estación existe en la base de datos entonces arme el json para graficar
if ($idEstacion != 0) {
	// Inicializa array de variables a sensar y graficar
	$variables = array("temperatura", "presion", "humedad", "precipitacion_real", "nivel", "radiacion", "velocidad", "direccion", "evapo_real");
	//Temperatura, Precipitación, Humedad Relativa, Radiación Solar, Presión Barométrica, Velocidad y Dirección del Viento
	// Dependiendo del tipo de estación, se debe revisar en estaciones o estacion_sensores
	switch ($tipoEstacion) {
		case 'ECT' :
			$tablaEstaciones = "estaciones"; break;
		case 'EHT' :
			$tablaEstaciones = "estaciones"; 
			// Inicializa array de variables a sensar y graficar
			//$variables = array("temperatura", "precipitacion", "nivel"); break;
		case 'EC' :
			$tablaEstaciones = "estacion_sensores"; break;
		case 'SN' :
			$tablaEstaciones = "estacion_sensores"; break;
		default :
			$tablaEstaciones = "estaciones"; break;
	}
	// Inicializa variables para la gráfica
	$xAxis = $series = array();
	// TODO: improve code with for's
	
	
	// Obtiene la estación según el tipo que define la tabla
	$query = "SELECT * FROM " . $tablaEstaciones . " WHERE id=" . $idEstacion;	//." and activo='true'";
	$est = $oMySQL -> ExecuteSQL($query);
	// Obtiene el nombre de la estación
	$tabla = $est["estNombreTb"];
	// Obtiene datos de la tabla de la estación del último dí­a (aproximadamente 285 últimos datos)
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 285";//285
	$estacionInfo = $oMySQL -> ExecuteSQL($query);
	$isFirstVal = true;
	$cuentaVeintiCuatro = 0;
	
	//echo count($estationTemperature['temperature'][]);
	
	//$contadorHoras = 0;
	// Itero la informatión de la tabla de la estación
	foreach ($estacionInfo as $data) {
		
		// Obtengo la hora de la medición
		$horaX = substr($data['hora'], 0, 2);		
		// Valido que la hora actual y la anterior sean la misma para sumar al promedio y al contador
		if (intval($ultimaHora) == intval($horaX) || $isFirstVal) {
			
			foreach($variables as $vars){
					if($data[$vars]!="-"){
						$contador[$vars]++;
						$promedio[$vars] += floatval($data[$vars]);
						
					}	
			}	
			$isFirstVal=false;
		} else {
			$cuentaVeintiCuatro++;
			foreach($variables as $var){
				// Si la hora anterior y la actual son diferentes, agrego el valor a un array y
				// renuevo el valor del promedio y el contador
				if($contador[$var]==0)
					$contador[$var] = 1;
				
				
					
				$promedio[$var] = $promedio[$var] / $contador[$var];
				$promedio[$var] = substr($promedio[$var], 0, 4);
				$estationTable[$var][] = array("hora" => intval($ultimaHora), "data" => floatval($promedio[$var]));
				
				$estationTable[$var.'_visible'] = true;
				/*TODO switch visibility to false if all the estation averages are 0 for an specific variable
				 * $cuentaPromedio[] = 
				if(floatval($promedio[$var])>0 && $contadorHoras>24){
					$estationTable[$var.'_visible'] = flase;
					$contadorHoras = 0;
				}else{
					$contadorHoras++;
				}*/				
				$promedio[$var] = $contador[$var] = 0;
				$isFirstVal=true;
				if($data[$var]!="-"){
					$contador[$var]++;
					$promedio[$var] += floatval($data[$var]);				
				}		
			}
		}
		// Actualizo variable de última hora
		$ultimaHora = $horaX;
		if($cuentaVeintiCuatro>=24)
			break;
	}


	/**
	 * Gets variables for area graphs
	 */
	 /*
	 $dt = new DateTime('', new DateTimeZone('America/Bogota')); 
	 echo $dt->format('Y-m-d H:i:s');
	 echo "</br></br>";
	  */ 
	// Get yesterday date like this 2014-10-03
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	$dt->sub(new DateInterval('P1D'));
	//echo $dt->format('Y-m-d H:i:s');
	//echo "</br></br>";
	$yesterday = $dt->format('Y-m-d');//date("Y-m-d", strtotime("yesterday"));//date("Y-m-d", strtotime("yesterday"));
	// Get data from yesterday ordered from last measure	
	$query = "SELECT * FROM " . $tabla . " where fecha >= '".$yesterday."' ORDER BY fecha ASC";//LIMIT 5
	/*
	echo "SELECT * FROM " . $tabla . " where fecha >= '".$yesterday."' ORDER BY fecha DESC";
	echo "</br></br>";
	*/
	$estacionesInfoSinceYesterday = $oMySQL -> ExecuteSQL($query);
	
	$lastValue = array();
	foreach($variables as $v){
		$lastValue[$v] = 0;
	}
	/*
	echo "</br>";
	//var_dump($serieNew['temperatura']);
	echo count($estacionesInfoSinceYesterday['temperatura']);
	echo "</br></br>";
*/
	
	foreach ($estacionesInfoSinceYesterday as $data) {
		$fecha = explode("-", $data["fecha"]);
		$dia = $fecha[2];
		$mes = $fecha[1];
		$ano = $fecha[0];
		$hora = explode(":", $data["hora"]);
		$seg = $hora[2];
		$min = $hora[1];
		$hora = $hora[0];
		// Get values for each variable
		foreach($variables as $v){
			if($data[$v]!="-"){
				$lastValue[$v] = $estInfo[$v][] = array("Date.UTC(".$ano.", ".$mes.", ".$dia.", ".$hora.",".$min.",".$seg.")",floatval($data[$v]));
			}else{
				$estInfo[$v][] = $lastValue[$v]; 
			}	
		}	
	}
	
	/*
	echo "</br>";
	//var_dump($serieNew['temperatura']);
	echo count($estInfo['temperatura']);
	echo "</br></br>";
	*/
	
	// Sets series variables for Area Graph
	foreach($variables as $v){
		// This will patch the bug when the first value is 0, this is because on the db the first value is "-"
		/*if($estInfo[$v][0]==0){
			$estInfo[$v][0] = $estInfo[$v][1];
		}*/
		// This sets the jsons arrays values
		$serieNew[$v] = json_encode(array("data" => $estInfo[$v]));
	}
	//var_dump($serieNew['temperatura']);
	
	
	
	// Arrange the variable data for OLD graph
	foreach($variables as $var){		
		foreach ($estationTable[$var] as $data) {
			// Obtengo un solo array de datos y uno solo de horas en el eje x
			$jsonData[$var][] = $data["data"];
			$x[] = $data["hora"];
		}
	}
	
	//$xAxisTemp = $x;
	$nombre_estacion = $est["estNombre"];
	$xAxis = json_encode(array_reverse($x));

	$emptyTableGraph = "{name: '" . $est["estNombre"] . "', data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}";
/*	
	foreach($variables as $var){
		if(!empty($var)){
			$series[$var] = true;
			if (!empty($jsonData[$var])) {
				$series[$var] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData[$var]), "visible"=>$estationTable[$var.'_visible']));
			} else {
				$series[$var] = $emptyTableGraph;
				$series[$var.'Json'] = false;
			}	
		}
			
	}	
*/
	$series['temperaturaJson'] = $series['presionJson'] = $series['humedadJson'] = $series['precipitacionJson']  = $series['nivelJson'] = $series['radiacionJson'] = $series['velocidadJson'] = $series['direccionJson'] = $series['evapotranspiracionJson'] = true;

	if (!empty($jsonData['temperatura'])) {
		$series['temperatura'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['temperatura']), "visible"=>$estationTable['temperatura_visible']));
	} else {
		$series['temperatura'] = $emptyTableGraph;
		$series['temperaturaJson'] = false;
	}
	
	//var_dump($serieTempe);
	
	if (!empty($jsonData['presion'])) {
		$series['presion'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['presion']), "visible"=>$estationTable['presion_visible'] ) );
	} else {
		$series['presion'] = $emptyTableGraph;
		$series['presionJson'] = false;
	}
	
	if (!empty($jsonData['humedad'])) {
		$series['humedad'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['humedad']), "visible"=>$estationTable['humedad_visible']));
	} else {
		$series['humedad'] = $emptyTableGraph;
		$series['humedadJson'] = false;
	}
	
	if (!empty($jsonData['precipitacion'])) {
		$series['precipitacion'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['precipitacion']), "visible"=>$estationTable['precipitacion_visible']));
	} else {
		$series['precipitacion'] = $emptyTableGraph;
		$series['precipitacionJson'] = false;
	}
	
	if (!empty($jsonData['nivel'])) {
		$series['nivel'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['nivel']), "visible"=>$estationTable['nivel_visible']));
	} else {
		$series['nivel'] = $emptyTableGraph;
		$series['nivelJson'] = false;
	}
	
	if (!empty($jsonData['radiacion'])) {
		$series['radiacion'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['radiacion']), "visible"=>$estationTable['radiacion_visible']));
	} else {
		$series['radiacion'] = $emptyTableGraph;
		$series['radiacionJson'] = false;
	}
	
	//var_dump($jsonData['velocidad']);
	
	if (!empty($jsonData['velocidad'])) {
		$series['velocidad'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['velocidad']), "visible"=>$estationTable['velocidad_visible']));
	} else {
		$series['velocidad'] = $emptyTableGraph;
		$series['velocidadJson'] = false;
	}

	//var_dump($series['velocidad']);

	if (!empty($jsonData['direccion'])) {
		$series['direccion'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['direccion']), "visible"=>$estationTable['direccion_visible']));
	} else {
		$series['direccion'] = $emptyTableGraph;
		$series['direccionJson'] = false;
	}

	//var_dump($series['velocidad']);

	if (!empty($jsonData['evapotranspiracion'])) {
		$series['evapotranspiracion'] = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData['evapotranspiracion']), "visible"=>$estationTable['evapotranspiracion_visible']));
	} else {
		$series['evapotranspiracion'] = $emptyTableGraph;
		$series['evapotranspiracionJson'] = false;
	}
	
	//var_dump($series['direccion']);

	$oMySQL -> closeConnection();

}
?>
<!DOCTYPE html> 
<html>
	<head>
		<meta charset="utf-8">		
		<title>Sky Tabs</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<link rel="stylesheet" href="css/demo.css">
		<link rel="stylesheet" href="css/font-awesome.css">
		<link rel="stylesheet" href="css/sky-tabs.css">
		
		<!--[if lt IE 9]>
			<link rel="stylesheet" href="css/sky-tabs-ie8.css">
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<script src="js/sky-tabs-ie8.js"></script>
		<![endif]-->
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/highcharts.js"></script>
	    <script src="http://code.highcharts.com/modules/exporting.js"></script>
<?php 
	if($idEstacion!=0){ 
?>
	<script>
	$(function () {
		
		var estacion = '<?php echo  $est["estNombre"];?>';
	    
	    // General options for chart    
	    Highcharts.setOptions({
	    	title: {
	    		text: '<?php echo  $est["estNombre"];?>',
	            x: -20 //center
	        },
	        subtitle: {
	          	userHTML:true,
	            text: 'Mediciones últimas 24 horas<br/>Origen: Red Hidroclimatológica de Risaralda',
	            x: -20
	        },            
	        yAxis: {
	        	plotLines: [{
	        		value: 0,
	                width: 1,
	                color: '#808080'
	            }]
	        },
	        legend: {
	        	layout: 'vertical',
	            align: 'right',
	            verticalAlign: 'middle',
	            borderWidth: 0
	        },
	        credits: { style: {right: '10px'} }
	    });
	    
	    temperaturasEstaciones =[<?php echo $series['temperatura']; ?>];
	    presionEstaciones = [<?php echo $series['presion']; ?>];
	    humedadEstaciones = [<?php echo $series['humedad']; ?>];
	    precipitacionEstaciones = [<?php echo $series['precipitacion_real']; ?>];
	    nivelEstaciones = [<?php echo $series['nivel']; ?>];
	    radiacionEstaciones = [<?php echo $series['radiacion']; ?>];    
	    velocidadEstaciones = [<?php echo $series['velocidad']; ?>];
	    direccionEstaciones = [<?php echo $series['direccion']; ?>];
	    evapotranspiracionEstaciones = [<?php echo $series['evapotranspiracion']; ?>];
	    
	   
	    var seriesArea = Array;
	    seriesArea['temperatura'] = [<?php echo $serieNew['temperatura']; ?>];
	    seriesArea['presion'] = [<?php echo $serieNew['presion']; ?>];
	    seriesArea['humedad'] = [<?php echo $serieNew['humedad']; ?>];
	    seriesArea['precipitacion_real'] = [<?php echo $serieNew['precipitacion_real']; ?>];
	    seriesArea['nivel'] = [<?php echo $serieNew['nivel']; ?>];
	    seriesArea['radiacion'] = [<?php echo $serieNew['radiacion']; ?>];
	    seriesArea['velocidad'] = [<?php echo $serieNew['velocidad']; ?>];
	    seriesArea['direccion'] = [<?php echo $serieNew['direccion']; ?>];
	    seriesArea['evapo_real'] = [<?php echo $serieNew['evapo_real']; ?>];
	    
	    
	    testUTF2 = Date.UTC(2010, 09, 13);//Date.UTC(2014, 9, 5);//,  18,19,0     0, 0, 0
	    console.log(testUTF2);
	    
	    
	    
	    
	    //console.log(testUTF2.getFullYear()+"-"+testUTF2.getDate()+"-"+testUTF2.getDay()+" "+testUTF2.getHours()+":"+testUTF2.getMinutes()+":"+testUTF2.getSeconds());
	    //console.log(new Date(testUTF2));
	   
	    
	    var xAxisValues =  <?php echo $xAxis; ?>;
		
		createGraphArea('Presión Barométrica (mm/mg)', 'mm/mg', 'container-presion', seriesArea['presion']);
		createGraphArea('Humedad Relativa (%)', '%', 'container-humedad', seriesArea['humedad']);
		createGraphArea('Nivel (cm)', 'cm', 'container-nivel', seriesArea['nivel']);
		createGraphArea('Precipitación (mm)', 'mm', 'container-precipitacion_real', seriesArea['precipitacion_real']);
		<?php
		//if($series['nivelJson'] && $tipoEstacion=='EHT'){
		?>
			createGraphArea('Radiación Solar ( W/m²)', 'W/m²', 'container-radiacion', seriesArea['radiacion']);
		<?php
		//}
		?>
		createGraphArea('Velocidad (m/s)', 'm/s', 'container-velocidad', seriesArea['velocidad']);
		createGraphArea('Dirección (°)', '°', 'container-direccion', seriesArea['direccion']);
		createGraphArea('Evapotranspiracion (mm)', 'mm', 'container-evapo_real', seriesArea['evapo_real']);
		createGraphArea('Temperatura (°C)', '°C', 'container-temperatura', seriesArea['temperatura']);
		
		/**
	     * Create a graph based on parameters
	     * @param string
	     */
	    function createGraphArea(title, unit, container, seriesValues, minYValue){
	    	//JSONArray json = new JSONArray();
    		//Collection<JSONObject> items = new ArrayList<JSONObject>();
    		//JSONObject item1 = new JSONObject();
    		var new_obj = [{"data": []}];
    		console.log(title);
    		console.log(seriesValues);
	    	$.each(seriesValues[0].data, function(key, value) {
	    		time = eval(value[0]);
	    		//console.log(time);
	    		new_obj[0].data.push([time, value[1]]);
			    //item1.put(eval(value[0]), value[1]);
			    //items.add(item1);
			    //item1.put("aTargets", new JSONArray(0));
			    //items.add(item1);
	    		//newSerie = [eval(value[0]), value[1]];
	    		//json.put(eval(value[0]), value[1]);
	    		//seriesValues[0].data[key] = eval(value[0]);
	    		//seriesValues[0].data[key] = [eval(value[0]), value[1]];
	    		//newSerie[0].data.push([eval(value[0]), value[1]]);
	    		//console.log(key);
	     		//console.log(eval(value[0]));
				//if(value==0){
				//setMinTemp = true;
				//return false;
				//}
			});
			console.log(new_obj[0].data);
			//console.log(seriesValues);
	    	// Parche para el bug del primer valor cero cuando en la bd es "-" 
			if(minYValue==undefined){
				maxMinPresion = getMaxMinVal(seriesValues);
				yAxisJSON = {title: {text: title},min:maxMinPresion.min};				
			}else{
				yAxisJSON = {title: {text: title},min:minYValue};
			}
			
			dataCount = Object.keys(seriesValues[0].data).length;
			//console.log(dataCount);
			//Lineal regresion for point interval factor value
			//pointIntervalFactor = dataCount * 3.63 / 434;
			//pointIntervalFactor = 3.63,//434
			//pointIntervalFactor = 4.0183 + -0.0009 * dataCount;
			//pointIntervalFactor = 3.978 + -0.0008 * dataCount;
			if(estacion=="Quinchia Seafield"){
				//pointIntervalFactor = 3.9976 + -0.0008 * dataCount;
				//pointIntervalFactor = 3.4757 + 0.0002 * dataCount;
				pointIntervalFactor = 3.45 + 0.0002 * dataCount;
			}else{
				pointIntervalFactor = 3.44 + 0.0002 * dataCount;
				//pointIntervalFactor = 3.4757 + 0.0002 * dataCount;				
				//Manually adjusted formula for point interval factor
				//pointIntervalFactor = 3.959 + -0.0008 * dataCount;	
			}
			//console.log(estacion);
			
			//console.log("pointIntervalFactor:"+pointIntervalFactor);
			var d = new Date();
			//console.log(d);
			var currentYear = d.getFullYear();
			//console.log(currentYear);
			var currentMonth = d.getMonth();
			//console.log(currentMonth);
			var currentDay = d.getDate();
			//console.log(currentDay);
			
			
			
			// Opciones Generales Gráfico
		  	opcionesGenerales = { 
		  		yAxis: yAxisJSON,
		    	tooltip: {
		    		valueSuffix: unit
		    	},
		    	xAxis: {
		            type: 'datetime',
		            //categories:[Date.UTC(currentYear, currentMonth, currentDay-1),Date.UTC(currentYear, currentMonth, currentDay) ],//Date.UTC(currentYear, currentMonth, currentDay-1);
		            tickInterval: 24 * 3600 * 1000/*,
		            minRange: 2 * 24 * 3600000*/
		        },
		        plotOptions: {
		            area: {
		                fillColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
		                    stops: [
		                        [0, Highcharts.getOptions().colors[0]],
		                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
		                    ]
		                },
		                marker: {
		                    radius: 2
		                },
		                lineWidth: 1,
		                states: {
		                    hover: {
		                        lineWidth: 1
		                    }
		                },
		                threshold: null
		            }
		        },
		        series: [{
		            type: 'area',
		            name: title,
		            pointInterval: 24 * 3600 * 3.579,//pointIntervalFactor,//3.579,
		            //pointInterval: 24 * 3600 * pointIntervalFactor,//3.579,
		            //434/3.63 = 119.56, 491/3.579 = 137.189, 502/3.57389, 504/3.5736, 305/3.525, 405/3.525
		            // 434 es el 11.6% de 491
		            // 3.63 y 3.579 difieren en 1.4%
		            pointStart: Date.UTC(currentYear, currentMonth, currentDay-1),
		            //pointStart: Date.UTC(2014, 9, 4),
		            //pointEnd: Date.UTC(2014, 09, 05,  20,40,0),
		            data: new_obj[0].data//seriesValues[0].data
		        }]
		        
			};
			
			//console.log(Date());
			
		  	// Opciones Gráfico
		  	opcionesGrafico = {
		    	chart: {
		      		renderTo: container         
		    	},
		    	series: seriesValues
		  	};
		  	
		  	opcionesGrafico = jQuery.extend(true, {}, opcionesGenerales, opcionesGrafico);
		  	grafico = new Highcharts.Chart(opcionesGrafico);
	    	
	    	return grafico;
	    }

/*
		$('#container-temperatura').highcharts({
        chart: {
            zoomType: 'x'
        },
        title: {
            text: '<?php echo  $est["estNombre"];?>'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime',
            minRange: 1 * 24 * 3600000 // fourteen days
        },
        yAxis: {
            title: {
                text: 'Exchange rate'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Temperatura',
            // This is 
            pointInterval: 24 * 3600 * 3.63,//434
            pointStart: Date.UTC(2014, 09, 3),
            data: temp[0].data
        }]
    });
 */   
    
    
    

testJSON = [
                0.8446, 0.8445, 0.8444, 0.8451,    0.8418, 0.8264,    0.8258, 0.8232,    0.8233, 0.8258,
                0.8283, 0.8278, 0.8256, 0.8292,    0.8239, 0.8239,    0.8245, 0.8265,    0.8261, 0.8269,
                0.8273, 0.8244, 0.8244, 0.8172,    0.8139, 0.8146,    0.8164, 0.82,    0.8269, 0.8269,
                0.8269, 0.8258, 0.8247, 0.8286,    0.8289, 0.8316,    0.832, 0.8333,    0.8352, 0.8357,
                0.8355, 0.8354, 0.8403, 0.8403,    0.8406, 0.8403,    0.8396, 0.8418,    0.8409, 0.8384,
                0.8386, 0.8372, 0.839, 0.84, 0.8389, 0.84, 0.8423, 0.8423, 0.8435, 0.8422,
                0.838, 0.8373, 0.8316, 0.8303,    0.8303, 0.8302,    0.8369, 0.84, 0.8385, 0.84,
                0.8401, 0.8402, 0.8381, 0.8351,    0.8314, 0.8273,    0.8213, 0.8207,    0.8207, 0.8215,
                0.8242, 0.8273, 0.8301, 0.8346,    0.8312, 0.8312,    0.8312, 0.8306,    0.8327, 0.8282,
                0.824, 0.8255, 0.8256, 0.8273, 0.8209, 0.8151, 0.8149, 0.8213, 0.8273, 0.8273,
                0.8261, 0.8252, 0.824, 0.8262, 0.8258, 0.8261, 0.826, 0.8199, 0.8153, 0.8097,
                0.8101, 0.8119, 0.8107, 0.8105,    0.8084, 0.8069,    0.8047, 0.8023,    0.7965, 0.7919,
                0.7921, 0.7922, 0.7934, 0.7918,    0.7915, 0.787, 0.7861, 0.7861, 0.7853, 0.7867,
                0.7827, 0.7834, 0.7766, 0.7751, 0.7739, 0.7767, 0.7802, 0.7788, 0.7828, 0.7816,
                0.7829, 0.783, 0.7829, 0.7781, 0.7811, 0.7831, 0.7826, 0.7855, 0.7855, 0.7845,
                0.7798, 0.7777, 0.7822, 0.7785, 0.7744, 0.7743, 0.7726, 0.7766, 0.7806, 0.785,
                0.7907, 0.7912, 0.7913, 0.7931, 0.7952, 0.7951, 0.7928, 0.791, 0.7913, 0.7912,
                0.7941, 0.7953, 0.7921, 0.7919, 0.7968, 0.7999, 0.7999, 0.7974, 0.7942, 0.796,
                0.7969, 0.7862, 0.7821, 0.7821, 0.7821, 0.7811, 0.7833, 0.7849, 0.7819, 0.7809,
                0.7809, 0.7827, 0.7848, 0.785, 0.7873, 0.7894, 0.7907, 0.7909, 0.7947, 0.7987,
                0.799, 0.7927, 0.79, 0.7878, 0.7878, 0.7907, 0.7922, 0.7937, 0.786, 0.787,
                0.7838, 0.7838, 0.7837, 0.7836, 0.7806, 0.7825, 0.7798, 0.777, 0.777, 0.7772,
                0.7793, 0.7788, 0.7785, 0.7832, 0.7865, 0.7865, 0.7853, 0.7847, 0.7809, 0.778,
                0.7799, 0.78, 0.7801, 0.7765, 0.7785, 0.7811, 0.782, 0.7835, 0.7845, 0.7844,
                0.782, 0.7811, 0.7795, 0.7794, 0.7806, 0.7794, 0.7794, 0.7778, 0.7793, 0.7808,
                0.7824, 0.787, 0.7894, 0.7893, 0.7882, 0.7871, 0.7882, 0.7871, 0.7878, 0.79,
                0.7901, 0.7898, 0.7879, 0.7886, 0.7858, 0.7814, 0.7825, 0.7826, 0.7826, 0.786,
                0.7878, 0.7868, 0.7883, 0.7893, 0.7892, 0.7876, 0.785, 0.787, 0.7873, 0.7901,
                0.7936, 0.7939, 0.7938, 0.7956, 0.7975, 0.7978, 0.7972, 0.7995, 0.7995, 0.7994,
                0.7976, 0.7977, 0.796, 0.7922, 0.7928, 0.7929, 0.7948, 0.797, 0.7953, 0.7907,
                0.7872, 0.7852, 0.7852, 0.786, 0.7862, 0.7836, 0.7837, 0.784, 0.7867, 0.7867,
                0.7869, 0.7837, 0.7827, 0.7825, 0.7779, 0.7791, 0.779, 0.7787, 0.78, 0.7807,
                0.7803, 0.7817, 0.7799, 0.7799, 0.7795, 0.7801, 0.7765, 0.7725, 0.7683, 0.7641,
                0.7639, 0.7616, 0.7608, 0.759, 0.7582, 0.7539, 0.75, 0.75, 0.7507, 0.7505,
                0.7516, 0.7522, 0.7531, 0.7577, 0.7577, 0.7582, 0.755, 0.7542, 0.7576, 0.7616,
                0.7648, 0.7648, 0.7641, 0.7614, 0.757, 0.7587, 0.7588, 0.762, 0.762, 0.7617,
                0.7618, 0.7615, 0.7612, 0.7596, 0.758, 0.758, 0.758, 0.7547, 0.7549, 0.7613,
                0.7655, 0.7693, 0.7694, 0.7688, 0.7678, 0.7708, 0.7727, 0.7749, 0.7741, 0.7741,
                0.7732, 0.7727, 0.7737, 0.7724, 0.7712, 0.772, 0.7721, 0.7717, 0.7704, 0.769,
                0.7711, 0.774, 0.7745, 0.7745, 0.774, 0.7716, 0.7713, 0.7678, 0.7688, 0.7718,
                0.7718, 0.7728, 0.7729, 0.7698, 0.7685, 0.7681, 0.769, 0.769, 0.7698, 0.7699,
                0.7651, 0.7613, 0.7616, 0.7614, 0.7614, 0.7607, 0.7602, 0.7611, 0.7622, 0.7615,
                0.7598, 0.7598, 0.7592, 0.7573, 0.7566, 0.7567, 0.7591, 0.7582, 0.7585, 0.7613,
                0.7631, 0.7615, 0.76, 0.7613, 0.7627, 0.7627, 0.7608, 0.7583, 0.7575, 0.7562,
                0.752, 0.7512, 0.7512, 0.7517, 0.752, 0.7511, 0.748, 0.7509, 0.7531, 0.7531,
                0.7527, 0.7498, 0.7493, 0.7504, 0.75, 0.7491, 0.7491, 0.7485, 0.7484, 0.7492,
                0.7471, 0.7459, 0.7477, 0.7477, 0.7483, 0.7458, 0.7448, 0.743, 0.7399, 0.7395,
                0.7395, 0.7378, 0.7382, 0.7362, 0.7355, 0.7348, 0.7361, 0.7361, 0.7365, 0.7362,
                0.7331, 0.7339, 0.7344, 0.7327, 0.7327, 0.7336, 0.7333, 0.7359, 0.7359, 0.7372,
                0.736, 0.736, 0.735, 0.7365, 0.7384, 0.7395, 0.7413, 0.7397, 0.7396, 0.7385,
                0.7378, 0.7366, 0.74, 0.7411, 0.7406, 0.7405, 0.7414, 0.7431, 0.7431, 0.7438,
                0.7443, 0.7443, 0.7443, 0.7434, 0.7429, 0.7442, 0.744, 0.7439, 0.7437, 0.7437,
                0.7429, 0.7403, 0.7399, 0.7418, 0.7468, 0.748, 0.748, 0.749, 0.7494, 0.7522,
                0.7515, 0.7502, 0.7472, 0.7472, 0.7462, 0.7455, 0.7449, 0.7467, 0.7458, 0.7427,
                0.7427, 0.743, 0.7429, 0.744, 0.743, 0.7422, 0.7388, 0.7388, 0.7369, 0.7345,
                0.7345, 0.7345, 0.7352, 0.7341, 0.7341, 0.734, 0.7324, 0.7272, 0.7264, 0.7255,
                0.7258, 0.7258, 0.7256, 0.7257, 0.7247, 0.7243, 0.7244, 0.7235, 0.7235, 0.7235,
                0.7235, 0.7262, 0.7288, 0.7301, 0.7337, 0.7337, 0.7324, 0.7297, 0.7317, 0.7315,
                0.7288, 0.7263, 0.7263, 0.7242, 0.7253, 0.7264, 0.727, 0.7312, 0.7305, 0.7305,
                0.7318, 0.7358, 0.7409, 0.7454, 0.7437, 0.7424, 0.7424, 0.7415, 0.7419, 0.7414,
                0.7377, 0.7355, 0.7315, 0.7315, 0.732, 0.7332, 0.7346, 0.7328, 0.7323, 0.734,
                0.734, 0.7336, 0.7351, 0.7346, 0.7321, 0.7294, 0.7266, 0.7266, 0.7254, 0.7242,
                0.7213, 0.7197, 0.7209, 0.721, 0.721, 0.721, 0.7209, 0.7159, 0.7133, 0.7105,
                0.7099, 0.7099, 0.7093, 0.7093, 0.7076, 0.707, 0.7049, 0.7012, 0.7011, 0.7019,
                0.7046, 0.7063, 0.7089, 0.7077, 0.7077, 0.7077, 0.7091, 0.7118, 0.7079, 0.7053,
                0.705, 0.7055, 0.7055, 0.7045, 0.7051, 0.7051, 0.7017, 0.7, 0.6995, 0.6994,
                0.7014, 0.7036, 0.7021, 0.7002, 0.6967, 0.695, 0.695, 0.6939, 0.694, 0.6922,
                0.6919, 0.6914, 0.6894, 0.6891, 0.6904, 0.689, 0.6834, 0.6823, 0.6807, 0.6815,
                0.6815, 0.6847, 0.6859, 0.6822, 0.6827, 0.6837, 0.6823, 0.6822, 0.6822, 0.6792,
                0.6746, 0.6735, 0.6731, 0.6742, 0.6744, 0.6739, 0.6731, 0.6761, 0.6761, 0.6785,
                0.6818, 0.6836, 0.6823, 0.6805, 0.6793, 0.6849, 0.6833, 0.6825, 0.6825, 0.6816,
                0.6799, 0.6813, 0.6809, 0.6868, 0.6933, 0.6933, 0.6945, 0.6944, 0.6946, 0.6964,
                0.6965, 0.6956, 0.6956, 0.695, 0.6948, 0.6928, 0.6887, 0.6824, 0.6794, 0.6794,
                0.6803, 0.6855, 0.6824, 0.6791, 0.6783, 0.6785, 0.6785, 0.6797, 0.68, 0.6803,
                0.6805, 0.676, 0.677, 0.677, 0.6736, 0.6726, 0.6764, 0.6821, 0.6831, 0.6842,
                0.6842, 0.6887, 0.6903, 0.6848, 0.6824, 0.6788, 0.6814, 0.6814, 0.6797, 0.6769,
                0.6765, 0.6733, 0.6729, 0.6758, 0.6758, 0.675, 0.678, 0.6833, 0.6856, 0.6903,
                0.6896, 0.6896, 0.6882, 0.6879, 0.6862, 0.6852, 0.6823, 0.6813, 0.6813, 0.6822,
                0.6802, 0.6802, 0.6784, 0.6748, 0.6747, 0.6747, 0.6748, 0.6733, 0.665, 0.6611,
                0.6583, 0.659, 0.659, 0.6581, 0.6578, 0.6574, 0.6532, 0.6502, 0.6514, 0.6514,
                0.6507, 0.651, 0.6489, 0.6424, 0.6406, 0.6382, 0.6382, 0.6341, 0.6344, 0.6378,
                0.6439, 0.6478, 0.6481, 0.6481, 0.6494, 0.6438, 0.6377, 0.6329, 0.6336, 0.6333,
                0.6333, 0.633, 0.6371, 0.6403, 0.6396, 0.6364, 0.6356, 0.6356, 0.6368, 0.6357,
                0.6354, 0.632, 0.6332, 0.6328, 0.6331, 0.6342, 0.6321, 0.6302, 0.6278, 0.6308,
                0.6324, 0.6324, 0.6307, 0.6277, 0.6269, 0.6335, 0.6392, 0.64, 0.6401, 0.6396,
                0.6407, 0.6423, 0.6429, 0.6472, 0.6485, 0.6486, 0.6467, 0.6444, 0.6467, 0.6509,
                0.6478, 0.6461, 0.6461, 0.6468, 0.6449, 0.647, 0.6461, 0.6452, 0.6422, 0.6422,
                0.6425, 0.6414, 0.6366, 0.6346, 0.635, 0.6346, 0.6346, 0.6343, 0.6346, 0.6379,
                0.6416, 0.6442, 0.6431, 0.6431, 0.6435, 0.644, 0.6473, 0.6469, 0.6386, 0.6356,
                0.634, 0.6346, 0.643, 0.6452, 0.6467, 0.6506, 0.6504, 0.6503, 0.6481, 0.6451,
                0.645, 0.6441, 0.6414, 0.6409, 0.6409, 0.6428, 0.6431, 0.6418, 0.6371, 0.6349,
                0.6333, 0.6334, 0.6338, 0.6342, 0.632, 0.6318, 0.637, 0.6368, 0.6368, 0.6383,
                0.6371, 0.6371, 0.6355, 0.632, 0.6277, 0.6276, 0.6291, 0.6274, 0.6293, 0.6311,
                0.631, 0.6312, 0.6312, 0.6304, 0.6294, 0.6348, 0.6378, 0.6368, 0.6368, 0.6368,
                0.636, 0.637, 0.6418, 0.6411, 0.6435, 0.6427, 0.6427, 0.6419, 0.6446, 0.6468,
                0.6487, 0.6594, 0.6666, 0.6666, 0.6678, 0.6712, 0.6705, 0.6718, 0.6784, 0.6811,
                0.6811, 0.6794, 0.6804, 0.6781, 0.6756, 0.6735, 0.6763, 0.6762, 0.6777, 0.6815,
                0.6802, 0.678, 0.6796, 0.6817, 0.6817, 0.6832, 0.6877, 0.6912, 0.6914, 0.7009,
                0.7012, 0.701, 0.7005, 0.7076, 0.7087, 0.717, 0.7105, 0.7031, 0.7029, 0.7006,
                0.7035, 0.7045, 0.6956, 0.6988, 0.6915, 0.6914, 0.6859, 0.6778, 0.6815, 0.6815,
                0.6843, 0.6846, 0.6846, 0.6923, 0.6997, 0.7098, 0.7188, 0.7232, 0.7262, 0.7266,
                0.7359, 0.7368, 0.7337, 0.7317, 0.7387, 0.7467, 0.7461, 0.7366, 0.7319, 0.7361,
                0.7437, 0.7432, 0.7461, 0.7461, 0.7454, 0.7549, 0.7742, 0.7801, 0.7903, 0.7876,
                0.7928, 0.7991, 0.8007, 0.7823, 0.7661, 0.785, 0.7863, 0.7862, 0.7821, 0.7858,
                0.7731, 0.7779, 0.7844, 0.7866, 0.7864, 0.7788, 0.7875, 0.7971, 0.8004, 0.7857,
                0.7932, 0.7938, 0.7927, 0.7918, 0.7919, 0.7989, 0.7988, 0.7949, 0.7948, 0.7882,
                0.7745, 0.771, 0.775, 0.7791, 0.7882, 0.7882, 0.7899, 0.7905, 0.7889, 0.7879,
                0.7855, 0.7866, 0.7865, 0.7795, 0.7758, 0.7717, 0.761, 0.7497, 0.7471, 0.7473,
                0.7407, 0.7288, 0.7074, 0.6927, 0.7083, 0.7191, 0.719, 0.7153, 0.7156, 0.7158,
                0.714, 0.7119, 0.7129, 0.7129, 0.7049, 0.7095
            ];
            
			//console.log("ORIGINAL: "+Object.keys(testJSON).length);    
    
     	
		
		     
		/**
	     * Create a graph based on parameters
	     * @param string
	     */
	    function createGraph(title, unit, container, seriesValues){
	    	// This is going to set a min y axis value in case one value of the serie is 0
	    	setMinTemp = false;
		    $.each(seriesValues[0].data, function(key, value) {
					if(value==0){
						setMinTemp = true;
						return false;
					}
			});
	    	yAxisJSON = (setMinTemp)?{title: {text: title},min:0}:{title: {text: title}};
			// Opciones Generales Gráfico
		  	opcionesGenerales = { 
		  		yAxis: yAxisJSON,
		    	tooltip: {
		    		valueSuffix: unit
		    	},  
		    	xAxis: {
		    		categories: xAxisValues 
		    	}    
			};
		
		  	// Opciones Gráfico
		  	opcionesGrafico = {
		    	chart: {
		      		renderTo: container         
		    	},
		    	series: seriesValues
		  	};
		  	
		  	opcionesGrafico = jQuery.extend(true, {}, opcionesGenerales, opcionesGrafico);
		  	grafico = new Highcharts.Chart(opcionesGrafico);
	    	
	    	return grafico;
	    }
	 });
	
	/**
	 * This will get min and max value of a json
	 * @param json the json to get values
	 * @return json with min and max value
	 */
	function getMaxMinVal(json){
		var max = min = 0;
	    $.each(json[0].data, function (key,value){
	        max = (max < value) ? value : max;
	    });
	    
	    min = max;
	    
	    $.each(json[0].data, function (key,value){
	    	//if(value>0){
	    		min = (min > value) ? value : min;	
	    	//}	        
	    });
	    
	    response = {"max":max, "min":min};
	    return response;
	}
	
	function getHighestVal(json){
		var max = 0;
	    $.each(json[0].data, function (key,value){
	        max = (max < value) ? value : max;
	    });
	    
	    return max;
	}
	
	function getLowestVal(json){
		var min = 2;
	    $.each(json[0].data, function (key,value){
	    	value = parseInt(value);
	    	if(value>0){
	    		min = (min > value) ? value : min;	
	    	}	        
	    });
	    return min;
	}
	 </script>
<?php 
} 
?>
	</head>
	<body class="bg-cyan">		
		<div class="body">
			<!-- tabs -->
			<div class="sky-tabs sky-tabs-external sky-tabs-position sky-tabs-pos-top-left sky-tabs-anim-slide-down sky-tabs-response-to-icons">
				<?php
				if($series['temperaturaJson'] || $series['presionJson'] || $series['humedadJson'] || $series['precipitacionJson'] || $series['nivelJson'] || $series['radiacionJson'] || $series['velocidadJson'] || $series['direccionJson'] || $series['evapotranspiracionJson']){
					$check = "";
				?>
				<input type="radio" name="sky-tabs" checked id="sky-tab1" class="sky-tab-content-1">				
				<label for="sky-tab1"><span><span><i class="fa fa-bolt"></i>Variables</span></span></label>
				<?php
				}else{
					$check = "checked";
				}
				?>
				<input type="radio" name="sky-tabs" <?php echo $check; ?> id="sky-tab2" class="sky-tab-content-2">
				<label for="sky-tab2"><span><span><i class="fa fa-picture-o"></i>Galería</span></span></label>
				<input type="radio" name="sky-tabs" id="sky-tab3" class="sky-tab-content-3">
				<label for="sky-tab3"><span><span><i class="fa fa-cogs"></i>Reportes</span></span></label>
				<ul>
					
						<?php
						if($series['temperaturaJson'] || $series['presionJson'] || $series['humedadJson'] || $series['precipitacionJson'] || $series['nivelJson'] || $series['radiacionJson'] || $series['velocidadJson'] || $series['direccionJson'] || $series['evapotranspiracionJson']){
						?>
						<li class="sky-tab-content-1">
						<div class="sky-tabs sky-tabs-internal sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack background">
						<?php	
							$contaTabs = 1;
							foreach ($variables as $var) {
						?>									
								<input type="radio" name="sky-tabs-1" <?php echo ($var=="temperatura")?"checked":""; ?> id="<?php echo "sky-tab1-".$contaTabs;?>" class="sky-tab-content-<?php echo $contaTabs;?>">
								<label for="<?php echo "sky-tab1-".$contaTabs;?>"><span class="sky-tabs_custom_pad"><span><?php echo ucfirst($var); ?></span></span></label>
						<?php	
								$contaTabs++;		
							}	
						?>
						<ul>
						<?php	
							$contaTabs = 1;
							foreach ($variables as $var) {
						?>									
								<li class="sky-tab-content-<?php echo $contaTabs;?>">
									<div class="typography">
									<?php 
										if($idEstacion!=0){ 
									?>
											<div id="container-<?php echo $var;?>" style="min-width: 400px; height: 380px; margin: 0 auto"></div>
									<?php 
										}else{ 
									?>
											<h1><?php echo $nombre_estacion;?><h1>
											No hay variables disponibles para graficar
									<?php 
										} 
									?>
									</div>
								</li>
						<?php	
								$contaTabs++;		
							}	
						?>
						</ul>
					</div>
					</li>		
					<?php
	  				}/*else{
	  				?>
						<h1><?php echo $nombre_estacion;?><h1>
						No hay variables disponibles para graficar
					<?php	  				
	  				}*/
	  				?>	
					
					<li class="sky-tab-content-2">
						<div class="typography" style="margin: 0 0 0 60px;">
							<iframe src="../galeria/index.php?id=<?php echo $idEstacion."&name=".$nombre_estacion."&tipo=".$tipoEstacion; ?>" height="500px" width="420px"></iframe>
						</div>
					</li>
					<li class="sky-tab-content-3">
						<div class="typography"  >
							<iframe src="../reportes/index.php?name=<?php echo $nombre_estacion."&tipo=".$tipoEstacion; ?>" height="400px" width="540px"></iframe>
						</div>
					</li>
				</ul>
			</div>
			<!--/ tabs -->
		</div>
	</body>
</html>