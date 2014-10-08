<?php
/**
 * @author Juan manuel Mora <juan_manuel28@hotmail.com>
 * @date   05-09-214
 * @description
 *      Metodo en php para cargar los archivos que se encuentra en el directorio que se especifica en la 
 *      busqueda de Reportes.
 * @param 
 *      $_REQUEST['boletin'] = Tipo de boletin
 *      $_REQUEST['estacion'] = Lugar o ubicación
 *      $_REQUEST['periocidad'] = Periodo que se va buscar
 *      $_REQUEST['fecha'] = Fecha en que se va realizar la busqueda
 * 
 * @return
 *      JSON = Retorna un vector en json con la respuesta y un cadena con el listado de archivos en html
 * 
 */
$respuesta = false;
$salida = "";
if($_REQUEST["boletin"] != "" && $_REQUEST["estacion"] != "" && $_REQUEST["periocidad"] != "" && $_REQUEST["fecha"] != ""){
    $path = "";
    if(isset($_REQUEST['mes']))
        $path = "/".$_REQUEST['mes'];
    $directory = "boletines/".$_REQUEST["boletin"]."/".$_REQUEST["estacion"]."/".$_REQUEST["fecha"]."/".$_REQUEST["periocidad"].$path;
    //$directory= "images/estaciones/".$estacion;
    $dirint = dir($directory);
    $vector = array();
    if(!empty($dirint)){
        while (($archivo = $dirint->read()) !== false)
        {               
            if (eregi("pdf", $archivo)){
                  $respuesta = true;
                  array_push($vector,$archivo);
            }
        }
        $dirint->close();
    }   
    
    /*
     * Ordena archivos y arma las listas para previsualizar
     */
    sort($vector,SORT_STRING);    
    if(!empty($vector)){
        $salida = "<ul>";
        foreach ($vector as $valor)
        { 
                  $salida .= '<li><a target="_blank" href="'.$directory."/".$valor.'">'.$valor.'</a></li>';                                      
        }
        $salida .= "</ul>";
    } 
}
$salidaJson = array("respuesta" => $respuesta, "salida" => $salida);
echo json_encode($salidaJson);
?>
