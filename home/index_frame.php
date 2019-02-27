<?php 
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Obtiene la conexión a la bd
include_once ('visit_models.php');
include_once ('../lib/class.MySQL.php');

// session_start();
if(isset($_SESSION['sessid']) && $_SESSION['sessid'] == session_id()){
	$_SESSION['sess'] = true;		
}else{
	$_SESSION['sess'] = false;
}

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
		<link rel="stylesheet" href="css/font/fontawesome_v5.7.2.css">
		<link rel="stylesheet" href="css/index.css">

		<script src="/lib/jquery.min.2.2.4.js"></script>
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="js/perfect-scrollbar-0.3.3/perfect-scrollbar.js"></script>
		<script type="text/javascript" src="js/global.js"></script>
		<script src="../mapa/js/estaciones.js?rndstr=<?php  echo uniqid(); ?>"></script>

		<script>			
			var session = '<?php echo  $_SESSION['sess'];?>';
		</script>
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5yDKoirZb5OXV-0l0OkpC_ZlsfgwEZG8&v=3.exp"></script> <!-- &libraries=places&sensor=false -->
		<script src="js/index.js"></script>
		<script src="js/mapa/mapa.js"></script>
		
	</head>
	<body>
	<!-- <i style="display:none;" class="fas fa-angle-double-right"></i> -->
	<span id="sidebar-in" style="display:none;"><i style="float:left; margin: 20px 5px;" class="fas fa-angle-double-right"></i></span>
	
		<div class="container-fluid">
			<div class="row-fluid main-row">			
				<div class="span5 main-wrapper">
					<div class="row-fluid logo">
						<div class="span12">
							<img src="img/logos/red_hidroclimatologica.jpg" width="70%" />
							<i id="sidebar-out" style="float:right; margin: 10px 10px;" class="fas fa-angle-double-left"></i>							
							
							<hr />							
							<!-- <h1>Red Hidroclimatológica de Risaralda</h1>-->
						</div>
						
						
					</div><!--/logo-->
					<!-- /*height: 57%; overflow: scroll;*/ -->
					<div class="row-fluid block-wrapper" style="margin: -30px 0 0 0;" >
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

								<div align="center">
									<a href="#" id="recursos_boton" onclick="checkSessionClick();" data-section="3" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Recursos Humanos</h3> </a>
								</div>
								
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="7" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Contribuyen a la Red</h3> </a>
								</div>
								
								<div align="center">
									<a href="#" onclick="checkSessionClick();" data-section="10" data-title="" class="floating-box" style="text-decoration: none;"> <h3>Sitios y Documentos de Interés</h3> </a>
								</div>

								<div align="center">
									<a href="#" onclick="checkSessionClick('login');" data-section="9" data-title="" class="floating-box" style="text-decoration: none;"><h3>Acceso a Empresa</h3></a>								
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
															<!--
															<div class="loader"></div>					
															onload="$('.loader').fadeOut('slow');" 
															-->									
															<iframe src="content/estado_del_tiempo.php"  width="100%" height="480px" scrolling="yes" frameBorder="0"><div class="loader"></div></iframe>
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
								<video width="90%" src="img/logos/LOGOSUTP6.mp4" autoplay muted loop>
									Tu navegador no implementa el elemento <code>video</code>.									
								</video>
							</div>
						</div>
					</div><!--/page-footer-->
				</div>
				<!-- end main-wrapper -->

				<div class="span7 span-fixed-sidebar">
					<div class="row-fluid" style="height: 100%;">
						<div class="span12" style="height: 100%;">
							<div id="map_canvas"></div>
							<div class="tcg">Powered by <a href="http://thinkcloudgroup.com" target="_blank" style="color:#1C5EA0; background-color: #fff;">Think Cloud Group</a></div>							
						</div>
					</div>
					<div id="type-selector" class="controls" hidden="hidden">
						      <input type="radio" name="type" id="changetype-all" checked="checked">
						      <label for="changetype-all">All</label>
						      <input type="radio" name="type" id="changetype-establishment">
						      <label for="changetype-establishment">Establishments</label>
						      <input type="radio" name="type" id="changetype-geocode">
						      <label for="changetype-geocode">Geocodes</label>
							</div>

					<div style="font-size: 28px; color: Dodgerblue; position:absolute; top:10px; right:55px; background-color:#FFFFFF; padding: 6px; border-radius: 2px; box-shadow:0px 1px 1px #b6b6b6; -webkit-box-shadow:0px 1px 1px #b6b6b6; -moz-box-shadow:0px 1px 1px #b6b6b6;">
						<!-- style="float:right; margin: 10px 10px;" -->
						<a href="/mapa" target="_blank"><i class="fas fa-map-marked-alt" title="Ver Herramienta de Mapa"></i></a>
					</div>

					<div id="logout"  onclick='logout();' style="font-size: 28px; color: Dodgerblue; position:absolute; top:10px; right:103px; background-color:#FFFFFF; padding: 6px; border-radius: 2px; box-shadow:0px 1px 1px #b6b6b6; -webkit-box-shadow:0px 1px 1px #b6b6b6; -moz-box-shadow:0px 1px 1px #b6b6b6;">
						<a href="#"><i class="fas fa-sign-out-alt" title="Logout"></i></a>
					</div>
					
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