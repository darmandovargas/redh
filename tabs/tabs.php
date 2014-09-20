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
	// Dependiendo del tipo de estaciÃ³n, se debe revisar en estaciones o estacion_sensores
	switch ($tipoEstacion) {
		case 'ECT' :
			$tablaEstaciones = "estaciones"; break;
		case 'EHT' :
			$tablaEstaciones = "estaciones"; break;
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
	// Inicializa array de variables a sensar y graficar
	$variables = array("temperatura", "presion", "humedad", "precipitacion", "nivel", "radiacion");
	
	// Obtiene la estación según el tipo que define la tabla
	$query = "SELECT * FROM " . $tablaEstaciones . " WHERE id=" . $idEstacion;	//." and activo='true'";
	$est = $oMySQL -> ExecuteSQL($query);
	// Obtiene el nombre de la estaciÃ³n
	$tabla = $est["estNombreTb"];
	// Obtiene datos de la tabla de la estaciÃ³n del Ãºltimo dÃ­a (aproximadamente 285 Ãºltimos datos)
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 285";
	$estacionInfo = $oMySQL -> ExecuteSQL($query);
	$isFirstVal = true;
	//$contadorHoras = 0;
	// Itero la informatión de la tabla de la estación
	foreach ($estacionInfo as $data) {
		// Obtengo la hora de la medición
		$horaX = substr($data['hora'], 0, 2);		
		// Valido que la hora actual y la anterior sean la misma para sumar al promedio y al contador
		if ($ultimaHora == $horaX || $isFirstVal) {				
			foreach($variables as $vars){
				if(!empty($var)){
					if($data[$vars]!="-"){
						$contador[$vars]++;
						$promedio[$vars] += floatval($data[$vars]);
					}	
				}				
			}	
			$isFirstVal=false;
		} else {
			foreach($variables as $var){
				//Variable $var	
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
	}

	foreach($variables as $var){		
		foreach ($estationTable[$var] as $data) {
			// Obtengo un solo array de datos y uno solo de horas en el eje x
			$jsonData[$var][] = $data["data"];
			$x[] = $data["hora"];
		}
	}
	
	$xAxisTemp = $x;
	$nombre_estacion = $est["estNombre"];
	$seriesTemp[] = array("name" => $est["estNombre"], "data" => $jsonData['temperatura']);
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
	$series['temperaturaJson'] = $series['presionJson'] = $series['humedadJson'] = $series['precipitacionJson']  = $series['nivelJson'] = $series['radiacionJson'] = true;

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
        
	temperaturasEstaciones =[<?php echo $series['temperatura']; ?>];
    presionEstaciones = [<?php echo $series['presion']; ?>];
    humedadEstaciones = [<?php echo $series['humedad']; ?>];
    precipitacionEstaciones = [<?php echo $series['precipitacion']; ?>];
    nivelEstaciones = [<?php echo $series['nivel']; ?>];
    radiacionEstaciones = [<?php echo $series['radiacion']; ?>];
            
        
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
        
        
        
        
        
        
  // default options
  var optionsChart1 = { 
  	yAxis: {
  		title: {
  			text: 'Temperatura (Â°C)'
        }
    },
    tooltip: {
    	valueSuffix: 'Â°C'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    	
    }    
  };

  // create the chart
  var chart1Options = {
    chart: {
      renderTo: 'container-1'        
    },
    series: temperaturasEstaciones
  };
  chart1Options = jQuery.extend(true, {}, optionsChart1, chart1Options);
  var chart1 = new Highcharts.Chart(chart1Options);
        
   
  // default options
  var optionsChart2 = { 
  	yAxis: {
  		title: {
  			text: 'Presión Barométrica (Pa)'
        }
    },
    tooltip: {
    	valueSuffix: 'Pa'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    }    
  };
   // create the chart
  var chart2Options = {
    chart: {
      renderTo: 'container-2'
    },
    series: presionEstaciones
  };
  chart2Options = jQuery.extend(true, {}, optionsChart2, chart2Options);
  var chart2 = new Highcharts.Chart(chart2Options);  
  
  
  
    // default options
  var optionsChart3 = { 
  	yAxis: {
  		title: {
  			text: 'Humedad Relativa'
        }
    },
    tooltip: {
    	valueSuffix: 'HR'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    }    
  };
   // create the chart
  var chart3Options = {
    chart: {
      renderTo: 'container-3'
    },
    series: humedadEstaciones
  };
  chart3Options = jQuery.extend(true, {}, optionsChart3, chart3Options);
  var chart3 = new Highcharts.Chart(chart3Options);
  
  
     // default options
  var optionsChart4 = { 
  	yAxis: {
  		title: {
  			text: 'Precipitación (mm)'
        }
    },
    tooltip: {
    	valueSuffix: 'mm'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    }    
  };
   // create the chart
  var chart4Options = {
    chart: {
      renderTo: 'container-4'
    },
    series: precipitacionEstaciones
  };
  chart4Options = jQuery.extend(true, {}, optionsChart4, chart4Options);
  var chart4 = new Highcharts.Chart(chart4Options);
  
  
  
     // default options
  var optionsChart5 = { 
  	yAxis: {
  		title: {
  			text: 'Radiación Solar ( W/m²)'
        }
    },
    tooltip: {
    	valueSuffix: ' W/m²'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    }    
  };
   // create the chart
  var chart5Options = {
    chart: {
      renderTo: 'container-5'
    },
    series: radiacionEstaciones
  };
  chart5Options = jQuery.extend(true, {}, optionsChart5, chart5Options);
  var chart5 = new Highcharts.Chart(chart5Options);  
       

  
  
     // default options
  var optionsChart6 = { 
  	yAxis: {
  		title: {
  			text: 'Nivel (Nm3/h)'
        }
    },
    tooltip: {
    	valueSuffix: 'Nm3/h'
    },  
    xAxis: {
    	categories: <?php echo $xAxis; ?>
    }    
  };
   // create the chart
  var chart6Options = {
    chart: {
      renderTo: 'container-6'
    },
    series: nivelEstaciones
  };
  chart6Options = jQuery.extend(true, {}, optionsChart6, chart6Options);
  var chart6 = new Highcharts.Chart(chart6Options); 
          
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
				<!--
				<input type="radio" name="sky-tabs" id="sky-tab4" class="sky-tab-content-4">
				<label for="sky-tab4"><span><span><i class="fa fa-globe"></i>Newton</span></span></label>
				-->
				<ul>
					
					<li class="sky-tab-content-1">
						<?php
					if($series['temperaturaJson'] || $series['presionJson'] || $series['humedadJson'] || $series['precipitacionJson'] || $series['nivelJson'] || $series['radiacionJson']){
						?>
						<div class="sky-tabs sky-tabs-internal sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack background">
						<?php	
												
							//if($series['temperaturaJson']){
							?>
							<input type="radio" name="sky-tabs-1" checked id="sky-tab1-1" class="sky-tab-content-1">
							<label for="sky-tab1-1"><span class="sky-tabs_custom_pad"><span>Temperatura</span></span></label>
							<?php
							//}		
							//if($series['presionJson']){
							?>
							<input type="radio" name="sky-tabs-1" id="sky-tab-1-2" class="sky-tab-content-2">
							<label for="sky-tab-1-2"><span  class="sky-tabs_custom_pad"><span>Presión</span></span></label>							
							<?php							
							//}
							//if($series['humedadJson']){
							?>
							<input type="radio" name="sky-tabs-1" id="sky-tab1-3" class="sky-tab-content-3">
							<label for="sky-tab1-3"><span  class="sky-tabs_custom_pad"><span>Humedad</span></span></label>							
							<?php							
							//}
							//if($series['precipitacionJson']){
							?>
							<input type="radio" name="sky-tabs-1" id="sky-tab1-4" class="sky-tab-content-4">
							<label for="sky-tab1-4"><span  class="sky-tabs_custom_pad"><span>Precipitación</span></span></label>
							<?php
							//}
							if($series['nivelJson'] && $tipoEstacion=='EHT'){
							?>
							<input type="radio" name="sky-tabs-1" id="sky-tab1-6" class="sky-tab-content-6">
							<label for="sky-tab1-6"><span  class="sky-tabs_custom_pad"><span>Nivel</span></span></label>
							<?php
							}
							//if($series['radiacionJson']){
							?>
							<input type="radio" name="sky-tabs-1" id="sky-tab1-5" class="sky-tab-content-5">
							<label for="sky-tab1-5"><span  class="sky-tabs_custom_pad"><span>Radiación</span></span></label>
							<?php
							//}							
							?>
							
							
							<ul>
								<?php								
								//if($series['temperaturaJson']){
								?>
								<li class="sky-tab-content-1">
									<div class="typography">
										<?php if($idEstacion!=0){ ?>
							<div id="container-1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							<?php }else{ ?>
									<h1><?php echo $nombre_estacion;?><h1>
									No hay variables disponibles para graficar
							<?php } ?>
									</div>
								</li>
								<?php
								//}
								//if($series['presionJson']){
								?>
								<li class="sky-tab-content-2">
									<div class="typography">
										<?php if($idEstacion!=0){ ?>
										<div id="container-2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
										<?php }else{ ?>
												<h1><?php echo $nombre_estacion;?><h1>
												No hay variables disponibles para graficar
										<?php } ?>
									</div>
								</li>
								<?php
								//}
								//if($series['humedadJson']){
								?>
								<li class="sky-tab-content-3">
									<div class="typography">
										<?php if($idEstacion!=0){ ?>
										<div id="container-3" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
										<?php }else{ ?>
												<h1><?php echo $nombre_estacion;?><h1>
												No hay variables disponibles para graficar
										<?php } ?>
									</div>									
								</li>
								<?php
								//}
								//if($series['precipitacionJson']){
								?>
								<li class="sky-tab-content-4">
									<div class="typography">
										<?php if($idEstacion!=0){ ?>
										<div id="container-4" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
										<?php }else{ ?>
												<h1><?php echo $nombre_estacion;?><h1>
												No hay variables disponibles para graficar
										<?php } ?>
									</div>									
								</li>
								<?php
								//}
								if($series['nivelJson'] && $tipoEstacion=='EHT'){
								?>
								<li class="sky-tab-content-6">
									<div class="typography">
										<?php if($idEstacion!=0 ){ ?>
										<div id="container-6" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
										<?php }else{ ?>
												<h1><?php echo $nombre_estacion;?><h1>
												No hay variables disponibles para graficar
										<?php } ?>
									</div>									
								</li>
								<?php
								}
								//if($series['radiacionJson']){
								?>
								<li class="sky-tab-content-5">
									<div class="typography">
										<?php if($idEstacion!=0){ ?>
										<div id="container-5" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
										<?php }else{ ?>
												<h1><?php echo $nombre_estacion;?><h1>
												No hay variables disponibles para graficar
										<?php } ?>
									</div>									
								</li>
								<?php
								//}
								?>
								
								
							</ul>
							
					
						</div>		
						
						<?php
	  			}else{?>
					<h1><?php echo $nombre_estacion;?><h1>
					No hay variables disponibles para graficar
				<?php	  				
	  			}
	  			?>	
						
						
										
						
					</li>
					<?php
					//}
					?>
					<li class="sky-tab-content-2">
						<div class="typography" style="margin: 0 0 0 60px;">
							<iframe src="../galeria/index.php?id=<?php echo $idEstacion."&name=".$nombre_estacion."&tipo=".$tipoEstacion; ?>" height="500px" width="420px"></iframe>
							<!--
							<h1>Leonardo da Vinci</h1>	
							<p>Italian Renaissance polymath: painter, sculptor, architect, musician, mathematician, engineer, inventor, anatomist, geologist, cartographer, botanist, and writer. His genius, perhaps more than that of any other figure, epitomized the Renaissance humanist ideal. Leonardo has often been described as the archetype of the Renaissance Man, a man of "unquenchable curiosity" and "feverishly inventive imagination". He is widely considered to be one of the greatest painters of all time and perhaps the most diversely talented person ever to have lived. According to art historian Helen Gardner, the scope and depth of his interests were without precedent and "his mind and personality seem to us superhuman, the man himself mysterious and remote". Marco Rosci states that while there is much speculation about Leonardo, his vision of the world is essentially logical rather than mysterious, and that the empirical methods he employed were unusual for his time.</p>
							<p>Born out of wedlock to a notary, Piero da Vinci, and a peasant woman, Caterina, at Vinci in the region of Florence, Leonardo was educated in the studio of the renowned Florentine painter, Verrocchio. Much of his earlier working life was spent in the service of Ludovico il Moro in Milan. He later worked in Rome, Bologna and Venice, and he spent his last years in France at the home awarded him by Francis I.</p>
							<p class="text-right"><em>Find out more about Leonardo da Vinci from <a href="http://en.wikipedia.org/wiki/Leonardo_da_Vinci" target="_blank">Wikipedia</a>.</em></p>
							-->
						</div>
					</li>
					
					<li class="sky-tab-content-3">
						<div class="typography"  >
							<iframe src="../reportes/index.php?name=<?php echo $nombre_estacion."&tipo=".$tipoEstacion; ?>" height="400px" width="540px"></iframe>
							<!--
							<h1>Albert Einstein</h1>
							<p>German-born theoretical physicist who developed the general theory of relativity, one of the two pillars of modern physics (alongside quantum mechanics). While best known for his massâ€“energy equivalence formula E = mc2 (which has been dubbed "the world's most famous equation"), he received the 1921 Nobel Prize in Physics "for his services to theoretical physics, and especially for his discovery of the law of the photoelectric effect". The latter was pivotal in establishing quantum theory.</p>
							<p>Near the beginning of his career, Einstein thought that Newtonian mechanics was no longer enough to reconcile the laws of classical mechanics with the laws of the electromagnetic field. This led to the development of his special theory of relativity. He realized, however, that the principle of relativity could also be extended to gravitational fields, and with his subsequent theory of gravitation in 1916, he published a paper on the general theory of relativity.</p>
							<p>He continued to deal with problems of statistical mechanics and quantum theory, which led to his explanations of particle theory and the motion of molecules. He also investigated the thermal properties of light which laid the foundation of the photon theory of light. In 1917, Einstein applied the general theory of relativity to model the large-scale structure of the universe.</p>
							<p class="text-right"><em>Find out more about Albert Einstein from <a href="http://en.wikipedia.org/wiki/Albert_Einstein" target="_blank">Wikipedia</a>.</em></p>
							-->
						</div>
					</li>
					<!--					
					<li class="sky-tab-content-4">
						<div class="typography">
							<h1>Isaac Newton</h1>
							<p>English physicist and mathematician who is widely regarded as one of the most influential scientists of all time and as a key figure in the scientific revolution. His book PhilosophiÃ¦ Naturalis Principia Mathematica ("Mathematical Principles of Natural Philosophy"), first published in 1687, laid the foundations for most of classical mechanics. Newton also made seminal contributions to optics and shares credit with Gottfried Leibniz for the invention of the infinitesimal calculus.</p>
							<p>Newton's Principia formulated the laws of motion and universal gravitation that dominated scientists' view of the physical universe for the next three centuries. It also demonstrated that the motion of objects on the Earth and that of celestial bodies could be described by the same principles. By deriving Kepler's laws of planetary motion from his mathematical description of gravity, Newton removed the last doubts about the validity of the heliocentric model of the cosmos.</p>
							<p class="text-right"><em>Find out more about Isaac Newton from <a href="http://en.wikipedia.org/wiki/Isaac_Newton" target="_blank">Wikipedia</a>.</em></p>
						</div>
					</li>
					-->					
				</ul>
			</div>
			<!--/ tabs -->
			
		</div>
	</body>
</html>
