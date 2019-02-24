<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Estaciones</title>

<style type="text/css">
body {/*margin:50px 0px;*/margin:0px 0px; padding:0px; /*background-color: #000000;*/background-color: #EAF5F3; color: #ffffff;}
#content {/*width:620px; margin:0px auto;*/background-color: #EAF5F3;}
#desc {/*margin:10px;*/margin:0px; float:left; font-family: Arial, sans-serif; font-size: 12px;}
</style>

<!-- include CSS always before including js -->
<link type="text/css" rel="stylesheet" href="skins/tn3/tn3.css"></link>
<!-- include jQuery library -->
<script src="/lib/jquery.min.2.2.4.js"></script>
<!-- include tn3 plugin -->
<script type="text/javascript" src="js/jquery.tn3lite.min.js"></script>

<!--  initialize the TN3 when the DOM is ready -->
<script type="text/javascript">
	$(document).ready(function() {
		//Thumbnailer.config.shaderOpacity = 1;
		var tn1 = $('.mygallery').tn3({
			skinDir : "skins",
			playClick: true,
			//imageClick : "fullscreen",
			image : {
				maxZoom : 1.5,
				crop : true,
				clickEvent : "dblclick",
				transitions : [{
					type : "blinds"
				}, {
					type : "grid"
				}, {
					type : "grid",
					duration : 60,
					easing : "easeInQuad",
					gridX : 1,
					gridY : 8,
					// flat, diagonal, circle, random
					sort : "random",
					sortReverse : false,
					diagonalStart : "bl",
					// fade, scale
					method : "scale",
					partDuration : 60,
					partEasing : "easeOutSine",
					partDirection : "left"
				}]
			}
		});
		$(".tn3-play").click();
	});
</script>
</head>
<body>
    <div id="content">
    <div class="mygallery">
	<div class="tn3 album">
	    <h4>Fixed Dimensions</h4>
	    <div class="tn3 description">Images with fixed dimensions</div>
	    <div class="tn3 thumb">images/35x35/1.jpg</div>
	    <ol>
                <?php
                    /**
                    * @author Juan manuel Mora <juan_manuel28@hotmail.com>
                    * @date   05-09-214
                    * @description
                    *      Se crea el ciclo para leer el directorio de la estacion para mostrar en la galeria
                    * @param 
                    *      $_REQUEST['id'] = Id de la estacion
                    *      $_REQUEST['tipo'] = Tipo de la estacion 
                    */
                    //error_reporting(-1);
					//ini_set('display_errors', 'On');
                    
                    $estacion = $_REQUEST["id"];
                    $tipoEstacion = $_REQUEST["tipo"];
                    if($tipoEstacion == "PDNT")
                        $estacion = "pluv";
                    elseif($_REQUEST["folder"] != "undefined")
                        $estacion = $_REQUEST["folder"];                        					
//                    else if($tipoEstacion == "SNNT" || $tipoEstacion == "SN")
//                        $estacion = "sensor";
//                    else
//                        $estacion = "sensor";                   
                    $nombre  = $_REQUEST["name"];
                    $directory= "images/estaciones/".$estacion;
                    $dirint = dir($directory);
					
					//print_r($dirint);
					
					$isEmpty = true;
					if(file_exists($directory)) {
                    	while (($archivo = $dirint->read()) !== false){
                            if (!in_array($archivo, array('.', '..')) &&  (eregi("gif", $archivo) || eregi("jpg", $archivo) || eregi("png", $archivo))){
                            	$isEmpty = false;
                                ?>
                                <li>
                                    <h4><?php echo $nombre; ?></h4>
                                    <?php $descripcion = explode("_", $archivo); ?>
                                    <div class="tn3 description"><?php echo $descripcion[0]; ?></div>
                                    <a href="<?php echo $directory."/".$archivo; ?>" >
                                        <?php  echo '<img src="'.$directory."/".$archivo.'" />'; ?>
                                    </a>
                                </li>     
                                 <?php                            
                            }
                        }
                    }
						if($isEmpty){
								?>
                                <li>
                                    <h4></h4>
                                    <div class="tn3 description"></div>
                                    <a href="images/nodisponible.gif" >
                                        <?php  echo '<img src="images/nodisponible.gif" >'; ?>
                                    </a>
                                </li>     
                                 <?php   
                            
						}
                    
                    $dirint->close();
                ?>
	    </ol>
	</div>
    </div>
    <!--
    <div id="desc">
	<p>Note that 'blinds' and 'grid' transition types work only if the images are of same size and not scaled. If you choose album with large images and because 'crop' options is turned on, you will see default transition('slide') instead of 'blinds' and 'grid' types.</p>
    </div>
    -->
</div>

<?php
//echo $filecount;
//echo "directory: ".$directory;
?>
</body>
</html>



