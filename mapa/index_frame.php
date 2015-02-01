<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>REDH Virtual</title>

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
						//console.log("URL: "+url);
						/*
						 if(url=="login"){
						 setTimeout(function(){
						 closePage();
						 $('#estado_tiempo').click();
						 },1000);
						 }*/
						setTimeout(function() {
							/*$('.close').click(function (){
							 checkSessionClick();
							 });*/
						}, 1500);

						session = true;
						showLogout(true);
						if (isMapOutOfDate && url != 'isFirstLoad') {
							//filter_estation();
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
					$("#panel_log").removeClass("waitLogout").addClass("logout").hide().html("<span id='logoutImage'><a href='#' onclick='logout();'><img src='../home/img/logout.png' style='width:30px;height:30px;' ></a></span>").fadeIn("slow");
				} else {
					$("#panel_log").removeClass("logout").addClass("waitLogout").fadeOut("slow").html("<img src='../home/img/wait_logout.gif' width='70%'>");
				}
                        }                        
		</script>

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
		<div id="panel">
			<!-- <input onclick="cleanLines();" type=button value="Limpar Gráficos"> -->
                        <form id="uploadForm" action="upload.php" method="post">
				<input id="uploadImage" name="uploadImage" type="file"  >
				<input type="submit" value="Subir Imagen" >
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
		<div id="panel_despl" >
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
			<a class="trigger" title="Ubicar coordenadas" href="#"></a>
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