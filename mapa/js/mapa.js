// Global variables
var map, rectangle, infoWindow, htmlraw, historicalOverlay, overlay, input, map_actual, northEast, southWest, bounds, showWeather, showBird = true;

var isFirstOne = true;
var isSecondOne = false;
var lastLat = 0;
var lastLong = 0;
var openedStationsWindows = [];
var openedStations = [];
var openedStationsPositions = [];
var map = "";
var lastZoom = 10;

// This will create the second layer for the uploaded image
DebugOverlay.prototype = new google.maps.OverlayView();

// Initialize custom function for map
function initialize(isRefresh, dynamicImage, estation, loadBird) { //loadWeather, loadBird) {	

	// This will paint or not the search (the seach input can´t be obtained by javascript on clean lines)
	if (typeof (isRefresh) === 'object') {
		isRefresh = false;
	}

	// Map Options
	var mapOptions = {
		zoom: 12,
		center: new google.maps.LatLng(4.814168323, -75.69444586)
	};
	// Set Map options and element to render map
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	// Set nort east and south west limits to render map
	northEast = new google.maps.LatLng(5.295278392, -75.490426);
	southWest = new google.maps.LatLng(4.70917919, -75.78304604);
	// Set limits to the map
	bounds = new google.maps.LatLngBounds(southWest, northEast);
	// Set bounds to the map
	map.fitBounds(bounds);

	// Search Box, this won´t render after upload document because there are errors
	if (!isRefresh) {
		showSearch();
	}
	//End Search Box

	// DEPRECIATED
	// Show Wheather Info
	//showWheather(loadWeather);
	// End Wheather

	// Show Birds Info
	if (typeof loadBird !== undefined && loadBird) {
		showBirds();
	}
	// End Bird

	// Tools
	showTools();
	// End Tools	

	// This will get the image if found
	showImage(dynamicImage);
	// Fin Superposición de Imágenes y kml

	// Show Stations
	showStations(estation);
	// End Show Stations	
}

/**
 * This will show the search box
 */
function showSearch() {
	var types = document.getElementById('type-selector');
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.bindTo('bounds', map);
	var infowindowTool = new google.maps.InfoWindow();
	var markerTool = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(0, -29)
	});
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		infowindowTool.close();
		markerTool.setVisible(false);
		var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}
		// If the place has a geometry, then present it on a map.
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);
			// Why 17? Because it looks good.
		}
		markerTool.setIcon( /** @type {google.maps.Icon} */ ({
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(35, 35)
		}));
		markerTool.setPosition(place.geometry.location);
		markerTool.setVisible(true);

		var address = '';
		if (place.address_components) {
			address = [(place.address_components[0] && place.address_components[0].short_name || ''), (place.address_components[1] && place.address_components[1].short_name || ''), (place.address_components[2] && place.address_components[2].short_name || '')].join(' ');
		}

		infowindowTool.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		infowindowTool.open(map, markerTool);
	});

	// Sets a listener on a radio button to change the filter type on Places
	// Autocomplete.
	function setupClickListener(id, types) {
		var radioButton = document.getElementById(id);
		google.maps.event.addDomListener(radioButton, 'click', function () {
			autocomplete.setTypes(types);
		});
	}
	setupClickListener('changetype-all', []);
	setupClickListener('changetype-establishment', ['establishment']);
	setupClickListener('changetype-geocode', ['geocode']);
}

/**
 * This will render wheather information on the map 
 */
function showWheather(showW) {
	showWeather = showW;
	if (showW) {
		var weatherLayer = new google.maps.weather.WeatherLayer({
			temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS
		});
		weatherLayer.setMap(map);
	}

	var cloudLayer = new google.maps.weather.CloudLayer();
	cloudLayer.setMap(map);
}

/**
 * This will render bird information on the map
 */
function showBirds() {
	var ctaLayer = new google.maps.KmlLayer({
		url: 'http://redhidro.org/mapa/uploads/gallinazos.kml'
	});
	ctaLayer.setMap(map);
}

/**
 * This will show the tools bar on top right of the map
 */
