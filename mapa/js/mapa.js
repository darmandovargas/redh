var rectangle;
var map;
var infoWindow;

var htmlraw;

var historicalOverlay;

var overlay, input;

DebugOverlay.prototype = new google.maps.OverlayView();

function initialize(isRefresh, dynamicImage) {

	//console.log( typeof (image));
	// This will paint or not the search (the seach input can´t be obtained by javascript on clean lines)
	if ( typeof (dynamicImage) === 'undefined')
		dynamicImage = 'img/Risaralda_A3_sin.png';//'img/mapa_risaralda.png';
	else
		dynamicImage = dynamicImage;
	// This will paint or not the search (the seach input can´t be obtained by javascript on clean lines)
	if ( typeof (isRefresh) === 'object')
		isRefresh = false;

	var styles = [{
		stylers : [{
			hue : "#0000ff"
		}, {
			saturation : 0
		}]
	}, {
		featureType : "road",
		elementType : "geometry",
		stylers : [{
			lightness : 100
		}, {
			visibility : "simplified"
		}]
	}, {
		featureType : "road",
		elementType : "labels",
		stylers : [{
			visibility : "off"
		}]
	}];

	var mapOptions = {
		zoom : 12,
		center : new google.maps.LatLng(4.814168323, -75.69444586)/*,
		scrollwheel: true,
		navigationControl: false,
		scaleControl: false,
		streetViewControl: false,
		draggable: true,
		mapTypeControl: false,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
		mapTypeId: google.maps.MapTypeId.ROADMAP
		*/
		//styles: styles
		//center: new google.maps.LatLng(-25.363882, 131.044922)
		//center: new google.maps.LatLng(4.81321, -75.6946)
		// Pereira latitud: 4.81321
		// Pereira longitud: -75.6946
	};

	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	

	var northEast = new google.maps.LatLng(5.295278392, -75.490426);
	var southWest = new google.maps.LatLng(4.70917919, -75.78304604);

	var bounds = new google.maps.LatLngBounds(southWest, northEast);
	
	/*
	console.log("dynamicImage");
	console.log(dynamicImage);
	console.log("isRefresh: "+isRefresh);
	console.log("dynamicImage[0].type: "+dynamicImage[0].type);
	*/
	//if(isRefresh && dynamicImage[0].type == "kml"){
		console.log(dynamicImage[0].type);	
		var ctaLayer = new google.maps.KmlLayer({
	    	url: 'uploads/kml.kml' //dynamicImage[0].url
	    });
  		ctaLayer.setMap(map);
  		console.log("after layer");
  //}

	// Search Box
	if (!isRefresh) {
		
		var types = document.getElementById('type-selector');
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);
		var infowindowTool = new google.maps.InfoWindow();
		var markerTool = new google.maps.Marker({
			map : map,
			anchorPoint : new google.maps.Point(0, -29)
		});
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
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
			markerTool.setIcon(/** @type {google.maps.Icon} */( {
				url : place.icon,
				size : new google.maps.Size(71, 71),
				origin : new google.maps.Point(0, 0),
				anchor : new google.maps.Point(17, 34),
				scaledSize : new google.maps.Size(35, 35)
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
			google.maps.event.addDomListener(radioButton, 'click', function() {
				autocomplete.setTypes(types);
			});
		}

		setupClickListener('changetype-all', []);
		setupClickListener('changetype-establishment', ['establishment']);
		setupClickListener('changetype-geocode', ['geocode']);

	}
	console.log("debug1");
	//End Search Box

	// Clima
	var weatherLayer = new google.maps.weather.WeatherLayer({
		temperatureUnits : google.maps.weather.TemperatureUnit.CELSIUS
	});
	weatherLayer.setMap(map);

	var cloudLayer = new google.maps.weather.CloudLayer();
	cloudLayer.setMap(map);
	// End Clima
console.log("debug2");
	// Tools
	var drawingManager = new google.maps.drawing.DrawingManager({
		//drawingMode : google.maps.drawing.OverlayType.MARKER,
		drawingControl : true,
		drawingControlOptions : {
			position : google.maps.ControlPosition.TOP_RIGHT,
			drawingModes : [google.maps.drawing.OverlayType.MARKER, google.maps.drawing.OverlayType.CIRCLE, google.maps.drawing.OverlayType.POLYGON, google.maps.drawing.OverlayType.POLYLINE, google.maps.drawing.OverlayType.RECTANGLE]
		},
		markerOptions : {
			icon : 'img/hidroelectrica.png'
		},
		circleOptions : {
			fillColor : '#ffff00',
			fillOpacity : 1,
			strokeWeight : 5,
			clickable : false,
			editable : true,
			zIndex : 1
		}
	});
	drawingManager.setMap(map);
console.log("debug3");
	// End Tools
	
	// Superposición de Imagenes
	/* Simple working test
	var imageBounds = new google.maps.LatLngBounds(
	new google.maps.LatLng(4.70917919, -75.78304604),
	new google.maps.LatLng(5.295278392, -75.490426));

	historicalOverlay = new google.maps.GroundOverlay(
	'http://localhost/mapa_risaralda.png',
	imageBounds);
	historicalOverlay.setMap(map);
	*/
	//if(dynamicImage!='img/mapa_risaralda.png'){}
	//var bounds = new google.maps.LatLngBounds(southWest, northEast);
	var swBound = southWest;
	var neBound = northEast;

	var srcImage = dynamicImage;

	overlay = new DebugOverlay(bounds, srcImage, map);

	var markerA = new google.maps.Marker({
		position : swBound,
		map : map,
		draggable : true,
		icon : 'img/resize_icon_small.png'
	});

	var markerB = new google.maps.Marker({
		position : neBound,
		map : map,
		draggable : true,
		icon : 'img/resize_icon_small.png'
	});

	google.maps.event.addListener(markerA, 'drag', function() {

		var newPointA = markerA.getPosition();
		var newPointB = markerB.getPosition();
		var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
		overlay.updateBounds(newBounds);
	});

	google.maps.event.addListener(markerA, 'drag', function() {

		var newPointA = markerA.getPosition();
		var newPointB = markerB.getPosition();
		var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
		overlay.updateBounds(newBounds);
	});

	google.maps.event.addListener(markerB, 'drag', function() {

		var newPointA = markerA.getPosition();
		var newPointB = markerB.getPosition();
		var newBounds = new google.maps.LatLngBounds(newPointA, newPointB);
		overlay.updateBounds(newBounds);
	});

	google.maps.event.addListener(markerA, 'dragend', function() {

		var newPointA = markerA.getPosition();
		var newPointB = markerB.getPosition();
		
	});

	google.maps.event.addListener(markerB, 'dragend', function() {
		var newPointA = markerA.getPosition();
		var newPointB = markerB.getPosition();
		
	});
console.log("debug4");
	// Fin Superposición de Imágenes

	/*
	Add KML layer
	var ctaLayer = new google.maps.KmlLayer({
	url: 'http://gmaps-samples.googlecode.com/svn/trunk/ggeoxml/cta.kml'
	//url: 'KML_Samples.kml'
	});
	ctaLayer.setMap(map);
	*/

	/*
	var weatherLayer = new google.maps.weather.WeatherLayer({
	temperatureUnits: google.maps.weather.TemperatureUnit.FAHRENHEIT
	});
	weatherLayer.setMap(map);

	var cloudLayer = new google.maps.weather.CloudLayer();
	cloudLayer.setMap(map);
	*/

	/*
	var polyOptions = {
	strokeColor: '#000000',
	strokeOpacity: 1.0,
	strokeWeight: 3
	};
	poly = new google.maps.Polyline(polyOptions);
	poly.setMap(map);

	// Add a listener for the click event
	google.maps.event.addListener(map, 'click', addLatLng);

	// Define the rectangle and set its editable property to true.
	rectangle = new google.maps.Rectangle({
	bounds: bounds,
	editable: true,
	draggable: true
	});

	rectangle.setMap(map);

	// Add an event listener on the rectangle.
	google.maps.event.addListener(rectangle, 'bounds_changed', showNewRect);
	*/

	// Define an info window on the map.
	infoWindow = new google.maps.InfoWindow();

	map.fitBounds(bounds);
console.log("debug5");
	$.each(estacionesJSON, function(idx, obj) {
		//console.log(obj.coordenadas.latitud + "," + obj.coordenadas.longitud);
		var position = new google.maps.LatLng(obj.coordenadas.latitud, obj.coordenadas.longitud);
		var marker = new google.maps.Marker({
			position : position,
			animation : google.maps.Animation.DROP,
			//icon:'icon.png',
			icon : obj.icono,
			map : map
		});
		google.maps.event.addListener(marker, 'click', toggleBounce);
		marker.setTitle(obj.tipo + ": " + obj.nombre);
		attachSecretMessage(marker, obj.tipo + ": " + obj.nombr + "latitud: " + obj.coordenadas.latitud + "longitud: " + obj.coordenadas.longitud, obj.id, obj.tipo);

		function toggleBounce() {
			if (marker.getAnimation() != null) {
				marker.setAnimation(null);
			} else {
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		}

	});
	console.log("debug6");
}

// Superposición Funciones
function DebugOverlay(bounds, image, map) {

	this.bounds_ = bounds;
	this.image_ = image;
	this.map_ = map;
	this.div_ = null;
	this.setMap(map);
}

DebugOverlay.prototype.onAdd = function() {

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

DebugOverlay.prototype.draw = function() {
	var overlayProjection = this.getProjection();
	var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
	var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());
	var div = this.div_;
	div.style.left = sw.x + 'px';
	div.style.top = ne.y + 'px';
	div.style.width = (ne.x - sw.x) + 'px';
	div.style.height = (sw.y - ne.y) + 'px';
};

DebugOverlay.prototype.updateBounds = function(bounds) {
	this.bounds_ = bounds;
	this.draw();
};

DebugOverlay.prototype.onRemove = function() {
	this.div_.parentNode.removeChild(this.div_);
	this.div_ = null;
};
// Fin Superposición Funciones

// The five markers show a secret message when clicked
// but that message is not within the marker's instance data
function attachSecretMessage(marker, message, id, tipo) {

	var infowindow = new google.maps.InfoWindow({
		//style: 'position: absolute; left: 12px; top: 9px; overflow: auto; width: 250%; height: 412px;',
		//width: '2500px',
		content : '<iframe src="../tabs/tabs.php?id='+id+'&tipo='+tipo+'" height="560px" width="800px"></iframe>'
	});
	//console.log(htmlraw);
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(marker.get('map'), marker);
		$.get({
			url : "../tabs/tabs.php?id="+id+"&tipo="+tipo,
			success : function(data) {

				htmlraw = data;
				//alert(htmlraw)  /// should print the raw test.html
				$("#ta").html(htmlraw);
			}
		});
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
		position : event.latLng,
		title : '#' + path.getLength(),
		map : map
	});
}

