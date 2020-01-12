
application/x-httpd-php estado_del_tiempo.php ( HTML document, UTF-8 Unicode text )
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta charset="utf-8">
		<title>Estado del Tiempo REDH</title>
		<script src="/lib/jquery.min.2.2.4.js"></script>
		<style>
			th {
				font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
				color: #6D929B;
				border-right: 1px solid #C1DAD7;
				border-bottom: 1px solid #C1DAD7;
				border-top: 1px solid #C1DAD7;
				letter-spacing: 2px;
				/*text-transform: uppercase;*/
				text-align: center;
				padding: 6px 6px 6px 12px;
				/*background: #CAE8EA url(images/bg_header.jpg) no-repeat;*/
			}

			th.nobg {
				border-top: 0;
				border-left: 0;
				border-right: 1px solid #C1DAD7;
				background: none;
			}

			th.spec {
				border-left: 1px solid #C1DAD7;
				border-top: 0;
				/*background: #fff url(images/bullet1.gif) no-repeat;*/
				font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
			}

			th.specalt {
				border-left: 1px solid #C1DAD7;
				border-top: 0;
				/*background: #f5fafa url(images/bullet2.gif) no-repeat;*/
				font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
				color: #B4AA9D;
			}

			td {
				border-right: 1px solid #C1DAD7;
				border-bottom: 1px solid #C1DAD7;
				background: #fff;
				padding: 6px 6px 6px 12px;
				color: #6D929B;
			}

			td.alt {
				background: #F5FAFA;
				color: #B4AA9D;
			}
			
			.loader {opacity: 0.4;	filter: alpha(opacity=40); /* For IE8 and earlier */ position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 1000; background: url('contactenos/wait.gif') no-repeat rgb(255,255,255) center center;
		</style>
		<script>
		$( document ).ready(function() {
			$(".loader").fadeOut("slow");
		    setTimeout("location.reload();",60000*5);		   		    
		});	
			
		</script>
	</head>
	<body>
		<h3 style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100; margin-left:10px;">La información se actualiza automáticamente cada 5 minutos</h3>
		<div class="loader"></div>
<?php
include_once ('../../lib/class.MySQL.php');

 mysql_set_charset('utf8');

$publicEstations = array("tb_san_jose", "tst_ellago", "tst_cortaderal", "tst_el_cedral", "tb_san_juan", "tst_el_nudo", "tst_quinchia", "tst_planes");

// This will get rid of the test stations
$idsToDelete = array(74,75,76,77,80);

$estationTable = $privateEstationTable = array();

$query = "SELECT * FROM tdp_stations";

$estacionesList = $oMySQL -> ExecuteSQL($query);

foreach ($estacionesList as $estacion) {
	$tabla = $estacion["tableName"];
	$query = "SELECT * FROM " . $tabla . " ORDER BY stationTime DESC LIMIT 1";
	$estacionesInfo = $oMySQL -> ExecuteSQL($query);
	if (in_array($tabla, $publicEstations)) {		
		$estationTable[] = array("estacion" => $estacion, "info" => $estacionesInfo);
	}else{
		// This will delete the 4 stations Camilo asked
		if(!in_array($estacion['idStation'], $idsToDelete)){
			// This will keep until the last 2 hours valid record (different than '-')
			if($estacionesInfo['level']=='-' /*&& $tabla=='bocatoma_nuevo_libare'*/){
				$estacionesInfoLast = $estacionesInfo;
				$counter = 1;
				do{
					$query = "SELECT * FROM " . $tabla . " ORDER BY stationTime DESC LIMIT ".$counter.",1";
					$estacionesInfo = $oMySQL -> ExecuteSQL($query);
					$counter++;
				}while($estacionesInfo['level']=='-' && $counter <24);
				if($estacionesInfo['level']=='-'){
					$estacionesInfo = $estacionesInfoLast;
				}
			}	
			$privateEstationTable[] = array("estacion" => $estacion, "info" => $estacionesInfo);
		}
	}
}

$oMySQL -> closeConnection();
?>
		<span style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100">
			<table style="width: 100%;">
				<tbody id="estado">
					<tr align="center">
						<th>Estación</th>
						<th><b>Temperatura (°C)</b></th>
						<th><b>Fecha Última Transmisión</b></th>
						<th>Dirección (°)</th>
						<th>Presión Barométrica (mm/mg)</th>
						<th>Humedad Relativa (%)</th>
						<th>Precipitación (mm)</th>
						<th>Nivel (cm)</th>
						<th>Radiación Solar ( W/m²)</th>				
						<th>Velocidad (m/s)</th>
						<th>Evapotranspiración (mm)</th>
						
						<!-- <th><b>Enlaces</b></th>
						<th><b>Símbolo</b></th> -->
					</tr>
					<?php
					foreach($estationTable as $et){
					echo "
					<tr align='center'>
						<td>".$et['estacion']['stationName']."</td>
						<td>".$et['info']['temperature']."</td>
						<td>".$et['info']['stationTime']."</td>						
						<td>".$et['info']['windDirection']."</td>
						<td>".$et['info']['presure']." </td>
						<td>".$et['info']['humidity']."</td>						
						<td>".$et['info']['realPrecipitation']."</td>
						<td>".$et['info']['level']."</td>
						<td>".$et['info']['radiation']."</td>				
						<td>".$et['info']['windSpeed']."</td>				
						<td>".$et['info']['realETO']."</td>						
					</tr>
					";
					}
					?>
				</tbody>
			</table> </span>
<?php
	session_start();			
	if($_SESSION['sessid']== session_id()){
		
?>		
			</br></br>
			<div align="center">
			<h3 style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100"> Sección de Empresa </h3>
			</div>
			</br></br>
			<span style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100">
			<table style="width: 100%;">
				<tbody id="estado">
					<tr align="center">
						<th>Estación</th>
						<th>Nivel (cm)</th>
						<th>Caudal (m3/s)</th>
						<th>Fecha Última Transmisión</th>
						
					</tr>
					<?php
					foreach($privateEstationTable as $pet){
					echo "
					<tr align='center'>
						<td>".$pet['estacion']['stationName']."</td>
						<td>".$pet['info']['level']."</td>
						<td>".$pet['info']['riverFlow']."</td>
						<td>".$pet['info']['stationTime']."</td>
														
					</tr>
					";
					}
					?>
				</tbody>
			</table> </span>
<?php			
}
?>
		</body>
</html>