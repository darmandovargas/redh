<?php

include_once ('../../lib/class.MySQL.php');

//$publicEstations = array("tb_san_jose", "tb_ellago", "tb_cortaderal", "tb_el_cedral", "tb_san_juan", "tb_el_nudo", "tb_quinchia");
$privateEstations = array("canal_entrada_belmonte", "bocatoma_nuevo_libare", "bocatoma_belmonte", "planta_nuevo_libare", "canal_salida_belmonte", "planta_belmonte");

$estationTable = $privateEstationTable = array();

$query = "SELECT * FROM estaciones";

$estacionesList = $oMySQL -> ExecuteSQL($query);

foreach ($estacionesList as $estacion) {
	$tabla = $estacion["estNombreTb"];
	if (in_array($tabla, $privateEstations)) {		
		$privateEstationForJSON[] = $estacion;
	}else{
		$publicEstationForJSON[] = $estacion;
		
	}
}

$query = "SELECT * FROM estacione_sensores";

$estacione_sensoresList = $oMySQL -> ExecuteSQL($query);

foreach ($estacion_sensoresList as $estacion) {
	$tabla = $estacion["estNombreTb"];
	if (in_array($tabla, $privateEstations)) {		
		$privateEstationForJSON[] = $estacion;
	}else{
		$publicEstationForJSON[] = $estacion;
		
	}
}

$estacionesJSON = array();
/*
$estacionesTipo["hidrometeorologica"] = "Estaciones Hidroclimatológicas telemétricas (EHT)";
$estacionesTipo["meteorologica"] = "Estaciones Climatológica Telemétrica (ECT)";
$estacionesTipo["hidro_caudal"] = "";
$estacionesTipo["hidro_caudal"] = ;
$estacionesTipo["meteorologica"] = ;


foreach ($publicEstationForJSON as $pue) {
	$estacionesJSON[] = array(
			"id" => $pue->id,
  			"tipo"=> "",
		    "nombre"=> "ECT001 (El Lago)",
		    "variables"=> {
		      "0"=> "Temperatura",
		      "1"=> "Precipitacion",
		      "2"=> "Humedad Relativa",
		      "3"=> "Radiación Solar",
		      "4"=> "Presión Barométrica",
		      "5"=> "Velocidad del Viento",
		      "6"=> "Dirección del Viento"
		    },
		    "coordenadas"=> {
		      "latitud"=> 4.814751659,
		      "longitud"=> -75.69949032
		    },
		    "isPublic"=> true,
		    "tipo"=>"ECT",
		    "icono"=> iconoECT,
		    "altitud"=> "1450 m.s.n.m",
		    "ubicacion"=> "Centro Administrativo el Lago, Centro del municipio de Pereira",
		    "fecha"=> "Septiembre de 2006",
		    "estado"=> "Activa"	  );		
}
 */ 
 
?>