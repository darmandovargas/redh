var overlay;
var isFirstOne = true;
var isSecondOne = false;
var lastLat = 0;
var lastLong = 0;
var openedStations = [];
var map="";
var lastZoom = 0;

//$(document).ready(function () {

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
			var position = new google.maps.LatLng(obj.coordenadas.latitud, obj.coordenadas.longitud);
			var marker = new google.maps.Marker({
				position: position,
				animation: google.maps.Animation.DROP,
				icon: obj.icono,
				map: map
			});
			
			marker.setTitle(obj.nombre);//obj.tipo + ": " +  
			if (obj.carpeta == "cam") {
				addPopUpCAM(marker, obj.tipo + ": " + obj.nombre + "latitud: " + obj.coordenadas.latitud + "longitud: " + obj.coordenadas.longitud, obj.id, obj.tipo, obj.carpeta, obj.bd);
			} else {
				attachSecretMessage(marker, obj.tipo + ": " + obj.nombr + "latitud: " + obj.coordenadas.latitud + "longitud: " + obj.coordenadas.longitud, obj.id, obj.tipo, obj.nombre, obj.carpeta, obj.bd);
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
			if (openedStations.length > 0) {
				let popToClose = openedStations[0];
				popToClose.close();
				openedStations.push(infowindow);
				openedStations.shift();
			} else {
				openedStations.push(infowindow);
			}
		} else if (isSecondOne) {
			if (openedStations.length > 1) {
				let popToClose = openedStations[0];
				popToClose.close();
				openedStations.push(infowindow);
				openedStations.shift();
			} else {
				openedStations.push(infowindow);
			}
		} else {
			isFirstOne = true;
			let popToClose = openedStations[0];
			popToClose.close();
			openedStations.push(infowindow);
			openedStations.shift();
		}
		
		infowindow.open(marker.get('map'), setPopupPosition(marker));

		$.get({
			url: "../tabs/tabs.php?id=" + id + "&tipo=" + tipo,
			success: function (data) {
				htmlraw = data;
				$("#ta").html(htmlraw);
			}
		});
	});
}

function setPopupPosition(marker) {
	var marker2 = marker;

	if (isFirstOne) {
		marker2 = new google.maps.Marker({
			position: new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng()),
			animation: marker.animation,
			opacity: 0,
			icon: marker.get('icon'),
			map: marker.get('map')
		});
		isFirstOne = false;
		isSecondOne = true;
		lastLat = marker.getPosition().lat();
		lastLong = marker.getPosition().lng();
	} else {
		isSecondOne = false;

		$('#sidebar-out').click();

		marker2 = new google.maps.Marker({
			position: new google.maps.LatLng(lastLat, lastLong + 0.9), //0.5 (0.2*map.getZoom()/10)
			animation: marker.animation,
			opacity: 0,
			icon: marker.get('icon'),
			map: marker.get('map')
		});

		switch (map.getZoom()) {
			case 11: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.45)); break;
			case 12: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.22)); break;
			case 13: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.11)); break;
			case 14: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.055)); break;
			case 15: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.0275)); break;
			case 16: marker2.setPosition(new google.maps.LatLng(lastLat, lastLong + 0.01375)); break;
		}
	}

	map.addListener('zoom_changed', function() {
		// 3 seconds after the center of the map has changed, pan back to the
		// marker.
		window.setTimeout(function() {					
			// Zoom out  
			if(lastZoom > map.getZoom()){
				switch(map.getZoom()){
					case 11: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.45)); break;
					case 12: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.22)); break;
					case 13: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.11)); break;
					case 14: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.055)); break;
					case 15: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.0275)); break;
					case 16: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()+0.01375)); break;
				}
	 	    // Zoom in
			}else if(lastZoom < map.getZoom()){					
				switch(map.getZoom()){
					case 11: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.45)); break;
					case 12: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.22)); break;
					case 13: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.11)); break;
					case 14: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.055)); break;
					case 15: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.0275)); break;
					case 16: openedStations[1].setPosition(new google.maps.LatLng(openedStations[1].getPosition().lat(), openedStations[1].getPosition().lng()-0.01375)); break;
				}					
			}
			lastZoom = map.getZoom();
		}, 1000);	
	});

	return marker2;
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
					//initialize();				
					isMapOutOfDate = false;
				}
			} else {
				session = false;
				showLogout(false);
			}
			//setTimeout("google.maps.event.addDomListener(window, 'load', initialize);",5000);
			
		});

		return isSession;
	}

$(function(){
	//checkSessionClick('firstLoad');
	//showLogout(true);
	//showLogout(false);	
	//Click event handler for green button
	$(".btn-custom-green").click(function () {
		window.open("../mapa", '_blank');
	});
});

google.maps.event.addDomListener(window, 'load', initialize);

//google.maps.event.addDomListener(window, 'load', initialize);