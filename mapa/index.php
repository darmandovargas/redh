<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>REDH Virtual</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<link rel="icon" href="favicon.ico" type="image/x-icon" />

		<!-- MAP -->
		<style>
			html, body, #map-canvas {
				height: 100%;
				margin: 0px;
				padding: 0px
			}
		</style>
		<!--
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<!-- &key=AIzaSyBA1M3-e9UO0KCvslfK44zM67ZPM77oy_o -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,weather,drawing"></script>
		<script src="js/estaciones.js"></script>
		<script src="js/mapa.js"></script>
		<style>
			.controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #search {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      
      #panel {
        position: absolute;
        top: 87.6%;
        left: 85.2%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
			
		</style>
		
	</head>
	<body>
		<div id="panel">
			<!-- <input onclick="cleanLines();" type=button value="Limpar Gráficos"> -->
	      <form id="uploadForm" action="upload.php" method="post">
		      <input id="uploadImage" type="file" value="Seleccionar Imagen" >
		      <input type="submit" value="Subir Imagen" >
		  </form>
		  <input onclick="refreshMap();" type=button value="Refrescar Mapa" style="width: 100%">
	    </div>
		<div id="map-canvas"></div>
		
		<input id="search"  type="text" class="controls" placeholder="Ingrese Ubicación">
		
		<div id="type-selector" class="controls" >
			<input type="radio" name="type" id="changetype-all" checked="checked">
			<label for="changetype-all">Todo</label>

			<input type="radio" name="type" id="changetype-establishment">
			<label for="changetype-establishment">Establecimientos</label>

			<input type="radio" name="type" id="changetype-geocode">
			<label for="changetype-geocode">Geocoordenadas</label>
		</div>
	</body>
</html>