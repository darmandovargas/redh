<?php 

include_once ('../lib/class.MySQL.php');

$publicEstations = array("tb_san_jose", "tb_ellago", "tb_Cortaderal", "tb_el_cedral", "tb_san_juan", "tb_el_nudo", "tb_quinchia");

$estationTable = $privateEstationTable = array();

$idEstacion = $_GET['id'];

$xAxis = $series = array();

//echo "ID: ".$idEstacion;

//function getEstation($idEst){
		
	// Obtiene la estación segú parámetro get
	$query = "SELECT * FROM estaciones WHERE id=".$idEstacion;//." and activo='true'";
	//$query2 = "SELECT * FROM estaciones where activo='true'";
	$est = $oMySQL -> ExecuteSQL($query);
	$estaciones= $oMySQL -> ExecuteSQL($query2);
	//$est = $estacion;
//	foreach ($estaciones as $est) {
		// Obtiene el nombre de la estación
		$tabla = $est["estNombreTb"];
		// Obtiene datos de la tabla de la estación del último día (aproximadamente 285 últimos datos)
		$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 285";
		//echo $query."</br>";
		$estacionInfo = $oMySQL -> ExecuteSQL($query);
		// Inicializa variables para guardar el promedio por hora y un contador
		$promedio = $contador = $ultimaHora = 0;
		// Itero la informatión de la tabla de la estación
		foreach ($estacionInfo as $data) {
			// Obtengo la hora de la medición
			$horaX = substr($data['hora'], 0, 2);
			// Valido que la hora actual y la anterior sean la misma para sumar al promedio y al contador
			if ($ultimaHora == intval($horaX) || $contador == 0) {
				$contador++;
				$promedio += $data['temperatura'];
			} else {
				// Si la hora anterior y la actual son diferentes, agrego el valor a un array y 
				// renuevo el valor del promedio y el contador 
				$promedio = $promedio / $contador;
				$promedio = substr($promedio, 0, 4);
				$estationTable[] = array("hora" => intval($horaX), "data" => floatval($promedio));
				$promedio = $data['temperatura'];
				$contador=0;
			}
			// Actualizo variable de última hora
			$ultimaHora = $horaX;
		}
		//var_dump($estationTable);
		// Inicializo variable de datos json y eje x
		$x = $jsonData = array();
		foreach ($estationTable as $data) {
			// Obtengo un solo array de datos y uno solo de horas en el eje x
			$jsonData[] = $data["data"];			
			$x[] = $data["hora"];
		}
		
		//$jsonString[] = array("name" => $estacion["estNombre"], "data" => $jsonData, "visible"=>($estacion["id"]==$idEstacion)?true:false);
		//$xAxis[] =	
		//if(isset($xAxisTemp))	
		$xAxisTemp = $x;
		$nombre_estacion = $est["estNombre"];
		$seriesTemp[] = array("name" => $est["estNombre"], "data" => $jsonData);
//}		
$xAxis = $series = array();
$xAxis = json_encode(array_reverse($xAxisTemp));
//foreach ($seriesTemp as $ser) {
	
	echo $xAxis;
	//$series[] = json_encode(array("name" => $seriesTemp["name"], "data" => $ser["data"]));
	
	//echo $series;
//}
$series = json_encode(array("name" => $est["estNombre"], "data" => array_reverse($jsonData) ));
		echo $series;
		//return array($xAxis, $series);	
		//var_dump($series);
//}