function showTools() {
	var drawingManager = new google.maps.drawing.DrawingManager({
		//drawingMode : google.maps.drawing.OverlayType.MARKER,
		drawingControl: true,
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_RIGHT,
			drawingModes: [google.maps.drawing.OverlayType.MARKER, google.maps.drawing.OverlayType.CIRCLE, google.maps.drawing.OverlayType.POLYGON, google.maps.drawing.OverlayType.POLYLINE, google.maps.drawing.OverlayType.RECTANGLE]
		},
		markerOptions: {
			icon: 'img/hidroelectrica.png'
		},
		circleOptions: {
			fillColor: '',
			fillOpacity: 0.4,
			strokeWeight: 4,
			clickable: false,
			editable: true,
			zIndex: 1
		}
	});
	drawingManager.setMap(map);
}

/**
 * This will show image if the initialize function finds one
 */
function showImage(dynamicImage) {
	/* Working example of a kml image
	Add KML layer
	var ctaLayer = new google.maps.KmlLayer({
	url: 'http://gmaps-samples.googlecode.com/svn/trunk/ggeoxml/cta.kml'
	//url: 'KML_Samples.kml'
	});
	ctaLayer.setMap(map);
	*/
	// TODO Render Kml from upload form
	/*if(isRefresh && dynamicImage[0].type == "kml"){
		var ctaLayer = new google.maps.KmlLayer({
	    	url: 'uploads/kml.kml' //dynamicImage[0].url
	    });
  		ctaLayer.setMap(map);
    }   
    */
	/**
	 * Se añade validación para subir archivo kml
	 * 
	 */
	// TODO Render Kml from upload form
	if ( /*isRefresh &&*/ typeof dynamicImage !== 'undefined' && dynamicImage[0].type == "kml") {
		//typeof data.error === 'undefined'
		var url = dynamicImage[0].url;
		var res = url.replace(/\s/g, "%20");
		var ctaLayer = new google.maps.KmlLayer({
			url: 'http://redhidro.org/mapa/' + res
		});
		ctaLayer.setMap(map);
	} else {
		// TODO add validations for specific extensions .jpeg, .jpg, kml... etc
		if (typeof (dynamicImage) === 'undefined') {
			image = '';
		} else {
			image = dynamicImage[0].url;
		}

		if (image != '') {
			//var bounds = new google.maps.LatLngBounds(southWest, northEast);
			var swBound = southWest;
			var neBound = northEast;

			var srcImage = image;

			overlay = new DebugOverlay(bounds, srcImage, map);

			var markerA = new google.maps.Marker({
				position: swBound,
				map: map,
				draggable: true,
				icon: 'img/resize_icon_small.png'
			});

			var markerB = new google.maps.Marker({
				position: neBound,
				map: map,
				draggable: true,
				icon: 'img/resize_icon_small.png'
			});

			google.maps.event.addListener(markerA, 'drag', function () {

				var newPointA = markerA.getPosition();
				var newPointB = markerB.getPosition();
				var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
				overlay.updateBounds(newBounds);
			});

			google.maps.event.addListener(markerA, 'drag', function () {

				var newPointA = markerA.getPosition();
				var newPointB = markerB.getPosition();
				var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
				overlay.updateBounds(newBounds);
			});

			google.maps.event.addListener(markerB, 'drag', function () {

				var newPointA = markerA.getPosition();
				var newPointB = markerB.getPosition();
				var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
				overlay.updateBounds(newBounds);
			});

			google.maps.event.addListener(markerA, 'dragend', function () {

				var newPointA = markerA.getPosition();
				var newPointB = markerB.getPosition();

			});

			google.maps.event.addListener(markerB, 'dragend', function () {
				var newPointA = markerA.getPosition();
				var newPointB = markerB.getPosition();

			});
		}
	}
}

/**
 * Show stations, only publics if there is no session, or all of them if the user is logged in
 */
