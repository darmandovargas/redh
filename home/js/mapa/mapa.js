var overlay;
var isFirstOne = true;
var isSecondOne = false;
var lastLat = 0;
var lastLong = 0;
var openedStations = [];

DebugOverlay.prototype = new google.maps.OverlayView();

function initialize() {

	var mapOptions = {
		zoom: 12,
		center: new google.maps.LatLng(4.814168323, -75.69444586)
	};

	var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

	var northEast = new google.maps.LatLng(5.295278392, -75.490426);
	var southWest = new google.maps.LatLng(4.70917919, -75.78304604);

	var bounds = new google.maps.LatLngBounds(southWest, northEast);

	// Search Box
	var input = /** @type {HTMLInputElement} */ (document.getElementById('search'));

	var types = document.getElementById('type-selector');
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.bindTo('bounds', map);

	var infowindow = new google.maps.InfoWindow();
	var marker = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(0, -29)
	});

	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		infowindow.close();
		marker.setVisible(false);
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
		marker.setIcon( /** @type {google.maps.Icon} */ ({
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(35, 35)
		}));
		marker.setPosition(place.geometry.location);
		marker.setVisible(true);

		var address = '';
		if (place.address_components) {
			address = [(place.address_components[0] && place.address_components[0].short_name || ''), (place.address_components[1] && place.address_components[1].short_name || ''), (place.address_components[2] && place.address_components[2].short_name || '')].join(' ');
		}

		infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
		infowindow.open(map, marker);
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
	//End Search Box

	// Define an info window on the map.
	infoWindow = new google.maps.InfoWindow();

	map.fitBounds(bounds);

	$.each(estacionesJSON, function (idx, obj) {
		//console.log("session EACH: "+session);	
		//console.log("(obj.isPublic='true' && session==false): "+(obj.isPublic="true" && session==false) );
		//console.log("session==true: "+session==true);
		if ((obj.isPublic && !session) || session) {
			var position = new google.maps.LatLng(obj.coordenadas.latitud, obj.coordenadas.longitud);
			var marker = new google.maps.Marker({
				position: position,
				animation: google.maps.Animation.DROP,
				icon: obj.icono,
				map: map
			});
			google.maps.event.addListener(marker, 'click', toggleBounce);
			marker.setTitle( /*obj.tipo + ": " + */ obj.nombre);
			if (obj.carpeta == "cam") {
				addPopUpCAM(marker, obj.tipo + ": " + obj.nombre + "latitud: " + obj.coordenadas.latitud + "longitud: " + obj.coordenadas.longitud, obj.id, obj.tipo, obj.carpeta, obj.bd);
			} else {
				attachSecretMessage(marker, obj.tipo + ": " + obj.nombr + "latitud: " + obj.coordenadas.latitud + "longitud: " + obj.coordenadas.longitud, obj.id, obj.tipo, obj.nombre, obj.carpeta, obj.bd);
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
			position: new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng() - 0.4),
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

		console.log(marker.getPosition().lng());
		marker2 = new google.maps.Marker({
			position: new google.maps.LatLng(lastLat, lastLong + 0.5),
			animation: marker.animation,
			opacity: 0,
			icon: marker.get('icon'),
			map: marker.get('map')
		});
	}

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


// Show the new coordinates for the rectangle in an info window.

/** @this {google.maps.Rectangle} */
function showNewRect(event) {
	var ne = rectangle.getBounds().getNorthEast();
	var sw = rectangle.getBounds().getSouthWest();

	var contentString = '<b>Rectangle moved.</b><br>' + 'New north-east corner: ' + ne.lat() + ', ' + ne.lng() + '<br>' + 'New south-west corner: ' + sw.lat() + ', ' + sw.lng();

	// Set the info window's content and position.
	infoWindow.setContent(contentString);
	infoWindow.setPosition(ne);

	infoWindow.open(map);
}

/**
 * Handles click events on a map, and adds a new point to the Polyline.
 * @param {google.maps.MouseEvent} event
 */
function addLatLng(event) {

	var path = poly.getPath();

	// Because path is an MVCArray, we can simply append a new coordinate
	// and it will automatically appear.
	path.push(event.latLng);

	// Add a new marker at the new plotted point on the polyline.
	var marker = new google.maps.Marker({
		position: event.latLng,
		title: '#' + path.getLength(),
		map: map
	});
}

$(document).ready(function () {
	checkSessionClick('firstLoad');
	//lookForSession();
	//showLogout(true);
	//showLogout(false);	
	//Click event handler for green button
	$(".btn-custom-green").click(function () {
		window.open("../mapa", '_blank');
	});

});


google.maps.event.addDomListener(window, 'load', initialize);