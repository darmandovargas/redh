<?php
// Obtiene la conexión a la bd
include_once ('../lib/class.MySQL.php');
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
	
	// Obtiene la estación según el tipo que define la tabla
	$query = "SELECT * FROM " . $tablaEstaciones . " WHERE id=" . $idEstacion;	//." and activo='true'";
	$est = $oMySQL -> ExecuteSQL($query);
	// Obtiene el nombre de la estación
	$tabla = $est["estNombreTb"];

	/**
	 * Gets variables for area graphs
	 */
	// Get yesterday date like this 2014-10-03
	$dt = new DateTime('', new DateTimeZone('America/Bogota'));
	$dt->sub(new DateInterval('P1D'));
	//echo $dt->format('Y-m-d H:i:s');
	//echo "</br></br>";
	$yesterday = $dt->format('Y-m-d');//date("Y-m-d", strtotime("yesterday"));//date("Y-m-d", strtotime("yesterday"));
	// Get data from yesterday ordered from last measure	
	$query = "SELECT * FROM " . $tabla . " where fecha >= '".$yesterday."' ORDER BY fecha ASC";//LIMIT 5
	$estacionesInfoSinceYesterday = $oMySQL -> ExecuteSQL($query);
	$oMySQL -> closeConnection();	
	$lastValue = array();
	foreach($variables as $v){
		$lastValue[$v] = 0;
	}
	
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
	
	$nombre_estacion = $est["estNombre"];
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
		//var_dump($serieNew['presion']);
		//echo "</br></br></br>"; 
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
		
		// Create series	   
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
	    
	    // Json to validate if we must show the variables tab
	    /*var jsonSeries = Array;
	    jsonSeries['temperatura'] = (seriesArea['temperatura'][0]==undefined)?false:true;
	    jsonSeries['presion'] = (seriesArea['presion'][0]===undefined)?false:true;
	    jsonSeries['humedad'] = (seriesArea['humedad'][0]==undefined)?false:true;
	    jsonSeries['precipitacion_real'] = (seriesArea['precipitacion_real'][0]==undefined)?false:true;
	    jsonSeries['nivel'] = (seriesArea['nivel'][0]==undefined)?false:true;
	    jsonSeries['radiacion'] = (seriesArea['radiacion'][0]==undefined)?false:true;
	    jsonSeries['velocidad'] = (seriesArea['velocidad'][0]==undefined)?false:true;
	    jsonSeries['direccion'] = (seriesArea['direccion'][0]==undefined)?false:true;
	    jsonSeries['evapo_real'] = (seriesArea['evapo_real'][0]==undefined)?false:true;
	    
	    console.log("HEY");
	    console.log(seriesArea['presion'][0]);
	    *///Mejoras para cuando no hay valores de mediciones en alguna estación, simplemente no muestra el tab de variables (ésto solo sucede en el caso en que no haya valores e)
	    // Render graphs
	    <?php
	    //$nullJSON = '{"data":[null]}';
		//var_dump($serieNew['presion']);
	    /*
	    if($serieNew['presion']!=$nullJSON && $serieNew['humedad']!=$nullJSON  && $serieNew['nivel']!=$nullJSON  && $serieNew['precipitacion_real']!=$nullJSON  && $serieNew['radiacion']!=$nullJSON  && $serieNew['velocidad']!=$nullJSON 
			&& $serieNew['direccion']!=$nullJSON  && $serieNew['evapo_real']!=$nullJSON  && $serieNew['temperatura']!=$nullJSON
		){*/
	    ?>
	    //console.log("PRESION:"+jsonSeries['presion']);
		//if(jsonSeries['presion'])
			createGraphArea('Presión Barométrica (mm/mg)', 'mm/mg', 'container-presion', seriesArea['presion']);
		//if(jsonSeries['humedad'])
			createGraphArea('Humedad Relativa (%)', '%', 'container-humedad', seriesArea['humedad']);
		//if(jsonSeries['nivel'])
			createGraphArea('Nivel (cm)', 'cm', 'container-nivel', seriesArea['nivel']);
		//if(jsonSeries['precipitacion_real'])
			createGraphArea('Precipitación (mm)', 'mm', 'container-precipitacion_real', seriesArea['precipitacion_real']);
		<?php
		//if($series['nivelJson'] && $tipoEstacion=='EHT'){
		?>
		//if(jsonSeries['radiacion'])
			createGraphArea('Radiación Solar ( W/m²)', 'W/m²', 'container-radiacion', seriesArea['radiacion']);
		<?php
		//}
		?>
		//if(jsonSeries['velocidad'])
			createGraphArea('Velocidad (m/s)', 'm/s', 'container-velocidad', seriesArea['velocidad']);
		//if(jsonSeries['direccion'])
			createGraphArea('Dirección (°)', '°', 'container-direccion', seriesArea['direccion']);
		//if(jsonSeries['evapo_real'])
			createGraphArea('Evapotranspiracion (mm)', 'mm', 'container-evapo_real', seriesArea['evapo_real']);
		//if(jsonSeries['temperatura'])
			createGraphArea('Temperatura (°C)', '°C', 'container-temperatura', seriesArea['temperatura']);
		<?php
		//}
		?>
		
		
		/**
	     * Create a graph based on parameters
	     * @param string
	     */
	    function createGraphArea(title, unit, container, seriesValues, minYValue){
	    	console.log(title);
	    	//if(seriesValues[0].data[0] == undefined)
	    	//	return false;
    		var new_obj = [{"data": []}];
	    	$.each(seriesValues[0].data, function(key, value) {
	    		time = eval(value[0]);
	    		new_obj[0].data.push([time, value[1]]);
			});
	    	// Parche para el bug del primer valor cero cuando en la bd es "-" 
			if(minYValue==undefined){
				maxMinPresion = getMaxMinVal(new_obj);
				//console.log(title);
				//console.log(maxMinPresion);
				yAxisJSON = {title: {text: title},min:maxMinPresion.min};				
			}else{
				yAxisJSON = {title: {text: title},min:minYValue};
			}

			var d = new Date();
			var currentYear = d.getFullYear();
			var currentMonth = d.getMonth();
			var currentDay = d.getDate();
			
			// Opciones Generales Gráfico
		  	opcionesGenerales = { 
		  		yAxis: yAxisJSON,
		    	tooltip: {
		    		valueSuffix: unit
		    	},
		    	xAxis: {
		            type: 'datetime',
		            tickInterval: 24 * 3600 * 1000
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
		            pointInterval: 24 * 3600 * 3.579,
		            pointStart: Date.UTC(currentYear, currentMonth, currentDay-1),
		            data: new_obj[0].data
		        }]
			};

		  	// Opciones Gráfico
		  	opcionesGrafico = {
		    	chart: {
		      		renderTo: container         
		    	},
		    	series: new_obj
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
	    	//console.log("key:"+key+" value:"+value);
	        max = (max < value[1]) ? value[1] : max;
	    });
	    
	    min = max;
	    
	    $.each(json[0].data, function (key,value){
	    	//if(value>0){
	    		min = (min > value[1]) ? value[1] : min;	
	    	//}	        
	    });
	    
	    response = {"max":max, "min":min};
	    //console.log(response);
	    return response;
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
				if($idEstacion!=0 && $serieNew['presion']!='{"data":[null]}'){
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
						if($idEstacion!=0 && $serieNew['presion']!='{"data":[null]}'){
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
										if($idEstacion!=0 && $serieNew['presion']!='{"data":[null]}'){ 
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
	  				}
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