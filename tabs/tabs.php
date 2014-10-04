<?php
// Obtiene la conexiÃ³n a la bd
include_once ('../lib/class.MySQL.php');
// Estaciones pÃºblicas
$publicEstations = array("tb_san_jose", "tb_ellago", "tb_Cortaderal", "tb_el_cedral", "tb_san_juan", "tb_el_nudo", "tb_quinchia");
// Inicializa variables
$estationTable = $privateEstationTable = array();
// Obtiene id de la estación
$idEstacion = $_GET['id'];
// Obtiene el tipo de estacion
$tipoEstacion = $_GET['tipo'];
// Si la estaciÃ³n existe en la base de datos entonces arme el json para graficar
if ($idEstacion != 0) {
	// Inicializa array de variables a sensar y graficar
	$variables = array("temperatura", "presion", "humedad", "precipitacion", "nivel", "radiacion", "velocidad", "direccion", "evapotranspiracion");
	//Temperatura, Precipitación, Humedad Relativa, Radiación Solar, Presión Barométrica, Velocidad y Dirección del Viento
	// Dependiendo del tipo de estaciÃ³n, se debe revisar en estaciones o estacion_sensores
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
	// Inicializa variables para la grÃ¡fica
	$xAxis = $series = array();
	// TODO: improve code with for's
	
	
	// Obtiene la estación según el tipo que define la tabla
	$query = "SELECT * FROM " . $tablaEstaciones . " WHERE id=" . $idEstacion;	//." and activo='true'";
	$est = $oMySQL -> ExecuteSQL($query);
	// Obtiene el nombre de la estaciÃ³n
	$tabla = $est["estNombreTb"];
	// Obtiene datos de la tabla de la estaciÃ³n del Ãºltimo dÃ­a (aproximadamente 285 Ãºltimos datos)
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 285";//285
	$estacionInfo = $oMySQL -> ExecuteSQL($query);
	$isFirstVal = true;
	$cuentaVeintiCuatro = 0;
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

	foreach($variables as $var){		
		foreach ($estationTable[$var] as $data) {
			// Obtengo un solo array de datos y uno solo de horas en el eje x
			$jsonData[$var][] = $data["data"];
			$x[] = $data["hora"];
		}
	}
	//var_dump($jsonData);
	$xAxisTemp = $x;
	$nombre_estacion = $est["estNombre"];
	//$seriesTemp[] = array("name" => $est["estNombre"], "data" => $jsonData['temperatura']);
	//$xAxis = $series = array();
	$xAxis = json_encode(array_reverse($xAxisTemp));

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
	    precipitacionEstaciones = [<?php echo $series['precipitacion']; ?>];
	    nivelEstaciones = [<?php echo $series['nivel']; ?>];
	    radiacionEstaciones = [<?php echo $series['radiacion']; ?>];    
	    velocidadEstaciones = [<?php echo $series['velocidad']; ?>];
	    direccionEstaciones = [<?php echo $series['direccion']; ?>];
	    evapotranspiracionEstaciones = [<?php echo $series['evapotranspiracion']; ?>];
	    
	    console.log(radiacionEstaciones);
	    console.log(velocidadEstaciones);
	    console.log(direccionEstaciones);
	    console.log(evapotranspiracionEstaciones);
	    
	    var xAxisValues =  <?php echo $xAxis; ?>;
		
		createGraph('Temperatura (°C)', '°C', 'container-temperatura', temperaturasEstaciones);
		createGraph('Presión Barométrica (mm/mg)', 'mm/mg', 'container-presion', presionEstaciones);
		createGraph('Humedad Relativa (%)', '%', 'container-humedad', humedadEstaciones);
		createGraph('Precipitación (mm)', 'mm', 'container-precipitacion', precipitacionEstaciones);
		createGraph('Nivel (cm)', 'cm', 'container-nivel', nivelEstaciones);
		<?php
		//if($series['nivelJson'] && $tipoEstacion=='EHT'){
		?>
			createGraph('Radiación Solar ( W/m²)', 'W/m²', 'container-radiacion', radiacionEstaciones);
		<?php
		//}
		?>	
		createGraph('Velocidad (m/s)', 'm/s', 'container-velocidad', velocidadEstaciones);
		createGraph('Dirección (°)', '°', 'container-direccion', direccionEstaciones);
		createGraph('Evapotranspiracion (mm)', 'mm', 'container-evapotranspiracion', evapotranspiracionEstaciones);
		     
		/**
	     * Create a grap based on parameters
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
	 </script>
<?php 
} 
?>
	</head>
	<body class="bg-cyan">		
		<div class="body">
			<!-- tabs -->
			<div class="sky-tabs sky-tabs-external sky-tabs-position sky-tabs-pos-top-left sky-tabs-anim-slide-down sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs" checked id="sky-tab1" class="sky-tab-content-1">
				<label for="sky-tab1"><span><span><i class="fa fa-bolt"></i>Variables</span></span></label>
				<input type="radio" name="sky-tabs" id="sky-tab2" class="sky-tab-content-2">
				<label for="sky-tab2"><span><span><i class="fa fa-picture-o"></i>Galería</span></span></label>
				<input type="radio" name="sky-tabs" id="sky-tab3" class="sky-tab-content-3">
				<label for="sky-tab3"><span><span><i class="fa fa-cogs"></i>Reportes</span></span></label>
				<ul>
					<li class="sky-tab-content-1">
						<?php
						if($series['temperaturaJson'] || $series['presionJson'] || $series['humedadJson'] || $series['precipitacionJson'] || $series['nivelJson'] || $series['radiacionJson'] || $series['velocidadJson'] || $series['direccionJson'] || $series['evapotranspiracionJson']){
						?>
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
											<div id="container-<?php echo $var;?>" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
					<?php
	  				}else{
	  				?>
						<h1><?php echo $nombre_estacion;?><h1>
						No hay variables disponibles para graficar
					<?php	  				
	  				}
	  				?>	
					</li>
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