$oMySQL -> closeConnection();		
		 	
	

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
		<script src="http://code.highcharts.com/highcharts.js"></script>
	    <script src="http://code.highcharts.com/modules/exporting.js"></script>
	    <script>
		$(function () {
			
		
			
		temperaturasEstaciones =[<?php echo $series; ?>]/*,{
                name: 'New York',
                data: [0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5,0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }]/*, {
                name: 'New York',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Berlin',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'London',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }*/;
            
         console.log(temperaturasEstaciones);
            
        $('#container').highcharts({
            title: {
                text: 'Mediciones últimas 24 horas',
                x: -20 //center
            },
            subtitle: {
                text: 'Origen: Red Climatológica de Risaralda',
                x: -20
            },
            xAxis: {
                categories: <?php echo $xAxis; ?>/*['1', '2', '3', '4', '5', '6',
                    '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18',
                    '19', '20', '21', '22', '23', '24']
            */},
            yAxis: {
                title: {
                    text: 'Temperatura (°C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '°C'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series:temperaturasEstaciones/*[{
                name: 'Tokyo',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'New York',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Berlin',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'London',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]*/
        });
    });
		
	</script>
	</head>
	
	<body class="bg-cyan">		
		<div class="body">
			<!-- tabs -->
			<div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-slide-down sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs" checked id="sky-tab1" class="sky-tab-content-1">
				<label for="sky-tab1"><span><span><i class="fa fa-bolt"></i>Temperatura</span></span></label>
				
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
						<div class="typography">
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							<!--
							<h1>Nikola Tesla</h1>
							<p>Serbian-American inventor, electrical engineer, mechanical engineer, physicist, and futurist best known for his contributions to the design of the modern alternating current (AC) electrical supply system.</p>
							<p>Tesla started working in the telephony and electrical fields before emigrating to the United States in 1884 to work for Thomas Edison. He soon struck out on his own with financial backers, setting up laboratories/companies to develop a range of electrical devices. His patented AC induction motor and transformer were licensed by George Westinghouse, who also hired Tesla as a consultant to help develop an alternating current system. Tesla is also known for his high-voltage, high-frequency power experiments in New York and Colorado Springs which included patented devices and theoretical work used in the invention of radio communication, for his X-ray experiments, and for his ill-fated attempt at intercontinental wireless transmission in his unfinished Wardenclyffe Tower project.</p>
							<p>Tesla's achievements and his abilities as a showman demonstrating his seemingly miraculous inventions made him world-famous. Although he made a great deal of money from his patents, he spent a lot on numerous experiments. He lived for most of his life in a series of New York hotels although the end of his patent income and eventual bankruptcy led him to live in diminished circumstances. Tesla still continued to invite the press to parties he held on his birthday to announce new inventions he was working and make (sometimes unusual) statements. Because of his pronouncements and the nature of his work over the years, Tesla gained a reputation in popular culture as the archetypal "mad scientist". He died in room 3327 of the New Yorker Hotel on 7 January 1943.</p>
							<p class="text-right"><em>Find out more about Nikola Tesla from <a href="http://en.wikipedia.org/wiki/Nikola_Tesla" target="_blank">Wikipedia</a>.</em></p>
							-->
						</div>
					</li>
					
					<li class="sky-tab-content-2">
						<div class="typography" style="margin: 0 0 0 60px;">
							<iframe src="../galeria/index.php?id=<?php echo $idEstacion."&name=".$nombre_estacion; ?>" height="500px" width="420px"></iframe>
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
							<iframe src="../reportes/index.php?name=<?php echo $nombre_estacion; ?>" height="400px" width="540px"></iframe>
							<!--
							<h1>Albert Einstein</h1>
							<p>German-born theoretical physicist who developed the general theory of relativity, one of the two pillars of modern physics (alongside quantum mechanics). While best known for his mass–energy equivalence formula E = mc2 (which has been dubbed "the world's most famous equation"), he received the 1921 Nobel Prize in Physics "for his services to theoretical physics, and especially for his discovery of the law of the photoelectric effect". The latter was pivotal in establishing quantum theory.</p>
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
							<p>English physicist and mathematician who is widely regarded as one of the most influential scientists of all time and as a key figure in the scientific revolution. His book Philosophiæ Naturalis Principia Mathematica ("Mathematical Principles of Natural Philosophy"), first published in 1687, laid the foundations for most of classical mechanics. Newton also made seminal contributions to optics and shares credit with Gottfried Leibniz for the invention of the infinitesimal calculus.</p>
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