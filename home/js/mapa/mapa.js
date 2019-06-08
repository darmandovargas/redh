var overlay;
var isFirstOne = true;
var isSecondOne = false;
var lastLat = 0;
var lastLong = 0;
var openedStationsWindows = [];
var openedStations = [];
var openedStationsPositions = [];
var map = "";
var lastZoom = 10;

DebugOverlay.prototype = new google.maps.OverlayView();

function initialize() {

	var mapOptions = {
		zoom: 12,
		center: new google.maps.LatLng(4.814168323, -75.69444586)
	};

	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

	var northEast = new google.maps.LatLng(5.295278392, -75.490426);
	var southWest = new google.maps.LatLng(4.70917919, -75.78304604);

	var bounds = new google.maps.LatLngBounds(southWest, northEast);

	var types = document.getElementById('type-selector');
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

	var infowindow = new google.maps.InfoWindow();

	var marker = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(0, -29)
	});

	// Define an info window on the map.
	infoWindow = new google.maps.InfoWindow();

	map.fitBounds(bounds);

	$.each(estacionesJSON, function (idx, obj) {

		if ((obj.isPublic && !session) || session) {
			var position = new google.maps.LatLng(obj.latitud, obj.longitud);
			var marker = new google.maps.Marker({
				position: position,
				animation: google.maps.Animation.DROP,
				icon: "/mapa/img/"+obj.icono,
				map: map
			});

			marker.setTitle(obj.nombre); 
			if (obj.carpeta == "cam") {
				addPopUpCAM(marker, obj.tipo + ": " + obj.nombre + "latitud: " + obj.latitud + "longitud: " + obj.longitud, obj.id, obj.tipo, obj.carpeta, obj.bd);
			} else {
				attachSecretMessage(marker, obj.tipo + ": " + obj.nombr + "latitud: " + obj.latitud + "longitud: " + obj.longitud, obj.id, obj.tipo, obj.nombre, obj.carpeta, obj.bd);
			}
			google.maps.event.addListener(marker, 'click', toggleBounce);
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

//Calcula ruta
function calc_route() {
	var start = $('#search').val();
	var end = $('meta[name="geo.placename"]').attr("content");
	var request = {
		origin: start,
		destination: end,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	$('#directions-panel').fadeOut('slow');
	directionsService.route(request, function (response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
			$('#directions-panel').fadeIn('slow');
		}
	});
}

// Superposición Funciones
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


// The five markers show a secret message when clicked
// but that message is not within the marker's instance data
function attachSecretMessage(marker, message, id, tipo, nombre, carpeta, bd) {
	content = '<div class="scrollFix"><iframe class="infoWindow" src="../tabs/tabs.php?id=' + id + '&tipo=' + tipo + '&carpeta=' + carpeta + '&bd=' + bd + '" height="485px" width="700px"  scrolling="no"></iframe></div>'

	var infowindow = new google.maps.InfoWindow({
		content: content,
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
 * This function allows to perform the position of the marker infow
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
 * Pop up of each station
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
 * This function will do the ajax call to see if there is session
 * @return boolean
 */
function checkSessionClick(url) {
	isSession = false;

	$.ajax({
		type: "POST",
		url: "content/login/validate.php"
	}).done(function (msg) {
		if (msg == "success") {
			session = true;
			showLogout(true);
			if (isMapOutOfDate && url != 'isFirstLoad') {
				initialize();
				isMapOutOfDate = false;
			}
		} else {
			session = false;
			showLogout(false);
		}
	});
	return isSession;
}

$(function () {
	showLogout(session);
	//Click event handler for green button
	$(".btn-custom-green").click(function () {
		window.open("../mapa", '_blank');
	});
});

google.maps.event.addDomListener(window, 'load', initialize);