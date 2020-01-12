<?php

/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   15-09-214
 * @description
 *      Script para insertar y buscar el contenido de las paginas que son editables
 * @param 
 *      $_REQUEST['actionID'] = Acción a realizar cuando se hace un petición por ajax
 * 
 */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ('../../lib/class.MySQL.php');

switch ($_REQUEST['actionID']) {
    case 'insert':
        $texto = $_REQUEST['texto'];
        $page = $_REQUEST['page'];
        $resultado = insert_content($oMySQL,$page,$texto);        
        $salidaJson = array("respuesta" => $resultado);
        echo json_encode($salidaJson);
        break;
    case 'search':
        $page = $_REQUEST['page'];
        $resultado = search_content($oMySQL,$page);        
        $salidaJson = array("respuesta" => $resultado);
        echo json_encode($salidaJson);
    default:
        break;
}

/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   16-09-214
 * @description
 *      Metodo para buscar el contenido de una pagina
 * 
 * @param 
 *      $oMySQL = Objeto Mysql para conexion
 *      $page = Cadena con la pagina que se va buscar
 * 
 * @return
 *      Cadena con el resultado si lo encuentra de lo contrario boleano false
 * 
 */

include_once('error.php');
include '../../lib/db.php';

function search_content($oMySQL,$page){
    //echo "flusing...";
    $query = "mysqladmin flush-hosts";	
    $resultado = $oMySQL -> ExecuteSQL($query);    
    //echo "connecting to the database...";
    $query = "SELECT contenido FROM ti_contenidos WHERE pagina='$page'";	
    $resultado = $oMySQL -> ExecuteSQL($query);
    

    if($resultado){
        //echo "Successful...";
        return $resultado['contenido'];
    }else{
        //echo "ERROR...";
        return false;
    }
}

function search_contentImproved($oMySQL,$page){

    $dbhost = '201.131.90.140';
    $dbuser = 'usrAdmin';
    $dbpass = '$admin2018.';
    $dbname = 'albatros_db_utp';

    $db = new db($dbhost, $dbuser, $dbpass, $dbname);

    

    //echo "flusing...";
    //$query = "mysqladmin flush-hosts";	
    //$resultado = $oMySQL -> ExecuteSQL($query);    
    //echo "connecting to the database...";
    $query = "SELECT contenido FROM ti_contenidos WHERE pagina='$page'";	
    //echo $query;

    $resultado = $db->query($query)->fetchAll();

    //print_r($resultado);

    $db->close();
    
    //$resultado = $oMySQL -> ExecuteSQL($query);
    

    if($resultado){
        //echo "Successful...";
        return $resultado[0]['contenido'];
    }else{
        //echo "ERROR...";
        return false;
    }
}

/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   16-09-214
 * @description
 *      Metodo para insertar o actualizar contenido
 * 
 * @param 
 *      $oMySQL = Objeto Mysql para conexion
 *      $page = Cadena con la pagina que se va buscar
 *      $texto = Cadena con el texto que se va almacenar
 * 
 * @return
 *      Booleano true si realizo la acción false si hubo problemas
 * 
 */


function insert_content($oMySQL,$page,$texto){
    
    $search = search_content($oMySQL,$page);
    if($search == false){
        $txt = utf8_decode($texto);
        $query = "INSERT INTO ti_contenidos VALUES(null,'$page','$txt')";
        $resultado = $oMySQL -> ExecuteSQL($query);                
    }else{
        $txt = utf8_decode($texto);
        $query = "UPDATE ti_contenidos SET contenido = '$txt' WHERE pagina = '$page';";	
        $resultado = $oMySQL -> ExecuteSQL($query);
    }
    if($resultado){
        return true;
    }else{
        return false;
    }
}

?>
