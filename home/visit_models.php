<?php
// Timezone fix so now time creation returns a Colombian (Panama) Timezone in order to avoid issues with the diff
date_default_timezone_set("America/Pangnirtung");
/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   29-10-214
 * @description
 *      Script con funciones para ingresar, actualizar y controlar el número de visitas a la pagina
 * 
 */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   29-10-214
 * @description
 *      Metodo para buscar si la ip del cliente ya se encuentra en la tabla de visitantes
 * 
 * @param 
 *      $oMySQL = Objeto Mysql para conexion
 *      $ip = Ip del host del usuario que se conecta
 * 
 * @return
 *      Array con el campo id y last_date si encuentra la ip de lo contrario retorna false
 * 
 */

function search_host($oMySQL,$ip){
    $query = "SELECT id,last_date FROM ti_visitas WHERE host='$ip' limit 1";
    $resultado = $oMySQL -> ExecuteSQL($query);
    if($oMySQL->records > 0){
        return $resultado;
    }else{
        return false;
    }   
    
}

/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   29-10-214
 * @description
 *      Metodo para insertar o actualizar en la tabla visitas
 * 
 * @param 
 *      $oMySQL = Objeto Mysql para conexion
 *      $ip = $ip = Ip del host del usuario que se conecta
 * 
 * @return
 *      La cantidad de visitantes
 * 
 */


function insert_host($oMySQL,$ip){    
    $search = search_host($oMySQL,$ip);
    if($search == false){
        $ahora = date("Y-m-d H:i:s");
        $query = "INSERT INTO ti_visitas(host,last_date) VALUES('$ip','$ahora')";
        $resultado = $oMySQL -> ExecuteSQL($query);
        return search_num_visit($oMySQL);
    }else{
        $fecha_host = date($search['last_date']);
        $nuevafecha = strtotime ( '+1 hour' , strtotime ( $fecha_host ) ) ;        
        $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
        //print_r($nuevafecha);
        $ahora = date("Y-m-d H:i:s");
        $date1=date_create($nuevafecha);
        $date2=date_create($ahora);
        $diff=date_diff($date1,$date2);        
        /*print_r($date1);
        print_r($date2);
        print_r($diff->format("%h"));
        */        
        
        if($diff->format("%h") > 0 ){            
            $id = $search['id'];
            $query = "UPDATE ti_visitas SET last_date = NOW(),visitas=visitas+1 WHERE id = '$id';";	
            $resultado = $oMySQL -> ExecuteSQL($query);
        }         
        return search_num_visit($oMySQL);
    }
}

/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   29-10-214
 * @description
 *      Metodo para sumar el número de visitantes a la pagina
 * 
 * @param 
 *      $oMySQL = Objeto Mysql para conexion
 * 
 * @return
 *      La cantidad de visitantes
 * 
 */

function search_num_visit($oMySQL){
    $query = "SELECT sum(visitas)as visitas FROM ti_visitas";
    $resultado = $oMySQL -> ExecuteSQL($query);
    //return $oMySQL->lastError;
    if($oMySQL->records > 0){
        return $resultado['visitas'];
    }else{
        return false;
    }   
    
}

?>
