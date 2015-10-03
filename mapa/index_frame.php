<!DOCTYPE html>
<html>
	<head>
		<!-- No Cache 
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		-->
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>REDH</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="css/map.css" type="text/css">
		<!-- MAP -->
		<style>
			html, body, #map-canvas {
				height: 100%;
				margin: 0px;
				padding: 0px
			}
			.tcg{
				padding: 5px 5px; 
				position: absolute; 
				bottom: 0; 
				right:45%; 
				font-size:11px;  
				color: rgb(68, 68, 68);
				text-decoration: none; 
				cursor: pointer; 
				background-color: #fff;
				border-top-right-radius: 5px;
				border-top-left-radius: 5px; 
				/*
				-moz-border-radius: 5px; 
				-webkit-border-radius: 5px;
				 */
			}			
		</style>
		<!--
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		-->
                <link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script type="text/javascript" src="../home/js/jquery.js"></script>
                <script src="jquery-ui/jquery-ui.js"></script>
		<!-- &key=AIzaSyBA1M3-e9UO0KCvslfK44zM67ZPM77oy_o -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,weather,drawing"></script>
		
		<script src="js/estaciones.js"></script>
		<script src="js/mapa.js"></script>
		<script src="js/desplaza.js"></script>
<script>
			var session = false;
			var isMapOutOfDate = true;

			$(document).ready(function() {
				//$("#panel_log").html("Clic<img src='../home/img/wait_logout.gif' width='70%'>");
				checkSessionClick();

			});

			function checkSessionClick(url) {
				isSession = false;

				$.ajax({
					type : "POST",
					url : "../home/content/login/validate.php",
					async : false
				}).success(function(msg) {
					if (msg == "success") {
						
						

						session = true;
						showLogout(true);
						if (isMapOutOfDate && url != 'isFirstLoad') {
							filter_estation();
							isMapOutOfDate = false;
						}

					} else {
						session = false;
						showLogout(false);
					}
					//google.maps.event.addDomListener(window, 'load', initialize);
				});
				/*
				 if(isSession){
				 showLogout(true);
				 }else{
				 lookForSession();
				 }
				 */
				return isSession;
			}

			/**
			 * This function will logout and will hide the icon smoothly, if the logout is
			 * successful, then the lookForSession function will start looking for a new session
			 */
			function logout() {
				showLogout(false);
				isSession = true;
				$.ajax({
					type : "POST",
					url : "../home/content/login/logout.php"
				}).success(function(msg) {
					session = false;
					filter_estation();
					isMapOutOfDate = true;

				});

				//lookForSession();
			}

			/**
			 * Show or Hide logout icon
			 */
			function showLogout(showIcon) {
				if (showIcon) {
					$("#panel_log").removeClass("waitLogout").addClass("logout").hide().html("<span id='logoutImage'><a href='#' onclick='logout();'><img src='../home/img/logout_blue.png' title='Cerrar Sesión' style='width:28px;height:28px;' ></a></span>").fadeIn("slow");
				} else {
					$("#panel_log").removeClass("logout").addClass("waitLogout").fadeOut("slow").html("<img src='../home/img/wait_logout.gif' width='130%'>");
				}
                        }                        
		</script>
		
	<style>
		li{ text-align:left; vertical-align: middle;}
		ul li img{
			style="float:left;" 
		}
	</style>
	</head>
	<body>                
                <div id="content_inf">
                    <div id="information"><div id="text_inf" style="opacity: 1;"></div>                    
                        <div style="width: 95%;text-align: right;margin-top: 25px;opacity: 1;">
                            <button onclick="javascript:close_content_inf()">Aceptar</button>
                        </div>
                    </div>
                </div>
                <div id="msg_loader" ><img src="img/loader.gif" style="width: 25px;height: 25px;" /> Subiendo imagen...</div>
        <div id="panelInfo">
        	<ul style="list-style:none; alignment-adjust: left; margin-left:-35px;">
        		<li>
        			<img src="img/ECTnewblued.png"/> Est. Climatológica Telemétrica (ECT)
        		</li>
        		<li>
        			<img src="img/EHT25.png" />&nbsp&nbsp&nbspEst. Hidroclimatológica Telemétrica (EHT)
        		</li>
        		<li>
        			<img src="img/ENT.png" />&nbsp&nbsp&nbspEst. de Nivel Telemétrica (ENT)
        		</li>
        		<li>
        			<img src="img/caudal.png" />&nbsp&nbsp&nbspEst. de Caudal Telemétrica (EQT)
        		</li>
        		<li>
        			<img src="img/ECnew2.png" />Estaciones Climatológicas </br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        			No Telemétricas (EC)
        		</li>
        		<li>
        			<img src="img/nivelb23.png" />&nbsp&nbsp&nbspSensores de Nivel por Presión </br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        			de Lamina de Agua, no telemétricos (SN)
        		</li>
        		<li>
        			<img src="img/PD30.png" /> Pluviómetros con Datalogger,</br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        			no telemétricos (PD)
        		</li>
        		
        	</ul>
        </div>                        
		<div id="panel">
			<!-- <input onclick="cleanLines();" type=button value="Limpar Gráficos"> -->
            <form id="uploadForm" action="upload.php" method="post">
				<input id="uploadImage" name="uploadImage" type="file" value="Test" />
				<input type="submit" value="Subir kml o imagen" >
			</form>
			<input onclick="refreshMap();" type=button value="Refrescar Mapa" style="width: 100%">
			<div>
				<input type="checkbox" id="estationsOrVariables" name="type[]" value="1" onchange="showHideVariables()" />
				<span class="check_labels">Filtrar por Variables</span>
			</div>	
			<div id="estationsDiv">
			<input type="checkbox" id="check_estacion" name="estation[]" value="ECT EHT ENT EQT EC" onchange="filter_estation()" checked />
			<span class="check_labels">Estaciones</span>
			<input type="checkbox" id="check_sensor" name="estation[]" value="SN SNNT" onchange="filter_estation()"  checked />
			<span class="check_labels">Sensores</span>
			<input type="checkbox" id="check_pluviometro" name="estation[]" value="PDNT" onchange="filter_estation()" checked />
			<span class="check_labels">Pluviometros</span>
			</div>
			<div id="variablesDiv" style="display: none;">
				<input type="checkbox" id="check_temperatura" name="estation[]" value="temperatura" onchange="filter_estation(true)"  />
				<span class="check_labels">Temperatura</span>
				<input type="checkbox" id="check_presion" name="estation[]" value="presion" onchange="filter_estation(true)"   />
				<span class="check_labels">Presión</span>
				<input type="checkbox" id="check_humedad" name="estation[]" value="humedad" onchange="filter_estation(true)"  />
				<span class="check_labels">Humedad</span>
				<input type="checkbox" id="check_precipitacion" name="estation[]" value="precipitacion" onchange="filter_estation(true)"  />
				<span class="check_labels">Precipitación</span>
				<input type="checkbox" id="check_radiacion" name="estation[]" value="nivel" onchange="filter_estation(true)"   />
				<span class="check_labels">Nivel</span>
				<input type="checkbox" id="check_radiacion" name="estation[]" value="radiacion" onchange="filter_estation(true)"   />
				<span class="check_labels">Radiación</span>
				<input type="checkbox" id="check_velocidad" name="estation[]" value="velocidad" onchange="filter_estation(true)"  />
				<span class="check_labels">Velocidad</span>
				<input type="checkbox" id="check_direccion" name="estation[]" value="direccion" onchange="filter_estation(true)"  />
				<span class="check_labels">Dirección</span>
				<input type="checkbox" id="check_evapotranspiracion" name="estation[]" value="evapotranspiracion" onchange="filter_estation(true)"   />
				<span class="check_labels">Evapotranspiración</span>
				<input type="checkbox" id="check_caudal" name="estation[]" value="caudal" onchange="filter_estation(true)"   />
				<span class="check_labels">Caudal</span>				
			</div>
		</div>
		<div id="panel_log">
			<span id="logout" class="logout">
		</div>
		<!--<img class="weather" src="img/Weather-icon.png" title="Ver Clima Según Google" />-->
		<!-- <img class="gallinazos" src="img/gallinazos.png" title="Ver Gallinazos" /> -->
		<div id="panel_despl" >
			<img class="coordinate" src="img/embed-places-icon-45x45.png" title="Ubicar Coordenada" />
			<div class="panel1">
				<div >
					X (Este)
					<input type="text" onkeypress="return validateCharacter(event);" placeholder="--°--'--''" name="coordx" id="coordx" />
					Y (Norte)
					<input type="text" onkeypress="return validateCharacter(event);" placeholder="--°--'--''" name="coordy" id="coordy" />
					<button onclick="javascript:locate_position()">
						Ubicar
					</button>
				</div>

			</div>
			<!-- TODO: delete css related to this obsolete icon style
 			<a class="trigger" title="Ubicar coordenadas" href="#"></a>
			-->
		</div>
		<div id="map-canvas"></div>
		
		<div class="tcg">Powered by <a href="http://thinkcloudgroup.us" target="_blank" style="color:#1C5EA0; background-color: #fff;">Think Cloud Group</a></div>
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