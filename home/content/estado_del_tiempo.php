<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Estado del Tiempo REDH</title>
		<script src='../../lib/jquery-1.11.1.min.js'></script>
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
		<div class="loader"></div>
<?php
include_once ('../../lib/class.MySQL.php');

$publicEstations = array("tb_san_jose", "tb_ellago", "tb_cortaderal", "tb_el_cedral", "tb_san_juan", "tb_el_nudo", "tb_quinchia");

$estationTable = $privateEstationTable = array();

$query = "SELECT * FROM estaciones";

$estacionesList = $oMySQL -> ExecuteSQL($query);

foreach ($estacionesList as $estacion) {
	$tabla = $estacion["estNombreTb"];
	$query = "SELECT * FROM " . $tabla . " ORDER BY fecha DESC LIMIT 1";
	$estacionesInfo = $oMySQL -> ExecuteSQL($query);
	if (in_array($tabla, $publicEstations)) {		
		$estationTable[] = array("estacion" => $estacion, "info" => $estacionesInfo);
	}else{
		$privateEstationTable[] = array("estacion" => $estacion, "info" => $estacionesInfo);
	}
}

//var_dump($privateEstationTable);

$oMySQL -> closeConnection();
?>
		<span style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100">
			<table style="width: 100%;">
				<tbody id="estado">
					<tr align="center">
						<th>Estación</th>
						<th><b>Temperatura (°C)</b></th>
						<th><b>Fecha Última Transmisión</b></th>
						<th><b>Hora Última Transmisión</b></th>
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
						<td>".$et['estacion']['estNombre']."</td>
						<td>".$et['info']['temperatura']."</td>
						<td>".$et['info']['fecha']."</td>
						<td>".$et['info']['hora']."</td>
						<td>".$et['info']['direccion']."</td>
						<td>".$et['info']['presion']." </td>
						<td>".$et['info']['humedad']."</td>						
						<td>".$et['info']['precipitacion_real']."</td>
						<td>".$et['info']['nivel']."</td>
						<td>".$et['info']['radiacion']."</td>				
						<td>".$et['info']['velocidad']."</td>				
						<td>".$et['info']['evapotranspiracion_real']."</td>						
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
						<th>Hora Última Transmisión</th>
					</tr>
					<?php
					foreach($privateEstationTable as $pet){
					echo "
					<tr align='center'>
						<td>".$pet['estacion']['estNombre']."</td>
						<td>".$pet['info']['nivel']."</td>
						<td>".$pet['info']['caudal']."</td>
						<td>".$pet['info']['fecha']."</td>
						<td>".$pet['info']['hora']."</td>											
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