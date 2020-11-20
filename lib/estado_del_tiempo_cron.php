<?php
include_once ('../../lib/class.MySQL.php');

mysql_set_charset('utf8');

$estationTable = $privateEstationTable = array();

// Get UTP stations and save them into
$query = "CALL sp_estado_del_tiempo_private();";

$estacionesListUTP = $oMySQL -> ExecuteSQL($query);

$oMySQL->closeConnection();
unset($oMySQL);

session_start();			
// Validate if there is session in order to add private stations
if($_SESSION['sessid']== session_id()){		
    $oMySQL = new MySQL($dbsigName, $bdsigUser, $bdsigPassword, $bdsigIp);

    mysql_set_charset('utf8');

    $query = "CALL sp_estado_del_tiempo_private();";

    $estacionesListUTPPrivate = $oMySQL -> ExecuteSQL($query);

    $oMySQL->closeConnection();
    unset($oMySQL);
}

// Connect to the aguasName
$oMySQL = new MySQL($db_aguasName, $bd_aguasUser, $bd_aguasPassword, $bd_aguasIp);	

mysql_set_charset('utf8');

$query = "CALL sp_estado_del_tiempo();";

$estacionesListAguas = $oMySQL -> ExecuteSQL($query);

$oMySQL->closeConnection();

unset($oMySQL);
?>