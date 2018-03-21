<?php 
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Obtiene la conexión a la bd
include_once ('visit_models.php');
include_once ('../lib/class.MySQL.php');
$ip = "";   
// De esta forma se obtiene la Ip de host del usuario
if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip=$_SERVER['REMOTE_ADDR'];
}

$host = insert_host($oMySQL, $ip);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- No Cache 
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		-->

		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Red Hidroclimatológica de Risaralda</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="geo.placename" content="WC1B, london">
		<meta name="geo.position" content="51.51954;-0.125549" />
		
		<link rel="icon" href="favicon.ico" type="image/x-icon" />

		<link rel="stylesheet" href="css/themes/default/bootstrap.css" type="text/css">
		<link rel="stylesheet" href="css/themes/default/bootstrap-responsive.css" type="text/css">


		<link href="js/perfect-scrollbar-0.3.3/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--[if lt IE 9]>
		<link rel="stylesheet" href="css/bootstrap_ie7.css" type="text/css">
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- DEFAULT MAP 
		<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
		-->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
		<!--
		<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
		-->
		<script type="text/javascript" src="js/perfect-scrollbar-0.3.3/perfect-scrollbar.js"></script>
		<script type="text/javascript" src="js/global.js"></script>
		
		
		<!-- CUSTOM MAP -->
		<!--
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<!--<script src="js/estaciones.js"></script>
		<script src="js/mapa.js"></script>
		-->
		
    	<script src="../mapa/js/estaciones.js?rndstr=<?php  echo uniqid(); ?>"></script>
		
		
		<style>
			.loader {opacity: 0.4;	filter: alpha(opacity=40); /* For IE8 and earlier */ position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 1000; background: url('content/contactenos/wait.gif') no-repeat rgb(255,255,255) center center;}
			.logout {			    
			   	position: absolute;
				/*top: -85px;
				right: 38px;*/
				top: -54px;
				right: -435px;
				/*background-color: #333333;
				background-color: rgba(51, 51, 51, 0.8);
				*/height: 50px;
				margin: 0 -45px;
				margin-top: 20px;
			}
			.waitLogout {	
				opacity: 0.4;	filter: alpha(opacity=40); /* For IE8 and earlier */		    
			   	position: absolute;
				/*top: -85px;
				right: 130px;
				*/
				top: -53px;
				right: 39px;
				/*background-color: #333333;
				background-color: rgba(51, 51, 51, 0.8);
				*/height: 50px;
				margin: 0 -45px;
				margin-top: 20px;
 			}
 			a {text-decoration: none}
 			
 			.btn-custom-blue {
			  background-color: hsl(201, 100%, 30%) !important;
			  background-repeat: repeat-x;
			  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#00a5ff", endColorstr="#006399");
			  background-image: -khtml-gradient(linear, left top, left bottom, from(#00a5ff), to(#006399));
			  background-image: -moz-linear-gradient(top, #00a5ff, #006399);
			  background-image: -ms-linear-gradient(top, #00a5ff, #006399);
			  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #00a5ff), color-stop(100%, #006399));
			  background-image: -webkit-linear-gradient(top, #00a5ff, #006399);
			  background-image: -o-linear-gradient(top, #00a5ff, #006399);
			  background-image: linear-gradient(#00a5ff, #006399);
			  border-color: #006399 #006399 hsl(201, 100%, 25%);
			  color: #fff !important;
			  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);
			  -webkit-font-smoothing: antialiased;
			}

			.btn-custom-green {
			  
			  /*opacity: 0.95;*/
			  background-color: hsl(86, 79%, 44%) !important;
			  background-repeat: repeat-x;
			  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#daf6b5", endColorstr="#7cc817");
			  background-image: -khtml-gradient(linear, left top, left bottom, from(#daf6b5), to(#7cc817));
			  background-image: -moz-linear-gradient(top, #daf6b5, #7cc817);
			  background-image: -ms-linear-gradient(top, #daf6b5, #7cc817);
			  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #daf6b5), color-stop(100%, #7cc817));
			  background-image: -webkit-linear-gradient(top, #daf6b5, #7cc817);
			  background-image: -o-linear-gradient(top, #daf6b5, #7cc817);
			  background-image: linear-gradient(#daf6b5, #7cc817);
			  border-color: #7cc817 #7cc817 hsl(86, 79%, 34%);
			  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.66);
			  color: #111 !important;
			  -webkit-font-smoothing: antialiased;
			  font-size:16px;
			  margin-left: -10px;			  
			  moz-border-radius: 15px;
			  -webkit-border-radius: 2px;
			}
			.tcg{
				padding: 2px 2px; 
				position: absolute; 
				bottom: -5px; 
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
		
		
		<script src="js/index.js"></script>
		<script src="js/mapa/mapa.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row-fluid main-row">
				<div class="span5 main-wrapper">
					<div class="row-fluid logo">
						<div class="span12">
							<img src="img/logos/red_hidroclimatologica.jpg" width="80%" />
							<hr />
							<!-- <h1>Red Hidroclimatológica de Risaralda</h1>-->
						</div>
					</div><!--/logo-->

					<div class="row-fluid block-wrapper" style="margin: -30px 0 0 0; /*height: 57%; overflow: scroll;*/ " >
						<div class="block" id="section-1">
							<!--/floating-wrapper-->
							<div class="row-fluid floating-boxes" style="/*overflow: scroll;*/">
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="1" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Información General</h3> </a>
								</div>
								
								<div align="center">
									<a href="#" id="estado_tiempo" onclick="checkSessionClick();" data-section="5" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Estado del Tiempo</h3> </a>
								</div>

								<div align="center">
									<a href="#" id="notice_boton" onclick="checkSessionClick();" data-section="2" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Noticias y Eventos</h3> </a>
								</div>

								<!--
								<div align="center">

								<a href="#" data-section="4" data-title="" class="floating-box"> <h3>Boletines</h3> </a>
								</div>
								-->
								
								
								<!--
								<div align="center">
								<a href="#" data-section="6" data-title="" class="floating-box"> <h3>Galería</h3> </a>
								</div>
								-->
								

								<!--
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="8" data-title="" class="floating-box"> <h3>Contáctenos</h3> </a>
								</div>
								-->
								
																
								<!--
								<div align="center">
									<a href="#" onclick="checkSessionClick('login');" data-section="9" data-title="" class="floating-box"> <h3>Login</h3> </a>
								</div>
								-->
								<div align="center">
									<a href="#" id="recursos_boton" onclick="checkSessionClick();" data-section="3" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Recursos Humanos</h3> </a>
								</div>
								<!--
								<div align="center">

									<a href="../mapa/" onclick="checkSessionClick();" target="_blank" style="text-decoration: none;"> <h3>Ver Mapa Completo</h3> </a>
								</div>
								-->
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="7" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Contribuyen a la Red</h3> </a>
								</div>
								
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="10" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Sitios y Documentos de Interés</h3> </a>
								</div>
								
								 <div align="center">
								    Mayor Información: </br>
								    Facultad de Ciencas Ambientales Of f-213 </br> 
									Juan Camilo Berrio Carvajal </br>
									Telefono: 3137249 </br></br></br>
								</div> 								
							</div>							
						</div>								
					</div>
					</br></br></br></br></br></br></br></br></br></br>
						</br></br></br></br></br></br></br></br></br></br>
						</br></br></br></br></br></br></br></br></br></br>
						</br></br></br></br></br></br></br></br></br></br>

						<div class="block" id="section-1">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="row-fluid">
											<div class="span6">
												<div class="media">
													<div class="media-body">
														<iframe src="content/information_general.html" width="99%" height="480px" scrolling="yes" frameBorder="0"></iframe>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-2">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="row-fluid">
											<div class="span6">
												<div class="media">
													<div class="media-body">
														<iframe src="content/noticias.php" width="99%" height="480px" scrolling="yes" frameBorder="0"></iframe>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-3">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="content/recursos_humanos.php" width="99%" height="480px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-4">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="../reportes/index.html" width="100%" height="480px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-5">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div>
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body" style="margin: 0 0 0 -30px;">	
															<div class="loader"></div>														
															<iframe src="content/estado_del_tiempo.php" onload="$('.loader').fadeOut('slow');" width="100%" height="480px" scrolling="yes" frameBorder="0"><div class="loader"></div></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-6">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="../galeria/index.html" width="100%" height="480px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-7">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="content/contribuyen_a_la_red.html" width="100%" height="480px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-8">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="content/contactenos/demo.php" width="100%" height="500px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->

						<div class="block" id="section-9">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="content/login/index.php" width="100%" height="500px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->		
						
						<div class="block" id="section-10">
							<div class="row-fluid leave-gap">
								<div class="span12">
									<div class="section-content">
										<div class="span10 offset1 gallery">
											<div class="row-fluid">
												<div class="span6">
													<div class="media">
														<div class="media-body">
															<iframe src="content/sitios_de_interes.html" width="100%" height="500px" scrolling="yes" frameBorder="0"></iframe>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end block -->		
					
					
					
					
					
					
					
					
					
					
					
					
					<!-- end block-wrapper -->

					<div class="row-fluid leave-gap page-footer">
				       <hr />
						<div class="span12" style="margin-left:-10px;">
							<div style="text-align: center; ">
								<img src="img/logos/ciencias ambientales.png" width="20%" />	
								<img src="img/logos/logo_acuaseo.jpg" width="13%">
								<img src="img/logos/Alcaldia Pereira.jpg" width="15%">
								<img src="img/logos/Carder.png" width="20%">
								<!-- <img src="img/logos/logo_utp.jpg" width="20%"> -->
								<img src="img/logos/identificador-vertical.jpg" width="20%">
								<img src="img/logos/NUEVO LOGO AGUAS Y AGUAS DE PEREIRA.jpg" width="22%">
								<img src="img/logos/dopad.bmp" width="15%">
								<img src="img/logos/Seafield_colour.jpg" width="25%">
								<img src="img/logos/EEP.jpg" width="20%" style="margin: 0 0 0 10px;">
								<img src="img/logos/Grupos EIS.png" width="12%">
								<img src="img/logos/LOGO ASAMUN.jpg" width="30%">
							</div>
						</div>
					</div><!--/page-footer-->
				</div>
				<!-- end main-wrapper -->

				<div class="span7 span-fixed-sidebar">
					<div class="row-fluid" style="height: 100%;">
						<div class="span12" style="height: 100%;">
							<div id="map_canvas"></div>
							<!--<div style="position: absolute; bottom: -2px; right:44%; font-size:11px;">Developed by <a href="http://thinkcloudgroup.com" target="_blank">Think Cloud Group</a></div>-->
							<div class="tcg">Powered by <a href="http://thinkcloudgroup.com" target="_blank" style="color:#1C5EA0; background-color: #fff;">Think Cloud Group</a></div>
							<!--<iframe src="../mapa/" width="100%" height="800px"></iframe>-->
						</div>
					</div>

					<div class="floating-search span4">
						<div>
							<!--
							<form action="" id="search_directions" method="post">
								<input type="text" id="search" placeholder="Enter your location..."/>
								<a href="#" id="search-btn"><i class="icon-search"></i></a>
							</form>
							-->
							<!-- -->                                     
                            <span id="logout" class="logout"></span>
							<input id="search"  type="text" placeholder="Ingrese Ubicación">
                                                        <div style="color:#929292;float:right;margin-top: 30px;margin-right: 3px;font-family: Roboto;">
                                                            Visitas <span><?php echo add_ceros($host,4); ?></span>
                                                            
                                                            <?php
                                                            function add_ceros($numero,$ceros) {
                                                                $order_diez = explode(".",$numero);
                                                                $dif_diez = $ceros - strlen($order_diez[0]);
                                                                for($m = 0 ; $m < $dif_diez; $m++)
                                                                {
                                                                        $insertar_ceros .= 0;
                                                                }
                                                                return $insertar_ceros .= $numero;
                                                            }
                                                            
                                                            ?>
                                                        </div>
						    <div id="type-selector" class="controls" hidden="hidden">
						      <input type="radio" name="type" id="changetype-all" checked="checked">
						      <label for="changetype-all">All</label>
						      <input type="radio" name="type" id="changetype-establishment">
						      <label for="changetype-establishment">Establishments</label>
						      <input type="radio" name="type" id="changetype-geocode">
						      <label for="changetype-geocode">Geocodes</label>
						    </div>
						</div>

						<div id="directions-panel" class="span12">
							<button type="button" class="close">
								&times;
							</button>
							<div id="directions-wrapper">
								<div id="directions-result"></div>
							</div>
						</div>
						<div class="span12">
							<input type="button" class="btn-custom-green" style="width: 100%;" value="Ver Herramientas del Mapa"/>
						</div>
					</div>

					<!--/floating-search-->
					<div class="row-fluid floating-boxes">		
						<a href="#" data-section="5" onclick="checkSessionClick();" data-title="" class="offset1 span3 floating-box">
						<h3>Estado del Tiempo</h3>
						<p>Revisa las últimas mediciones</p>
						</a>
						<a href="#" onclick="checkSessionClick('login');" data-section="9" data-title="" class="span3 floating-box">
						<h3>Empresa</h3>
						<p>Acceso a empresa</p>						
						</a>
						<a href="#" data-section="8" data-title="" class="span3 floating-box">
						<h3>Contactenos</h3>
						<p>Dejenos saber sus comentarios</p>
						</a>
						
					</div>
					<!--/floating-boxes-->
					
					<div class="row-fluid floating-wrapper" style="top: -600px">
						<div class="offset1 span10 top-span">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								&times;
							</button>
							<h3>Video</h3>
							<p>
								Duis mollis, est non commodo luctus, nisi erat porttitor ligula.
							</p>
							<div class="row-fluid">
								<div class="floating-container">
									<div class="span12 floating-content">
										<div class="row-fluid">
											Empty
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<!-- end span-fixed-sidebar -->
			</div><!--/main-row-->
		</div><!--/.container-fluid-->
	</body>
</html>