function showStations(estation) {
	$.each(estacionesJSON, function (idx, obj) {
		var n = -1;

		if (typeof (estation) === 'undefined') {
			n = 1;
		} else {
			if (estation[1] == "estation") {
				n = estation[0].indexOf(obj.tipo);
			} else {
				$.each(obj.variables, function (idy, variable) {
					//console.log(variable);
					senseVar = estation[0].indexOf(variable);
					if (senseVar > -1) {
						n = 1;
						return false;
					}
				});
			}
		}

		if ((obj.isPublic && !session) || session) {
			if (n !== -1) {
				var position = new google.maps.LatLng(obj.latitud, obj.longitud);
				var marker = new google.maps.Marker({
					position: position,
					animation: google.maps.Animation.DROP,					
					icon: "/mapa/img/"+obj.icono,					
					map: map
				});
				google.maps.event.addListener(marker, 'click', toggleBounce);
				marker.setTitle(obj.tipo + ": " + obj.nombre);

				/*if (obj.carpeta == "cam") {
					addPopUpCAM(marker, obj.tipo + ": " + obj.nombre + "latitud: " + obj.latitud + "longitud: " + obj.longitud, obj.id, obj.tipo, obj.carpeta, obj.bd);
				} else {*/
				addPopUp(marker, obj.tipo + ": " + obj.nombre + "latitud: " + obj.latitud + "longitud: " + obj.longitud, obj.id, obj.tipo, obj.carpeta, obj.bd);
				//}
			} 
		} 

		function toggleBounce() {
			if (marker.getAnimation() != null) {
				marker.setAnimation(null);
			} else {
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		}
	});
}

/**
 * This will show custom positions
 */
function locate_position() {

	var x = $("#coordx").val().trim();
	var y = $("#coordy").val().trim();

	if (x == '' || y == '') {
		$('#text_inf').html("");
		$('#text_inf').html("Debe ingresar las coordenadas X Y para encontrar la ubicación");
		$('#content_inf').show();
		return;
	} else {
		var RegExPatternLatitude = /[0-9]{1,2}[°][0-9]{1,2}['](?:\b[0-9]+(?:\.[0-9]*)?|\.[0-9]+\b)"$/;
		if ((x.match(RegExPatternLatitude)) && (y.match(RegExPatternLatitude))) {

		} else {
			$('#text_inf').html("");
			$('#text_inf').html("Debe ingresar un formato de coordenada valida</br>Ej: 75°41'58.16''");
			$('#content_inf').show();
			return;
		}
	}
	// Obtiene coordenada X
	// Para ésta coordenada x°y'z'' el primer split obtiene ésto array(x,y'z'')    
	var coordenadas = $("#coordx").val().split("°");
	var gradosx = coordenadas[0];
	// este split obtiene array (y,z,)
	var coordenadas2 = coordenadas[1].split("'");
	var minutosx = coordenadas2[0];
	var segundosx = coordenadas[1];

	coordenadas = $("#coordx").val().split("°");
	var gradosx = coordenadas[0];
	// este split obtiene array (y,z,)
	var coordenadas2 = coordenadas[1].split("'");
	var minutosx = coordenadas2[0];
	var segundosx = coordenadas2[1];

	x = parseFloat(gradosx) + parseFloat(minutosx) * 0.0166667 + parseFloat(segundosx) * 0.0166667 * 0.0166667;
	// Obtiene coordenada Y
	coordenadas = $("#coordy").val().split("°");
	var gradosy = coordenadas[0];
	// este split obtiene array (y,z,)
	coordenadas2 = coordenadas[1].split("'");
	var minutosy = coordenadas2[0];
	var segundosy = coordenadas2[1];

	y = (parseFloat(gradosy) + parseFloat(minutosy) * 0.0166667 + parseFloat(segundosy) * 0.0166667 * 0.0166667) * -1;
	
	var position = new google.maps.LatLng(x, y);
	var marker = new google.maps.Marker({
		position: position,
		animation: google.maps.Animation.DROP,
		icon: iconoPos,
		map: map
	});
	marker.setTitle("Latitud: " + x + " Longitud: " + y);
	return;
}

/**
 * Image layer functions
 */
function DebugOverlay(bounds, image, map) {

	this.bounds_ = bounds;
	this.image_ = image;
	this.map_ = map;
	this.div_ = null;
	this.setMap(map);
}

DebugOverlay.prototype.onAdd = function () {

	var div = document.createElement('div');
	div.style.borderStyle = 'none';
	div.style.borderWidth = '0px';
	div.style.position = 'absolute';
	var img = document.createElement('img');
	img.src = this.image_;
	img.style.width = '100%';
	img.style.height = '100%';
	img.style.opacity = '0.5';
	img.style.position = 'absolute';
	div.appendChild(img);
	this.div_ = div;
	var panes = this.getPanes();
	panes.overlayLayer.appendChild(div);
};

DebugOverlay.prototype.draw = function () {
	var overlayProjection = this.getProjection();
	var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
	var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());
	var div = this.div_;
	div.style.left = sw.x + 'px';
	div.style.top = ne.y + 'px';
	div.style.width = (ne.x - sw.x) + 'px';
	div.style.height = (sw.y - ne.y) + 'px';
};

DebugOverlay.prototype.updateBounds = function (bounds) {
	this.bounds_ = bounds;
	this.draw();
};

DebugOverlay.prototype.onRemove = function () {
	this.div_.parentNode.removeChild(this.div_);
	this.div_ = null;
};
// Fin Superposición Funciones

/**
 * Pop up of each station
 */
function addPopUp(marker, message, id, tipo, carpeta, bd) {

	var infowindow = new google.maps.InfoWindow({
		content: '<div class="scrollFix"><iframe src="../tabs/tabs.php?id=' + id + '&tipo=' + tipo + '&carpeta=' + carpeta + '&bd=' + bd + '" height="485px" width="700px"></iframe></div>',
		maxWidth: 605
	});

	google.maps.event.addListener(marker, 'click', function () {
		if (isFirstOne) {
			if (openedStationsWindows.length > 1) {
				let popToClose = openedStationsWindows[0];
				popToClose.close();
				openedStations.shift();
				openedStationsPositions.shift();
				openedStationsWindows.shift();
			}

		} else {
			if (openedStationsWindows.length > 1) {
				let popToClose = openedStationsWindows[0];
				popToClose.close();

				openedStations[0].setOpacity(1);
				openedStations[0].setPosition(openedStationsPositions[0]);
				openedStations.pop();
				openedStationsPositions.pop();
				openedStationsWindows.pop();
			}
		}

		openedStationsWindows.push(infowindow);
		openedStations.push(marker);

		var lat = marker.getPosition().lat();
		var lng = marker.getPosition().lng();
		openedStationsPositions.push(new google.maps.LatLng(lat, lng));

		infowindow.open(marker.get('map'), setPopupPosition(marker));

		$.get({
			url: "../tabs/tabs.php?id=" + id + "&tipo=" + tipo,
			success: function (data) {
				htmlraw = data;
				$("#ta").html(htmlraw);
			}
		});

		google.maps.event.addListener(infowindow, 'closeclick', function () {
			// If the window we are closing is the second one we opened, then return the marker to the initial position and make it visible
			if (openedStationsWindows[0] != undefined && openedStationsWindows[0].content != infowindow.content) {
				if (openedStations[1] != undefined) {
					openedStations[1].setOpacity(1);
					openedStations[1].setPosition(openedStationsPositions[1]);
					isFirstOne = false;
					openedStationsWindows.pop();
					openedStations.pop();
					openedStationsPositions.pop();
				} 				
			} else {
				openedStationsWindows.shift();
				openedStations.shift();
				openedStationsPositions.shift();
				if (openedStationsWindows.length == 0) {
					isFirstOne = true;
				}
			}
		});
	});

}

/**
 * This function allows to perform the comparison of two stations
 * @param {*} marker 
 */
function setPopupPosition(marker) {

	if (isFirstOne) { 
		isFirstOne = false;
		lastLat = marker.getPosition().lat();
		lastLong = marker.getPosition().lng();
	} else {
		isFirstOne = true;

		$('#sidebar-out').click();

		let factor = 0.9;

		if ((map.getZoom() - 10) > 0) {
			for (var i = 0; i < (map.getZoom() - 10); i++) {
				factor = factor / 2;
			}
			marker.setPosition(new google.maps.LatLng(lastLat, lastLong + factor));
		} else if ((map.getZoom() - 10) == 0) {
			marker.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.9));

		}

		marker.setOpacity(0);
		lastZoom = map.getZoom();
	}

	map.addListener('zoom_changed', function () {
		let factor = 0.9;
		if (lastZoom != map.getZoom() && openedStations.length > 0) {

			if (openedStations[1] != undefined) {

				if ((map.getZoom() - 10) > 0) {
					for (var i = 0; i < (map.getZoom() - 10); i++) {
						factor = factor / 2;
					}
					openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng() - factor));
				} else if ((map.getZoom() - 10) == 0) {
					openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng() + factor));

				}				
			}
		}
		lastZoom = map.getZoom();		
	});

	return marker;
}

