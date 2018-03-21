<?php
// Obtiene la conexión a la bd
include_once ('../lib/class.MySQL.php');
//if (isset($_GET['bd']) && !empty($_GET['bd']) && $_GET['bd'] != "wunderground") {
	mysql_query("set names 'utf8'");
//}
// Obtiene id de la estación
$idEstacion = $_GET['id'];
// Obtiene las estaciones desde wunderground y la información asociada a cada variable
include_once ('../lib/wunderground.php');
// Obtiene las estaciones desde las bases de datos de la UTP y la de Aguas y Aguas y la información asociada a cada variable
include_once ('../lib/db_graph_info.php');
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
if($idEstacion!="0"){	     
    $est["stationName"]=isset($est["stationName"])?$est["stationName"]:$idEstacion;
?>
<script>
	$(function () {
		
		var estacion = '<?php echo  $est["stationName"];?>';
	    
	    // General options for chart    
	    Highcharts.setOptions({
	    	title: {
	    		text: '<?php echo  $est["stationName"];?>',
	            x: -20 //center
	        },
	        subtitle: {
	          	userHTML:true,
	            text: 'Mediciones últimas 48 horas<br/>Origen: Red Hidroclimatológica de Risaralda',
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
	    <?php
	    $variableTags=array("temperature"=>array(0=>"Temperatura (°C)",1=>"(°C)"), 
	    					"presure"=>array(0=>"Presión (mm/mg)",1=>"mm/mg"),
							"humidity"=>array(0=>"Humedad Relativa (%)",1=>"%"),
							"realPrecipitation"=>array(0=>"Precipitación (mm)",1=>"mm"),
							"level"=>array(0=>"Nivel (cm)",1=>"cm"),
							"radiation"=>array(0=>"Radiación Solar (W/m²)",1=>"W/m²"),
							"windSpeed"=>array(0=>"Velocidad (m/s)",1=>"m/s"),
							"windDirection"=>array(0=>"Dirección (°)",1=>"°"),							
							"realETO"=>array(0=>"Evapotranspiracion (mm)",1=>"mm"),
							"riverFlow"=>array(0=>"Caudal (m3/s)",1=>"m3/s")
							);
	    
	    foreach ($variables as $var) {
	    ?>
			seriesArea['<?php echo $var; ?>'] = [<?php echo $serieNew[$var]; ?>];
	    		createGraphArea('<?php echo $variableTags[$var][0];?>' , '<?php echo $variableTags[$var][1];?>', 'container-<?php echo $var;?>', seriesArea['<?php echo $var; ?>']);	
		<?php
		}
	    ?>   
		
		/**
	     * Create a graph based on parameters
	     * @param string
	     */
	    function createGraphArea(title, unit, container, seriesValues, minYValue){
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
				//Null value for a serie
				$ns = '{"data":[null]}';
				$ns2 = '{"data":null}';
				// Verify any of the variables has data
				$nullDataValidation = false;
				foreach ($variables as $var) {
				    $nullDataValidation = $serieNew[$var] != $ns && $serieNew[$var] != $ns2;
					if($nullDataValidation){
						break;
					} 
				}
				if($idEstacion!="0" && $nullDataValidation){
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
						
						
						if($idEstacion!="0" && $nullDataValidation){						
						?>
						<li class="sky-tab-content-1">
						<div class="sky-tabs sky-tabs-internal sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack background">
						<?php	
							$contaTabs = 1;
							
							foreach ($variables as $var) {
						?>			
						$variables = array("temperature", "presure", "humidity", "realPrecipitation", "radiation", "windDirection", "windDirection", "realETO");						
								<input type="radio" name="sky-tabs-1" <?php echo ($var=="temperature" || count($variables)==1)?"checked":""; ?> id="<?php echo "sky-tab1-".$contaTabs;?>" class="sky-tab-content-<?php echo $contaTabs;?>">
								<label for="<?php echo "sky-tab1-".$contaTabs;?>"><span class="sky-tabs_custom_pad"><span><?php echo ($var=="level")?"Nivel" : ( ($var=="temperature")?"Temperatura": ($var=="temperature")?"Temperatura": ( ($var=="realPrecipitation")?"Precipitación":(($var=="realETO")?"Evapotranspiración":(($var=="presure")?"Presión":(($var=="radiation")?"Radiación":(($var=="windDirection")?"Dirección": ( ($var=="windSpeed")?"Velocidad" : ( ($var=="humidity")?"Humedad" : ucfirst($var) ) ) ) ) ) ) ) ); ?></span></span></label>
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
										if($idEstacion!="0" && $nullDataValidation){ 
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
							<iframe src="../galeria/index.php?id=<?php echo $idEstacion."&name=".$nombre_estacion."&tipo=".$tipoEstacion."&folder=".$carpeta; ?>" height="500px" width="420px"></iframe>
						</div>
					</li>
					<li class="sky-tab-content-3">
						<div class="typography"  >
						<?php
							$nombre_estacion = (empty($nombre_estacion))?$carpeta:$nombre_estacion;
						?>
							<iframe src="../reportes/index.php?name=<?php echo $nombre_estacion."&tipo=".$tipoEstacion."&folder=".$carpeta; ?>" height="400px" width="540px"></iframe>
						</div>
					</li>
				</ul>
			</div>
			<!--/ tabs -->
		</div>
	</body>
</html>