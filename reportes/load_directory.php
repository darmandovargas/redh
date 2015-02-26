<?php
/**
 * @date   11-09-214
 * @description
 *     Archivo php donde se cargan los dropdown y se buscan los ficheros de un directorio
 * @param 
 *      $_POST['actionID'] = Parametro post donde se recibe la acción que se va realizar
 *      $_POST['path'] = Ruta donde se va buscar
 *      $_POST['name'] = Nombre de la estacion que se va buscar
 * 
 * @return
 *      JSON = Muestra un Array JSON con el resultado y salida que genero la acción solicitada
 * 
 */
 
session_start();

$privateStations = array(
	"bocatoma nuevo libare",
	"bocatoma belmonte",
	"canal entrada belmonte",
	"canal salida belmonte",
	"planta nuevo libare",
	"planta belmonte",
);

$respuesta = false;
$salida = "";
$name = "";
switch ($_POST['actionID']) {
    case 'chargue_dp':
            $path = $_POST['path'];
            if(isset($_POST['name']))
                $name = $_POST['name'];
            
            $search = scandirectory($path,$name);
            if($search != ""){
                $respuesta = true;
                $salida = $search;
            }
        break;
    case 'search_directory':
            $name = $_POST['name'];
            $carpeta = $_POST['folder'];
            $tipo = $_POST['tipo'];
            $search = search_directory_estation($name,$carpeta,$tipo);
            if($search != ""){
                $respuesta = true;
                $salida = $search;
            }
        break;
    default:
        break;
}

/**
 * @date   11-09-214
 * @description
 *      Metodo para leer los ficheros de un directorio y crear las opciones de los dropdown del formulario
 * @param 
 *      $path = Ruta donde se va buscar
 *      $name = Nombre de la estacion a buscar
 * 
 * @return
 *      $salida = String con el html para alimentar los dropdown
 * 
 */
    
function scandirectory($path,$name){
    $salida = "";
    if($path != ""){
        $directorio = "boletines/".$path;
        $gestor_dir = opendir($directorio);
        if($gestor_dir){
        	$files = scandir($directorio);
            foreach ($files as $nombre_fichero) {	
                if($nombre_fichero != "." && $nombre_fichero != ".." && $nombre_fichero != ".DS_Store" && $nombre_fichero != "Icon_"){
                    $selected = "";
                    if($name != "" && $nombre_fichero == strtolower($name))
                        $selected = "selected='selected'";
					//Filtra estaciones privadas 
					if($_SESSION['sess']){
						$salida .= "<option value='".$nombre_fichero."' $selected >".ucwords($nombre_fichero)."</option>";	
					}else{
						if(!in_array($nombre_fichero, $GLOBALS['privateStations'])){
							$salida .= "<option value='".$nombre_fichero."' $selected >".ucwords($nombre_fichero)."</option>";
						}
					}
                }
            }   
        }
        return $salida;
    } 
}

/**
 * @date   11-09-214
 * @description
 *      Metodo para buscar una estacion en los 3 directorios creados para almacenar las estaciones
 * @param 
 *      $name = Nombre de la estacion a buscar
 * 
 * @return
 *      $search = String con el del directorio donde se encuentra la carpeta de la estacion
 * 
 */

function search_directory_estation($name,$carpeta,$tipo){
    $search = "";
    if($name != "" || $carpeta != ""){
        if(search_directory("estaciones",$name)){
            $search = "estaciones";
        }else if(search_directory("sensores",$name)){
            $search = "sensores";
        }else if(search_directory("pluviometros",$name,$carpeta,$tipo)){
            $search = "pluviometros";
        }else{
            $search = "";
        }
    }
    return $search;
}

/**
 * @date   11-09-214
 * @description
 *      Metodo para leer los directorios y encontrar el que se busca
 * @param 
 *      $path = Ruta donde se va buscar
 *      $name = Nombre de la estacion a buscar
 * 
 * @return
 *      $respuesta = Booleano false sino lo encuentra true si lo encuentra
 * 
 */

function search_directory($path,$name,$carpeta,$tipo){
    $respuesta = false;
    if($name != "" || $carpeta != ""){
        if ($tipo == "PD" && $carpeta != "") {
            $name = $carpeta;
        }
        $directorio = "boletines/".$path;
        $gestor_dir = opendir($directorio);
        if($gestor_dir){
            while (false !== ($nombre_fichero = readdir($gestor_dir))) {
                if($nombre_fichero == strtolower($name)){
                    $respuesta = true;
                }
            }   
        }
    } 
    return $respuesta;
}

$salidaJson = array("respuesta" => $respuesta, "salida" => $salida, "name" => $name);
echo json_encode($salidaJson);

?>