/**
 * Pop up of CAM Station temporary solution for client
 */
function addPopUpCAM(marker, message, id, tipo, carpeta, bd) {

	var infowindow = new google.maps.InfoWindow({
		content: '<img src="img/imagen-no-disponible.jpg" height="200px" width="200px" ></img>'
	});

	google.maps.event.addListener(marker, 'click', function () {
		infowindow.open(marker.get('map'), marker);
	});
}

/**
 * Reload Map
 */
function refreshMap() {
	location.reload();
}

/**
 * Load image
 * @param Json url and type
 */
function loadImageOrKml(imageInfo) {
	initialize(true, imageInfo);
}

/**
 * Funcion que se llama para filtrar por tipos de estaciones según los checkbox seleccionados 
 */
function filter_estation(isVariable) {
	var checkboxValues = [];
	if (typeof isVariable !== 'undefined') {
		checkboxValues[1] = "variable";
		$('input[name="estation[]"]:checked').each(function () {
			checkboxValues[0] += $(this).val() + " ";
		});
		initialize(true, undefined, checkboxValues);
	} else {
		checkboxValues[1] = "estation";
		$('input[name="estation[]"]:checked').each(function () {
			checkboxValues[0] += $(this).val() + " ";
		});
		initialize(true, undefined, checkboxValues);
	}
}

