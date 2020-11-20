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
			
			.loader {
				opacity: 0.4;	filter: alpha(opacity=40); /* For IE8 and earlier */ position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 1000; background: url('contactenos/wait.gif') no-repeat rgb(255,255,255) center center;
			}
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
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include_once ('../../lib/class.MySQL.php');
mysql_set_charset('utf8');
$estationTable = $privateEstationTable = array();
$query = "SELECT * FROM ti_estado_del_tiempo";
$estacionesListUTP = $oMySQL -> ExecuteSQL($query);
$oMySQL->closeConnection();
unset($oMySQL);
session_start();			

if($_SESSION['sessid']== session_id()){		
	$oMySQL = new MySQL($dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp);
	mysql_set_charset('utf8');
	$query = "SELECT * FROM ti_estado_del_tiempo_private";
	$estacionesListUTPPrivate = $oMySQL -> ExecuteSQL($query);
	$oMySQL->closeConnection();
	unset($oMySQL);
}

$oMySQL = new MySQL($db_aguasName, $bd_aguasUser, $bd_aguasPassword, $bd_aguasIp);	
mysql_set_charset('utf8');
$query = "SELECT * FROM ti_estado_del_tiempo";
$estacionesListAguas = $oMySQL -> ExecuteSQL($query);
$oMySQL->closeConnection();
unset($oMySQL);
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
					foreach($estacionesListUTP as $et){
					echo "
					<tr align='center'>
						<td>".$et['tableName']."</td>
						<td>".$et['temperature']."</td>
						<td>".$et['stationTime']."</td>						
						<td>".$et['windDirection']."</td>
						<td>".$et['presure']." </td>
						<td>".$et['humidity']."</td>						
						<td>".$et['realPrecipitation']."</td>
						<td>".$et['level']."</td>
						<td>".$et['radiation']."</td>				
						<td>".$et['windSpeed']."</td>				
						<td>".$et['realETO']."</td>						
					</tr>
					";
					}

					foreach($estacionesListAguas as $et){
						echo "
						<tr align='center'>
							<td>".$et['tableName']."</td>
							<td>".$et['temperature']."</td>
							<td>".$et['stationTime']."</td>						
							<td>".$et['windDirection']."</td>
							<td>".$et['presure']." </td>
							<td>".$et['humidity']."</td>						
							<td>".$et['realPrecipitation']."</td>
							<td>".$et['level']."</td>
							<td>".$et['radiation']."</td>				
							<td>".$et['windSpeed']."</td>				
							<td>".$et['realETO']."</td>						
						</tr>
						";
						}
					?>
				</tbody>
			</table> </span>
<?php
	
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
					foreach($estacionesListUTPPrivate as $pet){
						//if(!empty($pet['level']) || !empty($pet['riverFlow'])){
							echo "
							<tr align='center'>
								<td>".$pet['tableName']."</td>
								<td>".$pet['level']."</td>
								<td>".$pet['riverFlow']."</td>
								<td>".$pet['stationTime']."</td>
																
							</tr>
							";
						//}
					}
					?>
				</tbody>
			</table> </span>
<?php			
}
?>
		</body>

		
</html>