google.maps.event.addDomListener(window, 'load', initialize);


/*
function cleanLines() {
	loadImage();
}
*/

function refreshMap() {
	location.reload();
}

function loadImageOrKml(imageInfo) {
	initialize(true,imageInfo);
}


// Upload Image

$( document ).ready(function() {
	
input = /** @type {HTMLInputElement} */(document.getElementById('search'));
   
// Variable to store your files
var files, imageName;

//function loadImage(){
	// Add events
	$('input[type=file]').on('change', prepareUpload);
//}

// Grab the files and set them to our variable
function prepareUpload(event){
	files = event.target.files;
	imageName = files[0].name;
	console.log(files[0].name);
}

$('#uploadForm').on('submit', uploadFiles);
 
// Catch the form submit and upload the files
function uploadFiles(event){
  event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening
 
    // START A LOADING SPINNER HERE
 
    // Create a formdata object and add the files
	var data = new FormData();
	$.each(files, function(key, value)
	{
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
        success: function(data, textStatus, jqXHR)
        {
        	if(typeof data.error === 'undefined')
        	{
        		// Success so call function to process the form
        		submitForm(event, data);
        	}
        	else
        	{
        		// Handle errors here
        		console.log('ERRORS: ' + data.error);
        	}
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	// Handle errors here
        	console.log('ERRORS: ' + textStatus);
        	// STOP LOADING SPINNER
        }
    });
}

function submitForm(event, data)
{
  // Create a jQuery object from the form
	$form = $(event.target);
	
	// Serialize the form data
	var formData = $form.serialize();
	
	// You should sterilise the file names
	$.each(data.files, function(key, value)
	{
		formData = formData + '&filenames[]=' + value;
	});
 
	$.ajax({
		url: 'upload.php',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        success: function(data, textStatus, jqXHR)
        {
        	if(typeof data.error === 'undefined')
        	{
        		// Success so call function to process the form
        		console.log('SUCCESS: ' + data.success);
        		console.log("data");
        		console.log(data);
        		//console.log(imageName);
        		tipo = imageName.split(".");
        		tipo = tipo[1];
        		console.log(tipo);
        		imageInfo = [{'url':'uploads/kml.kml', 'type':'kml'}];
        		console.log("imageName");
        		console.log(imageName);        		
        		loadImageOrKml(imageInfo);
        	}
        	else
        	{
        		imageName="";
        		// Handle errors here
        		console.log('ERRORS: ' + data.error);
        	}
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	// Handle errors here
        	console.log('ERRORS: ' + textStatus);
        },
        complete: function()
        {
        	// STOP LOADING SPINNER
        }
	});
}

 
});

// End Upload Image