/**
 * Función para mostrar u ocultar variables o tipos de estaciones como filtro
 */
function showHideVariables() {
	if ($("#estationsOrVariables").is(':checked')) {
		$("#estationsDiv").fadeOut("slow").hide();
		$("#variablesDiv").fadeIn("slow").show();
		filter_estation(true);
	} else {
		$("#estationsDiv").fadeIn("slow").show();
		$("#variablesDiv").fadeOut("slow").hide();
		filter_estation();
	}
}

// This will load the map on window load event
google.maps.event.addDomListener(window, 'load', initialize);

// Upload image
$(document).ready(function () {

	input = document.getElementById('search');

	// Variable to store your files
	var files, imageName;
	// Line to prepare upload of the image
	$('input[type=file]').on('change', prepareUpload);
	// Grab the files and set them to our variable
	function prepareUpload(event) {
		files = event.target.files;
		imageName = files[0].name;		
	}
	// Event submit image form
	$('#uploadForm').on('submit', uploadFiles);
	// Catch the form submit and upload the files

	function uploadFiles(event) {
		$("#msg_loader").show();
		event.stopPropagation();
		// Stop stuff happening
		event.preventDefault();
		// Totally stop stuff happening
		// START A LOADING SPINNER HERE
		var uploadImage = document.getElementById("uploadImage").value;
		if (uploadImage == '') {
			$('#text_inf').html("");
			$('#text_inf').html("Debe seleccionar un archivo para subir");
			$('#content_inf').show();
			$("#msg_loader").hide();
			return;
		}

		// Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function (key, value) {
			data.append(key, value);
		});

		$.ajax({
			url: 'upload.php?files',
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			success: function (data, textStatus, jqXHR) {
				if (typeof data.error === 'undefined') {
					// Success so call function to process the form
					$("#msg_loader").hide();
					submitForm(event, data);
				} else {
					// Handle errors here
					$("#msg_loader").hide();
					$('#text_inf').html("");
					$('#text_inf').html(data.error);
					$('#content_inf').show();
					console.log('ERRORS: ' + data.error);
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
	}

	function submitForm(event, data) {		
		// Create a jQuery object from the form
		$form = $(event.target);

		// Serialize the form data
		var formData = $form.serialize();

		// You should sterilise the file names
		$.each(data.files, function (key, value) {
			formData = formData + '&filenames[]=' + value;
		});

		$.ajax({
			url: 'upload.php',
			type: 'POST',
			data: formData,
			cache: false,
			dataType: 'json',
			success: function (data, textStatus, jqXHR) {
				if (typeof data.error === 'undefined') {
					// Success so call function to process the form					
					tipo = imageName.split(".");
					tipo = tipo[1];					
					imageInfo = [{
						'url': 'uploads/' + imageName,
						'type': tipo
					}];					
					loadImageOrKml(imageInfo);
				} else {
					imageName = "";
					// Handle errors here
					console.log('ERRORS: ' + data.error);
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
			},
			complete: function () {
				// STOP LOADING SPINNER
			}
		});
	}

	// Toogle gallinazos layer
	$(".gallinazos").click(function () {
		initialize(true, undefined, undefined, showBird);
		showBird = !showBird;
	});
});
// End Upload Image

/* Close div content_inf */
function close_content_inf() {
	$('#content_inf').fadeOut(1000);
}