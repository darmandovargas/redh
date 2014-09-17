<?php

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

function search_content($oMySQL,$page){
    $query = "SELECT contenido FROM contenidos WHERE pagina='$page'";	
    $resultado = $oMySQL -> ExecuteSQL($query);

    if($resultado){
        return $resultado['contenido'];
    }else{
        return false;
    }
}

function insert_content($oMySQL,$page,$texto){
    
    $search = search_content($oMySQL,$page);
    if($search == false){        
        //$txt = limpiahtml($texto);
        $txt = utf8_decode($texto);
        $query = "INSERT INTO contenidos VALUES(null,'$page','$txt')";
        $resultado = $oMySQL -> ExecuteSQL($query);                
    }else{
        //$txt = limpiahtml($texto);
        $txt = utf8_decode($texto);
        $query = "UPDATE contenidos SET contenido = '$txt' WHERE pagina = '$page';";	
        $resultado = $oMySQL -> ExecuteSQL($query);
    }
    if($resultado){
        return true;
    }else{
        return false;
    }
}

function limpiahtml($codigo){
    $buscar = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
    $reemplazar = array('>','<','\\1');
    $codigo = preg_replace($buscar, $reemplazar, $codigo);
    $codigo = str_replace("> <", "><", $codigo);
    return $codigo;
}

?>
